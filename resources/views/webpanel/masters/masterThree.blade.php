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
                            <li class="nav-item"><a href="#email_master" data-toggle="tab" class="nav-link active show">Email Master</a>
                            </li>
                        </ul>
                        <div class="tab-content pt-3">
                            <div id="email_master" class="tab-pane fade active show">

                                    <div class="row">
                                        <div class="col-xl-4">
                                            <div class="card">
                                                <div class="card-body">

                                                  <div class=" mb-5">
                                                    <!-- <h5 class="text-primary d-inline">Today Highlights</h5><a href="javascript:void()" class="pull-right f-s-16">More</a> -->
                                                    <form action="#"  name="frm" id="frm">

                                                      <input type="hidden" name="hidextid" id="hidextid">
                                                      <div class="form-group">
                                                        <label class="text-label">Email Ext. <span class="required">*</span></label>
                                                        <input type="text" name="extension" id="extension"  maxlength="70" class="form-control" placeholder="" required>
                                                        <small id="extension_error" class="form-text text-muted error"></small>
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
                                                    <table id="email_master_table" class="display">
                                                      <thead>
                                                        <tr>
                                                          <th>Sl No.</th>
                                                          <th>Mail Ext.</th>
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

@include('frames/footer');

<script>
      $(document).ready(function() {
        table = $('#email_master_table').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    'ajax': {
        url: "<?php echo url('/') ?>/masterData/getEmailsMaster",
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
            "data": "extension"
        },
        {
            "data": "display_status",
            "render": function(display_status, type, full, meta) {
                if (display_status == 1) return '<span class="badge badge-rounded badge-success">Active</span>';
                else return '<span class="badge badge-rounded badge-danger">Inactive</span>';

                return btn;
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


$('#email_master_table tbody').on('click', '.edit', function() {
    $("[id*='_error']").text('');
    var data = table.row($(this).parents('tr')).data();
         $("#hidextid").val(data.id);
		 $("#extension").val(data.extension.trim());
		 if(data.display_status==1)  $('#display_status').prop("checked", true); else  $('#display_status').prop("checked", false);
		 crude_btn_manage(2,1);
    });
/////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////

$('#email_master_table tbody').on('click', '.delete', function() {
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
				 url: "<?php echo url('/') ?>/masterData/deleteEmailExt",
				 data:ajaxval,
				 success: function(result){

                            if(result.status==1)
                            {
                                swal("Done", result.message, "success");

                                table.ajax.reload();
                            }
                            else{
                                sweetAlert("Oops...",result.message, "error");
                            }
                     },
                    });
        }
      })

      });
/////////////////////////////////////////////////////////////////////

});


    function submit_form(page,crude)
    {
        var url="";
        if(page==1)
        {
            url='{{route('saveEmailExt')}}';
            var form = $('#frm')[0];
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

                                if(page==1)
                                {
                                   table.ajax.reload();
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
    }


    function crude_btn_manage(type=1,page)
{
    if(page==1)
    {
        if(type==1) $('#crude').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\''+page+'\',\''+type+'\')" >Save</button>');
	 else if(type==2)  $('#crude').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\''+page+'\',\''+type+'\')" >Update</button>');

    }

}
</script>
