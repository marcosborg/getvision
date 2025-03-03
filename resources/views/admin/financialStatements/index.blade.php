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
        <a href="/admin/financial-statements/year/{{ $tvde_year->id }}" class="btn btn-default {{ $tvde_year->id == $tvde_year_id ? 'disabled selected' : '' }}">{{ $tvde_year->name
            }}</a>
        @endforeach
    </div>
    <div class="btn-group btn-group-justified" role="group" style="margin-top: 5px;">
        @foreach ($tvde_months as $tvde_month)
        <a href="/admin/financial-statements/month/{{ $tvde_month->id }}" class="btn btn-default {{ $tvde_month->id == $tvde_month_id ? 'disabled selected' : '' }}">{{
            $tvde_month->name
            }}</a>
        @endforeach
    </div>
    <div class="btn-group btn-group-justified" role="group" style="margin-top: 5px;">
        @foreach ($tvde_weeks as $tvde_week)
        <a href="/admin/financial-statements/week/{{ $tvde_week->id }}" class="btn btn-default {{ $tvde_week->id == $tvde_week_id ? 'disabled selected' : '' }}">Semana de {{
            \Carbon\Carbon::parse($tvde_week->start_date)->format('d')
            }} a {{ \Carbon\Carbon::parse($tvde_week->end_date)->format('d') }}</a>
        @endforeach
    </div>
    @foreach ($drivers as $d)
    <a href="/admin/financial-statements/driver/{{ $d->id }}" class="btn btn-default {{ $driver_id == $d->id ? 'disabled selected' : '' }}" style="margin-top: 5px;">{{
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
                                <td style="text-align: right;">{{ number_format($earnings->earnings->uber ?? 0, 2) }}<small>€</small></td>
                            </tr>
                            <tr>
                                <th>BOLT</th>
                                <td style="text-align: right;">{{ number_format($earnings->earnings->bolt ?? 0, 2) }}<small>€</small></td>
                            </tr>
                            <tr>
                                <th>Operadores</th>
                                <td style="text-align: right;">{{ number_format($earnings->earnings->operators_gross ?? 0, 2) }}€</td>
                            </tr>
                            <tr>
                                <th>Ganhos <small>(Depois dos descontos)</small></th>
                                <td style="text-align: right;">{{ number_format($earnings->earnings->net_notip_nobonus_after_contract ?? 0, 2) }}€</td>
                            </tr>
                            <tr>
                                <th>Gorjetas</th>
                                <td style="text-align: right;">{{ number_format($earnings->earnings->operators_tips ?? 0, 2) }}€</td>
                            </tr>
                            <tr>
                                <th>Bonus</th>
                                <td style="text-align: right;">{{ number_format($earnings->earnings->operators_bonus_dev ?? 0, 2) }}€</td>
                            </tr>
                            <tr>
                                <th>Gorjetas e bonus <small>(Depois dos descontos)</small></th>
                                <td style="text-align: right;">{{ number_format($earnings->earnings->net_tip_bonus ?? 0, 2) }}€</td>
                            </tr>
                            <tr>
                                <th>Totais <small>(Depois dos descontos)</small></th>
                                <td style="text-align: right;">{{ number_format($earnings->earnings->operators_final ?? 0, 2) }}€</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Origem dos ganhos
                </div>
                <div class="panel-body">
                    <canvas id="driver_earnings" style="height: 400px"></canvas>
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
                            </tr>
                            <tr>
                                <th>Ganhos</th>
                                <td style="text-align: right;">{{ number_format($earnings->earnings->net_notip_nobonus_after_contract ?? 0, 2) }}€</td>
                                <td style="text-align: right;"></td>
                            </tr>
                            <tr>
                                <th>Gorjetas e bonus</th>
                                <td style="text-align: right;">{{ number_format($earnings->earnings->net_tip_bonus ?? 0, 2) }}€</td>
                                <td style="text-align: right;"></td>
                            </tr>
                            <tr>
                                <th>Ajustes</th>
                                @if ($earnings->earnings->adjustments >= 0)
                                <td style="text-align: right;">{{ number_format($earnings->earnings->adjustments ?? 0, 2) }}€</td>
                                <td style="text-align: right;"></td>
                                @else
                                <td style="text-align: right;"></td>
                                <td style="text-align: right;">{{ number_format($earnings->earnings->adjustments ?? 0, 2) }}€</td>
                                @endif
                            </tr>
                            <tr>
                                <th>Abastecimento</th>
                                <td style="text-align: right;"></td>
                                <td style="text-align: right;">-{{ number_format($earnings->earnings->fuel_expenses ?? 0, 2) }}€</td>
                            </tr>
                            <tr>
                                <th>Devolução de portagens</th>
                                <td style="text-align: right;">{{ number_format($earnings->earnings->operators_tolls_dev ?? 0, 2) }}€</td>
                                <td style="text-align: right;"></td>
                            </tr>
                            <tr>
                                <th>Equipa</th>
                                <td style="text-align: right;">{{ number_format($earnings->earnings->net_final_team ?? 0, 2) }}€</td>
                                <td style="text-align: right;"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3 class="pull-left">Valor a pagar: <span style="font-weight: 800;">{{ number_format($earnings->earnings->net_final ?? 0, 2) }}</span>€</h3>
                    <div class="pull-right">
                        <a target="_new" href="/admin/financial-statements/pdf" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i></a>
                        <a href="/admin/financial-statements/pdf/1" class="btn btn-primary"><i class="fa fa-cloud-download"></i></a>
                    </div>
                </div>
                @if (auth()->user()->hasRole('Admin'))
                <div class="panel-footer">
                    <form action="/admin/financial-statements/update-balance" method="post" id="update-balance">
                        @csrf
                        <input type="hidden" name="driver_balance_id" value="{{ $drivers_balance->id }}">
                        <div class="form-inline">
                            <div class="input-group">
                                <div class="input-group-addon">Saldo (€)</div>
                                <input type="text" class="form-control" value="{{ $drivers_balance->drivers_balance }}" name="balance">
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
</div>
@endsection
@section('scripts')
@parent
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx2 = document.getElementById('driver_earnings');
    new Chart(ctx2, {
      type: 'doughnut',
      data: {
        labels: ['UBER', 'BOLT', 'GORJETAS E BONUS'],
        datasets: [{
          label: 'Valor faturado',
          data: [{{ $earnings->earnings->uber }}, {{ $earnings->earnings->bolt }}, {{ $earnings->earnings->net_tip_bonus }}],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
      }
    });
</script>
<script src="https://malsup.github.io/jquery.form.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js">
</script>
<script>
    $(() => {
        $('#update-balance').ajaxForm({
            beforeSubmit: () => {
                $('#update-balance').LoadingOverlay('show');
            },
            success: () => {
                $('#update-balance').LoadingOverlay('hide');
                Swal.fire({
                    title: 'Atualizado com sucesso',
                    icon: 'success',
                }).then(() => {
                    location.reload();
                });
            },
            error: (error) => {
                $('#update-balance').LoadingOverlay('hide');
                var html = '';
                $.each(error.responseJSON.errors, (i, v) => {
                    $.each(v, (index, value) => {
                        html += value + '<br>'
                    });
                });
                Swal.fire({
                    title: 'Erro de validação',
                    html: html,
                    icon: 'error',
                }).then(() => {
                    location.reload();
                });
            }
        });
    });
</script>
@endsection
<script>console.log({!! json_encode($earnings) !!})</script>