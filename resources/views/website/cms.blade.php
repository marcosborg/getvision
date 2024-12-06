@extends('layouts.website')
@section('title')
{{ $page->title }}
@endsection
@section('fixed')
header-inner-pages
@endsection
@section('content')
<!-- ======= Breadcrumbs ======= -->
<section class="breadcrumbs">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center">
            <h2>{{ $page->title }}</h2>
        </div>

    </div>
</section><!-- End Breadcrumbs -->

<section class="inner-page">
    <div class="container">
        @if ($page->image)
        <img src="{{ $page->image->getUrl() }}" class="img-thumbnail"
            style="max-width: 30%;float: inline-start;margin-right: 50px;margin-bottom: 51px;">
        @endif
        @if ($page->description)
        <p class="fst-italic">
            {{ $page->description }}
        </p>
        <hr>
        @endif
        {!! $page->text !!}
    </div>
</section>
@endsection