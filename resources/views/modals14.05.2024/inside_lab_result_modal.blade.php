<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }
</style>
<div id="inside-lab-result" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form name="inside-lab-form" id="inside-lab-form" action="#" >
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
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-xl-4">
                                            <div class="card card-sm">
                                                <div class="card-header">
                                                    <h4 class="card-title">
                                                        Add New - Inside Lab Result
                                                    </h4>
                                                </div>
                                                <div class="card-body">

                                                    <div class=" mb-5">

                                                        <div class="form-group">
                                                            <label>Search a test</label>
                                                            <select class="search-all-test-ajax"></select>
                                                        </div>


                                                        <div class="form-group">
                                                            <label class="text-label">Selected Test<span class="required">*</span></label>
                                                            <input type="hidden" class="form-control"  readonly name="search_test_names" id="search_test_names">
                                                            <input type="text" class="form-control"  readonly name="search_test_s" id="search_test_s">
                                                            <small id="search_test_names_error" class="form-text text-muted error"></small>

                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-label">Result<span class="required">*</span></label>
                                                            <input type="text" name="result_value" id="result_value" value="" class="form-control" placeholder="" >
                                                            <small id="result_value_error" class="form-text text-muted error"></small>
                                                        </div>

                                                        <div class="d-flex flex-wrap align-content-center h-100">
                                                            <button type="button" class="btn btn-sm btn-primary mt-1" onclick="SaveInsideLabResult(1)">Save</button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-8">
                                            <div class="card card-sm">
                                                <div class="card-header">
                                                    <h4 class="card-title">Inside Lab Listing</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive pt-3">
                                                        <table id="inside_lab_data" class="display table" style="width:100%">
                                                            <thead>
                                                            <tr>
                                                                <th> Sl No</th>
                                                                <th> Test Name</th>
                                                                <th> Result </th>

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


    function SaveInsideLabResult(crude=1)
    {
        $("[id*='_error']").text('');
        var form = $('#inside-lab-form')[0];
        var formData = new FormData(form);
        formData.append('crude', crude);
        url='{{route('save-inside-lab-data')}}';

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(result){
                if (result.status == 1) {
                    swal("Done", result.message, "success");
                    var form = $('#inside-lab-form')[0];
                    document.getElementById("inside-lab-form").reset();

                }
                else if (result.status == 2) {
                    swal("Done", result.message, "success");
                    document.getElementById("inside-lab-form").reset();

                }
                else {
                    sweetAlert("Oops...", result.message, "error");


                }

                $('#inside_lab_data').DataTable().ajax.reload();


            },
            error: function(result,jqXHR, textStatus, errorThrown){
                if( result.status === 422 ) {
                    result=result.responseJSON;
                    var error=result.errors;
                    $.each(error, function (key, val) {
                        let errorMsg = "This field is required";
                        if(key == 'search_test_names') {
                            errorMsg = "Test Field is Required."
                        }
                        if(key == 'result_value') {
                            errorMsg = "Result Field is Required."
                        }
                        $("#" + key + "_error").text(errorMsg);
                    });
                }

            }
        });
    }


    $(document).ready(function(){
        $('.search-all-test-ajax').on('select2:select', function (e) {
            var data = e.params.data;
            $("#search_test_s").val(data.text);
            $("#search_test_names").val(data.id);

         });
    });
    </script>
