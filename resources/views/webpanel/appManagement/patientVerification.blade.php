
<div class="content-body">
<div class="container-fluid pt-2">
<div class="row" >
    <div class="col-md-12">


            <div id="patient-reference" class="tab-pane fade active show">

                <div class="row">

                <div class="col-xl-12">
                    <div class="card">
                    <div class="card-body">
                        <div class="table-responsive pt-3">
                        <table id="specialistTable" class="display">
                            <thead>
                            <tr>
                                <th>Sl No.</th>
                                <th> First Name</th>
                                <th> Last Name</th>
                                <th> Mobile Number</th>
                                <th>Email</th>
                                <th>Created On</th>
                                <th>Is verified</th>
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
@include('frames/footer');


<script>


    $(document).ready(function() {

    table = $('#specialistTable').DataTable({

    'ajax': {
        url: "<?php echo url('/') ?>/appManagement/getPatientVerification",
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
            "data": "first_name"
        },
        {
            "data": "last_name"
        },
        {
            "data": "mobile_number"
        },

        {
            "data": "email"
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
    "data": null,
    "render": function(data, type, row, meta) {
    if (type === 'display') {
        return '<button id="verifyButton" class="btn btn-success">Verify</button>';
    }
    return '';
    }
},
    ]
});
//////////////////////////////////////////////////////


$('#specialistTable tbody').on('click', '#verifyButton', function(){
    var data = table.row($(this).parents('tr')).data();
    let ajaxval = {
        id: data.id,
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
                url: "<?php echo url('/') ?>/appManagement/patientVerificationApp",
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
////////////////////////////////////////////////////////
});
</script>
