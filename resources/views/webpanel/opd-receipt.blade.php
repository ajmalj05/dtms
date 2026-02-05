<!DOCTYPE  html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>file_1666325277728</title>
    <meta name="author" content="Research 1"/>
    <style type="text/css"> * {margin:0; padding:0; text-indent:0; }
        .s1 { color: black; font-family:Cambria, serif; font-style: normal; font-weight: bold; text-decoration: none; font-size: 9px; }
        .s2 { color: black; font-family:Cambria, serif; font-style: normal; font-weight: bold; text-decoration: none; font-size: 10px; }
        .s3 { color: black; font-family:Cambria, serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 10px; }
        .s4 { color: #0462C1; font-family:Cambria, serif; font-style: normal; font-weight: normal; text-decoration: underline; font-size: 10px; }
        .s5 { color: black; font-family:"Times New Roman", serif; font-style: normal; font-weight: bold; text-decoration: none; font-size: 14px; }
        .s6 { color: black; font-family:Cambria, serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 11px; }
        .s7 { color: black; font-family:Cambria, serif; font-style: normal; font-weight: bold; text-decoration: none; font-size: 8px; }
        .s8 { color: black; font-family:Cambria, serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 8px; }
        table, tbody {vertical-align: top; overflow: visible; }
    </style>
</head>
<body>
<table style="border-collapse:collapse;margin-left:6%;margin-top:15px;" cellspacing="0">
    <tr style="height:95px">
        <td style="width:100px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px" colspan="3">
            <p style="text-indent: 0px;text-align: left;"><br/></p>
            <p style="padding-left: 2px;text-indent: 0px;text-align: left;">
                  <span>
               <table border="0" cellspacing="0" cellpadding="0"><tr><td><img width="218" height="67" src=""></td></tr></table></span></p>
            <p style="text-indent: 0px;text-align: left;"><br/></p>
            <p class="s1" style="padding-left: 5px;text-indent: 0px;text-align: left;">(A Project of Living Longer Life Care Pvt. Ltd)</p>
        </td>
        <td style="width:50px;border-top-style:solid;border-top-width:1px;border-bottom-style:solid;border-bottom-width:1px">
            <p style="text-indent: 0px;text-align: left;"><br/></p>
        </td>
        <td style="width:106px;border-top-style:solid;border-top-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px" colspan="2">
            <p style="text-indent: 0px;text-align: left;"><br/></p>
            <p class="s2" style="padding-left: 5px;padding-right: 9px;text-indent: 0px;line-height: 108%;text-align: left;">JDC Junction, Mudavanmugal, Konkalam Road, Trivandrum, Kerala, India, Pin. 695032.</p>
            <p class="s3" style="padding-left: 5px;text-indent: 0px;text-align: left;">Phone: 0471-2356200, 9846040055</p>
            <p style="padding-left: 5px;text-indent: 0px;text-align: left;"><a href="mailto:info@jothydev.net" style=" color: black; font-family:Cambria, serif; font-style: normal; font-weight: normal; text-decoration: none; font-size: 10px;" target="_blank">email: </a><a href="mailto:info@jothydev.net" class="s4" target="_blank">info@jothydev.net</a></p>
            <p class="s1" style="padding-top: 1px;padding-left: 5px;text-indent: 0px;text-align: left;">GSTIN:</p>
        </td>
    </tr>
    <tr style="height:32px">
        <td style="width:200px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px" colspan="6">
            <p class="s5" style="padding-top: 7px;padding-left: 200px;padding-right: 200px;text-indent: 0px;text-align: center;">Receipx/Bill</p>
        </td>
    </tr>
    <tr style="height:14px">
        <td style="width:124px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-right-style:solid;border-right-width:1px" colspan="4">
            <p class="s2" style="padding-left: 5px;text-indent: 0px;text-align: left;">Patient Name: <span class="s6">{{$data['patient_data']->name}}</span></p>
        </td>
        <td style="width:86px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-right-style:solid;border-right-width:1px" colspan="2">
            <p class="s2" style="padding-left: 5px;text-indent: 0px;line-height: 12px;text-align: left;">UHID: <span class="s6">{{$data['patient_data']->uhidno}}</span></p>
        </td>
    </tr>
    <tr style="height:26px;">
        <td style="width:56px;border-left-style:solid;border-left-width:1px" colspan="2">
            <p class="s2" style="padding-left: 5px;text-indent: 0px;line-height: 12px;text-align: left;">Age</p>
            <p class="s2" style="padding-left: 5px;text-indent: 0px;text-align: left;">Gender</p>
        </td>
        <td style="width:130px">
            <p class="s2" style="padding-left: 10px;text-indent: 0px;line-height: 12px;text-align: left;">:
                @if(isset($data['patient_data']->dob))
                    {{ \Carbon\Carbon::parse($data['patient_data']->dob)->diff(\Carbon\Carbon::now())->format('%y') . ' yrs' }}
                @else
                    {{ '' }}
                @endif </p>
            <p class="s2" style="padding-left: 9px;text-indent: 0px;text-align: left;">:
                {{$data['patient_data']->gender}}
            </p>
        </td>
        <td style="width:88px">
            <p style="text-indent: 0px;text-align: left;"><br/></p>
        </td>
        <td style="width:50px;border-left-style:solid;border-left-width:1px">
            <p style="text-indent: 0px;text-align: left;">

            </p>
        </td>
        <td style="width:86px;border-right-style:solid;border-right-width:1px">
            <p style="text-indent: 0px;text-align: left;"><br/></p>
            <p style="padding-left: 22px;text-indent: 0px;text-align: left;">
                  <span>
               <table border="0" cellspacing="0" cellpadding="0"><tr><td></td></tr></table></span></p>
        </td>
    </tr>
    <tr style="height:9px;border-right-style:solid;border-right-width:1px;">
        <td style="width:56px;border-left-style:solid;border-left-width:1px" colspan="2" rowspan="2">
            <p class="s2" style="padding-left: 5px;text-indent: 0px;line-height: 12px;text-align: left;">Address:</p>
        </td>
        <td style="width:130px" rowspan="2">
            <p class="s2" style="padding-left: 10px;text-indent: 0px;line-height: 12px;text-align: left;">:
                {{$data['patient_data']->address}}
            </p>
        </td>
        <td style="width:88px;border-right:1px solid;" rowspan="2">
            <p style="text-indent: 0px;text-align: left;"><br/></p>
        </td>
        <td style="width:88px;" >
            <p style="text-indent: 0px;text-align: left;"><br/></p>
        </td>
        <td style="width:88px;border-right-style:solid;border-right-width:1px;border-bottom: 1px solid;" >
            <p style="text-indent: 0px;text-align: left;"><br/></p>
        </td>

    </tr>
    <tr style="height:11px;border-right-style:solid;border-right-width:1px;">
        <td style="border-left:1px solid;width:196px;border-top-style:solid;border-top-width:1px;border-bottom-style:solid;border-bottom-width:1px;" rowspan="2">
            <p class="s2" style="padding-top: 8px;padding-left: 5px;text-indent: 0px;text-align: left;">IP Number: <span class="s6">0000</span></p>
        </td>
        <td style="width:106px;border-top-style:solid;border-top-width:1px;border-bottom-style:solid;border-bottom-width:1px;" rowspan="2">
            <p class="s2" style="padding-top: 8px;padding-left: 5px;text-indent: 0px;text-align: left;"><span class="s6"></span></p>
        </td>
    </tr>
    <tr style="height:20px;border-right-style:solid;border-right-width:1px;">
        <td style="width:150px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px" colspan="4">
            <p class="s2" style="padding-top: 6px;padding-left: 5px;text-indent: 0px;line-height: 11px;text-align: left;">Contact Number:
                <span class="s6">{{$data['patient_data']->mobile_number}}</span>
            </p>
        </td>
    </tr>
    <tr style="height:13px">
        <td style="width:150px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-right-style:solid;border-right-width:1px" colspan="4">
            <p class="s2" style="padding-left: 5px;text-indent: 0px;line-height: 11px;text-align: left;">Doctor In-Charge:
                <span class="s6"> {{$data['patient_data']->specialist_name}}</span>
            </p>
        </td>
        <td style="width:196px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-right-style:solid;border-right-width:1px" colspan="2">
            <p style="text-indent: 0px;text-align: left;"><br/></p>
        </td>
    </tr>
    <tr style="height:13px">
        <td style="width:150px;border-left-style:solid;border-left-width:1px;border-right-style:solid;border-right-width:1px" colspan="4">
            <p class="s2" style="padding-left: 5px;text-indent: 0px;line-height: 11px;text-align: left;">Ward : <span class="s3"></span></p>
        </td>
        <td style="width:196px;border-left-style:solid;border-left-width:1px;border-right-style:solid;border-right-width:1px" colspan="2">
            <p class="s2" style="padding-left: 5px;text-indent: 0px;line-height: 11px;text-align: left;">Bill No: <span class="s3">{{$data['bill_no']}}</span></p>
        </td>
    </tr>
    <tr style="height:13px">
        <td style="width:150px;border-left-style:solid;border-left-width:1px;border-right-style:solid;border-right-width:1px" colspan="4">
            <p class="s2" style="padding-left: 5px;text-indent: 0px;line-height: 11px;text-align: left;">Bed No: <span class="s3"></span></p>
        </td>
        <td style="width:105px;border-left-style:solid;border-left-width:1px">
            <p class="s2" style="padding-left: 5px;text-indent: 0px;line-height: 11px;text-align: left;">Date: <span class="s3">{{$data['bill_date']}}</span></p>
        </td>
        <td style="width:91px;border-right-style:solid;border-right-width:1px">
            <p class="s2" style="padding-left: 13px;text-indent: 0px;line-height: 11px;text-align: left;">Time: ………..</p>
        </td>
    </tr>
    <tr style="height:14px">
        <td style="width:150px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px" colspan="4">
            <p class="s2" style="padding-left: 5px;text-indent: 0px;line-height: 11px;text-align: left;">Policy Number: <span class="s3"></span></p>
        </td>
        <td style="width:196px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px" colspan="2">
            <p style="text-indent: 0px;text-align: left;"><br/></p>
        </td>
    </tr>
    <tr style="height:37px">
        <td style="width:150px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px" colspan="4">
            <p style="text-indent: 0px;text-align: left;"><br/></p>
            <p class="s2" style="padding-left: 5px;text-indent: 0px;text-align: left;">Billing Account Type: <span class="s3">{{ $data['billing_account_data']->payment_mode_name }}</span></p>
        </td>
        <td style="width:196px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px" colspan="2">
            <p class="s2" style="padding-top: 5px;padding-left: 5px;text-indent: 0px;line-height: 108%;text-align: left;">Admission Date: ………. Time: ………… Discharge Date: ………… Time: …………</p>
        </td>
    </tr>
    <tr style="height:24px">
        <td style="width:36px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px">
            <p class="s2" style="padding-left: 6px;text-indent: 0px;text-align: left;">Sl No</p>
        </td>
        <td style="width:150px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px">
            <p class="s2" style="padding-left: 49px;text-indent: 0px;text-align: left;">Particulars</p>
        </td>
        <td style="width:88px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px">
            <p class="s2" style="padding-left: 12px;text-indent: 0px;text-align: left;">Item code /ID</p>
        </td>
        <td style="width:118px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px">
            <p class="s2" style="padding-left: 47px;padding-right: 47px;text-indent: 0px;text-align: center;">Rate</p>
        </td>
        <td style="width:58px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px">
            <p class="s2" style="padding-left: 16px;text-indent: 0px;text-align: left;">Units</p>
        </td>
        <td style="width:70px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px">
            <p class="s2" style="padding-left: 13px;text-indent: 0px;text-align: left;">Amount₹</p>
        </td>
    </tr>
    @php
        $i =1;
    @endphp
    @foreach($data['service_item_data'] as $service)
    <tr style="height:84px">
        <td style="width:36px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px">
            <p style="text-indent: 0px;text-align: left;"><br/>{{ $i }}</p>
        </td>
        <td style="width:150px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px">
            <p style="text-indent: 0px;text-align: left;"><br/>{{ $service->item_name}}</p>
        </td>
        <td style="width:88px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px">
            <p style="text-indent: 0px;text-align: left;"><br/>{{ $service->item_code}}</p>
        </td>
        <td style="width:118px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px">
            <p style="text-indent: 0px;text-align: left;"><br/>
                @if(isset($service->item_amount))
                    {{ 'Rs ' . $service->item_amount}}
                @else
                    {{ '0' }}
                @endif</p>
        </td>
        <td style="width:58px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px">
            <p style="text-indent: 0px;text-align: left;"><br/>
                {{ $service->quantity}}
            </p>
        </td>
        <td style="width:70px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px">
            <p style="text-indent: 0px;text-align: left;"><br/>
                @if(isset($service->serviceitemamount))
                    {{ 'Rs' . $service->serviceitemamount}}
                @else
                    {{ '0' }}
                @endif
            </p>
        </td>
    </tr>
        @php
            $i++;
        @endphp
    @endforeach

    <tr style="height:14px">
        <td style="width:368px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px" colspan="4">
            <p class="s1" style="padding-right: 4px;text-indent: 0px;text-align: right;">Gross Amount</p>
        </td>
        <td style="width:152px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px" colspan="2">
            <p class="s7" style="padding-left: 5px;text-indent: 0px;text-align: left;">₹ {{ $data['billing_account_data']->Grossamount }}</p>
        </td>
    </tr>
    <tr style="height:14px">
        <td style="width:368px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px" colspan="4">
            <p class="s1" style="padding-right: 4px;text-indent: 0px;text-align: right;">Concession Amount</p>
        </td>
        <td style="width:152px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px" colspan="2">
            <p class="s7" style="padding-left: 5px;text-indent: 0px;text-align: left;">₹ {{ $data['billing_account_data']->Discamount }}</p>
        </td>
    </tr>
    <tr style="height:14px">
        <td style="width:368px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px" colspan="4">
            <p class="s1" style="padding-right: 4px;text-indent: 0px;text-align: right;">Net Amount</p>
        </td>
        <td style="width:152px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px" colspan="2">
            <p class="s7" style="padding-left: 5px;text-indent: 0px;text-align: left;">₹ {{ $data['billing_account_data']->NetAmount }}</p>
        </td>
    </tr>
    <tr style="height:15px">
        <td style="width:368px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px" colspan="4">
            <p class="s1" style="padding-right: 5px;text-indent: 0px;text-align: right;">Receipx Amount</p>
        </td>
        <td style="width:152px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px" colspan="2">
            <p class="s7" style="padding-left: 5px;text-indent: 0px;text-align: left;">₹ {{ $data['billing_account_data']->balance_amount }}</p>
        </td>
    </tr>
    <tr style="height:54px">
        <td style="width:520px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px" colspan="6">
            <p style="text-indent: 0px;text-align: left;"><br/></p>
            <p class="s6" style="padding-left: 5px;text-indent: 0px;text-align: left;">Bill Amount in words: ……………………………………………………………</p>
            <p class="s8" style="padding-top: 6px;padding-left: 5px;text-indent: 0px;text-align: left;">*This is a computerized Bill/Receipx</p>
        </td>
    </tr>
    <tr style="height:66px">
        <td style="width:520px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px" colspan="6">
            <p class="s3" style="padding-left: 294px;text-indent: 0px;line-height: 12px;text-align: left;">For Jothydev’s Diabetes Hospital &amp; Research Centre</p>
            <p style="text-indent: 0px;text-align: left;"><br/></p>
            <p class="s3" style="padding-top: 9px;padding-left: 419px;padding-right: 4px;text-indent: 25px;text-align: right;">Employee Name Authorised Signatory*</p>
        </td>
    </tr>
    <tr style="height:14px">
        <td style="width:520px;border-top-style:solid;border-top-width:1px;border-left-style:solid;border-left-width:1px;border-bottom-style:solid;border-bottom-width:1px;border-right-style:solid;border-right-width:1px" colspan="6">
            <p class="s3" style="padding-left: 200px;padding-right: 200px;text-indent: 0px;line-height: 12px;text-align: center;">Wish You Speedy Recovery</p>
        </td>
    </tr>
</table>
</body>
</html>
