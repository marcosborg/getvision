@extends('layouts.website')
@section('header')
<x-hero></x-hero>
@endsection
@section('content')

<x-about></x-about>

<x-about-component />

<x-services-component />

<x-brand-component />

<x-testimonial-component />

<x-cta-component />

<x-faqs-component />

@endsection