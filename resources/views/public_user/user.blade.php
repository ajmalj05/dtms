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
                    <h3 class="ls-n-20 line-height-1">Book Appointment</h3>
                </div>
            </div>
            <div class="row" id="top_elm">
                <div class="col-lg-8 offset-lg-0 col-sm-8 offset-sm-2 col-10 offset-1">

                    <div class="alert alert-danger" role="alert" id="error_area" style="display: none">

                      </div>

                      <div class="alert alert-success" role="alert" id="success_area" style="display: none">

                      </div>

                    <form class="appoint-form mb-3" name="appontment-online-frm" id="appontment-online-frm">
                        @csrf
                        <div class="input-group input-light">
                            {{-- <div class="row"> --}}

                            <div class="col-sm-12">
                                <h6 class="input-title">Registration Type</h6>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input regType" type="radio" name="regType" id="inlineRadio1" value="1" Checked>
                                    <label class="form-check-label" for="inlineRadio1">New Registration</label>
                                </div>
                                <div class="form-check form-check-inline">




                                    <input class="form-check-input regType" type="radio" name="regType" id="inlineRadio2" value="2">
                                    <label class="form-check-label" for="inlineRadio2">Old Registration</label>
                                </div>


                            </div>
                            {{-- </div>
                            <br>--}}
                            <div class="row">
                                <div class="col-sm-11" id="regNumberDiv" style="display:none">
                                    <h6 class="input-title">Registration Number<span class="required">*</span></h6>


                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" id="uhid_no" name="uhid_no" placeholder="Registration Number">
                                        <div class="input-group-append">
                                          <span class="input-group-text"  id="basic-addon2"><i class="fas fa-search"></i></span>
                                        </div>
                                      </div>






                                    <small id="uhid_no_error" class="form-text text-muted error"></small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <h6 class="input-title">Salutation<span class="required">*</span></h6>
                                    <select class="form-control" aria-label="Default select example" id="salutation" name="salutation">
                                        <option  value="" selected>Salutation</option>
                                        {{LoadCombo("salutation_master","id","salutation_name","",'where display_status=1 AND is_deleted=0',"order by id desc");}}
                                    </select>
                                    <small id="salutation_error" class="form-text text-muted error"></small>

                                </div>
                                <div class="col-sm-4">
                                    <h6 class="input-title">First Name<span class="required">*</span></h6>
                                    <input type="text" class="form-control" name="patient_name" id="patient_name" placeholder="First name">
                                    <small id="patient_name_error" class="form-text text-muted error"></small>
                                </div>
                                <div class="col-sm-4">
                                    <h6 class="input-title">Surname<span class="required">*</span></h6>
                                    <input type="text" class="form-control" name="sur_name" id="sur_name" placeholder="Last name">
                                    <small id="sur_name_error" class="form-text text-muted error"></small>
                                </div>



                                <div class="col-sm-4">
                                    <h6 class="input-title">Gender<span class="required">*</span></h6>
                                    <select class="form-control" id="gender" name="gender" aria-label="Default select example">
                                        <option value="">Select</option>
{{--                                        <option value="1">Male</option>--}}
{{--                                        <option value="2">Female</option>--}}
{{--                                        <option value="3">Other</option>--}}
                                        <option  value="m">Male</option>
                                        <option value='f'>Female</option>
                                        <option value='o'>Others</option>
                                    </select>
                                    <small id="gender_error" class="form-text text-muted error"></small>

                                </div>
                                <div class="col-sm-4">

                                    <h6 class="input-title">Mobile <span class="required">*</span></h6>
                                    <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="xxx-xxx-xxxx">
                                    <small id="mobile_number_error" class="form-text text-muted error"></small>
                                </div>

                                <div class="col-sm-4">
                                    <h6 class="input-title">Email</h6>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="example@gmail.com">
                                    <small id="email_error" class="form-text text-muted error"></small>
                                </div>

                                <div class="col-sm-4">
                                    <h6 class="input-title">Date of Birth<span class="required">*</span></h6>
{{--                                    <input type="text" id="dob" name="dob" class="form-control" onchange="calcAge(this.value)" placeholder="dd/mm/yy" min="1990-01-01" max="2030-12-31">--}}
                                    <input type="date" id="dob" name="dob" class="form-control" onchange="calcAge(this.value)" placeholder="dd/mm/yy" >
                                    <small id="dob_error" class="form-text text-muted error"></small>
                                </div>
                                <div class="col-sm-4">
                                    <h6 class="input-title">Age<span class="required"></span></h6>
                                    <input type="text" id="age" name="age" class="form-control" disabled>
                                </div>
                                <div class="col-sm-4">
                                    <h6 class="input-title">Departments<span class="required">*</span></h6>
                                    <select class="form-control" id="department" name="department" aria-label="Default select example">
                                        <option value="">Select</option>
                                        {{LoadCombo("departments","id","department_name","","where display_status=1  AND is_deleted=0","order by id desc");}}
                                    </select>
                                    <small id="department_error" class="form-text text-muted error"></small>
                                </div>

                                <div class="col-sm-4">
                                    <h6 class="input-title">Consultant Name<span class="required">*</span></h6>
                                    <select id="specialist" name="specialist" class="form-control" aria-label="Default select example" disabled data-none-selected-text="Select">
                                    </select>
                                    <small id="specialist_error" class="form-text text-muted error"></small>
                                </div>
                                <div class="col-sm-4">
                                    <h6 class="input-title">Disease type<span class="required"></span></h6>
                                    <select class="form-control" aria-label="Default select example">
                                        <option value="">Select</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <h6 class="input-title">Branch <span class="required">*</span></h6>
                                    <select class="form-control" id="branch" name="branch" aria-label="Default select example">
                                        <option value="">Select</option>
                                        {{LoadCombo("branch_master","branch_id","branch_name","","","order by branch_id desc");}}
                                    </select>
                                    <small id="branch_error" class="form-text text-muted error"></small>
                                </div>
                                <div class="col-sm-4">
                                    <h6 class="input-title">Appointment Date<span class="required">*</span></h6>
                                    <input type="date" class="form-control" id="appointment_date" name="appointment_date" placeholder="dd/mm/yy">
                                    <small id="appointment_date_error" class="form-text text-muted error"></small>
                                </div>
                                <div class="col-sm-4">
                                    <h6 class="input-title">Appointment time<span class="required">*</span></h6>
                                    <input type="time" class="form-control" id="appointment_time" name="appointment_time" placeholder="dd/mm/yy">
                                    <small id="appointment_time_error" class="form-text text-muted error"></small>
                                </div>
                                <input type="hidden" name="pid" id="pid" value="0">
                                <input type="hidden" name="appointmentid" id="appointmentid" value="">
                            </div>
                            <br>
                            <br>
                            <br>
                            <div class="row" style="padding-top:10px">
                                {{--                                <div class="col-sm-12">--}}
                                {{--                                    <script src="https://www.google.com/recaptcha/api.js"--}}
                                {{--                                            async defer></script>--}}
                                {{--                                    <div class="g-recaptcha" id="feedback-recaptcha"--}}
                                {{--                                         data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}">--}}
                                {{--                                    </div>--}}

                                {{--                                </div>--}}
                                <div class="col-sm-12">

                                    <div class="form-group{{ $errors->has('CaptchaCode') ? ' has-error' : '' }}">




                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-secondary-color btn-form d-flex mr-auto ml-auto mb-1" onclick="saveBookAppointment(1,event)">
                                        <span>Book Appointment</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="col-lg-4 offset-lg-0 col-sm-8 offset-sm-2 col-10 offset-1">
                    <div class="image-box">
                        <figure>
                            <img src="online_assets/images/doctors/doctor3-7.jpg" class="w-100" alt="Doctor" width="370" height="407">
                        </figure>
                        <!-- <div class="box-content box-doctor">
                            <h4 class="b<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{asset('/js/jquery.toaster.js')}}"></script>ox-title mb-0">Dr. George Brown</h4>
                            <div class="ratings-container mb-0">
                                <div class="ratings">
                                    <div class="ratings-val" style="width: 100%;"></div>
                                </div>
                                <span>4.8/5.0</span>
                            </div>
                            <span class="box-desc mb-0 ls-0">
                                You'll be seeing Dr. George tomorrow at 9:00 PM CDT
                            </span>
                        </div> -->
                        <!-- <div class="box-content box-content-clock mt-1">
                            <h3 class="box-title m-b-1"><i class="far fa-clock"></i> 5:36</h3>
                            <span class="box-desc mb-0 ls-0">
                                We're holding your appointment while you complete your booking.
                            </span>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
        <!------------------------------------------------
        content - end
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
    $(function () {

        // $('#dob').datepicker({
        //         autoclose: true,
        //         endDate: '+0d',
        //         format: 'dd-mm-yyyy'
        // });
        // $('#appointment_date').datepicker({
        //         autoclose: true,
        //         endDate: '+0d',
        //         format: 'dd-mm-yyyy'
        // });

        $("input[name=regType]:radio").click(function () {
            if ($('input[name=regType]:checked').val() == "1") {
                $('#salutation,#department,#patient_name,#sur_name,#gender,#mobile_number,#email,#dob,#branch,#appointment_date,#appointment_time').attr('readonly', false);

                $('#regNumberDiv').hide();

            } else if ($('input[name=regType]:checked').val() == "2") {
                $('#regNumberDiv').show();


            }
        });
    });
</script>


<!-- Main JS File -->
<script src="online_assets/js/main.min.js"></script>
<script>

    $(function () {


        $("input[name=regType]:radio").click(function () {
            if ($('input[name=regType]:checked').val() == "1") {
                $("pid").val(0);// make regn id 0
                $('#regNumberDiv').hide();

            } else if ($('input[name=regType]:checked').val() == "2") {

                $('#regNumberDiv').show();


            }
        });
    });

    function calcAge(dob){
        dob = new Date(dob);
        var today = new Date();
        var age = Math.floor((today-dob) / (365.25  * 24 * 60 * 60 * 1000));
        $('#age').val(age);
    }

    var App = {
        initialize: function() {
            $('#department').on('change', App.showConsultantList);
            // $('#uhid_no').on('change', App.showoldRegistrationList);
            $("#basic-addon2").on('click', App.showoldRegistrationList);
        },
        showConsultantList : function() {
            var department_id = $('#department').val();
            $.ajax({
                url: "{{ route('consultant-list') }}",
                type: 'GET',
                data : {
                    department_id : department_id,
                },
                success : function(response) {
                    $("#specialist").html('<option  value="" selected>Select</option>');
                    $.each(response.data, function(key, value) {
                        $("#specialist").append('<option value=' + value.id + '>' + value.specialist_name + '</option>');
                    });

                    $("#specialist").attr('disabled', false);
                    $("#specialist").selectpicker('refresh');;
                },
            });
        },
        showoldRegistrationList : function() {
            var uhid_no = $('#uhid_no').val();
            // var saturation_id = $('#salutation').val();
            // var department_id = $('#department').val();
            // var patientName = $('#patient_name').val();
            // var surName = $('#sur_name').val();
            // var gender = $('#gender').val();
            // var age = $('#age').val();
            // var mobileNumber = $('#mobile_number').val();
            // var email = $('#email').val();
            // var dob = $('#dob').val();
            // var specialist = $('#specialist').val();
            // var branch = $('#branch').val();
            // var appointmentDate = $('#appointment_date').val();
            // var appointmentTime = $('#appointment_time').val();

            $.ajax({
                url: "{{ route('get-old-registration-list') }}",
                type: 'POST',
                data : {
                    uhid_no : uhid_no,
                    // department_id : department_id,
                    // saturation_id : saturation_id,
                    // patientName : patientName,
                    // surName : surName,
                    // gender : gender,
                    // age : age,
                    // mobileNumber : mobileNumber,
                    // email : email,
                    // dob : dob,
                    // specialist : specialist,
                    // branch : branch,
                    // appointmentDate : appointmentDate,
                    // appointmentTime : appointmentTime,
                },
                success : function(response) {



                    if (response.status == 'false') {
                        $('#salutation,#department,#patient_name,#sur_name,#gender,#mobile_number,#email,#dob,#branch,#appointment_date,#appointment_time').val('');
                        $("#error_area").html(response.message);
                        $("#error_area").show();

                       // sweetAlert("Oops...", response.message, "error");

                        $('#salutation,#department,#patient_name,#sur_name,#gender,#mobile_number,#dob,#branch,#appointment_date,#appointment_time').attr('readonly', false);
                        var form = $('#appontment-online-frm')[0];
                       // document.getElementById("appontment-online-frm").reset();
                    } else {
                        $("#error_area").html('');
                        $("#error_area").hide();
                        $('#salutation,#department,#patient_name,#sur_name,#gender,#mobile_number,#dob,#branch,#email').attr('readonly', true);
                        // $.each(response.data, function(key, value) {
                        var dob = new Date(response.data.dob);
                        var today = new Date();
                        var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
                            $('#specialist').val(response.data.specialist_name);
                            $('#salutation').val(response.data.salutation_id);
                            $('#department').val(response.data.department_id);
                            $('#branch').val(response.data.branch_id);
                            $('#patient_name').val(response.data.name);
                            $('#mobile_number').val(response.data.mobile_number);
                            $('#sur_name').val(response.data.last_name);
                            // $('#gender').val(response.data.gender);
                        $("#gender option[value="+response.data.gender+"]").attr("selected","selected");

                        $('#age').val(age);
                            $('#email').val(response.data.email);
                            $('#dob').val(response.data.dob);
                            $("#pid").val(response.data.id);// add pid
                        // });
                    }
                },
            });
        },
    };
    App.initialize();

    function saveBookAppointment(crude=1,e)
    {
        $("[id*='_error']").text('');
        e.preventDefault();
        var form = $('#appontment-online-frm')[0];
        var formData = new FormData(form);
        formData.append('crude', crude);
        url = "{{ url('save-book-appointment') }}";


        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(result){
                if (result.status == 1) {
                  //  swal("Done", result.message, "success");
                        $("#error_area").hide();
                        $("#success_area").html(result.message);
                        $("#success_area").show();
                        $('html, body').animate({
                        scrollTop: $("#top_elm").offset().top
                        }, );

                    var form = $('#appontment-online-frm')[0];
                    document.getElementById("appontment-online-frm").reset();
                    $('#salutation,#department,#patient_name,#sur_name,#gender,#mobileNumber,#email,#dob,#branch,#appointment_date,#appointment_time').val('');


                }
                else {
                    $("#success_area").hide();
                        $("#error_area").html(result.message);
                        $("#error_area").show();
                        $('html, body').animate({
                        scrollTop: $("#top_elm").offset().top
                        },);
                    sweetAlert("Oops...", result.message, "error");
                }


            },
            error: function(result,jqXHR, textStatus, errorThrown){
                if( result.status === 422 ) {
                    result=result.responseJSON;
                    var error=result.errors;
                    $.each(error, function (key, val) {
                        $("#" + key + "_error").text(val[0]);
                    });
                }


            }
        });
    }

$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});

</script>


</body>
</html>
