<link href="{{asset('date_picker/css/bootstrap-datepicker.min.css')}}" rel='stylesheet' type='text/css'>
<style>
    .content-body .container {
        margin-top: 0px !important;
        position: absolute;
        bottom: -790px;
        width: 92%;
    }
    .active_link{
        color:blue;
        text-decoration: underline  !important;
    }
    .colapsemenu{

        float: right;
        top: 50%;
        /* bottom: 0px; */
        position: fixed;
        z-index: 999;
        right: 0px;
    }
    .gear{
        background: #00a179;
        color: #fff;
        padding: 10px;
    }
    @import url("https://fonts.googleapis.com/css?family=Roboto");
    @-webkit-keyframes come-in {
        0% {
            -webkit-transform: translatey(100px);
            transform: translatey(100px);
            opacity: 0;
        }
        30% {
            -webkit-transform: translateX(-50px) scale(0.4);
            transform: translateX(-50px) scale(0.4);
        }
        70% {
            -webkit-transform: translateX(0px) scale(1.2);
            transform: translateX(0px) scale(1.2);
        }
        100% {
            -webkit-transform: translatey(0px) scale(1);
            transform: translatey(0px) scale(1);
            opacity: 1;
        }
    }
    @keyframes come-in {
        0% {
            -webkit-transform: translatey(100px);
            transform: translatey(100px);
            opacity: 0;
        }
        30% {
            -webkit-transform: translateX(-50px) scale(0.4);
            transform: translateX(-50px) scale(0.4);
        }
        70% {
            -webkit-transform: translateX(0px) scale(1.2);
            transform: translateX(0px) scale(1.2);
        }
        100% {
            -webkit-transform: translatey(0px) scale(1);
            transform: translatey(0px) scale(1);
            opacity: 1;
        }
    }
    * {
        margin: 0;
        padding: 0;
    }

    html, body {
        background: #eaedf2;
        font-family: 'Roboto', sans-serif;
    }

    .floating-container {
        position: fixed;
        right: 0;
        margin: 35px 25px;
        width: 25px;
        height: 0px;
        /* top: 35%; */
        top: 12%;


    }


    .floating-container .floating-button {
        position: absolute;
        width: 50px;
        height: 50px;
        background: #2cb3f0;
        bottom: 0;
        border-radius: 50%;
        left: 0;
        right: 0;
        margin: auto;
        color: white;
        line-height: 50px;
        text-align: center;
        font-size: 23px;
        z-index: 100;
        box-shadow: 0 10px 25px -5px rgba(44, 179, 240, 0.6);
        cursor: pointer;
        -webkit-transition: all 0.3s;
        transition: all 0.3s;
    }
    .floating-container .float-element {
        position: relative;
        display: block;
        border-radius: 50%;
        width: 270px;
        height: 50px;
        margin: 15px auto;
        color: white;
        font-weight: 500;
        text-align: center;
        /* line-height: 50px; */
        z-index: 0;
        /* opacity: 0; */
        display: none;
        -webkit-transform: translateY(100px);
        transform: translateY(100px);
    }
    .floating-container .float-element .material-icons {
        vertical-align: middle;
        font-size: 16px;
    }



    .list-group{
        right: 225px;
        position: inherit;
        background: #fff;
        z-index: 999;
        top:-130px!important;

    }

    .badge{
        margin:auto;
        margin-top:2px;
        line-height: 0.7;
    }
.scrollvisit{
    max-height: 350x;
    overflow-y: scroll;

}

</style>

