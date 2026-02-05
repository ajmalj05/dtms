<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }
</style>
<div id="abroad-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form name="abroad-form" id="abroad-form" action="#" >
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
                                                        Add New - Abroad Details
                                                    </h4>
                                                </div>
                                                <div class="card-body">

                                                    <div class=" mb-5">
                                                        <div class="form-group">
                                                            <label class="text-label">Name <span class="required">*</span></label>
                                                            <input type="text" name="patient_name" id="patient_name" class="form-control" value="" placeholder="" >
                                                            <small id="patient_name_error" class="form-text text-muted error"></small>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="text-label">Mobile Number <span class="required">*</span></label>
                                                            <input type="text" name="phone_no" id="phone_no" class="form-control" value="" placeholder="" maxlength="15" onKeyPress="return onlyNumbers(event)">
                                                            <small id="phone_no_error" class="form-text text-muted error"></small>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-label">Email Id<span class="required">*</span></label>
                                                            <input type="email" name="email" id="email" class="form-control" value="" placeholder="" >
                                                            <small id="email_error" class="form-text text-muted error"></small>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-label">Foreign Address<span class="required">*</span></label>
                                                            <textarea class="form-control"  placeholder="Enter the Address" name="address" id="address" rows="10"></textarea>
                                                            <small id="address_error" class="form-text text-muted error"></small>
                                                        </div>

                                                        <div class="d-flex flex-wrap align-content-center h-100">
                                                            <button type="button" class="btn btn-sm btn-primary mt-1" onclick="saveAbroadData(1)">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-8">
                                            <div class="card card-sm">
                                                <div class="card-header">
                                                    <h4 class="card-title">Abroad Details Listing</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive pt-3">
                                                        <table id="abroad_data" class="display table" style="width:100%">
                                                            <thead>
                                                            <tr>
                                                                <th> Sl No</th>
                                                                <th> Patient Name</th>
                                                                <th> Mobile Number</th>
                                                                <th> Email</th>
                                                                <th>Foreign Address</th>
                                                                <th>Action</th>

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

    .modal-lg {
        max-width: 80% !important;
    }
    .search-btn{
        padding:0.375rem 0.75rem!important;
    }
</style>


<script>
    $(document).ready(function(){
        $("#patient_name").keyup(function() {
            $("#patient_name_error").html("");
        });
        $("#phone_no").keyup(function() {
            $("#phone_no_error").html("");
        });
        $("#email").keyup(function() {
            $("#email_error").html("");
        });
        $("#address").keyup(function() {
            $("#address_error").html("");
        });
    });
    function saveAbroadData(crude=1)
    {
        $("[id*='_error']").text('');
        var form = $('#abroad-form')[0];
        var formData = new FormData(form);
        formData.append('crude', crude);
        url='{{route('saveAbroadData')}}';

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
                    var form = $('#abroad-form')[0];
                    document.getElementById("abroad-form").reset();
                    $('#patient_name,#phone_no,#email_id,#address').val('');
                    $('#abroad_data').DataTable().ajax.reload();

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

    $('#abroad_data tbody').on('click', '.delete_abroad_data', function() {

        var data = $('#abroad_data').DataTable().row($(this).parents('tr')).data();

        let ajaxval = {
            id: data.id,
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
                    url: "<?php echo url('/') ?>/deleteAbroadData",
                    data: ajaxval,
                    success: function(result) {

                        if (result.status == 1) {
                            swal("Done", result.message, "success");
                            $('#abroad_data').DataTable().ajax.reload();
                        }
                        else {
                            sweetAlert("Oops...", result.message, "error");
                            $('#abroad_data').DataTable().ajax.reload();
                        }
                    },
                });
            }
        })
    });

</script>
