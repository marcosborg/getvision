<?php

namespace App\Http\Controllers\Traits;

use App\Models\Adjustment;
use App\Models\CombustionTransaction;
use App\Models\ContractTypeRank;
use App\Models\Driver;
use App\Models\DriversBalance;
use App\Models\ElectricTransaction;
use App\Models\TvdeActivity;
use App\Models\TvdeWeek;
use App\Models\CurrentAccount;
use App\Models\Electric;
use App\Models\Card;
use App\Models\TvdeMonth;
use App\Models\TvdeYear;
use App\Models\CompanyExpense;
use App\Models\CompanyPark;
use App\Models\Consultancy;
use App\Models\Company;
use App\Models\CompanyData;

trait Reports
{
    public function getWeekReport($company_id, $tvde_week_id)
    {

        $tvde_week = TvdeWeek::find($tvde_week_id);

        $drivers = Driver::where('company_id', $company_id)
            ->where([
                'state_id' => 1,
            ])
            ->orderBy('name')
            ->get()
            ->load([
                'contract_vat',
                'card',
                'electric',
                'contract_type.contract_type_ranks',
                'team.drivers',
            ]);

        foreach ($drivers as $driver) {
            $driver = $this->driverWeekReport($tvde_week, $driver, $company_id);
            //VERIFICAÇÃO EQUIPA
            $net_final_team = [];
            if ($driver->team->count() > 0) {
                foreach ($driver->team as $team) {
                    foreach ($team->drivers as $team_driver) {
                        $team_driver = $this->driverWeekReport($tvde_week, $team_driver, $company_id);
                        $company_margin = $team_driver->earnings['net_notip_nobonus'] * 0.1;
                        $operators_tolls_dev = $team_driver->earnings['operators_tolls_dev'];
                        $team_net = $team_driver->earnings['net_notip_nobonus'] - $company_margin - $team_driver->earnings['net_final'] + $operators_tolls_dev;
                        $net_final_team[] = $team_net;
                    }
                }
            }
            $net_final_team = array_sum($net_final_team);
            $driver->earnings['net_final_team'] = $net_final_team;
            $driver->earnings['net_final'] = $driver->earnings['net_final'] + $net_final_team;

            $current_account = CurrentAccount::where([
                'tvde_week_id' => $tvde_week_id,
                'driver_id' => $driver->id
            ])->first();

            $driver->current_account = $current_account;
        }

        return [
            'drivers' => $drivers,
        ];
    }

    private function driverWeekReport($tvde_week, $driver, $company_id)
    {
        $uber_activities = TvdeActivity::where([
            'company_id' => $company_id,
            'tvde_operator_id' => 1,
            'tvde_week_id' => $tvde_week->id,
            'driver_code' => $driver->uber_uuid
        ])
            ->get();

        $bolt_activities = TvdeActivity::where([
            'company_id' => $company_id,
            'tvde_operator_id' => 2,
            'tvde_week_id' => $tvde_week->id,
            'driver_code' => $driver->bolt_name
        ])
            ->get();

        $adjustments = Adjustment::whereHas('drivers', function ($query) use ($driver) {
            $query->where('id', $driver->id);
        })
            ->where('company_id', $company_id)
            ->where(function ($query) use ($tvde_week) {
                $query->where('start_date', '<=', $tvde_week->start_date)
                    ->orWhereNull('start_date');
            })
            ->where(function ($query) use ($tvde_week) {
                $query->where('end_date', '>=', $tvde_week->end_date)
                    ->orWhereNull('end_date');
            })
            ->get();

        $refunds = [];
        $deducts = [];

        foreach ($adjustments as $adjustment) {
            if ($adjustment->type == 'deduct') {
                $deducts[] = $adjustment->amount;
            } else {
                $refunds[] = $adjustment->amount;
            }
        }

        $refunds = array_sum($refunds);
        $deducts = array_sum($deducts);
        $adjustments = $refunds - $deducts;

        $uber_gross = $uber_activities->sum('earnings_two');
        $tips_uber = $uber_activities->sum('earnings_one');
        $tolls_dev_uber = $uber_activities->sum('tolls');
        $parks_dev_uber = $uber_activities->sum('parks');
        $bonus_dev_uber = $uber_activities->sum('bonus');
        $bolt_gross = $bolt_activities->sum('earnings_two');
        $tips_bolt = $bolt_activities->sum('earnings_one');
        $tolls_dev_bolt = $bolt_activities->sum('tolls');
        $parks_dev_bolt = $bolt_activities->sum('parks');
        $bonus_dev_bolt = $bolt_activities->sum('bonus');
        $operators_gross = $uber_gross + $bolt_gross;
        $operators_tips = $tips_uber + $tips_bolt;
        $operators_parks_dev = $parks_dev_uber + $parks_dev_bolt;
        $operators_tolls_dev = $tolls_dev_uber + $tolls_dev_bolt + $operators_parks_dev;
        $operators_bonus_dev = $bonus_dev_uber + $bonus_dev_bolt;

        $gross_notip_nobonus = $operators_gross - $operators_tips - $operators_bonus_dev;
        $net_notip_nobonus = $gross_notip_nobonus * 0.94;
        $net_tip_bonus = ($operators_tips + $operators_bonus_dev) * 0.94;
        $percent = $driver->contract_type->contract_type_ranks[0]->percent;
        $net_notip_nobonus_after_contract = $net_notip_nobonus * ($percent / 100);

        $combustion_transactions = CombustionTransaction::where([
            'card' => $driver->card->code ?? '',
            'tvde_week_id' => $tvde_week->id
        ])->sum('total');

        $electric_transactions = ElectricTransaction::where([
            'card' => $driver->electric->code ?? '',
            'tvde_week_id' => $tvde_week->id
        ])->sum('total');

        $fuel_expenses = $combustion_transactions + $electric_transactions;

        switch ($driver->contract_type->id) {
            case 19:
                $net_final = $net_notip_nobonus_after_contract + $net_tip_bonus + $adjustments - $fuel_expenses;
                break;
            case 21:
                $net_final = $net_notip_nobonus_after_contract + $net_tip_bonus + $adjustments + $operators_tolls_dev - $fuel_expenses;
                break;
            case 22:
                $net_final = $net_notip_nobonus_after_contract + $net_tip_bonus + $adjustments - $fuel_expenses;
                break;
            default:
                $net_final = $net_notip_nobonus_after_contract + $net_tip_bonus + $adjustments + $operators_tolls_dev - $fuel_expenses;
                break;
        }

        $driver->earnings = collect([
            'uber' => $uber_gross,
            'bolt' => $bolt_gross,
            'operators_gross' => $operators_gross,
            'operators_tips' => $operators_tips,
            'net_notip_nobonus_after_contract' => $net_notip_nobonus_after_contract,
            'operators_tolls_dev' => $operators_tolls_dev,
            'operators_bonus_dev' => $operators_bonus_dev,
            'adjustments' => $adjustments,
            'net_notip_nobonus' => $net_notip_nobonus,
            'net_tip_bonus' => $net_tip_bonus,
            'net_final' => $net_final,
            'fuel_expenses' => $fuel_expenses
        ]);

        return $driver;
    }

