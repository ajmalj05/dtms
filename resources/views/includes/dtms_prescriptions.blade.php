<style>
    #append_prescription_data td {
        height: 45px;
    }
</style>
<div class="table-responsive">
    <table class="table table-stripped table-hover">
        <thead>
        <tr>
            <th width="10%">Tablet</th>
            <th>Medication</th>
            <th width="25%">Generic</th>
            <th>Dose</th>
            <th>Remarks</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody id="append_prescription_data">

        </tbody>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="text-label">Select Type</label>
                        <select class="tablettype_options"    id="tablettype_options">
                            <option value=''>All</option>
                            {{LoadCombo("tablet_type_master","id","tablet_type_name",'','where display_status=1 AND is_deleted=0',"order by id desc");}}

                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="text-label">Select Medicine</label>
                        <select class="js-data-example-ajax"></select>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-center">
                    <div class="form-group">
                        @php
                                $patientId = $patient_data->id;
                        @endphp
                        <br>
                        <button type="button" class="btn btn-sm btn-primary" id="old-medicines" onclick="viewOldData('{{ $patientId }}')">Previous medication</button>
                    </div>
                </div>
            </div>
        </div>




    </table>
</div>

<script>
    function  viewOldData(patientId) {
        var visitId = localStorage.getItem("dtms_visitId");
        $('#view-old-data-modal').modal();
        getAllOldMedicineData(patientId,visitId);
    }

    function getAllOldMedicineData(patientId, visitId)
    {
        table = $('#old_medicine_data').DataTable({
            // scrollY: 470,
            "order": [] ,
            "autoWidth": false,
            "dom": 'lfrtip',
            "destroy" : true,
            'ajax': {
                url: "<?php echo url('/') ?>/get-all-old-medicine-data",
                type: 'POST',
                "data": function(d) {
                    d.visitId= visitId;
                    d.patientId= patientId;
                }
            },
            "columns": [
                {
                    "data": "id",
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data": "tablet_type"
                },
                {
                    "data": "tablet_name"
                },
                {
                    "data": "qty"
                },
                {
                    "data": "dose"
                },
                {
                    "data": "remark"
                },
            ]
        });
    }
</script>
<script>
    var count=1;
    function removeFiled(id, thiss){
     if(!id.includes('tr'))
     {
        $(`#${id}`).remove();
     } else {
        let ajaxval = {
                id: thiss,
            };
            swal({
                title: 'Are you sure?',
                text: "You won't be able to recover this data!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Delete it!'
            }).then((result) => {
                if (result.value) {


                    $.ajax({
                        type: "POST",
                        url: "<?php echo url('/'); ?>/precription/delete",
                        data: ajaxval,
                        success: function(result) {

                            if (result.status == 1) {
                                swal("Done", result.message, "success");
                            $(`#${id}`).remove();
                            } else {
                                sweetAlert("Oops...", result.message, "error");
                            }
                        },
                    });
                }
            })
     }

    }
    var medicine = [];

    function add_duplicate(tablettype_Id="", medicine_Id="",remarks="",dose="",tablettype_name='',medicine_name='',id='',generic_name=""){


            count++;
            let newId;
            if(id!= ''){
                var html = "<tr id='tr_"+id+"'>";
                    newId=`tr_${id}`
            }
            else{
                var html = "<tr id='new_"+count+"'>";
                    newId=`new_${count}`
            }

            html += "<td><input type='text'  id='tablet_type_name_" + count + "' class='form-control' readonly><input type='hidden' name='tablet_type_id[]' id='tablet_type_id_" + count + "' class='form-control'></td>";
            html += "<td><input type='text'  id='medicine_options_name_" + count + "' class='form-control' readonly><input type='hidden' name='medicine_id[]' id='medicine_options_" + count + "' class='form-control'></td>";
            html += "<td>"+generic_name+"</td>";
            html += "<td><input type='text'  name='dose[]' id='dose_" + count + "'  class='form-control' placeholder='Dose'></td>";
            html += "<td><input type='text' name='remarks[]' id='remarks_" + count + "' class='form-control' placeholder='Remarks'></td>";
            html += "<td><i style='color:red' class='fa fa-trash' onclick=removeFiled('"+newId+"','"+id+"');></i></td>";
            html += "</tr>"
            $('#append_prescription_data').append(html);
            if (tablettype_Id) {
                $('#tablet_type_id_' + count).val(tablettype_Id);
                $('#tablet_type_name_' + count).val(tablettype_name);
            }
            if (medicine_Id) {
                $('#medicine_options_' + count).val(medicine_Id);
                $('#medicine_options_name_' + count).val(medicine_name);
            }
            if (remarks) {
                $('#remarks_' + count).val(remarks);
            }
            if (dose) {
                $('#dose_' + count).val(dose);
            }


        // $(".js-data-example-ajax").empty();



        // $(".js-data-example-ajax").val('').select2("refresh");
        // $("#tablettype_options").val('').selectpicker('refresh');


    }

    // function getMedicineLists(typeid,count,medicineid="",dose=""){

    //     var url='{{route('getMedicineLists')}}';
    //     _token="{{csrf_token()}}";

    //     $.ajax({
    //         type: "POST",
    //         url: url,
    //         data: {type_id:typeid,_token:_token},
    //         success: function(result) {

    //             $('#medicine_options_'+count).empty().append(result).trigger('change');

    //             if(medicineid){
    //                 $('#medicine_options_'+count).val(medicineid).trigger('change');
    //             }


    //         },
    //     });


    //     //
    // }

    function getmedicinedose(medid,count,dose=""){

        if(!medid){
            return true;
        }
        if(dose){
            return true;
        }
        var url='{{route('getMedicineDoseValue')}}';
        _token="{{csrf_token()}}";

        $.ajax({
            type: "POST",
            url: url,
            data: {medid:medid,_token:_token},
            success: function(result) {
                var data=JSON.parse(result);
                $('#dose_'+count).val(data?.dose);

            },
        });
    }




</script>


<style>


</style>
