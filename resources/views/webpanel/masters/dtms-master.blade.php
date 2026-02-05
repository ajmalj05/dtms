<style>
    table.dataTable
    {
        width: 100% !important;
    }
    #maintable td {
        width: 150px;
        text-align: center;
        border: 1px ;
        padding: 5px;
      }
</style>
<div class="content-body">
  <div class="container-fluid pt-2">
    <div class="row" style="">
      <div class="col-md-12">

        <div class="profile-tab pb-2">
          <div class="custom-tab-1">
            <ul class="nav nav-tabs">
              <li class="nav-item"><a href="#visit-type" data-toggle="tab" class="nav-link active show">Visit
                  Type</a>
              </li>
              <li class="nav-item"><a href="#Diagnosis" data-toggle="tab" class="nav-link">Diagnosis</a>
              </li>
              <li class="nav-item"><a href="#complicationtab" data-toggle="tab" class="nav-link">Complication</a>
              </li>
              <li class="nav-item"><a href="#sub-complication" data-toggle="tab" class="nav-link">Sub Complication</a>
              </li>
             
              <li class="nav-item"><a href="#questionaries" data-toggle="tab" class="nav-link">Questionnaires</a>
              </li>
              <li class="nav-item"><a href="#miscellaneous" data-toggle="tab" class="nav-link">Miscellaneous</a>
              </li>
              <li class="nav-item"><a href="#sub-diagnosis" data-toggle="tab" class="nav-link">Sub Diagnosis</a>
              </li>
            </ul>
            <div class="tab-content pt-3">
              <div id="visit-type" class="tab-pane fade active show">

                <div class="row">
                  <div class="col-xl-4">
                    <div class="card">
                      <div class="card-body">

                        <div class=" mb-5">
                          <!-- <h5 class="text-primary d-inline">Today Highlights</h5><a href="javascript:void()" class="pull-right f-s-16">More</a> -->
                          <form action="#"  name="frm" id="frm" action="" method="POST">
                              <input type="hidden" name="hid_visit_type_id" id="hid_visit_type_id">
                            <div class="form-group">
                              <label class="text-label"> Visit Type Name<span class="required">*</span></label>
                              <input type="text" name="visit_type_name" id="visit_type_name" onKeyPress="return blockSpecialChar(event)" maxlength="80" class="form-control" placeholder="" required>
                              <small id="visit_type_name_error" class="form-text text-muted error"></small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="display_status" id="display_status" class="form-check-input" checked >
                              <label class="form-check-label" for="displayStatus">Display Status</label>
                            </div>

                            <div id="crude">
                                <button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(1,1)" >Save</button>
                            </div>
                          </form>

                        </div>

                      </div>
                    </div>
                  </div>
                  <div class="col-xl-8">
                    <div class="card">
                      <div class="card-body">
                        <div class="table-responsive pt-3">
                          <table id="VisitType" class="display">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>Visit Type</th>
                                <th>Display Status</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>


                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div id="Diagnosis" class="tab-pane fade">

                <div class="row">
                  <div class="col-xl-4">
                    <div class="card">
                      <div class="card-body">

                        <div class=" mb-5">
                            <form name="frm1" id="frm1" method="POST" action="">
                                <input type="hidden" name="hid_diagnosis_id" id="hid_diagnosis_id">
                            <div class="form-group">
                              <label class="text-label">Diagnosis Name<span class="required">*</span></label>
                              <input type="text" name="diagnosis_name" id="diagnosis_name" onKeyPress="return blockSpecialChar(event)" maxlength="50" class="form-control" placeholder="" required>
                              <small id="diagnosis_name_error" class="form-text text-muted error"></small>
                            </div>
                            <div class="form-group">
                              <label class="text-label">Diagnosis Code</label>
                              <input type="text" name="diagnosis_code" id="diagnosis_code" onKeyPress="return blockSpecialChar(event)" maxlength="50" class="form-control" placeholder="" required>
                              <small id="diagnosis_code_error" class="form-text text-muted error"></small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="display_status" id="display_status_diagnosis" class="form-check-input" checked >
                              <label class="form-check-label" for="displayStatus">Display Status</label>
                            </div>

                            <div id="crud_diagnosis">
                                <button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(2,1)" >Save</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-8">
                    <div class="card">
                      <div class="card-body">
                        <div class="table-responsive pt-3">
                          <table id="DiagnosisTable" class="display">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>Diagnosis Name</th>
                                <th>Diagnosis Code</th>
                                <th>Display Status</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div id="complicationtab" class="tab-pane fade">
                  <div class="row">
                    <div class="col-xl-4">
                      <div class="card">
                        <div class="card-body">

                          <div class=" mb-5">
                            <!-- <h5 class="text-primary d-inline">Today Highlights</h5><a href="javascript:void()" class="pull-right f-s-16">More</a> -->
                            <form action="#"  name="frm2" id="frm2" action="" method="POST">
                              <input type="hidden" name="hid_complication_id" id="hid_complication_id">
                              <div class="form-group">
                                <label class="text-label">Complication Name<span class="required">*</span></label>
                                <input type="text" name="complication_name" id="complication_name" onKeyPress="return blockSpecialChar(event)" maxlength="50" class="form-control" placeholder="" required>
                                  <small id="complication_name_error" class="form-text text-muted error"></small>

                              </div>
                              <div class="form-group">
                                <label class="text-label">Complication Code</label>
                                <input type="text" name="complication_code" id="complication_code" onKeyPress="return blockSpecialChar(event)" maxlength="50" class="form-control" placeholder="" required>
                                <small id="complication_code_error" class="form-text text-muted error"></small>
                              </div>

                              <div class="form-check">
                                  <input type="checkbox" name="display_status" id="display_status_complication" class="form-check-input" checked >

                                <label class="form-check-label" for="displayStatus">Display Status</label>
                              </div>

                              <div id="crud_complication">
                                  <button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(3,1)" >Save</button>
                              </div>
                            </form>

                          </div>

                        </div>
                      </div>
                    </div>
                    <div class="col-xl-8">
                      <div class="card">
                        <div class="card-body">
                          <div class="table-responsive pt-3">
                            <table id="ComplicationTable" class="display">
                              <thead>
                                <tr>
                                  <th>Sl No.</th>
                                  <th>Complication Name</th>
                                  <th>Complication Code</th>
                                  <th>Display Status</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>

                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
              </div>

              <div id="sub-complication" class="tab-pane fade">
                <div class="row">
                  <div class="col-xl-4">
                    <div class="card">
                      <div class="card-body">

                        <div class=" mb-5">
                            <form name="frm3" id="frm3" method="POST" action="">
                              <input type="hidden" name="hid_subcomplication_id" id="hid_subcomplication_id" value="{{ old('mobile_number',isset($patient_data) ? $patient_data->mobile_number : '') }}">

                              <div class="form-group" id="ghtml">
                                  <label class="text-label"> Select Complication<span class="required">*</span></label>
                                  <select id="complication" name="complication" class="form-control">
{{--                                    <option value="">Select</option>--}}
                                  {{-- {{LoadCombo("complication_master","id","complication_name",'','where display_status=1 AND is_deleted=0',"order by id desc");}} --}}
                                  </select>
                                  <small id="complication_error" class="form-text text-muted error"></small>
                              </div>

                              <div class="form-group">
                              <label class="text-label">Sub Complication Name<span class="required">*</span></label>
                              <input type="text" name="sub_complication_name" id="sub_complication_name" onKeyPress="return blockSpecialChar(event)" maxlength="50" class="form-control" placeholder="" required>
                                <small id="sub_complication_name_error" class="form-text text-muted error"></small>
                              </div>

                              <div class="form-group">
                              <label class="text-label">Sub Complication Code</label>
                              <input type="text" name="sub_complication_code" id="sub_complication_code" onKeyPress="return blockSpecialChar(event)" maxlength="50" class="form-control" placeholder="" required>
                              <small id="sub_complication_code_error" class="form-text text-muted error"></small>
                              </div>

                              <div class="form-check">
                                  <input type="checkbox" name="display_status" id="display_status_subcomplication" class="form-check-input" checked >
                                <label class="form-check-label" for="displayStatus">Display Status</label>
                              </div>

                              <div id="crud_subcomplication">
                                  <button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(4,1)" >Save</button>
                              </div>
                          </form>

                        </div>

                      </div>
                    </div>
                  </div>
                  <div class="col-xl-8">
                    <div class="card">
                      <div class="card-body">
                        <div class="table-responsive pt-3">
                          <table id="SubComplication" class="display">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>Complication Name</th>
                                <th>Sub Complication Name</th>
                                <th>Sub Complication Code</th>
                                <th>Display Status</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>



              <div id="sub-diagnosis" class="tab-pane fade">
                <div class="row">
                  <div class="col-xl-4">
                    <div class="card">
                      <div class="card-body">

                        <div class=" mb-5">
                            <form name="frm6" id="frm6" method="POST" action="">
                              <input type="hidden" name="hid_subdiagnosis_id" id="hid_subdiagnosis_id" value="{{ old('mobile_number',isset($patient_data) ? $patient_data->mobile_number : '') }}">

                              <div class="form-group" id="ghtml">
                                  <label class="text-label"> Select Diagnosis<span class="required">*</span></label>
                                 
                                   <select id="diagnosis" name="diagnosis" class="form-control">
                                                                <option  value="" selected>Choose...</option>
                                                                {{-- {{LoadCombo("diagnosis_master","id","diagnosis_name",'','where display_status=1 AND is_deleted=0',"order by id desc");}} --}}
                                                            </select>
                                  <small id="diagnosis_error" class="form-text text-muted error"></small>
                              </div>

                              <div class="form-group">
                              <label class="text-label">Sub Diagnosis Name<span class="required">*</span></label>
                              <input type="text" name="sub_diagnosis_name" id="sub_diagnosis_name" onKeyPress="return blockSpecialChar(event)" maxlength="50" class="form-control" placeholder="" required>
                                <small id="sub_diagnosis_name_error" class="form-text text-muted error"></small>
                              </div>

                              <div class="form-group">
                              <label class="text-label">Sub Diagnosis Code</label>
                              <input type="text" name="sub_diagnosis_code" id="sub_diagnosis_code" onKeyPress="return blockSpecialChar(event)" maxlength="50" class="form-control" placeholder="" required>
                              <small id="sub_diagnosis_code_error" class="form-text text-muted error"></small>
                              </div>

                              <div class="form-check">
                                  <input type="checkbox" name="display_status" id="display_status_subdiagnosis" class="form-check-input" checked >
                                <label class="form-check-label" for="displayStatus">Display Status</label>
                              </div>

                              <div id="crud_subdiagnosis">
                                  <button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(7,1)" >Save</button>
                              </div>
                          </form>

                        </div>

                      </div>
                    </div>
                  </div>
                  <div class="col-xl-8">
                    <div class="card">
                      <div class="card-body">
                        <div class="table-responsive pt-3">
                          <table id="SubDiagnosis" class="display">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>Diagnosis Name</th>
                                <th>Sub Diagnosis Name</th>
                                <th>Sub Diagnosis Code</th>
                                <th>Display Status</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>






              <div id="questionaries" class="tab-pane fade">

                  <div class="row">
                    <div class="col-xl-4">
                      <div class="card">
                        <div class="card-body">

                          <div class=" mb-5">
                            <!-- <h5 class="text-primary d-inline">Today Highlights</h5><a href="javascript:void()" class="pull-right f-s-16">More</a> -->
                            <form action="#"  name="frm4" id="frm4" action="" method="POST">
                                <input type="hidden" name="hid_question_id" id="hid_question_id">
                              <div class="form-group">
                                <label class="text-label"> Type<span class="required">*</span></label>
                                <select id="question_type" name="question_type" class="form-control">
                                    <option value="1">Diet Plan</option>
                                    <option value="2">PEP</option>
                                    {{-- <option value="3"> miscellaneous </option> --}}
                                  </select>
                                <small id="question_type_error" class="form-text text-muted error"></small>
                              </div>
                              <div class="form-group">
                                <label class="text-label"> Questions(?)<span class="required">*</span></label>
                                <input type="text" name="question" id="question"  maxlength="80" class="form-control" placeholder="" required>
                                <small id="question_error" class="form-text text-muted error"></small>
                              </div>
                              <div class="form-group">
                                <label class="text-label"> Order number<span class="required">*</span></label>
                                <input type="number" name="order_no" id="order_no" onKeyPress="return blockSpecialChar(event)" maxlength="80" class="form-control" placeholder="" required>
                                <small id="order_no_error" class="form-text text-muted error"></small>
                              </div>
                              <div class="form-group">
                                <label class="text-label"> Label Name<span class="required">*</span> <i style="color:green;padding-left:20px" class="fa fa-plus" onclick="add_duplicate();"></i></label>
                                                <table id="maintable">
                                                        <tr>
                                                           <td> <input type="text" class="form-control " name="label[]" placeholder="Enter Label"/> </td>



                                                            <td> <i style="color:red;" class="fa fa-trash btnDelete" ></i></td>
                                                        </tr>
                                                        <br>
                                                    </table>


                              </div>


                              <div class="form-check">
                                  <input type="checkbox" name="display_status_question" id="display_status_question" class="form-check-input" checked >
                                <label class="form-check-label" for="displayStatus">Display Status</label>
                              </div>

                              <div id="crud_question">
                                  <button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(5,1)" >Save</button>
                              </div>
                            </form>

                          </div>

                        </div>
                      </div>
                    </div>
                    <div class="col-xl-8">
                      <div class="card">
                        <div class="card-body">
                          <div class="table-responsive pt-3">
                            <table id="questionTable" class="display">
                              <thead>
                                <tr>
                                  <th>Sl No.</th>
                                  <th>Type</th>
                                  <th>Questions</th>
                                  <th>Order Number</th>
                                  <th>Display Status</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>


                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
              </div>

              <div id="miscellaneous" class="tab-pane fade">

                <div class="row">
                  <div class="col-xl-4">
                    <div class="card">
                      <div class="card-body">

                        <div class=" mb-5">
                          <form action="#"  name="frm5" id="frm5" action="" method="POST">
                              <input type="hidden" name="hid_question_id5" id="hid_question_id5">

                              <div class="form-group">
                                <label>Query<span class="required">*</span></label>
                                <textarea  name="querys"  id='querys' class="form-control" required ></textarea>
                                <small id="querys_error" class="form-text text-muted error"></small>
                            </div>
                            <div class="form-group">
                                <label>Type<span class="required">*</span></label>
                                    <select class="form-control question_type_id" name="question_type_id" id="question_type_id" onchange="GetDynamicfieldBox(this.value);">

                                        <option value="">Select </option>
                                        <?php

                                        foreach ($form_types as $key => $value) {
                                            echo "<option value='$value->type!$value->id'>$value->label</option>";
                                        }

                                        ?>


                                    </select>
                                    <small id="question_type_id_error" class="form-text text-muted error"></small>
                            </div>
                            <div class="form-group">
                                <div id="dymaicfield" ></div>
                                <small id="optionfield_error" class="form-text text-muted error"></small>
                            </div>


                            {{-- <div class="form-check">
                                <input type="checkbox" name="display_status_question" id="display_status_question" class="form-check-input" checked >
                              <label class="form-check-label" for="displayStatus">Display Status</label>
                            </div> --}}

                            <div id="crud_question6">
                                <button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(6,1)" >Save</button>
                            </div>
                          </form>

                        </div>

                      </div>
                    </div>
                  </div>
                  <div class="col-xl-8">
                    <div class="card">
                      <div class="card-body">
                        <div class="table-responsive pt-3">
                          <table id="questionTable6" class="display">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>Type</th>
                                <th>Questions</th>
                                {{-- <th>Display Status</th> --}}
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>


                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


