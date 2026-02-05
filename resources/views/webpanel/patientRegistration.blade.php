<link href='date_picker/css/bootstrap-datepicker.min.css' rel='stylesheet' type='text/css'>

<div class="content-body">
    <div class="container-fluid pt-2">
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
            .select2-container--default .select2-selection--multiple:before {
                content: ' ';
                display: block;
                position: absolute;
                border-color: #888 transparent transparent transparent;
                border-style: solid;
                border-width: 5px 4px 0 4px;
                height: 0;
                right: 6px;
                margin-left: -4px;
                margin-top: -2px;top: 50%;
                width: 0;cursor: pointer
            }

            .select2-container--open .select2-selection--multiple:before {
                content: ' ';
                display: block;
                position: absolute;
                border-color: transparent transparent #888 transparent;
                border-width: 0 4px 5px 4px;
                height: 0;
                right: 6px;
                margin-left: -4px;
                margin-top: -2px;top: 50%;
                width: 0;cursor: pointer
            }
        </style>

<script type="text/javascript" src="webcam/webcamjs/webcam.min.js"></script>


<form name="frm" id="frm" action="{{URL('saveImages')}}" enctype="multipart/form-data" method="POST">
<div class="row">
    <div class="row">
        <div class="col-xl-4 col-lg-12 col-sm-12">
            <div class="card card-sm">
                <div class="text-center p-3 overlay-box " style="background-image: url(images/big/img1.jpg);">
                    @if(isset($gallery))
                    <div class="profile-photo">
                                <img src="{{ url('/images/'.$gallery->image) }}" id="pro_pic" width="100" class="img-fluid rounded-circle" alt="">
                            </div>
                            @else
                            <div class="profile-photo">
                                <img src="images/profile/profile.png" id="pro_pic" width="100" class="img-fluid rounded-circle" alt="">
                            </div>
                           @endif


                    <h3 class="mt-3 mb-1 text-white">Upload Photo</h3>
                    <p class="text-white mb-0">JPEG|PNG |JPG
                    </p>
                </div>

                <button type="button" class="btn btn-square btn-primary mb-2" onClick="document.getElementById('results').innerHTML =''" data-toggle="modal" data-target="#basicModal">Open Web Cam</button>
                                    <!-- Modal -->
                                    <div class="modal fade bd-example-modal-lg" tabindex="-1" id="basicModal" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog "modal-dialog modal-lg role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Web Cam</h5>
                                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">

                                                        <div class="row d-flex justify-content-center">

                                                        @if(isset($gallery))

                                                            <img id="previewImg" src="{{ url('/images/'.$gallery->image) }}" alt="Placeholder" style="height:150px;width:150px">
                                                            @else
                                                            <img id="previewImg" src="images/profile/profile.png" alt="Placeholder" style="height:150px;width:150px">
                                                        @endif






                                                                <div id="my_camera"></div>


                                                        </div>
                                                        <br>

                                                        <button type="button" class="openwebcam btn btn-primary btn-xs" onClick="openwebcam()">Open Webcam</button>

                                                        <button type="button" class="take_snapshot hidedata btn btn-primary btn-xs" onClick="take_snapshot()">Take Snapshot</button>
                                                        <input type="file" id="imgupload" style="display:none" name="patient_image" onchange="preview_image()" accept=".jpg,.jpeg,.png"  />

                                                        <input type="hidden" id="patient_snapshot" style="display:none" name="patient_snapshot" />

                                                        <!-- <input type="hidden" name="image" class="image-tag"> -->


                                                        <button type="button" class="btn btn-info btn-xs" onClick="open_gallery()">Open From Gallery</button>
                                                        <br>  <br>
                                                        <div class="row d-flex justify-content-center">
                                         <div id="results" ></div>

                                                        </div>


                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger light" onclick="removeProfileImage()" data-dismiss="modal">Close</button>
                                                    <button type="button"  class="btn btn-primary" data-dismiss="modal">Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
            </div>
        </div>
        <div class="col-xl-8 col-xxl-8 col-lg-12 col-sm-12">
            <div class="card card-sm">
                <div class="card-header">
                    <h4 class="card-title">Communication Details</h4>
                </div>
                <div class="card-body">

                    <section>
                        <div class="row">
                            <div class="col-xl-3 col-md-3 mb-2">
                                <div class="form-group">
                                    <label class="text-label">Mobile Number <span class="required">*</span>
                                    @if(isset($patient_data))

                                    <span class=" myModal_btn badge light badge-secondary" data_id="" data-toggle="modal"><i class="fa fa-check"></i></span>
                                    <div id="myModal"class="modal fade" tabindex="-1" role="dialog"
                                                aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="myModalLabel">Verify Mobile Number
                                                            </h5>
                                                           <button type="button" class="close" data-dismiss="modal"><span>×</span>
                                                             </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Enter Your OTP</label>
                                                                <input class="form-control" id="checks" name="checks" type="text" placeholder="" id="example-text-input">
                                                            </div>
                                                            <div class="modal-footer">

                                                                <button  id="maintenanceBtn"
                                                                    class="btn btn-primary waves-effect waves-light"  >Submit
                                                                </button>
                                                            </div>

                                                        </div>

                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->

                                            </div>

                                            @endif
                                    </label>

                                    <div class="input-group">
                                    <input type="text" name="mobile_number" id="mobile_number"  value="{{ old('mobile_number',isset($patient_data) ? $patient_data->mobile_number : '') }}" class="form-control" placeholder="" required>
                                    <small id="mobile_number_error" class="form-text text-muted error"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-2">
                                <div class="form-group">
                                    <label class="text-label">WhatsApp Number <input type="checkbox" title="Same As Mobile Number" id="same_as_mobile" onclick="copymobile()"></label>
                                    <input type="text" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number',isset($patient_data) ? $patient_data->whatsapp_number : '') }}" class="form-control" placeholder="" required>
                                    {{-- <small id="whatsapp_number_error" class="form-text text-muted error"></small> --}}
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6 mb-2">
                                <div class="form-group">
                                    <label class="text-label">Email Address
                                    @if(isset($patient_data))
                                    @if($patient_data->email!=null)
                                    @if($patient_data->is_email_verify==1)
                                    <span class="badge light badge-success"><i class="fa fa-check"></i></span>
                                    @else
                                        <span class=" myModal_btn1 badge light badge-secondary" data_id="{{$patient_data->id}}" data-toggle="modal"><i class="fa fa-check"></i></span>
                                     <!-- sample modal content -->
                                     <div id="myModal1"class="modal fade" tabindex="-1" role="dialog"
                                                aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="myModalLabel">Verify Email Address
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"><span>×</span>
                                                             </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Enter Your OTP</label>
                                                                <input class="form-control" id="patient_email_verify_id" name="patient_email_verify_id" type="hidden" placeholder="" id="example-text-input">
                                                                <input class="form-control" id="otp" name="otp" type="number" placeholder="" id="example-text-input">
                                                            </div>
                                                            <div class="modal-footer">

                                                                <a  id="emailVerification"
                                                                    class="btn btn-primary waves-effect waves-light"  >Submit
                                                                </a>
                                                            </div>

                                                        </div>

                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->

                                            </div>
                                            @endif
                                            @endif
                                            @endif
                                </label>

                                    <div class="input-group">
                                        <input type="text" class="form-control" name="email" id="email_address" value="{{ old('email',isset($patient_data) ? $patient_data->email : '') }}" id="email">
                                        {{--  onKeyPress="return onlyNumbersAndCharacter(event)"  --}}
                                        <div class="input-group-append">
                                            <select id="email_extension" class="form-control" name="email_extension" id="">
                                            {{LoadCombo("extension_master","id","extension",isset($patient_data)?$patient_data->email_extension:'','where is_deleted=0 AND display_status=1',"order by id ASC");}}
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6 mb-2">
                                <div class="form-group">
                                    <label class="text-label">Alternate  Number 1</label>
                                    <div class="input-group">
                                        <input type="text" name="alternative_number_1_number" value="{{ old('alternative_number_1_number',isset($patient_data) ? $patient_data->alternative_number_1_number : '') }}" id="alternative_number_1_number" class="form-control" placeholder="" required>
                                        <div class="input-group-append">
                                            <select id="alternative_number_1_type" class="form-control" name="alternative_number_1_type" >
                                            <option  value="0" selected>Choose...</option>
                                            {{LoadCombo("relation_master","id","relation_name",isset($patient_data)?$patient_data->alternative_number_1_type:'','where display_status=1 AND is_deleted=0',"order by id desc");}}

                                            </select>
                                        </div>
                                        <div class="input-group-append" style="width: 50%">
                                            <input type="text" name="alternative_number_1_name" id="alternative_number_1_name" value="{{ old('alternative_number_1_name',isset($patient_data) ? $patient_data->alternative_number_1_name : '') }}" onKeyPress="return Onlycharecters(event)"  class="form-control" placeholder="Relative Name" required>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6 mb-2">
                                <div class="form-group">
                                    <label class="text-label">Alternate  Number 2</label>
                                    <div class="input-group">
                                        <input type="text" name="alternative_number_2_number"  value="{{ old('alternative_number_2_number',isset($patient_data) ? $patient_data->alternative_number_2_number : '') }}" id="alternative_number_2_number" class="form-control" placeholder="" required>
                                        <div class="input-group-append">
                                            <select id="alternative_number_2_type" name="alternative_number_2_type" class="form-control">
                                            <option  value="0" selected>Choose...</option>
                                            {{LoadCombo("relation_master","id","relation_name",isset($patient_data)?$patient_data->alternative_number_2_type:'','where display_status=1 AND is_deleted=0',"order by id desc");}}


                                            </select>
                                        </div>
                                        <div class="input-group-append" style="width: 50%">
                                            <input type="text" name="alternative_number_2_name" id="alternative_number_2_name" value="{{ old('alternative_number_2_name',isset($patient_data) ? $patient_data->alternative_number_2_name : '') }}" onKeyPress="return Onlycharecters(event)"  class="form-control" placeholder="Relative Name" required >
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

        <div class="row">
            <div class="col-xl-12 col-xxl-12">
                <div class="card card-sm">
                    {{-- <div class="card-header">
                        <h4 class="card-title">Patient Registration Form</h4>
                    </div> --}}
                    <div class="card-body">

                            <div>
                                <section>


                                    <div class="row">
                                        <div class="col-xl-3 col-md-6 mb-2">

                                            <input type="hidden" name="consultant_id" id="consultant_id" value="{{ isset($patient_data)?$patient_data->specialist_id:'' }}">
                                            <div class="form-group">

                                                <label class="text-label">UHID No.</label>
                                                <input type="hidden" name="patient_id" id="patient_id" class="form-control" placeholder="" required readonly
                                                value="{{ old('patient_id',isset($patient_data) ? $patient_data->id : '') }}"


                                                >
                                                <input type="text" name="uhid" id="uhid" class="form-control" placeholder="" required readonly

                                                value="{{isset($uhidNo) ? $uhidNo :''}}"
                                                >
                                                <!-- value="{{ old('uhid',isset($patient_data) ? $patient_data->uhidno : '') }}" -->
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3 mb-2">
                                            <div class="form-group ">
                                                <label>Patient Type <span class="required">*</span> </label>
                                                <select id="patient_type" name="patient_type" class="form-control">
                                                    <option  value="" selected>Choose...</option>
                                                    {{LoadCombo("patient_type_master","id","patient_type_name",isset($patient_data)?$patient_data->patient_type:'','where display_status=1 AND is_deleted=0',"order by id desc");}}

                                                </select>
                                                <small id="patient_type_error" class="form-text text-muted error"></small>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3 mb-2">
                                            <div class="form-group ">
                                                <label>Category <span class="required">*</span></label>
                                                <select multiple="multiple" class="form-control multi-select" name="category[]" id="category">


                                                    {{LoadComboMulti("category_master","id","category_name",isset($patient_category)?$patient_category:""
                                                        ,'where display_status=1 AND is_deleted=0',"order by id desc");}}
                                                </select>
                                                <small id="category_error" class="form-text text-muted error"></small>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-3 mb-2">
                                            <div class="form-group ">
                                                <label>Division</label>
                                                <select multiple="" class="form-control multi-select" name="subCategory[]" id="sel2 subCategory">


                                                    {{LoadComboMulti("sub_category_master","id","sub_category_name",isset($patient_sub_category)?$patient_sub_category:""

                                                        ,'where display_status=1 AND is_deleted=0',"order by id desc");}}
                                                </select>
                                            </div>
                                        </div>



                                    </div>
                                    <div class="row">

                                        <div class="col-xl-3 col-md-3 mb-2">
                                            <div class="form-group ">
                                                <label>Sub Division</label>
                                                <select id="sub_division_id" class="form-control" name="sub_division_id" >
                                                <option  value="" selected>Choose...</option>
                                                {{LoadCombo("sub_division","id","sub_division_name",isset($patient_data)?$patient_data->sub_division_id:'','where display_status=1 AND is_deleted=0',"order by id desc");}}
                                                     </select>
                                            </div>
                                        </div>


                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group ">
                                                <label>Salutation <span class="required">*</span> </label>
                                                <select id="salutation" name="salutation" class="form-control">
                                                <option  value="" selected>Choose...</option>
                                                {{LoadCombo("salutation_master","id","salutation_name",isset($patient_data)?$patient_data->salutation_id:'','where display_status=1 AND is_deleted=0',"order by id desc");}}
                                            </select>
                                            <small id="salutation_error" class="form-text text-muted error"></small>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Patient Name <span class="required">*</span></label>
                                                <input type="text" name="patient_name" id="patient_name" class="form-control" value="{{ old('patient_name',isset($patient_data) ? $patient_data->name : '') }}" onKeyPress="return Onlycharecters(event)"  placeholder="" required>
                                                <small id="patient_name_error" class="form-text text-muted error"></small>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group">
                                                {{-- <span class="required">*</span> --}}
                                                <label class="text-label">Surname   </label>
                                                <input type="text" name="surname" id="surname" value="{{ old('surname',isset($patient_data) ? $patient_data->last_name : '') }}" onKeyPress="return Onlycharecters(event)"  class="form-control" placeholder="" required>
                                                <small id="surname_error" class="form-text text-muted error"></small>
                                            </div>
                                        </div>


                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group ">
                                          <label>Gender  <span class="required">*</span> </label>
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
                                            <div class="form-group">
                                                <label class="text-label">DOB<span class="required">*</span></label>
                                                <input type="text" name="dob" id="dob" value="{{ old('dob',isset($patient_data) ?date('d/m/Y', strtotime($patient_data->dob))  : '') }}" onchange="calcAge(this.value)" class="form-control" placeholder="" required readonly >
                                                <small id="dob_error" class="form-text text-muted error"></small>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Age </label>
                                                <input type="text" readonly name="age" value="{{ old('dob',isset($patient_data) ?\Carbon\Carbon::parse($patient_data->dob)->diff(\Carbon\Carbon::now())->format('%y') : '') }}" id="age" class="form-control" placeholder="" required>
                                                <small id="age_error" class="form-text text-muted error"></small>
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
                                            <div class="form-group ">
                                                <label>Occupation</label>
                                                <select id="occupation" name="occupation" class="form-control">
                                                    <option  value="0" selected>Choose...</option>
                                                    {{LoadCombo("occupation_master","id","occupation_name",isset($patient_data)?$patient_data->occupation:'','where display_status=1 AND is_deleted=0',"order by id desc");}}
                                                    </select>
                                            </div>
                                        </div>



                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group ">
                                                <label>Relation of Caregiver</label>
                                                <select id="caregiver_relation" name="caregiver_relation"  value="{{ old('caregiver_relation',isset($patient_data) ? $patient_data->caregiver_relation : '') }}" class="form-control">
                                                <option  value="0" selected>Choose...</option>
                                                {{LoadCombo("relation_master","id","relation_name",isset($patient_data)?$patient_data->caregiver_relation:'','where display_status=1 AND is_deleted=0',"order by id desc");}}

                                            </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Caregiver Name</label>
                                                <input type="text" name="caregiver_name" id="caregiver_name"  value="{{ old('caregiver_name',isset($patient_data) ? $patient_data->caregiver_name : '') }}" onKeyPress="return Onlycharecters(event)"  class="form-control" placeholder="Relative Name" required>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Caregiver PID</label>
                                                <input type="text" name="caregiver_pid" id="caregiver_pid" maxlength="50" class="form-control" value="{{ old('caregiver_pid',isset($patient_data) ? $patient_data->caregiver_pid : '') }}" placeholder="Relative PID" required>
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
                                            <div class="form-group">
                                                <label class="text-label">Department</label>
                                                <select id="department_id" name="department_id" class="form-control" onchange="getConsultantList(this.value)">
                                                    <option  value="0" selected>Choose...</option>
                                                    {{LoadCombo("departments","id","department_name",isset($patient_data)?$patient_data->department_id:'',"where display_status=1  AND is_deleted=0","order by id desc");}}
                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-3 mb-2">
                                            <div class="form-group ">
                                                <label>Specialist</label>
                                                <!-- <select id="specialist_id" name="specialist_id" class="form-control" aria-label="Default select example" disabled data-none-selected-text="Select">
                                                </select> -->

                                                <select id="specialist_id" name="specialist_id" class="form-control" >
                                                     <option  value="0" selected>Choose...</option>
                                                    <?php
                                                    if(isset($patient_data) && $patient_data->specialist_id)
                                                    {

                                                        {{LoadCombo("specialist_master","id","specialist_name",isset($patient_data)?$patient_data->specialist_id:'','where display_status=1 AND is_deleted=0',"order by id desc");}}
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-6 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Token Number</label>
                                                <input type="text" name="token_number" id="token_number" maxlength="100" onKeyPress="return blockSpecialChar(event)" value="{{ old('token_number',isset($patient_data) ? $patient_data->token_number : '') }}" class="form-control" placeholder="" required>
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

                                    </div>
                                    <div class="row">

                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group ">
                                                <label>Country</label>
                                                <select id="country_id" name="country_id" class="form-control">
                                                        <option  value="0" selected>Choose...</option>
                                                        {{LoadCombo("country_has","id","name", (isset($patient_data) && $patient_data->country_id) ? $patient_data->country_id:'','where display_status=1 AND is_deleted=0',"order by order_no");}}
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
                                                <input type="text" name="pincode"  id="pincode" class="form-control" value="{{ old('pincode',isset($patient_data) ? $patient_data->pincode : '') }}" placeholder="" maxlength="10" required autocomplete="false" onKeyPress="return onlyNumbers(event)" onkeyup="clearError(this.id)">
                                                <small id="pin_code" class="form-text text-muted error"></small>
                                            </div>
                                        </div>
                                        <div class="col-xl-12 col-md-12 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Address   <span class="required">*</span></label>
                                                <input type="text" name="address" id="address" class="form-control"  value="{{ old('address',isset($patient_data) ? $patient_data->address : '') }}" placeholder="" required>
                                                <small id="address_error" class="form-text text-muted error"></small>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-6 mb-2">
                                            <div class="form-group ">
                                                <label>Patient Reference</label>
                                                <select id="patient_reference_type_id" name="patient_reference_type_id" class="form-control">
                                                    <option  value="0" selected>Choose...</option>
                                                    {{LoadCombo("patient_reference_master","id","patient_reference_name",isset($patient_data)?$patient_data->patient_reference_type_id:'','where display_status=1 AND is_deleted=0',"order by id desc");}}

                                            </select>
                                            </div>
                                        </div>


                                        <div class="col-xl- col-md-4 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Reference Name (PID/Doctor Name/Others)</label>
                                                <input type="text" class="form-control" name="patient_reference_name" id="patient_reference_name" value="{{ old('patient_reference_name',isset($patient_data) ? $patient_data->patient_reference_name : '') }}">
                                                {{-- <select id="patient_reference_name" name="patient_reference_name" class="form-control">
                                                    <option  value="0" selected>Choose...</option>

                                                            {{LoadCombo("patient_ref_master","id","patient_ref_name",isset($patient_data)?$patient_data->patient_reference_name:'','where display_status=1 AND is_deleted=0',"order by id desc");}}


                                            </select> --}}
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





                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group ">
                                                <label>Annual Income</label>
                                                <select id="annual_income" name="annual_income" class="form-control">
                                                        <option  value="0" selected>Choose...</option>
                                                        {{LoadCombo("annual_income_master","id","annual_income_name",isset($patient_data)?$patient_data->annual_income:'','where display_status=1 AND is_deleted=0',"order by id desc");}}

                                                    </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group ">
                                                <label>ID Proof Type</label>
                                                <select id="id_proof_type" name="id_proof_type" class="form-control">
                                                    <option  value="0" selected>Choose...</option>
                                                    {{LoadCombo("id_proof_type_master","id","id_proof_name",isset($patient_data)?$patient_data->id_proof_type:'','where display_status=1 AND is_deleted=0',"order by id desc");}}

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">ID Proof No</label>
                                                <input type="text" name="id_proof_number" value="{{ old('id_proof_number',isset($patient_data) ? $patient_data->id_proof_number : '') }}" id="id_proof_number" onKeyPress="return blockSpecialChar(event)" class="form-control" placeholder="" maxlength="25" required>
                                            </div>
                                        </div>

                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Empanelment No.</label>
                                                <input type="text" name="empanelment_no" id="empanelment_no" class="form-control" onKeyPress="return blockSpecialChar(event)" value="{{ old('empanelment_no',isset($patient_data) ? $patient_data->empanelment_no : '') }}"placeholder="" required>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">Claim Id</label>
                                                <input type="text" name="claim_id" id="claim_id" onKeyPress="return blockSpecialChar(event)" value="{{ old('claim_id',isset($patient_data) ? $patient_data->claim_id : '') }}" class="form-control" placeholder="" required>
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 mb-2">
                                            <div class="form-group ">
                                                <label>Status</label>
                                                <select id="status" name="status" class="form-control">
                                                    <option  value="">Choose...</option>
                                                    @foreach(\App\Http\Constants\PatientConstant::PATIENT_STATUS as $key => $status)
                                                        <option value="{{ $key }}" {{(old('status',isset($patient_data) ? $patient_data->status : '') == $key) ? 'selected' : '' }}>{{$status}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                     </div>


                                    @if(isset($patient_data))
                                    <div class="col-xl-3 col-md-6 mb-2">
                                        <div class="d-flex flex-wrap align-content-center h-100">
                                            <button type="button" class="btn btn-sm btn-primary mt-1" onclick="saveRegn(2)">Update</button>
                                        </div>
                                    </div>
                                    @else
                                    <div class="col-xl-3 col-md-6 mb-2">
                                        <div class="d-flex flex-wrap align-content-center h-100">
                                            <button type="button" class="btn btn-sm btn-primary mt-1" onclick="saveRegn(1)">Save</button>
                                        </div>
                                    </div>
                                    @endif
                            </div>

                            </section>
                    </div>

                </div>
            </div>
        </div>
    </div>

</form>
</div>
@include('frames/footer');

<link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
<link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
<script src='date_picker/js/bootstrap-datepicker.min.js' type='text/javascript'></script>
<script src="./vendor/select2/js/select2.full.min.js"></script>
    <script src="./js/plugins-init/select2-init.js"></script>
    <script language="JavaScript">

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

                            var test=isNaN(data.pincode) ;
                            if(!test)
                            {
                                $('#pincode').val(data.pincode);
                            }

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

        $("#pin_code").html('');
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
                            // $("#place_id, #state_id,  #country_id").html('');
                            // $('#place_id , #state_id, #country_id' ).selectpicker('refresh');
                          //  $("#pin_code").append('Please enter valid pincode');
                            // sweetAlert("Oops...", "Please enter valid pincode", "error");
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
        format: 'dd/mm/yyyy'
    });
});

	 // Configure a few settings and attach camera


    // preload shutter audio clip
    var shutter = new Audio();
    shutter.autoplay = true;
    shutter.src = navigator.userAgent.match(/Firefox/) ? 'shutter.ogg' : 'shutter.mp3';


    function showWebcam(){

    }
    function openwebcam(){
        $('#my_camera').removeClass('hidedata');
        $('.openwebcam').addClass('hidedata');
        $('.take_snapshot').removeClass('hidedata');
        Webcam.set({
        width: 320,
        height: 240,
        image_format: 'jpeg',
        jpeg_quality: 90
        });
        Webcam.attach( '#my_camera' );

        $('#previewImg').attr("style", 'display:none');
    }
    function take_snapshot() {
        // play sound effect
        shutter.play();

        // take snapshot and get image data
        Webcam.snap( function(data_uri) {

            console.log(data_uri);
            // display results in page
            document.getElementById('results').innerHTML =
            '<img src="'+data_uri+'"/>';
            $('#imgupload').val('');
            $('#patient_snapshot').val(data_uri);
            $('#pro_pic').attr('src',data_uri);
            $('#pro_pic').attr('width','150');
        } );

    }
    function removeProfileImage() {
        $("#previewImg").attr("src", 'images/profile/profile.png');
        $('#pro_pic').attr('src','images/profile/profile.png');
    }
    function open_gallery() {

        $('.openwebcam').removeClass('hidedata');
        $('.take_snapshot').addClass('hidedata');


        $('#previewImg').attr("style", 'display:block;height:150px;width:150px');
        $('#my_camera').addClass('hidedata');


        $('#imgupload').trigger('click');
        if ( $('#imgupload').files &&  $('#imgupload').files [0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $("#previewImg").attr("src", reader.result);
                $('#pro_pic').attr('src',reader.result);

            }

            reader.readAsDataURL(input.files[0]);
            $('#patient_snapshot').val('');
    }
    }
    function preview_image() {
        var fileName = document.getElementById("imgupload").value;
        var idxDot = fileName.lastIndexOf(".") + 1;
        var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
        var file =  $('#imgupload').get(0).files[0];

        if (extFile=="jpg" || extFile=="jpeg" || extFile=="png"){
            if(file){
                var reader = new FileReader();

                reader.onload = function(){
                    $("#previewImg").attr("src", reader.result);
                    $('#pro_pic').attr('src',reader.result);

                }

                reader.readAsDataURL(file);
            }
        }else{
            sweetAlert("Oops...", "Only jpg/jpeg and png files are allowed!", "error");

        }



  }
    function calcAge(dob){
        var dateParts = dob.split("/");
        dob = new Date(+ dateParts[2], dateParts[1] -1, +dateParts[0]);
        var today = new Date();
        var age = Math.floor((today-dob) / (365.25  * 24 * 60 * 60 * 1000));
        $('#age').val(age);
    }

    function saveRegn(crude=1)
    {

        console.log($('#patient_snapshot').val());
        var form = $('#frm')[0];
        var formData = new FormData(form);
        formData.append('crude', crude);
        url='{{route('savePatient')}}';
        // $("#country_id").val() > 0 && $("#state_id").val() > 0 && $("#place_id").val() > 0
        if (x=1) {

            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                processData: false,
                contentType: false,
                success: function (result) {
                    if (result.status == 1) {
                        console.log()
                        swal("Done", result.message, "success");
                        var form = $('#frm')[0];
                        window.location.href = '{{url("patientRegistration")}}?id=' + result.id;
                    } else if (result.status == 2) {
                        swal("Done", result.message, "success");
                        location.reload();
                        document.getElementById("frm").reset();
                        if (page == 1) {

                        }

                    } else {
                        sweetAlert("Oops...", result.message, "error");
                    }

                },
                error: function (result, jqXHR, textStatus, errorThrown) {
                    if (result.status === 422) {
                        result = result.responseJSON;
                        var error = result.errors;
                        $.each(error, function (key, val) {
                            // console.log(key);
                            $("#" + key + "_error").text(val[0]);
                        });
                    }

                }
            });
        } else {
            sweetAlert("Oops...", 'Invalid Pincode', "error");
        }
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
        if(document.getElementById('same_as_mobile').checked)
        {

            var mobile=$("#mobile_number").val();
            $("#whatsapp_number").val(mobile);

        }
    }


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
                $("#specialist_id").append('<option selected value=' + value.id + '>' + value.specialist_name + '</option>');
            });

            $("#specialist_id").attr('disabled', false);
            $("#specialist_id").selectpicker('refresh');

            }
    });

}
	</script>
<script>
    $(document).ready(function(){
        $("#pincode").keyup(function() {
            $("#pin_code").html("");
        });
    });
</script>

