<style>
    table.dataTable
    {
        width: 99% !important;
    }
</style>
<div class="content-body">
  <div class="container-fluid pt-2">
    <div class="row" style="">
      <div class="col-md-12">


              <div id="patient-reference" class="tab-pane fade active show">

                <div class="row">
                  <div class="col-xl-4">
                    <div class="card">
                      <div class="card-body">

                        <div class=" mb-5">
                          <!-- <h5 class="text-primary d-inline">Today Highlights</h5><a href="javascript:void()" class="pull-right f-s-16">More</a> -->
                          <form action="#"  name="frm" id="frm" method="POST">
                            <input type="hidden" name="hidid" id="hidid">
                            <div class="form-group">
                                <label class="text-label"> User<span
                                class="required">*</span></label>
                                <select id="UserSelect" name="user" class="form-control" onchange="clearError(this.id)" required>
                                    <option  value="">Choose...</option>
                                    {{-- @foreach($user_data as $item)
                                    <option value="{{$item['userid']}}">{{$item['name']}}</option>
                                    @endforeach --}}
                                </select>
                                <small id="user_error" class="form-text text-muted error"></small>
                            </div>

                            <div class="form-group">
                              <label class="text-label"> Specialist Name</label>
                              <input type="text" name="specialist_name" id="specialist_name" onKeyPress="return blockSpecialChar(event)" maxlength="80" class="form-control" placeholder=""  readonly>
                              <small id="specialist_name_error" class="form-text text-muted error"></small>
                            </div>

                          <div class="form-group">
                              <label class="text-label">Specialist Email</label>
                              <input type="text" name="specialist_email" id="specialist_email" maxlength="80"
                                     class="form-control" placeholder=""  autocomplete="new-password" value="" onkeyup="clearError(this.id)" readonly>
                              <small id="specialist_email_error" class="form-text text-muted error"></small>
                          </div>

                          <div class="form-group">
                              <label class="text-label">Department<span
                                      class="required">*</span></label>
                              <select id="department" name="department" class="form-control">
                                  <option  value="" selected>Choose...</option>
                                  {{LoadCombo("departments","id","department_name",'',"where display_status=1  AND is_deleted=0","order by id desc");}}
                              </select>
                              <small id="department_error" class="form-text text-muted error"></small>
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
                          <table id="specialistTable" class="display">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th> Specialist Name</th>
                                <th> Specialist Email</th>
                                <th> User</th>
                                <th> Department</th>
                                <th>Display Status</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>


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

@include('frames/footer');
<script>




function submit_form(page,crude)
    {

        var url="";
        if(page==1)
        {
            url='{{route('saveSpecialist')}}';
            var form = $('#frm')[0];
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
                                window.location.reload();
                                // $('#UserSelect').val('').selectpicker('refresh');
                                // table.ajax.reload();
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
                         if ( result.status === 422 ) {
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


    $(document).ready(function() {

    table = $('#specialistTable').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    'ajax': {
        url: "<?php echo url('/') ?>/masterData/getSpecialist",
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
            "data": "specialist_name"
        },
        {
            "data": "email"
        },
        {
            "data": "name"
        },
        {
            "data": "department_name"
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
                // return '<div class="d-flex"><a href="#" class="delete btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
            }
        },
    ]
});
//////////////////////////////////////////////////////
$('#specialistTable tbody').on('click', '.edit', function() {
    $("[id*='_error']").text('');
    var data = table.row($(this).parents('tr')).data();
      // console.log(data);
    $("#hidid").val(data.id);
    //  $("#UserSelect").val(data.user_id);
    // $("#UserSelect").append("<option value='"+data.user_id+"'>"+data.name+"</option>").val(data.user_id).selectpicker('refresh');
    $("#specialist_name").val(data.specialist_name.trim());
    $("#department").val(data.department_id).change();
    $("#specialist_email").val(data.email.trim());
	if(data.display_status==1)  $('#display_status').prop("checked", true); else  $('#display_status').prop("checked", false);
	crude_btn_manage(2,1);

    getSpecialistUserDropdown(data.user_id,data.name);
 });
////////////////////////////////////////////////////////


$('#specialistTable tbody').on('click', '.delete', function() {
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
                url: "<?php echo url('/') ?>/masterData/deleteSpecialist",
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

            getSpecialistUserDropdown();
        }
    })

});

});

function crude_btn_manage(type=1,page)
{
    if(page==1)
    {
    if(type==1) $('#crude').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\''+page+'\',\''+type+'\')" >Save</button>');
    else if(type==2)  $('#crude').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\''+page+'\',\''+type+'\')" >Update</button>');

    }

}

function getSpecialistUserDropdown(user_id="",username=""){
  $("#UserSelect").empty();
  $.ajax({
      url: "{{ route('getSpecialistUserDropdown') }}",
      type: 'GET',
      data : {},
      success : function(response) {
        var opt="<option value=''>Select</option>";
        $.each(response, function (key, val) {

          opt+="<option value='"+val.userid+"' >"+val.name+"</option>";
        });

        if(user_id!=""){
          $("#UserSelect").append(opt).selectpicker('refresh');
          $("#UserSelect").append("<option value='"+user_id+"'>"+username+"</option>").val(user_id).selectpicker('refresh');

        }else{
          $("#UserSelect").append(opt).selectpicker('refresh');

        }


      }
    });

}
$(document).ready(function(){
  getSpecialistUserDropdown();
    $("#UserSelect").on("change",function() {
        $("#user_error").html("");
    });
    $("#department").on("change",function() {
        $("#department_error").html("");
    });
})
</script>


<script>
        var App = {
            initialize: function() {
                $('#UserSelect').on('change', App.showUserList);
            },
            showUserList : function() {
                var user_id = $('#UserSelect').val();

                $.ajax({
                    url: "{{ route('get-user-list') }}",
                    type: 'GET',
                    data : {
                        user_id : user_id,
                    },
                    success : function(response) {
                      $("#specialist_name").val('');
                      $("#specialist_email").val('');

                        $.each(response.data, function(key, value)
                        {
                            $("#specialist_name").val(value.name);
                            $("#specialist_email").val(value.email);
                        });
                    },
                });
            },
        };
        App.initialize();
    </script>
