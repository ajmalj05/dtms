<link href='date_picker/css/bootstrap-datepicker.min.css' rel='stylesheet' type='text/css'>
<style>
    label.btn.btn-outline-success {
        border-radius: 25px !important;
    }
    .text-label
    {
        color: #232527 !important;
    }
    .form-control
    {
        color: #2b2e30 !important;
    }
    .hidedata{
        display:none;
    }

    .readonly .dropdown-menu{
        display: none;
        pointer-events: none!important;
    }

    div .readonly{
        background-color: #e9ecef !important
    }
</style>
<script type="text/javascript" src="webcam/webcamjs/webcam.min.js"></script>

<div class="content-body">
    <div class="container-fluid pt-2">




        <form name="frm" id="frm" action="{{URL('saveImages')}}" enctype="multipart/form-data" method="POST">
            <div class="row">
                <div class="row">

                    <div class="col-xl-12 col-xxl-12 col-lg-12 col-sm-12">
                        <div class="card card-sm">
                            <div class="card-header">
                               <h4 class="card-title">
                                    <a href="{{route('appointmentList')}}">
                                        <i class="fa fa-arrow-left cursorp"></i>
                                    </a>
                                    &nbsp; &nbsp; &nbsp; &nbsp;
                                    Patient Details
                                </h4>
                                <span style="flaot:right"><i class='fa fa-search' ondblclick="SearchPatients();" data-toggle="tooltip" data-placement="bottom" title="Double click here to search patients"></i></span>

                            </div>
                            <div class="card-body">

                                <section>
                                    <div class="row">
                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <input type="hidden" name="consultant_id" id="consultant_id" value="{{ isset($patient_data)?$patient_data->specialist_id:'' }}">
                                            <div class="form-group ">
                                                <label>Salutation  </label>
                                                <select id="a_salutation_id" name="a_salutation_id" class="form-control">
                                                <option  value="" selected>Choose...</option>
                                                {{LoadCombo("salutation_master","id","salutation_name",isset($patient_data)?$patient_data->salutation_id:'','where display_status=1 AND is_deleted=0',"order by id desc");}}
                                            </select>
                                            <small id="salutation_id_error" class="form-text text-muted error"></small>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Patient Name <span class="required">*</span></label>
                                                <input type="text" name="a_patientname" id="a_patientname" class="form-control" value="{{ old('name',isset($patient_data) ? $patient_data->patientname : '') }}" placeholder="" required>
                                                <small id="a_patientname_error" class="form-text text-muted error"></small>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Surname   </label>
                                                <input type="text" name="a_last_name" id="a_last_name" value="{{ old('last_name',isset($patient_data) ? $patient_data->last_name : '') }}" class="form-control" placeholder="" required>
                                                <small id="last_name_error" class="form-text text-muted error"></small>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group ">
                                          <label>Gender   </label>
                                                <select id="a_gender"  name="a_gender" class="form-control">
                                                <option  value=""  >Choose...</option>
                                                    <?php
                                                        if(isset($patient_data))
                                                        {
                                                            ?>
                                                            <option  value="m" <?php if(str_contains($patient_data->gender, 'm') ) echo "selected" ?>>Male</option>
                                                             <option value='f' <?php if( str_contains($patient_data->gender, 'f') ) echo "selected" ?>>Female</option>
                                                             <option value='o' <?php if( str_contains($patient_data->gender, 'o') ) echo "selected" ?>>Others</option>
                                                        <?php
                                                        }
                                                        else{
                                                            ?>
                                                             <option  value="m">Male</option>
                                                             <option value='f'>Female</option>
                                                             <option value='o'>Others</option>
                                                            <?php
                                                        }
                                                     ?>




                                            </select>
                                            <small id="gender_error" class="form-text text-muted error"></small>


                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">DOB</label>
                                                <input type="text" name="a_dob" id="a_dob" value="{{ old('a_dob',(isset($patient_data) && isset($patient_data->dob))  ? date('d-m-Y', strtotime($patient_data->dob))  : '') }}" onchange="calcAge(this.value)" class="form-control" placeholder="" >
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Age  </label>
                                                <input type="text" readonly name="a_age" value="{{ old('a_dob',(isset($patient_data) && isset($patient_data->dob))  ? \Carbon\Carbon::parse($patient_data->dob)->diff(\Carbon\Carbon::now())->format('%y') : '') }}" id="a_age" class="form-control" placeholder="" >
                                                <small id="age_error" class="form-text text-muted error"></small>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Mobile Number <span class="required">*</span></label>


                                                <input type="text" name="a_mobile_number" id="a_mobile_number"  class="form-control" placeholder=""  onKeyPress="return onlyNumbers(event)" value="{{ old('a_mobile_number',isset($patient_data) ? $patient_data->mobile_number : '') }}">
                                                <small id="a_mobile_number_error" class="form-text text-muted error"></small>
                                                </div>
                                            </div>

                                        <div class="col-xl-3 col-md-3 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Email Address</label>
                                                    <input type="text" class="form-control" name="a_email_address" id="a_email_address" value="{{ old('a_email_address',isset($patient_data) ? $patient_data->email : '') }}" >


                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Department</label>

                                                    <select id="a_department_id" name="a_department_id" class="form-control" onchange="getConsultantList(this.value)">
                                                        <option  value="" selected>Choose...</option>
                                                        {{LoadCombo("departments","id","department_name",isset($patient_data)?$patient_data->department_id:'',"where display_status=1  AND is_deleted=0","order by id desc");}}
                                                    </select>



                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Consultant Name<span class="required">*</span></label>
                                                <select id="a_specialist_id" name="a_specialist_id" class="form-control" disabled data-none-selected-text="Choose Consultant">
                                                    <?php
                                                    if(isset($patient_data) && $patient_data->specialist_id)
                                                    {

                                                        {{LoadCombo("specialist_master","id","specialist_name",isset($patient_data)?$patient_data->specialist_id:'','where display_status=1 AND is_deleted=0',"order by id desc");}}
                                                    }
                                                    ?>
{{--                                                    <option  value="" selected>Choose...</option>--}}
{{--                                                    {{LoadCombo("specialist_master","id","specialist_name",isset($patient_data)?$patient_data->specialist_id:'','where display_status=1 AND is_deleted=0',"order by id desc");}}--}}
                                                </select>

                                                <small id="a_specialist_id_error" class="form-text text-muted error"></small>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Appointment Date<span class="required">*</span></label>
                                                    <input type="text" class="form-control" name="a_appointment_date" id="a_appointment_date"  value="{{ old('a_appointment_date',isset($patient_data) ? date('d-m-Y', strtotime($patient_data->appointment_date))  : date('d-m-Y',strtotime("+1 day")) ) }}"  >
{{--                                                    <input type="text" class="form-control" name="a_appointment_date" id="a_appointment_date"  value="{{ old('a_appointment_date',isset($patient_data) ? date('d-m-Y', strtotime($patient_data->appointment_date))  : date('d-m-Y',strtotime("0 day")) ) }}"  >--}}
                                                <small id="a_appointment_date_error" class="form-text text-muted error"></small>

                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Appointment Time</label>
                                                    <input type="time" class="form-control" name="a_appointment_time" id="a_appointment_time" value="{{ old('a_appointment_time',isset($patient_data) ? $patient_data->appointment_time  : "" ) }}">


                                            </div>
                                        </div>

                                        <input type="hidden" name="a_pid" id="a_pid" value="{{isset($patient_data) ? $patient_data->patient_id  : ""}}">
                                        <input type="hidden" name="a_appointmentid" id="a_appointmentid" value="{{isset($patient_data) ? $patient_data->id  : ""}}">

                                        @if(isset($patient_data))
                                            <div class="col-xl-3 col-md-6 mb-2">
                                                <div class="d-flex flex-wrap align-content-center h-100">
                                                    <button type="button" class="btn btn-sm btn-primary mt-1" onclick="saveAppointment(2)">Update</button>
                                                </div>
                                            </div>
                                        @else

                                            <div class="col-xl-1 col-md-6 mb-2">
                                                <div class="d-flex flex-wrap align-content-center h-100">
                                                    <button type="button" class="btn btn-sm btn-primary mt-1" onclick="saveAppointment(1)">Save</button>
                                                </div>
                                            </div>

                                            <div class="col-xl-3 col-md-6 mb-2">
                                                <div class="d-flex flex-wrap align-content-center h-100">
                                                    <button type="button" class="btn btn-sm btn-primary mt-1" onclick="clearform()">Clear</button>
                                                </div>
                                            </div>

                                        @endif

                                    </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@include('frames/footer');
