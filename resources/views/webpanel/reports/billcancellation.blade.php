<div class="content-body">
    <div class="container-fluid">


        <form name="frm" id="frm" action="" class="card card-body" method="POST">
            @csrf
            <div class="row">
                <div class="col-xl-2 col-md-6">
                    <div class="form-group">
                        <label class="text-label">From Date</label>
                        <input type="text" name="from_date" id="from_date" class="form-control"
                            value="<?= date('d-m-Y') ?>" placeholder="">
                        <small id="name_error" class="form-text text-muted error"></small>
                    </div>
                </div>


                <div class="col-xl-2 col-md-3">
                    <div class="form-group">
                        <label class="text-label">To Date </label>


                        <input type="text" name="to_date" id="to_date" value="<?= date('d-m-Y') ?>"
                            class="form-control" placeholder="">
                    </div>
                </div>
                <div class="col-xl-2 col-md-3 d-flex align-items-center">
                    <div>
                        {{-- search-btn --}}
                        <button class="btn btn-primary  btn-sm search-btn" type="button">Search</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-sm-12">
                <div class="card card-sm ">
                    <div class="card-body">
                        <table id="report" class="display">
                            <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Patiet Name</th>
                                    <th>Uhid No</th>
                                    <th>Bill Date</th>
                                    <th>Bill No</th>
                                    <th>Bill Amount</th>
                                    <th>Remarks</th>
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
@include('frames/footer');
<link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
<script src='date_picker/js/bootstrap-datepicker.min.js' type='text/javascript'></script>
<script src="./vendor/select2/js/select2.full.min.js"></script>
<script src="./js/plugins-init/select2-init.js"></script>

<script>

$(document).ready(function() {


$('.search-btn').click(function(e){
    e.preventDefault();
    table.ajax.reload();
})

$('#to_date').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
});
$('#from_date').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy'
});
});

    $(document).ready(function() {
        table = $('#report').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            'ajax': {
                url: '<?php echo url('/') ?>/generateCancellBillReport',
                type: 'POST',
                "data": function(d) {
                    d.bill_from_date=$("#from_date").val();
                    d.bill_to_date=$("#to_date").val();
                }
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
                    "data": "uhidno"
                },
                {
                    "data": "created_at",
                    "className": "text-right",
                    "render": function(data, type, full, meta) {
                        var date = new Date(data);
                        var month = date.getMonth() + 1;
                        return ('0' + date.getDate()).slice(-2) + "-" + (month.toString().length > 1 ? month : "0" + month) + "-" +   date.getFullYear();

                    },
                },
                {
                    "data": "PatientLabNo"
                },
                {
                    "data": "TotalAmount"
                },
                {
                    "data": "bill_remarks"
                }
            ]
        });
    })
</script>
