<style>
table.dataTable {
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
                            <li class="nav-item"><a href="#category_master" data-toggle="tab"
                                    class="nav-link active show">Category Master</a>
                            </li>
                            <li class="nav-item"><a href="#sub_category" data-toggle="tab" class="nav-link">Division
                                </a>
                            </li>
                            <li class="nav-item"><a href="#patient_type" data-toggle="tab" class="nav-link">Patient
                                    Type</a>
                            </li>
                            {{-- <li class="nav-item"><a href="#patient_reference" data-toggle="tab" class="nav-link">Patient Reference</a> --}}
                            </li>
                            <li class="nav-item"><a href="#sub_division" data-toggle="tab" class="nav-link">Sub
                                    Division</a>
                            </li>
                        </ul>
                        <div class="tab-content pt-3">
                            <div id="category_master" class="tab-pane fade active show">

                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="card">
                                            <div class="card-body">

                                                <div class=" mb-5">
                                                    <!-- <h5 class="text-primary d-inline">Today Highlights</h5><a href="javascript:void()" class="pull-right f-s-16">More</a> -->
                                                    <form action="#" name="frm" id="frm">

                                                        <input type="hidden" name="hidcatid" id="hidcatid">
                                                        <div class="form-group">
                                                            <label class="text-label">Category <span
                                                                    class="required">*</span></label>
                                                            <input type="text" name="category_name" id="category_name"
                                                                onKeyPress="return Onlycharecters(event)" maxlength="50"
                                                                class="form-control" placeholder="" required>
                                                            <small id="category_name_error"
                                                                class="form-text text-muted error"></small>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="display_status"
                                                                id="display_status" class="form-check-input" checked>
                                                            <label class="form-check-label" for="displayStatus">Display
                                                                Status</label>
                                                        </div>
                                                        <div id="crude">
                                                            <button type="button"
                                                                class="btn btn-sm btn-primary my-2 pull-right"
                                                                onclick="submit_form(1,1)">Save</button>
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
                                                    <table id="category_master_table" class="display">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl No.</th>
                                                                <th>Category</th>
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

                            <div id="sub_category" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class=" mb-5">
                                                    <form action="#" name="frm1" id="frm1" method="POST">
                                                        <input type="hidden" name="hidsub_catid" id="hidsub_catid">
                                                        <div class="form-group">
                                                            <label class="text-label">Division<span
                                                                    class="required">*</span></label>
                                                            <input type="text" name="division_name" id="division_name"
                                                                onKeyPress="return Onlycharecters(event)" maxlength="50"
                                                                class="form-control" placeholder="" value="" required>
                                                            <small id="division_name_error"
                                                                class="form-text text-muted error"></small>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="sub_display_status"
                                                                id="sub_display_status" class="form-check-input" checked
                                                                id="displayStatus">
                                                            <label class="form-check-label" for="displayStatus">Display
                                                                Status</label>
                                                        </div>
                                                        <div id="sub_crude">
                                                            <button type="button"
                                                                class="btn btn-sm btn-primary my-2 pull-right"
                                                                onclick="submit_form(2,1)">Save</button>
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
                                                    <table id="sub_category_master_table" class="display">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl No.</th>
                                                                <th>Division</th>
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
                            <div id="patient_type" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class=" mb-5">
                                                    <form action="" name="frm2" id="frm2" method="POST">
                                                        <input type="hidden" name="hidpatid" id="hidpatid">
                                                        <div class="form-group">
                                                            <label class="text-label">Patient Type <span
                                                                    class="required">*</span></label>
                                                            <input type="text" name="patient_type_name"
                                                                id="patient_type_name"
                                                                onKeyPress="return Onlycharecters(event)" maxlength="50"
                                                                class="form-control" placeholder="" required>
                                                            <small id="patient_type_name_error"
                                                                class="form-text text-muted error"></small>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="pt_display_status"
                                                                id="pt_display_status" class="form-check-input" checked>
                                                            <label class="form-check-label" for="displayStatus">Display
                                                                Status</label>
                                                        </div>
                                                        <div id="pt_crude">
                                                            <button type="button"
                                                                class="btn btn-sm btn-primary my-2 pull-right"
                                                                onclick="submit_form(3,1)">Save</button>
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
                                                    <table id="patient_type_table" class="display">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl No.</th>
                                                                <th>Patient Type</th>
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
                            <div id="patient_reference" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class=" mb-5">
                                                    <form action="" name="frm3" id="frm3" method="POST">
                                                        <input type="hidden" name="hidrefid" id="hidrefid">
                                                        <div class="form-group">
                                                            <label class="text-label">Patient Reference <span
                                                                    class="required">*</span></label>
                                                            <input type="text" name="patient_ref_name"
                                                                id="patient_ref_name"
                                                                onKeyPress="return Onlycharecters(event)" maxlength="50"
                                                                class="form-control" placeholder="" required>
                                                            <small id="patient_ref_name_error"
                                                                class="form-text text-muted error"></small>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="ref_display_status"
                                                                id="ref_display_status" class="form-check-input"
                                                                checked>
                                                            <label class="form-check-label" for="displayStatus">Display
                                                                Status</label>
                                                        </div>
                                                        <div id="ref_crude">
                                                            <button type="button"
                                                                class="btn btn-sm btn-primary my-2 pull-right"
                                                                onclick="submit_form(4,1)">Save</button>
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
                                                    <table id="patient_ref_table" class="display">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl No.</th>
                                                                <th>Patient Reference</th>
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
                            <div id="sub_division" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class=" mb-5">
                                                    <form action="#" name="frm4" id="frm4" method="POST">
                                                        <input type="hidden" name="hidsub_divid" id="hidsub_divid">
                                                        <div class="form-group">
                                                            <label class="text-label">Sub Division<span
                                                                    class="required">*</span></label>
                                                            <input type="text" name="sub_division_name"
                                                                id="sub_division_name"
                                                                onKeyPress="return Onlycharecters(event)" maxlength="50"
                                                                class="form-control" placeholder="" value="" required>
                                                            <small id="sub_division_name_error"
                                                                class="form-text text-muted error"></small>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox" name="div_display_status"
                                                                id="div_display_status" class="form-check-input" checked
                                                                id="displayStatus">
                                                            <label class="form-check-label" for="displayStatus">Display
                                                                Status</label>
                                                        </div>
                                                        <div id="div_crude">
                                                            <button type="button"
                                                                class="btn btn-sm btn-primary my-2 pull-right"
                                                                onclick="submit_form(5,1)">Save</button>
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
                                                    <table id="sub_division_master_table" class="display">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl No.</th>
                                                                <th>Sub Division</th>
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
// var table = $('#category_master_table').DataTable();

//  var table3 = $('#religion_master_table').DataTable();
//   tabl2 = $('#sub_category_master_table').DataTable();



$(document).ready(function() {
    /////////////////////////////////////////////////////////////////
    /// CATEGORY MASTER
    table = $('#category_master_table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        'ajax': {
            url: "<?php echo url('/') ?>/masterData/getCategory",
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
                "data": "category_name"
            },
            {
                "data": "display_status",
                "render": function(display_status, type, full, meta) {
                    if (display_status == 1)
                    return '<span class="badge badge-rounded badge-success">Active</span>';
                    else
                return '<span class="badge badge-rounded badge-danger">Inactive</span>';

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

    // sub category master
    /////////////////////////////////////////////////////////////////////////////

    table2 = $('#sub_category_master_table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        'ajax': {
            url: "<?php echo url('/') ?>/masterData/getSubCategory",
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
                "data": "sub_category_name"
            },
            {
                "data": "display_status",
                "render": function(display_status, type, full, meta) {
                    if (display_status == 1)
                    return '<span class="badge badge-rounded badge-success">Active</span>';
                    else
                return '<span class="badge badge-rounded badge-danger">Inactive</span>';

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

});
//Patient Type Master
////////////////////////////////////////////////////////////////////

table3 = $('#patient_type_table').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    'ajax': {
        url: "<?php echo url('/') ?>/masterData/getPatientType",
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
            "data": "patient_type_name"
        },
        {
            "data": "display_status",
            "render": function(display_status, type, full, meta) {
                if (display_status == 1)
                return '<span class="badge badge-rounded badge-success">Active</span>';
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

// table4 = $('#patient_ref_table').DataTable({
//     dom: 'Bfrtip',
//     buttons: [
//         'copy', 'csv', 'excel', 'pdf', 'print'
//     ],
//     'ajax': {
//         url: "<?php echo url('/') ?>/masterData/getPatientRef",
//         type: 'POST',
//         "data": function(d) {}
//     },
//     "columns": [{
//             "data": "id",
//             render: function(data, type, row, meta) {
//                 return meta.row + meta.settings._iDisplayStart + 1;
//             }
//         },
//         {
//             "data": "patient_ref_name"
//         },
//         {
//             "data": "display_status",
//             "render": function(display_status, type, full, meta) {
//                 if (display_status == 1) return '<span class="badge badge-rounded badge-success">Active</span>';
//                 else return '<span class="badge badge-rounded badge-danger">Inactive</span>';

//                 return btn;
//             }
//         },
//         {
//             "data": "id",
//             "render": function(display_status, type, full, meta) {
//                 return '<div class="d-flex"><a href="#" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a><a href="#" class="delete btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
//             }
//         },
//     ]
// });

table5 = $('#sub_division_master_table').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    'ajax': {
        url: "<?php echo url('/') ?>/masterData/getSubDiv",
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
            "data": "sub_division_name"
        },
        {
            "data": "display_status",
            "render": function(display_status, type, full, meta) {
                if (display_status == 1)
                return '<span class="badge badge-rounded badge-success">Active</span>';
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

///////////////////////////////////////////////////////////////////////////////////////////////
$('#sub_category_master_table tbody').on('click', '.edit', function() {
    $("[id*='_error']").text('');
    var data = table2.row($(this).parents('tr')).data();
    $("#hidsub_catid").val(data.id);
    $("#division_name").val(data.sub_category_name.trim());
    if (data.display_status == 1) $('#sub_display_status').prop("checked", true);
    else $('#sub_display_status').prop("checked", false);
    crude_btn_manage(2, 2);
});
////////////////////////////////////////////////////////////////

$('#category_master_table tbody').on('click', '.edit', function() {
    $("[id*='_error']").text('');
    var data = table.row($(this).parents('tr')).data();
    $("#hidcatid").val(data.id);
    $("#category_name").val(data.category_name.trim());
    if (data.display_status == 1) $('#display_status').prop("checked", true);
    else $('#display_status').prop("checked", false);
    crude_btn_manage(2, 1);
});
/////////////////////////////////////////////////////////////////
$('#patient_type_table tbody').on('click', '.edit', function() {
    $("[id*='_error']").text('');
    var data = table3.row($(this).parents('tr')).data();
    $("#hidpatid").val(data.id);
    $("#patient_type_name").val(data.patient_type_name.trim());
    if (data.display_status == 1) $('#pt_display_status').prop("checked", true);
    else $('#pt_display_status').prop("checked", false);
    crude_btn_manage(2, 3);
});

$('#patient_ref_table tbody').on('click', '.edit', function() {
    $("[id*='_error']").text('');
    var data = table4.row($(this).parents('tr')).data();
    $("#hidrefid").val(data.id);
    $("#patient_ref_name").val(data.patient_ref_name.trim());
    if (data.display_status == 1) $('#ref_display_status').prop("checked", true);
    else $('#ref_display_status').prop("checked", false);
    crude_btn_manage(2, 4);
});
$('#sub_division_master_table tbody').on('click', '.edit', function() {
    $("[id*='_error']").text('');
    var data = table5.row($(this).parents('tr')).data();
    $("#hidsub_divid").val(data.id);
    $("#sub_division_name").val(data.sub_division_name.trim());
    if (data.display_status == 1) $('#div_display_status').prop("checked", true);
    else $('#div_display_status').prop("checked", false);
    crude_btn_manage(2, 5);
});

/////////////////////////////////////////////////////////////////////////////////
$('#sub_category_master_table tbody').on('click', '.delete', function() {
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
                url: "<?php echo url('/') ?>/masterData/deleteSubCategory",
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

////////////////////////////////////////////////////////////////////////////////

$('#category_master_table tbody').on('click', '.delete', function() {
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
                url: "<?php echo url('/') ?>/masterData/deleteCategory",
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
/////////////////////////////////////////////////////////////////////
$('#patient_type_table tbody').on('click', '.delete', function() {
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
                url: "<?php echo url('/') ?>/masterData/deletePatientType",
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
$('#patient_ref_table tbody').on('click', '.delete', function() {
    var data = table4.row($(this).parents('tr')).data();

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
                url: "<?php echo url('/') ?>/masterData/deletePatientRef",
                data: ajaxval,
                success: function(result) {

                    if (result.status == 1) {
                        swal("Done", result.message, "success");

                        table4.ajax.reload();
                    } else {
                        sweetAlert("Oops...", result.message, "error");
                    }
                },
            });
        }
    })

});
$('#sub_division_master_table tbody').on('click', '.delete', function() {
    var data = table5.row($(this).parents('tr')).data();

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
                url: "<?php echo url('/') ?>/masterData/deleteSubDiv",
                data: ajaxval,
                success: function(result) {

                    if (result.status == 1) {
                        swal("Done", result.message, "success");

                        table5.ajax.reload();
                    } else {
                        sweetAlert("Oops...", result.message, "error");
                    }
                },
            });
        }
    })

});

////////////////////////////////////////////////

function submit_form(page, crude) {

    var url = "";
    if (page == 1) {
        url = '{{route('saveCategory')}}';
        var form = $('#frm')[0];
    } else if (page == 2) {
        url = '{{route('saveSubCategory')}}';
        var form = $('#frm1')[0];
    } else if (page == 3) {
        url = '{{route('savePatientType')}}';
        var form = $('#frm2')[0];
    } else if (page == 4) {
        url = '{{route('savePatientRef')}}';
        var form = $('#frm3')[0];
    } else if (page == 5) {
        url = '{{route('saveSubDiv')}}';
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
        success: function(result) {

            if (result.status == 1) {
                swal("Done", result.message, "success");
                document.getElementById("frm").reset();
                document.getElementById("frm1").reset();
                document.getElementById("frm3").reset();
                document.getElementById("frm2").reset();
                document.getElementById("frm4").reset();
                if (page == 1) {
                    table.ajax.reload();
                } else if (page == 2) {
                    table2.ajax.reload();
                } else if (page == 3) {
                    table3.ajax.reload();
                } else if (page == 5) {
                    // table4.ajax.reload();
                    table5.ajax.reload();
                }

                crude_btn_manage(1, page)

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

function crude_btn_manage(type = 1, page) {
    if (page == 1) {
        if (type == 1) $('#crude').html(
            '<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\'' + page +
            '\',\'' + type + '\')" >Save</button>');
        else if (type == 2) $('#crude').html(
            '<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\'' +
            page + '\',\'' + type + '\')" >Update</button>');

    } else if (page == 2) {
        if (type == 1) $('#sub_crude').html(
            '<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\'' + page +
            '\',\'' + type + '\')" >Save</button>');
        else if (type == 2) $('#sub_crude').html(
            '<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\'' +
            page + '\',\'' + type + '\')" >Update</button>');

    } else if (page == 3) {
        if (type == 1) $('#pt_crude').html(
            '<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\'' + page +
            '\',\'' + type + '\')" >Save</button>');
        else if (type == 2) $('#pt_crude').html(
            '<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\'' +
            page + '\',\'' + type + '\')" >Update</button>');

    } else if (page == 4) {
        if (type == 1) $('#ref_crude').html(
            '<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\'' + page +
            '\',\'' + type + '\')" >Save</button>');
        else if (type == 2) $('#ref_crude').html(
            '<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\'' +
            page + '\',\'' + type + '\')" >Update</button>');

    } else if (page == 5) {
        if (type == 1) $('#div_crude').html(
            '<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\'' + page +
            '\',\'' + type + '\')" >Save</button>');
        else if (type == 2) $('#div_crude').html(
            '<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\'' +
            page + '\',\'' + type + '\')" >Update</button>');

    }
}
</script>
