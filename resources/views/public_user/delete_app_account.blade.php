<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title>JDC</title>

    <meta name="keywords" content="Caremed HTML5 Responsive Template Medicine COVID-19 Corona Hospital" />
    <meta name="description" content="Caremed - Hospital HTML5 Responsive Template">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="online_assets/images/favicon.ico">
    <script src="https://www.google.com/recaptcha/api.js"
            async defer></script>
    <!-- Plugins CSS File -->

    <link rel="stylesheet" href="{{asset('online_assets/css/bootstrap.min.css')}}">

    <link rel="stylesheet" href="{{asset('online_assets/css/plugins/owl.carousel.min.css')}}">


    <!-- Main CSS File -->

    <link rel="stylesheet" href="{{asset('online_assets/css/style.min.css')}}">

    <link rel="stylesheet" href="{{asset('online_assets/vendor/fontawesome/css/all.min.css')}}">

    <link href="{{asset('date_picker/css/bootstrap-datepicker.min.css')}}" rel='stylesheet' type='text/css'>

</head>
<body>
<!------------------------------------------------
loading overlay - start
------------------------------------------------>
<div class="loading-overlay">
    <div class="bounce-loader">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
</div>
<!------------------------------------------------
loading overlay - end
------------------------------------------------>
<div class="page-wrapper">
    <!------------------------------------------------
    navigation - start
    ------------------------------------------------>
    <header class="header">

        <div class="header-middle sticky-header" style="height:25px">
            <div class="header-left">
                <a href="index.html" class="logo">
                    <h1 class="mb-0"><img src="online_assets/images/logo.png" alt="Caremed Logo" ></h1>
                </a>
            </div>
            <div class="header-right">
                <button class="mobile-menu-toggler">
                    <span class="sr-only">Toggle mobile menu</span>
                    <i class="fal fa-bars"></i>
                </button>



            </div>
        </div>
    </header>
    <!------------------------------------------------
    navigation - end
    ------------------------------------------------>
    <main class="main">
        <div class="page-header bg-more-light" style="padding:6.5rem !important">

        </div>
        <!------------------------------------------------
        page header - start
        ------------------------------------------------>

        <!------------------------------------------------
        page header - end
        ------------------------------------------------>
        <!------------------------------------------------
        step bar - start
        ------------------------------------------------>

        <!------------------------------------------------
        step bar - end
        ------------------------------------------------>
        <!------------------------------------------------
        content - start
        ------------------------------------------------>
        <div class="container apppointment-step-2-section" style="padding-top:6rem !important">
            <div class="row">
                <div class="col-lg-8 offset-lg-0 col-sm-8 offset-sm-2 col-10 offset-1">
                    <h3 class="ls-n-20 line-height-1">DELETE MY ACCOUNT</h3>
                </div>
            </div>
            <div class="row" id="top_elm">
                <div class="col-lg-8 offset-lg-0 col-sm-8 offset-sm-2 col-10 offset-1">



                    <div class="row">
                        <div class="col-md-12">
                        <div class="form-group">

                            <label class="ml-2">Mobile Number</label>
                            <input type="text" class="form-control">
                            </div>

                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                        <div class="form-group">

                            <label class="ml-2">Reason for Deletion</label>
                            <textarea class="form-control"></textarea>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <button type="submit" class="btn btn-secondary-color btn-form d-flex mr-auto ml-auto mb-1">
                            <span>DELETE ACCOUNT</span>
                        </button>
                    </div>


                </div>
                <div class="col-lg-4 offset-lg-0 col-sm-8 offset-sm-2 col-10 offset-1">
                    <div class="image-box">
                        <figure>
                            <img src="online_assets/images/doctors/delete_account.png" class="w-100" alt="Doctor" width="370" height="407">
                        </figure>

                    </div>
                </div>
            </div>
        </div>
        <!------------------------------------------------
        content - e
        ------------------------------------------------>
    </main>
    <!------------------------------------------------
    footer - start
    ------------------------------------------------>
    <footer class="footer bg-primary-color">
        <div class="container">


            <div class="footer-bottom" style="padding:0.2rem;2.5rem !important">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-12 col-sm-7 col-10">
                        <p>Developed By | Netroxe IT Solutions PVT LTD</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!------------------------------------------------
    footer - end
    ------------------------------------------------>
</div>

<button id="scroll-top" title="Back to Top"><i class="fal fa-angle-up"></i></button>

<div class="mobile-menu-overlay"></div>


<!-- Plugins JS File -->

<script src="{{asset('online_assets/js/jquery.min.js')}}"></script>

<script src="{{asset('online_assets/js/bootstrap.bundle.min.js')}}"></script>

<script src="{{asset('online_assets/js/jquery.waypoints.min.js')}}"></script>

<script src="{{asset('online_assets/js/plugins/owl.carousel.min.js')}}"></script>

<script src="{{asset('online_assets/js/plugins/imagesloaded.pkgd.min.js')}}"></script>
<script src="{{asset('online_assets/js/plugins/isotope.pkgd.min.js')}}"></script>

 <script src="{{asset('date_picker/js/bootstrap-datepicker.min.js')}}" type='text/javascript'></script>
<script src="{{asset('/vendor/sweetalert2/dist/sweetalert2.min.js')}}"></script>

<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet">   -->

<!-- Main JS File -->
<script src="online_assets/js/main.min.js"></script>
<script>


$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});

</script>


</body>
</html>
