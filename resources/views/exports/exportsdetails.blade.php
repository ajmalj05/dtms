<html>
<head>
    <style>
        .common{
            font-family:Verdana, Arial, Helvetica, sans-serif;
            font-size: 12px;
        }
        .table_custom
        {
            font-size:11px;font-family:Verdana, Arial, Helvetica, sans-serif;
            width:100%;
            border-collapse: collapse;

        }
        .border
        {
            border: 1px solid #000;
        }
        .bt{
            border-top: 1px solid #000;
        }
        .bb{
            border-bottom: 1px solid #000;
        }
        .bl{
            border-left: 1px solid #000;
        }
        .br{
            border-right: 1px solid #000;
        }

        .table_custom2{
            font-size:12px;font-family:Verdana, Arial, Helvetica, sans-serif;
            width:100%;
            border-collapse: collapse;
            border-top: 1px solid #000;
            border-left: 1px solid #000;
            border-right: 1px solid #000;
        }
        .s6{
            font-size: 12px;
        }
    </style>
    <title>Bill</title>

</head>
<body>
    <div class="common">
       <table class="table_custom">
        <tr>
            <td>
                <img width="30%" src="./images/company-logo.png">
                <br>
            </td>
            <td width="10%">
                &nbsp;
            </td>
            <td>
                <b> JDC Junction, Mudavanmugal, Konkalam Road,<br>
                    Trivandrum, Kerala, India, Pin. 695032.</b> <br>
                    Phone: 0471-2356200, 9846040055, Email : info@jothydev.net
            </td>
        </tr>
       </table>
       <br>
       (A Project of Living Longer Life Care Pvt. Ltd)
       <hr>

       <div style="text-align: center">
        <h4>Detailed Bill Report</h4>

      </div>

      <table class="table table-bordered table-sm border table_custom" style="border-top:none !important" >


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
             <tr style="background-color: #e79090;border-top:1px solid #000" class="border">
                <th>Sl No</th>
                <th style="border-left:1px solid #000">Patient Name</th>
                <th style="border-left:1px solid #000">Bill Date</th>
                <th style="border-left:1px solid #000">Total Amount</th>
                <th style="border-left:1px solid #000">Discount</th>
                <th style="border-left:1px solid #000">Net AMount</th>
            </tr>
            <tr class="border" style="border-top:1px solid #000">
                <td style="border-top:1px solid #000">{{$sl}}</td>
                <td style="border-top:1px solid #000;border-left:1px solid #000">{{$bill->patientName}}</td>
                <td style="border-top:1px solid #000;border-left:1px solid #000">{{$bill->billdate}} <br> <b> {{$bill->PatientLabNo}}</b></td>
                <td align="right" style="border-top:1px solid #000;border-left:1px solid #000">{{$accounts[0]->TotalAmount}}</td>
                <td align="right" style="border-top:1px solid #000;border-left:1px solid #000">{{$accounts[0]->Discamount}}</td>
                <td align="right" style="border-top:1px solid #000;border-left:1px solid #000">{{$accounts[0]->NetAmount}}</td>
            </tr>

            <tr class="border" style="border-top:1px solid #000">
                <th style="border-top:1px solid #000" width="10%">Sl No</th>
                <th style="border-top:1px solid #000;border-left:1px solid #000" width="30%">Item Name</th>
                <th style="border-top:1px solid #000;border-left:1px solid #000" width="10%">Qty</th>
                <th style="border-top:1px solid #000;border-left:1px solid #000" width="15%">Amount</th>
                <th style="border-top:1px solid #000;border-left:1px solid #000" width="10%">Discount</th>
                <th style="border-top:1px solid #000;border-left:1px solid #000" width="">Net Amount</th>
            </tr>

            <?php
            $sr=0;
                foreach ($bill->items as $item) {
                    $sr++;
                    $f_quantity=$f_quantity+$item->quantity;
                    $f_unit_total=$f_unit_total+$item->unit_total;
                    $f_discount_amount=$f_discount_amount+$item->discount_amount;
                    ?>
                 <tr style="border-top:1px solid #000">
                    <td style="border-top:1px solid #000">{{$sr}}</td>
                    <td style="border-top:1px solid #000;border-left:1px solid #000">{{$item->item_name}}</td>
                    <td style="border-top:1px solid #000;border-left:1px solid #000">{{$item->quantity}}</td>
                    <td align="right" style="border-top:1px solid #000;border-left:1px solid #000">{{$item->unit_total}}</td>
                    <td align="right" style="border-top:1px solid #000;border-left:1px solid #000">{{$item->discount_amount}}</td>
                    <td align="right" style="border-top:1px solid #000;border-left:1px solid #000">{{$item->serviceitemamount}}</td>
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



</body>

</html>
