    public function filter()
    {
        $company_id = session()->get('company_id') ?? $company_id = session()->get('company_id');
        $tvde_year_id = session()->get('tvde_year_id') ? session()->get('tvde_year_id') : $tvde_year_id = TvdeYear::orderBy('name', 'desc')->first()->id;
        if (session()->has('tvde_month_id')) {
            $tvde_month_id = session()->get('tvde_month_id');
        } else {
            $tvde_month = TvdeMonth::orderBy('number', 'desc')
                ->whereHas('weeks', function ($week) use ($company_id) {
                    $week->whereHas('tvdeActivities', function ($tvdeActivity) use ($company_id) {
                        $tvdeActivity->where('company_id', $company_id);
                    });
                })
                ->where('year_id', $tvde_year_id)
                ->first();
            if ($tvde_month) {
                $tvde_month_id = $tvde_month->id;
            } else {
                $tvde_month_id = 0;
            }
        }
        if (session()->has('tvde_week_id')) {
            $tvde_week_id = session()->get('tvde_week_id');
        } else {
            $tvde_week = TvdeWeek::has('tvdeActivities')
                ->orderBy('number', 'desc')
                ->where('tvde_month_id', $tvde_month_id)
                ->first();
            if ($tvde_week) {
                $tvde_week_id = $tvde_week->id;
                session()->put('tvde_week_id', $tvde_week->id);
            } else {
                $tvde_week_id = 1;
            }
        }

        $tvde_years = TvdeYear::orderBy('name')
            ->whereHas('months', function ($month) use ($company_id) {
                $month->whereHas('weeks', function ($week) use ($company_id) {
                    $week->whereHas('tvdeActivities', function ($tvdeActivity) use ($company_id) {
                        $tvdeActivity->where('company_id', $company_id);
                    });
                });
            })
            ->get();
        $tvde_months = TvdeMonth::orderBy('number', 'asc')
            ->whereHas('weeks', function ($week) use ($company_id) {
                $week->whereHas('tvdeActivities', function ($tvdeActivity) use ($company_id) {
                    $tvdeActivity->where('company_id', $company_id);
                });
            })
            ->where('year_id', $tvde_year_id)->get();

        $tvde_weeks = TvdeWeek::orderBy('number', 'asc')
            ->whereHas('tvdeActivities', function ($tvdeActivity) use ($company_id) {
                $tvdeActivity->where('company_id', $company_id);
            })
            ->where('tvde_month_id', $tvde_month_id)->get();

        $tvde_week = TvdeWeek::find($tvde_week_id);

        $drivers = Driver::where('company_id', $company_id)->where('state_id', 1)->orderBy('name')->get()->load('team');

        return [
            'company_id' => $company_id,
            'tvde_year_id' => $tvde_year_id,
            'tvde_years' => $tvde_years,
            'tvde_week_id' => $tvde_week_id,
            'tvde_week' => $tvde_week,
            'tvde_months' => $tvde_months,
            'tvde_month_id' => $tvde_month_id,
            'tvde_weeks' => $tvde_weeks,
            'drivers' => $drivers,
        ];
    }

