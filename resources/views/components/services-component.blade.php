<!-- ======= Services Section ======= -->
<section id="services" class="services section-bg">
    <div class="container" data-aos="fade-up">

        <div class="section-title">
            <h2>Expertcom</h2>
            <p>Venha trabalhar connosco</p>
        </div>

        <div class="row" data-aos="fade-up" data-aos-delay="200">
            @foreach ($services as $item)
            <div class="col-md-6">
                <div class="icon-box">
                    <i class="{{ \App\Models\Service::ICON_RADIO[$item->icon] ?? '' }}"></i>
                    <h4><a href="#">{{ $item->title }}</a></h4>
                    <p>{{ $item->text }}</p>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section><!-- End Services Section -->