<html>
<head>
    <style>
        .common{
            font-family:Verdana, Arial, Helvetica, sans-serif;
            font-size: 12px;
        }
    </style>
    <title>JDC</title>

</head>
<body>
<div class="common">
    {{-- <div style="font-family:Verdana, Arial, Helvetica, sans-serif;width:100%;text-align: center;font-size:12px">
        <img width="218" height="67" src="./images/company-logo.png">
        <br> <br>
        <b> JDC Junction, Mudavanmugal, Konkalam Road,<br>
            Trivandrum, Kerala, India, Pin. 695032.</b> <br>
        Phone: 0471-2356200, 9846040055, Email : info@jothydev.net
    </div>

    (A Project of Living Longer Life Care Pvt. Ltd)
    <hr>
    <div style="text-align: center">
       <h2><u>Vital signs</u></h2>
        <h2><u></u></h2>
    </div> --}}

    <table style="font-size:11px;font-family:Verdana, Arial, Helvetica, sans-serif;width:100%;border-collapse: collapse" border="0">
        <tr>
            <td style="width:15%;font-weight:bold">UHID No.</td>
            <td>:</td>
            <td align="left" style="font-weight:bold;width:40%;">{{ ! is_null($data['patient_data']) ? $data['patient_data']->uhidno : ''}}</td>
            <td width="5%" style="text-align:center">&nbsp;</td>
            <td style="width:15%;font-weight:bold" >Date</td>
            <td>:</td>
{{--            <td align="left" style="font-weight:bold;width:18%;">{{ ! is_null($data['patient_data']) ? $data['patient_date'] : ''}}</td>--}}
            <td align="left" style="font-weight:bold;width:18%;">{{ date('d-m-Y') }}</td>
        </tr>
        <tr>
            <td style="width:15%;font-weight:bold">Patient Name</td>
            <td>:</td>
            <td align="left" style="font-weight:bold;width:40%;">{{ ! is_null($data['patient_data']) ? $data['patient_data']->name : ''}}   {{ ! is_null($data['patient_data']) ? $data['patient_data']->last_name : ''}} &nbsp; / &nbsp; {{ ! is_null($data['patient_data']) ? $data['patient_data']->patient_type_name: ''}}
        
        
        </td>
            <td width="5%" style="text-align:center">&nbsp;</td>
            <td style="width:15%;font-weight:bold" >Age/Sex</td>
            <td>:</td>
            <td align="left" style="font-weight:bold;width:18%;">{{ ! is_null($data['patient_data']) ? \Carbon\Carbon::parse($data['patient_data']->dob)->diff(\Carbon\Carbon::now())->format('%y') . ' yrs' : ''}}
                @if (isset($data['patient_data']))
                    &nbsp; / &nbsp;
                    @if($data['patient_data']->gender != '')
                        @if(str_contains($data['patient_data']->gender, 'm'))
                            {{ 'Male' }}
                        @elseif (str_contains($data['patient_data']->gender, 'f'))
                            {{ 'Female' }}
                        @elseif (str_contains($data['patient_data']->gender, 'o'))
                            {{ 'Others' }}
                        @else
                            {{ 'NA' }}
                        @endif
                    @endif
                @endif
            </td>
        </tr>

        <tr>
            <td style="width:15%;font-weight:bold">Dr Name</td>
            <td>:</td>
            <td align="left" style="font-weight:bold;width:40%;">{{ ! is_null($data['doctor_name']) ? $data['doctor_name'] : ''}}</td>
            <td width="5%" style="text-align:center">&nbsp;</td>
            <td style="width:15%;font-weight:bold" >Token</td>
            <td>:</td>
            <td align="left" style="font-weight:bold;width:18%;"> </td>
        </tr>
        {{-- <tr>
            <td style="width:15%;font-weight:bold">Address</td>
            <td>:</td>
            <td align="left" style="font-weight:bold;width:40%;">{{ ! is_null($data['patient_data']) ? $data['patient_data']->address : ''}}</td>
            <td width="5%" style="text-align:center">&nbsp;</td>
            <td style="width:15%;font-weight:bold" ></td>
            <td></td>
            <td align="left" style="font-weight:bold;width:18%;"></td>
        </tr> --}}
    </table>
    <hr>

    <div id="qansarea">
{{--        @php--}}
{{--            $i =1;--}}
{{--        @endphp--}}
        <?php
            $sl=0;
            $bpString="";
        ?>
        @foreach($data['vitals'] as $vital)

        <?php
        /*
            $sl++;

            if($sl==1 && $vital->vital_id==6)  //bps  bpd Adjustment 26/04/2023 BP Single Line Update
            {
                $bpString.="<b>BP : </b><b>{$vital->vitals_value}/";
                // echo "<b>BP : </b><b>{$vital->vitals_value}/";

            }
            else  if($sl==2 && $vital->vital_id==7) // bpd Adjustment
            {

                echo $bpString."$vital->vitals_value"." mmHg &nbsp;";
                // echo trim($vital->vitals_value, ' ') . "</b>";


            }
            else{
                ?>
                 <b>{{$vital->vital_name}} :</b>
                 <b>{{$vital->vitals_value}} <?php echo ($vital->unit_name)?> </b>&nbsp;
                <?php
            }
*/
        
$sl++;

if ($sl == 1 && $vital->vital_id == 6) { // Check for BP Systolic (bps)
    $bpString .= "<b>BP : </b><b>{$vital->vitals_value}/";
} elseif ($sl == 2 && $vital->vital_id == 7) { // Check for BP Diastolic (bpd)
    echo $bpString . "$vital->vitals_value mmHg &nbsp;";
} elseif ($vital->vital_id >= 1 && $vital->vital_id <= 10) { // Check if vital ID is between 1 and 10
    ?>
    <b>{{ $vital->vital_name }} :</b>
    <b>{{ $vital->vitals_value }} <?php echo ($vital->unit_name) ?></b>&nbsp;
    <?php
}            
        ?>


{{--            @php--}}
{{--                $i++;--}}
{{--            @endphp--}}
        @endforeach
        {{--            <b>Q.1 :</b>  QUestions  Details <br>--}}
        {{--            <b>Ans . </b>  <br>--}}
        {{--            <b>Q.2 :</b>  QUestions  Details <br>--}}
        {{--            <b>Ans . </b>  <br>--}}
        {{--            <b>Q.3 :</b>  QUestions  Details <br>--}}
        {{--            <b>Ans . </b>--}}
    </div>

    <hr>

    <div id="content" style="height: 560px">

    </div>

    <div style="text-align: right">
       <!-- For Jothydev’s Diabetes Hospital  &amp;  <br>
        Research Centre<br>
        <br>
        Authorised Signatory*-->
    </div>
    <br>
    Wish You Speedy Recovery
</div>
</body>
</html>
