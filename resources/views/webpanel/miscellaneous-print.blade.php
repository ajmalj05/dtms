<html>
<head>
    <title>JDC</title>
    <style>
        .common{
            font-family:Verdana, Arial, Helvetica, sans-serif;
            font-size: 12px;
        }
    </style>
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
            <h2><u>Miscellaneous</u></h2>
        </div>

        <table style="font-size:11px;font-family:Verdana, Arial, Helvetica, sans-serif;width:100%;border-collapse: collapse" border="0">
            <tr>
                <td style="width:15%;font-weight:bold">UHID No.</td>
                <td>:</td>
                <td align="left" style="font-weight:bold;width:40%;">{{ ! is_null($data['patient_data']) ? $data['patient_data']->uhidno : ''}}</td>
                <td width="5%" style="text-align:center">&nbsp;</td>
                <td style="width:15%;font-weight:bold" >Date</td>
                <td>:</td>
                <td align="left" style="font-weight:bold;width:18%;">{{ ! is_null($data['patient_data']) ? $data['patient_date'] : ''}}</td>
                </tr>
                <tr>
                    <td style="width:15%;font-weight:bold">Patient Name</td>
                    <td>:</td>
                    <td align="left" style="font-weight:bold;width:40%;">{{ ! is_null($data['patient_data']) ? $data['patient_data']->name : ''}}</td>
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
                        <td align="left" style="font-weight:bold;width:40%;">{{ ! is_null($data['patient_data']) ? $data['patient_data']->specialist_name : ''}}</td>
                        <td wid{{--            @foreach($data['patient_miscellaneous_details'] as $key => $miscellaneousData)--}}
{{--                @dd($miscellaneousData);--}}
{{--                <b>Relationship:</b> {{ $miscellaneousData->relation }} <br>--}}

{{--            @endforeach--}}
{{--            <b>Age: </b>  <br>--}}
{{--            <b>If expired, Age at death: </b>  <br>--}}
{{--            <b>Diabetes: </b>  QUestions  Details <br>--}}
{{--            <b>CAD: </b>  <br>--}}
{{--            <b>CKD: </b>  <br>--}}
{{--            <b>CVD: </b>  <br>--}}
{{--            <b>Amputation: </b>  <br>--}}
{{--            <b>Cancer: </b>  QUestions  Details <br>--}}
{{--            <b>Thyroid Disorders: </b>  QUestions  Details <br>--}}
{{--            <b>HTN: </b>  QUestions  Details <br>--}}
{{--            <b>Dyslipidemia: </b>--}}

