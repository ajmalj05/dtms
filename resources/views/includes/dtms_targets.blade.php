<?php
use App\Models\PatientVisits;
?>




<div class="text-center p-3">
    <table class="table custom-table customtable_color">
        <thead>
            <tr class="collapsebtn" data-toggle="collapse" href="#collapseVitals" role="button" aria-expanded="false" aria-controls="collapseVitals">
                <th>Vital Sign Name</th>
                <th>Value</th>
                <th>
                    <button type="button" class="btn btn-sm btn-primary" id="vital_print">
                        <i class="fa fa-print" aria-hidden="true"></i>
                    </button>
                </th>
            </tr>
        </thead>
        <tbody class="collapse" id="collapseVitals">
            <?php 
            $sl = 0; 
            
            // Retrieve creatinine values for each visit
            $visit_vitals = DB::table('patient_visits AS pv')
                ->join('test_results AS ts', 'ts.visit_id', '=', 'pv.id')
                ->select('pv.id', 'pv.visit_date', 'ts.TestId', 'ts.ResultValue', 'pv.patient_id')
                ->where('ts.TestId', '=', 21) // TestId 21 for creatinine
                ->where('pv.patient_id', '=', $patient_data->id)
                ->get();

            // Function to calculate eGFR
            function calculateEGFR($scr, $gender, $age) {
                if ($gender == 'f') {
                    $val = ($scr <= 0.7) ? -0.329 : -1.209; // Factor for female
                } elseif ($gender == 'm') {
                    $val = ($scr <= 0.9) ? -0.411 : -1.209; // Factor for male
                } else {
                    return null; // Invalid gender
                }

                // eGFR calculation formula
                $egfr = (144 * pow(($scr / 0.7), $val)) * pow(0.993, $age);
                return round($egfr, 2); // Round to 2 decimal places
            }

            // Gender determination
            $gender = null;
            if (isset($patient_data->gender) && $patient_data->gender != '') {
                if (str_contains($patient_data->gender, 'm')) {
                    $gender = 'm'; // Male
                } elseif (str_contains($patient_data->gender, 'f')) {
                    $gender = 'f'; // Female
                }
            }

            // Age calculation
            $age = null;
            if (isset($patient_data->dob) && !empty($patient_data->dob)) {
                $age = \Carbon\Carbon::parse($patient_data->dob)->diff(\Carbon\Carbon::now())->format('%y');
            }?>

        
            @foreach($vital_data as $item)
                <?php
                $sl++;

                if ($item['id'] == 78) {
                    // Find the creatinine value for the visit
                    $creatinine = $visit_vitals->where('TestId', 21)->first();

                    // Calculate eGFR if creatinine is available for this visit
                    if ($creatinine && $creatinine->ResultValue !== null) {
                        $egfr_value = calculateEGFR($creatinine->ResultValue, $gender, $age);
                    } else {
                        $egfr_value = ''; // No creatinine for this visit, no eGFR
                    }
                ?>
                    <tr>
                        <td>eGFR</td>
                        <td colspan="2">
                            <input type="text" class="form-control vital_id vital_sign_data custom-box" value="{{ $egfr_value }}" name="vital_{{ $item['id'] }}" id="vital_{{ $item['id'] }}" readonly>
                        </td>
                    </tr>
                <?php
                } elseif ($sl == 1) {
                ?>
                    <tr>
                        <td>BP</td>
                        <td>
                            <input type="text" placeholder="{{ $item['vital_name'] }}" class="form-control vital_id vital_sign_data custom-box" onChange="CalculateVitalBMI();" name="vital_{{ $item['id'] }}" id="vital_{{ $item['id'] }}">
                        </td>
                <?php
                } elseif ($sl == 2) {
                ?>
                    <td>
                        <input type="text" placeholder="{{ $item['vital_name'] }}" class="form-control vital_id vital_sign_data custom-box" onChange="CalculateVitalBMI();" name="vital_{{ $item['id'] }}" id="vital_{{ $item['id'] }}">
                    </td>
                    </tr>
                <?php
                } else {
                ?>
                    <tr>
                        <td>{{ $item['vital_name'] }}</td>
                        <td colspan="2">
                            <input type="text" class="form-control vital_id vital_sign_data custom-box" onChange="CalculateVitalBMI();" name="vital_{{ $item['id'] }}" id="vital_{{ $item['id'] }}">
                        </td>
                    </tr>
                <?php
                }
                ?>
            @endforeach
        </tbody>
    </table>
