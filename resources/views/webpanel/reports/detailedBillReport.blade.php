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
                            <?php if($bill_data){?>
                        <tr>
                            <button type="button" class="btn btn-primary" id="print_scrn" style="float: right;">Print</button>
                        </tr>
                      <?php } ?>
                            <?php
                            $sl=0;
                            $f_total=0;
                            $f_quantity=0;
                            $f_unit_total=0;
                            $f_discount_amount=0;
                            foreach ($bill_data as $bill) {
                                $sl++;
                                $accounts=$bill->accounts;
                                $f_total=$f_total+$accounts[0]->NetAmount;
                                ?>
                                 <tr style="background-color: #e79090">
                                    <th>Sl No</th>
                                    <th>Patient Name</th>
                                    <th>Bill Date</th>
                                    <th>Total Amount</th>
                                    <th>Discount</th>
                                    <th>Net AMount</th>
                                </tr>
                                <tr>
                                    <td>{{$sl}}</td>
                                    <td>{{$bill->patientName??''}}</td>
                                    <td>{{$bill->billdate??''}}  <br> <b> {{$bill->PatientLabNo}}</b></td>
                                    <td align="right">{{$accounts[0]->TotalAmount??''}}</td>
                                    <td align="right">{{$accounts[0]->Discamount??''}}</td>
                                    <td align="right">{{$accounts[0]->NetAmount??''}}</td>
                                </tr>

                                <tr>
                                    <th  width="10%">Sl No</th>
                                    <th  width="30%">Item Name</th>
                                    <th  width="10%">Qty</th>
                                    <th  width="15%">Amount</th>
                                    <th  width="10%">Discount</th>
                                    <th  width="">Net Amount</th>
                                </tr>

                                <?php
                                $sr=0;
                                    foreach ($bill->items as $item) {
                                        $sr++;
                                        $f_quantity=$f_quantity+$item->quantity;
                                        $f_unit_total=$f_unit_total+$item->unit_total;
                                        $f_discount_amount=$f_discount_amount+$item->discount_amount;
                                        ?>
                                     <tr>
                                        <td>{{$sr}}</td>
                                        <td>{{$item->item_name??''}}</td>
                                        <td>{{$item->quantity??''}}</td>
                                        <td align="right">{{$item->unit_total??''}}</td>
                                        <td align="right">{{$item->discount_amount??''}}</td>
                                        <td align="right">{{$item->serviceitemamount??''}}</td>
                                     </tr>
                                        <?php
                                     }
                                ?>

                                <?php
                            }
                            ?>
                        <tr style="background-color: #6967EB;color:#FFF;font-weight:600">

                            <td colspan="2"><b>TOTAL</b></td>
                            <td>{{$f_quantity}}</td>
                            <td align="right">{{$f_unit_total}}</td>
                            <td align="right">{{$f_discount_amount}}</td>
                            <td align="right">{{$f_total}}</td>
                         </tr>
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

$('#print_scrn').click(function(){
    var from_date=$('#from_date').val();
    var to_date=$('#to_date').val();
        $.ajax
        ({
            url: '<?php echo url('/') ?>/generateDeatilpdf',
            data: {"from_date": from_date,"to_date":to_date,"_token": "{{ csrf_token() }}"},
            type: 'post',
            xhrFields: {
                responseType: 'blob'
            },
            success: function(response) {

                var blob = new Blob([response]);
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "Detailed_Bill.pdf";
                link.click();
            },
            error: function(blob){
                console.log(blob);
            }

        });
        });


    });
</script>