<div class="content-body">
    <div class="container-fluid pt-2 dtmshome">
        <div class="row">

            {{-- @include('includes/dtms_profile_sidebar',['data'=>$patient_data]) --}}
            <div class="col-xl-12 col-lg-12 col-sm-12">
                @include('includes/dtms_profile',['data'=>$patient_data])
            </div>
            <div class="col-xl-12 col-lg-12 col-sm-12 pb-2" >
                <div class="row">
                    <div class="col-3">
                        <button type="button" class="btn btn-sm btn-primary mt-1 btn-block" onclick="addDiagnosis();">Diagnosis</button>

                    </div>
                    <!-- <div class="col-2">
                        <a href='#' class="custom-a" onclick="viewDiagnosis();">View Diagnosis</a>
                    </div> -->

                    <div class="col-3">
                        <button type="button" class="btn btn-sm btn-primary mt-1 btn-block" onclick="addComplication();">Complication</button>
                    </div>
                    <div class="col-md-2">
                        <button type="button" title="Save DTMS" class="btn btn-sm btn-primary mt-1 saveAllDtmsDatabtn" onclick="saveAllDtmsData(1)">Save</button>
                    </div>
                    <div class="toast" data-autohide="false" id="image-status" style="display: none;">
                        <div class="toast-header">
                            <strong class="mr-auto text-primary">Alert</strong>
                            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
                        </div>
                        <div class="toast-body">
                            Please update your profile image
                        </div>
                    </div>
                    <input type="hidden" id="patient-profile-img" value="{{ is_null($gallery) ? 1 : 0 }}"/>
                    <input type="hidden" id="patient_id" value="{{ ! is_null($patient_data) ? $patient_data->id : '' }}"/>

                </div>


            </div>

            <div class="col-md-3">
                <div class="card">
                    <form name="create-new-visit-form" id="create-new-visit-form" action="#" >
                        <div class="row">
                            <div class="col-xl-6 pl-30">
                                <div class="form-group">
                                    <label class="text-label">Visit Type<span class="required">*</span></label>
                                    <select id="visit_type_id" name="visit_type_id" class="form-control">
                                        <option  value="" selected>Choose...</option>
                                        {{LoadCombo("visit_type_master","id","visit_type_name",'','where display_status=1 AND is_deleted=0',"order by id desc");}}
                                    </select>
                                    <small id="visit_type_id_error" class="form-text text-muted error"></small>
                                </div>
                            </div>
                            <div class="col-xl-6 pr-30">
                                <div class="form-group">
                                    <label class="text-label">Visit Date<span class="required">*</span></label>
                                    <input type="text" name="new_visit_date" id="new_visit_date" class="form-control custom-date" value="<?=date('d-m-Y');?>"  placeholder="" readonly>
                                    <small id="new_visit_date_error" class="form-text text-muted error"></small>
                                </div>
                            </div>
                            <div class="col-xl-6 pl-30">
                                <div class="form-group ">
                                    <label>Specialist</label>
                                    <select id="specialist" name="specialist" class="form-control">
                                        <option  value="" selected>Choose...</option>
                                    <!-- {{LoadCombo("specialist_master","id","specialist_name",isset($patient_data)?$patient_data->specialist_id:'','where display_status=1 AND is_deleted=0',"order by id desc");}} -->
                                        {{LoadCombo("specialist_master","id","specialist_name",'','where display_status=1 AND is_deleted=0',"order by id desc");}}

                                    </select>
                                    <small id="specialist_error" class="form-text text-muted error"></small>
                                </div>
                            </div>
                            @if(Session::get('dtms_visitid') =="")


                                <div class="col-12">
                                    <button type="button" class="btn btn-sm btn-primary mt-1 btn-block" id="createNewVisit" onclick="saveNewVisitData(1)">Create New Visit</button>
                                </div>
                            @endif
                        </div>
                    </form>

                    <form name="dtms-form" id="dtms-form" action="#" >
                    @include('includes/dtms_targets',['data'=>$isenabled,'isactive'=>'DH','pid'=>$patient_data->id])
                    @include('includes/dtms_vitals',['data'=>$isenabled,'isactive'=>'DH','pid'=>$patient_data->id])


                </div>
            </div>

            <div class="col-md-9">
                <div class="row">
                    <div class="col-xl-4 col-lg-12 col-sm-12 pl-0">
                        <div class="card card-sm " >
                            <div class="card-header">
                                <h4 class="card-title">Visit History</h4>
                            </div>
                            <div class="card-body">
                                <div id="loadingdata">
                                    <h4>Loading...</h4>
                                </div>
                                {{-- fixed_header hided --}}
                                <table id="visit_history" class="display table  d-none  table-bordered table-fixed " >
                                    <thead>
                                    <tr>
                                        <th style="width:30%">Visit Type</th>
                                        <th style="width:30%">Code</th>
                                        <th style="width:40%">Date</th>
                                    </tr>
                                    </thead>
                                    <tbody id="visit_data" style="overflow-y: scroll;max-height: 200px">
                                    </tbody>
                                </table>
                                <!-- <span style="flaot:right"><i class='fa fa-search' ondblclick="SearchPatients();" data-toggle="tooltip" data-placement="bottom" title="Double click here to search patients"></i></span> -->
                            </div>
                            <div class="card-footer">
{{--                                <div class="row vistdetails container">--}}
                                <div class="row vistdetails">

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-12 col-sm-12 pl-0">


                        <div class="card card-sm fixed-ht">
                            <div class="card-header">
                                <h4 class="card-title">Test & Results</h4>
                                <button type="button" class="btn btn-sm btn-primary" onclick="outsideLabResult()">Outside Lab Result</button>

                            </div>
                            <div class="card-body row">
                                <div class="col-md-12 custom_scroll">
                                    <div style="padding-inline: inherit;">
                                        <span>Outside Lab</span> <span style="background-color: #6967eb; padding-right: 20px;">&nbsp;</span>
                                        <span style="padding-left: 10px;">Inside Lab </span> <span style="background-color: #FFB6C1; padding-right: 20px;">&nbsp;</span>
                                    </div>
                                    <table id="" class="display table customstdtable" style="display: inline-grid;">
                                        <thead>
                                        <tr>
                                            <th>Test</th>
                                            <th width="25%">Result</th>
                                        </tr>
                                        </thead>
{{--                                        <tbody id="test_data" style="overflow: scroll; height: 278px;">--}}
                                        <tbody id="test_data" style="overflow-y: scroll;max-height: 200px">
                                        @foreach($patient_test_results as $key => $value)
                                        <tr @if($value['is_outside_lab'] == 1) style="background-color: #6967eb;" @else style="background-color: #FFB6C1;" @endif>
                                            <td @if($value['is_outside_lab'] == 1) style="color: white;" @endif>{{ $value['test_name'] }}</td>
                                            <td><input type="text" readonly  class="form-control custom-box" value="{{ $value['result'] }}"></td>
                                        </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
{{--                                <div class="col-md-6 pl-0">--}}
{{--                                    <table id="" class="display table customstdtable">--}}
{{--                                        <thead>--}}
{{--                                        <tr>--}}
{{--                                            <th>Test</th>--}}
{{--                                            <th width="25%">Result</th>--}}



{{--                                        </tr>--}}
{{--                                        </thead>--}}
{{--                                        <tbody class="table-box-color-2">--}}
{{--                                        <tr>--}}
{{--                                            <td >ERS</td>--}}
{{--                                            <td><input type="text" class="form-control custom-box"></td>--}}

{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td  >Urea</td>--}}
{{--                                            <td><input type="text" class="form-control custom-box"></td>--}}
{{--                                        </tr>--}}
{{--                                        <tr>--}}
{{--                                            <td  >Creatine</td>--}}
{{--                                            <td><input type="text" class="form-control custom-box"></td>--}}
{{--                                        </tr>--}}




