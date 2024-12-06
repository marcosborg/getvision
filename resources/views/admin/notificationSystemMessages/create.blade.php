@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.create') }} {{ trans('cruds.notificationSystemMessage.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.notification-system-messages.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                            <label for="roles">{{ trans('cruds.notificationSystemMessage.fields.roles') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="roles[]" id="roles" multiple>
                                @foreach($roles as $id => $role)
                                <option value="{{ $id }}" {{ in_array($id, old('roles', [])) ? 'selected' : '' }}>{{ $role }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('roles'))
                            <span class="help-block" role="alert">{{ $errors->first('roles') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.notificationSystemMessage.fields.roles_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('drivers') ? 'has-error' : '' }}">
                            <label for="drivers">{{ trans('cruds.notificationSystemMessage.fields.drivers') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="drivers[]" id="drivers" multiple>
                                @foreach($drivers as $id => $driver)
                                <option value="{{ $id }}" {{ in_array($id, old('drivers', [])) ? 'selected' : '' }}>{{ $driver }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('drivers'))
                            <span class="help-block" role="alert">{{ $errors->first('drivers') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.notificationSystemMessage.fields.drivers_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('companies') ? 'has-error' : '' }}">
                            <label for="companies">{{ trans('cruds.notificationSystemMessage.fields.companies') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="companies[]" id="companies" multiple>
                                @foreach($companies as $id => $company)
                                <option value="{{ $id }}" {{ in_array($id, old('companies', [])) ? 'selected' : '' }}>{{ $company }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('companies'))
                            <span class="help-block" role="alert">{{ $errors->first('companies') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.notificationSystemMessage.fields.companies_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('notification_system_template') ? 'has-error' : '' }}">
                            <label for="notification_system_template_id">{{ trans('cruds.notificationSystemMessage.fields.notification_system_template') }}</label>
                            <select class="form-control select2" name="notification_system_template_id" id="notification_system_template_id">
                                @foreach($notification_system_templates as $id => $entry)
                                <option value="{{ $id }}" {{ old('notification_system_template_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('notification_system_template'))
                            <span class="help-block" role="alert">{{ $errors->first('notification_system_template') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.notificationSystemMessage.fields.notification_system_template_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('custom_subject') ? 'has-error' : '' }}">
                            <label class="required" for="custom_subject">{{ trans('cruds.notificationSystemMessage.fields.custom_subject') }}</label>
                            <input class="form-control" type="text" name="custom_subject" id="custom_subject" value="{{ old('custom_subject', '') }}" required>
                            @if($errors->has('custom_subject'))
                            <span class="help-block" role="alert">{{ $errors->first('custom_subject') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.notificationSystemMessage.fields.custom_subject_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('message') ? 'has-error' : '' }}">
                            <label for="message">{{ trans('cruds.notificationSystemMessage.fields.message') }}</label>
                            <textarea class="form-control ckeditor" name="message" id="message">{!! old('message') !!}</textarea>
                            @if($errors->has('message'))
                            <span class="help-block" role="alert">{{ $errors->first('message') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.notificationSystemMessage.fields.message_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(() => {
        // Inicializar CKEditor para o campo #message uma vez
        let editorInstance;
        
        ClassicEditor.create(document.querySelector('#message'), {
        }).then(editor => {
            editorInstance = editor; // Salva a instância do editor
        }).catch(error => {
            console.error('Erro ao inicializar o CKEditor:', error);
        });

        // Atualizar o conteúdo do CKEditor quando o template for alterado
        $('#notification_system_template_id').change(() => {
            let notification_system_template_id = $('#notification_system_template_id').val();
            $.get('/admin/notification-system-template/' + notification_system_template_id).then((resp) => {
                $('#custom_subject').val(resp.subject); // Atualiza o campo de assunto

                if (editorInstance) {
                    editorInstance.setData(resp.message); // Atualiza o conteúdo do CKEditor com o novo template
                }
            });
        });
    });
</script>

@endsection
