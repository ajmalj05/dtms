<style>
    .dropdown bootstrap-select form-control-new dropup {
        width: 50% !important;
    }
</style>


<div class="content-body">
    <div class="container-fluid pt-2">

        <form name="frm" id="frm" action="" method="POST">

            <div class="row">
                <div class="col-md-12">

                    <div class="card">
                        <div class="card-body">

                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label> Result Enterd Date and Time</label>
                                    {{-- <input type="datetime-local" class="form-control" id="from_date" name="from_date"
                                        placeholder="Result Enterd Date and Time"
                                        value="{{ isset($testreultsDates)? \Carbon\Carbon::parse($testreultsDates->result_date): now()->setTimezone('T')->format('Y-m-dTh:m') }}"> --}}

                                        <input type="text" class="form-control date" id="from_date" readonly name="from_date" placeholder="Result Enterd Date and Time"  value="{{$result_date}}">
                                    </div>

                                <div class="form-group col-md-2">
                                    <label>Patient Name.</label>
                                    <input type="text" class="form-control" id="name" name="name" readonly
                                        value="{{ old('patient_name', isset($patient_data) ? $patient_data->name : '') }}">
                                </div>


                                <div class="form-group col-md-1">
                                    <label>Age</label>
                                    <input type="text" class="form-control" id="age" name="age" readonly
                                        value="{{ old('dob',isset($patient_data)? \Carbon\Carbon::parse($patient_data->dob)->diff(\Carbon\Carbon::now())->format('%y'): '') }}">

                                </div>
                                <div class="form-group col-md-2">
                                    <label>UHID No.</label>
                                    <input type="text" class="form-control" id="uhid" name="uhid" readonly
                                        value="{{ old('uhid', isset($patient_data) ? $patient_data->uhidno : '') }}">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Test Result Date and Time.</label>
                                    <input type="text" class="form-control" id="test_date" name="test_date"
                                        placeholder="Test Result Date and Time"  readonly value="{{$test_date}}">
                                </div>



                                <div class="form-group col-md-1">
                                    <label>Gender</label>
                                    <select id="gender" name="php gender" class="form-control" disabled>
                                        <option value="">Choose...</option>
                                        <?php
                                        if(isset($patient_data))
                                        {
                                            ?>
                                        <option value="m" <?php if (str_contains($patient_data->gender, 'm')) {
                                            echo 'selected';
                                        } ?>>Male</option>
                                        <option value='f' <?php if (str_contains($patient_data->gender, 'f')) {
                                            echo 'selected';
                                        } ?>>Female</option>
                                        <option value='o' <?php if (str_contains($patient_data->gender, 'o')) {
                                            echo 'selected';
                                        } ?>>Others</option>
                                        <?php
                                        }
                                        else{
                                            ?>
                                        <option value="m">Male</option>
                                        <option value='f'>Female</option>
                                        <option value='o'>Others</option>
                                        <?php
                                        }
                                     ?>
                                    </select>
                                </div>


                                <div class="form-group col-md-2">
                                    <label>Mobile</label>
                                    <input type="text" readonly name="mobile" id="mobile"
                                        value="{{ old('mobile_number', isset($patient_data) ? $patient_data->mobile_number : '') }}"class="form-control"
                                        placeholder="">
                                </div>



                            </div>








                        </div>



                    </div>
                </div>
            </div>
    </div>

    <div class="container-fluid pt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                    <!-- jdc14.10.2024-->

                    <div>
  <button class="btn btn-primary inline-flex items-center px-4 py-2" type="button" onclick="sendSMS()">Your result will be available in one hour</button>
  <button class="btn btn-primary inline-flex items-center px-4 py-2" type="button" onclick="sendDetails()" disabled>Send Details</button>

</div>


                        <div class="d-flex flex-row-reverse">



                            <div class="p-2">
                                <button type="button"
                                class="btn btn-success shadow btn-sm sharp mr-1 result_entry  float-right"
                                onclick="generateReport()" title="Print"><i class="fa fa fa-print"
                                    aria-hidden="true"></i></button>
                                </div>

                                <div class="p-2">
                                    <select class="form-control" name="result_print_type" id="result_print_type">
                                        <option value="0">Without Header</option>
                                        <option value="1">With Header</option>
                                    </select>
                                </div>
                        </div>


                        <div class="table-responsive">
                            <table id="example4" class="display table table-striped" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>Test Name</th>
                                        <th style="width: 20%">Result</th>
                                        <th>Measure Unit </th>
                                        <th>Normal Value</th>



                                    </tr>
                                </thead>
                                <tbody id="search_filter">
                                    <?php

                                  foreach ($test_results as $key) {

                                           ?>
                                    <tr>
                                        <td colspan="5" class="t-center table-headings" align="">
                                            <input type="checkbox" name="chkbp[]" id="<?php print $key['depid']; ?>"
                                            value="<?php print $key['depid']; ?>" class="flat-red"
                                            onClick="selectall(this.id)" checked> &nbsp;&nbsp;
                                            {{ $key['depName'] }}</td>
                                    </tr>
                                    <?php
                                                foreach ($key['groups'] as $grps)
                                                {
                                                    ?>
                                    <tr>
                                        <td colspan="5" class="t-left table-sub-headings">
                                            <input type="checkbox" name="chkbpGrp[]" value="<?php print $grps['groupId']; ?>" class="flat-red"
                                             checked>

                                            {{ $grps['groupName'] }}


                                        </td>
                                    </tr>
                                    <?php

                                                        foreach ($grps['test_items'] as $item) {

                                                        $test_id=$item['test_id'];

                                                        $expectedOut="";
                                                        $re_type=$item['result_type'];
                                                        if($re_type==1)
                                                        {

                                                            $cond=array(
                                                            array('id',$item['colour_id']),
                                                            );

                                                            $expectedOut=getAfeild("color_name","test_colours",$cond);

                                                        }
                                                        else  if($re_type==4) // clarity
                                                        {

                                                            $cond=array(
                                                            array('id',$item['clarity_id']),
                                                            );

                                                            $expectedOut=getAfeild("clarity_name","test_clarity",$cond);

                                                        }
                                                        else if($re_type==2)
                                                        {
                                                            $expectedOut=$item['from_range']. " - ".$item['to_range'];
                                                        }
                                                        else if($re_type==3)
                                                        {
                                                            if($item['positive_negative']==1){
                                                                $expectedOut="+ve";
                                                            }
                                                            else if(($item['positive_negative']==2)){
                                                                $expectedOut="-ve";
                                                            }
                                                            else if(($item['positive_negative']==3)){
                                                                $expectedOut="Normal";
                                                            }
                                                            else{
                                                                $expectedOut="---";
                                                            }

                                                        }

                                                        ?>
                                    <tr>
                                        <td>
                                            {{ $item['test_name'] }}


                                            <?php
                                                if($item['test_method']!="")
                                                {
                                                ?>
                                            <br>&nbsp;&nbsp;&nbsp; {{ $item['test_method'] }}
                                            <?php
                                                }
                                                ?>

                                        </td>
                                        <td>
                                            <input type="hidden" value="{{ $item['result_type'] }}"
                                                name="result_type_{{ $test_id }}"
                                                id=""result_type_{{ $test_id }}">
                                            <?php
                                            if($item['result_type']==1) // color
                                            {
                                            ?>
                                            <select class="form-control" style="width:50%"
                                                name="test_result_{{ $test_id }}"
                                                id="test_result_{{ $test_id }}">
                                                <option value="">-----Select Result-----</option>
                                                <?php
                                                foreach ($colours as $clrs) {
                                                    if ($item['result'] == $clrs->id) {
                                                        $sel = 'selected';
                                                    } else {
                                                        $sel = '';
                                                    }

                                                    echo "<option value='$clrs->id' $sel>$clrs->color_name</option>";
                                                }
                                                ?>


                                            </select>
                                            <?php
                                                                        }
else  if($item['result_type']==4) // clarity
                                                                        {
                                                                            ?>
                                            <select class="form-control" style="width:50%"
                                                name="test_result_{{ $test_id }}"
                                                id="test_result_{{ $test_id }}">
                                                <option value="">-----Select Result-----</option>
                                                <?php
                                                foreach ($clarity as $cts) {
                                                    if ($item['result'] == $cts->id) {
                                                        $sel = 'selected';
                                                    } else {
                                                        $sel = '';
                                                    }

                                                    echo "<option value='$cts->id' $sel>$cts->clarity_name</option>";
                                                }
                                                ?>


                                            </select>
                                            <?php
                                                                        }



                                                                        else if($item['result_type']==2) // range
                                                                        {
                                                                            ?>
                                            <input type="text" value="{{ $item['result'] }}" class="form-control"
                                                maxlength="30" name="test_result_{{ $test_id }}"
                                                id="test_result_{{ $test_id }}">

                                            <?php
                                                                        }
                                                                        else if($item['result_type']==3) // +ve,negative
                                                                        {
                                                                        ?>
                                            <select class="form-control" style="width:50%"
                                                name="test_result_{{ $test_id }}"
                                                id="test_result_{{ $test_id }}" onchange="activateaddvalue({{ $test_id }})">
                                                <option value="">-----Select Result-----</option>
                                                <option value="1" <?php if ($item['result'] == 1) {
                                                    echo 'selected';
                                                } ?>>+ve</option>
                                                <option value="2" <?php if ($item['result'] == 2) {
                                                    echo 'selected';
                                                } ?>>-ve</option>
                                                <option value="3" <?php if ($item['result'] == 3) {
                                                    echo 'selected';
                                                } ?>>Normal</option>
                                                  <option value="-1" <?php if ($item['result'] == -1) {
                                                    echo 'selected';
                                                } ?>>Enter Value</option>
                                            </select>
                                            <?php
                                                    if ($item['result'] == -1)
                                                    {
                                                        $classs="block";
                                                        $testVal=$item['test_result_value'];
                                                    }
                                                    else{
                                                        $classs="none";
                                                        $testVal="";
                                                    }
                                            ?>
                                            <input type="text" style="display: {{$classs}}" class="form-control form-control-sm" value="{{$testVal}}" placeholder="Enter Value" name="test_result_add_{{ $test_id }}" id="test_result_add_{{ $test_id }}">
                                            <?php
                                                                        }
                                                                    ?>

                                        </td>
                                        <td class="s5">{{ $item['unit'] }}</td>
                                        <td>{{ $expectedOut }}</td>

                                    </tr>

                                    <?php
                                                        }
                                                }  // end group


                                        } // end test result//departemnt
                                    ?>




                                </tbody>
                            </table>
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label>Reported By</label>
                                    <select id="reported_by" name="reported_by" class="form-control reported_by">
                                        <option value="">Select User</option>
                                        {{ LoadCombo('users', 'id', 'name', isset($testreultsDates) ? $testreultsDates->user_id : '', 'where active_status=1 AND is_deleted=0', 'order by id asc') }}
                                    </select>

                                </div>
                                <div class="form-group col-md-2">
                                    <label></label><br>
                                    <button class="btn btn-primary inline-flex items-center px-4 py-2" type="button"
                                        onclick="saveResults()">Save Result</button>
                                </div>
                            </div>
                            {{-- <button class="btn btn-primary inline-flex items-center px-4 py-2" type="button"
                                onclick="saveResults()">Save Result</button> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </form>

    </div>
</div>
@include('frames/footer');
{{-- <script src='date_picker/js/bootstrap-datepicker.min.js' type='text/javascript'></script> --}}

<style>
    .details .form-group {
        margin-bottom: unset !important;
    }

    input[type="checkbox"]:after {
        background: unset;
    }

    .t-center {
        text-align: center;
    }

    .t-left {
        text-align: left;
    }

    .form-control-new {

        background: #fff;
        border-radius: 0.35rem;
        border: 1px solid #d7dae3;
        color: #394955;
        height: 33px;
    }

    .table th,
    .table td {
        padding: 8px 9px;
    }

    .table-headings {
        background-color: #2592cc;
        color: #fff;
        font-weight: 600;

    }

    .table-sub-headings {
        background-color: #9796d7;
        color: #fff;
    }

    .table.table-striped tbody tr:nth-of-type(odd),
    .table.table-hover tr:hover {
        background-color: #fff !important;
    }
</style>
<script src="{{ asset('/js/jquery-redirect.js') }}"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">

<!----Athira---->
<script>
    // JavaScript function to send data via POST request
    function sendSMS() {
    var name = $('#name').val();
    var age = $('#age').val();
    var uhid = $('#uhid').val();
    var mobile = $('#mobile').val();
    var token = $('meta[name="csrf-token"]').attr('content');

    // Prepare data to send as JSON
    var data = {
        name: name,
        age: age,
        uhid: uhid,
        mobile: mobile,
        _token: token
    };

    var url = '{{ route("sendSMS") }}';

    $.ajax({
        type: "POST",
        url: url,
        data: JSON.stringify(data),  // Send JSON data
        contentType: 'application/json',  // Set content type to JSON
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    type: 'success',  // Use 'type' instead of 'icon'
                    title: 'Success',
                    text: 'SMS sent successfully!'
                });
            } else {
                Swal.fire({
                    type: 'error',
                    title: 'Error',
                    text: 'Failed to send SMS.'
                });
            }
        },
        error: function() {
            Swal.fire({
                type: 'error',
                title: 'Error',
                text: 'An error occurred while sending SMS.'
            });
        }
    });
}



