@extends('layouts.admin')
@section('content')
<div class="content">
    @can('tvde_activity_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.tvde-activities.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.tvdeActivity.title_singular') }}
                </a>
                <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', ['model' => 'TvdeActivity', 'route' => 'admin.tvde-activities.parseCsvImport'])
                <form action="/admin/tvde-activities/delete-filter" method="post" style="margin-top: 10px;">
                @csrf
                <select name="week_filter" class="select2" style="max-width: 200px;">
                    <option selected disabled>Semana</option>
                    @foreach ($tvde_weeks as $tvde_week)
                    <option value="{{ $tvde_week->id }}">{{ $tvde_week->start_date }}</option>
                    @endforeach
                </select>
                <select name="company_filter" class="select2" style="max-width: 200px;">
                    <option selected disabled>Empresa</option>
                    @foreach ($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                    @endforeach
                </select>
                <button onclick="return confirm('Tem certeza que deseja eliminar os dados do filtro?')" class="btn btn-danger" data-toggle="modal" type="submit">
                    Eliminar seleção de filtro
                </button>
                </form>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.tvdeActivity.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-TvdeActivity">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>
                                    {{ trans('cruds.tvdeActivity.fields.id') }}
                                </th>
                                <th>
                                    {{ trans('cruds.tvdeActivity.fields.tvde_week') }}
                                </th>
                                <th>
                                    {{ trans('cruds.tvdeActivity.fields.tvde_operator') }}
                                </th>
                                <th>
                                    {{ trans('cruds.tvdeActivity.fields.company') }}
                                </th>
                                <th>
                                    {{ trans('cruds.tvdeActivity.fields.driver_code') }}
                                </th>
                                <th>
                                    {{ trans('cruds.tvdeActivity.fields.earnings_one') }}
                                </th>
                                <th>
                                    {{ trans('cruds.tvdeActivity.fields.earnings_two') }}
                                </th>
                                <th>
                                    {{ trans('cruds.tvdeActivity.fields.bonus') }}
                                </th>
                                <th>
                                    {{ trans('cruds.tvdeActivity.fields.tolls') }}
                                </th>
                                <th>
                                    {{ trans('cruds.tvdeActivity.fields.parks') }}
                                </th>
                                <th>
                                    &nbsp;
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('tvde_activity_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.tvde-activities.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.tvde-activities.index') }}",
    columns: [
      { data: 'placeholder', name: 'placeholder' },
{ data: 'id', name: 'id' },
{ data: 'tvde_week_start_date', name: 'tvde_week.start_date' },
{ data: 'tvde_operator_name', name: 'tvde_operator.name' },
{ data: 'company_name', name: 'company.name' },
{ data: 'driver_code', name: 'driver_code' },
{ data: 'earnings_one', name: 'earnings_one' },
{ data: 'earnings_two', name: 'earnings_two' },
{ data: 'bonus', name: 'bonus' },
{ data: 'tolls', name: 'tolls' },
{ data: 'parks', name: 'parks' },
{ data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };
  let table = $('.datatable-TvdeActivity').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection