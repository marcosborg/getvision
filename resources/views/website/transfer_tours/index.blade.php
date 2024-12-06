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
            <h2>Transfers & Tours</h2>
        </div>

    </div>
</section><!-- End Breadcrumbs -->

<section class="inner-page">
    <div class="container">
        <div class="row">
            @foreach ($transfer_tours as $transfer_tour)
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-img-top" style="height: 200px; background-image: url({{ $transfer_tour->photo[0]->getUrl() }}); background-size: cover; background-position: center center"></div>
                    <div class="card-body">
                        <h5 class="card-title" style="height: 55px;">{{ $transfer_tour->name }}</h5>
                        <div style="height: 120px;">
                            {!! Str::limit($transfer_tour->description, 100) !!}
                        </div>
                        <a href="/transfers-tours/tour/{{ $transfer_tour->id }}" class="btn btn-theme">Ver tour</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
<script>
    console.log({!! $transfer_tours !!})
</script>