@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.edit') }} {{ trans('cruds.service.title_singular') }}
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{ route("admin.services.update", [$service->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label for="title">{{ trans('cruds.service.fields.title') }}</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{ old('title', $service->title) }}">
                            @if($errors->has('title'))
                                <span class="help-block" role="alert">{{ $errors->first('title') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.service.fields.title_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('text') ? 'has-error' : '' }}">
                            <label for="text">{{ trans('cruds.service.fields.text') }}</label>
                            <textarea class="form-control" name="text" id="text">{{ old('text', $service->text) }}</textarea>
                            @if($errors->has('text'))
                                <span class="help-block" role="alert">{{ $errors->first('text') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.service.fields.text_helper') }}</span>
                        </div>
                        <div class="form-group {{ $errors->has('icon') ? 'has-error' : '' }}">
                            <label>{{ trans('cruds.service.fields.icon') }}</label>
                            @foreach(App\Models\Service::ICON_RADIO as $key => $label)
                                <div>
                                    <input type="radio" id="icon_{{ $key }}" name="icon" value="{{ $key }}" {{ old('icon', $service->icon) === (string) $key ? 'checked' : '' }}>
                                    <label for="icon_{{ $key }}" style="font-weight: 400">{{ $key }}</label>
                                </div>
                            @endforeach
                            @if($errors->has('icon'))
                                <span class="help-block" role="alert">{{ $errors->first('icon') }}</span>
                            @endif
                            <span class="help-block">{{ trans('cruds.service.fields.icon_helper') }}</span>
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