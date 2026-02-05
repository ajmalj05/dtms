<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }
</style>
<div id="stock-management-modal" class="modal fade " role="dialog">
    <div class="modal-dialog ">
        <form name="stock-form" id="stock-form" action="#" >
            <div class="modal-content">
                <div class="modal-header">
                    <div class="card-title">
                        <p>{{$title}}</p>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="container-fluid pt-2">

                        <div class="row">
                            <div class="col-xl-12 col-xxl-12 col-lg-12 col-sm-12">
                                <div class="">

                                    <div class="">
                                        <section>
                                            <div class="row">
                                                <div class="col-xl-12 col-md-12 mb-6">
                                                    <input type="hidden" name="hid_pd_id" id="hid_pd_id">
                                                    <div class="form-group">
                                                        <label class="text-label">Available Quantity<span class="required">*</span></label>
                                                        <input type="text" name="quantity" id="quantity" class="form-control custom-date"  placeholder="" readonly>
                                                        <small id="quantity_error" class="form-text text-muted error"></small>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12 col-md-12 mb-6">
                                                    <div class="form-group">
                                                        <label class="text-label">New Availability<span class="required">*</span></label>
                                                        <input type="text" name="new_availability" id="new_availability" class="form-control custom-date" onKeyPress="return onlyNumbers(event)" placeholder="" >
                                                        <small id="new_availability_error" class="form-text text-muted error"></small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-3 col-md-6 mb-2">
                                                    <div class="d-flex flex-wrap align-content-center h-100">
                                                        <button type="button" class="btn btn-sm btn-primary mt-1" onclick="saveStockData(1)">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                    </section>
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

    .modal-lg {
        max-width: 80% !important;
    }
    .search-btn{
        padding:0.375rem 0.75rem!important;
    }
</style>

<script>

    function saveStockData(crude=1)
    {
        $("[id*='_error']").text('');
        var form = $('#stock-form')[0];
        var formData = new FormData(form);
        formData.append('crude', crude);
        url='{{route('save-stock-data')}}';

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(result){
                if (result.status == 1) {
                    swal("Done", result.message, "success");
                    $('#stock-management-modal').modal('toggle');
                    var form = $('#stock-form')[0];
                    table.ajax.reload();
                    $('#new_availability').val('').selectpicker('refresh');

                }
                else if (result.status == 2) {
                    swal("Done", result.message, "success");
                    $('#stock-management-modal').modal('toggle');
                    table.ajax.reload();
                    document.getElementById("stock-form").reset();
                    $('#new_availability').val('').selectpicker('refresh');

                    if(page==1){

                    }

                }
                else {
                    sweetAlert("Oops...", result.message, "success");
                }

            },
            error: function(result,jqXHR, textStatus, errorThrown){
                if( result.status === 422 ) {
                    result=result.responseJSON;
                    var error=result.errors;
                    $.each(error, function (key, val) {
                        $("#" + key + "_error").text(val[0]);
                    });
                }

            }
        });
    }


</script>
