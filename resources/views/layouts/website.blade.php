<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>{{ env('APP_NAME') }} - @yield('title')</title>
    <meta content="@yield('description')" name="description">

    <!-- Favicons -->
    <link href="/assets/favicon.ico" type="image/x-icon">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="/assets/website/vendor/aos/aos.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />
    <link href="/assets/website/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/website/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/website/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="/assets/website/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="/assets/website/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="/assets/website/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />

    <!-- Template Main CSS File -->
    <link href="/assets/website/css/style.css" rel="stylesheet">

    @yield('styles')

</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top @yield('fixed')">
        <div class="container d-flex align-items-center justify-content-between">

            <a href="\" class="logo"><img src="/assets/website/img/logo-white.png" alt=""
                    class="img-fluid"></a>

            <x-navbar />

        </div>
    </header><!-- End Header -->

    @yield('header')

    <main id="main">

        @yield('content')
        
        <x-contact-component />

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row">

                    <div class="col-lg-3 col-md-6">
                        <div class="footer-info">
                            <img src="/assets/website/img/logo-white.png" width="200" class="mb-4">
                            <p>
                                Rua Faria Guimarães 654 4200-201 Porto<br>
                                Rua Godinho Faria 468 4465-150 São Mamede Infesta<br><br>
                                <strong>Telefone:</strong> +351 915 422 233<br>
                                <strong>Email:</strong> info@expertcom.pt<br>
                            </p>
                            <div class="social-links mt-3">
                                <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
                                <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
                                <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
                                <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 footer-links">
                        <h4>Links</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right"></i> <a href="/">Início</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="/#about">Sobre nós</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="/#services">Trabalhar connosco</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="">Termos e condições</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="/cms/3/politica-de-privacidade">Política de privacidade</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>Outros links</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right"></i> <a href="#">Outros links</a></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>

        <div class="container">
            <div class="copyright">
                &copy; Copyright <strong><span>gesTVDE</span></strong>. All Rights Reserved
            </div>
            <div class="credits">
                Designed by <a href="https://gestvde.pt/">gesTVDE</a>
            </div>
        </div>
    </footer><!-- End Footer -->

    <div id="preloader"></div>
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="/assets/website/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="/assets/website/vendor/aos/aos.js"></script>
    <script src="/assets/website/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/website/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="/assets/website/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="/assets/website/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Template Main JS File -->
    <script src="/assets/website/js/main.js"></script>

    @yield('scripts')

</body>

</html>