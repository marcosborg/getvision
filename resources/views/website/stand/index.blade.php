@extends('layouts.website')
@section('title')
Transfers & Tours
@endsection
@section('fixed')
header-inner-pages
@endsection
@section('content')
<!-- ======= Breadcrumbs ======= -->
<section class="breadcrumbs">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Stand</h2>
        </div>

    </div>
</section><!-- End Breadcrumbs -->

<section class="inner-page">
    <div class="container">
        <div class="row">
            <!-- Conteúdo principal -->
            <div class="col-md-9">
                @foreach ($stand_cars as $stand_car)
                <a href="/stand/viatura/{{ $stand_car->id }}">
                    <div class="card mb-4">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <div class="car-image"
                                    style="background-image: url('{{ $stand_car->images->isNotEmpty() ? $stand_car->images[0]->getUrl() : 'https://via.placeholder.com/400?text=IMAGEM' }}');">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <p class="fw-bold text-uppercase">{{ $stand_car->brand->name }} {{
                                                $stand_car->car_model->name }}</p>
                                        </div>
                                        <div class="col text-end">
                                            <span
                                                class="badge bg-{{ $stand_car->status->id == 1 ? 'danger' : 'success' }}">{{
                                                $stand_car->status->name }}</span>
                                        </div>
                                    </div>
                                    <h3><small>€</small> {{ number_format($stand_car->price, 2, '.', ' ') }}</h3>
                                    <div class="row">
                                        <div class="col" style="font-size: 12px">
                                            <span class="text-uppercase fw-bold">Combustivel</span><br>
                                            <span>{{ $stand_car->fuel->name }}</span>
                                        </div>
                                        <div class="col" style="font-size: 12px">
                                            <span class="text-uppercase fw-bold">Mês | Ano</span><br>
                                            <span>{{ $stand_car->month->name }} | {{ $stand_car->year }}</span>
                                        </div>
                                        <div class="col" style="font-size: 12px">
                                            <span class="text-uppercase fw-bold">Caixa</span><br>
                                            <span>{{ $stand_car->transmision }}</span>
                                        </div>
                                        <div class="col" style="font-size: 12px">
                                            <span class="text-uppercase fw-bold">Quilómetros</span><br>
                                            <span>{{ number_format($stand_car->kilometers, 0, '', ' ') }} km</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col" style="font-size: 12px">
                                            <span class="text-uppercase fw-bold">Cilindrada</span><br>
                                            <span>{{ $stand_car->cylinder_capacity }} cm3</span>
                                        </div>
                                        <div class="col" style="font-size: 12px">
                                            <span class="text-uppercase fw-bold">Potência</span><br>
                                            <span>{{ $stand_car->power }} CV</span>
                                        </div>
                                        <div class="col" style="font-size: 12px">
                                            <span class="text-uppercase fw-bold">Origem</span><br>
                                            <span>{{ $stand_car->origin->name }}</span>
                                        </div>
                                        <div class="col" style="font-size: 12px">
                                            <span class="text-uppercase fw-bold">Localidade</span><br>
                                            <span>{{ $stand_car->distance }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
                <!-- Adicionar mais viaturas conforme necessário -->
            </div>
            <!-- Filtros -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        Filtros
                    </div>
                    <div class="car-body p-2">
                        <div class="form-group">
                            <select name="brand_id" class="form-control select2">
                                <option selected disabled>Marca</option>
                                @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <hr>
                        <div class="form-group">
                            <select name="model_id" class="form-control select2">
                                <option selected disabled>Modelo</option>
                                @foreach ($models as $model)
                                <option value="{{ $model->id }}">{{ $model->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <hr>
                        <div class="form-group">
                            <select name="fuel_id" class="form-control select2">
                                <option selected disabled>Combustivel</option>
                                @foreach ($fuels as $fuel)
                                <option value="{{ $fuel->id }}">{{ $fuel->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <hr>
                        <div class="form-group">
                            <select name="transmision" class="form-control select2">
                                <option selected disabled>Caixa</option>
                                <option value="Manual">Manual</option>
                                <option value="Auto">Auto</option>
                            </select>
                        </div>
                        <hr>
                        <div class="form-group">
                            <select name="origin_id" class="form-control select2">
                                <option selected disabled>Origem</option>
                                @foreach ($origins as $origin)
                                <option value="{{ $origin->id }}">{{ $origin->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="kilometers_max" class="form-label">Quilómetros máx. <span
                                    id="kilometers_max_val">250000 Km</span></label>
                            <input type="range" class="form-range" id="kilometers_max" min="0" max="250000"
                                value="250000" onchange="updateKilometersRange()">
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="prices_max" class="form-label">Preço máx. <span id="prices_max_val">50000
                                    €</span></label>
                            <input type="range" class="form-range" id="prices_max" min="0" max="50000" value="50000"
                                onchange="updatePricesRange()">
                        </div>
                    </div>
                    <div class="card footer">
                        <button class="btn btn-theme">Filtrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
@endsection
@section('styles')
<style>
    .car-image {
        height: 200px;
        background-size: cover;
        background-position: center;
    }
</style>
@endsection