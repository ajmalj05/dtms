<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }
</style>
<div id="more-details-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

            <div class="modal-content">
                <div class="modal-header">
                    <div class="card-title">
                        <p>{{$title}}</p>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                <div class="main-content" id="htmlContent">

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <!-- <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <h4>Payment Gateway</h4>
                    <input type="hidden" id="path" value="{{ url('/') }}">
                </div>
            </div>
        </div> -->
        <!-- end page title -->

{{--                <div class="card card-sm">--}}
{{--                    <div class="text-center p-3 ">--}}

                        <form name="update-patient-info" id="update-patient-info" action="#" enctype="multipart/form-data" method="POST">
                            <div class="row">
                                <div class="col-xl-12 col-xxl-12">
                                    <div class="">
                                        {{-- <div class="card-header">
                                            <h4 class="card-title">Patient Registration Form</h4>
                                        </div> --}}
                                        <div class="">

                                            <div>
                                                <section>


                                                    <div class="row">
                                                        <div class="col-xl-3 col-md-6 mb-2">
                                                            <div class="form-group">

                                                                <label class="text-label">UHID No.</label>
                                                                <input type="hidden" name="patient_id" id="patient_id" class="form-control" placeholder=""  readonly
                                                                       value="{{ old('patient_id',isset($patient_data) ? $patient_data->id : '') }}"


                                                                >
                                                                <input type="text" name="uhid" id="uhid" class="form-control" placeholder=""  readonly

                                                                       value="{{isset($uhidNo) ? $uhidNo :''}}"
                                                                >
                                                            <!-- value="{{ old('uhid',isset($patient_data) ? $patient_data->id : '') }}" -->
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-3 col-md-6 mb-2">
                                                            <div class="form-group">
                                                                <label class="text-label">Admission-Date</label>
                                                                <input type="text" name="admission_date" id="admission_date" value="{{ old('admission_date',isset($patient_data) ?date('d/m/Y', strtotime($patient_data->admission_date))  : '') }}" onchange="calcAge(this.value)" class="form-control" placeholder="" >
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-3 col-md-6 mb-2">
                                                            <div class="form-group ">
                                                                <label>Gender </label>
                                                                <select id="gender"  name="gender" class="form-control">
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
                                                            <div class="form-group ">
                                                                <label>Salutation</label>
                                                                <select id="salutation_id" name="salutation_id" class="form-control">
                                                                    <option  value="" selected>Choose...</option>
                                                                    {{LoadCombo("salutation_master","id","salutation_name",isset($patient_data)?$patient_data->salutation_id:'','where display_status=1 AND is_deleted=0',"order by id desc");}}
                                                                </select>
                                                                <small id="salutation_id_error" class="form-text text-muted error"></small>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-3 col-md-6 mb-2">
                                                            <div class="form-group">
                                                                <label class="text-label">Patient Name </label>
                                                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name',isset($patient_data) ? $patient_data->name : '') }}" placeholder="" >
                                                                <small id="name_error" class="form-text text-muted error"></small>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-3 col-md-6 mb-2">
                                                            <div class="form-group">
                                                                <label class="text-label">Surname </label>
                                                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name',isset($patient_data) ? $patient_data->last_name : '') }}" class="form-control" placeholder="" >
                                                                <small id="last_name_error" class="form-text text-muted error"></small>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-3 col-md-6 mb-2">
                                                            <div class="form-group">
                                                                <label class="text-label">Religion</label>
                                                                <select id="religion_id" name="religion_id" class="form-control">
                                                                    <option  value="0" selected>Choose...</option>
                                                                    {{LoadCombo("religion_master","id","religion_name",isset($patient_data)?$patient_data->religion_id:'','where display_status=1 AND is_deleted=0',"order by id desc");}}

                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-3 col-md-3 mb-2">
                                                            <div class="form-group ">
                                                                <label>Specialist</label>
                                                                <select id="specialist_id" name="specialist_id" class="form-control">
                                                                    <option  value="0" selected>Choose...</option>
                                                                    {{LoadCombo("specialist_master","id","specialist_name",isset($patient_data)?$patient_data->specialist_id:'','where display_status=1 AND is_deleted=0',"order by id desc");}}
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-3 col-md-6 mb-2">
                                                            <div class="form-group">
                                                                <label class="text-label">Mobile Number
{{--                                                                    @if(isset($patient_data))--}}