{{--                                        </tbody>--}}
{{--                                    </table>--}}
{{--                                </div>--}}

                                <div class="col-12">
                                    <button type="button" class="btn btn-sm btn-primary mt-1 btn-block p-reminders" onclick="addPatientReminders();">Patient Reminders</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg12 col-sm-12 pl-0">
                        <div class="card card-sm fixed-ht">
                            <div class="card-header">
                                <h4 class="card-title">New Visit</h4>

                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4 p-0">  <input readonly type="text" class="form-control custom-box" id="info_visit_no"></div>
                                        <div class="col-md-4 p-0">  <input readonly type="text" class="form-control custom-box" id="info_uhid_no"></div>
                                        <div class="col-md-4 p-0">  <input readonly type="text" class="form-control custom-box" id="info_visit_date"></div>
                                        {{-- <div class="col-md-2 p-0">  <input type="text" class="form-control custom-box"></div>
                                        <div class="col-md-3 p-0">  <input type="text" class="form-control custom-box"></div> --}}
                                    </div>
                                </div>
                                <hr>
                                <div class="col-md-12">
                                    <div class="row">
                                        <!-- <div class="col-md-6 p-0">
                                            <textarea class="form-control" cols="8" placeholder="Insulin"></textarea>
                                        </div> -->
                                        <div class="col-md-12  p-0">
                                            <textarea class="form-control" cols="8" placeholder="Remarks" name="remark" id="remark"></textarea>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-sm-12 pl-0">
                        <div class="card card-sm">
                            <div class="card-header">
                                <h4 class="card-title">Prescriptions</h4>
                                <button type="button" class="btn btn-sm btn-primary" id="prescription_print"><i class="fa fa-print" aria-hidden="true"></i></button>
                                <div class="col-4">
                                    <input type="text" class="form-control w-45p float-left" id="copy_prescription">
                                    <button type="button" class="btn btn-sm btn-primary" id="copy_prescription_btn">Copy</button>
                                </div>
                            </div>
                            <div class="card-body">
                            @include('includes/dtms_prescriptions',['data'=>$isenabled,'isactive'=>'DH','pid'=>$patient_data->id])
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-12 text-center">
                <button type="button" class="btn btn-sm btn-primary mt-1" onclick="saveAllDtmsData(1)" id="saveAllDtmsDatabtn">Save</button>
                <center class="saveAllDtmsDatabtnText">Please select a visit to continue</center>
            </div>

            </form>
        </div>





    </div>
</div>
@include('frames/footer');
@include('modals/addvisit_modal',['title'=>'Create New Visit'])
@include('modals/diagnosis_modal',['title'=>'Patient Diagnosis'])
@include('modals/outside_lab_result_modal',['title'=>'Outside Lab Result'])
@include('modals/complication_modal',['title'=>'Patient Complication'])
@include('modals/medical_history_modal',['title'=>'Medical History','medical_history'=>$medical_history])
@include('modals/diet_history_modal',['title'=>'Diet History','diet_history'=>$diet_history])
@include('modals/diet_history_answer_sheet_modal',['title'=>'Diet History','diet_history'=>$diet_history])
@include('modals/pep_modal',['title'=>'Patient education module','isenabled'=>$isenabled,'pep_history'=>$pep_history,'pep_questions_data'=>$pep_questions_data])
@include('modals/pep_answer_sheet_modal',['title'=>'Patient education module','isenabled'=>$isenabled,'pep_history'=>$pep_history,'pep_questions_data'=>$pep_questions_data])

@include('modals/miscellaneous_modal',['title'=>'Miscellaneous','isenabled'=>$isenabled])
@include('modals/miscellaneous_details_modal',['title'=>'Miscellaneous','isenabled'=>$isenabled])
@include('modals/vaccination_modal',['title'=>'Patient Vaccination'])
@include('modals/view_old_medicine_data_modal',['title'=>'Previous medication'])
@include('modals/alert_modal',['title'=>'Alerts'])
@include('modals/abroad_details_modal',['title'=>'Abroad Details'])
@include('modals/patient_reminders_modal',['title'=>'Patient Reminders'])
@include('modals/more_details_modal',['title'=>'Patient Details'])
@include('modals/vaccination_modal',['title'=>'Create New Vaccination'])
{{--@include('modals/alert_modal',['title'=>'Create New Alert','data'=>'dfsds'])--}}
@include('modals/photos_modal',['title'=>'Patient Documents'])
@include('modals/patient_gallery_modal',['title'=>'Patient Gallery'])
@include('modals/target_modal',['title'=>'Targets'])

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
    .header {
        height: 3.5rem;
    }
    .nav-control {
        top:24%;
    }
    [data-header-position="fixed"] .content-body {
        padding-top: 4.5rem;
    }


    textarea.form-control {
        height: 155px;
    }
    .bootstrap-select .btn {
        min-height:22px;
    }
    .bootstrap-select .btn{
        padding:0.15rem;
    }

    .custom-date{
        height: 27px;
    }

    .visit-active {
        background-color: #01579B;
        color: white;
    }