</div>
</div>

@include('frames/footer');

<link rel="stylesheet" href="{{asset('/vendor/select2/css/select2.min.css')}}">
<link href="{{asset('/vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
<script src="{{asset('date_picker/js/bootstrap-datepicker.min.js')}}" type='text/javascript'></script>
<script src="{{asset('./vendor/select2/js/select2.full.min.js')}}"></script>
<script src="./js/plugins-init/select2-init.js"></script>
<script>

function loadSubComplication()
{
    $("#SubComplication").dataTable().fnDestroy()
    table4 = $('#SubComplication').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        'ajax': {
            url: "<?php echo url('/') ?>/getSubComplication",
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
                "data": "complication_name"
            },
            {
                "data": "subcomplication_name"
            },
            {
                "data": "code"
            },
            {
                "data": "display_status",
                "render": function(display_status, type, full, meta) {
                    if (display_status == 1) return '<span class="badge badge-rounded badge-success">Active</span>';
                    else return '<span class="badge badge-rounded badge-danger">Inactive</span>';
                }
            },
            {
                "data": "id",
                "render": function(display_status, type, full, meta) {
                    return '<div class="d-flex"><a href="#" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a><a href="#" class="delete btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                }
            },
        ]
    });
}
//jdc
function loadSubDiagnosis()
{
    $("#SubDiagnosis").dataTable().fnDestroy()
    table7 = $('#SubDiagnosis').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        'ajax': {
            url: "<?php echo url('/') ?>/getSubDiagnosis",
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
                "data": "diagnosis_name"
            },
            {
                "data": "subdiagnosis_name"
            },
            {
                "data": "code"
            },
            {
                "data": "display_status",
                "render": function(display_status, type, full, meta) {
                    if (display_status == 1) return '<span class="badge badge-rounded badge-success">Active</span>';
                    else return '<span class="badge badge-rounded badge-danger">Inactive</span>';
                }
            },
            {
                "data": "id",
                "render": function(display_status, type, full, meta) {
                    return '<div class="d-flex"><a href="#" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a><a href="#" class="delete btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                }
            },
        ]
    });
}

  $(document).ready(function() {

    InitializeDT1();



        //end of dt


    $('#VisitType tbody').on('click', '.edit-visit-type', function() {

      // $("#visit_type_name_error").text('');

      $("[id*='_error']").text('');

         var data = table.row($(this).parents('tr')).data();
        $("#hid_visit_type_id").val(data.id);
        $("#visit_type_name").val(data.visit_type_name.trim());
        if(data.display_status==1)  $('#display_status').prop("checked", true); else  $('#display_status').prop("checked", false);
        crude_btn_manage(2,1);
    });


    $('#Diagnosis tbody').on('click', '.edit-diagnosis', function() {
        var data = table2.row($(this).parents('tr')).data();
        $("#hid_diagnosis_id").val(data.id);
        $("#diagnosis_name").val(data.diagnosis_name);
        $('#diagnosis_name').trigger('change');
        $("#diagnosis_code").val(data.code);
        if(data.display_status==1)  $('#display_status_diagnosis').prop("checked", true); else  $('#display_status_diagnosis').prop("checked", false);
        crude_btn_manage(2,2);
    });
    $('#questionTable tbody').on('click', '.edit-questionaire', function() {
        var data = table5.row($(this).parents('tr')).data();
        $("#hid_question_id").val(data.id);
        $("#question").val(data.question.trim());
        $('#question').trigger('change');
        $("#order_no").val(data.order_no);
        $('#order_no').trigger('change');
        $("#question_type").val(data.type);
        if(data.display_status==1)  $('#display_status_question').prop("checked", true); else  $('#display_status_question').prop("checked", false);
        let ajaxval = {
           id: data.id,
        };
        $.ajax({
                type: "POST",
                url: "<?php echo url('/') ?>/getSubQuestions",
                data: ajaxval,
                success: function(data) {

                  table_data_row(data)
                },
            });
        crude_btn_manage(2,5);
    });
    $('#questionTable6 tbody').on('click', '.edit-miscellaneous', function() {
        var data = table6.row($(this).parents('tr')).data();

        $("#question_type_id").val(data.type+'!'+data.type_id).selectpicker('refresh').trigger('change');

        $("#hid_question_id5").val(data.id);
        $("#querys").val(data.question.trim());
        $('#querys').trigger('change');

        if(data.display_status==1)  $('#display_status_question').prop("checked", true); else  $('#display_status_question').prop("checked", false);
        let ajaxval = {
           id: data.id,
           type_id:data.type_id,
        };
        $.ajax({
                type: "POST",
                url: "<?php echo url('/') ?>/getSubQuestionsGroup",
                data: ajaxval,
                dataType: "json",
                success: function(data) {

                  if(data){
                    table_data_row_miscellaneous(data);
                  }
                },
            });
        crude_btn_manage(2,6);
    });




    $('#ComplicationTable tbody').on('click', '.edit-complication', function() {
      $("[id*='_error']").text('');
         var data = table3.row($(this).parents('tr')).data();
        $("#hid_complication_id").val(data.id);
        $("#complication_name").val(data.complication_name);
        $("#complication_code").val(data.code);
        if(data.display_status==1)  $('#display_status_complication').prop("checked", true); else  $('#display_status_complication').prop("checked", false);
        crude_btn_manage(2,3);
    });

    $('#SubComplication tbody').on('click', '.edit', function() {
      $("[id*='_error']").text('');
         var data = table4.row($(this).parents('tr')).data();
        $("#hid_subcomplication_id").val(data.id);
        $('#complication').val(data.complication_id).change();
        $("#sub_complication_name").val(data.subcomplication_name);
        $("#sub_complication_code").val(data.code);
        if(data.display_status==1)  $('#display_status_subcomplication').prop("checked", true); else  $('#display_status_subcomplication').prop("checked", false);
        crude_btn_manage(2,4);
    });
    $('#DiagnosisTable tbody').on('click', '.edit-diagnosis', function() {
      $("[id*='_error']").text('');
         var data = table2.row($(this).parents('tr')).data();
        $("#hid_diagnosis_id").val(data.id);
        $("#diagnosis_name").val(data.diagnosis_name);
        $("#diagnosis_code").val(data.code);
        if(data.display_status==1)  $('#display_status_diagnosis').prop("checked", true); else  $('#display_status_diagnosis').prop("checked", false);
        crude_btn_manage(2,3);
    });
    $('#SubDiagnosis tbody').on('click', '.edit', function() {
      $("[id*='_error']").text('');
         var data = table7.row($(this).parents('tr')).data();
        $("#hid_subdiagnosis_id").val(data.id);
        $('#diagnosis').val(data.diagnosis_id).change();
        $("#sub_diagnosis_name").val(data.subdiagnosis_name);
        $("#sub_diagnosis_code").val(data.code);
        if(data.display_status==1)  $('#display_status_subdiagnosis').prop("checked", true); else  $('#display_status_subdiagnosis').prop("checked", false);
        crude_btn_manage(2,4);
    });


    $('#VisitType tbody').on('click', '.delete', function() {
      var data = table.row($(this).parents('tr')).data();

      let ajaxval = {
          id: data.id,
      };
      swal({
          title: 'Are you sure?',
          text: "You won't be able to recover this data!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, Delete it!'
      }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "<?php echo url('/') ?>/deleteVisitType",
                data: ajaxval,
                success: function(result) {

                    if (result.status == 1) {
                        swal("Done", result.message, "success");
                        document.getElementById("frm").reset();
                        crude_btn_manage(1,1)
                        table.ajax.reload();
                    } else {
                        sweetAlert("Oops...", result.message, "error");
                    }
                },
            });
        }
        })
    });

    $('#questionTable6 tbody').on('click', '.delete', function() {
        var data = table6.row($(this).parents('tr')).data();

        let ajaxval = {
            id: data.id,
        };
        swal({
            title: 'Are you sure?',
            text: "You won't be able to recover this data!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Delete it!'
        }).then((result) => {
          if (result.value) {
              $.ajax({
                  type: "POST",
                  url: "<?php echo url('/') ?>/deleteMiscellaneousQs",
                  data: ajaxval,
                  success: function(result) {

                      if (result.status == 1) {
                          swal("Done", result.message, "success");
                          $('#question_type_id').val("").selectpicker('refresh');
                          document.getElementById("frm5").reset();
                          crude_btn_manage(1,6)
                          table6.ajax.reload();
                      } else {
                          sweetAlert("Oops...", result.message, "error");
                      }
                  },
              });
          }
        })
    });


    $('#DiagnosisTable tbody').on('click', '.delete', function() {
      var data = table2.row($(this).parents('tr')).data();
      let ajaxval = {
          id: data.id,
      };
      swal({
          title: 'Are you sure?',
          text: "You won't be able to recover this data!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, Delete it!'
      }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "<?php echo url('/') ?>/deleteDiagnosis",
                data: ajaxval,
                success: function(result) {

                    if (result.status == 1) {
                        swal("Done", result.message, "success");
                        document.getElementById("frm1").reset();
                        crude_btn_manage(1,2)
                        table2.ajax.reload();
                    } else {
                        sweetAlert("Oops...", result.message, "error");
                    }
                },
            });
        }
        })
    });

    $('#ComplicationTable tbody').on('click', '.delete', function() {
      var data = table3.row($(this).parents('tr')).data();

      let ajaxval = {
          id: data.id,
      };
      swal({
          title: 'Are you sure?',
          text: "You won't be able to recover this data!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, Delete it!'
      }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "<?php echo url('/') ?>/deleteComplication",
                data: ajaxval,
                success: function(result) {

                    if (result.status == 1) {
                        swal("Done", result.message, "success");
                        document.getElementById("frm2").reset();
                        crude_btn_manage(1,3)
                        table3.ajax.reload();
                    } else {
                        sweetAlert("Oops...", result.message, "error");
                    }
                },
            });
        }
        })
    });

    $('#questionTable tbody').on('click', '.delete', function() {
      var data = table5.row($(this).parents('tr')).data();

      let ajaxval = {
          id: data.id,
      };
      swal({
          title: 'Are you sure?',
          text: "You won't be able to recover this data!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, Delete it!'
      }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "<?php echo url('/') ?>/deleteQuestions",
                data: ajaxval,
                success: function(result) {

                    if (result.status == 1) {
                        swal("Done", result.message, "success");
                        document.getElementById("frm4").reset();
                        crude_btn_manage(1,5)
                        table5.ajax.reload();
                    } else {
                        sweetAlert("Oops...", result.message, "error");
                    }
                },
            });
        }
        })
    });

    $('#SubComplication tbody').on('click', '.delete', function() {
      var data = table4.row($(this).parents('tr')).data();

      let ajaxval = {
          id: data.id,
      };
      swal({
          title: 'Are you sure?',
          text: "You won't be able to recover this data!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, Delete it!'
      }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "<?php echo url('/') ?>/deleteSubComplication",
                data: ajaxval,
                success: function(result) {

                    if (result.status == 1) {
                        swal("Done", result.message, "success");
                        $('#complication').val("").selectpicker('refresh');
                        document.getElementById("frm3").reset();
                        crude_btn_manage(1,4)
                        table4.ajax.reload();
                    } else {
                        sweetAlert("Oops...", result.message, "error");
                    }
                },
            });
        }
        })
    });

    ////////////////////////
    $('#SubDiagnosis tbody').on('click', '.delete', function() {
      var data = table7.row($(this).parents('tr')).data();

      let ajaxval = {
          id: data.id,
      };
      swal({
          title: 'Are you sure?',
          text: "You won't be able to recover this data!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, Delete it!'
      }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: "<?php echo url('/') ?>/deleteSubDiagnosis",
                data: ajaxval,
                success: function(result) {

                    if (result.status == 1) {
                        swal("Done", result.message, "success");
                        $('#diagnosis').val("").selectpicker('refresh');
                        document.getElementById("frm6").reset();
                        crude_btn_manage(1,7)
                        table7.ajax.reload();
                    } else {
                        sweetAlert("Oops...", result.message, "error");
                    }
                },
            });
        }
        })
    });
  });


  function submit_form(page,crude)
  {
      $('#optionfield_error').text("");
        var url="";
        if(page==1)
        {
            url='{{route('saveVisitType')}}';
            var form = $('#frm')[0];
        }
        else  if(page==2)
        {
            url='{{route('saveDiagnosis')}}';
            var form = $('#frm1')[0];
        }
        else  if(page==3)
        {
            url='{{route('saveComplication')}}';
            var form = $('#frm2')[0];
        }
        else  if(page==4)
        {
            url='{{route('saveSubComplication')}}';
            var form = $('#frm3')[0];
        }
        else  if(page==5)
        {

            url='{{route('saveQuestions')}}';
            var form = $('#frm4')[0];
        }
        else  if(page==6)
        {

            url='{{route('saveform_engine')}}';
            var form = $('#frm5')[0];
        }
        else  if(page==7)
        {

            url='{{route('saveSubDiagnosis')}}';
            var form = $('#frm6')[0];
        }
        var formData = new FormData(form);
        formData.append('crude', crude);
        $.ajax({
				 type: "POST",
				 url: url,
				 data: formData,
				 processData: false,
				 contentType: false,
				 success: function(result){

                            if(result.status==1)
                            {
                                swal("Done", result.message, "success");
                                document.getElementById("frm").reset();
                                document.getElementById("frm1").reset();
                                document.getElementById("frm2").reset();
                                document.getElementById("frm3").reset();
                                document.getElementById("frm6").reset();

                                if(page==6)
                                {
                                  document.getElementById("frm5").reset();
                                  $('#question_type_id').val("").selectpicker('refresh');
                                  $('#dymaicfield').empty();
                                  table6.ajax.reload();

                                }else if(page==5){
                                  table5.ajax.reload();
                                }else if(page==4){
                                  $('#complication').val("").selectpicker('refresh');
                                  table4.ajax.reload();
                                }else if(page==3){
                                  table3.ajax.reload();
                                }else if(page==2){
                                  table2.ajax.reload();
                                }else if(page==1){
                                  table.ajax.reload();
                                }
                                else if(page==7){
                                  $('#diagnosis').val("").selectpicker('refresh');
                                  table7.ajax.reload();
                                }

                                // $('select').val('').selectpicker('refersh');
                                $("#question").val("");
                                $("#order_no").val("");



                                crude_btn_manage(1,page);
                                var	rows = '';
                                    rows = rows + '<tr>';
                                    rows = rows + ' <td> <input type="text" class="form-control " name="label[]" value="" placeholder="Enter Label"/> </td>';
                                    rows = rows + '<td> <i style="color:red;" class="fa fa-trash btnDelete" ></i></td>';
                                    rows+="</tr>";
                                  $("#maintable").html(rows);

                            }
                            else if(result.status==2){
                                sweetAlert("Oops...",result.message, "error");
                            }else if(result.status==5){
                              $("#optionfield_error").text("Atleast two options required");
                            }
                            else{
                                sweetAlert("Oops...",result.message, "error");
                            }
                     },
                     error: function(result,jqXHR, textStatus, errorThrown){
                         if( result.status === 422 ) {

                            result=result.responseJSON;
                            var error=result.errors;
                             $.each(error, function (key, val) {
                             console.log(key);
                             if(key=='querys'){
                              $("#" + key + "_error").text('The Query field is required');
                             }else{
                              $("#" + key + "_error").text(val[0]);
                             }

                    });
                }

                }
			 });
  }

  function add_duplicate()
  {

    $("#maintable").each(function () {

        var tds = '<tr>';
          tds = tds + ' <td> <input type="text" class="form-control " name="label[]" value="" placeholder="Enter Label"/> </td>';
          tds = tds + '<td> <i style="color:red;" class="fa fa-trash btnDelete" ></i></td>';
        tds += '</tr>';
        if ($('tbody', this).length > 0) {
            $('tbody', this).append(tds);
        } else {
            $(this).append(tds);
        }
    });
  }


  $("#maintable").on('click', '.btnDelete', function () {

            if( $(this).closest('tr').is('tr:only-child') ) {
              sweetAlert("Oops...","Alteast one label is required", "error");
    }
    else {
        $(this).closest('tr').remove();
    }
  });



  function crude_btn_manage(type=1,page)
  {
      if(page==1)
      {
          if(type==1) $('#crude').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\''+page+'\',\''+type+'\')" >Save</button>');
      else if(type==2)  $('#crude').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\''+page+'\',\''+type+'\')" >Update</button>');
      }
      else if(page==2)
      {
          if(type==1) $('#crud_diagnosis').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\''+page+'\',\''+type+'\')" >Save</button>');
      else if(type==2)  $('#crud_diagnosis').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\''+page+'\',\''+type+'\')" >Update</button>');

      }
      else if(page==3)
      {
          if(type==1) $('#crud_complication').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\''+page+'\',\''+type+'\')" >Save</button>');
      else if(type==2)  $('#crud_complication').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\''+page+'\',\''+type+'\')" >Update</button>');

      }
      else if(page==4)
      {
          if(type==1) $('#crud_subcomplication').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\''+page+'\',\''+type+'\')" >Save</button>');
      else if(type==2)  $('#crud_subcomplication').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\''+page+'\',\''+type+'\')" >Update</button>');

      }
      else if(page==5)
      {

          if(type==1) $('#crud_question').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\''+page+'\',\''+type+'\')" >Save</button>');
      else if(type==2)  $('#crud_question').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\''+page+'\',\''+type+'\')" >Update</button>');

      }
      else if(page==6)
      {

          if(type==1) $('#crud_question6').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\''+page+'\',\''+type+'\')" >Save</button>');
      else if(type==2)  $('#crud_question6').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\''+page+'\',\''+type+'\')" >Update</button>');

      }
      else if(page==7)
      {

        if(type==1) $('#crud_subdiagnosis').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\''+page+'\',\''+type+'\')" >Save</button>');
      else if(type==2)  $('#crud_subdiagnosis').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\''+page+'\',\''+type+'\')" >Update</button>');

      }
  }
  function table_data_row(data) {

          var	rows = '';


              $.each( data, function( kequestion_typeseley, value ) {
                  rows = rows + '<tr>';
                  rows = rows + ' <td> <input type="text" class="form-control " name="label[]" value="'+value.label+'" placeholder="Enter Label"/> </td>';
                  rows = rows + '<td> <i style="color:red;" class="fa fa-trash btnDelete" ></i></td>';

                  rows+="</tr>";
              });

          $("#maintable").html(rows);
  }

  function table_data_row_miscellaneous(data) 
  {

    var	rows = '';

    $i=0;
        $.each( data, function( kequestion_typeseley, value ) {
          $i++;

          rows +="<div class='row'><div class='col-md-8'><input type='text' name='attributestext[]' placeholder='Option 1' value='"+ value.attr_value+"' class='no-border form-control dynamicinputs' id='1'></div><div class='col-md-4'><i style='color:red;'' class='fa fa-trash btnDeleteMis' ></i></div></div>";

        });
        rows +="<input type='text'  name='attributestext[]' placeholder='Add Option' class='no-border form-control' onclick=addOptions(this,'') id='"+$i+"'>";


    $("#dymaicfield").html(rows);


  }
  $("#dymaicfield").on('click', '.btnDeleteMis', function () {


           $(this).closest('div').parent().remove();

  });

  $("#question_type").change(function(){

    table5.ajax.reload();
  });
