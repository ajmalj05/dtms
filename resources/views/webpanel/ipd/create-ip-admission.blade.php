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
                                    <a href="{{route('ip-admission-list')}}">
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
                                            <div class="form-group ">
                                                <label>Salutation  </label>
                                                <select id="a_salutation_id" name="a_salutation_id" class="form-control" disabled>
                                                    <option  value="" selected>Choose...</option>
                                                    {{LoadCombo("salutation_master","id","salutation_name",isset($admission_data)?$admission_data->salutation_id:'','where display_status=1 AND is_deleted=0',"order by id desc");}}
                                                </select>
                                                <small id="salutation_id_error" class="form-text text-muted error"></small>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Patient Name <span class="required">*</span></label>
                                                <input type="text" name="a_patientname"   id="a_patientname" readonly class="form-control" value="{{ old('name',isset($admission_data) ? $admission_data->name : '') }}" placeholder="" required>
                                                <small id="a_patientname_error" class="form-text text-muted error"></small>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Surname   </label>
                                                <input type="text" name="a_last_name" id="a_last_name" readonly value="{{ old('last_name',isset($admission_data) ? $admission_data->last_name : '') }}" class="form-control" placeholder="" required>
                                                <small id="last_name_error" class="form-text text-muted error"></small>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group ">
                                                <label>Gender   </label>
                                                <select id="a_gender"  name="a_gender" class="form-control" disabled>
                                                    <option  value=""  >Choose...</option>
                                                    <?php
                                                    if(isset($admission_data))
                                                    {
                                                    ?>
                                                    <option  value="m" <?php if(str_contains($admission_data->gender, 'm') ) echo "selected" ?>>Male</option>
                                                    <option value='f' <?php if( str_contains($admission_data->gender, 'f') ) echo "selected" ?>>Female</option>
                                                    <option value='o' <?php if( str_contains($admission_data->gender, 'o') ) echo "selected" ?>>Others</option>
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
                                                <input type="text" name="a_dob" id="a_dob" readonly value="{{ old('a_dob',(isset($admission_data) && isset($admission_data->dob))  ? date('d-m-Y', strtotime($admission_data->dob))  : '') }}" onchange="calcAge(this.value)" class="form-control" placeholder="" >
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Age  </label>
                                                <input type="text" readonly name="a_age" value="{{ old('a_dob',(isset($admission_data) && isset($admission_data->dob))  ? \Carbon\Carbon::parse($admission_data->dob)->diff(\Carbon\Carbon::now())->format('%y') : '') }}" id="a_age" class="form-control" placeholder="" >
                                                <small id="age_error" class="form-text text-muted error"></small>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Mobile Number</label>


                                                <input type="text" name="a_mobile_number" id="a_mobile_number" readonly  class="form-control" placeholder=""  value="{{ old('a_mobile_number',isset($admission_data) ? $admission_data->mobile_number : '') }}">
                                                <small id="a_mobile_number_error" class="form-text text-muted error"></small>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Email Address</label>
                                                <input type="text" class="form-control" name="a_email_address" readonly id="a_email_address" value="{{ old('a_email_address',isset($admission_data) ? $admission_data->email : '') }}" >


                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Department<span class="required">*</span></label>
                                                <select id="ip_department_name" name="ip_department_name" class="form-control">
                                                    <option  value="" selected>Choose...</option>
                                                    {{LoadCombo("departments","id","department_name",isset($admission_data)?$admission_data->department_id:'',"where display_status=1  AND is_deleted=0","order by id desc");}}
                                                </select>
                                                <small id="ip_department_name_error" class="form-text text-muted error"></small>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Consultant Name</label>
                                                <select id="ip_specialist_name" name="ip_specialist_name" class="form-control" disabled data-none-selected-text="Choose Consultant">
                                                    @if(isset($admission_data))
                                                    {
                                                        {{LoadCombo("specialist_master","id","specialist_name",isset($admission_data)?$admission_data->specialist_id:'',"where display_status=1  AND is_deleted=0","order by id desc");}}
                                                    }
                                                    @endif
                                                </select>
                                                <small id="ip_specialist_name_error" class="form-text text-muted error"></small>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Admit Date<span class="required">*</span></label>
                                                <input type="text" class="form-control" name="ip_admit_date" id="ip_admit_date"  value="{{ old('ip_admit_date',isset($admission_data) ? date('d-m-Y', strtotime($admission_data->admission_date))  : date('d-m-Y',strtotime("+1 day")) ) }}"  >
{{--                                                <input type="text" class="form-control" name="ip_admit_date" id="ip_admit_date"  value="{{ old('ip_admit_date',isset($admission_data) ? date('d-m-Y', strtotime($admission_data->admission_date))  : date('d-m-Y',strtotime("0 day")) ) }}"  >--}}
                                                <small id="ip_admit_date_error" class="form-text text-muted error"></small>

                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Ward Number<span class="required">*</span></label>
                                                <input type="text" name="ip_ward_number" id="ip_ward_number" class="form-control" value="{{ old('ip_ward_number',isset($admission_data) ? $admission_data->ward_number : '') }}" placeholder="" required>
                                                <small id="ip_ward_number_error" class="form-text text-muted error"></small>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Bed Number<span class="required">*</span></label>
                                                <input type="text" name="ip_bed_number" id="ip_bed_number" class="form-control" value="{{ old('ip_bed_number',isset($admission_data) ? $admission_data->bed_number : '') }}" placeholder="" required>
                                                <small id="ip_bed_number_error" class="form-text text-muted error"></small>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Policy Number</label>
                                                <input type="text" name="ip_policy_number" id="ip_policy_number" class="form-control" value="{{ old('ip_policy_number',isset($admission_data) ? $admission_data->policy_number : '') }}" placeholder="" required>
                                                <small id="ip_policy_number_error" class="form-text text-muted error"></small>
                                            </div>
                                        </div>

                                        <input type="hidden" name="a_pid" id="a_pid" value="{{isset($admission_data) ? $admission_data->patient_id  : ""}}">
                                        <input type="hidden" name="ip_admissionid" id="ip_admissionid" value="{{isset($admission_data) ? $admission_data->id  : ""}}">


                                        @if(isset($admission_data))
                                            <div class="col-xl-3 col-md-6 mb-2">
                                                <div class="d-flex flex-wrap align-content-center h-100">
                                                    <button type="button" class="btn btn-sm btn-primary mt-1" onclick="saveIPAdmission(2)">Update</button>
                                                </div>
                                            </div>
                                        @else

                                            <div class="col-xl-1 col-md-6 mb-2">
                                                <div class="d-flex flex-wrap align-content-center h-100">
                                                    <button type="button" class="btn btn-sm btn-primary mt-1" onclick="saveIPAdmission(1)">Save</button>
                                                </div>
                                            </div>

                                            <div class="col-xl-3 col-md-6 mb-2" style="max-width: 5%;">
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

<link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
<link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
<script src='date_picker/js/bootstrap-datepicker.min.js' type='text/javascript'></script>
<script src="./vendor/select2/js/select2.full.min.js"></script>
<script src="./js/plugins-init/select2-init.js"></script>
    <script language="JavaScript">


    $(document).ready(function(){
        // $('#a_dob').datepicker({
        //     autoclose: true,
        //     endDate: '+0d',
        //     format: 'dd-mm-yyyy'
        // });
        $('#ip_admit_date').datepicker({
            autoclose: true,
            // endDate: '+10d',
            startDate: '+0d',
            format: 'dd-mm-yyyy'

        });
        $('#ip_discharge_date').datepicker({
            autoclose: true,
            // endDate: '+10d',
            startDate: '+0d',
            format: 'dd-mm-yyyy'

        });


    });


    function SearchPatients(){
        $('#addModal').modal();
    }

    function saveIPAdmission(crude=1)
    {
        $("[id*='_error']").text('');
        var form = $('#frm')[0];
        var formData = new FormData(form);
        formData.append('crude', crude);
        url='{{route('save-ip-admission')}}';

        $.ajax({
				 type: "POST",
				 url: url,
				 data: formData,
				 processData: false,
				 contentType: false,
				 success: function(result){
                    if (result.status == 1) {
                        // swal("Done", result.message, "success");
                        swal({
                            title: "Done",
                            text: result.message,
                            type: "success"
                        }
                        ).then((swalStatus) => {
                            if (swalStatus.value) {
                                window.location.href = "{{url('ip-admission-list')}}";

                            }
                        });
                        var form = $('#frm')[0];
                        document.getElementById("frm").reset();
                        $('#ip_department_name, #ip_specialist_name,#ip_ward_number,#ip_bed_number,#ip_policy_number').val('').selectpicker('refresh');


                    }
                    else if (result.status == 2) {
                        swal("Done", result.message, "success");
                        // location.reload();
                        document.getElementById("frm").reset();
                        $('#ip_department_name, #ip_specialist_name,#ip_ward_number,#ip_bed_number,#ip_policy_number').val('').selectpicker('refresh');
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
        $('#ip_department_name, #ip_specialist_name').parent().removeClass('readonly');
        $('#ip_department_name, #ip_specialist_name').val('').selectpicker('refresh');
         $('#ip_ward_number,#ip_bed_number,#ip_policy_number').removeAttr('readonly');
         $('#ip_admit_date').val('');
         $('#ip_discharge_date').val('');
    }

    // output from search patient modal
    $('#searchpatient_MDT tbody').on('click', 'tr', function() {
         var data = table.row($(this)).data();
        var dob = new Date(data.dob);
        var today = new Date();
        var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
        var month = dob.getMonth() + 1;
        var dobDate = ('0' + dob.getDate()).slice(-2) + "-" + (month.toString().length > 1 ? month : "0" + month) + "-" +   dob.getFullYear();
        $(this).css('background-color','black');
         console.log(data);
         $('#a_salutation_id').val(data.salutation_id).selectpicker('refresh');
         $('#a_patientname').val(data.name);
         $('#a_last_name').val(data.last_name);
         $('#a_gender').val(data.gender?.trim()).selectpicker('refresh');

         $('#a_dob').val(dobDate);
         $('#a_age').val(age);
         $('#a_mobile_number').val(data.mobile_number);
         $('#a_email_address').val(data.email);
         $('#a_pid').val(data.id);

         $('#a_salutation_id, #a_gender').parent().addClass('readonly');
         $('#a_patientname,#a_last_name,#a_dob,#a_mobile_number,#a_email_address').attr('readonly', 'readonly');
        //  $('#a_specialist_id').val(data.name);


         $('#addModal').modal('toggle');
    });

    var App = {
        initialize: function() {
            $('#ip_department_name').on('change', App.showSpecialistList);
        },
        showSpecialistList : function() {
            var department_id = $('#ip_department_name').val();
            $.ajax({
                url: "{{ route('get-ip-specialist-list') }}",
                type: 'GET',
                data : {
                    department_id : department_id,
                },
                success : function(response) {
                    $("#ip_specialist_name").html('<option  value="" selected>Choose a consultant</option>');
                    $.each(response.data, function(key, value) {
                        $("#ip_specialist_name").append('<option value=' + value.id + '>' + value.specialist_name + '</option>');
                    });

                    $("#ip_specialist_name").attr('disabled', false);
                    $("#ip_specialist_name").selectpicker('refresh');
                },
            });
        },
    };
    App.initialize();
	</script>