</style>

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<div class="floating-container">
    <div class="floating-button"><i class="fa fa-gear"></i></div>
    <div class="element-container">

        {{-- <a href="google.com"> <span class="float-element tooltip-left">
           <i class="material-icons">phone
           </i></a>
         </span>
           <span class="float-element">
           <i class="material-icons">email
     </i>
         </span>
           <span class="float-element">
           <i class="material-icons">chat</i>
         </span> --}}
        <div class="text-center p-3  float-element">
            <ul class="list-group">

                <a  href="{{url('dtmshome/'.$patient_data->id)}}" ><li class="list-group-item"    >DTMS Home</li></a>
                <a href="#" onclick="viewTestResultChart('{{ $patientId }}')" class=""><li class="list-group-item">Visit Chart</li></a>
                <a href="#" class="" onclick="viewMhModel()" ><li class="list-group-item" >Medical History</li></a>
                <a href="#" class="" onclick="viewDietHistoryAnswerSheetModel()" ><li class="list-group-item" >Diet History</li></a>
                <a href="#" class="" onclick="viewPepAnswerSheetModal()"><li class="list-group-item " >Patient education module (PEP)</li></a>
                {{-- <a href="{{url('prescription')}}" class=""><li class="list-group-item  ">Prescription Management</li></a> --}}
                <a  href="#" class="" onclick="viewVacinationModel()"><li class="list-group-item">Vaccination Details</li></a>
                <a  href="#" class="" onclick="addAlert()"><li class="list-group-item">Alert</li></a>
                {{-- <a href="#" class="" onclick="viewMedicationModel()"><li class="list-group-item">Medications</li></a> --}}
                <a href="#" class="" onclick="getMiscellaneousData()"><li class="list-group-item">Miscellaneous module</li></a>

                <a href="#" class="" onclick="viewPhotos()"><li class="list-group-item ">Patient Documents</li></a>
                <a href="#" class="" onclick="viewPatientGallery()"><li class="list-group-item ">Patient Gallery</li></a>
                @php
                    $patientId = base64_encode($patient_data->id);
                @endphp
                <a href="#" onclick="gotograph('{{ $patientId }}')" class=""><li class="list-group-item">Chart</li></a>
               
                {{-- <a href="{{url('miscellaneous')}}" class=""><li class="list-group-item ">Pharmacy</li></a> --}}
                <a href="#" class="" onclick="viewMoreDetailsModal()"><li class="list-group-item">More Details</li></a>
                <a href="#" class="" onclick="addAbroadData()"><li class="list-group-item">Abroad Details</li></a>

            </ul>

        </div>
    </div>
</div>



<link rel="stylesheet" href="{{asset('/vendor/select2/css/select2.min.css')}}">
<link href="{{asset('/vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
<script src="{{asset('date_picker/js/bootstrap-datepicker.min.js')}}" type='text/javascript'></script>
<script src="{{asset('./vendor/select2/js/select2.full.min.js')}}"></script>
<!-- <script src="{{asset('./js/plugins-init/select2-init.js')}}"></script> -->

<script>
    function  gotograph(patientId) {
        window.open('{{url("view-all-test-results")}}/' + patientId ,'_blank' );

    }
</script>