</script>
<script>
  function GetDynamicfieldBox(inputtext){

      var inputArray=inputtext.split('!');
      var inputtype=inputArray[0];
      var inputtypeId=inputArray[1];
      if(!inputtype){return true;}

      $.ajax({
      type: "POST",
      url:   url='{{route('generateformfields')}}',
      data:{
          inputtype: inputtype,
          inputclass : "form-control"

      } ,
      success: function(result){
          // console.log(result);
          // alert(inputtype);
          $('#dymaicfield').empty();
          if(inputtypeId=='1' || inputtypeId =='2' || inputtypeId=='3')
          $('#dymaicfield').html(result);
      }
    });
  }

  function addOptions($thiss,$value){

      var ids = $($thiss).attr('id');
      var newids=parseInt(ids)+1;
      $('#'+ids).attr("placeholder","Option "+ids);
      $('#'+ids).attr("onclick", "").unbind("click");


      $AppendField="<input type='text'  name='attributestext[]' placeholder='Add Option' class='no-border form-control' onclick=addOptions(this,'"+$value+"') id='"+newids+"'>";


     $("#dymaicfield").append($AppendField);

  }


  $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
    currTabTarget = $(e.target).attr('href');

    if(currTabTarget=="#visit-type"){
      InitializeDT1();
    }else  if(currTabTarget=="#Diagnosis"){
      InitializeDT2();
    }else if(currTabTarget=="#complicationtab"){
      InitializeDt3();
    }
    else if(currTabTarget=="#sub-complication"){
      InitializeDt4();
    }
    else if(currTabTarget=="#questionaries"){
      InitializeDt5();
    }
    else if(currTabTarget=="#miscellaneous"){
      InitializeDt6();
    }
    //jdc20.05.24
    else if(currTabTarget=="#sub-diagnosis"){
      InitializeDt7();
    }


  });


  function InitializeDT1(){
        table = $('#VisitType').DataTable({
        "destroy": true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        'ajax': {
            url: "<?php echo url('/') ?>/getVisitType",
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
                "data": "visit_type_name"
            },
            {
                "data": "display_status",
                "render": function(display_status, type, full, meta) {
                    if (display_status == 1) return '<span class="badge badge-rounded badge-success">Active</span>';
                    else return '<span class="badge badge-rounded badge-danger">Inactive</span>';
                }
            },
            {
                "data": "id",
                "render": function(display_status, type, full, meta) {
                    return '<div class="d-flex"><a href="#" class="edit-visit-type btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a><a href="#" class="delete btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                }
            },
        ]
      });

  }
  function InitializeDT2(){
    table2 = $('#DiagnosisTable').DataTable({
          "destroy": true,
          dom: 'Bfrtip',
          buttons: [
              'copy', 'csv', 'excel', 'pdf', 'print'
          ],
          'ajax': {
              url: "<?php echo url('/') ?>/getDiagnosis",
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
                  "data": "diagnosis_name"
              },
              {
                  "data": "code"
              },
              {
                  "data": "display_status",
                  "render": function(display_status, type, full, meta) {
                      if (display_status == 1) return '<span class="badge badge-rounded badge-success">Active</span>';
                      else return '<span class="badge badge-rounded badge-danger">Inactive</span>';
                  }
              },
              {
                  "data": "id",
                  "render": function(display_status, type, full, meta) {
                      return '<div class="d-flex"><a href="#" class="edit-diagnosis btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a><a href="#" class="delete btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                  }
              },
          ]
      });
  }
  function InitializeDt3(){
        table3 = $('#ComplicationTable').DataTable({
        "destroy": true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        'ajax': {
            url: "<?php echo url('/') ?>/getComplication",
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
                "data": "complication_name"
            },
            {
                "data": "code"
            },
            {
                "data": "display_status",
                "render": function(display_status, type, full, meta) {
                    if (display_status == 1) return '<span class="badge badge-rounded badge-success">Active</span>';
                    else return '<span class="badge badge-rounded badge-danger">Inactive</span>';
                }
            },
            {
                "data": "id",
                "render": function(display_status, type, full, meta) {
                    return '<div class="d-flex"><a href="#" class="edit-complication btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a><a href="#" class="delete btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                }
            },
        ]
    });
  }

  function InitializeDt4()
  {
        table4 = $('#SubComplication').DataTable({
        "destroy": true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        'ajax': {
            url: "<?php echo url('/') ?>/getSubComplication",
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
                "data": "complication_name"
            },
            {
                "data": "subcomplication_name"
            },
            {
                "data": "code"
            },
            {
                "data": "display_status",
                "render": function(display_status, type, full, meta) {
                    if (display_status == 1) return '<span class="badge badge-rounded badge-success">Active</span>';
                    else return '<span class="badge badge-rounded badge-danger">Inactive</span>';
                }
            },
            {
                "data": "id",
                "render": function(display_status, type, full, meta) {
                    return '<div class="d-flex"><a href="#" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a><a href="#" class="delete btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                }
            },
        ]
      });


      loadcomplicationDropdown();
  }

