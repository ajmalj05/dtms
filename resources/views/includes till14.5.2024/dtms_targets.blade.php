
        <div class="text-center p-3 ">
            <table class="table custom-table customtable_color">
                <thead>
                <tr class="collapsebtn" data-toggle="collapse" href="#collapseVitals" role="button" aria-expanded="false" aria-controls="collapseVitals">
                    <th>Vital Sign Name</th>
                    <th>Value</th>
                    <th><button type="button" class="btn btn-sm btn-primary" id="vital_print"><i class="fa fa-print" aria-hidden="true"></i></button></th>
                </tr>
                </thead>
                <tbody class="collapse" id="collapseVitals">
                    <?php
                        $sl=0;
                    ?>
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
        </div>

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
