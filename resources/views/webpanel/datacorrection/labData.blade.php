<div class="content-body">

    <div class="container-fluid pt-2">
<form name="frm"

            <div class="row">
                <div class="col-md-3">
                    <div class="row" >

                        <div class="input-group">
                            <input type="text" class="form-control" name="uhidno" id="uhidno" placeholder="Enter UHID Number">
                            <button class="btn btn-primary btn-sm" type="button" onclick="getVisitList()">SHOW</button>
                        </div>


                        <div class="table-responsive">

                            <table class="table table-bordered">
                                <tr>
                                    <td>First Name</td>
                                    <td id="name"></td>
                                </tr>
                                <tr>
                                    <td>Last Name</td>
                                    <td id="lastname"></td>
                                </tr>
                                <tr>
                                    <td>Gender</td>
                                    <td id="gender"></td>
                                </tr>
                                <tr>
                                    <td>DOB</td>
                                    <td id="dob"></td>
                                </tr>
                            </table>

                        </div>

                    </div>

                </div>


                <div class="col-md-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <td>Visit Code</td>
                                    <td>Visit Date</td>
                                    <td>Visit Type</td>
                                </tr>
                           </thead>
                           <tbody id="visit_details">

                           </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <td>Test Name</td>
                                    <td>Result</td>
                                    <td>Update</td>

                                </tr>
                           </thead>
                           <tbody id="result_details">

                           </tbody>
                        </table>
                    </div>
                </div>

            </div>

    </div>

</div>

@include('frames/footer');

<script>

    function getVisitList()
    {
        //get patient data and visit details
        var uhidno=$("#uhidno").val();
        $.ajax({
            url: "{{ route('getPatientDatabyUhid') }}",
            type: 'POST',
            data:{uhidno:uhidno},
            success : function(result) {
                if (result.status == 1) {
                    var data=result.data;
                    var patientData=data.patient_data;
                    $("#name").html(patientData.name);
                    $("#lastname").html(patientData.last_name);
                    $("#gender").html(patientData.gender);
                    $("#dob").html(patientData.dob);

                    var visit_data=data.visit_data;

                    var html="";

                    $.each( visit_data, function( key, value ) {
                        html+="<tr onclick='loadVisit(\"" + value.id + "\")'><td>"+value.id+"</td><td>"+value.visit_date+"</td><td>"+value.visit_type_name+"</td></tr>";
                    })
                    $("#visit_details").html(html);

                }
                else{
                    alert("No data found")
                }
            },
        });
    }

    function loadVisit(id)
    {
        //N-2037
        //getallResultCorrectionData
        //   $("#result_details").html(result);
        $.ajax({
            url: "{{ route('getallResultCorrectionData') }}",
            type: 'POST',
            data:{id:id},
            success : function(result) {
                $("#result_details").html(result);
            },
        });
    }

    function updateData(id)
    {
        eid="test_"+id;

      testValue=$("#test_"+id).val();
       if(testValue!="")
       {
        alert(testValue);
        $.ajax({
            url: "{{ route('updateTestResultCorrection') }}",
            type: 'POST',
            data:{id:id,testValue:testValue},
            success : function(result) {
                    if(result.status==1)
                    {
                        alert("Updated");
                    }
                    else{
                        alert("Failed");
                    }
            },
        });
       }
    }
</script>
