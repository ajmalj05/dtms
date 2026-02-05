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
    <title>Receipt</title>

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
            <h4>Receipt</h4>
          </div>

          <table class="table_custom border">

            <tr class="border">

                <td>

                        <table>
                            <tr>
                                <td>
                                    <p>  Patient Name </p>
                                    <p>  Age </p>
                                    <p>  Gender </p>
                                    <p> Contact Number </p>
                                    <p> Address </p>
                                </td>
                                <td>
                                    <p> :  {{$data['patient_data']->name}} </p>
                                    <p> :
                                        @if(isset($data['patient_data']->dob))
                                        {{ \Carbon\Carbon::parse($data['patient_data']->dob)->diff(\Carbon\Carbon::now())->format('%y') . ' yrs' }}
                                    @else
                                        {{ '' }}
                                    @endif
                                    </p>
                                    <p>   :
                                        @if(str_contains($data['patient_data']->gender, 'f'))
                                        female
                                    @elseif (str_contains($data['patient_data']->gender, 'm'))
                                        male
                                    @endif
                                 </p>
                                    <p>  :  {{$data['patient_data']->mobile_number}}</p>

                                    <p> :   {{$data['patient_data']->address}} </p>
                                </td>
                            </tr>
                        </table>

                </td>

                <td width="20%">
                    <span class="s6"> UHID NO    <b>{{$data['patient_data']->uhidno}} </b></span>
                </td>

            </tr>

          </table>

          <table class="table_custom2" style="border-top:0px">

            <tr>
                <td>
                    <p>Doctor In-Charge  : {{$data['patient_data']->specialist_name}}</p>
                </td>


                <td width="30%">
                    {{-- Bill No: {{$data['bill_no']}} --}}
                    Receipt No.:<b>{{$data['billing_payment_data']->receipt_number}} </b>

                    <br>
                    Date :  <b>  {{$data['bill_payment_date']}}</b>
                </td>


            </tr>
            <tr style="border-top:1px solid #000">
                <td colspan="2" style="border-top:1px solid #000">
                    <p>
                    Billing Account Type: {{ $data['billing_payment_data']->payment_mode_name }}
                </p>
                </td>
            </tr>

            <tr style="border:1px solid #000">
                <td colspan="2" style="border-top:1px solid #000">
                    <p>
                       <b> Received (Reference No: {{$data['billing_payment_data']->reference_number}} ), a sum of Rs. {{ $data['billing_payment_data']->total_paid}}/-(Rupees Only).
                    </b>
                    </p>
                </td>
            </tr>


            <tr>
                <td colspan="2">
                    {{-- <p style="font-size: 12px">
                        Bill Amount in words: {{ $data['net_amount_in_words'] }}
                    </p> --}}
                    <p>
                        *This is a computerized Bill/Receipt
                    </p>
                </td>
            </tr>



          </table>



    </div>

    <div class="border" style="font-size: 11px">
        <div style="padding: 3%">
        For Jothydev’s Diabetes Hospital &amp; Research Centre  <br>
        <br>
        <br>
        Employee Name Authorised Signatory*
    </div>
    </div>
    <div class="border" style="font-size: 11px">
        <div style="padding: 3%">
            Wish You Speedy Recovery
        </div>
    </div>

</body>
</html>