//jdc
function InitializeDt7()
  {
        table7 = $('#SubDiagnosis').DataTable({
        "destroy": true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        'ajax': {
            url: "<?php echo url('/') ?>/getSubDiagnosis",
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
                "data": "diagnosis_name"
            },
            {
                "data": "subdiagnosis_name"
            },
            {
                "data": "code"
            },
            {
                "data": "display_status",
                "render": function(display_status, type, full, meta) {
                    if (display_status == 1) return '<span class="badge badge-rounded badge-success">Active</span>';
                    else return '<span class="badge badge-rounded badge-danger">Inactive</span>';
                }
            },
            {
                "data": "id",
                "render": function(display_status, type, full, meta) {
                    return '<div class="d-flex"><a href="#" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a><a href="#" class="delete btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                }
            },
        ]
      });


      loaddiagnosisDropdown();
  }
  

  function InitializeDt5(){
    table5 = $('#questionTable').DataTable({
        "destroy": true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        'ajax': {
            url: "<?php echo url('/') ?>/getQuestions",
            type: 'POST',
            "data": function(d) {

            d.type= $('#question_type').val();

            }
        },
        "columns": [{
                "data": "id",
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                "data": "type",
                "render": function(type,  full, meta) {
                    if (type == 1) {
                      return 'Diet Plan'
                    }
                    else if (type == 2) {
                      return 'PEP'
                    }
                    else{
                      return 'Miscellaneous '
                    }
                }
            },
            {
                "data": "question"
            },
            {
                "data": "order_no"
            },

            {
                "data": "display_status",
                "render": function(display_status, type, full, meta) {
                    if (display_status == 1) return '<span class="badge badge-rounded badge-success">Active</span>';
                    else return '<span class="badge badge-rounded badge-danger">Inactive</span>';
                }
            },
            {
                "data": "id",
                "render": function(display_status, type, full, meta) {
                    return '<div class="d-flex"><a href="#" class="edit-questionaire btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a><a href="#" class="delete btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                }
            },
        ]
    });
  }

  function InitializeDt6(){
    table6 = $('#questionTable6').DataTable({
        "destroy": true,
        'ajax': {
            url: "<?php echo url('/') ?>/getQuestionsGroup",
            type: 'POST',
            "data": function(d) {

            // d.type= $('#question_type').val();

            }
        },
        "columns": [{
                "data": "id",
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                "data": "typelabel",

            },
            {
                "data": "question"
            },


            // {
            //     "data": "display_status",
            //     "render": function(display_status, type, full, meta) {
            //         if (display_status == 1) return '<span class="badge badge-rounded badge-success">Active</span>';
            //         else return '<span class="badge badge-rounded badge-danger">Inactive</span>';
            //     }
            // },
            {
                "data": "id",
                "render": function(display_status, type, full, meta) {
                    return '<div class="d-flex"><a href="#" class="edit-miscellaneous btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a><a href="#" class="delete btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                }
            },
        ]
    });
  }


  function loadcomplicationDropdown()
  {
    // $('#complication').empty();


    $.ajax({
      type: "POST",
      url:   url='{{route('loadcomplicationDropdown')}}',
      data:{
      } ,
      success: function(result){
          $('#complication').html('');
          $('#complication').append(result).selectpicker('refresh').trigger('change');
      }
    });



  }
  function loaddiagnosisDropdown()
  {
    $.ajax({
      type: "POST",
      url:   url='{{route('loaddiagnosisDropdown')}}',
      data:{
      } ,
      success: function(result){
          $('#diagnosis').html('');
          $('#diagnosis').append(result).selectpicker('refresh').trigger('change');
      }
    });

  }

</script>
