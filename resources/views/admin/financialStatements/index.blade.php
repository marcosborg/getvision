@extends('layouts.admin')
@section('content')
<div class="content">
    @if ($company_id == 0)
    <div class="alert alert-info" role="alert">
        Selecione uma empresa para ver os seus extratos.
    </div>
    @else
    <div class="btn-group btn-group-justified" role="group">
        @foreach ($tvde_years as $tvde_year)
        <a href="/admin/financial-statements/year/{{ $tvde_year->id }}"
            class="btn btn-default {{ $tvde_year->id == $tvde_year_id ? 'disabled selected' : '' }}">{{ $tvde_year->name
            }}</a>
        @endforeach
    </div>
    <div class="btn-group btn-group-justified" role="group" style="margin-top: 5px;">
        @foreach ($tvde_months as $tvde_month)
        <a href="/admin/financial-statements/month/{{ $tvde_month->id }}"
            class="btn btn-default {{ $tvde_month->id == $tvde_month_id ? 'disabled selected' : '' }}">{{
            $tvde_month->name
            }}</a>
        @endforeach
    </div>
    <div class="btn-group btn-group-justified" role="group" style="margin-top: 5px;">
        @foreach ($tvde_weeks as $tvde_week)
        <a href="/admin/financial-statements/week/{{ $tvde_week->id }}"
            class="btn btn-default {{ $tvde_week->id == $tvde_week_id ? 'disabled selected' : '' }}">Semana de {{
            \Carbon\Carbon::parse($tvde_week->start_date)->format('d')
            }} a {{ \Carbon\Carbon::parse($tvde_week->end_date)->format('d') }}</a>
        @endforeach
    </div>
    @foreach ($drivers as $d)
    <a href="/admin/financial-statements/driver/{{ $d->id }}"
        class="btn btn-default {{ $driver_id == $d->id ? 'disabled selected' : '' }}" style="margin-top: 5px;">{{
        $d->name }} {{ $d->team->count() > 0 ? '(Team)' : '' }}</a>
    @endforeach
    <div class="row" style="margin-top: 5px;">
        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Atividades por operador
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th>UBER</th>
                                <td>{{ number_format($earnings['uber'] ?? 0, 2) }}<small>€</small></td>
                                <td>0%</td>
                                <td>0€</td>
                            </tr>
                            <tr>
                                <th>BOLT</th>
                                <td>{{ number_format($earnings['bolt'] ?? 0, 2) }}<small>€</small></td>
                                <td>0%</td>
                                <td>0€</td>
                            </tr>
                            <tr>
                                <th>Gorjetas</th>
                                <td>{{ number_format($earnings['operators_tips'] ?? 0, 2) }}<small>€</small></td>
                                <td>0%</td>
                                <td>0€</td>
                            </tr>
                            <tr>
                                <th>Totais</th>
                                <td>0€</td>
                                <td></td>
                                <td>0€</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Totais
                </div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th></th>
                                <th style="text-align: right;">Créditos</th>
                                <th style="text-align: right;">Débitos</th>
                                <th style="text-align: right;">Totais</th>
                            </tr>
                            <tr>
                                <th>Ganhos</th>
                                <td>0€</td>
                                <td>0€</td>
                                <td>0€</td>
                            </tr>
                            <tr>
                                <th>Gorjetas</th>
                                <td>0€</td>
                                <td>0€</td>
                                <td>0€</td>
                            </tr>
                            <tr>
                                <th>0</th>
                                <td>0</td>
                                <td>0</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>Totais</th>
                                <th style="text-align: right;">0€</th>
                                <th style="text-align: right;">- 0€</th>
                                <th style="text-align: right;">0€</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3 class="pull-left">Valor a pagar: <span style="font-weight: 800;">0</span>€</h3>
                    <div class="pull-right">
                        <a target="_new" href="/admin/financial-statements/pdf" class="btn btn-primary"><i
                                class="fa fa-file-pdf-o"></i></a>
                        <a href="/admin/financial-statements/pdf/1" class="btn btn-primary"><i
                                class="fa fa-cloud-download"></i></a>
                    </div>
                </div>
                @if (auth()->user()->hasRole('Admin'))
                <div class="panel-footer">
                    <form action="/admin/financial-statements/update-balance" method="post" id="update-balance">
                        @csrf
                        <input type="hidden" name="driver_balance_id" value="">
                        <div class="form-inline">
                            <div class="input-group">
                                <div class="input-group-addon">Saldo (€)</div>
                                <input type="text" class="form-control" value="0"
                                    name="balance">
                            </div>
                            <button type="submit" class="btn btn-success">Atualizar saldo</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
@endsection