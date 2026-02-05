<style>
    table.dataTable
    {
        width: 100% !important;
    }
</style>
<div class="content-body">
  <div class="container-fluid pt-2">
    <div class="row" style="">
      <div class="col-md-12">

        <div class="profile-tab pb-2">
          <div class="custom-tab-1">
            <ul class="nav nav-tabs">
            <li class="nav-item"><a href="#marital-status" data-toggle="tab" class="nav-link active show">Marital Status</a>  </li>
              <li class="nav-item"><a href="#education" data-toggle="tab" class="nav-link">Education</a>  </li>
              <li class="nav-item"><a href="#occupation" data-toggle="tab" class="nav-link">Occupation </a>  </li>
              <li class="nav-item"><a href="#blood-group" data-toggle="tab" class="nav-link">Blood group  </a>  </li>
              <li class="nav-item"><a href="#annual-income" data-toggle="tab" class="nav-link">Annual Income </a>  </li>
            </ul>
            <div class="tab-content pt-3">
              <div id="marital-status" class="tab-pane fade active show">

                <div class="row">
                  <div class="col-xl-4">
                    <div class="card">
                      <div class="card-body">

                        <div class=" mb-5">
                          <!-- <h5 class="text-primary d-inline">Today Highlights</h5><a href="javascript:void()" class="pull-right f-s-16">More</a> -->
                          <form action="#"  name="frm" id="frm" action="" method="POST">
                            <input type="hidden" name="hid_met_id" id="hid_met_id">
                            <div class="form-group">
                              <label class="text-label">Marital Status <span class="required">*</span></label>
                              <input type="text" name="marital_status_name" id="marital_status_name" onKeyPress="return Onlycharecters(event)" maxlength="50" class="form-control" placeholder="" required>
                                <small id="marital_status_name_error" class="form-text text-muted error"></small>

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
                          <table id="marital-status-table" class="display">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>Marital Status</th>
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
              <div id="education" class="tab-pane fade">
                <div class="row">
                  <div class="col-xl-4">
                    <div class="card">
                      <div class="card-body">

                        <div class=" mb-5">
                            <form action="#"  name="frm1" id="frm1" action="" method="POST">
                                <input type="hidden" name="hid_edu_id" id="hid_edu_id">
                            <div class="form-group">
                              <label class="text-label">Education <span class="required">*</span></label>
                              <input type="text" name="education_name" id="education_name" onKeyPress="return blockSpecialChar(event)" maxlength="50" class="form-control" placeholder="" required>
                              <small id="education_name_error" class="form-text text-muted error"></small>


                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="display_status_edu" id="display_status_edu" class="form-check-input" checked >
                              <label class="form-check-label" for="displayStatus">Display Status</label>
                            </div>

                            <div id="crude_edu">
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
                          <table id="education-table" class="display">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>Education</th>
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
              <div id="occupation" class="tab-pane fade">
                <div class="row">
                  <div class="col-xl-4">
                    <div class="card">
                      <div class="card-body">

                        <div class=" mb-5">
                            <form action="#"  name="frm2" id="frm2" action="" method="POST">
                                <input type="hidden" name="hid_occ_id" id="hid_occ_id">
                            <div class="form-group">
                              <label class="text-label">Occupation <span class="required">*</span></label>
                              <input type="text" name="occupation_name" id="occupation_name" onKeyPress="return Onlycharecters(event)" maxlength="50" class="form-control" placeholder="" required>
                                <small id="occupation_name_error" class="form-text text-muted error"></small>

                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="display_status_occ" id="display_status_occ" class="form-check-input" checked >
                              <label class="form-check-label" for="displayStatus">Display Status</label>
                            </div>

                            <div id="crude_occ">
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
                          <table id="occupation-table" class="display">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>Occupation</th>
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
              <div id="blood-group" class="tab-pane fade">
                <div class="row">
                  <div class="col-xl-4">
                    <div class="card">
                      <div class="card-body">

                        <div class=" mb-5">
                            <form action="#"  name="frm3" id="frm3" action="" method="POST">
                                <input type="hidden" name="hid_blood_id" id="hid_blood_id">
                            <div class="form-group">
                              <label class="text-label">Blood group <span class="required">*</span></label>
                              <input type="text" name="blood_group_name" id="blood_group_name"  maxlength="50" class="form-control" placeholder="" required>
                              <small id="blood_group_name_error" class="form-text text-muted error"></small>

                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="display_status_blood" id="display_status_blood" class="form-check-input" checked >

                              <label class="form-check-label" for="displayStatus">Display Status</label>
                            </div>

                            <div id="crude_blood">
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
                          <table id="blood-group-table" class="display">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>Blood group</th>
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
              <div id="annual-income" class="tab-pane fade">
                <div class="row">
                  <div class="col-xl-4">
                    <div class="card">
                      <div class="card-body">

                        <div class=" mb-5">
                            <form name="frm4" id="frm4" method="POST" action="">
                                <input type="hidden" name="hid_incom_id" id="hid_incom_id">
                            <div class="form-group">
                              <label class="text-label">Annual Income <span class="required">*</span></label>
                              <input type="text" name="annual_income_name" id="annual_income_name" onKeyPress="return onlyNumbers(event)" maxlength="50" class="form-control" placeholder="" required>
                              <small id="annual_income_name_error" class="form-text text-muted error"></small>


                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="display_status_incomef" id="display_status_incomef" class="form-check-input" checked >
                              <label class="form-check-label" for="displayStatus">Display Status</label>
                            </div>

                            <div id="crude_incom">
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
                          <table id="annual-income-table" class="display">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>Annual Income</th>
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

    table = $('#marital-status-table').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    'ajax': {
        url: "<?php echo url('/') ?>/masterData/getMeritalStatus",
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
            "data": "merital_status_name"
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
        url: "<?php echo url('/') ?>/masterData/getEducations",
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
            "data": "education_name"
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

  ///////////////////////////////////////////////////
  table3 = $('#occupation-table').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    'ajax': {
        url: "<?php echo url('/') ?>/masterData/getOccupation",
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
            "data": "occupation_name"
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




table4 = $('#blood-group-table').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    'ajax': {
        url: "<?php echo url('/') ?>/masterData/getBloodGroup",
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
            "data": "blood_group_name"
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

table5 = $('#annual-income-table').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    'ajax': {
        url: "<?php echo url('/') ?>/masterData/getAnnualIncome",
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
            "data": "annual_income_name"
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

$('#marital-status-table tbody').on('click', '.edit', function() {
    $("[id*='_error']").text('');
    var data = table.row($(this).parents('tr')).data();
        $("#hid_met_id").val(data.id);
        $("#marital_status_name").val(data.merital_status_name.trim());
        if(data.display_status==1)  $('#display_status').prop("checked", true); else  $('#display_status').prop("checked", false);
        crude_btn_manage(2,1);
    });


    $('#education-table tbody').on('click', '.edit', function() {
        $("[id*='_error']").text('');
        var data = table2.row($(this).parents('tr')).data();
        $("#hid_edu_id").val(data.id);
        $("#education_name").val(data.education_name.trim());
        if(data.display_status==1)  $('#display_status_edu').prop("checked", true); else  $('#display_status_edu').prop("checked", false);
        crude_btn_manage(2,2);
    });

    $('#occupation-table tbody').on('click', '.edit', function() {
        $("[id*='_error']").text('');
        var data = table3.row($(this).parents('tr')).data();
        $("#hid_occ_id").val(data.id);
        $("#occupation_name").val(data.occupation_name.trim());
        if(data.display_status==1)  $('#display_status_occ').prop("checked", true); else  $('#display_status_occ').prop("checked", false);
        crude_btn_manage(2,3);
    });

    $('#blood-group-table tbody').on('click', '.edit', function() {
        $("[id*='_error']").text('');
        var data = table4.row($(this).parents('tr')).data();
        $("#hid_blood_id").val(data.id);
        $("#blood_group_name").val(data.blood_group_name.trim());
        if(data.display_status==1)  $('#display_status_blood').prop("checked", true); else  $('#display_status_blood').prop("checked", false);
        crude_btn_manage(2,4);
    });

    $('#annual-income-table tbody').on('click', '.edit', function() {
        $("[id*='_error']").text('');
        var data = table5.row($(this).parents('tr')).data();
        $("#hid_incom_id").val(data.id);
        $("#annual_income_name").val(data.annual_income_name.trim());
        if(data.display_status==1)  $('#display_status_incomef').prop("checked", true); else  $('#display_status_incomef').prop("checked", false);
        crude_btn_manage(2,5);
    });

 // doc ready end
});




$('#marital-status-table tbody').on('click', '.delete', function() {
        var data = table.row($(this).parents('tr')).data();
        deletedata(1,data);
});

$('#education-table tbody').on('click', '.delete', function() {
        var data = table2.row($(this).parents('tr')).data();
        deletedata(2,data);
});

$('#occupation-table tbody').on('click', '.delete', function() {
        var data = table3.row($(this).parents('tr')).data();
        deletedata(3,data);
});

$('#blood-group-table tbody').on('click', '.delete', function() {
        var data = table4.row($(this).parents('tr')).data();
        deletedata(4,data);
});

$('#annual-income-table tbody').on('click', '.delete', function() {
        var data = table5.row($(this).parents('tr')).data();
        deletedata(5,data);
});


////save

function submit_form(page,crude)
    {
        var url="";
        if(page==1)
        {
            url='{{route('saveMaritalStatus')}}';
            var form = $('#frm')[0];
        }
        else if(page==2)
        {
            url='{{route('saveEducation')}}';
            var form = $('#frm1')[0];
        }

        else if(page==3)
        {
            url='{{route('saveOccupation')}}';
            var form = $('#frm2')[0];
        }
        else if(page==4)
        {
            url='{{route('saveBloodGroup')}}';
            var form = $('#frm3')[0];
        }
        else if(page==5)
        {
            url='{{route('saveAnnualIncome')}}';
            var form = $('#frm4')[0];
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
                                }
                                else if(page==2)
                                {
                                    table2.ajax.reload();
                                    document.getElementById("frm1").reset();
                                }
                                else if(page==3)
                                {
                                    table3.ajax.reload();
                                    document.getElementById("frm2").reset();
                                }
                                else if(page==4)
                                {
                                    table4.ajax.reload();
                                    document.getElementById("frm3").reset();
                                }
                                else if(page==5)
                                {
                                    table5.ajax.reload();
                                    document.getElementById("frm4").reset();
                                }

                                crude_btn_manage(1,page)
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
        crude="crude_occ";
    }
    else if(page==4)
    {
        crude="crude_blood";
    }
    else if(page==5)
    {
        crude="crude_incom";
    }


    if(type==1) $('#'+crude).html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\''+page+'\',\''+type+'\')" >Save</button>');
    else if(type==2)  $('#'+crude).html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\''+page+'\',\''+type+'\')" >Update</button>');

}

function deletedata(page,data)
{
    url="";
    if(page==1){
        url='{{route('deleteMeritialStatus')}}';
    }
    else if(page==2){
        url='{{route('deleteEducation')}}';
    }
    else if(page==3){
        url='{{route('deleteOccupation')}}';
    }
    else if(page==4){
        url='{{route('deleteBloodGroup')}}';
    }
    else if(page==5){
        url='{{route('deleteAnnualIncome')}}';
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
</script>
