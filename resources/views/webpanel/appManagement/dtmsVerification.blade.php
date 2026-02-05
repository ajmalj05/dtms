<div class="content-body">
    <div class="container-fluid pt-2">
        <div class="row" style="">
            <div class="col-md-12">

                <div class="profile-tab pb-2">
                    <div class="custom-tab-1">
                        <ul class="nav nav-tabs">
                            <li class="nav-item"><a href="#BP_status" data-toggle="tab" class="nav-link active show">BP
                                    Status</a>
                            </li>
                            <li class="nav-item"><a href="#smbg_status" data-toggle="tab" class="nav-link">SMBG
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content pt-3">
                            <div id="BP_status" class="tab-pane fade active show">

                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="table-responsive pt-3">
                                                    <table id="bp_status_table" class="display">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl No.</th>
                                                                <th>Date</th>
                                                                <th>Patient Name</th>
                                                                <th>Gender</th>
                                                                <th>UHID No</th>
                                                                <th>Phone Number</th>
                                                                <th>bpd</th>
                                                                <th>bps</th>
                                                                <th>pulse</th>
                                                                <th>Medication & Dosage</th>
                                                                <th>Verify</th>

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

                            <div id="smbg_status" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="table-responsive pt-3">
                                                    <table id="smbg_status_table" class="display">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl No.</th>
                                                                <th>Date</th>

                                                                <th>Patient Name</th>
                                                                <th>Gender</th>
                                                                <th>UHID No</th>
                                                                <th>Phone Number</th>
                                                                <th>Blood Glucose</th>
                                                                <th>Medicine & Dose</th>
                                                                <!-- <th>Dosage & </th> -->
                                                                <th>SMBG Test</th>

                                                                <th>Verify</th>

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
<link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
<link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
<script src='date_picker/js/bootstrap-datepicker.min.js' type='text/javascript'></script>
<script src="./vendor/select2/js/select2.full.min.js"></script>
<script src="./js/plugins-init/select2-init.js"></script>
<script>
//------------------------------Data table for bp status-----------------//
$(document).ready(function() {
    table = $('#bp_status_table').DataTable({
        'ajax': {
            type: 'POST',
            url: "{{route('getbpStatusVerification')}}",
            "data": function(d) {}
        },
        "columns": [{
                "data": "id",
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                "data": "created_date",
                "render": function(data, type, row, meta) {
                    if (type === 'display' || type === 'filter') {
                        var date = new Date(data);
                        var formattedDate = date.toLocaleDateString();
                        var dateParts = formattedDate.split('/');
                        var dateObject = new Date(dateParts[2], dateParts[0] - 1, dateParts[1]);
                        var convertedDate = dateObject.toLocaleDateString('en-GB')
                        return convertedDate;
                    }
                    return data;
                }
            },
            {
                "data": "name"
            },
            {
                "data": "gender"
            },
            {
                "data": "uhidno"
            },
            {
                "data": "mobile_number"
            },
            {
                "data": "bpd"
            },
            {
                "data": "bps"
            },
            {
                "data": "pulse"
            },
            {
                "data": "medicine"
            },
            {
                "data": null,
                "render": function(display_status, type, full, meta) {
                    return '<button id="verifyButtonbp" class="btn btn-success btn-sm">Verify</button>';
                }

            },
        ]
    });

    // ------------------------------------Data Table for SMBG----------------//

    table1 = $('#smbg_status_table').DataTable({
        'ajax': {
            type: 'POST',
            url: "{{route('getsmbgStatusVerification')}}",
            "data": function(d) {}
        },
        "columns": [{
                "data": "autoId",
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                "data": "created_at",
                "render": function(data, type, row, meta) {
                    if (type === 'display' || type === 'filter') {
                        var date = new Date(data);
                        var formattedDate = date.toLocaleDateString();
                        var dateParts = formattedDate.split('/');
                        var dateObject = new Date(dateParts[2], dateParts[0] - 1, dateParts[1]);
                        var convertedDate = dateObject.toLocaleDateString('en-GB')
                        return convertedDate;
                    }
                    return data;
                }
            },
            {
                "data": "name"
            },
            {
                "data": "gender"
            },
            {
                "data": "uhidno"
            },
            {
                "data": "mobile_number"
            },
            {
                "data": "blood_glucose"
            },
            {
                "data": "medicine"
            },
            // {
            //     "data": "dosage"
            // },
            {
                "data": "TestName"
            },
            {
                "data": null,
                "render": function(display_status, type, full, meta) {
                    return '<button id="verifyButtonsmbg" class="btn btn-success btn-sm">Verify</button>';
                }
            },
        ]
    });
    $("#smbg_status_table").css("width", "100%");
});


///-----------bp status verification------------///
$('#bp_status_table tbody').on('click', '#verifyButtonbp', function() {
    var data = table.row($(this).parents('tr')).data();
    let patient_id = data.patientId;
    let ajaxval = {
        reading_date: data.reading_date,
        patient_id: data.patientId,
        bpd: data.bpd,
        bps: data.bps,
        pulse: data.pulse,
        reading_time: data.reading_time,
        id: data.id,
        medicine: data.medicine,
    };

    swal({
        title: 'Patient verification',
        text: "Do you want to verify the data !",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, Verify it!'
    }).then((result) => {
        if (result.value) {


            $.ajax({
                type: "POST",
                url: "{{route('bpStatusVerification')}}",
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


//------------smbg verification-------------------//
$('#smbg_status_table tbody').on('click', '#verifyButtonsmbg', function() {
    var data = table1.row($(this).parents('tr')).data();
    console.log(data);
    let ajaxval = {
        reading_date: data.reading_date,
        patient_id: data.patientid,
        blood_glucose: data.blood_glucose,
        reading_time: data.reading_time,
        id: data.autoId,
        TestId: data.TestId,
        autoId: data.autoId,
        medicine: data.medicine,
        dosage: data.dosage
    };

    swal({
        title: 'Patient verification',
        text: "Do you want to verify the data !",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, Verify it!'
    }).then((result) => {
        if (result.value) {


            $.ajax({
                type: "POST",
                url: "{{route('smbgStatusVerification')}}",
                data: ajaxval,
                success: function(result) {
                    if (result.status == 1) {
                        swal("Done", result.message, "success");

                        table1.ajax.reload();

                    } else {
                        sweetAlert("Oops...", result.message, "error");
                    }
                },
            });
        }
    })

});
</script>