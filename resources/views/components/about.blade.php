@php
    $sobre = \App\Models\HomeInfo::first();
@endphp
<!-- ======= About Section ======= -->
<section id="about" class="about">
    <div class="container p-4" data-aos="fade-up">

        <div class="row">

            <div class="col-lg-6 align-self-baseline" data-aos="zoom-in" data-aos-delay="100">
                @if ($sobre->image)
                <img src="{{ $sobre->image ? $sobre->image->getUrl() : '' }}" class="img-fluid" alt="">
                @endif

            </div>

            <div class="col-lg-6 pt-3 pt-lg-0 content">
                <h3 class="mt-4">{{ $sobre->title ?? '' }}</h3>
                <p class="fst-italic">
                    {{ $sobre->description ?? '' }}
                </p>
                {!! $sobre->text ?? '' !!}
                @if ($sobre->button && $sobre->link)
                <p>&nbsp;</p>
                <a href="{{ $sobre->link ?? '' }}" class="btn-theme mt-4">{{ $sobre->button ?? '' }}</a>
                @endif
            </div>

        </div>

    </div>
</section><!-- End About Section -->