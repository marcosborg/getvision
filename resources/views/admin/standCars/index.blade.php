@extends('layouts.admin')
@section('content')
<div class="content">
    @can('stand_car_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.stand-cars.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.standCar.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.standCar.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-StandCar">
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        {{ trans('cruds.standCar.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.standCar.fields.brand') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.standCar.fields.car_model') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.standCar.fields.fuel') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.standCar.fields.transmision') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.standCar.fields.cylinder_capacity') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.standCar.fields.battery_capacity') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.standCar.fields.year') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.standCar.fields.month') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.standCar.fields.kilometers') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.standCar.fields.power') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.standCar.fields.origin') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.standCar.fields.distance') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.standCar.fields.price') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.standCar.fields.status') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.standCar.fields.images') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($standCars as $key => $standCar)
                                    <tr data-entry-id="{{ $standCar->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $standCar->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $standCar->brand->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $standCar->car_model->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $standCar->fuel->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\StandCar::TRANSMISION_RADIO[$standCar->transmision] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $standCar->cylinder_capacity ?? '' }}
                                        </td>
                                        <td>
                                            {{ $standCar->battery_capacity ?? '' }}
                                        </td>
                                        <td>
                                            {{ $standCar->year ?? '' }}
                                        </td>
                                        <td>
                                            {{ $standCar->month->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $standCar->kilometers ?? '' }}
                                        </td>
                                        <td>
                                            {{ $standCar->power ?? '' }}
                                        </td>
                                        <td>
                                            {{ $standCar->origin->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $standCar->distance ?? '' }}
                                        </td>
                                        <td>
                                            {{ $standCar->price ?? '' }}
                                        </td>
                                        <td>
                                            {{ $standCar->status->name ?? '' }}
                                        </td>
                                        <td>
                                            @foreach($standCar->images as $key => $media)
                                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                                    <img src="{{ $media->getUrl() }}" height="50">
                                                </a>
                                            @endforeach
                                        </td>
                                        <td>
                                            @can('stand_car_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.stand-cars.show', $standCar->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('stand_car_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.stand-cars.edit', $standCar->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('stand_car_delete')
                                                <form action="{{ route('admin.stand-cars.destroy', $standCar->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                                </form>
                                            @endcan

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
@can('stand_car_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.stand-cars.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
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

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-StandCar:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection