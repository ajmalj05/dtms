
        <div class="text-center target-edit p-3 ">
            <table class="table custom-table customtable_color">
                <thead >
                <tr >
                    <th colspan="2"  class="collapsebtn"  role="button" > Targets</th>
                    <th><a href="#" class="" onclick="addTarget()"><i class="fa fa-plus"></i></a> <i class="fa fa-pencil" onclick="editTarget()" ></i></th>
                </tr>
                <tr>
                    <th>Test</th>
                    <th>Target</th>
                    <th width="20%">Present</th>
                </tr>
                </thead>
                <tbody id="" class="target_data" >
                @foreach($patient_test_targets as $target)
                    <tr>
                        <td>{{ $target['TestName'] }}
                            <input type="hidden" id="test_name_{{ $target['test_master_id'] }}" name="test_name_{{ $target['test_master_id'] }}" value="{{  $target['test_master_id'] }}"/></td>
                        <td>
                            <input type="text" class="form-control custom-box patient_target_{{ $target['test_master_id'] }}" id="patient_target_{{ $target['test_master_id'] }}" name="patient_target_{{ $target['test_master_id']}}" value="{{ $target['target_value'] }}" readonly>
                        </td>
                        <td>
                            <input type="text" class="form-control custom-box patient_present_{{ $target['test_master_id'] }}" id="patient_present_{{ $target['test_master_id'] }}" name="patient_present_{{ $target['test_master_id'] }}"  value="{{ $target['present_value'] }}">
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td>Weight</td>
                    <td><input type="text" name="weight_target" id="weight_target" class="form-control custom-box" value="{{ isset($patient_target_details->weight_target)? $patient_target_details->weight_target : '' }}"></td>
                    <td><input type="text" name="weight_present" id="weight_present" class="form-control custom-box" value="{{ isset($patient_target_details->weight_target)? $patient_target_details->weight_target : ''}}"></td>
                </tr>
                <tr>
                    <td>Action Plan</td>
                    <td colspan="2">
                        <textarea cols="2" rows="2" name="action_plan" id="action_plan" class="form-control custom-box" style="height: 78px;" value="{{ isset($patient_target_details->action_plan)? $patient_target_details->action_plan : ''}}"></textarea>
                    </td>
                </tr>
               <!-- <td>Fibro scan value</td>
                    <td colspan="2">
                        <textarea cols="2" rows="1" name="fibro_scan" id="fibro_scan" class="form-control custom-box" style="height: 48px;">{{ isset($patient_target_details->fibro_scan)? $patient_target_details->fibro_scan : ''}}</textarea>
                    </td>-->
                </tr>



                </tbody>
            </table>


        </div>
<script>
    function addTarget(){
        var visitId = localStorage.getItem("dtms_visitId");



        if (visitId == "" || visitId == null) {
            sweetAlert("Oops...", "Please select a visit to continue", "warning");
        } else {
            $('#target-modal').modal();
        }
    }

    function editTarget(){
        var visitId = localStorage.getItem("dtms_visitId");



    if (visitId == "" || visitId == null) {

            sweetAlert("Oops...", "Please select a visit to continue", "warning");
        } else {
            $(".target-edit :input").attr("readonly", false);
        }

    }
</script>


