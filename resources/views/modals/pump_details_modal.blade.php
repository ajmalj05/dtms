<!-- <style>
   
</style> -->
<div id="pump-modal" class="modal fade " role="dialog">
    <div class="modal-dialog  modal-xl">
        <form name="alert-form" id="alert-form" action="#">
            <div class="modal-content ">
                <div class="modal-header">
                    <div class="card-title">
                        <p>Pump Details</p>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="container-fluid pt-2">
                        <div class="row">
                            <div class="col-xl-4 ">
                                <section>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label class="text-label">Model No.</label>
                                                <input type="text" name="model_number" id="model_number"
                                                   maxlength="50"
                                                    class="form-control" placeholder="" >
                                            </div>
                                            <div class="form-group">
                                                <label class="text-label">Remarks</label>
                                                <input type="text" name="remarks_value" id="remarks_value"
                                                   maxlength="50"
                                                    class="form-control" placeholder="" >
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-3 col-md-6 mb-2">
                                                    <div class="d-flex flex-wrap align-content-center h-100">
                                                        <button type="button" class="btn btn-sm btn-primary mt-1"
                                                            onclick="savePumpDataData()">Save</button>
                                                    </div>
                                                </div>
                                            </div>



                                        </div>
                                    </div>
                                </section>
                            </div>
                            <div class="col-xl-8 ">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="table-responsive pt-3">
                                            <table id="pump_table" class="display">
                                                <thead>
                                                    <tr>
                                                        <th>Sl No.</th>
                                                        <th>Modal Number</th>
                                                        <th>Remarks</th>
                                                        <!-- <th>C</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody>


                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </div>
    </form>
</div>
</div>



<style>
.modal-xl {
    max-width: 90% !important;
}

.search-btn {
    padding: 0.375rem 0.75rem !important;
}
</style>

<script>
$(document).ready(function() {

});

function showmodalTable() {
    table.destroy();
    table = $('#pump_table').DataTable({

        'ajax': {
            url: "{{ route('getpumpDetailstData') }}",
            type: 'POST',
        },
        "columns": [{
                "data": "id",
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                "data": "modal_number"
            },
            {
                "data": "remarks"
            },
        ]
    });

}

function savePumpDataData(){
    var modelnumber = document.getElementById("model_number").value;
    var remakrs = document.getElementById("remarks_value").value;
    let ajaxval = {
        modelnumber:modelnumber,
        remakrs:remakrs
    };

    $.ajax({
                type: "POST",
                url: "{{route('savepumpData')}}",
                data: ajaxval,
                success: function(result) {
                    if (result.status == 1) {
                        swal("Done", result.message, "success");

                        table.ajax.reload();

                    } else {
                        sweetAlert("Oops...", result.message, "error");
                    }
                },
            });

}

</script>