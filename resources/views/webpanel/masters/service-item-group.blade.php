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
              <li class="nav-item"><a href="#service-item-group-tab" data-toggle="tab" class="nav-link active show">Service Item Group</a>
              </li>
            </ul>
            <div class="tab-content pt-3">
              <div id="service-item-group-tab" class="tab-pane fade active show">

                <div class="row">
                  <div class="col-xl-4">
                    <div class="card">
                      <div class="card-body">

                        <div class=" mb-5">
                            <form name="service-group-frm" id="service-group-frm" method="POST" action="">
                                <input type="hidden" name="hid_service_group_id" id="hid_service_group_id">
                                <div class="form-group">
                                    <label class="text-label">Service Group <span class="required">*</span></label>
                                    <input type="text" name="service_group_name" id="service_group_name" onKeyPress="return Onlycharecters(event)" maxlength="50" class="form-control" placeholder="" required>
                                    <small id="service_group_name_error" class="form-text text-muted error"></small>
                                </div>

                            <div class="form-check">
                                <input type="checkbox" name="display_status" id="display_status" class="form-check-input" checked >
                              <label class="form-check-label" for="displayStatus">Display Status</label>
                            </div>

                            <div id="crud_service_group">
                                <button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="saveServiceGroupData(1,1)" >Save</button>
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
                          <table id="ServiceGroup" class="display">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>Service Group</th>
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

<link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
<link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
<script src='date_picker/js/bootstrap-datepicker.min.js' type='text/javascript'></script>
<script src="./vendor/select2/js/select2.full.min.js"></script>
<script src="./js/plugins-init/select2-init.js"></script>
<script>



  $(document).ready(function() {
    InitializeDT1();

    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        currTabTarget = $(e.target).attr('href');

        if(currTabTarget=="#service-item-group-tab"){
          InitializeDT1();
        }
    });


    function InitializeDT1(){
        table = $('#ServiceGroup').DataTable({
            "destroy": true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            'ajax': {
                url: "<?php echo url('/') ?>/get-service-item-group-data",
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
                    "data": "group_name"
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
                    "render": function(data, type, full, meta) {
                        return '<div class="d-flex"><a href="javascript:void(0)" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a><a href="#" class="delete btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                    }
                },
            ]
        });
    }

        //end of dt


    $('#ServiceGroup tbody').on('click', '.edit', function() {
          $("[id*='_error']").text('');
             var data = table.row($(this).parents('tr')).data();
            $("#hid_service_group_id").val(data.id);
            $('#service_group_name').val(data.group_name);
            if(data.display_status==1)  $('#display_status').prop("checked", true); else  $('#display_status').prop("checked", false);
            crude_btn_manage(2,1);
        });


    $('#ServiceGroup tbody').on('click', '.delete', function() {
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
                url: "<?php echo url('/') ?>/delete-service-item-group-data",
                data: ajaxval,
                success: function(result) {

                    if (result.status == 1) {
                        swal("Done", result.message, "success");
                        document.getElementById("service-group-frm").reset();
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

    ////////////////////////

  });


  function saveServiceGroupData(page,crude)
    {
        $("[id*='_error']").text('');
        var url="";
        if(page==1)
        {
            url='{{route('save-service-item-group-data')}}';
            var form = $('#service-group-frm')[0];
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
                                document.getElementById("service-group-frm").reset();
                                if(page==1){
                                    $('#service_group_name').val('').selectpicker('refresh');
                                    table.ajax.reload();                                }

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
        if(type==1) $('#crud_service_group').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="saveServiceGroupData(\''+page+'\',\''+type+'\')" >Save</button>');
        else if(type==2)  $('#crud_service_group').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="saveServiceGroupData(\''+page+'\',\''+type+'\')" >Update</button>');

    }
}

</script>
