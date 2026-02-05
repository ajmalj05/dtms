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
    <div style="font-family:Verdana, Arial, Helvetica, sans-serif;width:100%;text-align: center;font-size:12px">
        <img width="218" height="67" src="./images/company-logo.png">
         <br> <br>
         <b> JDC Junction, Mudavanmugal, Konkalam Road,<br>
             Trivandrum, Kerala, India, Pin. 695032.</b> <br>
             Phone: 0471-2356200, 9846040055, Email : info@jothydev.net
    </div>

         (A Project of Living Longer Life Care Pvt. Ltd)
    <hr>
        <div style="text-align: center">
            <h2><u>Pep History</u></h2>
        </div>

        <table style="font-size:11px;font-family:Verdana, Arial, Helvetica, sans-serif;width:100%;border-collapse: collapse" border="0">
            <tr>
                <td style="width:15%;font-weight:bold">UHID No.</td>
                <td>:</td>
                <td align="left" style="font-weight:bold;width:40%;">{{ ! is_null($data['patient_data']) ? $data['patient_data']->uhidno : ''}}</td>
                <td align="left" style="font-weight:bold;width:40%;"></td>
                <td width="5%" style="text-align:center">&nbsp;</td>
                <td style="width:15%;font-weight:bold" >Date</td>
                <td>:</td>
                <td align="left" style="font-weight:bold;width:18%;">{{ ! is_null($data['patient_data']) ? $data['patient_date'] : ''}}</td>
                <td align="left" style="font-weight:bold;width:18%;"></td>
                </tr>
                <tr>
                    <td style="width:15%;font-weight:bold">Patient Name</td>
                    <td>:</td>
                    <td align="left" style="font-weight:bold;width:40%;">{{ ! is_null($data['patient_data']) ? $data['patient_data']->name : ''}}</td>
                    <td align="left" style="font-weight:bold;width:40%;"></td>
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
                    <td align="left" style="font-weight:bold;width:18%;">

                    </td>
                    </tr>

                    <tr>
                        <td style="width:15%;font-weight:bold">Dr Name</td>
                        <td>:</td>
                        <td align="left" style="font-weight:bold;width:40%;">{{ ! is_null($data['patient_data']) ? $data['patient_data']->specialist_name : ''}}</td>
                        <td align="left" style="font-weight:bold;width:40%;"></td>
                        <td width="5%" style="text-align:center">&nbsp;</td>
                        <td style="width:15%;font-weight:bold" ></td>
                        <td>:</td>
                        <td align="left" style="font-weight:bold;width:18%;"></td>
                        </tr>
                        <tr>
                            <td style="width:15%;font-weight:bold">Address</td>
                            <td>:</td>
                            <td align="left" style="font-weight:bold;width:40%;">{{ ! is_null($data['patient_data']) ? $data['patient_data']->adddress : ''}}</td>
                            <td width="5%" style="text-align:center">&nbsp;</td>
                            <td style="width:15%;font-weight:bold" ></td>
                            <td></td>
                            <td align="left" style="font-weight:bold;width:18%;"></td>
                            </tr>
        </table>
        <hr>

        <div id="qansarea">
{{--            @php--}}
{{--                $i =1;--}}
{{--            @endphp--}}
            @foreach($data['pep_history'] as $pepHistory)
                <b>Q.{{ $pepHistory->questions_id }} :</b>
                {{$pepHistory->question}} <br>
                <b>Ans. {{$pepHistory->answer}}</b>
{{--                @foreach($questions->sub_question as $label)--}}
{{--                    @if($data['diet_history'])--}}
{{--                        @for($j=0;$j<count($data['diet_history']);$j++)--}}

{{--                              {{($data['diet_history'][$j]['dietplan_answer']==$label->id) ? $label->label : "" }}--}}
{{--                        @endfor--}}

{{--                    @endif--}}
{{--                @endforeach--}}
                <br>
{{--                @php--}}
{{--                    $i++;--}}
{{--                @endphp--}}
            @endforeach
{{--            <b>Q.1 :</b>  QUestions  Details <br>--}}
{{--            <b>Ans . </b>  <br>--}}
{{--            <b>Q.2 :</b>  QUestions  Details <br>--}}
{{--            <b>Ans . </b>  <br>--}}
{{--            <b>Q.3 :</b>  QUestions  Details <br>--}}
{{--            <b>Ans . </b>--}}
        </div>

        <hr>
        <div style="text-align: right">
            For Jothydev’s Diabetes Hospital  &amp;  <br>
            Research Centre<br>
            Anjana | 10.10.2022 <br>
            Authorised Signatory*
        </div>
        <br>
        Wish You Speedy Recovery
</div>
</body>
</html>
