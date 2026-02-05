<html>
<head>
    <style>
        .common{
            font-family:Verdana, Arial, Helvetica, sans-serif;
            font-size: 12px;
        }
        .table_custom
        {
            font-size:14px;font-family:'Times New Roman', Times, serif;
            width:100%;
            border-collapse: collapse;
            border-spacing: 10px;

        }
        .border
        {
            border: 1px solid #000;
            padding: 5px;
        }
        .table_custom td {
  padding: 10px; /* Adjust this value to set the desired spacing between rows */
}
    </style>
    <title>JDC</title>

</head>
<body>
<div class="common">
    <div style="text-align: center;font-size:16px">
        <b><u>  DISCHARGE SUMMARY</u> </b>
    </div>

    <div style="font-size: 13px;font-family: 'Times New Roman', Times, serif;">

        <table class="table_custom">
            <tr>
                <td style="width: 25%"> Name</td>
                <td > :  {{$data['patientData']->name}} </td>
            </tr>
            <tr>
                <td> Age </td>
                <td> :  {{$data['age']}} </td>
            </tr>
            <tr>
                <td> Sex</td>
                <td> : @php
                    $gender=trim(strtoupper ($data['patientData']->gender));
                    if($gender=="M") echo "Male";
                    else if($gender=="F") echo "Female";
                    else echo "Other";
                @endphp</td>
            </tr>
            <tr>
                <td> Date of Admission</td>
                <td> :  {{$data['ad_date']}} </td>
            </tr>

            <tr>
                <td> Date of Discharge:</td>
                <td> :  {{$data['dc_date']}} </td>
            </tr>


        </table>

    </div>

        <div>
            <?php
                echo     $data['admission_data']->discharge_summary;
            ?>

        </div>

        <br>
        <br>
        Prepared by:  <br>
        <div style="text-align: right">
            Doctor’s Name <br>  <br>
Designation  <br> <br>
E-signature  <br> <br>
        </div>
</div>
</body>
</html>
