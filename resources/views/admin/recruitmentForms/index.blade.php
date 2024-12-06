@extends('layouts.admin')
@section('content')
<div class="content">
    @can('recruitment_form_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.recruitment-forms.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.recruitmentForm.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.recruitmentForm.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-RecruitmentForm">
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        {{ trans('cruds.recruitmentForm.fields.id') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.recruitmentForm.fields.company') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.recruitmentForm.fields.name') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.recruitmentForm.fields.email') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.recruitmentForm.fields.phone') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.recruitmentForm.fields.appointment') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.recruitmentForm.fields.done') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.recruitmentForm.fields.status') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.recruitmentForm.fields.type') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.recruitmentForm.fields.chanel') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.recruitmentForm.fields.daytime') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.recruitmentForm.fields.user') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.recruitmentForm.fields.to_company') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.recruitmentForm.fields.amount_to_pay') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.recruitmentForm.fields.responsible_for_the_lead') }}
                                    </th>
                                    <th>
                                        {{ trans('cruds.recruitmentForm.fields.created_at') }}
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recruitmentForms as $key => $recruitmentForm)
                                    <tr data-entry-id="{{ $recruitmentForm->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $recruitmentForm->id ?? '' }}
                                        </td>
                                        <td>
                                            {{ $recruitmentForm->company->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $recruitmentForm->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $recruitmentForm->email ?? '' }}
                                        </td>
                                        <td>
                                            {{ $recruitmentForm->phone ?? '' }}
                                        </td>
                                        <td>
                                            {{ $recruitmentForm->appointment ?? '' }}
                                        </td>
                                        <td>
                                            <span style="display:none">{{ $recruitmentForm->done ?? '' }}</span>
                                            <input type="checkbox" disabled="disabled" {{ $recruitmentForm->done ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            {{ App\Models\RecruitmentForm::STATUS_RADIO[$recruitmentForm->status] ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\RecruitmentForm::TYPE_RADIO[$recruitmentForm->type] ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\RecruitmentForm::CHANEL_RADIO[$recruitmentForm->chanel] ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\RecruitmentForm::DAYTIME_RADIO[$recruitmentForm->daytime] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $recruitmentForm->user->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $recruitmentForm->to_company->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $recruitmentForm->amount_to_pay ?? '' }}
                                        </td>
                                        <td>
                                            {{ $recruitmentForm->responsible_for_the_lead ?? '' }}
                                        </td>
                                        <td>
                                            {{ $recruitmentForm->created_at ?? '' }}
                                        </td>
                                        <td>
                                            @can('recruitment_form_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.recruitment-forms.show', $recruitmentForm->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('recruitment_form_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.recruitment-forms.edit', $recruitmentForm->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('recruitment_form_delete')
                                                <form action="{{ route('admin.recruitment-forms.destroy', $recruitmentForm->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('recruitment_form_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.recruitment-forms.massDestroy') }}",
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
  let table = $('.datatable-RecruitmentForm:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection