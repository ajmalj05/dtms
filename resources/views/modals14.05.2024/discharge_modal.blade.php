<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }
</style>
<div id="discharge-modal" class="modal fade " role="dialog">
    <div class="modal-dialog modal-lg">
        <form name="discharge-form" id="discharge-form" action="#" >
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
                                                    <input type="hidden" name="hid_ip_admission_id" id="hid_ip_admission_id">
                                                    <div class="form-group">
                                                        <label class="text-label">Discharge Date<span class="required">*</span></label>
                                                        <input type="text" class="form-control" name="discharge_date" id="discharge_date"  >
                                                        <small id="discharge_date_error" class="form-text text-muted error"></small>
                                                    </div>
                                                </div>

                                                <div class="col-xl-12 col-md-12 mb-6">
                                                    <div class="form-group">
                                                        <label class="text-label">Discharge Summary<span class="required">*</span></label>
                                                        <textarea class="form-control"  placeholder="" name="discharge_summary" id="discharge_summary" rows="10"></textarea>
                                                        <small id="discharge_summary_error" class="form-text text-muted error"></small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-3 col-md-6 mb-2">
                                                    <div class="d-flex flex-wrap align-content-center h-100">
                                                        <button type="button" class="btn btn-sm btn-primary mt-1" onclick="updateDischargeData(2)">Save</button>
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

<script src="{{asset('/js/jquery-redirect.js')}}"></script>

<style>

    .modal-lg {
        max-width: 80% !important;
    }
    .search-btn{
        padding:0.375rem 0.75rem!important;
    }
</style>

<script>

    function updateDischargeData(crude=2)
    {
        $("[id*='_error']").text('');
        var form = $('#discharge-form')[0];
        var formData = new FormData(form);
        formData.append('crude', crude);
        formData.append('discharge_summary', CKEDITOR.instances['discharge_summary'].getData());
        var hid_ip_admission_id=$("#hid_ip_admission_id").val();
        url='{{route('update-discharge-data')}}';

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(result){
                if (result.status == 1) {
                    swal("Done", result.message, "success");
                    $('#discharge-modal').modal('toggle');
                    var form = $('#discharge-form')[0];


                    $.redirect('{{url("view_discharge_summary")}}', { _token: "{{ csrf_token() }}", ip_admission_id: hid_ip_admission_id}, 'POST', '_blank');

                    table.ajax.reload();
                    $('#discharge_date,#discharge_summary').val('').selectpicker('refresh');

                }
                else if (result.status == 2) {
                    swal("Done", result.message, "success");
                    $('#discharge-modal').modal('toggle');
                    table.ajax.reload();
                    document.getElementById("discharge-form").reset();
                    $('#discharge_date,#discharge_summary').val('').selectpicker('refresh');

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

<script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>



<script>
    // Initialize CKEditor after the page has loaded
    $(function(){



CKEDITOR.replace('discharge_summary');




});
</script>
