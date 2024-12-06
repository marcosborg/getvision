@extends('layouts.website')
@section('title')
Veículo Detalhes
@endsection
@section('fixed')
header-inner-pages
@endsection
@section('content')
<!-- ======= Breadcrumbs ======= -->
<section class="breadcrumbs">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Detalhes do Veículo</h2>
        </div>
    </div>
</section><!-- End Breadcrumbs -->

<section class="inner-page">
    <div class="container">
        <div class="row">
            <!-- Conteúdo principal -->
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="row g-0">
                        <div class="col-md-6">
                            <!-- Slider main container -->
                            <div class="swiper swiper-main">
                                <!-- Additional required wrapper -->
                                <div class="swiper-wrapper">
                                    <!-- Slides -->
                                    @if ($car->images->isNotEmpty())
                                    @foreach ($car->images as $image)
                                    <div class="swiper-slide">
                                        <div class="car-image"
                                            style="background-image: url('{{ $image->getUrl() }}'); height: 400px;">
                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <div class="swiper-slide">
                                        <div class="car-image"
                                            style="background-image: url('https://via.placeholder.com/600x400'); height: 400px;">
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <!-- If we need pagination -->
                                <div class="swiper-pagination"></div>

                                <!-- If we need navigation buttons -->
                                <div class="swiper-button-prev"></div>
                                <div class="swiper-button-next"></div>
                            </div>
                            <!-- Thumbnail slider -->
                            <div class="swiper swiper-thumbs mt-2">
                                <div class="swiper-wrapper">
                                    @if ($car->images->isNotEmpty())
                                    @foreach ($car->images as $image)
                                    <div class="swiper-slide">
                                        <div class="thumb-image"
                                            style="background-image: url('{{ $image->getUrl() }}'); height: 100px;">
                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <div class="swiper-slide">
                                        <div class="thumb-image"
                                            style="background-image: url('https://via.placeholder.com/100x100'); height: 100px;">
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body">
                                <h3 class="card-title">{{ $car->brand->name }} {{ $car->car_model->name }}</h3>
                                <h4><small>€</small> {{ number_format($car->price, 2, '.', ' ') }}</h4>
                                <span class="badge bg-{{ $car->status->id == 1 ? 'danger' : 'success' }}">{{
                                    $car->status->name }}</span>
                                <div class="mt-3">
                                    <p><span class="fw-bold">Combustível:</span> {{ $car->fuel->name }}</p>
                                    <p><span class="fw-bold">Mês | Ano:</span> {{ $car->month->name }} | {{ $car->year
                                        }}</p>
                                    <p><span class="fw-bold">Caixa:</span> {{ $car->transmision }}</p>
                                    <p><span class="fw-bold">Quilómetros:</span> {{ number_format($car->kilometers, 0,
                                        '', ' ') }} km</p>
                                    <p><span class="fw-bold">Cilindrada:</span> {{ $car->cylinder_capacity }} cm³</p>
                                    <p><span class="fw-bold">Potência:</span> {{ $car->power }} CV</p>
                                    <p><span class="fw-bold">Origem:</span> {{ $car->origin->name }}</p>
                                    <p><span class="fw-bold">Localidade:</span> {{ $car->distance }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Formulário de Contato -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Peça Mais Informações</h5>
                        <form action="" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nome</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Telefone</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="message" class="form-label">Mensagem</label>
                                        <textarea class="form-control" id="message" name="message" rows="3"
                                            required></textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-theme">Enviar</button>
                        </form>
                    </div>
                </div>
                <!-- Fim do Formulário de Contato -->
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .car-image {
        height: 400px;
        background-size: cover;
        background-position: center;
    }

    .thumb-image {
        height: 100px;
        background-size: cover;
        background-position: center;
        cursor: pointer;
    }
</style>
@endsection

@section('scripts')
<script>
    const swiperThumbs = new Swiper('.swiper-thumbs', {
        slidesPerView: 4,
        spaceBetween: 10,
        freeMode: true,
        watchSlidesProgress: true,
    });

    const swiperMain = new Swiper('.swiper-main', {
        loop: true,
        spaceBetween: 10,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        thumbs: {
            swiper: swiperThumbs,
        },
    });
</script>
@endsection