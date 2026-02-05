<div class="row">
    {{-- <div class="col-md-2">
        <a href="" class="btn btn-primary btnexpand" ><i class="fa fa-long-arrow-left"></i>
        </a>

    </div> --}}
    <div class="col-md-12">
        <p class="btn btn-primary btnexpand" >
            <span class="bar-details" style="font-size: 20px;" id="displayIcon">
                @if (isset($patient_data->category_name))
                    @if($patient_data->category_name != '')
                        {{$patient_data->category_name}} &nbsp;&nbsp;| &nbsp;&nbsp;
                    @endif
                @endif

                <?php
                if(isset($patient_data->patient_type))
                {
                    $cond=['id' => $patient_data->patient_type];
                    $p_typevalue=getAfeild("patient_type_name","patient_type_master", $cond);

                    echo $p_typevalue." | ";
                }

                ?>

                {{ $patient_data->uhidno }} &nbsp;&nbsp;| &nbsp;&nbsp; {{$patient_data->salutation_name}}{{$patient_data->name}} {{$patient_data->last_name}}



                @if (isset($patient_data->dob))
                    @if($patient_data->dob != '')
                            &nbsp;&nbsp;| &nbsp;&nbsp; {{ \Carbon\Carbon::parse($patient_data->dob)->diff(\Carbon\Carbon::now())->format('%y') }} yrs/
                    @endif
                @endif




                @if (isset($patient_data->gender))
                    @if($patient_data->gender != '')
                        @if(str_contains($patient_data->gender, 'm'))
                            {{ 'Male' }}
                        @elseif (str_contains($patient_data->gender, 'f'))
                            {{ 'Female' }}
                        @elseif (str_contains($patient_data->gender, 'o')){
                            {{ 'Others' }}
                        @else
                            {{ 'NA' }}
                        @endif
                    @endif
                @endif

                @if (isset($vital_height->vitals_value))
                    @if($vital_height->vitals_value != '')
                    &nbsp;&nbsp;| &nbsp; {{ $vital_height->vitals_value . 'cm' }}
                    @endif
                @endif

                @if (isset($vital_weight->vitals_value))
                    @if($vital_weight->vitals_value != '')
                        &nbsp;&nbsp;| &nbsp;&nbsp; {{$vital_weight->vitals_value . 'kg'}}
                    @endif
                @endif
                <?php 
                                                    // Fetch the latest height value for the patient
                                                    $latest_height = DB::table('patient_vitals')
                                                    ->where('patient_id', $patient_data->id)
                                                    ->where('vitals_id', 1) // Assuming vital_id 10 corresponds to height
                                                    ->orderBy('created_at', 'desc')
                                                    ->value('vitals_value');

                                                    if ($latest_height !== null) {
                                                    echo "&nbsp;&nbsp|&nbsp;&nbsp; Height: " . $latest_height."&nbspcm";
                                                    } else {
                                                    echo "&nbsp;&nbsp|&nbspHeight not recorded";
                                                    }
                                                ?>
                                                <?php 
                                                // Fetch the latest height value for the patient
                                                $latest_weight = DB::table('patient_vitals')
                                                ->where('patient_id', $patient_data->id)
                                                ->where('vitals_id', 2) // Assuming vital_id 10 corresponds to height
                                                ->orderBy('created_at', 'desc')
                                                ->value('vitals_value');

                                                if ($latest_weight !== null) {
                                                echo "&nbsp;&nbsp|&nbsp;&nbsp; Weight: " . $latest_weight."&nbspkg";
                                                } else {
                                                echo "&nbsp;&nbsp|&nbspweight not recorded";
                                                }
                                                ?>
                                                <?php 
                                                // Fetch the latest height value for the patient
                                                $latest_bmi = DB::table('patient_vitals')
                                                ->where('patient_id', $patient_data->id)
                                                ->where('vitals_id', 3) // Assuming vital_id 10 corresponds to eight
                                                ->orderBy('created_at', 'desc')
                                                ->value('vitals_value');

                                                if ($latest_bmi !== null) {
                                                echo "&nbsp;&nbsp|&nbsp;&nbsp; BMI: " . $latest_bmi."&nbspkg/m2";
                                                } else {
                                                echo "&nbsp;&nbsp|&nbsp BMI not recorded";
                                                }
                                                ?>
@if (isset($patient_data->gender) && $patient_data->gender != '')
    @if (str_contains($patient_data->gender, 'm'))
        @php
            $gender_display = 'Male';
            $gender = 'm';
        @endphp
    @elseif (str_contains($patient_data->gender, 'f'))
        @php
            $gender_display = 'Female';
            $gender = 'f';
        @endphp
    @elseif (str_contains($patient_data->gender, 'o'))
        @php
            $gender_display = 'Others';
        @endphp
    @else
        @php
            $gender_display = 'NA';
        @endphp
    @endif
@endif
@php
    $age = null;
    if (isset($patient_data->dob) && $patient_data->dob != '') {
        $age = \Carbon\Carbon::parse($patient_data->dob)->diff(\Carbon\Carbon::now())->format('%y');
    }
@endphp




<?php 
$age = null;
if (isset($patient_data->dob) && !empty($patient_data->dob)) {
    $age = \Carbon\Carbon::parse($patient_data->dob)->diff(\Carbon\Carbon::now())->format('%y');
}
$scr = DB::table('test_results')
    ->where('PatientId', $patient_data->id)
    ->where('TestId', 21) // TestId for serum creatinine
    ->orderBy('created_at', 'desc')
    ->value('ResultValue');

// Calculate eGFR if scr and gender are available
$egfr_value = null;

if ($scr !== null && $age !== null && $gender !== null) {
    if ($gender == 'f') {
        $val = ($scr <= 0.7) ? -0.329 : -1.209;
    } else if ($gender == 'm') {
        $val = ($scr <= 0.9) ? -0.411 : -1.209;
    }

    $egfr = (144 * pow(($scr / 0.7), $val)) * pow(0.993, $age);
    $egfr_value = round($egfr, 2);

}

// Display calculated eGFR
if ($egfr_value !== null) {
    echo "&nbsp;&nbsp|&nbsp;&nbsp; eGFR: " . $egfr_value . " mL/min/1.73m²";
} else {
    echo "&nbsp;&nbsp|&nbsp;&nbsp; eGFR not calculated";
}?>





                                                <script>
                                                         $(document).ready(function(){
                                                         patientLoadImage(); // Call patientLoadImage() after BMI information is rendered
                                                         });
                                                </script>
            </span>

            <!-- <a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
               <i class="fa fa-gear fa-spin fagear"></i>
           </a> -->
            <!-- <span class="back-arrow" onclick="gobacktoHome(event)"><i class="fa fa-long-arrow-left"></i></span> -->
        </p>
        <p class="btn btn-primary btnexpand diagnosis-info-block">
            <span class="bar-details" id='diagnosis-info' style="font-size: 14px;">
            </span>
                <!-- <a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                <i class="fa fa-gear fa-spin fagear"></i>
               </a> -->
            <!-- <span class="back-arrow" onclick="gobacktoHome(event)"><i class="fa fa-long-arrow-left"></i></span> -->
        </p>
        <p class="btn btn-primary btnexpand complication-info-block">
            <span class="bar-details" id="complication-info"  style="font-size: 14px;">

            </span>

            <!-- <a data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                <i class="fa fa-gear fa-spin fagear"></i>
            </a> -->
            <!-- <span class="back-arrow" onclick="gobacktoHome(event)"><i class="fa fa-long-arrow-left"></i></span> -->
        </p>
        <div class="collapse" id="collapseExample">
            <div class="card card-body">
                <div class="row">
                    <div class="col-md-4">
                         <table class="table table-profile">
                        <tr>
                            <td>Name:</td>
                            <td>{{$patient_data->name}}</td>
                        </tr>
                        <tr>
                            <td>UHID No:</td>
                            <td>{{$uhidNo}}</td>
                        </tr>
                        <tr>
                            <td>Gender:</td>
                            <td>
                                @php
                                    if(str_contains($patient_data->gender, 'm') ) {
                                        echo "Male";
                                    }else if(str_contains($patient_data->gender, 'f')){
                                        echo "Feimale";
                                    }else if(str_contains($patient_data->gender, 'o')){
                                        echo "Others";
                                    }else{
                                        echo 'NA';
                                    }


                                @endphp
                            </td>

                        </tr>
                        <tr>
                            <td>DOB:</td>
                            <td>{{$patient_data->dob}}</td>
                        </tr>
                        <tr>
                            <td>Age:</td>
                            <td>{{ \Carbon\Carbon::parse($patient_data->dob)->diff(\Carbon\Carbon::now())->format('%y') }} yrs</td>
                        </tr>
                        <tr>
                            <td>Mobile No:</td>
                            <td>{{$patient_data->mobile_number}}</td>
                        </tr>
                </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table table-profile">
                            <tr>
                            <td>Diagnosis:</td>
                            <td id='diagnosis-info'></td>
                        </tr>
{{--                    <tr>--}}
{{--                        <td>Sub Complication:</td>--}}
{{--                        <td id="sub-complication-info"></td>--}}
{{--                    </tr>--}}

                         </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table table-profile">
                        <tr>
                            <td>Complication:</td>
                            <td id="complication-info"></td>
                        </tr>
                         </table>

                    </div>
            </div>
            </div>
        </div>
    </div>
</div>
<style>
    .table-profile td{
        border-top:0px;
    }
    .table-profile{
        width: 100%;
    }
    .back-arrow{
        float: right;
        cursor: pointer;
    }
    .bar-details{
        float: left;
        font-size: 12px;
    }
    .fagear{
        color:#fff;
    }
</style>
<script>
    function gobacktoHome(e){
        e.preventDefault();
        window.location.href = '{{url("dtmshome")}}/'+{{$patient_data->id}};


    }
    function patientLoadImage(){
    var patient_id = $('#patient_id').val();
    $.ajax({
        url: "{{ route('getIconPatientdiet') }}",
        type: 'POST',
        data: { patient_id : patient_id },
        success : function(result) {
            if (result.drinking == "YES") {
                $("#displayIcon").append('<span class="pl-2"> <img src="/images/iconImage/drinkingjdc.png" alt=""></span>');
            }
            if (result.smoking == "YES") {
                $("#displayIcon").append('<span class="pl-2"><img src="/images/iconImage/cigaratjdc.png" alt=""></span>');
            }
            if (result.pump == "YES") {
                $("#displayIcon").append('<span class="pl-2"> <img src="/images/iconImage/pump1.png" alt=""></span>');
            }
            if (result.drinking !== "YES" && result.smoking !== "YES" && result.pump !== "YES") {
                console.log("No icons available for this patient.");
            }
        },
        error: function(xhr, status, error) 
        {
           console.error("Error fetching icon:", error); // Log any errors to console
        }
        
    });
}


</script>
