<style>
    .error_red{
        background-color: #D46A6A;
        color: #FFF;
    }

</style>
<div class="content-body" >
    <div class="container-fluid " >


        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderd">
                        <thead>
                            <tr>
                                <td>Sl No</td>
                                <td>Lab Ref No</td>
                                <td>Patient Id</td>
                                <td>UHID No</td>
                                <td>Patient Name</td>
                                <td>Bill Date</td>
                                <td>Result Count</td>
                                <td>Choose Visit</td>
                                <td>Update</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sl=0;
                            foreach ($bills as $key) {
                                $sl++;

                    $cond=array(array('id',$key->PatientId));
                    $name=getAfeild("name","patient_registration",$cond);
                    $uhid=getAfeild("uhidno","patient_registration",$cond);


                    $cond2=array(array('Labno',$key->PatientLabNo));
                    $resultCount=getCount('test_results',$cond2);
                    if(!$resultCount || $resultCount=="") $resultCount=0;

                               $iconName=$key->patientName;

                               if($name!=$iconName)
                               {
                                $class="error_red";
                               }
                               else{
                                $class="s";
                               }
                                ?>
                                <tr class="{{$class}}">
                                    <td>{{$sl}}</td>
                                    <td>{{$key->PatientLabNo}}</td>
                                    <td>{{$key->PatientId}}</td>
                                    <td>{{$uhid}}</td>
                                    <td>{{$key->patientName}}</td>
                                    <td>{{$key->created_at}}</td>
                                    <td>{{$resultCount}}</td>
                                    <td>
                                        <select id="visit_id_{{$key->id}}" name="visit_id_{{$key->id}}" class="form-control">
                                            <option value="">Choose</option>
                                            {{     LoadCombo("patient_visits","id","visit_date",'',"where patient_id=$key->PatientId","order by id desc");}}
                                        </select>
                                        <?php


                                        ?>

                                    </td>
                                    <td>
                                        <?php
                                            if($resultCount>0)
                                            {
                                                ?>
                                                 <button type="button" class="btn btn-success btn-sm" onclick="updateData({{$key->id}},{{$key->PatientLabNo}},{{$key->PatientId}})">Integrate</button>

                                                <?php
                                            }
                                            else{
                                                ?>
                                                <button type="button" class="btn btn-danger btn-sm">No Results</button>

                                                <?php
                                            }
                                        ?>
                                         </td>
                                </tr>
                                <?php

                            }


                                if($sl==0)
                                {
                                    ?>

                                    <tr>
                                        <td colspan="8" align="center">No data found to integrate</td>
                                    </tr>
                                    <?php
                                }
                            ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@include('frames/footer');

<script>

    function updateData(bid,PatientLabNo,pid)
    {
        alert(bid);
        var visit_id=$("#visit_id_"+bid).val();
        if(visit_id>0)
        {
           $.ajax({
            url: "{{ route('visit_bill_update') }}",
            type: 'post',
            data: {
                billId: bid,
                visitId: visit_id,
                patientLabNo:PatientLabNo,
                pid:pid,
                token: "{{ csrf_token() }}"
            },
            success: function (response) {
                swal("Done", response.message, "success");
            },
        });
        }
        else{
            alert("Select a visit to update")
        }

    }
</script>