</script>



<script>
    var today = new Date().toISOString().slice(0, 16);
    var today_p = new Date().toISOString().slice(0, 16);
    document.getElementsByName("from_date")[0].min = today_p;
    document.getElementsByName("from_date")[0].max = today;
    document.getElementsByName("test_date")[0].min = today_p;
    document.getElementsByName("test_date")[0].max = today;
</script>
<script>
    function saveResults() {
        var billId = {{ $patient_billing_id }};
        var pid = {{ $patient_data->id }};
        var visit_id = {{ $visit_id }};

        var form = $('#frm')[0];
        var formData = new FormData(form);



        formData.append('billId', billId);
        formData.append('pid', pid);
        formData.append('visit_id', visit_id);

        var e = document.getElementsByTagName("select");
        for (var i = 0; i < e.length; i++) {
            var name = e[i].getAttribute("name");
            formData.append(name, $("#" + name).val());
        }

        url = '{{ route('save-result') }}';

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(result) {
                //console.log(result);
                if (result.status == 1) {
                    swal("Done", result.message, "success");
                } else {
                    sweetAlert("Oops...", result.message, "error");

                }
            }
        });
    }

    function generateReport() {

        var patient_id = {{ $patient_data->id }}
        var billId = {{ $patient_billing_id }};
        // var departments = $("input[name='chkbp[]']")
        //       .map(function(){return $(this).val();}).get();

        var result_print_type=$("#result_print_type").val();

            var departments = [];
            $("input:checkbox[name='chkbp[]']:checked").each(function() {
                departments.push($(this).val());
            });

            var selectedDepartments = departments;

            var groups=[];

            $("input:checkbox[name='chkbpGrp[]']:checked").each(function() {
                groups.push($(this).val());
            });





        $.redirect('{{ url('printResult') }}', {
            _token: "{{ csrf_token() }}",
            patient_id: patient_id,
            billing_type: 3,
            patient_billing_id: billId,
            selectedDepartments:selectedDepartments,
            selectedGroups:groups,
            result_print_type:result_print_type
        }, 'POST', '_blank');

    }



$( document ).ready(function() {


//    $('#from_date').datetimepicker(
//     {
//         useCurrent: false,
//             format: 'MM/DD/YYYY hh:mm',
//             debug:true
//     }
//    );
//    //('#datetimepicker1').datetimepicker();

//    $('#StartDate').datetimepicker({
//             useCurrent: false,
//             format: 'MM/DD/YYYY hh:mm',
//             debug:true
//         })
});

function activateaddvalue(testId)
{

    eid="test_result_"+testId;
    enewId="test_result_add_"+testId;
    var selectdVal=$("#"+eid).val();

    if(selectdVal==-1){
        $("#"+enewId).show();
    }
    else{
        $("#"+enewId).val('');
        $("#"+enewId).hide();
    }
}

</script>
