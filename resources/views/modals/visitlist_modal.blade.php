<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }
</style>
<div id="visit-list-modal" class="modal fade " role="dialog">
    <div class="modal-dialog ">
        <form name="visit-list-form" id="visit-list-form" action="#" >
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
                                                    <input type="hidden" name="hid_visit_id" id="hid_visit_id">
                                                    <div class="form-group">
                                                        <label class="text-label">Visit Type<span class="required">*</span></label>
                                                        <select id="visit_type_id" name="visit_type_id" class="form-control">
                                                            <option  value="" selected>Choose...</option>
                                                            {{LoadCombo("visit_type_master","id","visit_type_name",'','where display_status=1 AND is_deleted=0',"order by id desc");}}
                                                        </select>
                                                    </div>
                                                    <small id="visit_type_id_error" class="form-text text-muted error"></small>

                                                </div>
                                                <div class="col-xl-12 col-md-12 mb-6">
                                                    <div class="form-group">
                                                        <label class="text-label">Visit Date<span class="required">*</span></label>
                                                        <input type="text" name="visit_date" id="visit_date" class="form-control custom-date" value="<?=date('d-m-Y');?>" placeholder="" readonly>
                                                        <small id="visit_date_error" class="form-text text-muted error"></small>
                                                    </div>
                                                </div>
                                                <div class="col-xl-12 col-md-12 mb-6">
                                                    <div class="form-group ">
                                                        <label>Specialist<span class="required">*</span></label>
                                                        <select id="specialist" name="specialist" class="form-control">
                                                            <option  value="" selected>Choose...</option>
                                                            {{LoadCombo("specialist_master","id","specialist_name",'','where display_status=1 AND is_deleted=0',"order by id desc");}}
                                                        </select>
                                                    </div>
                                                    <small id="specialist_error" class="form-text text-muted error"></small>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-3 col-md-6 mb-2">
                                                    <div class="d-flex flex-wrap align-content-center h-100">
                                                        <button type="button" class="btn btn-sm btn-primary mt-1" onclick="updateVisitListData(2)">Update</button>
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

    function updateVisitListData(crude=2)
    {
        $("[id*='_error']").text('');
        var form = $('#visit-list-form')[0];
        var formData = new FormData(form);
        formData.append('crude', crude);
        url='{{route('updateVisitListData')}}';

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(result){
                if (result.status == 1) {
                    swal("Done", result.message, "success");
                    $('#visit-list-modal').modal('toggle');
                    var form = $('#visit-list-form')[0];
                    table.ajax.reload();
                    $('#specialist, #visit_type_id').val('').selectpicker('refresh');

                }
                else if (result.status == 2) {
                    swal("Done", result.message, "success");
                    $('#visit-list-modal').modal('toggle');
                    table.ajax.reload();
                    document.getElementById("visit-list-form").reset();
                    $('#specialist, #visit_type_id').val('').selectpicker('refresh');

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
                        // console.log(key);
                        $("#" + key + "_error").text(val[0]);
                    });
                }

            }
        });
    }
</script>
