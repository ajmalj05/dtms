<style>
    table.dataTable {
        width: 99% !important;
    }
    .menubox
    {
        max-height: 650px;
        overflow: scroll;
    }

</style>
<form action="#" name="frm" id="frm" method="POST">
<div class="content-body">

    <div class="modal fade" id="basicModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Group Mapping</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="hid_user_id" id="hid_user_id" value="">
                    <div class="form-group">
                        <label class="text-label"> Select Branch<span class="required">*</span></label>
                        <select id="branch_id" name="branch_id" class="form-control" onchange="loadGroup(this.value)">
                            <option  value="">Choose...</option>
                            {{LoadCombo("branch_master","branch_id","branch_name",'','','order by branch_id')}}
                        </select>
                    </div>

                    <div class="form-group" id="ghtml">
                        <label class="text-label"> Select Group<span class="required">*</span></label>
                        <select id="group_id" name="group_id" class="form-control"></select>
                    </div>




                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveUsersGroup()">Save</button>
                </div>
                <div class="modal-body">
                    <h5>Mapped Groups</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Branch</th>
                                <th>Group</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="added_data">

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>


    <div class="container-fluid pt-2">
        <div class="row" style="">
            <div class="col-md-12">


                <div id="patient-reference" class="tab-pane fade active show">

                    <div class="row">
                        <div class="col-xl-3">
                            <div class="card">
                                <div class="card-body">

                                    <div class=" mb-5">
                                        <!-- <h5 class="text-primary d-inline">Today Highlights</h5><a href="javascript:void()" class="pull-right f-s-16">More</a> -->

                                            <input type="hidden" name="hidid" id="hidid" value="">

                                            <div class="form-group">
                                                <label class="text-label"> User Type<span
                                                        class="required">*</span></label>
                                                        <select id="role_id" name="role_id" class="form-control" onchange="clearError(this.id)">
                                                            <option  value="">Choose...</option>
                                                            {{LoadCombo("user_roles","rid","role_name",isset($user_data)?$user_data->role_id:'','where rid>1','order by rid')}}


                                                        </select>

                                                        <small id="role_id_error" class="form-text text-muted error"></small>
                                            </div>



                                            <div class="form-group">
                                                <label class="text-label">Name<span
                                                        class="required">*</span></label>
                                                <input type="text" name="name" id="name"
                                                    onKeyPress="return blockSpecialChar(event)" maxlength="80"
                                                    class="form-control" value="{{ old('name',isset($user_data) ? $user_data->name : '') }}" placeholder="" required onkeyup="clearError(this.id)">
                                                    <small id="name_error" class="form-text text-muted error"></small>
                                            </div>


                                            <div class="form-group">
                                                <label class="text-label">Email<span
                                                        class="required">*</span></label>
                                                <input type="text" name="email" id="email" maxlength="80"
                                                    class="form-control" placeholder="" required autocomplete="new-password" value="{{ old('email',isset($user_data) ? $user_data->email : '') }}" onkeyup="clearError(this.id)">
                                                    <small id="email_error" class="form-text text-muted error"></small>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-label">Password<span
                                                        class="required pwd">*</span></label>
                                                <input type="password" name="password" id="password" maxlength="80"
                                                    class="form-control" placeholder="" required autocomplete="new-password" value="{{ old('password',isset($user_data) ? $user_data->password : '') }}" onkeyup="clearError(this.id)">
                                                    <small id="password_error" class="form-text text-muted error"></small>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-label">Confirm password<span
                                                        class="required pwd">*</span></label>
                                                <input type="password" name="password_confirmation" id="password_confirmation"
                                                     maxlength="80" onkeyup="clearError(this.id)" value="{{ old('password_confirmation',isset($user_data) ? $user_data->password : '') }}"
                                                    class="form-control" placeholder="" required>
                                                <small id="password_confirmation_error" class="form-text text-muted error"></small>
                                            </div>



                                            <div class="form-check">
                                                <input type="checkbox" name="display_status" id="display_status"
                                                    class="form-check-input" checked>
                                                <label class="form-check-label" for="displayStatus">Display
                                                    Status</label>
                                            </div>

                                            <div id="crude">
                                                <button type="button" class="btn btn-sm btn-primary my-2 pull-right" onclick="submit_form(1,1)">Save</button>
                                            </div>


                                    </div>

                                </div>
                            </div>
                        </div>



                        <div class="col-xl-7">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive pt-3">
                                        <table id="userTable" class="display">
                                            <thead>
                                                <tr>
                                                    <th>Sl No.</th>
                                                    <th>  Name</th>
                                                        <th>Email</th>
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

</form>
</div>