{{--                                                                        <span class=" myModal_btn badge light badge-secondary" data_id="" data-toggle="modal"><i class="fa fa-check"></i></span>--}}
{{--                                                                        <div id="myModal"class="modal fade" tabindex="-1" role="dialog"--}}
{{--                                                                             aria-labelledby="myModalLabel" aria-hidden="true">--}}
{{--                                                                            <div class="modal-dialog">--}}
{{--                                                                                <div class="modal-content">--}}
{{--                                                                                    <div class="modal-header">--}}
{{--                                                                                        <h5 class="modal-title" id="myModalLabel">Verify Mobile Number--}}
{{--                                                                                        </h5>--}}
{{--                                                                                        <button type="button" class="close" data-dismiss="modal"><span>×</span>--}}
{{--                                                                                        </button>--}}
{{--                                                                                    </div>--}}
{{--                                                                                    <div class="modal-body">--}}
{{--                                                                                        <div class="mb-3">--}}
{{--                                                                                            <label class="form-label">Enter Your OTP</label>--}}
{{--                                                                                            <input class="form-control" id="checks" name="checks" type="text" placeholder="" id="example-text-input">--}}
{{--                                                                                        </div>--}}
{{--                                                                                        <div class="modal-footer">--}}

{{--                                                                                            <button  id="maintenanceBtn"--}}
{{--                                                                                                     class="btn btn-primary waves-effect waves-light"  >Submit--}}
{{--                                                                                            </button>--}}
{{--                                                                                        </div>--}}

{{--                                                                                    </div>--}}

{{--                                                                                </div>--}}
{{--                                                                                <!-- /.modal-content -->--}}
{{--                                                                            </div>--}}
{{--                                                                            <!-- /.modal-dialog -->--}}

{{--                                                                        </div>--}}

