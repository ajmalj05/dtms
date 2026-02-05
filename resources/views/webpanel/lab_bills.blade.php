<div class="content-body">
    <div class="container-fluid">

        <form method="POST">
            <div  class="card card-body">
            <div class="row">

                <div class="col-xl-2 col-md-6">
                    <div class="form-group">
                        <label class="text-label">From Date</label>
                        <input type="text" name="from_date" id="from_date" class="form-control" value="<?= date('d-m-Y')?>"   placeholder="" >
                        <small id="name_error" class="form-text text-muted error"></small>
                    </div>
                </div>


                <div class="col-xl-2 col-md-3">
                    <div class="form-group">
                        <label class="text-label">To Date </label>


                        <input type="text" name="to_date" id="to_date"  value="<?=date('d-m-Y');?>" class="form-control" placeholder="" >
                        </div>
                  </div>
                  <div class="col-xl-2 col-md-3 d-flex align-items-center">
                    <div>
                        <button class="btn btn-primary search-btn btn-sm" type="button" onclick="reloadData()">Search</button>
                    </div>
                  </div>
            </div>
            </div>
        </form>


        <div class="row" >
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example4" class="display" style="min-width: 845px">
                                <thead>
                                <tr>
                                    <th>Billing Date</th>
                                    <th>Visit Date</th>
                                    <th>Bill No.</th>
                                    <th>Patient Name</th>
                                    <th>UhidNo</th>
                                    <th>Net Amt</th>
                                    <th>Paid Amount</th>
                                    <th>Balance Amount</th>
                                    <th>Result Status</th>
                                    <th>Actions</th>

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

@include('frames/footer');


<link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
<script src='date_picker/js/bootstrap-datepicker.min.js' type='text/javascript'></script>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="{{asset('/js/jquery-redirect.js')}}"></script>

<script>
 $(document).ready(function() {

$('#to_date').datepicker({
autoclose: true,
// endDate: '+0d',
format: 'dd-mm-yyyy'
});
$('#from_date').datepicker({
autoclose: true,
// endDate: '+0d',
format: 'dd-mm-yyyy'
});

});


$(document).ready(function() {
        getBillingData();
        $("#group_search").select2();
});

function getBillingData()
{
    $("#example4").dataTable().fnDestroy()
    table = $('#example4').DataTable({
            "autoWidth": false,
            dom: 'lfrtip',
            'ajax': {
            url: "<?php echo url('/') ?>/get-billing-data",
            type: 'POST',
            "data": function(d) {
                d._token= "{{ csrf_token() }}";
                d.billingType= 3;
                d.bill_from_date=$("#from_date").val();
                d.bill_to_date=$("#to_date").val();
                d.avoidCancelled=1;

              //  d.uhid= $('#uhid').val();
            }
          },
          "columns": [
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
                    "data": "visit_date",
                    "className": "text-right",
                    "render": function(data, type, full, meta) {
                        var date = new Date(data);
                        var month = date.getMonth() + 1;
                        return ('0' + date.getDate()).slice(-2) + "-" + (month.toString().length > 1 ? month : "0" + month) + "-" +   date.getFullYear();

                    },
                },
                {
                    "data": "PatientLabNo",

                },
                {
                    "data":"name"
                },
                {
                    "data":"uhidno"
                },

                {
                    "data": "NetAmount",
                    "className": "text-right",
                    "render": function(data, type, full, meta) {
                        return  parseFloat(data).toFixed(2);
                    },
                },
                {
                    "data": "total_paid",
                    "className": "text-right",
                    "render": function(data, type, full, meta) {
                        return  parseFloat(data).toFixed(2);
                    },
                },
                {
                    "data": "balance_amount",
                    "className": "text-right",
                    "render": function(data, type, full, meta) {
                        return  parseFloat(data).toFixed(2);
                    },
                },
                {
                    "data":"id"
                },
                {
                    "data": "PatientId",
                    "className": "text-right",
                    "render": function(data, type, full, meta) {
                        return '<a href="javascript:void(0)"  class="btn btn-primary shadow btn-xs sharp mr-1 result_entry"  title="Result Entry"><i class="fa fa fa-file" aria-hidden="true"></i></a><a href="javascript:void(0)"  class="active_link pdf_billing"><i class="fa fa-print" aria-hidden="true"></i></a>';
                    }
                },
            ]
        });
}

function reloadData()
{
    //$('#example4').DataTable().ajax.reload();
    getBillingData();
}



/////////BILL PRINT>..................................////


    $('#example4 tbody').on('click', '.pdf_billing', function() {
        var data = table.row($(this).parents('tr')).data();
        $.redirect('{{url("pdf-billing-data")}}', { _token: "{{ csrf_token() }}", patient_id: data.PatientId, billing_type: data.billing_type,  patient_billing_id: data.id}, 'POST', '_blank');
    });

    $('#example4 tbody').on('click', '.result_entry', function() {
        var data = table.row($(this).parents('tr')).data();
        $.redirect('{{url("manageResult")}}', { _token: "{{ csrf_token() }}", patient_id: data.PatientId, billing_type: data.billing_type,  patient_billing_id: data.id}, 'POST');
    });




</script>
