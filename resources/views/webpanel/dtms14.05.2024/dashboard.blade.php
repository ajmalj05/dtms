

<style type="text/css">
    .features .nav-feature-link {
        padding: 15px 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        cursor: pointer;
        margin-bottom: 1.5rem;
        background-color: #FFF;
        transition: all .2s ease-in-out;
        position: relative;
        border: 0px solid transparent;
        border-radius: 0.75rem;
        box-shadow: 0px 0px 13px 0px rgb(82 63 105 / 5%);
        height: calc(100% - 30px);
    }

    .features .nav-feature-link:hover {
        background-color: #d8e9f1;
    }

    span.key-text {
        color: #3f51b5;
    }

    .features .nav-feature-link i {
        color: #5f9ea0;
        line-height: 32px;
        height: 50px;
        width: 50px;
        border-radius: 50px;
        font-size: 32px;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f7f6f7;
    }

    .features .nav-feature-link h4 {
        font-size: 0.875rem;
        font-weight: 600;
        margin: 10px 0 0 0;
        color: #485664;
        line-height: 1.2rem;
        text-align: center;
    }

    .features .row {
        display: flex;
        flex-wrap: wrap;
        margin-top: calc(-1 * 1.5rem);
        margin-right: calc(-.5 * 1.5rem);
        margin-left: calc(-.5 * 1.5rem);
    }

    .features .row>* {
        flex-shrink: 0;
        width: 100%;
        max-width: 100%;
        padding-right: calc(1.5rem * .5);
        padding-left: calc(1.5rem * .5);
    }

    @media (min-width: 768px) {}
</style>

***********************************-->
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <div class="form-head d-flex mb-3 mb-md-5 align-items-start">
            <div class="mr-auto d-none d-lg-block">
                <h3 class="text-primary font-w600">Welcome to DTMS Dashboard!</h3>
               
            </div>

       
            <a href="javascript:void(0);" class="btn btn-primary ml-3"><i class="flaticon-381-settings-2 mr-0"></i></a>
        </div>



        <!--**********************************
   mnu items  start
***********************************-->
        <section id="features" class="features">
            <div class="">

                <ul class="nav-features row gy-4 d-flex">

                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>Today's Appointments<br> <span class="key-text">(FL)</span></h4>
                        </a>
                    </li>
                    <!-- End Tab 1 Nav -->
                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show" href="{{url('visitlists')}}">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>Today's Visits <br> <span class="key-text">(F2)</span></h4>
                        </a>
                    </li>
                    <!-- End Tab 1 Nav -->
                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>TOKEN DISPLAY <br><span class="key-text">(ALT+P)</span> </h4>
                        </a>
                    </li>
                    <!-- End Tab 1 Nav -->
                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>DOCTOR PRESCRIPTION <br><span class="key-text">(ALT+1)</span> </h4>
                        </a>
                    </li>
                 
                   
                    <!-- End Tab 1 Nav -->


                </ul>


            </div>
        </section>
    


    </div>
</div>

@include('frames/footer');


