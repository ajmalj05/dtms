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
                <b> {{$data['branch_details']->address_line_1}}<br>
                    {{$data['branch_details']->address_line_2}}.</b> <br>
                    Phone:  {{$data['branch_details']->phone}} <br> Email : {{$data['branch_details']->email}}
            </td>
        </tr>
       </table>
       <br>
       {{$data['branch_details']->tag_line}}
       <hr>

       <div style="text-align: center">
        <h4> Bill</h4>
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
                                <p> : {{$data['patient_data']->salutation_name}} {{$data['patient_data']->name}}  {{$data['patient_data']->last_name}}</p>
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
                <p>Doctor In-Charge  : {{$data['opd_billing_data']->specialist_name}}</p>
            </td>


            <td width="30%">
                {{-- Bill No: {{$data['bill_no']}} --}}
                Bill No:<b>{{ $data['opd_billing_data']->PatientLabNo }} </b>

                <br>
                Date :   {{$data['bill_date']}}
            </td>


        </tr>
        <tr style="border-top:1px solid #000">
            <td colspan="2" style="border-top:1px solid #000">
                <p>
                Billing Account Type:  {{ $data['billing_account_data']->payment_mode_name }}
            </p>
            </td>
        </tr>
      </table>
      <div style="height:300px" class="br bb bl">
      <table class="table_custom" style="border-top:none !important" border="1">

        <tr>
            <td><b>Sl No </b></td>
            <td><b>Item </b></td>
            <td><b>Qty </b></td>
            <td><b>Rate </b></td>
            <td><b>Amount </b></td>
            <td><b>Dis (%) </b></td>
            <td><b>Net Amount </b></td>
        </tr>


        @php
        $i =1;
    @endphp
    @foreach($data['service_item_data'] as $service)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $service->item_name}}</td>
                <td>{{ $service->quantity}}</td>
                <td>{{ $service->item_amount}}</td>
                <td align="right">
                    @if(isset($service->item_amount))
                    {{  number_format((float)$service->unit_total, 2, '.', '')}}
                @else
                    {{ '0' }}
                @endif

                </td>
                <td>  {{ $service->serviceitem_discount}}</td>
                <td align="right">
                    @if(isset($service->serviceitemamount))
                    {{ number_format((float)$service->serviceitemamount, 2, '.', '')}}
                @else
                    {{ '0' }}
                @endif
                </td>
            </tr>
    @php
    $i++;
@endphp
@endforeach

<tr>
    <td colspan="5">Total Amount</td>
    <td colspan="2" align="right">{{ number_format((float)$data['billing_account_data']->TotalAmount , 2, '.', '')}}</td>
</tr>

<tr>
    <td colspan="5">Discount Amount</td>
    <td colspan="2" align="right">{{ number_format((float)$data['billing_account_data']->Discamount , 2, '.', '')}}</td>
</tr>

<tr>
    <td colspan="5">Net Amount</td>
    <td colspan="2" align="right"><b>{{ number_format((float)$data['billing_account_data']->NetAmount , 2, '.', '')}}</b></td>
</tr>

<tr>
    <td colspan="5">Total Paid</td>
    <td colspan="2" align="right"><b> {{ number_format((float)$data['billing_account_data']->total_paid , 2, '.', '')}}</b></td>
</tr>

<tr>
    <td colspan="5">Balance Amount</td>
    <td colspan="2" align="right"><b>{{ number_format((float)$data['billing_account_data']->balance_amount , 2, '.', '')}}</b></td>
</tr>

<tr>
    <td colspan="7">
        <p style="font-size: 12px">
            Bill Amount in words: {{ $data['net_amount_in_words'] }}
        </p>
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