<script>
    $(document).ready(function(){
        loadImageData();
        $('#new_visit_date').datepicker({
            autoclose: true,
            // endDate: '+0d',
            startDate: '+0d',
            format: 'dd-mm-yyyy'
        });
        $('#saveAllDtmsDatabtn').attr('disabled',true);
        $('.saveAllDtmsDatabtn').attr('disabled',true);
        $('#prescription_print').attr('disabled',true);
        $('#old-medicines').attr('disabled',true);
        $('.saveAllDtmsDatabtnText').css('display','block');

    });
    $('#start_visit').click(function() {
        $('#pep-modal').modal('toggle');
        $('#addvisit_modal').modal('toggle');
    });

    $('.floating-container').click(function(e){
        e.preventDefault();
        $('.float-element').fadeToggle();
    })
    function outsideLabResult(){
        $('#outside-lab-result').modal();
    }

    function addDiagnosis(){
        $('#diagnosis-modal').modal();
        $("#diagnosis_data").dataTable().fnDestroy()
        table = $('#diagnosis_data').DataTable({
            dom: 'lfrtip',
            'ajax': {
                url: "<?php echo url('/') ?>/getDiagnosisData",
                type: 'POST',
                "data": function(d) {}
            },
            "columns": [{
                "data": "id",
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
                {
                    "data": "diagnosis_name",
                },

                {
                    "data": "diagnosis_year",
                },
                {
                    "data": "examined_date",
                },
                {
                    "data": "id",
                    "render": function(data, type, full, meta) {

                        return '<div class="d-flex"><a href="#" class="delete_diagnosis_data btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                    }
                },
            ]
        });
        $('#diagnosis_data').DataTable().ajax.reload();
    }

    function addPatientReminders(){
        $('#patient-reminders-modal').modal();
        $("#PatientReminder").dataTable().fnDestroy()
        table = $('#PatientReminder').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            'ajax': {
                url: "<?php echo url('/') ?>/getPatientReminders",
                type: 'POST',
                "data": function(d) {}
            },
            "columns": [{
                "data": "id",
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
                {
                    "data": "uhidno",
                },
                {
                    "data": "date",
                    "render": function(data, type, full, meta) {
                        var date = new Date(data);
                        var month = date.getMonth() + 1;
                        return date.getDate() + "-" + (month.toString().length > 1 ? month : "0" + month) + "-" + date.getFullYear();
                    }
                },
                {
                    "data": "details",
                },
                {
                    "data": "remarks",
                },
                {
                    "data": "name",
                },
                {
                    "data": "id",
                    "render": function(data, type, full, meta) {
                        return '<div class="d-flex"><a href="#" class="edit_reminders btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a><a href="#" class="delete_reminders btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                    }
                },
            ]
        });

    }

    function addComplication(){
        $('#complication-modal').modal();
        $("#complication_data").dataTable().fnDestroy()
        table = $('#complication_data').DataTable({
            dom: 'lfrtip',
            'ajax': {
                url: "<?php echo url('/') ?>/getComplicationData",
                type: 'POST',
                "data": function(d) {}
            },
            "columns": [{
                "data": "id",
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
                {
                    "data": "complication_name",
                },

                {
                    "data": "subcomplication_name",
                },
                {
                    "data": "complication_year",
                },
                {
                    "data": "examined_date",
                },
                {
                    "data": "id",
                    "render": function(data, type, full, meta) {

                        return '<div class="d-flex"><a href="#" class="delete_complication_data btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                    }
                },
            ]
        });
        $('#complication_data').DataTable().ajax.reload();
    }

    function addVaccination(){
        $('#vaccination-modal').modal();
    }

    function addAlert(){
        getAlertData();
        $('#alert-modal').modal();
    }

    function loadImageData(){
        getImageData();

    }
    function getImageData(){
        var patient_id = $('#patient_id').val();
        $.ajax({
            url: "{{ route('get-image-alert') }}",
            type: 'POST',
            data: { patient_id : patient_id },
            success : function(result) {
                console.log(result);
                if (result.status == 1) {
                    $("#image-status").show();
                }

            },
        });

    }

    function addAbroadData(){
        $('#abroad-modal').modal();

        $("#abroad_data").dataTable().fnDestroy()
        table = $('#abroad_data').DataTable({
            dom: 'lfrtip',
            'ajax': {
                url: "<?php echo url('/') ?>/getAbroadData",
                type: 'POST',
                "data": function(d) {}
            },
            "columns": [{
                "data": "id",
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
                {
                    "data": "patient_name",
                },
                {
                    "data": "phone_no",
                },
                {
                    "data": "email_id",
                },
                {
                    "data": "address",
                },
                {
                    "data": "id",
                    "render": function(data, type, full, meta) {

                        return '<div class="d-flex"><a href="#" class="delete_abroad_data btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                    }
                },
            ]
        });
        $('#abroad_data').DataTable().ajax.reload();

    }

    function viewMoreDetailsModal(){
        $('#more-details-modal').modal();
    }

    function viewComplication(){
        $('#view-complication-modal').modal();
    }

    function viewDiagnosis(){
        $('#view-diagnosis-modal').modal();
    }
    function viewMhModel(){
        $(".medical-history-edit :input").attr("readonly", true);
        $('#medical-history-modal').modal();
    }
    function viewDietHistoryAnswerSheetModel(){
        $('#diet-history-answer-sheet-modal').modal();

        $("#diet_history_answer_sheet_data").dataTable().fnDestroy()
        table = $('#diet_history_answer_sheet_data').DataTable({
            dom: 'lfrtip',
            'ajax': {
                url: "<?php echo url('/') ?>/get-diet-history-answer-sheet",
                type: 'POST',
                "data": function(d) {}
            },
            "columns": [{
                "data": "id",
                "className": "text-right",
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
                {
                    "data": "type_name",
                    "className": "text-right",

                },
                {
                    "data": "created_at",
                    "className": "text-right",
                    "render": function(data, type, full, meta) {
                        var date = new Date(data);
                        var month = date.getMonth() + 1;
                        return date.getDate() + "-" + (month.toString().length > 1 ? month : "0" + month) + "-" + date.getFullYear();
                    }
                },
                {
                    "data": "id",
                    "className": "text-right",
                    "render": function(data, type, full, meta) {

                        return '<a href="javascript:void(0)" target="_blank" class="active_link diet-history-print"><i class="fa fa-print" aria-hidden="true"></i></a>';
                    }
                },
            ]
        });
        $('#diet_history_answer_sheet_data').DataTable().ajax.reload();
    }


    function viewPepAnswerSheetModal(){
        $('#pep-answer-sheet-modal').modal();
        $("#pep_answer_sheet_data").dataTable().fnDestroy()
        table = $('#pep_answer_sheet_data').DataTable({
            dom: 'lfrtip',
            'ajax': {
                url: "<?php echo url('/') ?>/get-pep-answer-sheet",
                type: 'POST',
                "data": function(d) {}
            },
            "columns": [{
                "data": "id",
                "className": "text-right",
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
                {
                    "data": "type_name",
                    "className": "text-right",

                },
                {
                    "data": "created_at",
                    "className": "text-right",
                    "render": function(data, type, full, meta) {
                        var date = new Date(data);
                        var month = date.getMonth() + 1;
                        return ('0' + date.getDate()).slice(-2) + "-" + (month.toString().length > 1 ? month : "0" + month) + "-" +   date.getFullYear();
                    },
                },
                {
                    "data": "id",
                    "className": "text-right",
                    "render": function(data, type, full, meta) {

                        return '<a href="javascript:void(0)" target="_blank" class="active_link pep-print"><i class="fa fa-print" aria-hidden="true"></i></a>';
                    }
                },
            ]
        });
        $('#pep_answer_sheet_data').DataTable().ajax.reload();

    }

    function getMiscellaneousData() {
        $('#get-miscellaneous-list-modal').modal();

        $("#get_miscellaneous_data").dataTable().fnDestroy()
        table = $('#get_miscellaneous_data').DataTable({
            dom: 'lfrtip',
            'ajax': {
                url: "<?php echo url('/') ?>/get-miscellaneous-module",
                type: 'POST',
                "data": function(d) {}
            },
            "columns": [{
                "data": "id",
                "className": "text-right",
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
                {
                    "data": "type_name",
                    "className": "text-right",

                },
                {
                    "data": "created_at",
                    "className": "text-right",
                    "render": function(data, type, full, meta) {
                        var date = new Date(data);
                        var month = date.getMonth() + 1;
                        return date.getDate() + "-" + (month.toString().length > 1 ? month : "0" + month) + "-" + date.getFullYear();
                    }
                },
                {
                    "data": "id",
                    "className": "text-right",
                    "render": function(data, type, full, meta) {

                        return '<a href="javascript:void(0)" target="_blank" class="active_link miscellaneous-print"><i class="fa fa-print" aria-hidden="true"></i></a>';
                    }
                },
            ]
        });
        $('#get_miscellaneous_data').DataTable().ajax.reload();

    }

    function viewMiscellaneousModel(){
        $('#miscellaneous-modal').modal();
        $('#get-miscellaneous-list-modal').modal('hide');
        $('#hereditary_incidence').addClass('d-none');
        $('#loadingdata').removeClass('d-none');
        getMiscellaneousQuestions();
        getHeriditarydetails();
        getgfrdetails();

    }
    function viewPhotos(){
        $('#photos-modal').modal();
        $("#photos_data").dataTable().fnDestroy()

        table = $('#photos_data').DataTable({
            dom: 'lfrtip',


            'ajax': {
                url: "<?php echo url('/') ?>/getPhotos",
                type: 'POST',
                "data": function(d) {

                }
            },
            "columns": [{
                "data": "id",
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },

                {
                    "data": "image",
                    "render": function(data, type, full, meta) {
                        return '<a href="../images/'+data+'" target="_blank" class="active_link">'+data+'</a>';

                    }
                },
                {
                    "data": "type",
                    "render": function(data, type, full, meta) {
                        if(data==1)
                            return 'From Gallery';
                        else
                            return 'From Web Cam';
                    }
                },
                {
                    "data":"remarks"
                },
                {
                    "data":"category_name"
                },
                {
                    "data": "created_at",
                    "render": function(data, type, full, meta) {
                        var date = new Date(data);
                        var month = date.getMonth() + 1;
                        return date.getDate() + "-" + (month.toString().length > 1 ? month : "0" + month) + "-" +   date.getFullYear();


                    },
                },
                {
                    "data": "id",
                    "render": function(data, type, full, meta) {

                        return '<div class="d-flex"><a href="#" class="delete_photo btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                    }
                },

            ]
        });
        $('#photos_data').DataTable().ajax.reload();
    }

    function viewPatientGallery(){
        $('#patient-gallery-modal').modal();
        $("#patient_gallery_data").dataTable().fnDestroy()

        table = $('#patient_gallery_data').DataTable({
            dom: 'lfrtip',


            'ajax': {
                url: "<?php echo url('/') ?>/get-patient-gallery",
                type: 'POST',
                "data": function(d) {

                }
            },
            "columns": [{
                "data": "id",
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },

                {
                    "data": "image",
                    "render": function(data, type, full, meta) {
                        return '<a href="../images/'+data+'" target="_blank" class="active_link">'+data+'</a>';

                    }
                },
                {
                    "data": "upload_type",
                    "render": function(data, type, full, meta) {
                        if(data==1)
                            return 'From Gallery';
                        else
                            return 'From Web Cam';
                    }
                },
                {
                    "data": "created_at",
                    "render": function(data, type, full, meta) {
                        var date = new Date(data);
                        var month = date.getMonth() + 1;
                        return date.getDate() + "-" + (month.toString().length > 1 ? month : "0" + month) + "-" +   date.getFullYear();


                    },
                },
                {
                    "data": "id",
                    "render": function(data, type, full, meta) {

                        return '<div class="d-flex"><a href="#" class="delete_photo_gallery btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                    }
                },

            ]
        });
        $('#patient_gallery_data').DataTable().ajax.reload();
    }

    function viewTestResultChart(patientId){
        window.open('{{url("view-test-result-chart")}}/' + patientId ,'_blank' );

    }

    function viewVacinationModel(){
        $('#vaccination-modal').modal();
        $("#vaccination_data").dataTable().fnDestroy()
        table = $('#vaccination_data').DataTable({
            dom: 'lfrtip',
            'ajax': {
                url: "<?php echo url('/') ?>/getVaccinationData",
                type: 'POST',
                "data": function(d) {}
            },
            "columns": [{
                "data": "id",
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
                {
                    "data": "vaccination_name",
                },

                {
                    "data": "remarks",
                },
                {
                    "data": "vaccination_date",
                    "render": function(data, type, full, meta) {
                        var date = new Date(data);
                        var month = date.getMonth() + 1;
                        return date.getDate() + "-" + (month.toString().length > 1 ? month : "0" + month) + "-" + date.getFullYear();
                    }
                },
                {
                    "data": "id",
                    "render": function(data, type, full, meta) {

                        return '<div class="d-flex"><a href="#" class="delete_vaccination_data btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                    }
                },
            ]
        });
        $('#vaccination_data').DataTable().ajax.reload();
    }
    //   function viewMedicationModel(){
    //         $('#medication-modal').modal();
    //   }

    // function viewVaccination(){
    //     $('#view-vaccination-modal').modal();
    // }



    // $('#visit_history tbody').on('click','tr',function() {

    function getVistData(id){
        localStorage.setItem("dtms_visitId", id);
        $(this).addClass("active");
        // var id = $(this).attr('id');
        $.ajax({
            url: "<?php echo url('/') ?>/visitHistory",
            method: 'POST',
            data: { id : id },
            success: function(result) {
                var jsondata = $.parseJSON(result);
                for(var i=1; i<=4; i++){
                    $("#bps_status_time_"+i).val('');
                    $("#bps_status_bps_"+i).val('');
                    $("#bps_status_bpd_"+i).val('');
                    $("#bps_status_pulse_"+i).val('');
                }
                $('.vital_sign_data').val('');
                $('#remark').val('');


                if (jsondata.bp_data.length>0){
                    $.each(jsondata.bp_data, function(index, element) {
                        var index2=parseInt(index)+1;
                        $("#bps_status_time_"+index2).val(element.time);
                        $("#bps_status_bps_"+index2).val(element.bps);
                        $("#bps_status_bpd_"+index2).val(element.bpd);
                        $("#bps_status_pulse_"+index2).val(element.pulse);
                    });
                }
                if (jsondata.vital_data.length>0){
                    $.each(jsondata.vital_data, function(index, element) {
                        $("#vital_"+element.vitals_id).val(element.vitals_value);
                    });
                }

                // Target
                var html='';
                if (jsondata.patient_test_targets.length>0){
                    $.each(jsondata.patient_test_targets, function(index, element) {
                       html+="<tr>";
                       html+="<td>"+element.TestName+"</td>";
                       html+="<td><input type='text' class='form-control custom-box patient_target_"+element.test_master_id +"' name='patient_target_"+element.test_master_id+"' readonly value='"+element.target_value+"'></td>";
                       html+="<td><input type='text' class='form-control custom-box patient_present_"+element.test_master_id +"' name='patient_present_"+element.test_master_id+"'  value='"+element.present_value+"'></td>";
                       html+="</tr>";
                    });
                }
                // Target Details
                if (jsondata.patient_target_details) {
                    html += "<tr>";
                    html += "<td>Weight</td>";
                    html += "<td><input type='text' class='form-control custom-box' name='weight_target'  value='"+jsondata.patient_target_details.weight_target+"'></td>";
                    html += "<td><input type='text' class='form-control custom-box ' name='weight_present'  value='"+jsondata.patient_target_details.weight_present+"'></td>";
                    html += "</tr>";
                    html += "<tr>";
                    html += "<td>Action Plan</td>";
                    html += "<td colspan='2'><textarea cols='2' rows='2' name='action_plan' id='action_plan' class='form-control custom-box' style='height: 78px;'>"+jsondata.patient_target_details.action_plan+"</textarea></td>";
                    html += "</tr>"
                   // html += "<td>Fibro scan value</td>";
                    //html += "<td colspan='2'><textarea cols='2' rows='2' name='fibro_scan' id='fibro_scan' class='form-control custom-box' style='height: 78px;'>"+jsondata.patient_target_details.fibro_scan+"</textarea></td>";
                    html += "</tr>";
                }
                $('.target_data').empty().append(html);


                var tempDate = new Date(jsondata.remark_data.visit_date);
                var formattedDate = [ tempDate.getDate(),tempDate.getMonth() + 1, tempDate.getFullYear()].join('-');


                $('#remark').val(jsondata.remark_data.dtms_remarks);
                $('#visit_type_id').val(jsondata.remark_data.visit_type_id).change();
                $('#specialist').val(jsondata.remark_data.specialist_id).change();
                $('#info_visit_no').val(jsondata.remark_data.id);
                $('#info_uhid_no').val(jsondata.uhidNo);
                $('#info_visit_date').val(formattedDate);
                $('#fibro_scan').val(jsondata.remark_data.fibro_scan);
                



                $('#new_visit_date').val(formattedDate);


                // prescriptionData
                $('#append_prescription_data').empty();
                if (jsondata.prescriptionData.length>0){

                    $.each(jsondata.prescriptionData, function(index, element) {

                        add_duplicate(element.tablet_type_id,element.medicine_id,element.remarks,element.dose,element.tablet_type_name,element.medicine_name, true);


                    })
                }

            }
        });


        $('#collapseVitals').collapse();
        $('#collapseBPS').collapse();
        $('#collapseTarget').collapse();

        $('#saveAllDtmsDatabtn').attr('disabled',false);
        $('.saveAllDtmsDatabtn').attr('disabled',false);
        $('#prescription_print').attr('disabled',false);
        $('#old-medicines').attr('disabled',false);
        $('.saveAllDtmsDatabtnText').css('display','none');

        getTestResultData(id);
    }

    function getTestResultData(id)
    {
        $.ajax({
            url: "<?php echo url('/') ?>/testResultData",
            method: 'POST',
            data: { id : id },
            success: function(result) {
                var jsondata = $.parseJSON(result);
                $("#test_data").empty('');
                var html='';
                if (jsondata.test_result_data.length>0){
                    $.each(jsondata.test_result_data, function(index, element) {
                        var outstideLabStyle = "background-color: #FFB6C1;;";
                        var outstideLabInner = "";
                        if (element.is_outside_lab ==  1) {
                            outstideLabStyle ="background-color: #6967eb;";
                            outstideLabInner ="color: white;";
                        }
                        html+='<tr style="' + outstideLabStyle + '">';
                        html+='<td style="' + outstideLabInner + '">' + element.test_name + '</td>';
                        html+='<td><input type="text" readonly  class="form-control custom-box" value="' + element.result + '"></td>';
                        html+="</tr>";
                    });
                    $("#test_data").append(html);
                }
                console.log(id);
            }
        });
    }

</script>
<script>

$(document).ready(function(){
    localStorage.removeItem("dtms_visitId");

});
    function saveAllDtmsData(crude=1)
    {
        var form = $('#dtms-form')[0];
        var formData = new FormData(form);
        var remark = $('#remark').val();
        var visitTypeId = $('#visit_type_id').find(":selected").val();
        var visitDate = $('#new_visit_date').val();
        var trcount=$('#collapseBPS tr').length;



        var textinputs = document.querySelectorAll('input[name*=dose]');
        for( var i = 0; i < textinputs.length; i++ )
            formData.append('doselist[]', textinputs[i].value);


        var textinputs2 = document.querySelectorAll('input[name*=remarks]');
        for( var i = 0; i < textinputs2.length; i++ )
            formData.append('remarkslist[]', textinputs2[i].value);


        var textinputs3 = document.getElementsByName('medicine_id[]');
        for( var i = 0; i < textinputs3.length; i++ )
            formData.append('medicine_idlist[]', textinputs3[i].value);


        var textinputs4 = document.getElementsByName('tablet_type_id[]');
        for( var i = 0; i < textinputs4.length; i++ )
            formData.append('tablet_type_idlist[]', textinputs4[i].value);





        formData.append('crude', crude);
        formData.append('trcount', trcount);
        formData.append('remark', remark);
        formData.append('visit_type_id', visitTypeId);
        formData.append('visit_date', visitDate);
        url='{{route('saveDtmsData')}}';

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(result){
                if (result.status == 1) {
                    $('#visit_data tr').removeClass("active");
                    // $("#tablettype_options").select2("val", "");
                    $('#tablettype_options').val('').selectpicker('refresh');

                    swal("Done", result.message, "success");
                    medicine = [];

                    var visitId = localStorage.getItem("dtms_visitId");

                   // getVistData(visitId);
                } else if (result.status == 2) {
                    $('#visit_data tr').removeClass("active");
                    swal("Done", result.message, "success");
                } else {
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
    $(document).ready(function(){

        // $("#medicine_options_1").select2();

        $('#copy_prescription_btn').click(function(e){
            e.preventDefault();
            var vistcopycode=$('#copy_prescription').val();
            if(!vistcopycode){
                sweetAlert("Oops...", "Please enter a visit code and then copy", "warning");
            }
            $.ajax({
                url: "<?php echo url('/') ?>/visitHistoryById",
                method: 'POST',
                data: { id : vistcopycode },
                success: function(result) {
                    var jsondata = $.parseJSON(result);
                    $('#append_prescription_data').empty();
                    if (jsondata.prescriptionData.length>0){

                        $.each(jsondata.prescriptionData, function(index, element) {


                            add_duplicate(element.tablet_type_id,element.medicine_id,element.remarks,element.dose,element.tablet_type_name,element.medicine_name);

                        });
                    }
                }
            });
        });
        $('#prescription_print').click(function(e){
            e.preventDefault();
            window.open('{{url("prescription-print-data")}}', '_blank');
        });
    });

</script>
<script>
    $(document).ready(function(){
        getDtmsProfileData();
    });
    function getDtmsProfileData()
    {
        $.ajax({
            url: "{{ route('getDtmsProfileData') }}",
            type: 'POST',
            success : function(result) {
                var jsondata = $.parseJSON(result);
                if (jsondata.diagnosis_info != '') {
                    $('#diagnosis-info').html("<b>Diagnosis</b> : "+jsondata.diagnosis_info);
                    $('.diagnosis-info-block').show();
                } else {
                    $('.diagnosis-info-block').hide();
                }

                if (jsondata.complication_info != '') {
                    $('#complication-info').html("<b>Complication</b> : "+jsondata.complication_info);
                    $('.complication-info-block').show();
                } else {
                    $('.complication-info-block').hide();
                }

                $('#sub-complication-info').html(jsondata.sub_complication_info);
            },
        });
    }
</script>
<style>
    button#medicine_options_1{
        display:none!important;
    }
    .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
        width: 100%;
    }
    .select2-container--default .select2-selection--single {
        border: 1px solid #d7dae3;
    }


    /* .tablettype_options{
        width:100%;
    } */


</style>


<script>

    $(document).ready(function(){
        $(document).ajaxSend(function(){
            $('#loader').hide();
        });
        $(".js-data-example-ajax").select2({
            placeholder: "Search Medicine",
            ajax: {
                url: "{{ route('searchMedicineNames') }}",
                type: "post",
                dataType: 'json',
                // delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term, // search term
                        typeid :$('#tablettype_options').val(),
                    };

                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $('.js-data-example-ajax').on('select2:select', function (e) {
            var data = e.params.data;
            add_duplicate(data.tablet_typeid,data.id,'','',data.tablet_type_name,data.text);


        });


        var input = document.getElementById("scr");


        input.addEventListener("keypress", function(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                ();
            }
        });
    });

</script>

<script>
    $(document).ready(function(){
        getNewVisitData();
    });
    function getNewVisitData()
    {
        $.ajax({
            url: "{{ route('getNewVisitData') }}",
            type: 'POST',
            success : function(result) {
                $('.vistdetails').html('');
                $('#visit_data').html('');
                var jsondata = $.parseJSON(result);
                $('#visit_data').append(jsondata.visit_list);
                $.each(jsondata.visit_data, function(idx, obj) {
                    // $('.vistdetails').append('<div class="col-md-4"><p>' + obj.visit_name + '</p></div><div class="col-md-2"><p>' + obj.visit_count + '</p></div>');
                    $('.vistdetails').append("<span class='badge badge-info'>"+ obj.visit_name +" <span class='badge badge-light'>" + obj.visit_count +"</span></span>");

                });
                $('#visit_history').removeClass('d-none');
                $('#loadingdata').addClass('d-none');
            },
        });
    }


    function saveNewVisitData(crude=1)
    {
        $("[id*='_error']").text('');
        var form = $('#create-new-visit-form')[0];
        var formData = new FormData(form);
        formData.append('crude', crude);
        url='{{route('saveNewVisitData')}}';

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
                    var form = $('#create-new-visit-form')[0];
                    document.getElementById("create-new-visit-form").reset();
                    $('#visit_type_id, #specialist').val('').selectpicker('refresh');
                    getNewVisitData();
                } else {
                    sweetAlert("Oops...", result.message, "error");
                }

            },
            error: function(result,jqXHR, textStatus, errorThrown){
                if( result.status === 422 ) {
                    result=result.responseJSON;
                    var error=result.errors;
                    $.each(error, function (key, val) {
                        console.log([key,val]);
                        let errorMsg = "This field is required";
                        // if(key == 'visit_type_id') {
                        //     errorMsg = "Visit Type Field is Required."
                        // }
                        // if(key == 'new_visit_date') {
                        //     errorMsg = "Date Field is Required."
                        // }
                        $("#" + key + "_error").text(errorMsg);
                    });
                }

            }
        });
    }
</script>
<script>
    $(document).ready(function() {

        $(document).on('click', 'tr.visit-data-tr', function(){
            $('#visit_data tr').removeClass("visit-active");
            $(this).toggleClass('visit-active');
        });

        if($("#patient-profile-img").val() == 1) {
            $('.toast').toast('show');
        }


    });
</script>
