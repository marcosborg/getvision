@extends('layouts.website')
@section('title')
{{ $transfer_tour->name }}
@endsection
@section('fixed')
header-inner-pages
@endsection
@section('content')
<!-- ======= Breadcrumbs ======= -->
<section class="breadcrumbs">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h1>{{ $transfer_tour->name }}</h1>
        </div>
    </div>
</section><!-- End Breadcrumbs -->

<section class="inner-page">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <!-- Slider main container -->
                <div class="swiper">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper">
                        <!-- Slides -->
                        @foreach ($transfer_tour->photo as $photo)
                        <div class="swiper-slide">
                            <img src="{{ $photo->getUrl() }}" alt="">
                        </div>
                        @endforeach
                    </div>
                    <!-- If we need pagination -->
                    <div class="swiper-pagination"></div>

                    <!-- If we need navigation buttons -->
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>

                    <!-- If we need scrollbar -->
                    <div class="swiper-scrollbar"></div>
                </div>
            </div>
            <div class="col-md-6">
                {!! $transfer_tour->description !!}
                <div class="card mt-4">
                    <div class="card-header">
                        Pedir contacto
                    </div>
                    <form action="/transfers-tours/send-request" method="post">
                        @csrf
                        <input type="hidden" name="transfer_tour_id" value="{{ $transfer_tour->id }}">
                        <div class="card-body">
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
                            <div class="form-group">
                                <label>Nome</label>
                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" id="email">
                            </div>
                            <div class="form-group">
                                <label>Telefone</label>
                                <input type="text" class="form-control" name="phone" id="phone">
                            </div>
                            <div class="form-group">
                                <label>Localidade</label>
                                <input type="text" class="form-control" name="city" id="city">
                            </div>
                            <div class="form-group ">
                                <label for="message">Message</label>
                                <textarea class="form-control" name="message" id="message"></textarea>
                            </div>
                            <div class="form-group ">
                                <div>
                                    <input type="checkbox" name="rgpd" id="rgpd" value="1">
                                    <label class="required" for="rgpd" style="font-weight: 400">Autorizo o tratamento
                                        dos dados fornecidos</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-theme" type="submit">Pedir contacto</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
<script>
    const swiper = new Swiper('.swiper', {
        // Optional parameters
        direction: 'horizontal',
        loop: true,

        // If we need pagination
        pagination: {
            el: '.swiper-pagination',
        },

        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },

        // And if we need scrollbar
        scrollbar: {
            el: '.swiper-scrollbar',
        },
    });
</script>
@endsection
@section('styles')
<style>
    .swiper {
        height: 500px;
    }
</style>
@endsection
<script>
    console.log({!! $transfer_tour !!})
</script>