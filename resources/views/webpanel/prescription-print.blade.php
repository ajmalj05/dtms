<html>
<head>
    <style>
        .common{
            font-family:Verdana, Arial, Helvetica, sans-serif;
            font-size: 12px;
        }
        body {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        #secondTable {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        #secondTable th, #secondTable td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        #secondTable th:first-child, #secondTable td:first-child {
            width: 10%;
        }
        #secondTable th:nth-child(2), #secondTable td:nth-child(2) {
            width: 60%;
        }
        #secondTable th:nth-child(3), #secondTable td:nth-child(3) {
            width: 10%;
        }
        #secondTable th:nth-child(4), #secondTable td:nth-child(4) {
            width: 20%;
        }
        #secondTable td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        @media screen and (max-width: 600px) {
            #secondTable th, #secondTable td {
                padding: 5px;
            }
        }
    </style>
   
    </style>
    <title>JDC</title>

</head>
<body>
<div class="common">
@if ($data['print_model']==0)
    <div style="font-family:Verdana, Arial, Helvetica, sans-serif;width:100%;text-align: center;font-size:12px">
        <img width="218" height="67" src="./images/company-logo.png">
        <br> <br>
        <b> {{$data['branch_details']->address_line_1}}<br>
        {{$data['branch_details']->address_line_2}}.</b> <br>
        Phone: {{$data['branch_details']->phone}}, Email : {{$data['branch_details']->email}}<br>
        {{$data['branch_details']->tag_line}}
    </div>

 
    <hr>
    <div style="text-align: center">
      
        <h2><u></u></h2>
    </div>
    @else
    <div style="height: 170px">

    </div>

    @endif

    <table style="font-size:11px;font-family:Verdana, Arial, Helvetica, sans-serif;width:100%;border-collapse: collapse" border="0">
        <tr>
            <td style="width:15%;font-weight:bold">UHID No.</td>
            <td>:</td>
            <td align="left" style="font-weight:bold;width:40%;">{{ ! is_null($data['patient_data']) ? $data['patient_data']->uhidno : ''}}</td>
            <td width="5%" style="text-align:center">&nbsp;</td>
            <td style="width:15%;font-weight:bold" >Date</td>
            <td>:</td>
                     <td align="left" style="font-weight:bold;width:18%;"> {{ ! is_null($data['visit_date']) ? $data['visit_date'] : ''}}</td>
                    
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
            <td align="left" style="font-weight:bold;width:40%;">{{ ! is_null($data['patient_data'])  ? $data['doctor_name'] : ''}}</td>
            <td width="5%" style="text-align:center">&nbsp;</td>
            
        </tr>
    </table>
    <hr>

    <div id="qansarea">
        <table style="font-size:11px;font-family:Verdana, Arial, Helvetica, sans-serif;width:100%;">
            <thead>
            <tr>
                <th align="left" width="10%">Sl No</th>
                <th align="left" width="60%">Medicines</th>
                <th align="left" width="10%">Dose</th>
                <th align="left" width="20%">Remarks</th>
            </tr>
            </thead>
            <tbody class="">
            @php
                $i =1;
            @endphp
            @foreach($data['prescriptionData'] as $prescription)
                <tr>
                <td align="left">{{ $i }}</td>
                <td align="left">{{$prescription->tab}}{{ $prescription->medicine_name}}<br>{{ $prescription->generic_name}} </td>
                <td align="left">{{ $prescription->dose }}</td>
                <td align="left">{{ $prescription->remarks }}</td>
                </tr>
            @php
                $i++;
            @endphp
            @endforeach

            </tbody>
        </table>



    </div>

    <hr>
    <div style="text-align: right">
        For Jothydev’s Diabetes Hospital  &amp;  <br>
        Research Centre<br>
        <br><br><br>
        Authorised Signatory*
    </div>
    <br>
    Wish You Speedy Recovery
</div>
</body>
</html>
