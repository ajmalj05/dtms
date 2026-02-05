<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }
</style>
<div id="alert-modal" class="modal fade " role="dialog">
    <div class="modal-dialog ">
        <form name="alert-form" id="alert-form" action="#" >
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
                                                        <div class="form-group ">
                                                            <label class="text-label">Alerts<span class="required">*</span></label>
                                                            <textarea class="form-control"  placeholder="Enter the Alerts" name="alerts" id="alerts"   rows="10"></textarea>
                                                            <small id="alerts_error" class="form-text text-muted error"></small>
                                                        </div>
                                                    </div>
                                                    </div>
                                                    <div class="row">
                                                    <div class="col-xl-3 col-md-6 mb-2">
                                                        <div class="d-flex flex-wrap align-content-center h-100">
                                                            <button type="button" class="btn btn-sm btn-primary mt-1" onclick="saveAlertData(1)">Save</button>
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
    $(document).ready(function(){
        getAlertData();
        $("#alerts").keyup(function() {
           $("#alerts_error").html("");
        });
    });
    function getAlertData()
    {
        $.ajax({
            url: "{{ route('getAlertData') }}",
            type: 'POST',
            success : function(result) {
                var jsondata = $.parseJSON(result);
                if(jsondata){
                    $('#alerts').html(jsondata.alerts);
                }

            },
        });
    }
    function alertValidation(alertMessage) {
        console.log(alertMessage);
    }

    function saveAlertData(crude=1)
    {
        $("[id*='_error']").text('');
        var form = $('#alert-form')[0];
        var formData = new FormData(form);
        formData.append('crude', crude);
        url='{{route('saveAlertData')}}';

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(result){
                if (result.status == 1) {
                    // console.log()
                    swal("Done", result.message, "success");
                    var form = $('#alert-form')[0];
                    document.getElementById("alert-form").reset();
                    $('#alert-modal').modal('toggle');
                    // $('#alerts').val('');
                }
                else if (result.status == 2) {
                    swal("Done", result.message, "success");
                    // location.reload();
                    document.getElementById("alert-form").reset();
                    $('#alert-modal').modal('toggle');
                    $('#alerts').val('');

                }
                else {
                    sweetAlert("Oops...", result.message, "error");
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
