<div class="content-body" >
    <div class="container-fluid pt-2" >


        <div class="row">
            <div class="col-md-12">


                <div class="card">
                    <div class="card-body">
                        <form name="ipd-form" id="ipd-form" action="#" >
                            @csrf
                            <input type="hidden" id="ipd_id" name="ipd_id" value="{{$details->id}}">
                            <input type="hidden" id="patient_id" name="patient_id" value="{{$details->patient_id}}">
                            <input type="hidden" id="billing_type" name="billing_type" value="{{$billing_type}}">
                            <input type="hidden" name="specialist_id" value="{{$details->specialist_id}}">
                            <div class="form-row">
                                <div class="col-md-5">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Patient Name</label>
                                        </div>
                                        <div class="col-md-4">
                                            : <b>{{$details->name}}</b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Age</label>
                                        </div>
                                        <div class="col-md-4">
                                            : @if(isset($details->dob))
                                                {{ \Carbon\Carbon::parse($details->dob)->diff(\Carbon\Carbon::now())->format('%y') . ' yrs' }}
                                            @else
                                                {{ '' }}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Consultant</label>
                                        </div>
                                        <div class="col-md-4">
                                            : {{$details->specialist_name}}
                                        </div>
                                    </div>


                                </div>
                                <div class="col-md-5">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Total Outstanding</label>
                                        </div>
                                        <div class="col-md-4">
                                            : <b id='total-outstanding'></b>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p id="outstanding-btn"><button type="button" class="btn btn-sm btn-primary mt-1 btn-block" onclick="addTotalOutstanding()">Part Payment</button></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Date</label>
                                        </div>
                                        <div class="col-md-8">
                                            : <?= date('d-m-Y')?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Bill No</label>
                                        </div>
                                        <div class="col-md-8">
                                            : New
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Ward No</label>
                                        </div>
                                        <div class="col-md-8">
                                            : {{$details->ward_number}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Bed No</label>
                                        </div>
                                        <div class="col-md-8">
                                            : {{$details->bed_number}}
                                        </div>
                                    </div>


                                </div>
                            </div>

                            <div class="form-row">


                            </div>

                            <br>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <select id="group_search" name="group_search" class="form-control group_search" >
                                        <option value="">Select Group</option>
                                        {{LoadCombo("service_group_master","id","group_name",'','where display_status=1 AND is_deleted=0',"order by id asc");}}

                                    </select>
                                    <small id="group_search_error" class="form-text text-muted error"></small>
                                </div>
                                <div class="form-group col-md-4">
                                    <select id="item_search" class="form-control item_search">
                                        <option value="">Select Item</option>

                                    </select>

                                </div>
                            </div>
                            <div class="form-row">
                            </div>

                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                                    <th>Discount Percentage</th>
                                    <th>Discount Amount</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody id="append_data">
                                    <tr class="default_value"><td colspan="7" style="text-align: center;">No Items Selected</td></tr>
                                </tbody>
                            </table>




                            <hr>

                            <div class="details row">


                                <div class="form-group col-md-2">
                                    <label>Total Amt.</label>
                                    <input type="text" class="form-control" readonly id="total_amount" name="total_amount" placeholder="">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Payment Mode <span class="required">*</span></label>
                                    <select id="payment_mode_name" name="payment_mode_name" class="form-control payment_mode">
                                        <option  value="" selected>Choose...</option>
                                        {{LoadCombo("payment_mode_master","id","payment_mode_name",'','where display_status=1 AND is_deleted=0',"order by id desc");}}

                                    </select>
                                    <small id="payment_mode_name_error" class="form-text text-muted error"></small>
                                </div>

                                <div class="form-group col-md-2">
                                    <label>Dis in %</label>
                                    <input type="text" name="discount_in_percentage" id="discount_in_percentage" value="" class="form-control" placeholder="" onchange="calculateDiscountInPercentage()">
                                </div>

                                <div class="form-group col-md-2">
                                    <label>Total Bill Amt</label>
                                    <input type="text" name="totalbill_amount" id="totalbill_amount" value="" class="form-control" placeholder="" readonly>
                                </div>

                                <div class="form-group col-md-2">
                                    <label>Total Paid<span class="required">*</span></label>
                                    <input type="text" name="total_paid" id="total_paid" value="" class="form-control" placeholder="">
                                    <small id="total_paid_error" class="form-text text-muted error"></small>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Transaction ID</label>
                                    <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                                </div>

                            </div>
                            <br>
                            <a class="btn btn-primary inline-flex items-center px-4 py-2" style="color:white" onclick="saveIpdBillingData(1)" id="saveIpdBillingDatabtn">Save</a>
                        </form>
                    </div>



                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid pt-2" >
        <div class="row" >
            <div class="col-md-12">
                <div class="card">
                    {{-- <div class="card-header">
                        <h4 class="card-title">Fees Collection</h4>
                    </div> --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="ipd-billing-data" class="display" style="min-width: 845px">
                                <thead>
                                <tr>
                                    <th>Billing Date</th>
                                    <th>Admit Date</th>
                                    <th>Ipd code</th>
                                    <th>Bill No.</th>
                                    <th>Payment Mode</th>
                                    <th>Total Amt</th>
                                    <th>Dis Amt</th>
                                    <th>Net Amt</th>
                                    <th>Paid Amount</th>
                                    <th>Balance Amount</th>
                                    <th>Actions</th>

                                </tr>
                                </thead>
                                <tbody id="search_filter">




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
@include('modals/total_outstanding_modal',['title'=>'Part Payment','data'=>'dfsds'])

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="{{asset('/js/jquery-redirect.js')}}"></script>


<script>
    $(document).ready(function() {
        getIpdBillingData();
    });

    function getIpdBillingData() {
        var patientId=$('#patient_id').val();
        var ipdId=$('#ipd_id').val();
        var billingType=$('#billing_type').val();

        $("#ipd-billing-data").dataTable().fnDestroy()
        table_ipd_billing = $('#ipd-billing-data').DataTable({
            scrollY: 470,
            "order": [] ,
            "autoWidth": false,
            dom: 'lfrtip',
            'ajax': {
                url: "<?php echo url('/') ?>/get-ipd-billing-data",
                type: 'POST',
                "data": function(d) {
                    d.ipdId= ipdId;
                    d.patientId= patientId;
                    d.billingType= billingType;
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
                    "data": "admission_date",
                    "className": "text-right",
                    "render": function(data, type, full, meta) {
                        var date = new Date(data);
                        var month = date.getMonth() + 1;
                        return ('0' + date.getDate()).slice(-2) + "-" + (month.toString().length > 1 ? month : "0" + month) + "-" +   date.getFullYear();

                    },
                },
                {
                    "data": "ipd_id",
                    "className": "text-right",
                    "render": function(data, type, full, meta) {
                        var ipNo = data + 1;
                        var length = 3;
                        return (Array(length).join('0') + ipNo).slice(-length);

                    },
                },
                {
                    "data": "id",
                    "className": "text-right",
                    "render": function(data, type, full, meta) {
                        var billingNo = data + 1;
                        var length = 3;
                        return (Array(length).join('0') + billingNo).slice(-length);

                    },
                },
                {
                    "data": "patient_billing_mode_id",
                    "className": "text-right",
                    "render": function(patient_billing_mode_id, type, full, meta) {
                        if (patient_billing_mode_id == 1) return '<span>Cash</span>';
                        else if (patient_billing_mode_id == 2)return '<span>Card</span>';
                        else return '<span>Cheque</span>';
                    }
                },
                {
                    "data": "TotalAmount",
                    "className": "text-right",
                    "render": function(data, type, full, meta) {
                        return  parseFloat(data).toFixed(2);
                    },

                },
                {
                    "data": "Discamount",
                    "className": "text-right",
                    "render": function(data, type, full, meta) {
                        return  parseFloat(data).toFixed(2);
                    },
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
                    "data": "PatientId",
                    "className": "text-right",
                    "render": function(data, type, full, meta) {
                        return '<a href="javascript:void(0)" target="_blank" class="active_link ipd_pdf_billing"><i class="fa fa-print" aria-hidden="true"></i></a>';
                    }
                },
            ]
        });

    }



    var $i = 0;
    var serviceItems = [];
    function add_duplicate(service_item_id,name,code,amt)
    {
        if (jQuery.inArray(service_item_id, serviceItems) !== -1) {
            sweetAlert("Oops...", "Item Already added", "error");
        } else {
            serviceItems.push(service_item_id);
            if ($i == 0) {
                $(".default_value").remove();
            }
            $i++;
            var html="";
            html+="<tr>";
            html+=" <td><input type='text' class='form-control test_name' id='test_name_"+$i+"' name='test_name[]' placeholder='Name' value='"+name+"' readonly><input type='hidden' name='service_item_id[]' id='service_item_"+$i+"'  value='"+service_item_id+"' class='form-control service_item_id'></td>";
            html+="<td><input type='text' name='test_code[]' id='test_code_"+$i+"'  class='form-control test_code' placeholder='Code' value='"+code+"' readonly></td>";
            html+="<td><input type='text' name='quantity[]' id='quantity_"+$i+"' value='1' class='form-control quantity' placeholder='Quantity' onkeyup=calculateQuantityWithBasicAmount(this.value,'"+amt+"','"+$i+"')></td>";
            html+="<td><input type='text' name='test_amount[]' id='test_amount_"+$i+"'  class='form-control test_amount' placeholder='Amount' value='"+amt+"' readonly></td>";
            html+="<td><input type='text' name='discount_percentage[]' id='discount_percentage_"+$i+"' value='' class='form-control discount_percentage' onkeyup=calculateDiscountAmount(this.value,'"+$i+"') placeholder='Dsc %'></td>";
            html+="<td><input type='text' name='discount_amount[]' id='discount_amount_"+$i+"' value='' class='form-control discount_amount'  placeholder='Dsc Amt' readonly></td>";
            html+="<td><input type='text' name='amount[]' id='amount_"+$i+"' value='"+amt+"' class='amount form-control totalamt' placeholder='Amt' readonly></td>";
            html+="<td><i style='color:red' class='fa fa-trash' onclick=removeFiled(this);></i></td>";
            html+="</tr>"

            $('#append_data').prepend(html);
            $('#quantity_'+$i).focus();

            if(service_item_id){
                $('#service_item_'+$i).val(service_item_id);
                $('#test_name_'+$i).val(name);
            }
        }
    }

    $('#ipd-billing-data tbody').on('click', '.ipd_pdf_billing', function() {
        var data = $('#ipd-billing-data').DataTable().row($(this).parents('tr')).data();
        $.redirect('{{url("ipd-pdf-billing-data")}}', { _token: "{{ csrf_token() }}", patient_id: data.PatientId, ipd_id: data.ipd_id, billing_type: data.billing_type,  patient_billing_id: data.id}, 'POST', '_blank');
    });




    $('#item_search').on('change', function() {

        var selectText = $( "#item_search option:selected" ).text();
        var selectItemId = $( "#item_search option:selected" ).val();
        var selectCode = $( "#item_search option:selected" ).attr('itemcode');
        var selectAmt = $( "#item_search option:selected" ).attr('itemamt');


        add_duplicate(selectItemId,selectText,selectCode,selectAmt);

        getSumValue();
        calculateDiscountInPercentage();



    });
    $('#group_search').on('change', function (e) {
        getItemList();
    });



    function removeFiled(thiss){
        var serviceItemId = $(thiss).parent().parent().find("input[type=hidden]").val();
        const index = serviceItems.indexOf(serviceItemId);
        if (index > -1) {
            serviceItems.splice(index, 1);
        }
        $(thiss).parent().parent().remove();
        getSumValue();
        calculateDiscountInPercentage();
    }

    function getSumValue(){
        var sum_value=0;
        $('.totalamt').each(function(){
            sum_value += +$(this).val();
            // console.log(sum_value);

            $('#total_amount').val(sum_value);
            $('#totalbill_amount').val(sum_value);
        })
    }

    function getItemList(){
        $('#item_search').empty();
        var groupId=$('#group_search').val();
        $.ajax({
            type: "POST",
            url: "<?php echo url('/') ?>/getServiceItemList",
            data: {groupId:groupId},
            success: function(datas) {


                var opt="<option value=''>Select</option>";
                $.each(datas, function (key, val) {

                    opt+="<option value='"+val.id+"' itemamt='"+val.item_amount+"' itemcode='"+val.item_code+"'>"+val.item_name+"</option>";
                });

                $('#item_search').append(opt).selectpicker('refresh');
            },
        });
    }


</script>

<style>
    .details .form-group{
        margin-bottom: unset!important;
    }
    input[type="checkbox"]:after{
        background: unset;
    }
    .select2-container .select2-selection--single{
        height: 39px;
        padding: 5px;

    }
    .table td {
        padding: 3px 9px;
    }
</style>
;
<script>

    //Basic Amount
    function calculateQuantityWithBasicAmount(quantityId,amt,count)
    {
        var basicAmount= quantityId * amt;
        $('#test_amount_'+count).val(basicAmount);

        var percentage = $('#discount_percentage_'+count).val();
        calculateDiscountAmount(percentage, count)
    }

    //Discount Amount
    function calculateDiscountAmount(discount,count)
    {
        var  Amount= $('#test_amount_'+count).val();
        var discountPercentage=  (Amount * discount) / 100;
        $('#discount_amount_'+count).val(discountPercentage);
        var totalAmount= Amount - discountPercentage;
        $('#amount_'+count).val(totalAmount);
        getSumValue();
    }

    //Discount In Percentage
    function calculateDiscountInPercentage()
    {
        var  totalAmount= $('#total_amount').val();
        var  discount= $('#discount_in_percentage').val();
        var discountPercentage=  (totalAmount * discount) / 100;


        var totalBillAmount= totalAmount - discountPercentage;
        $('#totalbill_amount').val(totalBillAmount.toFixed(2));
    }

</script>

<script>
    function saveIpdBillingData(crude=1)
    {
        $("[id*='_error']").text('');
        var form = $('#ipd-form')[0];
        var formData = new FormData(form);
        formData.append('crude', crude);
        url='{{route('save-ipd-billing-data')}}';

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(result){

                if (result.status == 1) {
                    // swal("Done", result.message, "success");

                    var billingId = result.dataArray.patient_billing_id;
                    var patientId = result.dataArray.patient_id;
                    var ipdId = result.dataArray.ipd_id;
                    var billingType = result.dataArray.billing_type;
                    $i =0;
                    serviceItems = [];
                    var form = $('#ipd-form')[0];
                    document.getElementById("ipd-form").reset();
                    $('.service_item_id, .test_name, .test_code, .quantity, .test_amount, .discount_percentage, .discount_amount, .amount, .payment_mode, .item_search, .group_search').val('').selectpicker('refresh');
                    $("#append_data").html('');
                    $('#append_data').prepend('<tr class="default_value"><td colspan="7" style="text-align: center;">No Items Selected</td></tr>');
                    getOutStandingData();
                    swal({
                        title: "Do you want to print receipt?",
                        type: "success",
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes'
                    }).then((swalStatus) => {
                        if (swalStatus.value) {
                            // var url = '{{url("pdf-billing-data")}}?patient_id=' + result.dataArray.patient_id + '&patient_billing_id=' + result.dataArray.patient_billing_id;
                            // window.open(url, '_blank');
                            $.redirect('{{url("ipd-pdf-billing-data")}}', { _token: "{{ csrf_token() }}", patient_id: patientId, ipd_id: ipdId, billing_type: billingType,  patient_billing_id: billingId}, 'POST', '_blank');
                        }
                    });
                    $('#ipd-billing-data').DataTable().ajax.reload();

                }
                else {
                    sweetAlert("Oops...", result.message, "error");
                }
                $('#ipd-billing-data').DataTable().ajax.reload();

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
<script>
    $(document).ready(function(){
        getOutStandingData();
    });
    function getOutStandingData()
    {
        var patientId=$('#patient_id').val();
        $.ajax({
            url: "{{ route('get-outstanding-data') }}",
            type: 'POST',
            data: {patientId:patientId},
            success : function(result) {
                var jsondata = $.parseJSON(result);
                $('#total-outstanding').html('Rs. ' + parseFloat(jsondata).toFixed(2));
                $('#total_outstanding_amount').val(parseFloat(jsondata).toFixed(2));

                if (jsondata>0){
                    $("#outstanding-btn").css("display", "block")
                } else {
                    $("#outstanding-btn").css("display", "none")
                }
            },
        });
    }

    function addTotalOutstanding(){
        var patientId=$('#patient_id').val();
        $('#total-outstanding-modal').modal();
        getOutStandingData();

        $("#total_outstanding_data").dataTable().fnDestroy()
        table_ipd_outStanding = $('#total_outstanding_data').DataTable({
            "order": [] ,
            "autoWidth": false,
            dom: 'lfrtip',
            'ajax': {
                url: "<?php echo url('/') ?>/get-total-outstanding-data",
                type: 'POST',
                data: {patientId:patientId},
            },
            "columns": [{
                "data": "created_at",
                "className": "text-right",
                "render": function(data, type, full, meta) {
                    var date = new Date(data);
                    var month = date.getMonth() + 1;
                    return date.getDate() + "-" + (month.toString().length > 1 ? month : "0" + month) + "-" +   date.getFullYear();
                }
            },
                {
                    "data": "id",
                    "className": "text-right",
                    "render": function(data, type, full, meta) {
                        var receiptNo = data + 1;
                        var length = 3;
                        return "receipt-" +(Array(length).join('0') + receiptNo).slice(-length);

                    },
                },
                {
                    "data": "payment_mode_name",
                    "className": "text-right",
                },
                {
                    "data": "total_paid",
                    "className": "text-right",

                },
                {
                    "data": "id",
                    "className": "text-right",
                    "render": function(data, type, full, meta) {
                        return '<a href="javascript:void(0)" target="_blank" class="active_link pdf_outstanding_billing"><i class="fa fa-print" aria-hidden="true"></i></a>';
                    }
                },
            ]
        });
        $('#total_outstanding_data').DataTable().ajax.reload();
    }

    $('#total_outstanding_data tbody').on('click', '.pdf_outstanding_billing', function() {
        var data = $('#total_outstanding_data').DataTable().row($(this).parents('tr')).data();
        $.redirect('{{url("pdf-outstanding-data")}}', { _token: "{{ csrf_token() }}", patient_id: data.patient_id, patient_billing_payment_id: data.id}, 'POST', '_blank');
    });
</script>