th="5%" style="text-align:center">&nbsp;</td>
                        <td style="width:15%;font-weight:bold" >Token</td>
                        <td>:</td>
                        <td align="left" style="font-weight:bold;width:18%;">5</td>
                        </tr>
                        <tr>
                            <td style="width:15%;font-weight:bold">Address</td>
                            <td>:</td>
                            <td align="left" style="font-weight:bold;width:40%;">{{ ! is_null($data['patient_data']) ? $data['patient_data']->address : ''}}</td>
                            <td width="5%" style="text-align:center">&nbsp;</td>
                            <td style="width:15%;font-weight:bold" ></td>
                            <td></td>
                            <td align="left" style="font-weight:bold;width:18%;"></td>
                            </tr>
        </table>
        <hr>
        @php
            //print_r($data['type']);
            // print_r($data['answers']);


            // exit;
        @endphp

        <div id="qansarea">
            @foreach($data['question'] as $key => $questions)

                <b>Q : {{ $questions}}<br></b>
                @if(isset($data['answers'][$key]) && $data['answers'][$key] !="")
                        @if( ($data['type'][$key] == 'radio')  || ($data['type'][$key] == 'select'))
                            Ans. {{getAfeild('attr_value','formengine_attributes',array('id'=>$data['answers'][$key]));}} <br>

                        @elseif(($data['type'][$key] == 'date'))
                        Ans. {{ Carbon\Carbon::parse($data['answers'][$key])->format('d/m/Y'); }} <br>

                        @elseif(($data['type'][$key] == 'checkbox'))

                        {{-- $ans=1;
                        echo  $ans; --}}
                          <?php

                            $valuesArray = explode(',', $data['answers'][$key]);
                                        foreach ($valuesArray as $value) {
                                                $ans=getAfeild('attr_value','formengine_attributes',array('id'=>$value));

                                                echo "Ans. ".$ans." ,";
                                        }

                          ?>
                             <br>

                        @else
                            Ans. {!! $data['answers'][$key] !!} <br>
                        @endif
                @endif
            @endforeach
        </div>

        <div id="qansarea" style="padding-top: 10px;">
                <table align="center" style="border: 1px solid; font-size:15px;font-family:Verdana, Arial, Helvetica, sans-serif;width:50%;border-collapse: collapse">
                    <tr>
                        <th style="border: 1px solid;width:15%;font-weight:bold">Sl.No</th>
                        <th style="border: 1px solid;width:15%;font-weight:bold">Relationship</th>
                        <th style="border: 1px solid;width:15%;font-weight:bold">Age</th>
                        <th style="border: 1px solid;width:15%;font-weight:bold">If expired, Age at death</th>
                        <th style="border: 1px solid;width:15%;font-weight:bold">Diabetes</th>
                        <th style="border: 1px solid;width:15%;font-weight:bold">CAD</th>
                        <th style="border: 1px solid;width:15%;font-weight:bold">CKD</th>
                        <th style="border: 1px solid;width:15%;font-weight:bold">CVD</th>
                        <th style="border: 1px solid;width:15%;font-weight:bold">Amputation</th>
                        <th style="border: 1px solid;width:15%;font-weight:bold">Cancer</th>
                        <th style="border: 1px solid;width:15%;font-weight:bold">Thyroid Disorders</th>
                        <th style="border: 1px solid;width:15%;font-weight:bold">HTN</th>
                        <th style="border: 1px solid;width:15%;font-weight:bold">Dyslipidemia</th>

                    </tr>
                    @php
                        $i =1;
                    @endphp
                    @foreach($data['patient_miscellaneous_details'] as $miscellaneousData)
                        <tr style="border: 1px solid;" >
                            <td style="text-align:center; border: 1px solid;">{{ $i }}</td>
                            <td style="text-align:center; border: 1px solid; ">
                            @if(isset($miscellaneousData->relation))
                                {{ $miscellaneousData->relation }}
                            @else
                                {{ '' }}
                            @endif
                            <td style="text-align:center; border: 1px solid;">
                                @if(isset($miscellaneousData->age))
                                    {{ $miscellaneousData->age}}
                                @else
                                    {{ '' }}
                                @endif
                            </td>
                            <td style="text-align:center; border: 1px solid;">
                                @if(isset($miscellaneousData->expired_age))
                                    {{ $miscellaneousData->expired_age}}
                                @else
                                    {{ '' }}
                                @endif
                            </td>
                            <td style="text-align:center; border: 1px solid;">
                                @if(isset($miscellaneousData->diabetes))
                                    {{ $miscellaneousData->diabetes }}
                                @else
                                    {{ '' }}
                                @endif
                            </td>
                            <td style="text-align:center; border: 1px solid;">
                                @if(isset($miscellaneousData->cad))
                                    {{ $miscellaneousData->cad }}
                                @else
                                    {{ '' }}
                                @endif

                            </td>
                            <td style="text-align:center; border: 1px solid;">
                                @if(isset($miscellaneousData->ckd))
                                    {{ $miscellaneousData->ckd }}
                                @else
                                    {{ '' }}
                                @endif
                            </td>
                            <td style="text-align:center; border: 1px solid;">
                                @if(isset($miscellaneousData->cvd))
                                    {{ $miscellaneousData->cvd }}
                                @else
                                    {{ '' }}
                                @endif
                            </td>
                            <td style="text-align:center; border: 1px solid;">
                                @if(isset($miscellaneousData->amputation))
                                    {{ $miscellaneousData->amputation }}
                                @else
                                    {{ '' }}
                                @endif
                            </td>
                            <td style="text-align:center; border: 1px solid;">
                                @if(isset($miscellaneousData->cancer))
                                    {{ $miscellaneousData->cancer }}
                                @else
                                    {{ '' }}
                                @endif
                            </td>
                            <td style="text-align:center; border: 1px solid;">
                                @if(isset($miscellaneousData->thyroid))
                                    {{ $miscellaneousData->thyroid }}
                                @else
                                    {{ '' }}
                                @endif
                            </td>
                            <td style="text-align:center; border: 1px solid;">
                                @if(isset($miscellaneousData->htn))
                                    {{ $miscellaneousData->htn }}
                                @else
                                    {{ '' }}
                                @endif
                            </td>
                            <td style="text-align:center; border: 1px solid;">
                                @if(isset($miscellaneousData->dyslipidemia))
                                    {{ $miscellaneousData->dyslipidemia }}
                                @else
                                    {{ '' }}
                                @endif
                            </td>
                        </tr>
                        @php
                            $i++;
                        @endphp
                    @endforeach
                </table>

        </div>

        <div id="qansarea">
            <b>HEIGHT in cm:</b> {{ ! is_null($data['patient_miscellaneous']) ? $data['patient_miscellaneous']->height : ''}} <br>
            <b>WEIGHT in Kg:</b> {{ ! is_null($data['patient_miscellaneous']) ? $data['patient_miscellaneous']->weight : ''}} <br>
            <b>BMI:</b> {{ ! is_null($data['patient_miscellaneous']) ? $data['patient_miscellaneous']->bmi : ''}} <br>
            <b>GFR:</b> {{ ! is_null($data['patient_miscellaneous']) ? $data['patient_miscellaneous']->gfr : ''}} <br>

        </div>

        <hr>
        <div style="text-align: right">
            For Jothydev’s Diabetes Hospital  &amp;  <br>
            Research Centre<br>
            {{-- Anjana | 10.10.2022 <br> --}}
            <br>
            <br>
            Authorised Signatory*
        </div>
        <br>
        Wish You Speedy Recovery
</div>
</body>
</html>