@include('frames/footer');
<script>
    function submit_form(page, crude) {

        var url = "";
        if (page == 1) {
            url = '{{ route('saveNewUser') }}';
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
            success: function(result) {

                if (result.status == 1) {
                    swal("Done", result.message, "success");
                    document.getElementById("frm").reset();
                    table.ajax.reload();
                   // window.location.href = '{{url("/UserManagement/createUser")}}';
                   crude_btn_manage(1,1);

                } else if (result.status == 2) {
                    sweetAlert("Oops...", result.message, "error");
                } else {
                    sweetAlert("Oops...", result.message, "error");
                }
            },
            error: function(result, jqXHR, textStatus, errorThrown) {
                if (result.status === 422) {
                    result = result.responseJSON;
                    var error = result.errors;
                    $.each(error, function(key, val) {
                        console.log(key);
                        $("#" + key + "_error").text(val[0]);
                    });
                }

            }
        });
    }


    $(document).ready(function() {

        table = $('#userTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            'ajax': {
                url: "<?php echo url('/'); ?>/UserManagement/getUsers",
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
                    "data": "name"
                },
                {
                    "data": "email"
                },
                {
                    "data": "active_status",
                    "render": function(active_status, type, full, meta) {
                        if (active_status == 1) return '<span class="badge badge-rounded badge-success">Active</span>';
                        else return '<span class="badge badge-rounded badge-danger">Inactive</span>';
                    }
                },

                {
                    "data": "id",
                    "render": function(display_status, type, full, meta) {
                        return '<div class="d-flex"><a href="#" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a><a href="#" class="delete btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a> &nbsp;<a href="#" class="addgroup btn btn-warning shadow btn-xs sharp"><i class="fa fa-users"></i></a></div>'
                    }
                },
            ]
        });
        //////////////////////////////////////////////////////
        $('#userTable tbody').on('click', '.edit', function() {
            var data = table.row($(this).parents('tr')).data();
            console.log(data);
            $("#role_id").val(data.role_id);
            $('#role_id').trigger('change');
            $("#name").val(data.name);
            $('#name').trigger('change');
            $("#email").val(data.email);
            $('#email').trigger('change');
            $("#hidid").val(data.id);
            if(data.active_status==1)  $('#display_status').prop("checked", true); else  $('#display_status').prop("checked", false);
            //window.location.href = '{{url("/UserManagement/createUser")}}?id='+ data.id;
            crude_btn_manage(2,1);

        });

        $('#userTable tbody').on('click', '.addgroup', function() {
            var data = table.row($(this).parents('tr')).data();
            mapgroup(data.id);
        });
        ////////////////////////////////////////////////////////


        $('#userTable tbody').on('click', '.delete', function() {
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
                        url: "<?php echo url('/'); ?>/UserManagement/deleteUser",
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

    });

    function crude_btn_manage(type = 1, page) {
        if (page == 1) {
            if (type == 1)
            { $('#crude').html(
                '<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\'' + page + '\',\'' + type + '\')" >Save</button>');
                $(".pwd").show();
            }
            else if (type == 2)
            {
                $('#crude').html(
                '<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\'' +page + '\',\'' + type + '\')" >Update</button>');
                $(".pwd").hide();
            }

        }

    }



    function selectall(mid)
	{

	 if(document.getElementById(mid).checked == true)
	 {

	  $(".flat-red-"+mid).prop("checked", "true");

	 }
	 else
	 {
	$('.flat-red-'+mid).prop('checked', false);
	 }
	}

    function checkmaster(submid)
{
//alert(submid);
 var res = submid.split("_");
 var mid=res[1];

 var nos=$('input:checkbox.flat-red-'+mid+':checked').length;

 if(nos>0)
  {
  document.getElementById(mid).checked=true;
  }
  else
  {
  document.getElementById(mid).checked=false;
  }


}

function mapgroup(userid)
{
   $("#hid_user_id").val(userid);

   // get mapped data


   $("#basicModal").modal('show');
   getmappeddata(userid);
}

function loadGroup(branchid)
{
    url = '{{ route('getGroupByBranch') }}';
    $.ajax({
            type: "POST",
            url: url,
            data: {'branchId':branchid},
            success: function(result) {


                $('#group_id').append('<option value="">Hello...</option>');
//append('<option value="">Hello...</option>');



                html="";
                html+='<label class="text-label"> Select Group<span class="required">*</span></label> '+
                        '<select id="group_id" name="group_id" class="form-control"><option  value="">Choose...</option></select>' ;
                        $("#ghtml").html(html);

                    result = jQuery.parseJSON(result);

                    for (var i = 0; i < result.length; i++) {
                     var object = result[i];
                     $('#group_id').append('<option value="' + object.group_id + '">' + object.group_name + '</option>');
                }


            }
        });
}

function saveUsersGroup()
{

    var branch_id=$("#branch_id").val();
    var groupId=$("#group_id").val();
    var userId=$("#hid_user_id").val();
    url = '{{ route('mapuserGroup') }}';
    if(groupId>0 && branch_id>0)
        {
    $.ajax({
            type: "POST",
            url: url,
            data: {'branchId':branch_id,'groupId':groupId,'userId':userId},
            success: function(result) {
                $('#group_id,#branch_id').val('').selectpicker('refresh');

                if(result==1)
                    {
                        getmappeddata(userId);
                        swal("Done", "Group and Branch  allocated successfully", "success");

                        // alert("Group and Branch  allocated successfully");
                    }
                    else if(result==3)
                    {
                        sweetAlert("Oops...", "User already have a group in selected branch", "error");

                        // alert("User already have a group in selected branch");
                    }
            }
        });
    }
    else{
        sweetAlert("Oops...", "Please enter all mandatory coloumns", "error");

        // alert("Please enter all mandatory coloumns");
        }
}

function getmappeddata(userId)
{
    url = '{{ route('getMappedGroup') }}';
    $.ajax({
            type: "POST",
            url: url,
            data: {'userId':userId},
            success: function(result) {
                $("#added_data").html(result);
            }
        });

}

function deleteGroup(mapid)
{
    // if(confirm("Are you sure to delete this group"))
    // {
        url = '{{ route('deletemappedGroup') }}';
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
                    data: {'id':mapid},
                    success: function(result) {
                        if(result==1)
                        {
                            $("#added_data").html('');

                            swal("Done", "Data deleted successfully", "success");

                            // alert("Data deleted successfully");
                        }
                        else{
                            sweetAlert("Oops...", "Failed to delete", "error");

                            // alert("Failed to delete");
                        }
                    }
                });
            }
        })
    // }
}
</script>
