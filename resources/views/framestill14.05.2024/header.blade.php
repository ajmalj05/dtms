<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>DTMS </title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link href="{{asset('/vendor/jqvmap/css/jqvmap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('/vendor/chartist/css/chartist.min.css')}}">
    <link href="{{asset('vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.scss')}}" rel="stylesheet">
    <link href="https://cdn.lineicons.com/2.0/LineIcons.css" rel="stylesheet">
     <link href="{{asset('/vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">
     <link href="{{asset('/vendor/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet">
     <ink rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">



     <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">

     <meta name="csrf-token" content="{{ csrf_token() }}">
    <style type="text/css">
        .menu-card:hover {
            -webkit-box-shadow: 3px 5px 17px -4px #777777;
            box-shadow: 3px 5px 17px -4px #777777;
            background-color: #244072;
          
        }

        .menu-card {
            cursor: pointer;
            /* background-color: #244072;
	background-color: #4986CD; */
            background-color: #009688;
            color: #ffffff;
        }
        .error
        {
            color: red !important;
        }

        .menu-card .media>span {
            height: 50px;
            width: 50px;
            border-radius: 50px;
            padding: 10px 12px;
            font-size: 32px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #464a53;
            min-width: 50px;
            /* background-color: rgb(30 244 241 / 45%);
    color: #002C5B; */
            background-color: rgba(255, 255, 255, 0.25);
            color: #fff;
            

        }


        .form-control:disabled, .form-control[readonly] {
    background-color: #e9ecef !important;
    opacity: 1 !important;
}

        @media (min-width: 768px) {}



        .lds-ellipsis {
  display: inline-block;
  position: relative;
  width: 80px;
  height: 80px;
}
.lds-ellipsis div {
  position: absolute;
  top: 33px;
  width: 13px;
  height: 13px;
  border-radius: 50%;
  background: #fff;
  animation-timing-function: cubic-bezier(0, 1, 1, 0);
}
.lds-ellipsis div:nth-child(1) {
  left: 8px;
  animation: lds-ellipsis1 0.6s infinite;
}
.lds-ellipsis div:nth-child(2) {
  left: 8px;
  animation: lds-ellipsis2 0.6s infinite;
}
.lds-ellipsis div:nth-child(3) {
  left: 32px;
  animation: lds-ellipsis2 0.6s infinite;
}
.lds-ellipsis div:nth-child(4) {
  left: 56px;
  animation: lds-ellipsis3 0.6s infinite;
}
@keyframes lds-ellipsis1 {
  0% {
    transform: scale(0);
  }
  100% {
    transform: scale(1);
  }
}
@keyframes lds-ellipsis3 {
  0% {
    transform: scale(1);
  }
  100% {
    transform: scale(0);
  }
}
@keyframes lds-ellipsis2 {
  0% {
    transform: translate(0, 0);
  }
  100% {
    transform: translate(24px, 0);
  }
}
#loader{
	display:none;
}
#loader .content{
	position: fixed;
top: 0;
bottom: 0;
left: 0;
right: 0;
z-index: 9999999;
display: flex;
align-items: center;
justify-content: center;
background: #030303c9;
flex-direction: column;
}
    </style>

</head>



<body>
    <!--
        AJAX LOADING
    -->
    <div id="loader">
        <div class="content">
        <span style="color: #b5b3b3;font-size: 20px;"> Please wait</span>
        <br>
        <span style="color: #b5b3b3;font-size: 13px;">Processing...</span>
        <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
        </div>
        </div>
    <!------------------->

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->


    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">



        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <a href="javascript:void(0)" class="brand-logo">
                <img class="logo-abbr" src="{{asset('/images/logo.png')}}" alt="">
                <img class="logo-compact" src="{{asset('/images/logo-text.png')}}" alt="">
                <img class="brand-title" src="{{asset('/images/logo-text.png')}}" alt="">
            </a>

            <div class="nav-control">
                <div class="hamburger">
                    <span class="line"></span><span class="line"></span><span class="line"></span>
                </div>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Chat box start
        ***********************************-->


           <!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content">
                <nav class="navbar navbar-expand">
                    <div class="collapse navbar-collapse justify-content-between">
                        <div class="header-left">
                            <div class="dashboard_bar">
                                <?php echo $PageName; ?>
                            </div>
                        </div>

                        <ul class="navbar-nav header-right">
                            <li class="nav-item dropdown notification_dropdown">
                                <a class="nav-link dz-fullscreen" href="#">
                                    <svg id="icon-full" viewBox="0 0 24 24" width="26" height="26" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg>
                                    <svg id="icon-minimize" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minimize"><path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3"></path></svg>
                                </a>
                            </li>


                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                                    <div class="header-info">
                                        <span><?php echo Auth::user()->name; ?></span>
                                        <small>User</small>
                                    </div>
                                    <img src="{{asset('images/profile/pic1.jpg')}}" width="20" alt="" />
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
{{--                                    <a href="#" class="dropdown-item ai-icon">--}}
{{--                                        <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>--}}
{{--                                        <span class="ml-2">Profile </span>--}}
{{--                                    </a>--}}
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                    <a  href="#" onclick="event.preventDefault();
                                    this.closest('form').submit(); " class="dropdown-item ai-icon">
                                        <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                        <span class="ml-2">Logout </span>
                                    </a>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->