</div>




        <!--<div class="text-center p-3 ">
            <table class="table custom-table customtable_color">
                <thead>
                    <tr class="collapsebtn" data-toggle="collapse" href="#collapseBPS" role="button" aria-expanded="false" aria-controls="collapseBPS">
                        <th colspan="4">Outside Results</th>

                    </tr>
                    <tr class="collapsebtn" data-toggle="collapse" href="#collapseVitals" role="button" aria-expanded="false" aria-controls="collapseVitals">
                    <th>Test Name</th>
                    <th>Value</th>
                    <th></th>
                    </tr>
                </thead>
                <tbody class="collapse" id="collapseVitals">
                    
                @foreach($vital_data as $item)
                <?php
                    $sl++;
                    if($sl==1)
                    {
                        ?>
                        <tr>
                            <td>BP</td>
                             <td><input type="text" placeholder="{{ $item['vital_name'] }}" class="form-control vital_id vital_sign_data vital_{{ $item['class_name'] }} custom-box" onChange="CalculateVitalBMI();" name="vital_{{ $item['id']}}" id="vital_{{ $item['id'] }}" >
                            </td>

                        <?php
                    }
                    else   if($sl==2)
                    {
                        ?>
                         <td><input type="text"  placeholder="{{ $item['vital_name'] }}" class="form-control vital_id vital_sign_data vital_{{ $item['class_name'] }} custom-box" onChange="CalculateVitalBMI();" name="vital_{{ $item['id']}}" id="vital_{{ $item['id'] }}" >
                         </td>

                        </tr>
                        <?php
                    }
                    else{
                        ?>
                         <tr>
                            <td>{{ $item['vital_name'] }}</td>
                            <td  colspan="2">

                                    <input type="text" class="form-control vital_id vital_sign_data vital_{{ $item['class_name'] }} custom-box" onChange="CalculateVitalBMI();" name="vital_{{ $item['id']}}" id="vital_{{ $item['id'] }}" >

                            </td>
                        </tr>
                        <?php
                    }
                ?>

                @endforeach


                </tbody>
            </table>
        </div>-->
        <div class="text-center p-3 ">
            <table class="table custom-table customtable_color">
                <thead>
                    <tr class="collapsebtn" data-toggle="collapse" href="#collapseBPS" role="button" aria-expanded="false" aria-controls="collapseBPS">
                        <th colspan="4">BP Status</th>

                    </tr>
                    <tr>
                        <th>Time</th>
                        <th>BP's</th>
                        <th>BP'D</th>
                        <th>Pulse</th>
                    </tr>
                </thead>
                <tbody class="collapse" id="collapseBPS">
                    <tr>
                        <td><input type="text" class="form-control custom-box" name="bps_status_time_1" id="bps_status_time_1"></td>
                        <td><input type="text" class="form-control custom-box" name="bps_status_bps_1" id="bps_status_bps_1"></td>
                        <td><input type="text" class="form-control custom-box" name="bps_status_bpd_1" id="bps_status_bpd_1"></td>
                        <td><input type="text" class="form-control custom-box" name="bps_status_pulse_1" id="bps_status_pulse_1"></td>
                    </tr>
                    <tr>
                        <td><input type="text" class="form-control custom-box" name="bps_status_time_2" id="bps_status_time_2"></td>
                        <td><input type="text" class="form-control custom-box" name="bps_status_bps_2" id="bps_status_bps_2"></td>
                        <td><input type="text" class="form-control custom-box" name="bps_status_bpd_2" id="bps_status_bpd_2"></td>
                        <td><input type="text" class="form-control custom-box" name="bps_status_pulse_2" id="bps_status_pulse_2"></td>
                    </tr>
                    <tr>
                        <td><input type="text" class="form-control custom-box" name="bps_status_time_3" id="bps_status_time_3"></td>
                        <td><input type="text" class="form-control custom-box" name="bps_status_bps_3" id="bps_status_bps_3"></td>
                        <td><input type="text" class="form-control custom-box" name="bps_status_bpd_3" id="bps_status_bpd_3"></td>
                        <td><input type="text" class="form-control custom-box" name="bps_status_pulse_3" id="bps_status_pulse_3"></td>
                    </tr>
                    <tr>
                        <td><input type="text" class="form-control custom-box" name="bps_status_time_4" id="bps_status_time_4"></td>
                        <td><input type="text" class="form-control custom-box" name="bps_status_bps_4" id="bps_status_bps_4"></td>
                        <td><input type="text" class="form-control custom-box" name="bps_status_bpd_4" id="bps_status_bpd_4"></td>
                        <td><input type="text" class="form-control custom-box" name="bps_status_pulse_4" id="bps_status_pulse_4"></td>
                    </tr>

                </tbody>
            </table>
        </div>

        <script>
            function CalculateVitalBMI(){
                var height=$('.vital_height').val();
                var weight=$('.vital_weight').val();
                $('.vital_bmi').val('');

                if(height !="" &&  weight!=""){

                    heightinMeter=height/100;

                    var vitalBmi= weight / (heightinMeter*heightinMeter);
                    $('.vital_bmi').val(vitalBmi.toFixed(2));

                }
            }
    
</script>