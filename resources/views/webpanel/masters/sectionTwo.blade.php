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
              <li class="nav-item"><a href="#salutations" data-toggle="tab" class="nav-link active show">Salutations</a>
              </li>
              <li class="nav-item"><a href="#relation_master" data-toggle="tab" class="nav-link">Relation Master</a>
              </li>
              <li class="nav-item"><a href="#religion_master" data-toggle="tab" class="nav-link">Religion Master</a>
              </li>
            </ul>
            <div class="tab-content pt-3">
              <div id="salutations" class="tab-pane fade active show">

                <div class="row">
                  <div class="col-xl-4">
                    <div class="card">
                      <div class="card-body">

                        <div class=" mb-5">
                          <!-- <h5 class="text-primary d-inline">Today Highlights</h5><a href="javascript:void()" class="pull-right f-s-16">More</a> -->
                          <form action="#"  name="frm" id="frm" method="POST" action="">
                            <input type="hidden" name="hid_salutation_id" id="hid_salutation_id">
                            <div class="form-group">
                              <label class="text-label">Salutations <span class="required">*</span></label>
                              <input type="text" name="salutation_name" id="salutation_name" onKeyPress="return Onlycharecters(event)" maxlength="15" class="form-control" placeholder="" required>
                              <small id="salutation_name_error" class="form-text text-muted error"></small>
                            </div>
                            <div class="form-check">
                              <input type="checkbox" class="form-check-input" id="display_status" name="display_status" checked>
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
                          <table id="salutations_table" class="display">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>Salutations</th>
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
              <div id="relation_master" class="tab-pane fade">
                <div class="row">
                  <div class="col-xl-4">
                    <div class="card">
                      <div class="card-body">
                        <div class=" mb-5">
                            <form action="#"  name="frm1" id="frm1" method="POST">
                                <input type="hidden" name="hid_relation_id" id="hid_relation_id">
                            <div class="form-group">
                              <label class="text-label">Relation<span class="required">*</span></label>
                              <input type="text" name="relation_name" id="relation_name" onKeyPress=" return blockWithoutSlashAndOnlyCharacter(event);" maxlength="50" class="form-control" placeholder="" value="" required>
                              <small id="relation_name_error" class="form-text text-muted error"></small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="rel_display_status" id="rel_display_status" class="form-check-input" checked>

                              <label class="form-check-label" for="displayStatus">Display Status</label>
                            </div>
                            <div id="rel_crude">
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
                          <table id="relation_master_table" class="display">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>Relation</th>
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
              <div id="religion_master" class="tab-pane fade">
                <div class="row">
                  <div class="col-xl-4">
                    <div class="card">
                      <div class="card-body">
                        <div class=" mb-5">
                            <form action="#"  name="frm2" id="frm2" method="POST">
                                <input type="hidden" name="hid_regn_id" id="hid_regn_id">
                            <div class="form-group">
                              <label class="text-label">Religion <span class="required">*</span></label>
                              <input type="text" name="religion_name" id="religion_name" onKeyPress="return Onlycharecters(event)" maxlength="50" class="form-control" placeholder="" value="" required>
                              <small id="religion_name_error" class="form-text text-muted error"></small>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="rgn_display_status" id="rgn_display_status" class="form-check-input" checked >

                              <label class="form-check-label" for="displayStatus">Display Status</label>
                            </div>

                            <div id="regn_crude">
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
                          <table id="religion_master_table" class="display">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>Religion</th>
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




/////////////// SAVE DATA ////////////////////////////////
  function submit_form(page,crude)
    {

        var url="";
        if(page==1)
        {
            url='{{route('saveSalutation')}}';
            var form = $('#frm')[0];
        }
        else if(page==2)
        {
            url='{{route('saveRelation')}}';
            var form = $('#frm1')[0];
        }
        else if(page==3)
        {
            url='{{route('saveReligion')}}';
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
///////////////////////////////////////////////////////////////////


/////////////////////////DATAT TABLES

$(document).ready(function() {

    table = $('#salutations_table').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    'ajax': {
        url: "<?php echo url('/') ?>/masterData/getSalutation",
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
            "data": "salutation_name"
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



///////////////////////////////////////////////////////////////////////////////////////////////
$('#salutations_table tbody').on('click', '.edit', function() {
    $("[id*='_error']").text('');
    var data = table.row($(this).parents('tr')).data();
         $("#hid_salutation_id").val(data.id);
		 $("#salutation_name").val(data.salutation_name.trim());
		 if(data.display_status==1)  $('#display_status').prop("checked", true); else  $('#display_status').prop("checked", false);
		 crude_btn_manage(2,1);
    });

///////////////////////////////////////////////////////////////////////////////////////////////////
$('#salutations_table tbody').on('click', '.delete', function() {
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
            url: "<?php echo url('/') ?>/masterData/deleteSalutation",
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
/////////////////////////////////////////

table2 = $('#relation_master_table').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    'ajax': {
        url: "<?php echo url('/') ?>/masterData/getRelation",
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
            "data": "relation_name"
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

///////////////////////////////////////////////////////////////////////////////////////////////
$('#relation_master_table tbody').on('click', '.edit', function() {
    $("[id*='_error']").text('');
    var data = table2.row($(this).parents('tr')).data();
         $("#hid_relation_id").val(data.id);
		 $("#relation_name").val(data.relation_name.trim());
		 if(data.display_status==1)  $('#rel_display_status').prop("checked", true); else  $('#rel_display_status').prop("checked", false);
		 crude_btn_manage(2,2);
    });

///////////////////////////////////////////////////////////////////////////////////////////////////


$('#relation_master_table tbody').on('click', '.delete', function() {
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
            url: "<?php echo url('/') ?>/masterData/deleteRelation",
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
//////////////////////////////////////////////////////////////////////////////////////////////////////

table3 = $('#religion_master_table').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    'ajax': {
        url: "<?php echo url('/') ?>/masterData/getReligion",
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
            "data": "religion_name"
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


//////////////////////////////////////////
$('#religion_master_table tbody').on('click', '.edit', function() {
    $("[id*='_error']").text('');
    var data = table3.row($(this).parents('tr')).data();
         $("#hid_regn_id").val(data.id);
		 $("#religion_name").val(data.religion_name.trim());
		 if(data.display_status==1)  $('#rgn_display_status').prop("checked", true); else  $('#rgn_display_status').prop("checked", false);
		 crude_btn_manage(2,3);
    });
//////////////////////////////////////////
    $('#religion_master_table tbody').on('click', '.delete', function() {
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
            url: "<?php echo url('/') ?>/masterData/deleteReligion",
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


//end of document ready

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
        if(type==1) $('#rel_crude').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\''+page+'\',\''+type+'\')" >Save</button>');
	   else if(type==2)  $('#rel_crude').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\''+page+'\',\''+type+'\')" >Update</button>');

    }
    else if(page==3)
    {
        if(type==1) $('#regn_crude').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\''+page+'\',\''+type+'\')" >Save</button>');
	   else if(type==2)  $('#regn_crude').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\''+page+'\',\''+type+'\')" >Update</button>');

    }
}
</script>
