<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }
</style>
<div id="outside-lab-result" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form name="outside-lab-form" id="outside-lab-form" action="#" >
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
                                                        Add New - Outside Lab Result
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
                                                                <input type="hidden" class="form-control"  readonly name="search_test" id="search_test">
                                                                <input type="text" class="form-control"  readonly name="search_test_name" id="search_test_name">
                                                                <small id="search_test_error" class="form-text text-muted error"></small>

                                                            </div>


                                                        <div class="form-group">
                                                            <label class="text-label">Result<span class="required">*</span></label>
                                                            <input type="text" name="result" id="result" value="" class="form-control" placeholder="" >
                                                            <small id="result_error" class="form-text text-muted error"></small>
                                                        </div>

                                                        <div class="d-flex flex-wrap align-content-center h-100">
                                                            <button type="button" class="btn btn-sm btn-primary mt-1" onclick="SaveOutsideLabResult(1)">Save</button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-8">
                                            <div class="card card-sm">
                                                <div class="card-header">
                                                    <h4 class="card-title">Outside Lab Listing</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive pt-3">
                                                        <table id="outside_lab_data" class="display table" style="width:100%">
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


    function SaveOutsideLabResult(crude=1)
    {
        $("[id*='_error']").text('');
        var visitDate = $('#new_visit_date').val();
        console.log(visitDate);
        var form = $('#outside-lab-form')[0];
        var formData = new FormData(form);
        formData.append('crude', crude);
        formData.append('visit_date', visitDate);
        url='{{route('save-outside-lab-data')}}';

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(result){
                if (result.status == 1) {
                    swal("Done", result.message, "success");
                    var form = $('#outside-lab-form')[0];
                    document.getElementById("outside-lab-form").reset();
                    // $('#search_test').val('').selectpicker('refresh');

                }
                else if (result.status == 2) {
                    swal("Done", result.message, "success");
                    document.getElementById("outside-lab-form").reset();
                    // $('#search_test').val('').selectpicker('refresh');

                }
                else {
                    sweetAlert("Oops...", result.message, "error");


                }

                $('#outside_lab_data').DataTable().ajax.reload();


            },
            error: function(result,jqXHR, textStatus, errorThrown){
                if( result.status === 422 ) {
                    result=result.responseJSON;
                    var error=result.errors;
                    $.each(error, function (key, val) {
                        let errorMsg = "This field is required";
                        if(key == 'search_test') {
                            errorMsg = "Test Field is Required."
                        }
                        if(key == 'result') {
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
            $("#search_test_name").val(data.text);
			document.getElementById("search_test_name").value=data.text;

		 $("#search_test").val(data.id);
			document.getElementById("search_test").value=data.id;

         });
    });


    </script>
