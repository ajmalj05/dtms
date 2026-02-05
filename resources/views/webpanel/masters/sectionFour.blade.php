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
              <li class="nav-item"><a href="#patient-reference" data-toggle="tab" class="nav-link active show">Patient
                  Reference</a>
              </li>
              <li class="nav-item"><a href="#id-proof-type" data-toggle="tab" class="nav-link">ID Proof Type</a>
              </li>
              <li class="nav-item"><a href="#department" data-toggle="tab" class="nav-link">Department</a>
              </li>
            </ul>
            <div class="tab-content pt-3">
              <div id="patient-reference" class="tab-pane fade active show">

                <div class="row">
                  <div class="col-xl-4">
                    <div class="card">
                      <div class="card-body">

                        <div class=" mb-5">
                          <!-- <h5 class="text-primary d-inline">Today Highlights</h5><a href="javascript:void()" class="pull-right f-s-16">More</a> -->
                          <form action="#"  name="frm" id="frm" action="" method="POST">
                              <input type="hidden" name="hid_ref_id" id="hid_ref_id">
                            <div class="form-group">
                              <label class="text-label">Patient  Reference <span class="required">*</span></label>
                              <input type="text" name="patient_reference_name" id="patient_reference_name" onKeyPress="return blockSpecialChar(event)" maxlength="50" class="form-control" placeholder="" required>
                                <small id="patient_reference_name_error" class="form-text text-muted error"></small>
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
                          <table id="PatientReference" class="display">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>Patient Reference</th>
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
              <div id="id-proof-type" class="tab-pane fade">

                <div class="row">
                  <div class="col-xl-4">
                    <div class="card">
                      <div class="card-body">

                        <div class=" mb-5">
                            <form name="frm1" id="frm1" method="POST" action="">
                                <input type="hidden" name="hid_proof_id" id="hid_proof_id">
                            <div class="form-group">
                              <label class="text-label">ID Proof Type <span class="required">*</span></label>
                              <input type="text" name="id_proof_name" id="id_proof_name" onKeyPress="return Onlycharecters(event)" maxlength="50" class="form-control" placeholder="" required>
                              <small id="id_proof_name_error" class="form-text text-muted error"></small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="display_status_proof" id="display_status_proof" class="form-check-input" checked >
                              <label class="form-check-label" for="displayStatus">Display Status</label>
                            </div>

                            <div id="crude_proof">
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
                          <table id="IDProofType" class="display">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>ID Proof Type</th>
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
              <div id="department" class="tab-pane fade">
              <div class="row">
                  <div class="col-xl-4">
                    <div class="card">
                      <div class="card-body">

                        <div class=" mb-5">
                          <!-- <h5 class="text-primary d-inline">Today Highlights</h5><a href="javascript:void()" class="pull-right f-s-16">More</a> -->
                          <form action="#"  name="frm2" id="frm2" action="" method="POST">
                            <input type="hidden" name="hid_dep_id" id="hid_dep_id">
                            <div class="form-group">
                              <label class="text-label">Department Name <span class="required">*</span></label>
                              <input type="text" name="department_name" id="department_name" onKeyPress="return blockSpecialChar(event)" maxlength="50" class="form-control" placeholder="" required>
                                <small id="department_name_error" class="form-text text-muted error"></small>

                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="display_status" id="display_status_department" class="form-check-input" checked >

                              <label class="form-check-label" for="displayStatus">Display Status</label>
                            </div>

                            <div id="crud_dep">
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
                          <table id="department-table" class="display">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>Department_name</th>
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

        //dt
    table = $('#PatientReference').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    'ajax': {
        url: "<?php echo url('/') ?>/masterData/getPatientRefernce",
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
            "data": "patient_reference_name"
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


table2 = $('#IDProofType').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    'ajax': {
        url: "<?php echo url('/') ?>/masterData/getIdProofType",
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
            "data": "id_proof_name"
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
table3 = $('#department-table').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    'ajax': {
        url: "<?php echo url('/') ?>/masterData/getDepartmentDetails",
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
            "data": "department_name"
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

        //end of dt


    $('#PatientReference tbody').on('click', '.edit', function() {
        $("[id*='_error']").text('');
        var data = table.row($(this).parents('tr')).data();
        $("#hid_ref_id").val(data.id);
        $("#patient_reference_name").val(data.patient_reference_name.trim());
        if(data.display_status==1)  $('#display_status').prop("checked", true); else  $('#display_status').prop("checked", false);
        crude_btn_manage(2,1);
    });


    $('#IDProofType tbody').on('click', '.edit', function() {
        $("[id*='_error']").text('');
        var data = table2.row($(this).parents('tr')).data();
        $("#hid_proof_id").val(data.id);
        $("#id_proof_name").val(data.id_proof_name.trim());
        if(data.display_status==1)  $('#display_status_proof').prop("checked", true); else  $('#display_status_proof').prop("checked", false);
        crude_btn_manage(2,2);
    });

    $('#department-table tbody').on('click', '.edit', function() {
        $("[id*='_error']").text('');
        var data = table3.row($(this).parents('tr')).data();
        $("#hid_dep_id").val(data.id);
        $("#department_name").val(data.department_name.trim());
        if(data.display_status==1)  $('#display_status_department').prop("checked", true); else  $('#display_status_department').prop("checked", false);
        crude_btn_manage(2,3);
    });

    $('#PatientReference tbody').on('click', '.delete', function() {
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
                url: "<?php echo url('/') ?>/masterData/deletePatientReference",
                data: ajaxval,
                success: function(result) {

                    if (result.status == 1) {
                        swal("Done", result.message, "success");
                        table.ajax.reload();
                    } else {
                        sweetAlert("Oops...", result.message, "error");
                    }
                },
            });
        }
        })
    });

    $('#department-table tbody').on('click', '.delete', function() {
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
                url: "<?php echo url('/') ?>/masterData/deleteDepartment",
                data: ajaxval,
                success: function(result) {

                    if (result.status == 1) {
                        swal("Done", result.message, "success");
                        table3.ajax.reload();
                    } else {
                        sweetAlert("Oops...", result.message, "error");
                    }
                },
            });
        }
        })
    });


    $('#IDProofType tbody').on('click', '.delete', function() {
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
                url: "<?php echo url('/') ?>/masterData/deleteIdProof",
                data: ajaxval,
                success: function(result) {

                    if (result.status == 1) {
                        swal("Done", result.message, "success");
                        table2.ajax.reload();
                    } else {
                        sweetAlert("Oops...", result.message, "error");
                    }
                },
            });
        }
        })
    });

    ////////////////////////

  });


  function submit_form(page,crude)
    {
        var url="";
        if(page==1)
        {
            url='{{route('savePatientReference')}}';
            var form = $('#frm')[0];
        }
        else  if(page==2)
        {
            url='{{route('saveIdProofType')}}';
            var form = $('#frm1')[0];
        }
        else  if(page==3)
        {
            url='{{route('saveDepartment')}}';
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
                                document.getElementById("frm").reset();
                                document.getElementById("frm1").reset();
                                document.getElementById("frm2").reset();
                                table.ajax.reload();
                                table2.ajax.reload();
                                table3.ajax.reload();
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
    if(page==1)
    {
        if(type==1) $('#crude').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\''+page+'\',\''+type+'\')" >Save</button>');
	   else if(type==2)  $('#crude').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\''+page+'\',\''+type+'\')" >Update</button>');
    }
    else if(page==2)
    {
        if(type==1) $('#crude_proof').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\''+page+'\',\''+type+'\')" >Save</button>');
	   else if(type==2)  $('#crude_proof').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\''+page+'\',\''+type+'\')" >Update</button>');

    }
    else if(page==3)
    {
        if(type==1) $('#crud_dep').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\''+page+'\',\''+type+'\')" >Save</button>');
	   else if(type==2)  $('#crud_dep').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\''+page+'\',\''+type+'\')" >Update</button>');

    }
}
</script>