{{--                                                                    @endif--}}
                                                                </label>

                                                                <div class="input-group">
                                                                    <input type="text" name="mobile_number" id="mobile_number"  value="{{ old('mobile_number',isset($patient_data) ? $patient_data->mobile_number : '') }}" class="form-control" placeholder="" onKeyPress="return onlyNumbers(event)">
                                                                    <small id="mobile_number_error" class="form-text text-muted error"></small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-3 col-md-6 mb-2">
                                                            <div class="form-group">
                                                                <label class="text-label">Reference Name (PID/Doctor Name/Others)</label>
                                                                <input type="text" class="form-control" name="patient_reference_name" id="patient_reference_name" value="{{ old('patient_reference_name',isset($patient_data) ? $patient_data->patient_reference_name : '') }}">
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-3 col-md-6 mb-2">
                                                            <div class="form-group">
                                                                <label class="text-label">DOB</label>
                                                                <input type="text" name="dob" id="dob" value="{{ old('dob',isset($patient_data) ?date('d-m-Y', strtotime($patient_data->dob))  : '') }}" onchange="calcAge(this.value)" class="form-control" placeholder="" >
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-3 col-md-6 mb-2">
                                                            <div class="form-group">
                                                                <label class="text-label">Age</label>
                                                                <input type="text" readonly name="age" value="{{ old('dob',isset($patient_data) ?\Carbon\Carbon::parse($patient_data->dob)->diff(\Carbon\Carbon::now())->format('%y') : '') }}" id="age" class="form-control" placeholder="" >
                                                                <small id="age_error" class="form-text text-muted error"></small>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-3 col-md-6 mb-2">
                                                            <div class="form-group ">
                                                                <label>Country</label>
                                                                <select id="country_id" name="country_id" class="form-control">
                                                                    <option  value="0" selected>Choose...</option>
                                                                    {{LoadCombo("country_has","id","name", (isset($patient_data) && $patient_data->country_id) ? $patient_data->country_id:'','where display_status=1 AND is_deleted=0',"order by id desc");}}
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-3 col-md-6 mb-2">
                                                            <div class="form-group ">
                                                                <label>State</label>
                                                                <select id="state_id" name="state_id" class="form-control">
                                                                    <option  value="0" selected>Choose...</option>
                                                                    <?php
                                                                    if(isset($patient_data) && $patient_data->country_id)
                                                                    {
                                                                        {{LoadCombo("states","id","name",isset($patient_data)?$patient_data->state_id:'',"where country_id=$patient_data->country_id","order by id desc");}}
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-3 col-md-6 mb-2">
                                                            <div class="form-group ">
                                                                <label>City</label>
                                                                <select id="place_id" name="place_id" class="form-control">
                                                                    <option  value="0" selected>Choose...</option>
                                                                    <?php
                                                                    if($patient_data)
                                                                    {
                                                                        if($patient_data->country_id>0 && $patient_data->state_id>0)
                                                                        {{LoadCombo("cities","id","name",isset($patient_data)?$patient_data->place_id:'',"where state_id=$patient_data->state_id","order by id desc");}}
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-3 col-md-6 mb-2">
                                                            <div class="form-group">
                                                                <label class="text-label">Pin Code/Zip Code</label>
                                                                <input type="text" name="pincode"  id="pincode" class="form-control" value="{{ old('pincode',isset($patient_data) ? $patient_data->pincode : '') }}" maxlength="10" placeholder="" onKeyPress="return onlyNumbers(event)" onkeyup="clearError(this.id)" >
                                                                <small id="pin_code" class="form-text text-muted error"></small>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-12 col-md-12 mb-2">
                                                            <div class="form-group">
                                                                <label class="text-label">Address</label>
                                                                <input type="text" name="address" id="address" class="form-control"  value="{{ old('address',isset($patient_data) ? $patient_data->address : '') }}" placeholder="" >
                                                                <small id="address_error" class="form-text text-muted error"></small>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-2 col-md-6 mb-2">
                                                            <div class="form-group">
                                                                <label class="text-label">Marital Status</label>
                                                                <select id="marital_status" name="marital_status" class="form-control">
                                                                    <option  value="0" selected>Choose...</option>
                                                                    {{LoadCombo("merital_status_master","id","merital_status_name",isset($patient_data)?$patient_data->marital_status:'','where display_status=1 AND is_deleted=0',"order by id desc");}}
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-3 col-md-6 mb-2">
                                                            <div class="form-group ">
                                                                <label>Occupation</label>
                                                                <select id="occupation" name="occupation" class="form-control">
                                                                    {{LoadCombo("occupation_master","id","occupation_name",isset($patient_data)?$patient_data->occupation:'','where display_status=1 AND is_deleted=0',"order by id desc");}}
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-3 col-md-6 mb-2">
                                                            <div class="form-group ">
                                                                <label>Education</label>
                                                                <select id="education" name="education" class="form-control">
                                                                    <option  value="0" selected>Choose...</option>
                                                                    {{LoadCombo("education_master","id","education_name",isset($patient_data)?$patient_data->education:'','where display_status=1 AND is_deleted=0',"order by id desc");}}
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-3 col-md-6 mb-2">
                                                            <div class="form-group">
                                                                <label class="text-label">Email Address
{{--                                                                    @if(isset($patient_data))--}}
{{--                                                                        @if($patient_data->email!=null)--}}
{{--                                                                            @if($patient_data->is_email_verify==1)--}}
{{--                                                                                <span class="badge light badge-success"><i class="fa fa-check"></i></span>--}}
{{--                                                                            @else--}}
{{--                                                                                <span class=" myModal_btn1 badge light badge-secondary" data_id="{{$patient_data->id}}" data-toggle="modal"><i class="fa fa-check"></i></span>--}}
{{--                                                                                <!-- sample modal content -->--}}
{{--                                                                                <div id="myModal1"class="modal fade" tabindex="-1" role="dialog"--}}
{{--                                                                                     aria-labelledby="myModalLabel" aria-hidden="true">--}}
{{--                                                                                    <div class="modal-dialog">--}}
{{--                                                                                        <div class="modal-content">--}}
{{--                                                                                            <div class="modal-header">--}}
{{--                                                                                                <h5 class="modal-title" id="myModalLabel">Verify Email Address--}}
{{--                                                                                                </h5>--}}
{{--                                                                                                <button type="button" class="close" data-dismiss="modal"><span>×</span>--}}
{{--                                                                                                </button>--}}
{{--                                                                                            </div>--}}
{{--                                                                                            <div class="modal-body">--}}
{{--                                                                                                <div class="mb-3">--}}
{{--                                                                                                    <label class="form-label">Enter Your OTP</label>--}}
{{--                                                                                                    <input class="form-control" id="patient_email_verify_id" name="patient_email_verify_id" type="hidden" placeholder="" id="example-text-input">--}}
{{--                                                                                                    <input class="form-control" id="otp" name="otp" type="number" placeholder="" id="example-text-input">--}}
{{--                                                                                                </div>--}}
{{--                                                                                                <div class="modal-footer">--}}

{{--                                                                                                    <a  id="emailVerification"--}}
{{--                                                                                                        class="btn btn-primary waves-effect waves-light"  >Submit--}}
{{--                                                                                                    </a>--}}
{{--                                                                                                </div>--}}

{{--                                                                                            </div>--}}

{{--                                                                                        </div>--}}
{{--                                                                                        <!-- /.modal-content -->--}}
{{--                                                                                    </div>--}}
{{--                                                                                    <!-- /.modal-dialog -->--}}

{{--                                                                                </div>--}}
{{--                                                                            @endif--}}
{{--                                                                        @endif--}}
{{--                                                                    @endif--}}
                                                                </label>

                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" name="email" id="email_address" value="{{ old('email',isset($patient_data) ? $patient_data->email : '') }}" id="email" onKeyPress="return onlyNumbersAndCharacter(event)" >
                                                                    <div class="input-group-append">
                                                                        <select id="email_extension" class="form-control" name="email_extension" id="">
                                                                            {{LoadCombo("extension_master","id","extension",isset($patient_data)?$patient_data->patient_reference_type_id_extension:'','',"order by id desc");}}
                                                                        </select>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-2 col-md-6 mb-2">
                                                            <div class="form-group ">
                                                                <label>Blood Group</label>
                                                                <select id="blood_group_id" name="blood_group_id" class="form-control">
                                                                    <option  value="0" selected>Choose...</option>
                                                                    {{LoadCombo("blood_group_master","id","blood_group_name",isset($patient_data)?$patient_data->blood_group_id:'','where display_status=1 AND is_deleted=0',"order by id desc");}}
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-3 col-md-6 mb-2">
                                                            <div class="form-group">
                                                                <label class="text-label">Empanelment No.</label>
                                                                <input type="text" name="empanelment_no" id="empanelment_no" class="form-control"  value="{{ old('empanelment_no',isset($patient_data) ? $patient_data->empanelment_no : '') }}"placeholder="" >
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-3 col-md-6 mb-2">
                                                            <div class="form-group">
                                                                <label class="text-label">Claim Id</label>
                                                                <input type="text" name="claim_id" id="claim_id" value="{{ old('claim_id',isset($patient_data) ? $patient_data->claim_id : '') }}" class="form-control" placeholder="" >
                                                            </div>
                                                        </div>

                                                        <div class="col-xl-3 col-md-6 mb-2">
                                                            <div class="form-group">
                                                                <label class="text-label">WhatsApp Number (Same As Mobile Number)<input type="checkbox" title="Same As Mobile Number" id="same_as_mobile" onclick="copymobile()"></label>
                                                                <input type="text" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number',isset($patient_data) ? $patient_data->whatsapp_number : '') }}" class="form-control" placeholder="" >
                                                            </div>
                                                        </div>

{{--                                                    @if(isset($patient_data))--}}
                                                        <div class="col-xl-3 col-md-6 mb-2">
                                                            <div class="d-flex flex-wrap align-content-center h-100">
                                                                <button type="button" class="btn btn-sm btn-primary mt-1" onclick="updatePatientRegn(2)">Update</button>
                                                            </div>
                                                        </div>
{{--                                                @endif--}}
                                            </div>

                                            </section>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </form>
                        <!-- Tabs content -->
{{--                    </div>--}}

{{--                </div>--}}


                    <!-- End Page-content -->
            </div>
                </div>
            </div>

    </div>
</div>

</div>
</div>


<style>
    .table-profile td {
        padding :0 9px !important;
        border-top:unset !important;
        font-size: 13px!important;
    }
    /* .list-group-item:first-child  {
        border-top-left-radius: 0.25rem!important;
        border-top-right-radius: 0.25rem!important;
    }
    .list-group-item:last-child{
        border-bottom-left-radius: 0.25rem!important;
        border-bottom-right-radius: 0.25rem!important;
    } */
    .list-group-item {
        padding: 0.5rem 1.5rem;
        text-align: left;
        font-size: 14px;
    }
    .list-group-item:hover{
        background: #6b69eb;
        color: #fff;
    }
    .list-group-item.disabled{
        color: #fff;
        background-color: #abb2b8!important;
        border-color: #ced5db;
    }
    .btn {
        padding: 0.38rem 1.5rem;
    }
    #example5 td {

        padding: 5px 9px;
    }
    .form-control {
        height: 30px;
    }
    .medication_add{
        max-height: 60vh!important;
        overflow: auto;
    }
</style>




<script>
    $(function () {
        $("#country_id").change(function () {

            // $("#state_id").html();

            var selected =parseInt($("#country_id").val());
            var formoption = "";
            _token="{{csrf_token()}}";
            $.ajax({
                type: 'POST', //THIS NEEDS TO BE GET
                url:"{{url('getStates')}}",
                dataType: 'json',
                data: {selected:selected, _token:_token},
                success: function (data) {
                    console.log(data);
                    formoption += "<option value='0'>Select</option>";

                    $.each(data, function(index, item) {
                        formoption += "<option value='"+item.id+"'>"+item.name+"</option>";
                    });

                    $("#state_id") .html(formoption);
                    $('#state_id').selectpicker('refresh');


                },error:function(){
                    console.log();
                }
            });

        });
    });
    $(function () {
        $("#state_id").on('change', function() {

            var selected = parseInt($("#state_id").val());
            var formoption = "";
            _token="{{csrf_token()}}";
            $.ajax({
                type: 'POST', //THIS NEEDS TO BE GET
                url:"{{url('getCities')}}",
                data: {selected:selected, _token:_token},
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    formoption += "<option value='0'>Select</option>";

                    $.each(data, function(index, item) {
                        formoption += "<option value='"+item.id+"'>"+item.name+"</option>";
                    });

                    $("#place_id").html(formoption);
                    $('#place_id').selectpicker('refresh');

                },error:function(){
                    console.log();
                }
            });

        });
    });
    $(function () {

        function getStatebyplace(place_id){

            _token="{{csrf_token()}}";
            var formoption = "";
            $.ajax({
                type: 'POST', //THIS NEEDS TO BE GET
                url:"{{url('getStatesByCitiesReverse')}}",
                dataType: 'json',

                data: {place_id:place_id, _token:_token},
                success: function (data) {

                    if(data.length){

                        let item=data?.[0];
                        // $.each(data, function(index, item) {
                        formoption += "<option value='"+item.id+"' selected>"+item.name+"</option>";
                        // });

                        getcountrybyState(item.id);

                        $("#state_id") .html(formoption);
                        $('#state_id').selectpicker('refresh');
                    }
                }
            });
        }

        function getcountrybyState(state_id){

            _token="{{csrf_token()}}";
            var formoption = "";
            $.ajax({
                type: 'POST', //THIS NEEDS TO BE GET
                url:"{{url('getcountrybyState')}}",
                dataType: 'json',

                data: {state_id:state_id, _token:_token},
                success: function (data) {

                    if(data.length){
                        let item=data?.[0];

                        // $('select[name=selValue]').val(1);
                        // $('.selectpicker').selectpicker('refresh')


                        // $("#country_id") .val(item);

                        $.each(data, function(index, item) {
                            formoption += "<option value='"+item.id+"' selected>"+item.name+"</option>";
                        });

                        $("#country_id") .html(formoption);
                        $('#country_id').selectpicker('refresh');
                    }
                }
            });
        }

        $('#place_id').on('change', function() {

            var place_id = $("#place_id").val();
            var formoption = "";
            _token="{{csrf_token()}}";
            $.ajax({
                type: 'POST', //THIS NEEDS TO BE GET
                url:"{{url('getStatesByCities')}}",
                dataType: 'json',

                data: {place_id:place_id, _token:_token},
                success: function (data) {


                    if(data){
                        if($('#state_id').val() =="" || $('#state_id').val() ==null ||$('#state_id').val() ==0){
                            // load state based on place
                            getStatebyplace(place_id);

                        }else{
                            $('#pincode').val(data.pincode);
                            $('#address').focus();
                        }

                    }else{

                    }



                },error:function(){
                    console.log();
                }
            });

        });


        $("#pincode").change(function () {


            var pincode = $("#pincode").val();
            var formoption = "";
            _token="{{csrf_token()}}";
            $.ajax({
                type: 'POST', //THIS NEEDS TO BE GET
                url:"{{url('getCitiesByPin')}}",
                dataType: 'json',

                data: {pincode:pincode, _token:_token},
                success: function (data) {
                    // console.log(data);

                    if(data.length){


                        $("#place_id, #state_id").html(formoption);
                        $('#place_id , #state_id').selectpicker('refresh');


                        // formoption += "<option value='0'>Select</option>";

                        // $.each(data, function(index, item) {

                        let item=data?.[0];
                        formoption += "<option value='"+item.id+"' selected>"+item.name+"</option>";
                        // });

                        $("#place_id").html(formoption);
                        $('#place_id').selectpicker('refresh');
                        getStatebyplace(item.id);


                    }else{
                        $("#pin_code").append('Please enter valid pincode');

                        // var priority = 'danger';
                        // var title    = 'Error';
                        // var message  = 'No data available';
                        //
                        // $.toaster({ priority : priority, title : title, message : message });
                    }



                },error:function(){
                    console.log();
                }
            });


        });
    });

    $(function () {
        // $('#state_id').on('change', function() {


        //     var state_id = $("#state_id").val();
        //     var formoption = "";
        //     _token="{{csrf_token()}}";
        //             $.ajax({
        //                 type: 'POST', //THIS NEEDS TO BE GET
        //                 url:"{{url('getCountriesByCities')}}",
        //                 dataType: 'json',

        //                 data: {state_id:state_id, _token:_token},
        //                 success: function (data) {

        //                     formoption += "<option value='1'>Select</option>";

        //                     $.each(data, function(index, item) {
        //                         formoption += "<option value='"+item.id+"'>"+item.name+"</option>";
        //                     });

        //                     $("#country_id").html(formoption);
        //                     $('#country_id').selectpicker('refresh');

        //                 },error:function(){
        //                     console.log();
        //                 }
        //             });

        // });
    });
    $(document).ready(function(){
        $('#dob').datepicker({
            autoclose: true,
            endDate: '+0d',
            format: 'dd-mm-yyyy'
        });
        $('#admission_date').datepicker({
            autoclose: true,
            endDate: '+0d',
            format: 'dd-mm-yyyy'
        });
    });

    // Configure a few settings and attach camera






    function calcAge(dob){
        var dateParts = dob.split("/");
        dob = new Date(+ dateParts[2], dateParts[1] -1, +dateParts[0]);
        var today = new Date();
        var age = Math.floor((today-dob) / (365.25  * 24 * 60 * 60 * 1000));
        $('#age').val(age);
    }


    function updatePatientRegn(crude=1)
    {

        // console.log($('#patient_snapshot').val());
        //    return;
        var form = $('#update-patient-info')[0];
        var formData = new FormData(form);
        console.log();
        formData.append('crude', crude);
        url='{{route('updatePatientData')}}';

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(result){
                if (result.status == 1) {
                    console.log()
                    swal("Done", result.message, "success");
                    var form = $('#update-patient-info')[0];
{{--                    window.location.href = '{{url("patientRegistration")}}?id='+ result.id;--}}
                }
                else if (result.status == 2) {
                    swal("Done", result.message, "success");
                    location.reload();
                    document.getElementById("update-patient-info").reset();
                    if(page==1){

                    }

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
                        $("#" + key + "_error").text(val[0]);
                    });
                }

            }
        });
    }
    $(document).ready(function(){
        $('.myModal_btn').click(function(e){

            e.preventDefault();
            $('#myModal').modal('show');
        })
    })
    $('#emailVerification').click(function(e){

        e.preventDefault();
        let patient_email_verify_id = $('#patient_email_verify_id').val();
        let otp = $('#otp').val();
        _token="{{csrf_token()}}";
        $.ajax({
            url:"{{url('verifyOtp')}}/",
            type:"POST",
            data: {patient_email_verify_id:patient_email_verify_id,otp:otp,_token:_token},
            success:function(result){
                $('#myModal1 .form-control').val("");
                $('#myModal').modal('hide');

                if (result.status == 1) {
                    console.log()
                    swal("Done", result.message, "success");
                    window.location.href = '{{url("patientRegistration")}}?id='+ result.id;

                }
                else {
                    sweetAlert("Oops...", result.message, "error");
                    window.location.href = '{{url("patientRegistration")}}?id='+ result.id;
                }


            },
            error: function(response) {

            },
        });
    });
    $(document).ready(function(){
        $('.myModal_btn1').click(function(e){

            e.preventDefault();
            var email_address = $("#email_address").val();
            var email_extension = $("#email_extension").val();


            _token="{{csrf_token()}}";
            $.ajax({
                type: 'GET', //THIS NEEDS TO BE GET
                url:"{{url('requestOTP')}}",
                dataType: 'json',

                data: {email_address:email_address, email_extension:email_extension,_token:_token},
                success: function (data) {



                },error:function(){
                    console.log();
                }
            });
            $("#patient_email_verify_id").val( $(this).attr('data_id'));
            $('#myModal1').modal('show');
        })
    })
    function copymobile()
    {
        $("#whatsapp_number").val('');
        if(document.getElementById('same_as_mobile').checked)
        {

            var mobile=$("#mobile_number").val();
            $("#whatsapp_number").val(mobile);

        }
    }
</script>

<script>
    $(document).ready(function(){
        $("#pincode").keyup(function() {
            $("#pin_code").html("");
        });
    });
</script>
