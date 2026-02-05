<style>
    .bs-placeholder
    {
        display: none;
    }
</style>
<div class="content-body" >
    <div class="container-fluid pt-2" >
        <h2><?php
             if($ip_id==0) echo "<h3>OP Billing</h3>";  else if($ip_id>0) echo "<h3>IP Billing</h3>";
            ?></h2>

        <div class="row">
            <div class="col-md-12">


                <div class="card">
                    <div class="card-body">
                        <form name="billing-form" id="billing-form" action="#" >
                            @csrf
                            <input type="hidden" id="visit_id" name="visit_id" value="{{$visit_id_main}}">
                            <input type="hidden" id="patient_id" name="patient_id" value="{{$details->patient_id}}">
                            <input type="hidden" id="billing_type" name="billing_type" value="{{$billing_type}}">
                            <input type="hidden" name="specialist_id" value="{{$details->specialist_id}}">
                            <input type="hidden" name="id_data" id="id_data" value="{{$ip_id}}">
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
                                <div class="col-md-4">
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
                                <div class="col-md-3">
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
                                        <div class="col-md-8" >  <span id="billno"> </span>  </div>

                                    </div>
                                    <?php
                                        if($ip_id>0)
                                        {
                                        ?>
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
                                        <?php
                                        }
                                    ?>


                                </div>
                            </div>

                            <div class="form-row">
                                {{-- <div class="form-group col-md-2">
                                    <label>Date.</label>
                                    <input type="date" class="form-control" id="from_date" name="from_date" placeholder="From Date" value="<?= date('Y-m-d',strtotime("-1 days"))?>">
                                </div>

                                <div class="form-group col-md-2">
                                    <label>Bill No.</label>
                                    <input type="text" class="form-control" id="mobile_number" name="mobile_number" value="T134321">
                                </div> --}}

                                {{-- <div class="form-group col-md-2">
                                    <label>Category</label>
                                    <select id="patient_type" name="patient_type" class="form-control">
                                        <option  value="" selected>Choose...</option>
                                        {{LoadCombo("patient_type_master","id","patient_type_name",'','where display_status=1 AND is_deleted=0',"order by id desc");}}

                                    </select>
                                </div> --}}
                                {{-- <div class="form-group col-md-2">
                                    <label>Payment</label>
                                    <select id="gender"  name="gender" class="form-control">
                                        <option  value=""  selected>Choose...</option>
                                         <option value="m">Normal</option>

                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Doctor Name</label>
                                    <select id="gender"  name="gender" class="form-control">
                                        <option  value=""  selected>Choose...</option>
                                         <option value="m">Normal</option>

                                    </select>
                                </div> --}}

                            </div>


                            <br>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <select id="group_search"  name="group_search" class="form-control group_search">
                                        <option value="">Select Group</option>
                                        <?php
                                        if($billing_type==3)
                                        {
                                        // LoadCombo("test_sub_group","id","sub_group_name",'','where display_status=1 AND is_deleted=0',"order by id asc");
                                            // LoadCombo("service_group_master","id","group_name",'','where display_status=1 AND is_deleted=0 and is_lab_group=1',"order by id asc");

                                                    foreach ($test_groups as $key) {
                                                         ?>
                                                            <option value="{{$key->id}}" itemType="1" itemamt='{{$key->TestRate}}' itemcode='{{$key->test_code}}' testName='"+val.TestName+"'>{{$key->TestName}}</option>
                                                         <?php
                                                    }

                                        }
                                        else{
                                            LoadCombo("service_group_master","id","group_name",'','where display_status=1 AND is_deleted=0',"order by id asc");
                                        }
                                        ?>


                                    </select>
                                  <small id="group_search_error" class="form-text text-muted error"></small>
                                </div>
                                <?php   if($billing_type!=3)
                                {
                                    ?>
                                <div class="form-group col-md-4">
                                    <select id="item_search" name="item_search" placeholder="Select Item" class="form-control item_search">
                                        Select Item
                                    </select>

                                </div>
                                <?php
                                }
                                if($billing_type==3)
                                {?>
                                <div class="form-group col-md-4">
                                    <div class="form-group">

                                        <select class="items-dat"></select>
                                    </div>

                                </div>
                                <?php }?>
                            </div>
                            <div class="form-row">

                                {{-- <div class="form-group col-md-2">
                                    <label>Test Name</label>
                                    <input type="text" class="form-control" id="test_name" name=""  readonly placeholder="Name">
                                </div>


                                <div class="form-group col-md-2">
                                    <label>Item Code</label>
                                    <input type="text" name="test_code" id="test_code" readonly class="form-control" placeholder="Code">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Quantity</label>
                                    <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="Quantity">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Amount</label>
                                    <input type="text" name="test_amt" id="test_amt" readonly value="" class="form-control" placeholder="Amount">
                                </div>
                                <div class="form-group col-md-1">
                                    <label>Dis in %</label>
                                    <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="Dsc %">
                                </div>
                                <div class="form-group col-md-1">
                                    <label>Dis Amt</label>
                                    <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="Dsc Amt">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Net Amt</label>
                                    <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="Amt">
                                </div> --}}

                            </div>

                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Rate</th>
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
                            {{-- <div id="crud">
                                <div class="form-group col-md-2 align-items-center justify-content-sm-center">
                                    <br>
                                    <a id="add_duplicate" class="btn btn-primary inline-flex items-center px-4 py-2" style="color:white">Add</a>
                                </div>

                            </div> --}}


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
                                    <input type="text" name="discount_in_percentage" id="discount_in_percentage" value="" class="form-control" placeholder="" onkeyup="calculateDiscountInPercentage()">
                                </div>

                                <div class="form-group col-md-2">
                                    <label>Total Bill Amt</label>
                                    <input type="text" name="totalbill_amount" id="totalbill_amount" value="" class="form-control" placeholder="" readonly>
                                </div>

                                <div class="form-group col-md-2">
                                    <label>Total Paid<span class="required">*</span></label>
                                    <input type="text" name="total_paid" id="total_paid"  class="form-control" placeholder="" value="0">
                                    <small id="total_paid_error" class="form-text text-muted error"></small>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Transaction ID</label>
                                    <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                                </div>

                            </div>
                            {{-- <div class="details row">

                                <div class="form-group col-md-3">
                                    <label>Dis in %</label>
                                    <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                                </div>
                                <div class="form-group col-md-3">
                                    <label></label>
                                    <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                                </div>
                                <div class="form-group col-md-3">
                                    <label></label>
                                    <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                                </div>
                                <div class="form-group col-md-3">
                                    <label></label>
                                    <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                                </div>


                            </div> --}}
                            {{-- <div class="details row">

                                <div class="form-group col-md-3">
                                    <label>Total Paid</label>
                                    <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Single Payment Mode 2</label>
                                    <select id="patient_type" name="patient_type" class="form-control">
                                        <option  value="" selected>Choose...</option>
                                        {{LoadCombo("patient_type_master","id","patient_type_name",'','where display_status=1 AND is_deleted=0',"order by id desc");}}

                                    </select>
                                </div>


                                <div class="form-group col-md-3">
                                    <label>Total Credit</label>
                                    <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="details row">
                                <div class="form-group col-md-3">
                                    <label>Total Pending</label>
                                    <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                                </div>

                                <div class="form-group col-md-3">
                                    <label>IPD Bill No.</label>
                                    <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Edit Bill</label>
                                    <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                                </div>
                            </div> --}}

                            <input type="hidden" value="0" name="patientBillingId" id="patientBillingId">
                            <br>
                            <div class="col-md-4">
                                <label>Remarks</label>
                                <textarea class="form-control" name="bill_remarks" id="bill_remarks"></textarea>
                            </div>
                            <br>
                            <div id="crude">
                            <a class="btn btn-primary inline-flex items-center px-4 py-2" style="color:white" onclick="saveBillingData(1)" id="saveBillingDatabtn">Save</a>
                            </div>
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
                            <table id="example4" class="display" style="min-width: 845px">
                                <thead>
                                <tr>
                                    <th>Billing Date</th>
                                    <th>Visit Date</th>
                                    <th>Visit Code</th>
                                    <th>Bill No.</th>
                                    <th>Payment Mode</th>
                                    <th>Total Amt</th>
                                    <th>Dis Amt</th>
                                    <th>Net Amt</th>
                                    <th>Paid Amount</th>
                                    <th>Balance Amount</th>
                                    <th>Remarks</th>
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


