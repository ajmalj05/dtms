<div id="remainder-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form name="complication-form" id="complication-form" action="#">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="card-title">
                        <p>Reminder</p>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="container-fluid pt-2">

                        <div class="row">
                            <div class="col-xl-12 col-xxl-12 col-lg-12 col-sm-12">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-xl-4">
                                            <div class="card card-sm">
                                                <div class="card-header">
                                                    <h4 class="card-title">
                                                        Add Reminder
                                                    </h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group " id="validation">
                                                        <label class="text-label">Enter Details<span
                                                                class="required">*</span></label>
                                                        <textarea class="form-control textareaaheight" name="remainder"
                                                            id="remainder" rows="2" maxlength="200"></textarea>
                                                        <small id="alerts_error"
                                                            class="form-text text-muted error"></small>

                                                        <div class="d-flex flex-wrap btn-lg float-right mt-5 h-100">
                                                            <button type="button" class="btn btn-sm btn-primary"
                                                                onclick="Saveremaindertext()">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-8">
                                            <div class="card card-sm">
                                                <div class="card-header">
                                                    <h4 class="card-title">Reminder Listing</h4>
                                                </div>
                                                <div class="card-body">

                                                    <div class="table-responsive">
                                                        <table class="table table-striped" id="remainderTable"
                                                            name="remainderTable">
                                                            <thead>
                                                                <tr>
                                                                    <th>sl.No</th>
                                                                    <th>Rem
                                                                        inder</th>

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
                </div>
            </div>
    </div>
    </form>
</div>
</div>


<style>
.textareaaheight {
    height: auto !important;
}
</style>
<script>
function Saveremaindertext() {
    $(".text-danger").remove();

    var remanider = document.getElementById("remainder").value.trim();
    if (remanider != '') {
        var modalData = {
            remanider: remanider
        };
        url = "{{route('saveRemainder')}}";
        $.ajax({
            type: "POST",
            url: url,
            data: modalData,
            success: function(result) {
                if (result.status == 1) {
                    swal("Done", result.message, "success");
                    // $('#remainder-modal').modal('hide');
                    $('#remainderTable').DataTable().ajax.reload();

                    $("#remainder").val("")
                } else {
                    swal("Done", result.message, "Error");
                    $('#remainder-modal').modal('hide');


                }
            },
            error: function(result, jqXHR, textStatus, errorThrown) {
                if (result.status === 422) {
                    result = result.responseJSON;
                    var error = result.errors;
                    $.each(error, function(key, val) {
                        console.log(key);
                        $("#" + key + "_error").text(val[0]);
                    });
                }

            }
        });
    } else {
        $("#validation").append('<label class="text-danger">Please Enter Remainder</label>');

    }
}
</script>