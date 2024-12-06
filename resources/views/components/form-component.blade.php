@if(session('message'))
<div class="row" style='padding:20px 20px 0 20px;'>
    <div class="col-lg-12">
        <div class="alert alert-success" role="alert">{{ session('message') }}</div>
    </div>
</div>
@endif
@if($errors->count() > 0)
<div class="row" style='padding:20px 20px 0 20px;'>
    <div class="col-lg-12">
        <div class="alert alert-danger">
            <ul class="list-unstyled">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif
@php
$form_name = \App\Models\FormName::find($form_name_id)->load('form_inputs');
@endphp
<div class="d-flex justify-content-center">
    <div class="card" style="max-width: 400px; width: 100%;">
        <div class="card-body">
            <form action="/form-data" method="post" role="form" class="php-email-form">
                <input type="hidden" name="form_name_id" value="{{ $form_name->id }}">
                @csrf
                <div class="mb-4">
                    <h4>{{ $form_name->name }}</h4>
                    <strong>{{ $form_name->description }}</strong>
                </div>
                @foreach ($form_name->form_inputs as $form_input)
                <div class="form-group m-2">
                    <input type="text" name="{{ $form_input->name }}" class="form-control" id="{{ $form_input->name }}"
                        placeholder="{{ $form_input->label }}" {{ $form_input->required ?
                    'required' : '' }}>
                </div>
                @endforeach
                <div class="text-center mt-5"><button type="submit" class="btn-theme mt-4">Enviar</button></div>
            </form>
        </div>
    </div>
</div>