@include('modals/searchpatient_modal',['title'=>'Search Patient','data'=>'fgh'])

<link rel="stylesheet" href="{{asset('/vendor/select2/css/select2.min.css')}}">
<link href="{{asset('/vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
<script src="{{asset('date_picker/js/bootstrap-datepicker.min.js')}}" type='text/javascript'></script>
<script src="{{asset('./vendor/select2/js/select2.full.min.js')}}"></script>
<script src="./js/plugins-init/select2-init.js"></script>
    <script language="JavaScript">


    $(document).ready(function(){
        $('#a_dob').datepicker({
            autoclose: true,
            endDate: '+0d',
            format: 'dd-mm-yyyy'
        });

        $('#a_appointment_date').datepicker({
            autoclose: true,
            // endDate: '+10d',
            startDate: '+0d',
            format: 'dd-mm-yyyy'

        });


    });


	 // Configure a few settings and attach camera




    function calcAge(dob){
        // console.log(dob);

        var dateParts = dob.split("-");
        var dateObject = new Date(+dateParts[2], dateParts[1] - 1, +dateParts[0]);


        dob = new Date(dateObject);
        var today = new Date();
        var age = Math.floor((today-dob) / (365.25  * 24 * 60 * 60 * 1000));
        $('#a_age').val(age);
    }

    function SearchPatients(){
        $('#addModal').modal();
    }
    function saveAppointment(crude=1)
    {

        var form = $('#frm')[0];
        var formData = new FormData(form);
        formData.append('crude', crude);
        url='{{route('saveAppointment')}}';

        $.ajax({
				 type: "POST",
				 url: url,
				 data: formData,
				 processData: false,
				 contentType: false,
				 success: function(result){
                    if (result.status == 1) {
                        // console.log()
                        swal("Done", result.message, "success");
                        var form = $('#frm')[0];
                        document.getElementById("frm").reset();
                        $('#a_salutation_id, #a_gender,#a_department_id,#a_specialist_id').val('').selectpicker('refresh');
                    }
                    else if (result.status == 2) {
                        swal("Done", result.message, "success");
                        location.reload();
                        document.getElementById("frm").reset();
                        $('#a_salutation_id, #a_gender,#a_department_id,#a_specialist_id').val('').selectpicker('refresh');

                    }
                    else {
                        sweetAlert("Oops...", result.message, "success");
                    }

                     },
                     error: function(result,jqXHR, textStatus, errorThrown){
                         if( result.status === 422 ) {
                            result=result.responseJSON;
                            var error=result.errors;
                             $.each(error, function (key, val) {
                            // console.log(key);
                            $("#" + key + "_error").text("This field is required");
                    });
                }

                }
			 });
    }

    function clearform(){
        var form = $('#frm')[0];
        document.getElementById("frm").reset();

        $('#a_salutation_id, #a_gender').parent().removeClass('readonly');
        $('#a_salutation_id, #a_gender,#a_department_id,#a_specialist_id').val('').selectpicker('refresh');
         $('#a_patientname,#a_last_name,#a_dob,#a_mobile_number,#a_email_address').removeAttr('readonly');
         $('#a_appointment_date').val('');

    }

    // output from search patient modal
    $('#searchpatient_MDT tbody').on('click', 'tr', function() {
         var data = table.row($(this)).data();
        $(this).css('background-color','black');
         console.log(data);
         $('#a_salutation_id').val(data.salutation_id).selectpicker('refresh');
         $('#a_patientname').val(data.name);
         $('#a_last_name').val(data.last_name);
         $('#a_gender').val(data.gender?.trim()).selectpicker('refresh');

         $('#a_dob').val(data.dob);
         $('#a_age').val(data.age);
         $('#a_mobile_number').val(data.mobile_number);
         $('#a_email_address').val(data.email);
         $('#a_pid').val(data.id);

         $('#a_salutation_id, #a_gender').parent().addClass('readonly');
         $('#a_patientname,#a_last_name,#a_dob,#a_mobile_number,#a_email_address').attr('readonly', 'readonly');
        //  $('#a_specialist_id').val(data.name);


         $('#addModal').modal('toggle');
    });

    {{--var App = {--}}
    {{--    initialize: function() {--}}
    {{--        $('#a_department_id').on('change', App.showConsultantList);--}}
    {{--    },--}}
    {{--    showConsultantList : function() {--}}
    {{--        var department_id = $('#a_department_id').val();--}}
    {{--        $.ajax({--}}
    {{--            url: "{{ route('get-consultant-list') }}",--}}
    {{--            type: 'POST',--}}
    {{--            data : {--}}
    {{--                department_id : department_id,--}}
    {{--            },--}}
    {{--            success : function(response) {--}}
    {{--                $("#a_specialist_id").html('<option  value="" selected>Choose a consultant</option>');--}}
    {{--                $.each(response.data, function(key, value) {--}}
    {{--                    $("#a_specialist_id").append('<option value=' + value.id + '>' + value.specialist_name + '</option>');--}}
    {{--                });--}}

    {{--                $("#a_specialist_id").attr('disabled', false);--}}
    {{--                $("#a_specialist_id").selectpicker('refresh');;--}}
    {{--            },--}}
    {{--        });--}}
    {{--    },--}}
    {{--};--}}
    {{--App.initialize();--}}

    function getConsultantList(department_id) {
        var consultant_id=$("#consultant_id").val();
        var selected='';
        url = '{{ route('get-consultant-list') }}';
        $.ajax({
            type: "POST",
            url: url,
            data: {
                'department_id':department_id,
            },
            success: function(response) {
                $("#specialist_id").html('<option  value="" selected>Select</option>');
                $.each(response.data, function(key, value) {
                    if (consultant_id == value.id){
                        var selected='selected';
                    }
                    else{
                        selected='';
                    }
                    $("#a_specialist_id").append('<option selected value=' + value.id + '>' + value.specialist_name + '</option>');
                });

                $("#a_specialist_id").attr('disabled', false);
                $("#a_specialist_id").selectpicker('refresh');

            }
        });

    }
	</script>

