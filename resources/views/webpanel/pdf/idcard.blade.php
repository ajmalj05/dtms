<barcode code="{{$patient_data[0]['id']}}" size="0.9" type="QR" error="M" class="" />
<p>Name:{{$patient_data[0]['name']}}</p>
<p>Mobile:{{$patient_data[0]['mobile_number']}}</p>
<p>Email:{{ ! is_null($patient_data[0]['email']) ? $patient_data[0]['email'] . $patient_data[0]['extension'] : ''}}</p>

