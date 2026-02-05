

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

        The current Laravel version is {{ app()->version() }}

        <div class="form-head d-flex mb-3 mb-md-5 align-items-start">
            <div class="mr-auto d-none d-lg-block">
                <h3 class="text-primary font-w600">Welcome to DTMS!</h3>
                <p class="mb-0">Hospital Management System</p>
            </div>

            <div class="input-group search-area ml-auto d-inline-flex">
                <select type="text" class="form-control" placeholder="Search here" onchange="setBranch(this.value)">
                    <option value="">Select Branch</option>
                    <?php
                        foreach ($branch_list as $key) {
                            ?>
                            <option <?php if($selected_branch==$key->branch_id) echo "selected"; ?> value="{{$key->branch_id}}">{{$key->branch_name}}</option>
                            <?php
                        }
                    ?>
                </select>
                {{-- <div class="input-group-append">
                    <span class="input-group-text"><i class="flaticon-381-search-2"></i></span>
                </div> --}}
            </div>
            <a href="javascript:void(0);" class="btn btn-primary ml-3"><i class="flaticon-381-settings-2 mr-0"></i></a>
        </div>



        <!--**********************************
   mnu items  start
***********************************-->
        <section id="features" class="features">



            <div class="">

                <ul class="nav-features row gy-4 d-flex">


                    <?php
                      foreach ($menus as $row) {
                          $submenuflag=$row->submenuflag;

                        if($submenuflag==1)
                        {
                            foreach ($row->submenu as $rk) {
                                $submenu=$rk->submenu;
                                $submenulink=trim($rk->submenulink);
                                if($submenulink!="#")
                                {
                                $href=route("$submenulink");
                                }
                                else{
                                $href='#';
                                }
                                if($rk->sid>1)
                                {
                            ?>
                                <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                                    <a class="nav-feature-link active show" href="{{$href}}">
                                        <i class="flaticon-381-folder-5 color-cyan"></i>
                                        <h4>{{strtoupper($submenu)}} <br> <span class="key-text">({{$rk->short_cut}})</span></h4>
                                    </a>
                                </li>
                            <?php
                                }// avoid dashboard
                            }
                        }

                      }
                    ?>




                    {{-- <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show" href="{{url('patientRegistration')}}">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>REGISTRATION <br> <span class="key-text">(F2)</span></h4>
                        </a>
                    </li>

                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show"  href="{{url('patientSearch')}}">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>PATIENT SEARCH <br> <span class="key-text">(ALT+A)</span></h4>
                        </a>
                    </li>
                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show"  href="{{url('patientBooks')}}">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>PATIENT BOOKS <br> <span class="key-text">(ALT+B)</span></h4>
                        </a>
                    </li>
                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show" href="{{url('appointmentList')}}">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>APPOINTMENT LIST <br> <span class="key-text">(ALT+C)</span></h4>
                        </a>
                    </li>

                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show" href="{{url('visitlists')}}">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>TODAY'S VISITS <br> <span class="key-text">(ALT+Z)</span></h4>
                        </a>
                    </li>


                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show" href="{{url('ip-admission-list')}}">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>IP ADMISSION <br><span class="key-text">(ALT+D)</span> </h4>
                        </a>
                    </li>
                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show" href="{{url('discharge-list')}}">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>DISCHARGE LIST <br><span class="key-text">(ALT+E)</span> </h4>
                        </a>
                    </li>
                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show" href="{{url('UserManagement/userGroup')}}">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>USER GROUP <br><span class="key-text">(ALT+F)</span> </h4>
                        </a>
                    </li>
                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show" href="{{url('UserManagement/createUser')}}">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>CREATE USER <br><span class="key-text">(ALT+G)</span> </h4>
                        </a>
                    </li>
                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show" href="{{url('masterData/sectionOne')}}">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>MASTER DATA SECTION1 <br><span class="key-text">(ALT+H)</span> </h4>
                        </a>
                    </li>
                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show" href="{{url('masterData/sectionTwo')}}">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>MASTER DATA SECTION2 <br><span class="key-text">(ALT+I)</span> </h4>
                        </a>
                    </li>
                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show" href="{{url('masterData/sectionThree')}}">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>SPECIALISTS <br><span class="key-text">(ALT+J)</span> </h4>
                        </a>
                    </li>
                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show" href="{{url('masterData/sectionFour')}}">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>MASTER DATA SECTION4 <br><span class="key-text">(ALT+L)</span> </h4>
                        </a>
                    </li>
                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show" href="{{url('masterData/sectionFive')}}">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>MASTER DATA SECTION5 <br><span class="key-text">(ALT+M)</span> </h4>
                        </a>
                    </li>
                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show" href="{{url('dtms_master')}}">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>DTMS MASTERS <br><span class="key-text">(ALT+N)</span> </h4>
                        </a>
                    </li>
                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show" href="{{url('prescription_master')}}">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>PRESCRIPTION MASTER<br><span class="key-text">(ALT+O)</span> </h4>
                        </a>
                    </li>
                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show" href="{{url('app_notification')}}">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>APP SETTINGS <br><span class="key-text">(ALT+P)</span> </h4>
                        </a>
                    </li>
                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show" href="{{url('product')}}">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>PRODUCTS <br><span class="key-text">(ALT+Q)</span> </h4>
                        </a>
                    </li>
                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show" href="{{url('service-item-group')}}">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>SERVICE ITEM GROUP <br><span class="key-text">(ALT+R)</span> </h4>
                        </a>
                    </li>
                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show" href="{{url('service-item-master')}}">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>SERVICE ITEM MASTER <br><span class="key-text">(ALT+S)</span> </h4>
                        </a>
                    </li>
                    <li class="nav-feature-item col-6 col-md-4 col-lg-2">
                        <a class="nav-feature-link active show" href="{{url('test-master')}}">
                            <i class="flaticon-381-folder-5 color-cyan"></i>
                            <h4>TEST MASTER <br><span class="key-text">(ALT+T)</span> </h4>
                        </a>
                    </li>
                  --}}


                </ul>


            </div>
        </section>
        <!-- <div class="row">
              <div class="col-xl-3 col-lg-6 col-md-4 col-sm-6">
                <div class="widget-stat card menu-card">
                    <div class="card-body  py-2 pl-3">
                        <div class="media">
                            <div class="media-body ">
                                <p class="mb-1">Pharmacy(F11)</p>
                            </div>
                            <span class="ml-2">
                                <i class="flaticon-381-plus"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>	  <div class="col-xl-3 col-lg-6 col-md-4 col-sm-6">
                <div class="widget-stat card menu-card">
                    <div class="card-body  py-2 pl-3">
                        <div class="media">
                            <div class="media-body ">
                                <p class="mb-1">Pt.Documents(Alt+C)</p>
                            </div>
                            <span class="ml-2">
                                <i class="flaticon-381-folder-5"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>	  <div class="col-xl-3 col-lg-6 col-md-4 col-sm-6">
                <div class="widget-stat card menu-card">
                    <div class="card-body  py-2 pl-3">
                        <div class="media">
                            <div class="media-body ">
                                <p class="mb-1">Certificate(Alt+8)</p>
                            </div>
                            <span class="ml-2">
                                <i class="flaticon-381-folder-5"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>	  <div class="col-xl-3 col-lg-6 col-md-4 col-sm-6">
                <div class="widget-stat card menu-card">
                    <div class="card-body  py-2 pl-3">
                        <div class="media">
                            <div class="media-body ">
                                <p class="mb-1">ICU/Emergency(F5)</p>
                            </div>
                            <span class="ml-2">
                                <i class="flaticon-381-folder-5"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>






        </div>  -->
        <!--**********************************
   mnu items  end
