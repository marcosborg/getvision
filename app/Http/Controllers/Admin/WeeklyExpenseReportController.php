<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\Reports;
use App\Models\TvdeWeek;
use App\Models\Company;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\CompanyData;

class WeeklyExpenseReportController extends Controller
{

    use Reports;

    public function index()
    {
        abort_if(Gate::denies('weekly_expense_report_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (auth()->user()->hasRole('Empresas Associadas')) {
            $user = auth()->user()->load('company');
            $company_id = $user->company->id;
            session()->put('company_id', $company_id);
        }

        $filter = $this->filter();
        $company_id = $filter['company_id'];
        $tvde_week_id = $filter['tvde_week_id'];
        $tvde_years = $filter['tvde_years'];
        $tvde_year_id = $filter['tvde_year_id'];
        $tvde_months = $filter['tvde_months'];
        $tvde_month_id = $filter['tvde_month_id'];
        $tvde_weeks = $filter['tvde_weeks'];

        //COMPANY EXPENSES

        $company_data = CompanyData::where([
            'company_id' => $company_id,
            'tvde_week_id' => $tvde_week_id
        ])->first();

        if ($company_data) {
            $data = json_decode($company_data->data);
        } else {
            $this->saveCompanyExpenses($company_id, $tvde_week_id);
            return redirect(url()->current());
        }

        return view('admin.weeklyExpenseReports.index')->with([
            'company_id' => $company_id,
            'tvde_years' => $tvde_years,
            'tvde_year_id' => $tvde_year_id,
            'tvde_months' => $tvde_months,
            'tvde_month_id' => $tvde_month_id,
            'tvde_weeks' => $tvde_weeks,
            'tvde_week_id' => $tvde_week_id,
            'company_expenses' => $data->company_expenses,
            'total_company_expenses' => $data->total_company_expenses,
            'totals' => $data->totals,
            'company_park' => $data->company_park,
            'final_total' => $data->final_total,
            'final_company_expenses' => $data->final_company_expenses,
            'profit' => $data->profit,
            'roi' => $data->roi,
            'total_consultancy' => $data->total_consultancy,
            'fleet_adjusments' => $data->fleet_adjusments,
            'fleet_consultancies' => $data->fleet_consultancies,
            'fleet_company_parks' => $data->fleet_company_parks,
            'fleet_earnings' => $data->fleet_earnings,
            'total_company_adjustments' => $data->totals->total_company_adjustments,
        ]);
    }

    public function pdf(Request $request)
    {

        $company_id = session()->get('company_id') ?? $company_id = session()->get('company_id');
        $tvde_week_id = session()->get('tvde_week_id');

        //COMPANY EXPENSES

        $company_data = CompanyData::where([
            'company_id' => $company_id,
            'tvde_week_id' => $tvde_week_id
        ])->first();

        if ($company_data) {
            $data = json_decode($company_data->data);
        } else {
            $this->saveCompanyExpenses($company_id, $tvde_week_id);
            return redirect(url()->current());
        }

        $company = Company::find($company_id);
        $tvde_week = TvdeWeek::find($tvde_week_id);
        $main_company = Company::where('main', true)->first();

        //GRÁFICOS
        $chart1 = 'https://quickchart.io/chart/render/zm-8d666b59-11bf-49f1-8455-a264899a3611?data1=' . round($data->totals->total_operators) . ',' . round($data->final_total) . ',' . round($data->profit) . '';
        //$chart2 = 'https://quickchart.io/chart/render/sf-9970a21b-53f3-4f4f-b4e9-ea355f70f7ab?data1=' . round($total_company_expenses) . ',' . round(-$totals['total_company_adjustments']) . ',' . round($company_park) . ',' . round($total_consultancy) . ',' . round($totals['total_drivers']) . '';
        //

        /*

        return view('admin.weeklyExpenseReports.pdf')->with([
            'company_id' => $company_id,
            'tvde_week_id' => $tvde_week_id,
            'company_expenses' => $data->company_expenses,
            'totals' => $data->totals,
            'company_park' => $data->company_park,
            'final_total' => $data->final_total,
            'final_company_expenses' => $data->final_company_expenses,
            'profit' => $data->profit,
            'roi' => $data->roi,
            'total_consultancy' => $data->total_consultancy,
            'main_company' => $main_company,
            'company' => $company,
            'tvde_week' => $tvde_week,
            'chart1' => $chart1,
            'fleet_adjusments' => $data->fleet_adjusments,
            'fleet_consultancies' => $data->fleet_consultancies,
            'fleet_company_parks' => $data->fleet_company_parks,
            'fleet_earnings' => $data->fleet_earnings,
            'total_company_adjustments' => $data->totals->total_company_adjustments,
        ]);

        */

        $pdf = Pdf::loadView('admin.weeklyExpenseReports.pdf', [
            'company_id' => $company_id,
            'tvde_week_id' => $tvde_week_id,
            'company_expenses' => $data->company_expenses,
            'totals' => $data->totals,
            'company_park' => $data->company_park,
            'final_total' => $data->final_total,
            'final_company_expenses' => $data->final_company_expenses,
            'profit' => $data->profit,
            'roi' => $data->roi,
            'total_consultancy' => $data->total_consultancy,
            'main_company' => $main_company,
            'company' => $company,
            'tvde_week' => $tvde_week,
            'chart1' => $chart1,
            'fleet_adjusments' => $data->fleet_adjusments,
            'fleet_consultancies' => $data->fleet_consultancies,
            'fleet_company_parks' => $data->fleet_company_parks,
            'fleet_earnings' => $data->fleet_earnings,
            'total_company_adjustments' => $data->totals->total_company_adjustments,
        ])->setOption([
            'isRemoteEnabled' => true,
        ]);


        if ($request->download) {

            $filename = strtolower(str_replace(' ', '_', preg_replace('/[^A-Za-z0-9\-]/', '', $company->name . '-' . $tvde_week->start_date))) . '.pdf';

            return $pdf->download($filename);
        } else {
            return $pdf->stream();
        }
    }

    public function update()
    {
        CompanyData::where([
            'company_id' => session()->get('company_id'),
            'tvde_week_id' => session()->get('tvde_week_id')
        ])->delete();

        return redirect()->back()->with('message', 'Atualizado com sucesso');
    }

    public function getData(Request $request)
    {
        $tvde_weeks = TvdeWeek::where(function ($query) use ($request) {
            $query->where('start_date', '<=', $request->end_date)
                ->where('end_date', '>=', $request->start_date);
        })
            ->get();

        $total_operators = [];
        $final_total = [];
        $profit = [];
        $roi = [];

        foreach ($tvde_weeks as $tvde_week) {
            $company_data = CompanyData::where([
                'company_id' => session()->get('company_id'),
                'tvde_week_id' => $tvde_week->id
            ])->first();
            if ($company_data) {
                $data = json_decode($company_data->data);
                $total_operators[] = $data->totals->total_operators;
                $final_total[] = $data->final_total;
                $profit[] = $data->profit;
                $roi[] = $data->roi;
            }
        }

        $html = '<h3>Totais</h3>';
        $html .= '<table style="width: 100%">';
        $html .= '<tbody>';
        $html .= '<tr>';
        $html .= '<th>Ganhos</th>';
        $html .= '<td style="text-align: right">';
        $html .= number_format(array_sum($total_operators), 2, '.');
        $html .= '<small>€</small>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<th>Total de despesas</th>';
        $html .= '<td style="text-align: right">';
        $html .= number_format(array_sum($final_total), 2, '.');
        $html .= '<small>€</small></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<th>Rentabilidade</th>';
        $html .= '<td style="text-align: right">';
        $html .= number_format(array_sum($profit), 2, '.');
        $html .= '<small>€</small></td>';
        $html .= '</tr>';
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '<h3>Médias</h3>';
        $html .= '<table style="width: 100%">';
        $html .= '<tbody>';
        $html .= '<tr>';
        $html .= '<th>Ganhos</th>';
        $html .= '<td style="text-align: right">';
        $html .= number_format(array_sum($total_operators) / $tvde_weeks->count(), 2, '.');
        $html .= '<small>€</small>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<th>Total de despesas</th>';
        $html .= '<td style="text-align: right">';
        $html .= number_format(array_sum($final_total) / $tvde_weeks->count(), 2, '.');
        $html .= '<small>€</small></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<th>Rentabilidade</th>';
        $html .= '<td style="text-align: right">';
        $html .= number_format(array_sum($profit) / $tvde_weeks->count(), 2, '.');
        $html .= '<small>€</small></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<th>ROI (Return of investment)</th>';
        $html .= '<td style="text-align: right">';
        $html .= '<h1>';
        $html .= round(array_sum($roi) / $tvde_weeks->count());
        $html .= '<small>%</small></h1>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</tbody>';
        $html .= '</table>';

        return $html;

    }
}
