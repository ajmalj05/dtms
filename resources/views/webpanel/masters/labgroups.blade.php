<style>
    table.dataTable
    {
        width: 100% !important;
    }
    /* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
</style>
<div class="content-body">
  <div class="container-fluid pt-2">
    <div class="row" style="">
      <div class="col-md-12">
        <input type="hidden" id="hid_colors" value="{{$colours}}">
        <input type="hidden" id="hid_clarity" value="{{$clarity}}">

        <div class="profile-tab pb-2">
          <div class="custom-tab-1">
            <ul class="nav nav-tabs">
            <li class="nav-item"><a href="#groups" data-toggle="tab" class="nav-link active show">Department</a>  </li>
            <li class="nav-item"><a href="#sub_test" data-toggle="tab" class="nav-link">Group</a>  </li>
              <li class="nav-item"><a href="#subgroups" data-toggle="tab" class="nav-link">Tests/Procedures</a>  </li>

            </ul>
            <div class="tab-content pt-3">
              <div id="groups" class="tab-pane fade active show">

                <div class="row">
                  <div class="col-xl-4">
                    <div class="card">
                      <div class="card-body">

                        <div class=" mb-5">
                          <!-- <h5 class="text-primary d-inline">Today Highlights</h5><a href="javascript:void()" class="pull-right f-s-16">More</a> -->
                          <form action="#"  name="frm" id="frm" action="" method="POST">
                            <input type="hidden" name="hid_met_id" id="hid_met_id">
                            <div class="form-group">
                              <label class="text-label">Department Name <span class="required">*</span></label>
                              <input type="text" name="group_name" id="group_name" onKeyPress="return blockSpecialChar(event)" maxlength="50" class="form-control" placeholder="" required>
                                <small id="group_name_error" class="form-text text-muted error"></small>

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
                          <table id="group_table" class="display">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>Department Name</th>
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
              <div id="subgroups" class="tab-pane fade">
                <div class="row">
                  <div class="col-xl-12">
                    <div class="card">
                      <div class="card-body">

                        <div class=" mb-5">
                            <form action="#"  name="frm1" id="frm1" action="" method="POST">
                                <input type="hidden" name="hid_test_id" id="hid_test_id">

                                <input type="hidden" name="no_rows" id="no_rows" value="0">


                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="text-label">Group Name <span class="required">*</span></label>
                                            <select class="form-control" name="group_id" id="group_id">
                                                <option value="">Select</option>
                                                {{-- {{LoadCombo("service_group_master","id","group_name",'','where display_status=1 AND is_deleted=0 AND is_lab_group=1',"order by id desc");}} --}}
                                                {{-- {{LoadCombo("test_sub_group","id","sub_group_name",'','where display_status=1 AND is_deleted=0',"order by id desc");}} --}}
                                                {{-- {{LoadCombo("test_master","id","'TestName as tname'",'','where display_status=1 AND is_deleted=0 AND is_service_item=2',"order by id desc");}} --}}
                                                <?php
                                                    foreach ($test_groups as $key) {
                                                         ?>
                                                            <option value="{{$key->id}}">{{$key->TestName}}</option>
                                                         <?php
                                                    }
                                                ?>
                                            </select>
                                        <small id="group_id_error" class="form-text text-muted error"></small>
                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="text-label">Test/Procedure Name <span class="required">*</span></label>
                                        <input type="text" name="test_name" id="test_name" onKeyPress="return blockSpecialChar(event)" maxlength="50" class="form-control" placeholder="" required>
                                        <small id="test_name_error" class="form-text text-muted error"></small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="text-label">Test Amount <span class="required">*</span></label>
                                        <input type="text" name="test_amount" id="test_amount" onKeyPress="return blockSpecialChar(event)" maxlength="50" class="form-control" placeholder="" required>
                                        <small id="test_amount_error" class="form-text text-muted error"></small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="text-label">Item /Code Id <span class="required">*</span></label>
                                        <input type="text" name="item_code" id="item_code" onKeyPress="return blockSpecialChar(event)" maxlength="50" class="form-control" placeholder="" required>
                                        <small id="item_code_error" class="form-text text-muted error"></small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="text-label">Result Type <span class="required">*</span></label>
                                            <select class="form-control" name="result_typeid" id="result_typeid" onchange="Adddata(0)">
                                                <option value="">Select</option>
                                                <option value="1">Colour</option>
                                                <option value="2">Range</option>
                                                <option value="3">+ve / -ve</option>
                                                <option value="4">Clarity</option>

                                            </select>
                                        <small id="result_typeid_error" class="form-text text-muted error"></small>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Unit</label>
                                        <input type="text" name="unit" id="unit" maxlength="50" class="form-control" placeholder="">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Report Data</label>
                                        <textarea name="report_data" id="report_data"  class="form-control" placeholder=""></textarea>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Method</label>
                                        <input type="text" name="test_method" id="test_method"  class="form-control" placeholder="">
                                    </div>
                                </div>



                                <div class="clearfix"></div>



                            </div>
                            <div class="row">
                                <div id="append_data"></div>
                                <div class="col-md-1 addbtn hidedata">
                                    <div class="form-group">
                                      <label class="text-label" style="opacity:0">To Range</label>
                                      <i class="fa fa-plus" onclick="Adddata(1);"></i>


                                    </div>
                                </div>
                            </div>

                            <div class="row" style="display:none">

                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <p class="panelback">Other Lab Rate</p>
                                            <hr>
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-label">Lab Name <span class="required"></span></label>
                                                        <input type="text" name="lab_name[]" id="lab_name" onKeyPress="return blockSpecialChar(event)" maxlength="250" class="form-control" placeholder="" >
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label class="text-label">Lab Amount <span class="required"></span></label>
                                                        <input type="text" name="lab_amount[]" id="lab_amount" onKeyPress="return blockSpecialChar(event)" maxlength="250" class="form-control" placeholder="" >

                                                    </div>
                                                </div>
                                            </div>

                                            <div id="duplicate_labrate"></div>
                                            <div class="row">
                                                <div class="col-md-11"></div>
                                                <div class="col-md-1" style="float:right;bottom:48px;left:10px">
                                                    <i class="fa fa-plus cursorp" onClick="duplicateLabRates()"></i>
                                                </div>
                                            </div>


                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="card">
                                        <div class="card-body">
                                            <p class="panelback">Panel Rate List</p>
                                            <hr>
                                            <div class="row">

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="text-label">Ward Name <span class="required"></span></label>
                                                            <select class="form-control" name="ward_id[]" id="ward_id">
                                                                <option value="">Select</option>
                                                                {{LoadCombo("wards_master","id","ward_name",'','where display_status=1 AND is_deleted=0',"order by id desc");}}

                                                            </select>
                                                        <small id="ward_id_error" class="form-text text-muted error"></small>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="text-label">Department <span class="required"></span></label>
                                                            <select class="form-control" name="department_id[]" id="department_id">
                                                                <option value="">Select</option>
                                                                {{LoadCombo("departments","id","department_name",'','where display_status=1 AND is_deleted=0',"order by id desc");}}

                                                            </select>
                                                        <small id="department_id_error" class="form-text text-muted error"></small>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="text-label">Panel <span class="required">*</span></label>
                                                            <select class="form-control" name="ward_id[]" id="ward_id">
                                                                <option value="">Select</option>
                                                                {{LoadCombo("wards_master","id","ward_name",'','where display_status=1 AND is_deleted=0',"order by id desc");}}

                                                            </select>
                                                        <small id="group_id_error" class="form-text text-muted error"></small>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="text-label"> Amount <span class="required"></span></label>
                                                        <input type="text" name="panel_amount[]" id="panel_amount" onKeyPress="return blockSpecialChar(event)" maxlength="250" class="form-control" placeholder="" >

                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="text-label"> Item Code <span class="required"></span></label>
                                                        <input type="text" name="panel_code[]" id="panel_code" onKeyPress="return blockSpecialChar(event)" maxlength="250" class="form-control" placeholder="" >

                                                    </div>
                                                </div>
                                            </div>

                                            <div id="duplicate_labpanel"></div>
                                            <div class="row">
                                                <div class="col-md-11"></div>
                                                <div class="col-md-1" style="float:right;bottom:48px;left:8px">
                                                    <i class="fa fa-plus cursorp" onClick="duplicateLabPanel()"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="display:none">

                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <p class="panelback">Lab Inventory</p>
                                            <hr>
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="text-label">Item Name <span class="required"></span></label>
                                                        <input type="text" name="lab_inv_name[]" id="lab_inv_name" onKeyPress="return blockSpecialChar(event)" maxlength="250" class="form-control" placeholder="" >
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label class="text-label">Issue Quantity <span class="required"></span></label>
                                                        <input type="text" name="lab_inv_qty[]" id="lab_inv_qty" onKeyPress="return blockSpecialChar(event)" maxlength="250" class="form-control" placeholder="" >

                                                    </div>
                                                </div>
                                            </div>

                                            <div id="duplicate_labinv"></div>
                                            <div class="row">
                                                <div class="col-md-11"></div>
                                                <div class="col-md-1" style="float:right;bottom:48px;left:10px">
                                                    <i class="fa fa-plus cursorp" onClick="duplicateLabInv()"></i>
                                                </div>
                                            </div>


                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                <div class="form-group">
                                    <label class="text-label">Order Number </label>
                                    <input type="number" name="order_num" id="order_num" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==3) return false;"  class="form-control" placeholder="" >
                                    <small id="order_num_error" class="form-text text-muted error"></small>
                                </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="text-label">Chart Order  </label>
                                        <input type="number" name="chart_order_num" id="chart_order_num" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==3) return false;"  class="form-control" placeholder="" >
                                        <small id="chart_order_num_error" class="form-text text-muted error"></small>
                                    </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="text-label">DTMS Code </label>
                                            <input type="text" name="dtms_code" id="dtms_code"  class="form-control">

                                        </div>

                                    </div>

                                <div class="col-md-4" style="padding-top: 28px;">
                                <div class="form-check">
                                    <input type="checkbox" name="display_status_edu" id="display_status_edu" class="form-check-input" checked >
                                  <label class="form-check-label" for="displayStatus">Display Status</label>
                                </div>
                                </div>
                                <div class="col-md-4" style="padding-top: 25px;">
                                <div id="crude_edu">
                                    <button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(2,1)" >Save</button>
                                </div>
                                </div>
                        </div>



                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-12">
                    <div class="card">
                      <div class="card-body">
                        <div class="table-responsive pt-3">
                          <table id="education-table" class="display">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>Test Name</th>
                                <th>Test Code</th>
                                <th>Amount</th>
                                <th>Actions</th>
                                {{-- <th>Delete</th> --}}
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

              <div id="sub_test" class="tab-pane fade">
                <div class="row">
                    <div class="col-xl-4">
                      <div class="card">
                        <div class="card-body">
                            <div class=" mb-5">
                                <form action="#"  name="frm2" id="frm2" action="" method="POST">
                                    <input type="hidden" name="hid_sub_test_id" id="hid_sub_test_id">

                                    <div class="form-group">
                                        <label class="text-label">Department<span class="required">*</span></label>
                                        <select class="form-control" name="departemrnt_id" id="departemrnt_id">
                                            <option value="">Select</option>
                                          {{-- {{LoadCombo("service_group_master","id","group_name",'','where display_status=1 AND is_deleted=0 and is_lab_group=1',"order by id desc");}} --}}

                                          {{LoadCombo("test_group_master","id","groupname",'','where display_status=1 AND is_deleted=0',"order by id desc");}}
                                        </select>
                                        <small id="departemrnt_id_error" class="form-text text-muted error"></small>
                                    </div>

                                    <div class="form-group">
                                        <label class="text-label">Group Name <span class="required">*</span></label>
                                        <input type="text" name="sub_test_name" id="sub_test_name" maxlength="50" class="form-control" placeholder="" required>
                                          <small id="sub_test_name_error" class="form-text text-muted error"></small>

                                      </div>

                                      <div class="form-group">
                                        <label class="text-label">Group Code <span class="required">*</span></label>
                                        <input type="text" name="group_code" id="group_code" maxlength="50" class="form-control" placeholder="" required>
                                          <small id="group_code_error" class="form-text text-muted error"></small>

                                      </div>

                                      <div class="form-group">
                                        <label class="text-label">Amount <span class="required">*</span></label>
                                        <input type="number" name="group_amount" id="group_amount" maxlength="50" class="form-control" placeholder="" required>
                                          <small id="group_amount_error" class="form-text text-muted error"></small>

                                      </div>

                                      <div class="form-check">
                                        <input type="checkbox" name="display_status_3" id="display_status_3" class="form-check-input" checked >

                                      <label class="form-check-label" for="display_status_3">Display Status</label>
                                    </div>

                                    <div id="crude_3">
                                        <button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(3,1)" >Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                      </div>
                    </div>

                    {{-- END OOF FORM ROW --}}

                    {{-- GRID OF SUB TETS --}}
                    <div class="col-xl-8">
                        <div class="card">
                          <div class="card-body">
                            <div class="table-responsive pt-3">
                              <table id="sub_group_table" class="display">
                                <thead>
                                  <tr>
                                    <th>Sl No.</th>
                                    <th>Group Name</th>
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
                    {{-- END SUB TEST --}}

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
<script>






  $(document).ready(function() {

    table = $('#group_table').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    'ajax': {
        url: "<?php echo url('/') ?>/masterData/getlabGroups",
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
            "data": "groupname"
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

   //////////////////////////////////////////////////////////////

 table2 = $('#education-table').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    'ajax': {
        url: "<?php echo url('/') ?>/masterData/getTestMasterData",
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
            "data": "TestName"
        },
        {
            "data": "test_code"
        },
        {
            "data": "TestRate"
        },

        {
            "data": "id",
            "render": function(display_status, type, full, meta) {
                return '<div class="d-flex"><a href="#" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a><a href="#" class="delete btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
            }
        },
    ]
});
/////////////////////TABLE 3 for sub test


table3 = $('#sub_group_table').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    'ajax': {
        url: "<?php echo url('/') ?>/masterData/getTestSubgroups",
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
            "data": "TestName"
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
                // return '<div class="d-flex"><a href="#" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a><a href="#" class="delete btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                return '<div class="d-flex"><a href="#" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a></div>'
            }
        },
    ]
});

$('#group_table tbody').on('click', '.edit', function() {
            var data = table.row($(this).parents('tr')).data();
            $("#hid_met_id").val(data.id);
            $("#group_name").val(data.groupname?.trim());
            if(data.display_status==1)  $('#display_status').prop("checked", true); else  $('#display_status').prop("checked", false);
            crude_btn_manage(2,1);
        });

///////////////////////////////////////////////////////////////////////////////////////////////////////
    $('#sub_group_table tbody').on('click', '.edit', function() {
            var data = table3.row($(this).parents('tr')).data();
            console.log(data);
            $("#hid_sub_test_id").val(data.id);

            $("#departemrnt_id").val(data.TestDepartment);
            $('#departemrnt_id').trigger('change');

            $("#sub_test_name").val(data.TestName?.trim());
            $("#group_code").val(data.test_code?.trim());
            $("#group_amount").val(data.TestRate?.trim());

            if(data.display_status==1)  $('#display_status_3').prop("checked", true); else  $('#display_status_3').prop("checked", false);
            crude_btn_manage(2,3);
     });
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $('#education-table tbody').on('click', '.edit', function() {
            var data = table2.row($(this).parents('tr')).data();
            $("#hid_test_id").val(data.id);
            $("#group_id").val(data.group_id);
            $("#test_name").val(data.TestName);
            $("#test_amount").val(data.TestRate);
            $("#item_code").val(data.test_code);
            $("#result_typeid").val(data.result_type);

            $('#result_typeid').trigger('change');
            $('#group_id').trigger('change');

            $("#unit").val(data.unit);
            $("#report_data").val(data.report_data);
            $("#test_method").val(data.test_method);
            $("#order_num").val(data.order_num);
            $("#dtms_code").val(data.dtms_code);
            $("#chart_order_num").val(data.chart_order_no);

            if(data.display_status==1)  $('#display_status_edu').prop("checked", true); else  $('#display_status_edu').prop("checked", false);

            crude_btn_manage(2,2);


            loadtestConfig(data.id,data.result_type);
        });



    // doc ready end
    });




    $('#group_table tbody').on('click', '.delete', function() {
            var data = table.row($(this).parents('tr')).data();
            deletedata(1,data);
    });

    $('#education-table tbody').on('click', '.delete', function() {
            var data = table2.row($(this).parents('tr')).data();
            deletedata(2,data);
    });




////save

    function submit_form(page,crude)
        {

            var url="";
            if(page==1)
            {
                url='{{route('savelabGroups')}}';
                var form = $('#frm')[0];
            }
            else if(page==2)
            {
                url='{{route('saveTestMaster')}}';
                var form = $('#frm1')[0];
            }

            else if(page==3)
            {
                url='{{route('saveSubTestMaster')}}';
                var form = $('#frm2')[0];
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

                                    //document.getElementById("frm1").reset();
                                    if(page==1)
                                    {
                                        table.ajax.reload();
                                        document.getElementById("frm").reset();
                                        crude_btn_manage(1,page)
                                    }
                                    else if(page==2)
                                    {
                                        table2.ajax.reload();
                                      //  document.getElementById("frm1").reset();
                                    }
                                    else if(page==3)
                                    {
                                        table3.ajax.reload();
                                        document.getElementById("frm2").reset();
                                        crude_btn_manage(1,page)
                                    }



                                }
                                else if(result.status==2){
                                    sweetAlert("Oops...",result.message, "error");
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
                                $("#" + key + "_error").text(val[0]);
                        });
                    }

                    }
                });

        }

    function crude_btn_manage(type=1,page)
    {

        var crude;
        if(page==1)
        {
            crude="crude";
        }
        else if(page==2)
        {
            crude="crude_edu";
        }
        else if(page==3)
        {
            crude="crude_3";
        }



        if(type==1) $('#'+crude).html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\''+page+'\',\''+type+'\')" >Save</button>');
        else if(type==2)  $('#'+crude).html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\''+page+'\',\''+type+'\')" >Update</button>');

    }

    function deletedata(page,data)
    {
        url="";
        if(page==1){
            url='{{route('deletelabGroups')}}';
        }
        else if(page==2){
            url='{{route('deleteTestMaster')}}';
        }


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
                    url: url,
                    data: ajaxval,
                    success: function(result) {

                        if (result.status == 1) {
                            swal("Done", result.message, "success");
                            if(page==1){ table.ajax.reload(); }
                            else if(page==2){ table2.ajax.reload(); }
                            else if(page==3){ table3.ajax.reload(); }
                            else if(page==4){ table4.ajax.reload(); }
                            else if(page==5){ table5.ajax.reload(); }

                        } else {
                            sweetAlert("Oops...", result.message, "error");
                        }
                    },
                });
            }
            });



    }

    function duplicateLabRates(){

        var html="";
        html+="<div class='row'><div class='col-md-6'><div class='form-group'><label class='text-label'>Lab Name </label><input type='text' name='lab_name[]' id='lab_name'  maxlength='250' class='form-control' placeholder='' ></div></div>";
        html+="<div class='col-md-5'><div class='form-group'><label class='text-label'>Lab Amount</label><input type='text' name='lab_amount[]' id='lab_amount'  maxlength='250' class='form-control' placeholder='' ></div></div>";

        html+="<div class='col-md-1'><label style='opacity:0;' class='text-label'>Ranges in Value </label><i class='fa fa-trash' style='position: absolute;bottom: 30px;cursor:pointer;left:-5px;color:red;' onClick='remove_duplicateLabrates(this)'></i></div></div>";


        $('#duplicate_labrate').append(html);

    }

    function remove_duplicateLabrates(thiss){
        $(thiss).parent().parent().remove();

    }

    function duplicateLabInv(){
        var html="";
        html+="<div class='row'><div class='col-md-6'><div class='form-group'><label class='text-label'>Item Name </label><input type='text' name='lab_inv_name[]' id='lab_inv_name'  maxlength='250' class='form-control' placeholder='' ></div></div>";
        html+="<div class='col-md-5'><div class='form-group'><label class='text-label'>Issue Quantity </label><input type='text' name='lab_inv_qty[]' id='lab_inv_qty'  maxlength='250' class='form-control' placeholder='' ></div></div>";

        html+="<div class='col-md-1'><label style='opacity:0;' class='text-label'>Issue Quantity </label><i class='fa fa-trash' style='position: absolute;bottom: 30px;cursor:pointer;left:-5px;color:red;' onClick='remove_duplicateLabInv(this)'></i></div></div>";

        $('#duplicate_labinv').append(html);
    }
    function remove_duplicateLabInv(thiss){
        $(thiss).parent().parent().remove();

    }

    function duplicateLabPanel(){
        var html="";
        html+="<div class='row'><div class='col-md-3'><div class='form-group'><label class='text-label'>Ward Name <span class='required'></span></label><select class='form-control' name='ward_id[]' id='ward_id'><option value=''>Select</option>{{LoadCombo('wards_master','id','ward_name','','where display_status=1 AND is_deleted=0','order by id desc');}}</select><small id='ward_id_error' class='form-text text-muted error'></small></div></div>";
        html+="<div class='col-md-2'><div class='form-group'><label class='text-label'>Department <span class='required'></span></label><select class='form-control' name='department_id[]' id='department_id'><option value=''>Select</option>{{LoadCombo('departments','id','department_name','','where display_status=1 AND is_deleted=0','order by id desc');}}</select><small id='department_id_error' class='form-text text-muted error'></small></div></div>";
        html+="<div class='col-md-2'><div class='form-group'><label class='text-label'>Panel <span class='required'>*</span></label><select class='form-control' name='ward_id[]' id='ward_id'><option value=''>Select</option>{{LoadCombo('wards_master','id','ward_name','','where display_status=1 AND is_deleted=0','order by id desc');}}</select><small id='group_id_error' class='form-text text-muted error'></small></div></div>";
        html+="<div class='col-md-2'><div class='form-group'><label class='text-label'> Amount <span class='required'></span></label><input type='text' name='panel_amount[]' id='panel_amount' onKeyPress='return blockSpecialChar(event)' maxlength='250' class='form-control' placeholder='' ></div></div>";
        html+="<div class='col-md-2'><div class='form-group'><label class='text-label'> Item Code <span class='required'></span></label><input type='text' name='panel_code[]' id='panel_code' onKeyPress='return blockSpecialChar(event)' maxlength='250' class='form-control' placeholder='' ></div></div>";
        html+="<div class='col-md-1'><label style='opacity:0;' class='text-label'>Issue Quantity </label><i class='fa fa-trash' style='position: absolute;bottom: 30px;cursor:pointer;left:-5px;color:red;' onClick='remove_duplicateLabPanel(this)'></i></div></div>";

        $('#duplicate_labpanel').append(html);
    }

    function remove_duplicateLabPanel(thiss){
        $(thiss).parent().parent().remove();

    }

    function Adddata(type=0,data="",clear=0){


        if(type ==0){
            $('#append_data').empty();
            $("#no_rows").val(0);
        }
        var typeid=$('#result_typeid').val();
        var no_rows=$("#no_rows").val();
        no_rows=parseInt(no_rows)+1;





        $('.addbtn').removeClass('hidedata');

        var from_age="";
        var to_age="";

        var maleSelecter="";
        var femaleSelector="";
        var otherSelector="";

        if(data)
        {


           from_age=data.from_age;
           to_age=data.to_age;

           if(data.gender==1){
            maleSelecter="Selected";
           }
           else if(data.gender==2){
            femaleSelector="Selected";
           }
           else if(data.gender==3){
            otherSelector="Selected";
           }

        }

        var html="<div class='row'>";

        html+="<div class='col-md-3'><div class='form-group'><label class='text-label'>From Age</label><input type='text' value='"+from_age+"' name='from_age_"+no_rows+"'   id='from_age' onKeyPress='return blockSpecialChar(event)' maxlength='50' class='form-control' placeholder=''></div></div>";

        html+="<div class='col-md-2'><div class='form-group'><label class='text-label'>To Age</label><input type='text' value='"+to_age+"' name='to_age_"+no_rows+"' id='to_age' onKeyPress='return blockSpecialChar(event)' maxlength='50' class='form-control' placeholder=''></div></div>";

        html+="<div class='col-md-2'><div class='form-group'><label class='text-label'>Gender</label><select name='gender_"+no_rows+"' id='gender'  class='form-control' placeholder=''><option value='1' "+maleSelecter+">MALE</option><option value='2' "+femaleSelector+">FEMALE</option></select></div></div>";


        if(typeid==1){
            var colorVal="";

            var colors=$("#hid_colors").val();
            html+="<div class='col-md-3'><div class='form-group'><label class='text-label'>Color</label><select  name='color_"+no_rows+"' id='color_"+no_rows+"' class='form-control'>"+colors+"</select></div></div>";

        }else if(typeid==4)
        {
            var clarityVal="";

        var colors=$("#hid_clarity").val();
        html+="<div class='col-md-3'><div class='form-group'><label class='text-label'>Clarity</label><select  name='clarity_"+no_rows+"' id='clarity_"+no_rows+"' class='form-control'>"+colors+"</select></div></div>";

        }
        else if(typeid==2){

            var from_range="";
            var to_range="";
                if(data)
                {
                    from_range=data.from_range;
                    to_range=data.to_range;
                }

            html+="<div class='col-md-2'><div class='form-group'><label class='text-label'>From Rng</label><input type='text' value='"+from_range+"' name='from_range_"+no_rows+"' id='education_name' onKeyPress='return blockSpecialChar(event)' maxlength='50' class='form-control' placeholder=''></div></div>";

            html+="<div class='col-md-2'><div class='form-group'><label class='text-label'>To Range</label><input type='text' value='"+to_range+"'  name='to_range_"+no_rows+"' id='education_name' onKeyPress='return blockSpecialChar(event)' maxlength='50' class='form-control' placeholder=''></div></div>";

        }
        else if(typeid==3){

            html+="<div class='col-md-3'><div class='form-group'><label class='text-label'>Normal Value</label><select  name='postive_negative_"+no_rows+"'  id='postive_negative_"+no_rows+"' class='form-control'><option value='0'> --- <sup></sup></option><option value='1'>+ <sup>ve</sup></option><option value='2'>- <sup>ve</sup></option><option value='3'><sup>Normal</sup></option></select></div></div>";
        }

        html+="<div class='col-md-1'><div class='form-group'><label style='opacity:0' class='text-label'>delete</label><br><i style='color:red' class='fa fa-trash' onclick=removeFiled(this);></i></div>";




        html+="</div>";
        $("#no_rows").val(no_rows);
        $('#append_data').append(html);

        if(typeid==1){
            if(data)
                {
                    colorVal=data.colour_id;
                    $("#color_"+no_rows).val(colorVal);
                }
        }
        else if(typeid==3){
            if(data)
                {
                    itemval=data.positive_negative;
                    $("#postive_negative_"+no_rows).val(itemval);
                }
        }
        else if(typeid==4){
            if(data)
                {
                    itemval=data.clarity_id;
                    $("#clarity_"+no_rows).val(itemval);
                }
        }



    }
    function removeFiled(thiss){
        $(thiss).parent().parent().parent().remove();
    }


    function loadtestConfig(testId,ResultType=0)
    {
        if(testId>0)
        {
            ajaxval={test_id:testId}
            $.ajax({
                    type: "POST",
                    url: "<?php echo url('/') ?>/masterData/getTestConfig",
                    data: ajaxval,
                    success: function(result) {
                            if(result)
                            {
                                $('#append_data').empty();
                                $("#no_rows").val(0);


                                data=JSON.parse(result);
                                $.each(data, function(key,value) {
                                    console.log(value);
                                    Adddata(ResultType,value,1);
                                })
                            }
                    },
                });
        }
    }
</script>

<style>
    .panelback{
        background: #01579b;
        padding: 10px;
        color: #fff;
    }
    .hidedata{
        display: none;
    }

    #append_data{
        margin-left:17px;
    }
</style>
