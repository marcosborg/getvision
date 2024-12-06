@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.recruitmentForm.title_singular') }}
                </div>
                <form method="POST" action="{{ route("admin.recruitment-forms.update", [$recruitmentForm->id]) }}"
                    enctype="multipart/form-data">
                    <div class="panel-body">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('user') ? 'has-error' : '' }}">
                                    <label for="user_id">{{ trans('cruds.recruitmentForm.fields.user') }}</label>
                                    <select class="form-control select2" name="user_id" id="user_id">
                                        @foreach($users as $id => $entry)
                                        <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') :
                                            $recruitmentForm->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('user'))
                                    <span class="help-block" role="alert">{{ $errors->first('user_id') }}</span>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.recruitmentForm.fields.user_helper')
                                        }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('company') ? 'has-error' : '' }}">
                                    <label for="company_id">{{ trans('cruds.recruitmentForm.fields.company') }}</label>
                                    <select class="form-control select2" name="company_id" id="company_id">
                                        @foreach($companies as $id => $entry)
                                        <option value="{{ $id }}" {{ (old('company_id') ? old('company_id') :
                                            $recruitmentForm->
                                            company->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('company'))
                                    <span class="help-block" role="alert">{{ $errors->first('company') }}</span>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.recruitmentForm.fields.company_helper')
                                        }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                    <label class="required" for="name">{{ trans('cruds.recruitmentForm.fields.name')
                                        }}</label>
                                    <input class="form-control" type="text" name="name" id="name"
                                        value="{{ old('name', $recruitmentForm->name) }}" required>
                                    @if($errors->has('name'))
                                    <span class="help-block" role="alert">{{ $errors->first('name') }}</span>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.recruitmentForm.fields.name_helper')
                                        }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                    <label class="required" for="email">{{ trans('cruds.recruitmentForm.fields.email')
                                        }}</label>
                                    <input class="form-control" type="email" name="email" id="email"
                                        value="{{ old('email', $recruitmentForm->email) }}" required>
                                    @if($errors->has('email'))
                                    <span class="help-block" role="alert">{{ $errors->first('email') }}</span>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.recruitmentForm.fields.email_helper')
                                        }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('to_company') ? 'has-error' : '' }}">
                                    <label for="to_company_id">{{ trans('cruds.recruitmentForm.fields.to_company') }}</label>
                                    <select class="form-control select2" name="to_company_id" id="to_company_id">
                                        @foreach($companies as $id => $entry)
                                        <option value="{{ $id }}" {{ (old('to_company_id') ? old('to_company_id') :
                                            $recruitmentForm->to_company->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('to_company'))
                                    <span class="help-block" role="alert">{{ $errors->first('to_company') }}</span>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.recruitmentForm.fields.to_company_helper')
                                        }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('amount_to_pay') ? 'has-error' : '' }}">
                                    <label for="amount_to_pay">{{ trans('cruds.recruitmentForm.fields.amount_to_pay') }}</label>
                                    <input class="form-control" type="text" name="amount_to_pay" id="amount_to_pay" value="{{ old('amount_to_pay', $recruitmentForm->amount_to_pay) }}">
                                    @if($errors->has('amount_to_pay'))
                                        <span class="help-block" role="alert">{{ $errors->first('amount_to_pay') }}</span>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.recruitmentForm.fields.amount_to_pay_helper') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {{ $errors->has('cv') ? 'has-error' : '' }}">
                                    <label for="cv">{{ trans('cruds.recruitmentForm.fields.cv') }}</label>
                                    <div class="needsclick dropzone" id="cv-dropzone">
                                    </div>
                                    @if($errors->has('cv'))
                                    <span class="help-block" role="alert">{{ $errors->first('cv') }}</span>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.recruitmentForm.fields.cv_helper')
                                        }}</span>
                                </div>
                                <div class="form-group {{ $errors->has('comments') ? 'has-error' : '' }}">
                                    <label for="comments">{{ trans('cruds.recruitmentForm.fields.comments') }}</label>
                                    <textarea class="form-control ckeditor" name="comments"
                                        id="comments">{!! old('comments', $recruitmentForm->comments) !!}</textarea>
                                    @if($errors->has('comments'))
                                    <span class="help-block" role="alert">{{ $errors->first('comments') }}</span>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.recruitmentForm.fields.comments_helper')
                                        }}</span>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group {{ $errors->has('start_time') ? 'has-error' : '' }}">
                                            <label for="start_time">{{ trans('cruds.recruitmentForm.fields.start_time') }}</label>
                                            <input class="form-control timepicker" type="text" name="start_time" id="start_time" value="{{ old('start_time', $recruitmentForm->start_time) }}">
                                            @if($errors->has('start_time'))
                                                <span class="help-block" role="alert">{{ $errors->first('start_time') }}</span>
                                            @endif
                                            <span class="help-block">{{ trans('cruds.recruitmentForm.fields.start_time_helper') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group {{ $errors->has('end_time') ? 'has-error' : '' }}">
                                            <label for="end_time">{{ trans('cruds.recruitmentForm.fields.end_time') }}</label>
                                            <input class="form-control timepicker" type="text" name="end_time" id="end_time" value="{{ old('end_time', $recruitmentForm->end_time) }}">
                                            @if($errors->has('end_time'))
                                                <span class="help-block" role="alert">{{ $errors->first('end_time') }}</span>
                                            @endif
                                            <span class="help-block">{{ trans('cruds.recruitmentForm.fields.end_time_helper') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group {{ $errors->has('day_off') ? 'has-error' : '' }}">
                                            <label>{{ trans('cruds.recruitmentForm.fields.day_off') }}</label>
                                            @foreach(App\Models\RecruitmentForm::DAY_OFF_RADIO as $key => $label)
                                                <div>
                                                    <input type="radio" id="day_off_{{ $key }}" name="day_off" value="{{ $key }}" {{ old('day_off', $recruitmentForm->day_off) === (string) $key ? 'checked' : '' }}>
                                                    <label for="day_off_{{ $key }}" style="font-weight: 400">{{ $label }}</label>
                                                </div>
                                            @endforeach
                                            @if($errors->has('day_off'))
                                                <span class="help-block" role="alert">{{ $errors->first('day_off') }}</span>
                                            @endif
                                            <span class="help-block">{{ trans('cruds.recruitmentForm.fields.day_off_helper') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Informação sobre a entrevista</label>
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <div
                                            class="form-group {{ $errors->has('contact_successfully') ? 'has-error' : '' }}">
                                            <div>
                                                <input type="hidden" name="contact_successfully" value="0">
                                                <input type="checkbox" name="contact_successfully"
                                                    id="contact_successfully" value="1" {{
                                                    $recruitmentForm->contact_successfully ||
                                                old('contact_successfully', 0) === 1 ?
                                                'checked' : '' }}>
                                                <label for="contact_successfully" style="font-weight: 400">{{
                                                    trans('cruds.recruitmentForm.fields.contact_successfully')
                                                    }}</label>
                                            </div>
                                            @if($errors->has('contact_successfully'))
                                            <span class="help-block" role="alert">{{
                                                $errors->first('contact_successfully') }}</span>
                                            @endif
                                            <span class="help-block">{{
                                                trans('cruds.recruitmentForm.fields.contact_successfully_helper')
                                                }}</span>
                                        </div>
                                        <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                                            <label class="required" for="phone">{{
                                                trans('cruds.recruitmentForm.fields.phone')
                                                }}</label>
                                            <input class="form-control" type="text" name="phone" id="phone"
                                                value="{{ old('phone', $recruitmentForm->phone) }}" required>
                                            @if($errors->has('phone'))
                                            <span class="help-block" role="alert">{{ $errors->first('phone') }}</span>
                                            @endif
                                            <span class="help-block">{{
                                                trans('cruds.recruitmentForm.fields.phone_helper') }}</span>
                                        </div>
                                        <div
                                            class="form-group {{ $errors->has('scheduled_interview') ? 'has-error' : '' }}">
                                            <div>
                                                <input type="hidden" name="scheduled_interview" value="0">
                                                <input type="checkbox" name="scheduled_interview"
                                                    id="scheduled_interview" value="1" {{
                                                    $recruitmentForm->scheduled_interview || old('scheduled_interview',
                                                0) === 1 ?
                                                'checked' : '' }}>
                                                <label for="scheduled_interview" style="font-weight: 400">{{
                                                    trans('cruds.recruitmentForm.fields.scheduled_interview') }}</label>
                                            </div>
                                            @if($errors->has('scheduled_interview'))
                                            <span class="help-block" role="alert">{{
                                                $errors->first('scheduled_interview') }}</span>
                                            @endif
                                            <span class="help-block">{{
                                                trans('cruds.recruitmentForm.fields.scheduled_interview_helper')
                                                }}</span>
                                        </div>
                                        <div class="form-group {{ $errors->has('appointment') ? 'has-error' : '' }}">
                                            <label for="appointment">{{
                                                trans('cruds.recruitmentForm.fields.appointment') }}</label>
                                            <input class="form-control datetime" type="text" name="appointment"
                                                id="appointment"
                                                value="{{ old('appointment', $recruitmentForm->appointment) }}">
                                            @if($errors->has('appointment'))
                                            <span class="help-block" role="alert">{{ $errors->first('appointment')
                                                }}</span>
                                            @endif
                                            <span class="help-block">{{
                                                trans('cruds.recruitmentForm.fields.appointment_helper')
                                                }}</span>
                                        </div>
                                        <div class="form-group {{ $errors->has('done') ? 'has-error' : '' }}">
                                            <div>
                                                <input type="hidden" name="done" value="0">
                                                <input type="checkbox" name="done" id="done" value="1" {{
                                                    $recruitmentForm->done ||
                                                old('done', 0) === 1 ? 'checked' : '' }}>
                                                <label for="done" style="font-weight: 400">{{
                                                    trans('cruds.recruitmentForm.fields.done')
                                                    }}</label>
                                            </div>
                                            @if($errors->has('done'))
                                            <span class="help-block" role="alert">{{ $errors->first('done') }}</span>
                                            @endif
                                            <span class="help-block">{{
                                                trans('cruds.recruitmentForm.fields.done_helper') }}</span>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                                                    <label>{{ trans('cruds.recruitmentForm.fields.status') }}</label>
                                                    @foreach(App\Models\RecruitmentForm::STATUS_RADIO as $key => $label)
                                                        <div>
                                                            <input type="radio" id="status_{{ $key }}" name="status" value="{{ $key }}" {{ old('status', $recruitmentForm->status) === (string) $key ? 'checked' : '' }}>
                                                            <label for="status_{{ $key }}" style="font-weight: 400">{{ $label }}</label>
                                                        </div>
                                                    @endforeach
                                                    @if($errors->has('status'))
                                                        <span class="help-block" role="alert">{{ $errors->first('status') }}</span>
                                                    @endif
                                                    <span class="help-block">{{ trans('cruds.recruitmentForm.fields.status_helper') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                                                    <label>{{ trans('cruds.recruitmentForm.fields.type') }}</label>
                                                    @foreach(App\Models\RecruitmentForm::TYPE_RADIO as $key => $label)
                                                        <div>
                                                            <input type="radio" id="type_{{ $key }}" name="type" value="{{ $key }}" {{ old('type', $recruitmentForm->type) === (string) $key ? 'checked' : '' }}>
                                                            <label for="type_{{ $key }}" style="font-weight: 400">{{ $label }}</label>
                                                        </div>
                                                    @endforeach
                                                    @if($errors->has('type'))
                                                        <span class="help-block" role="alert">{{ $errors->first('type') }}</span>
                                                    @endif
                                                    <span class="help-block">{{ trans('cruds.recruitmentForm.fields.type_helper') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group {{ $errors->has('chanel') ? 'has-error' : '' }}">
                                                    <label>{{ trans('cruds.recruitmentForm.fields.chanel') }}</label>
                                                    @foreach(App\Models\RecruitmentForm::CHANEL_RADIO as $key => $label)
                                                        <div>
                                                            <input type="radio" id="chanel_{{ $key }}" name="chanel" value="{{ $key }}" {{ old('chanel', $recruitmentForm->chanel) === (string) $key ? 'checked' : '' }}>
                                                            <label for="chanel_{{ $key }}" style="font-weight: 400">{{ $label }}</label>
                                                        </div>
                                                    @endforeach
                                                    @if($errors->has('chanel'))
                                                        <span class="help-block" role="alert">{{ $errors->first('chanel') }}</span>
                                                    @endif
                                                    <span class="help-block">{{ trans('cruds.recruitmentForm.fields.chanel_helper') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group {{ $errors->has('daytime') ? 'has-error' : '' }}">
                                                    <label>{{ trans('cruds.recruitmentForm.fields.daytime') }}</label>
                                                    @foreach(App\Models\RecruitmentForm::DAYTIME_RADIO as $key => $label)
                                                        <div>
                                                            <input type="radio" id="daytime_{{ $key }}" name="daytime" value="{{ $key }}" {{ old('daytime', $recruitmentForm->daytime) === (string) $key ? 'checked' : '' }}>
                                                            <label for="daytime_{{ $key }}" style="font-weight: 400">{{ $label }}</label>
                                                        </div>
                                                    @endforeach
                                                    @if($errors->has('chanel'))
                                                        <span class="help-block" role="alert">{{ $errors->first('daytime') }}</span>
                                                    @endif
                                                    <span class="help-block">{{ trans('cruds.recruitmentForm.fields.daytime_helper') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group {{ $errors->has('responsible_for_the_lead') ? 'has-error' : '' }}">
                                            <label for="responsible_for_the_lead">{{
                                                trans('cruds.recruitmentForm.fields.responsible_for_the_lead')
                                                }}</label>
                                            <input class="form-control" type="text" name="responsible_for_the_lead" id="responsible_for_the_lead"
                                                value="{{ old('responsible_for_the_lead', $recruitmentForm->responsible_for_the_lead) }}">
                                            @if($errors->has('responsible_for_the_lead'))
                                            <span class="help-block" role="alert">{{ $errors->first('responsible_for_the_lead') }}</span>
                                            @endif
                                            <span class="help-block">{{
                                                trans('cruds.recruitmentForm.fields.responsible_for_the_lead_helper') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    Dropzone.options.cvDropzone = {
    url: '{{ route('admin.recruitment-forms.storeMedia') }}',
    maxFilesize: 2, // MB
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2
    },
    success: function (file, response) {
      $('form').find('input[name="cv"]').remove()
      $('form').append('<input type="hidden" name="cv" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="cv"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($recruitmentForm) && $recruitmentForm->cv)
      var file = {!! json_encode($recruitmentForm->cv) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="cv" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
<script>
    $(document).ready(function () {
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('admin.recruitment-forms.storeCKEditorImages') }}', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', '{{ $recruitmentForm->id ?? 0 }}');
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

@endsection