***********************************-->


    </div>
</div>

@include('frames/footer');

<script>
    $(document).keydown(function(event) {
        if (event.altKey && event.which === 65)
        {
            //Alt + A pressed!
            location.replace('{{route('patientSearch')}}')
            e.preventDefault();
        }
    });

    $(document).keydown(function(event) {
        if (event.altKey && event.which === 66)
        {
            //Alt + B pressed!
            location.replace('{{route('patientBooks')}}')
            e.preventDefault();
        }
    });

    $(document).keydown(function(event) {
        if (event.altKey && event.which === 67)
        {
            //Alt + C pressed!
            location.replace('{{route('appointmentList')}}')
            e.preventDefault();
        }
    });

    $(document).keydown(function(event) {
        if (event.altKey && event.which === 90)
        {
            //Alt + Z pressed!
            location.replace('{{route('visitlists')}}')
            e.preventDefault();
        }
    });

    $(document).keydown(function(event) {
        if (event.altKey && event.which === 68)
        {
            //Alt + D pressed!
            location.replace('{{route('ip-admission-list')}}')
            e.preventDefault();
        }
    });

    $(document).keydown(function(event) {
        if (event.altKey && event.which === 69)
        {
            //Alt + E pressed!
            location.replace('{{route('discharge-list')}}')
            e.preventDefault();
        }
    });

    $(document).keydown(function(event) {
        if (event.altKey && event.which === 70)
        {
            //Alt + F pressed!
            location.replace('{{route('userGroup')}}')
            e.preventDefault();
        }
    });

    $(document).keydown(function(event) {
        if (event.altKey && event.which === 71)
        {
            //Alt + G pressed!
            location.replace('{{route('createUser')}}')
            e.preventDefault();
        }
    });

    $(document).keydown(function(event) {
        if (event.altKey && event.which === 72)
        {
            //Alt + H pressed!
            location.replace('{{route('sectionOne')}}')
            e.preventDefault();
        }
    });

    $(document).keydown(function(event) {
        if (event.altKey && event.which === 73)
        {
            //Alt + I pressed!
            location.replace('{{route('sectionTwo')}}')
            e.preventDefault();
        }
    });

    $(document).keydown(function(event) {
        if (event.altKey && event.which === 74)
        {
            //Alt + J pressed!
            location.replace('{{route('sectionThree')}}')
            e.preventDefault();
        }
    });
    $(document).keydown(function(event) {
        if (event.altKey && event.which === 76)
        {
            //Alt + L pressed!
            location.replace('{{route('sectionFour')}}')
            e.preventDefault();
        }
    });
    $(document).keydown(function(event) {
        if (event.altKey && event.which === 77)
        {
            //Alt + M pressed!
            location.replace('{{route('sectionFive')}}')
            e.preventDefault();
        }
    });

    $(document).keydown(function(event) {
        if (event.altKey && event.which === 78)
        {
            //Alt + N pressed!
            location.replace('{{route('dtms_master')}}')
            e.preventDefault();
        }
    });

    $(document).keydown(function(event) {
        if (event.altKey && event.which === 79)
        {
            //Alt + O pressed!
            location.replace('{{route('prescription_master')}}')
            e.preventDefault();
        }
    });

    $(document).keydown(function(event) {
        if (event.altKey && event.which === 80)
        {
            //Alt + P pressed!
            location.replace('{{route('app_notification')}}')
            e.preventDefault();
        }
    });

    $(document).keydown(function(event) {
        if (event.altKey && event.which === 81)
        {
            //Alt + Q pressed!
            location.replace('{{route('product')}}')
            e.preventDefault();
        }
    });

    $(document).keydown(function(event) {
        if (event.altKey && event.which === 82)
        {
            //Alt + R pressed!
            location.replace('{{route('service-item-group')}}')
            e.preventDefault();
        }
    });

    $(document).keydown(function(event) {
        if (event.altKey && event.which === 83)
        {
            //Alt + S pressed!
            location.replace('{{route('service-item-master')}}')
            e.preventDefault();
        }
    });

    $(document).keydown(function(event) {
        if (event.altKey && event.which === 84)
        {
            //Alt + T pressed!
            location.replace('{{route('test-master')}}')
            e.preventDefault();
        }
    });






</script>
<script>
    $(document).keydown(function(event) {
        if (event.which === 113)
        {
            //F2 pressed!
            location.replace('{{route('patientRegistration')}}')
            e.preventDefault();
        }
    });
</script>
<script>

    function setBranch(branchId)
    {
        if(branchId>0)
        {
            url='{{route('changeBranch')}}';
            let ajaxval = { branch: branchId };
            $.ajax({
                type: "POST",
                url: url,
                data: ajaxval,
                success: function(result) {

                    if (result.status == 1) {
                        swal("Done", result.message, "success");

                     } else {
                        sweetAlert("Oops...", result.message, "error");
                    }
                },
            });

        }
    }
    </script>

