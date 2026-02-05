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



                                            {{-- <div class="form-group">
                                                <label class="text-label"> User Type<span
                                                        class="required">*</span></label>
                                                        <select id="role_id" name="role_id" class="form-control">
                                                            <option selected>Choose...</option>
                                                            {{LoadCombo("user_roles","rid","role_name",'','where rid>1','order by rid')}}


                                                        </select>
                                            </div> --}}


                                            <div class="form-group">
                                                <label class="text-label"> Group Name<span
                                                        class="required">*</span></label>
                                                        <input type="hidden" name="user_group_id" id="user_group_id" value="<?php echo $group_id; ?>">
                                                <input type="text" name="group_name" id="group_name"
                                                    onKeyPress="return blockSpecialChar(event)" maxlength="80"
                                                    class="form-control" placeholder="" value="{{ old('group_name',isset($group_data) ? $group_data->group_name : '') }}"required>
                                                <small id="group_name_error"
                                                    class="form-text text-muted error"></small>
                                            </div>
                                            {{-- <div class="form-check">
                                                <input type="checkbox" name="display_status" id="display_status"
                                                    class="form-check-input" checked>
                                                <label class="form-check-label" for="displayStatus">Display
                                                    Status</label>
                                            </div> --}}

                                            <div id="crude">
                                                <?php
                                                        if($group_id>0)
                                                        {
                                                            ?>
                                                             <button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(1,2)">Update</button>

                                                            <?php
                                                        }
                                                        else{
                                                            ?>
                                                             <button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(1,1)">Save</button>
                                                            <?php
                                                        }
                                                    ?>

                                            </div>


                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3">
                            <div class="card">
                                <div class="card-body menubox">
                                    <h5>Menus</h5><hr>

                                    <?php
                                    	$ckid=1;
			   							$chkname="chkbp".$ckid;

                            foreach ($menus as $row) {
                                    $menu=$row->menu;
                                    $micon=$row->micon;
                                    $mid=$row->mid;
                                    $submenuflag=$row->submenuflag;
                              // get a feild for chcked or not
                              $checked=0;
                                if($group_id)
                                {
                                    $condition=array('user_group_id'=>$group_id,'menu_type'=>1,'menu_id'=>$mid);
                                      $checked=getAfeild("id","user_group_menus",$condition);
                                }

                                    ?>
                                    <div class="form-group">
                                        <label style="font-weight:800">
                                            <input type="checkbox" name="chkbp[]" id="<?php print $mid; ?>"
                                                value="<?php print $mid; ?>" class="flat-red"
                                                onClick="selectall(this.id)"
                                                <?php if($checked>0) echo "checked"; ?>>
                                                &nbsp;&nbsp;<?php print $menu; ?>

                                            <?php if($submenuflag==1){  ?> <i class="fa fa-caret-down" aria-hidden="true"></i>
                                            <?php } ?>
                                        </label>
                                    </div>
                                    <?php

                                    if($submenuflag==1)
                                    {
                                        $cksubid=1;
					                	$chksubname="chksubmenu_".$mid;
                                        foreach ($row->submenu as $rk) {
                                        $sid=$rk->sid;
                                        $submenu=$rk->submenu;
                                        $clasname="flat-red-".$mid;
                                            $sub_checked=0;
                                                    if($group_id)
                                                    {
                                                         $Sub_condition=array('user_group_id'=>$group_id,'menu_type'=>2,'menu_id'=>$sid);
                                                        $sub_checked=getAfeild("id","user_group_menus",$Sub_condition);
                                                    }

                                        ?>
                                        <div class="form-group" style="margin-left:20%;">
                                        <label  style="font-weight:400">
                                        <input onClick="checkmaster(this.id)" type="checkbox" <?php if($sub_checked>0) echo "checked"; ?>   name="chksubmenu[]" id="<?php print $chksubname; ?>" value="<?php print $sid; ?>"  class="<?php print $clasname; ?>"> <?php print $submenu; ?>
                                        </label>
                                        </div>
                                        <?php
                                        $cksubid++;
                                        }

                                    }

                                }
                              ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-5">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive pt-3">
                                        <table id="userGroupTable" class="display">
                                            <thead>
                                                <tr>
                                                    <th>Sl No.</th>
                                                    <th> Group Name</th>

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
            url = '{{ route('saveUserGroup') }}';
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
                    $('#group_name').removeAttr('value');
                    document.getElementById("frm").reset();
                    table.ajax.reload();
                    crude_btn_manage(1,1)

                } else if (result.status == 2) {
                    swal("Done", result.message, "success");
                    $('#group_name').val("");
                    document.getElementById("frm").reset();
                    if(page==1){
                        table.ajax.reload();
                    }
                    crude_btn_manage(1,1)
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

        table = $('#userGroupTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            'ajax': {
                url: "<?php echo url('/'); ?>/UserManagement/GetUserGroupMenus",
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
                    "data": "id",
                    "render": function(display_status, type, full, meta) {
                        return '<div class="d-flex"><a href="#" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a><a href="#" class="delete btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                    }
                },
            ]
        });
        //////////////////////////////////////////////////////
        $('#userGroupTable tbody').on('click', '.edit', function() {
            var data = table.row($(this).parents('tr')).data();
            window.location.href = '{{url("/UserManagement/userGroup")}}?id='+ btoa(data.group_id);
        });
        ////////////////////////////////////////////////////////


        $('#userGroupTable tbody').on('click', '.delete', function() {
            var data = table.row($(this).parents('tr')).data();
            let ajaxval = {
                id: data.group_id,
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
                        url: "<?php echo url('/'); ?>/UserManagement/deleteUserGroup",
                        data: ajaxval,
                        success: function(result) {

                            if (result.status == 1) {
                                swal("Done", result.message, "success");
                                $('#group_name').removeAttr('value');
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

    });

    function crude_btn_manage(type = 1, page) {
        if (page == 1) {
            if (type == 1) $('#crude').html(
                '<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\'' +
                page + '\',\'' + type + '\')" >Save</button>');
            else if (type == 2) $('#crude').html(
                '<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\'' +
                page + '\',\'' + type + '\')" >Update</button>');

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
</script>
