<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }
</style>
<div id="vaccination-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form name="vaccination-form" id="vaccination-form" action="#" >
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
                                                        Add New - Vaccination
                                                    </h4>
                                                </div>
                                                <div class="card-body">

                                                    <div class=" mb-5">
                                                        <div class="form-group ">
                                                            <label>New Vaccination <span class="required">*</span></label>
                                                            <select id="vaccination_id" name="vaccination_id" class="form-control dt-input">
                                                                <option  value="" selected>Choose...</option>
                                                                @foreach($vaccination_data as $vaccination)
                                                                    <option value="{{$vaccination['id']}}">{{$vaccination['vaccination_name']}}</option>
                                                                @endforeach
                                                            </select>
                                                            <small id="vaccination_id_error" class="form-text text-muted error"></small>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-label">Date<span class="required">*</span></label>
                                                            <input type="text" class="form-control" name="vaccination_date" value="<?=date('d-m-Y');?>" id="vaccination_date">
                                                            <small id="vaccination_date_error" class="form-text text-muted error"></small>

                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-label">Remark</label>
                                                            <input type="text" name="vaccination_remark" id="vaccination_remark" value="" class="form-control" placeholder="" >
                                                            <small id="vaccination_remark_error" class="form-text text-muted error"></small>
                                                        </div>

                                                        <div class="d-flex flex-wrap align-content-center h-100">
                                                            <button type="button" class="btn btn-sm btn-primary mt-1" onclick="saveVaccination(1)">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-8">
                                            <div class="card card-sm">
                                                <div class="card-header">
                                                    <h4 class="card-title">Vaccination Listing</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive pt-3">
                                                        <table id="vaccination_data" class="display table" style="width:100%">
                                                            <thead>
                                                            <tr>
                                                                <th> Sl No</th>
                                                                <th>Vaccination Name</th>
                                                                <th>Remark</th>
                                                                <th>Vaccination Date</th>
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
        $("#vaccination_date").on("change",function() {
            $("#vaccination_date_error").html("");
        });
        $("#vaccination_id").on("change",function() {
            $("#vaccination_id_error").html("");
        });
    });
</script>
<script>

    $(document).ready(function(){

        $('#vaccination_date').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy'

        });

    });

</script>


<script>
    function saveVaccination(crude=1)
    {
        $("[id*='_error']").text('');
        var form = $('#vaccination-form')[0];
        var formData = new FormData(form);
        formData.append('crude', crude);
        url='{{route('saveVaccinationData')}}';

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
                    var form = $('#vaccination-form')[0];
                    document.getElementById("vaccination-form").reset();
                    $('#vaccination_id').val('').selectpicker('refresh');
                    // $('#vaccination_data').DataTable().ajax.reload();

                }
                else if (result.status == 2) {
                    swal("Done", result.message, "success");
                    // location.reload();
                    document.getElementById("vaccination-form").reset();
                    $('#vaccination_id').val('').selectpicker('refresh');
                    // $('#vaccination_data').DataTable().ajax.reload();

                }
                else {
                    sweetAlert("Oops...", result.message, "error");


                }

                $('#vaccination_data').DataTable().ajax.reload();

                $( "#vaccination_date" ).datepicker({dateFormat:"dd-mm-yyyy"}).datepicker("setDate",new Date());


            },
            error: function(result,jqXHR, textStatus, errorThrown){
                if( result.status === 422 ) {
                    result=result.responseJSON;
                    var error=result.errors;
                    $.each(error, function (key, val) {
                        let errorMsg = "This field is required";
                        if(key == 'vaccination_id') {
                            errorMsg = "Vaccination Field is Required."
                        }
                        if(key == 'vaccination_date') {
                            errorMsg = "Date Field is Required."
                        }
                        $("#" + key + "_error").text(errorMsg);
                    });
                }

            }
        });
    }
    $('#vaccination_data tbody').on('click', '.delete_vaccination_data', function() {

        var data = $('#vaccination_data').DataTable().row($(this).parents('tr')).data();

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
                    url: "<?php echo url('/') ?>/deleteVaccinationData",
                    data: ajaxval,
                    success: function(result) {

                        if (result.status == 1) {
                            swal("Done", result.message, "success");
                            $('#vaccination_data').DataTable().ajax.reload();
                        }
                        else {
                            sweetAlert("Oops...", result.message, "error");
                            $('#vaccination_data').DataTable().ajax.reload();
                        }
                    },
                });
            }
        })
    });
</script>


