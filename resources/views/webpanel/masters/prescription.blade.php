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
                            <li class="nav-item"><a href="#tablet-type" data-toggle="tab" class="nav-link active show">Tablet
                                    Type</a>
                            </li>
                            <li class="nav-item"><a href="#medicines-tab" data-toggle="tab" class="nav-link">Medicines</a>
                            </li>
                            <li class="nav-item"><a href="#vaccination-tab" data-toggle="tab" class="nav-link">Vaccination</a>
                            </li>

                        </ul>
                        <div class="tab-content pt-3">
                            <div id="tablet-type" class="tab-pane fade active show">

                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="card">
                                            <div class="card-body">

                                                <div class=" mb-5">
                                                    <form action="#"  name="frm" id="frm" action="" method="POST">
                                                        <input type="hidden" name="hid_tablet_type_id" id="hid_tablet_type_id">
                                                        <div class="form-group">
                                                            <label class="text-label"> Tablet Type Name<span class="required">*</span></label>
                                                            <input type="text" name="tablet_type_name" id="tablet_type_name" onKeyPress="return blockSpecialChar(event)" maxlength="80" class="form-control" placeholder="" required>
                                                            <small id="tablet_type_name_error" class="form-text text-muted error"></small>
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
                                                    <table id="TabletType" class="display">
                                                        <thead>
                                                        <tr>
                                                            <th>Sl No.</th>
                                                            <th>Tablet Type</th>
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
                            <div id="medicines-tab" class="tab-pane fade">

                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="card">
                                            <div class="card-body">

                                                <div class=" mb-5">
                                                    <form name="frm1" id="frm1" method="POST" action="">
                                                        <input type="hidden" name="hid_medicine_id" id="hid_medicine_id">
                                                        <div class="form-group">
                                                            <label class="text-label">Select Tablet Type<span class="required">*</span></label>
                                                            <select class="form-control" name="tablet_type" id="tablet_type">
                                                                <option value="">Select</option>

                                                            </select>
                                                            <small id="tablet_type_error" class="form-text text-muted error"></small>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="text-label">Medicine Name<span class="required">*</span></label>
                                                            <input type="text" name="medicine_name" id="medicine_name"  maxlength="50" class="form-control" placeholder="" required>
                                                            <small id="medicine_name_error" class="form-text text-muted error"></small>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-label">Notes</label>
                                                            <input type="text" name="notes" id="notes" onKeyPress="return blockSpecialChar(event)" maxlength="50" class="form-control" placeholder="" required>
                                                            <small id="notes_error" class="form-text text-muted error"></small>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-label">Generic Name</label>
                                                            <textarea name="generic_name" id="generic_name" class="form-control" placeholder=""></textarea>

                                                        </div>

                                                        <div class="form-check">
                                                            <input type="checkbox" name="display_status" id="display_status_medicine" class="form-check-input" checked >
                                                            <label class="form-check-label" for="displayStatus">Display Status</label>
                                                        </div>

                                                        <div id="crud_medicine">
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
                                                    <table id="Medicine" class="display">
                                                        <thead>
                                                        <tr>
                                                            <th>Sl No.</th>
                                                            <th>Tablet Type</th>
                                                            <th>Medicine Name</th>
                                                            <th>Generic Name</th>
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
                            <div id="vaccination-tab" class="tab-pane fade">

                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="card">
                                            <div class="card-body">

                                                <div class=" mb-5">
                                                    <form name="frm2" id="frm2" method="POST" action="">
                                                        <input type="hidden" name="hid_vaccination_id" id="hid_vaccination_id">
                                                        <div class="form-group">
                                                            <label class="text-label">Vaccination Name<span class="required">*</span></label>
                                                            <input type="text" name="vaccination_name" id="vaccination_name" onKeyPress="return blockSpecialChar(event)" maxlength="50" class="form-control" placeholder="" required>
                                                            <small id="vaccination_name_error" class="form-text text-muted error"></small>
                                                        </div>

                                                        <div class="form-check">
                                                            <input type="checkbox" name="display_status_vaccination" id="display_status_vaccination" class="form-check-input" checked >
                                                            <label class="form-check-label" for="displayStatus">Display Status</label>
                                                        </div>

                                                        <div id="crud_vaccination">
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
                                                    <table id="Vaccination" class="display">
                                                        <thead>
                                                        <tr>
                                                            <th>Sl No.</th>
                                                            <th>Vaccination Name</th>
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

            if(currTabTarget=="#tablet-type"){
                InitializeDT1();
            }else  if(currTabTarget=="#medicines-tab"){
                InitializeDT2();
            }else if(currTabTarget=="#vaccination-tab"){

                InitializeDT3();
            }
        });


        function InitializeDT1(){
            table = $('#TabletType').DataTable({
                "destroy": true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                'ajax': {
                    url: "<?php echo url('/') ?>/getPrescriptionMaster",
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
                        "data": "tablet_type_name"
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

        function InitializeDT2(){


            table2 = $('#Medicine').DataTable({
                "destroy": true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                'ajax': {
                    url: "<?php echo url('/') ?>/getPrescriptionMedicines",
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
                        "data": "tablet_type_name"
                    },
                    {
                        "data": "medicine_name"
                    },
                    {
                        "data": "generic_name"
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
                            return '<div class="d-flex"><a href="#" class="edit-medicine btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a><a href="#" class="delete btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                        }
                    },
                ]
            });
        }
        function InitializeDT3(){

            table3= $('#Vaccination').DataTable({
                "destroy": true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                'ajax': {
                    url: "<?php echo url('/') ?>/getPrescriptionVaccination",
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
                        "data": "vaccination_name"
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
                            return '<div class="d-flex"><a href="#" class="edit-vaccination btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a><a href="#" class="delete btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
                        }
                    },
                ]
            });
        }

        //end of dt


        $('#TabletType tbody').on('click', '.edit', function() {


            $("[id*='_error']").text('');

            var data = table.row($(this).parents('tr')).data();
            $("#hid_tablet_type_id").val(data.id);
            $("#tablet_type_name").val(data.tablet_type_name.trim());
            if(data.display_status==1)  $('#display_status').prop("checked", true); else  $('#display_status').prop("checked", false);
            crude_btn_manage(2,1);
        });


        $('#Medicine tbody').on('click', '.edit-medicine', function() {
            $("[id*='_error']").text('');
            var data = table2.row($(this).parents('tr')).data();
            $("#hid_medicine_id").val(data.id);
            $('#tablet_type').val(data.tablet_type_id).change();
            $('#medicine_name').val(data.medicine_name);
            $("#generic_name").val(data.generic_name);
            $("#notes").val(data.notes);
            // $("#dose").val(data.dose.trim());
            if(data.display_status==1)  $('#display_status_medicine').prop("checked", true); else  $('#display_status_medicine').prop("checked", false);
            crude_btn_manage(2,2);
        });

        $('#Vaccination tbody').on('click', '.edit-vaccination', function() {
            $("[id*='_error']").text('');
            var data = table3.row($(this).parents('tr')).data();
            $("#hid_vaccination_id").val(data.id);
            $('#vaccination_name').val(data.vaccination_name);
            if(data.display_status==1)  $('#display_status_vaccination').prop("checked", true); else  $('#display_status_vaccination').prop("checked", false);
            crude_btn_manage(2,3);
        });

        $('#TabletType tbody').on('click', '.delete', function() {
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
                        url: "<?php echo url('/') ?>/deletePrescriptionMaster",
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

        $('#Medicine tbody').on('click', '.delete', function() {
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
                        url: "<?php echo url('/') ?>/deletePrescriptionMedicines",
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

        $('#Vaccination tbody').on('click', '.delete', function() {
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
                        url: "<?php echo url('/') ?>/deletePrescriptionVaccination",
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

        ////////////////////////

    });


    function submit_form(page,crude)
    {
        var url="";
        if(page==1)
        {
            url='{{route('savePrescriptionMaster')}}';
            var form = $('#frm')[0];
        }
        else  if(page==2)
        {
            url='{{route('savePrescriptionMedicines')}}';
            var form = $('#frm1')[0];
        }
        else  if(page==3)
        {
            url='{{route('savePrescriptionVaccination')}}';
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
                    if(page==1){
                        table.ajax.reload();
                    }else if(page==2){
                        $('#tablet_type').val("").selectpicker('refresh');
                        table2.ajax.reload();
                    }else if(page==3){
                        table3.ajax.reload();
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
        if(page==1)
        {
            if(type==1) $('#crude').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\''+page+'\',\''+type+'\')" >Save</button>');
            else if(type==2)  $('#crude').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\''+page+'\',\''+type+'\')" >Update</button>');
        }
        else if(page==2)
        {
            if(type==1) $('#crud_medicine').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\''+page+'\',\''+type+'\')" >Save</button>');
            else if(type==2)  $('#crud_medicine').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\''+page+'\',\''+type+'\')" >Update</button>');

        }
        else if(page==3)
        {
            if(type==1) $('#crud_vaccination').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\''+page+'\',\''+type+'\')" >Save</button>');
            else if(type==2)  $('#crud_vaccination').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\''+page+'\',\''+type+'\')" >Update</button>');

        }
    }

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href") // activated tab
        $('#tablet_type').empty();
        if(target=='#medicines-tab'){

            _token="{{csrf_token()}}";

            $.ajax({
                type: "GET",
                url: "{{route('getTabletTypesListOptions')}}",
                data: {_token:_token},
                processData: false,
                contentType: false,
                success: function(result){


                    $('#tablet_type').append(result).selectpicker('refresh');;
                }
            });


        }
//   alert(target);
    });

</script>
