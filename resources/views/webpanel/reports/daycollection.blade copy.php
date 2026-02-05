<div class="content-body">
    <div class="container-fluid">

        <form name="frm" id="frm" action="#" method="post" class="card card-body">
            @csrf
            <div class="row">

                <div class="col-xl-2 col-md-6">
                    <div class="form-group">
                        <label class="text-label">From Date</label>
                        <input type="text" name="from_date" id="from_date" class="form-control" value="<?php echo $from_date_input ?>"   placeholder="" >
                        <small id="name_error" class="form-text text-muted error"></small>
                    </div>
                </div>


                <div class="col-xl-2 col-md-3">
                    <div class="form-group">
                        <label class="text-label">To Date </label>


                        <input type="text" name="to_date" id="to_date"  value="<?php echo $to_date_input ?>" class="form-control" placeholder="" >
                        </div>
                  </div>
                  <div class="col-xl-2 col-md-3 d-flex align-items-center">
                    <div>
                        <button class="btn btn-primary search-btn btn-sm" type="submit">Search</button>
                    </div>
                  </div>
            </div>
        </form>

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-sm-12">
                <div class="card card-sm ">

                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                            
                        <tr>
                            {{-- <button type="button" class="btn btn-primary" id="print_scrn" style="float: right;">Print</button> --}}
                        </tr>
                           
                                 <tr >                                 
                                    <th>BILL DATE</th>
                                    <th>FREE</th>
                                    <th>ONLINE/NEFT/OTHER</th>
                                    <th>CASH</th>
                                    <th>DEBIT/CREDIT CARD</th>
                                    <th>CHEQUE/BANK</th>
                                    <th>CREDIT/DUE</th>
                                    <th>BALANCE AMOUNT</th>
                                    <th>TOTAL AMOUNT</th>
                                    
                                   
                                </tr>
                                <?php
                               foreach ($bill_data as $bill) {
                              
                                ?>
                               <tr>

                                <td>{{$bill->billdate}}</td>
                                <?php if($bill->patient_billing_mode_id ==5 ){?>                              
                                <td>{{$bill->total_paid}}</td>
                                <?php }else{ ?>
                                    <td></td>
                                    <?php }?>
                                    <?php if($bill->patient_billing_mode_id ==4 ){?>                              
                                <td>{{$bill->total_paid}}</td>
                                <?php }else{ ?>
                                    <td></td>
                                    <?php }?>
                                <?php if($bill->patient_billing_mode_id ==1 ){?>                              
                                <td>{{$bill->total_paid}}</td>
                                <?php }else{ ?>
                                    <td></td>
                                    <?php }?>
                                <?php if($bill->patient_billing_mode_id ==3 ){?>      
                                <td>{{$bill->total_paid}}</td>
                                <?php }else{ ?>
                                    <td></td>
                                    <?php }?>
                                <?php if($bill->patient_billing_mode_id ==2 ){?>  
                                <td>{{$bill->total_paid}}</td>
                                <?php }else{ ?>
                                    <td></td>
                                    <?php }?> 
                                    <?php if($bill->patient_billing_mode_id ==0 ){?>                              
                                <td>{{$bill->total_paid}}</td>
                                <?php }else{ ?>
                                    <td></td>
                                    <?php }?>
                                <td>{{$bill->balance_amount}}</td>
                                <td>{{$bill->TotalAmount}}</td>
                               </tr>

                               <?php
                               }
                               ?>
                               

                               
                                   

                    </table>

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
</script>