    public function saveCompanyExpenses($company_id, $tvde_week_id)
    {
        $tvde_week = TvdeWeek::find($tvde_week_id);

        $company_expenses = CompanyExpense::where([
            'company_id' => $company_id,
        ])
            ->where('start_date', '<=', $tvde_week->start_date)
            ->where('end_date', '>=', $tvde_week->end_date)
            ->get();

        $company_expenses = $company_expenses->map(function ($expense) {
            $expense->total = $expense->qty * $expense->weekly_value;
            return $expense;
        });

        $total_company_expenses = [];

        foreach ($company_expenses as $company_expense) {
            $total_company_expenses[] = $company_expense->total;
        }

        $total_company_expenses = array_sum($total_company_expenses);

        $company_park = CompanyPark::where('tvde_week_id', $tvde_week_id)
            ->where('company_id', $company_id)
            ->sum('value');

        $tvde_week = TvdeWeek::find($tvde_week_id);

        $consultancy = Consultancy::where('company_id', $company_id)
            ->where('start_date', '<=', $tvde_week->start_date)
            ->where('end_date', '>=', $tvde_week->end_date)
            ->first();

        $totals = $this->getWeekReport($company_id, $tvde_week_id)['totals'];

        $company = Company::find($company_id);

        $total_consultancy = 0;

        if ($consultancy && !$company->main) {

            $total_consultancy = ($totals['total_operators'] * $consultancy->value) / 100;
        }

        //GET EARNINGS FROM OTHER COMPANIES

        $fleet_adjusments = 0;
        $fleet_consultancies = 0;
        $fleet_company_parks = 0;
        $fleet_earnings = 0;

        if ($company && $company->main) {

            $current_accounts = CurrentAccount::where([
                'tvde_week_id' => $tvde_week_id
            ])->get();

            $fleet_adjustments = [];

            foreach ($current_accounts as $current_account) {
                $data = json_decode($current_account->data);
                foreach ($data->adjustments as $fleet_adjustment) {
                    if ($fleet_adjustment->fleet_management == true) {
                        if ($fleet_adjustment->type == 'refund') {
                            $fleet_adjustments[] = (-$fleet_adjustment->amount);
                        } else {
                            $fleet_adjustments[] = $fleet_adjustment->amount;
                        }
                    }
                }
            }

            $fleet_adjusments = array_sum($fleet_adjustments);

            $companies = Company::whereHas('tvde_activities', function ($tvde_activity) use ($tvde_week_id) {
                $tvde_activity->where('tvde_week_id', $tvde_week_id);
            })
                ->get();

            $fleet_consultancies = [];

            foreach ($companies as $company) {
                $fleet_consultancy = Consultancy::where('company_id', $company->id)
                    ->where('start_date', '<=', $tvde_week->start_date)
                    ->where('end_date', '>=', $tvde_week->end_date)
                    ->first();
                $earnings = TvdeActivity::where([
                    'company_id' => $company->id,
                    'tvde_week_id' => $tvde_week_id,
                ])
                    ->sum('earnings_two');

                if ($fleet_consultancy && $fleet_consultancy->value && $earnings) {
                    $fleet_consultancies[] = ($earnings * $fleet_consultancy->value) / 100;
                }
            }

            $fleet_consultancies = array_sum($fleet_consultancies);

            $fleet_company_parks = CompanyPark::where([
                'tvde_week_id' => $tvde_week->id,
                'fleet_management' => true
            ])->sum('value');

            $fleet_earnings = $fleet_adjusments + $fleet_consultancies + $fleet_company_parks;
        }

        ////////////////////////////////

        $final_total = $total_company_expenses - $totals['total_company_adjustments'] + $company_park + $totals['total_drivers'] + $total_consultancy;

        //$final_total = $totals['total_company_adjustments'];

        $final_company_expenses = $total_company_expenses - $totals['total_company_adjustments'] + $company_park - $total_consultancy;

        $profit = $totals['total_operators'] - $final_total + $fleet_earnings;

        if ($totals['total_operators'] > 0) {
            $roi = ($profit / ($totals['total_operators'] + $fleet_earnings)) * 100;
        } else {
            $roi = 0;
        }

        $data = [
            'company_expenses' => $company_expenses,
            'total_company_expenses' => $total_company_expenses,
            'totals' => $totals,
            'company_park' => $company_park,
            'final_total' => $final_total,
            'final_company_expenses' => $final_company_expenses,
            'profit' => $profit,
            'roi' => $roi,
            'total_consultancy' => $total_consultancy,
            'fleet_adjusments' => $fleet_adjusments,
            'fleet_consultancies' => $fleet_consultancies,
            'fleet_company_parks' => $fleet_company_parks,
            'fleet_earnings' => $fleet_earnings
        ];

        $company_data = new CompanyData;
        $company_data->company_id = $company_id;
        $company_data->tvde_week_id = $tvde_week_id;
        $company_data->data = json_encode($data);
        $company_data->save();
    }
}