<script>
    $(document).ready(function() {
        $("#group_search").select2();
        getBillingData();
        var optionsCount = 0;
    });
    function getBillingData() {
        var patientId=$('#patient_id').val();
        var visitId=$('#visit_id').val();
        var billingType=$('#billing_type').val();

        $("#example4").dataTable().fnDestroy()
        table = $('#example4').DataTable({
            scrollY: 470,
            "order": [] ,
            "autoWidth": false,
            dom: 'lfrtip',
            'ajax': {
                url: "<?php echo url('/') ?>/get-billing-data",
                type: 'POST',
                "data": function(d) {
                    //d.visitId= visitId;
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
                    "data": "visit_date",
                    "className": "text-right",
                    "render": function(data, type, full, meta) {
                        var date = new Date(data);
                        var month = date.getMonth() + 1;
                        return ('0' + date.getDate()).slice(-2) + "-" + (month.toString().length > 1 ? month : "0" + month) + "-" +   date.getFullYear();

                    },
                },
                {
                    "data": "patient_visit_id",
                    "className": "text-right",
                },
                {
                    "data": "PatientLabNo",
                    "className": "text-right",
                    // "render": function(data, type, full, meta) {
                    //     var billingNo = data + 1;
                    //     var length = 3;
                    //     return (Array(length).join('0') + billingNo).slice(-length);

                    // },
                },
                {
                    "data": "payment_mode_name",
                    "className": "text-right",

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
                    "data":"bill_remarks"
                },
                {
                    "data": "PatientId",
                    "className": "text-right",
                    "render": function(data, type, full, meta) {

                        var renderArea="";
                        if (full.total_paid == 0.00 && full.is_cancelled==0 && full.is_result_enterd==0) renderArea +=`<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Edit"class="edit_bill_data btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil" ></i></a>`;
                        // && full.is_result_enterd==0  removed this condition for cancelation after result entry
                        if (full.total_paid == 0.00 && full.is_cancelled==0 ) renderArea +=`<a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Cancel" class="cancel_bill_data btn btn-danger shadow btn-xs sharp mr-1"><i class="fa fa-ban" ></i></a>`;

                        renderArea +=` <a href="javascript:void(0)" class="active_link pdf_billing"><i class="fa fa-print" aria-hidden="true"></i></a>`;

                        return renderArea;

                    }
                },

            ]
        });

    }

    function generateform(data){
        $(".default_value").remove();
        if(data.length){
            data.forEach((item, index) => {
               // alert(item.is_test_group);
                var html="";
                html+="<tr>";
                html+=" <td><input type='text' class='form-control test_name' id='test_name_"+index+"' name='test_name[]' placeholder='Name' value='"+item.item_name+"' readonly><input type='hidden' name='service_item_id[]' id='service_item_"+indexn+"'  value='"+item.serviceitemid+"' class='form-control service_item_id'></td>";
                html+="<td><input type='text' name='test_code[]' id='test_code_"+index+"'  class='form-control test_code' placeholder='Code' value='"+item.item_code+"' readonly></td>";
                html+="<td><input type='number' name='test_rate[]' id='test_rate_"+index+"'  class='form-control test_rate' placeholder='Rate' value='"+item.item_amount+"' onkeyup=calculateQuantityWithBasicAmount(this.value,'"+item.item_amount+"','"+index+"')></td>";

                html+="<td><input type='number' name='quantity[]' id='quantity_"+index+"' value='1' class='form-control quantity' placeholder='Quantity' onkeyup=calculateQuantityWithBasicAmount(this.value,'"+item.item_amount+"','"+index+"')></td>";
                html+="<td><input type='text' name='test_amount[]' id='test_amount_"+index+"'  class='form-control test_amount' placeholder='Amount' value='"+item.item_amount+"' readonly></td>";
                html+="<td><input type='text' name='discount_percentage[]' id='discount_percentage_"+index+"' value='' class='form-control discount_percentage' onkeyup=calculateDiscountAmount(this.value,'"+index+"') placeholder='Dsc %'></td>";
                html+="<td><input type='text' name='discount_amount[]' id='discount_amount_"+index+"' value='' class='form-control discount_amount'  placeholder='Dsc Amt' readonly></td>";
                html+="<td><input type='text' name='amount[]' id='amount_"+index+"' value='"+item.item_amount+"' class='amount form-control totalamt' placeholder='Amt' readonly></td>";
                html+="<td><i style='color:red' class='fa fa-trash' onclick=removeFiled(this);></i></td>";
                html+="<input type='hidden' name='item_type[]'  id='item_type_"+$i+"' value='"+item.is_test_group+"'></tr>"
                $('#append_data').prepend(html);
            });
        }
    }


    var $i = 0;
    var serviceItems = [];

    function generateform(data,service_item_id){
        if(data.length){
            // console.log('data tabel',data);
            if (jQuery.inArray(service_item_id, serviceItems) !== -1) {
            sweetAlert("Oops...", "Item Already added", "error");
        }else{
            $("#append_data").empty();
            $serviceItems = data.map((item)=>item.serviceitemid)
             console.log('dta log',$serviceItems);
            data.forEach((item, index) => {
                // add_duplicate(service_item_id,name,code,amt);
                var html="";

                var discount=0;
                if(item.discount_amount )
                {
                    discount=item.discount_amount;
                }

                html+="<tr>";
                html+=" <td><input type='text' class='form-control test_name' id='test_name_"+index+"' name='test_name[]' placeholder='Name' value='"+item.item_name+"' readonly><input type='hidden' name='service_item_id[]' id='service_item_"+index+"'  value='"+item.serviceitemid+"' class='form-control service_item_id'> <input type='hidden' name='service_id[]' id='service_"+index+"'  value='"+item.id+"' class='form-control service_id'><input type='hidden' name='PatientBillingIds[]' id='service_"+index+"'  value='"+item.patientbillingid+"' class='form-control PatientBillingId'></td>";
                html+="<td><input type='text' name='test_code[]' id='test_code_"+index+"'  class='form-control test_code' placeholder='Code' value='"+item.item_code+"' readonly></td>";
                html+="<td><input type='number' name='test_rate[]' id='test_rate_"+index+"'  class='form-control test_rate' placeholder='Rate' value='"+item.unit_rate+"' onkeyup=calculateQuantityWithBasicAmount(this.value,'"+item.unit_rate+"','"+index+"')></td>";

                html+="<td><input type='number' name='quantity[]' id='quantity_"+index+"' value='"+item.quantity+"' class='form-control quantity' placeholder='Quantity' onkeyup=calculateQuantityWithBasicAmount(this.value,'"+item.unit_rate+"','"+index+"')></td>";
                html+="<td><input type='text' name='test_amount[]' id='test_amount_"+index+"'  class='form-control test_amount' placeholder='Amount' value='"+item.unit_total+"' readonly></td>";
                html+="<td><input type='text' name='discount_percentage[]' id='discount_percentage_"+index+"' value='"+item.serviceitem_discount+"' class='form-control discount_percentage' onkeyup=calculateDiscountAmount(this.value,'"+index+"') placeholder='Dsc %'></td>";
                html+="<td><input type='text' name='discount_amount[]' id='discount_amount_"+index+"' value='"+discount+"' class='form-control discount_amount'  placeholder='Dsc Amt' readonly></td>";
                html+="<td><input type='text' name='amount[]' id='amount_"+index+"' value='"+item.serviceitemamount+"' class='amount form-control totalamt' placeholder='Amt' readonly></td>";
                html+="<td><i style='color:red' class='fa fa-trash' onclick=removeFiled(this);></i></td>";
                html+="<input type='hidden' name='item_type[]'  id='item_type_"+$i+"' value='"+item.is_test_group+"'></tr>"
                $('#append_data').prepend(html);
            });
        }
    }
    else{
        $("#append_data").empty();
        $(".default_value").addClass();
    }
    }



    function add_duplicate(service_item_id,name,code,amt,itemType=0)
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
            html+="<td><input type='number' name='test_rate[]' id='test_rate_"+$i+"'  class='form-control test_rate' placeholder='Rate' value='"+amt+"' onkeyup=calculateQuantityWithBasicAmount(this.value,'"+amt+"','"+$i+"')></td>";

            html+="<td><input type='number' name='quantity[]' id='quantity_"+$i+"' value='1' class='form-control quantity' placeholder='Quantity' onkeyup=calculateQuantityWithBasicAmount(this.value,'"+amt+"','"+$i+"')></td>";
            html+="<td><input type='text' name='test_amount[]' id='test_amount_"+$i+"'  class='form-control test_amount' placeholder='Amount' value='"+amt+"' readonly></td>";
            html+="<td><input type='text' name='discount_percentage[]' id='discount_percentage_"+$i+"' value='' class='form-control discount_percentage' onkeyup=calculateDiscountAmount(this.value,'"+$i+"') placeholder='Dsc %'></td>";
            html+="<td><input type='text' name='discount_amount[]' id='discount_amount_"+$i+"' value='' class='form-control discount_amount'  placeholder='Dsc Amt' readonly></td>";
            html+="<td><input type='text' name='amount[]' id='amount_"+$i+"' value='"+amt+"' class='amount form-control totalamt' placeholder='Amt' readonly></td>";
            html+="<td><i style='color:red' class='fa fa-trash' onclick=removeFiled(this);></i></td>";
            html+="<input type='hidden' name='item_type[]'  id='item_type_"+$i+"' value='"+itemType+"'></tr>"

            $('#append_data').prepend(html);
            $('#quantity_'+$i).focus();

            if(service_item_id){
                $('#service_item_'+$i).val(service_item_id);
                $('#test_name_'+$i).val(name);
            }
        }
    }

    $('#example4 tbody').on('click', '.pdf_billing', function() {
        var data = table.row($(this).parents('tr')).data();
        $.redirect('{{url("pdf-billing-data")}}', { _token: "{{ csrf_token() }}", patient_id: data.PatientId, billing_type: data.billing_type,  patient_billing_id: data.id}, 'POST', '_blank');
    });

    $('#example4 tbody').on('click', '.cancel_bill_data', function() {
        var data = table.row($(this).parents('tr')).data();

        var billId=data.id;
       swal({
                title: "Are you sure to cancel this bill ? "+ data.PatientLabNo,
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes'
            }).then((swalStatus) => {
                if (swalStatus.value) {
                  ///////////////////////////////// CANCEL AJAX
                $.ajax({
                type: "POST",
                url: "<?php echo url('/') ?>/cancelBill",
                data: {billId:billId},
                success: function(result) {
                        if(result.status==1){
                            sweetAlert("Done...", result.message, "success");
                            $('#example4').DataTable().ajax.reload();
                            getOutStandingData();
                         }
                         else{
                            sweetAlert("Done...", result.message, "error");
                         }
                     }
                });
                ///////////////////////////////CANCEL AJAX
                }
            });

        });




    $('#group_search').on('change', function() {

        biltype=$('#billing_type').val();

        if(biltype==3){
        var selectText = $( "#group_search option:selected" ).text();
        var selectItemId = $( "#group_search option:selected" ).val();
        var selectCode = $( "#group_search option:selected" ).attr('itemcode');
        var selectAmt = $( "#group_search option:selected" ).attr('itemamt');
        var itemType=  1; // for group

        if(selectItemId>0)
        {
            add_duplicate(selectItemId,selectText,selectCode,selectAmt,itemType);
            getSumValue();
            calculateDiscountInPercentage();
        }
     } // only for lab bill

    });

    $('#item_search').on('change', function() {

        var selectText = $( "#item_search option:selected" ).text();
        var selectItemId = $( "#item_search option:selected" ).val();
        var selectCode = $( "#item_search option:selected" ).attr('itemcode');
        var selectAmt = $( "#item_search option:selected" ).attr('itemamt');

        if(selectItemId == 0)
        {
            for(i=1;i<=optionsCount;i++)
            {
                add_duplicate($(`#${i}`).val(), $(`#${i}`).text(), $(`#${i}`).attr('itemcode'),$(`#${i}`).attr('itemamt'));
            }
        }
        else{
            add_duplicate(selectItemId,selectText,selectCode,selectAmt);

        }


        getSumValue();
        calculateDiscountInPercentage();



    });
    $('#group_search').on('change', function (e) {

        // var billing_type=$("#billing_type").val();
        // if(billing_type==3)
        // {
        //     getTestItems();
        // }
        // else{
        //     getItemList();
        // }

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
        })
        $('#total_amount').val(sum_value);
        $('#totalbill_amount').val(sum_value);
       // alert(sum_value);
    }
    /////////////////FOR TEST ITEMS
    function getTestItems()
    {
        $('#item_search').empty();
        var groupId=$('#group_search').val();

        $.ajax({
            type: "POST",
            url: "<?php echo url('/') ?>/getTestItemList",
            data: {groupId:groupId},
            success: function(datas) {
               var opt="<option value=''>Select</option>";
                opt+="<option value='-1' id='selctall'>Select All</option>";
                $.each(datas, function (key, val) {

                    opt+="<option value='"+val.id+"' itemamt='"+val.TestRate+"' itemcode='"+val.test_code+"' testName='"+val.TestName+"'>"+val.TestName+"</option>";
                });

                $('#item_search').append(opt).selectpicker('refresh');
            },
        });

    }
    ////////////////FOR GENERAL ITEMS
    function getItemList(){
        $('#item_search').empty();
        var groupId=$('#group_search').val();
        $.ajax({
            type: "POST",
            url: "<?php echo url('/') ?>/getServiceItemList",
            data: {groupId:groupId},
            success: function(datas) {


                var opt="<option value=''>Select</option>";
                var billing_type=$("#billing_type").val();
                if(billing_type==3)
                {
                    var opt = "<option value='-1'>--Select--</option>"
                    opt+="<option value='0'>Select All Test</option>";
                }
                else{
                    var opt = "<option value='-1'>--Select--</option>"
                    opt+="<option value='0'>Select All Test</option>";
                }
                optionsCount = datas.length;
                // console.log('dta', datas);
                $.each(datas, function (key, val) {
                    const id = key+1;
                    opt+="<option id='"+id+"' value='"+val.id+"' itemamt='"+val.item_amount+"' itemcode='"+val.item_code+"'>"+val.item_name+"</option>";
                });

                // console.log('options', opt);

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
    $( document ).ready(function() {
        edit_billno_div(1,1);
});

    //Basic Amount
    function calculateQuantityWithBasicAmount(quantityId,amt,count)
    {

        var rate=$("#test_rate_"+count).val();
        if(!rate || rate=="") rate=0;
        amt=rate;


        var quantityId=$("#quantity_"+count).val();
        if(!quantityId || quantityId=="") quantityId=0;


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



    function saveBillingData(crude,page)
    {
        $("[id*='_error']").text('');
        // var url = "";
        // if (page == 1) {
        //     url='{{route('save-billing-data')}}';
        //     var form = $('#billing-form')[0];
        // }
        url='{{route('save-billing-data')}}';
        var form = $('#billing-form')[0];
        var formData = new FormData(form);
        formData.append('crude', crude);

        // var form = $('#billing-form')[0];
        // var formData = new FormData(form);
        // formData.append('crude', crude);
        // url='{{route('save-billing-data')}}';

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
                    var billingType = result.dataArray.billing_type;

                    $i =0;
                    serviceItems = [];
                    var form = $('#billing-form')[0];
                    document.getElementById("billing-form").reset();
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
                            $.redirect('{{url("pdf-billing-data")}}', { _token: "{{ csrf_token() }}", patient_id: patientId, patient_billing_id: billingId, billing_type: billingType }, 'POST', '_blank');

                        }
                    });
                crude_btn_manage(1,1);
                edit_billno_div(1,1);
                }
                else {
                    sweetAlert("Oops...", result.message, "error");
                }
                $('#example4').DataTable().ajax.reload();

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
                $('#total_outstanding_amount').val( parseFloat(jsondata).toFixed(2));

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
        table_outStanding = $('#total_outstanding_data').DataTable({
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

    $('#example4 tbody').on('click', '.edit_bill_data', function() {
            var data = table.row($(this).parents('tr')).data();
             console.log(data);
            // $("#billno").val(data.);
            // $('#billno').trigger('change');
            $("#total_amount").val(data.TotalAmount);
            $('#total_amount').trigger('change');
             $("#payment_mode_name").val(data.patient_billing_mode_id);
            $('#payment_mode_name').trigger('change');
            $("#discount_in_percentage").val(data.discount_in_percentage);
            $('#discount_in_percentage').trigger('change');
            $("#totalbill_amount").val(data.NetAmount);
            $('#totalbill_amount').trigger('change');
            $("#total_paid").val(data.total_paid);
            $('#total_paid').trigger('change');
            $("#id_data").val(data.id);
            $('#id_data').trigger('change');
            $("#patientBillingId").val(data.id);
            $("#bill_remarks").val(data.bill_remarks);
            // $("#hidid").val(data.id);
            // if(data.active_status==1)  $('#display_status').prop("checked", true); else  $('#display_status').prop("checked", false);
            //window.location.href = '{{url("/UserManagement/createUser")}}?id='+ data.id;
             crude_btn_manage(2,1);
             edit_billno_div(2,1,data.id);

        });
        function edit_billno_div(type = 1, page,bill_id = false)
        {
            var data = table.row($(this).parents('tr')).data();
            // console.log('id',data);
            var a = bill_id;
            var b = 1;
            var sum = a+b;
            if (page == 1) {
            if (type == 1)
            { $('#billno').html(
                ': New');

            }
            else if (type == 2)
            {
                $('#billno').html(
                    ' : 0'+sum +' ');

            }

        }

        }
        function crude_btn_manage(type = 1, page) {
        if (page == 1) {
            if (type == 1)
            { $('#crude').html(
                '<button type="button" class="btn btn-sm btn-primary my-2 pull-"  onclick="saveBillingData(\'' + type + '\',\'' + page + '\')" >Save</button>');

            }
            else if (type == 2)
            {
                $('#crude').html(
                '<button type="button" class="btn btn-sm btn-primary my-2 pull-left"  onclick="saveBillingData(\'' +type + '\',\'' + page + '\')" >Update</button>');

            }

        }

    }

    $('#example4 tbody').on('click', '.edit_bill_data', function() {
        var data = table.row($(this).parents('tr')).data();

        $.ajax({
            url: "{{url("get-billing-data-byid")}}",
            type: 'POST',
            data:  { _token: "{{ csrf_token() }}", patient_id: data.PatientId, billing_type: data.billing_type,  patient_billing_id: data.id},
            success : function(result) {
                var jsondata = $.parseJSON(result);
                 console.log(jsondata);
                generateform(jsondata.data.serviceItems);
                // billingDtaform(jsondata.data.billing);
            },
        })

    });
    $(document).ready(function(){
        $(document).ajaxSend(function(){
            $('#loader').hide();
        });

    $(".items-dat").select2({
            placeholder: "Search Items",
            ajax: {
                url: "{{ route('getAllItemListBygrp') }}",
                type: "post",
                dataType: 'json',
                // delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term, // search term

                    };

                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

        $('.items-dat').on('select2:select', function (e) {
            var data = e.params.data;
            // console.log('hit',data);
            var selectText = data.text;
            var selectItemId = data.id;
            var selectCode = data.itemcode;
            var selectAmt = data.itemamt;
            add_duplicate(selectItemId,selectText,selectCode,selectAmt);

            getSumValue();
            calculateDiscountInPercentage();
        });

    });
</script>

