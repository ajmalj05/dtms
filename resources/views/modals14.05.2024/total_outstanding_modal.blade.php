<script src="{{asset('/js/jquery.cookie.js')}}"></script>
<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }
</style>
<div id="total-outstanding-modal" class="modal fade " role="dialog">
    <div class="modal-dialog modal-lg">
        <form name="outstanding-form" id="outstanding-form" action="#" >
            <div class="modal-content">
                <div class="modal-header">
                    <div class="card-title">
                        <p>{{$title}}</p>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="container-fluid pt-2">
                        <input type="hidden" name="patient_id" id="patient_id" value="{{$details->patient_id}}">
                            <div class="row">
                                <div class="col-xl-12 col-xxl-12 col-lg-12 col-sm-12">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-xl-4">
                                                <div class="card card-sm">
                                                    <div class="card-header">
                                                        <h4 class="card-title">
                                                            Add New - Part Payment
                                                        </h4>
                                                    </div>
                                                    <div class="card-body">

                                                        <div class=" mb-5">
                                                            <div class="form-group ">
                                                                <label class="text-label">Balance Amount</label>
                                                                <input type="text" name="total_outstanding_amount" id='total_outstanding_amount' value="" class="form-control" placeholder="" readonly>
                                                                <small id="total_outstanding_amount_error" class="form-text text-muted error"></small>
                                                            </div>
                                                            <div class="form-group ">
                                                                <label class="text-label">Reference Number<span class="required">*</span></label>
                                                                <input type="text" name="reference_number" id="reference_number" value="" class="form-control" placeholder="">
                                                                <small id="reference_number_error" class="form-text text-muted error"></small>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Payment Mode <span class="required">*</span></label>
                                                                <select id="payment_mode" name="payment_mode" class="form-control">
                                                                    <option  value="" selected>Choose...</option>
                                                                    {{LoadCombo("payment_mode_master","id","payment_mode_name",'','where display_status=1 AND is_deleted=0',"order by id desc");}}

                                                                </select>
                                                                <small id="payment_mode_error" class="form-text text-muted error"></small>
                                                            </div>
                                                            <div class="form-group ">
                                                                <label class="text-label">Total Paid<span class="required">*</span></label>
                                                                <input type="text" name="amount_paid" id="amount_paid" value="" class="form-control" placeholder="" onKeyPress="return onlyNumbers(event)">
                                                                <small id="amount_paid_error" class="form-text text-muted error"></small>
                                                            </div>

                                                            <div class="d-flex flex-wrap align-content-center h-100">
                                                                <button type="button" class="btn btn-sm btn-primary mt-1" onclick="saveOutStandingData(1)">Save</button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-8">
                                                <div class="card card-sm">
                                                    <div class="card-header">
                                                        <h4 class="card-title">Part Payment Listing</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="table-responsive pt-3">
                                                            <table id="total_outstanding_data" class="display table" style="width:100%">
                                                                <thead>
                                                                <tr>
                                                                    <th>Date</th>
                                                                    <th>Transaction Number</th>
                                                                    <th>Payment Mode</th>
                                                                    <th>Total Paid</th>
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
    </form>
</div>
</div>



<style>

    .modal-lg {
        max-width: 80% !important;
    }
    .search-btn{
        padding:0.375rem 0.75rem!important;
    }
</style>
<script src="{{asset('/js/jquery-redirect.js')}}"></script>
<script>
    $(document).ready(function(){
        $("#reference_number").keyup(function() {
            $("#reference_number_error").html("");
        });
        $("#payment_mode").on("change",function() {
            $("#payment_mode_error").html("");
        });
        $("#amount_paid").keyup(function() {
            $("#amount_paid_error").html("");
        });
    });
</script>
<script>

    function saveOutStandingData(crude=1)
    {
        $("[id*='_error']").text('');
        var form = $('#outstanding-form')[0];
        var formData = new FormData(form);
        formData.append('crude', crude);
        url='{{route('save-outstanding-data')}}';

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(result){
                // console.log(result)

                if (result.status == 1) {
                    $('#total-outstanding').html(result.total_balance);
                    $('#example4').DataTable().ajax.reload();
                    var form = $('#outstanding-form')[0];
                    document.getElementById("outstanding-form").reset();
                    $('#total-outstanding-modal').modal('toggle');
                    $('#payment_mode').val('').selectpicker('refresh');
                    $('#amount_paid').val('');
                    $('#reference_number').val('');
                    // swal("Done", result.message, "success");
                    var billingPaymentId = result.out_standing_data.patient_billing_payment_id;
                    var patientId = result.out_standing_data.patient_id;

                    swal({
                        title: "Do you want to print receipt?",
                        type: "success",
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes'
                    }).then((swalStatus) => {
                        if (swalStatus.value) {
                            // var url = '{{url("pdf-outstanding-data")}}?patient_id=' + result.out_standing_data.patient_id + '&patient_billing_payment_id=' + result.out_standing_data.patient_billing_payment_id +'&patient_total_outstanding_amount=' + result.total_balance;
                            // window.open(url, '_blank');
                            $.redirect('{{url("pdf-outstanding-data")}}', { _token: "{{ csrf_token() }}", patient_id: patientId, patient_billing_payment_id: billingPaymentId}, 'POST', '_blank');
                        }
                    });


                }
                else if (result.status == 2) {
                    swal("Done", result.message, "success");
                    // location.reload();
                    document.getElementById("outstanding-form").reset();
                    $('#total-outstanding-modal').modal('toggle');
                    $('#payment_mode').val('').selectpicker('refresh');
                    $('#amount_paid').val('');
                    $('#reference_number').val('');

                }
                else {
                    $('#payment_mode').val('').selectpicker('refresh');
                    $('#amount_paid').val('');
                    $('#reference_number').val('');
                    sweetAlert("Oops...", result.message, "error");
                }


            },
            error: function(result,jqXHR, textStatus, errorThrown){
                if( result.status === 422 ) {
                    result=result.responseJSON;
                    var error=result.errors;
                    $.each(error, function (key, val) {
                        $("#" + key + "_error").text(val[0]);
                    });
                }

            }
        });
    }

</script>
