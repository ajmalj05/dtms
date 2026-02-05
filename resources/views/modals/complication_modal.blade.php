<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }
</style>
<div id="complication-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form name="complication-form" id="complication-form" action="#" >
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
                                                        Add New - Complication
                                                    </h4>
                                                </div>
                                                <div class="card-body">

                                                    <div class=" mb-5">
                                                        <div class="form-group ">
                                                            <label>New Complication <span class="required">*</span></label>
                                                            <select id="ComplicationSelect" name="complication_id" class="form-control dt-input">
                                                                <option  value="" selected>Choose...</option>
                                                                @foreach($complication_data as $complication)
                                                                    <option value="{{$complication['id']}}">{{$complication['complication_name']}}</option>
                                                                @endforeach
                                                            </select>
                                                            <small id="complication_id_error" class="form-text text-muted error"></small>
                                                        </div>

                                                        <div class="form-group ">
                                                            <label>Sub Complication</label>
                                                            <select class="form-control dt-input SubComplicationSelect" id="SubComplicationSelect" name="subcomplication_id" disabled data-none-selected-text="Choose Sub Complication">>
                                                            </select>
                                                            <small id="subcomplication_id_error" class="form-text text-muted error"></small>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-label">ICD Diagnosis</label><br>
                                                            <a href="https://www.aapc.com/codes/icd-10-codes-range/" target="_blank" class="custom-a" >View ICD Diagnosis</a>
                                                            {{-- <input type="text" name="icd_diagnosis" id="icd_diagnosis" class="form-control" value="" placeholder="">
                                                            <small id="icd_diagnosis_error" class="form-text text-muted error"></small> --}}
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-label">ICD Code</label>
                                                            <input type="text" name="icd_code" id="icd_code" value="" class="form-control" placeholder="" >
                                                            <small id="icd_code_error" class="form-text text-muted error"></small>
                                                        </div>

                                                        <div class="form-group ">
                                                            <label class="text-label">Year of Complication <span class="required">*</span></label>
                                                            <select class="form-control" name="complication_year" id="complication_year">
                                                                <option data-display="Select Year" value="">Select Year</option>
                                                                @for ($i = date('Y'); $i>=1960; $i--)
                                                                    <option value="{{$i}}">{{$i}}</option>
                                                                @endfor
                                                            </select>
                                                            <small id="complication_year_error" class="form-text text-muted error"></small>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-label">Date<span class="required">*</span></label>
                                                            <input type="text" class="form-control" name="complication_date" value="<?=date('d-m-Y');?>" id="complication_date">
                                                            <small id="complication_date_error" class="form-text text-muted error"></small>

                                                        </div>

                                                        <div class="d-flex flex-wrap align-content-center h-100">
                                                            <button type="button" class="btn btn-sm btn-primary mt-1" onclick="saveComplication(1)">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-8">
                                            <div class="card card-sm">
                                                <div class="card-header">
                                                    <h4 class="card-title">Complication Listing</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive pt-3">
                                                        <table id="complication_data" class="display table" style="width:100%">
                                                            <thead>
                                                            <tr>
                                                                <th> Sl No</th>
                                                                <th> Complication Name</th>
                                                                <th> Sub Complication Name</th>
                                                                <th> Complication Year</th>
                                                                <th> Examined Date</th>
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
        $("#ComplicationSelect").on("change",function() {
            $("#complication_id_error").html("");
        });
        $("#SubComplicationSelect").on("change",function() {
            $("#subcomplication_id_error").html("");
        });
        $("#complication_year").on("change",function() {
            $("#complication_year_error").html("");
        });
        $("#complication_date").on("change",function() {
            $("#complication_date_error").html("");
        });

        $('#complication_date').datepicker({
            autoclose: true,
            // endDate: '+10d',
            startDate: '+0d',
            format: 'dd-mm-yyyy'

        });

    });

</script>
<script>
    var App = {
        initialize: function() {
            $('#ComplicationSelect').on('change', App.showSubcomplicationList);
        },
        showSubcomplicationList : function() {
            var complication_id = $('#ComplicationSelect').val();
            $.ajax({
                url: "{{ route('get-subcomplication-list') }}",
                type: 'GET',
                data : {
                    complication_id : complication_id,
                },
                success : function(response) {
                    $("#SubComplicationSelect").html('<option  value="" selected>Choose a sub complication</option>');
                    $.each(response.data, function(key, value) {
                        $("#SubComplicationSelect").append('<option value=' + value.id + '>' + value.subcomplication_name + '</option>');
                    });

                    $("#SubComplicationSelect").attr('disabled', false);
                    $("#SubComplicationSelect").selectpicker('refresh');;
                },
            });
        },
    };
    App.initialize();
</script>

<script>
    function saveComplication(crude=1)
    {
        $("[id*='_error']").text('');
        var form = $('#complication-form')[0];
        var formData = new FormData(form);
        formData.append('crude', crude);
        url='{{route('saveComplicationList')}}';

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
                    var form = $('#complication-form')[0];
                    document.getElementById("complication-form").reset();
                    $('#ComplicationSelect, #SubComplicationSelect, #icd_code, #complication_year, #complication_date').val('').selectpicker('refresh');
                    $('#complication_data').DataTable().ajax.reload();

                }
                else if (result.status == 2) {
                    swal("Done", result.message, "success");
                    location.reload();
                    document.getElementById("complication-form").reset();
                    $('#ComplicationSelect, #SubComplicationSelect, #icd_code, #complication_year, #complication_date').val('').selectpicker('refresh');
                    $('#complication_data').DataTable().ajax.reload();

                }
                else {
                    sweetAlert("Oops...", result.message, "error");
                    $('#complication_data').DataTable().ajax.reload();

                }

            },
            error: function(result,jqXHR, textStatus, errorThrown){
                if( result.status === 422 ) {
                    result=result.responseJSON;
                    var error=result.errors;
                    $.each(error, function (key, val) {
                        let errorMsg = "This field is required";
                        if(key == 'complication_id') {
                            errorMsg = "New Complication Field is Required."
                        }
                        if(key == 'subcomplication_id') {
                            errorMsg = "Sub Complication Field is Required."
                        }
                        if(key == 'complication_year') {
                            errorMsg = "Complication Year Field is Required."
                        }
                        if(key == 'complication_date') {
                            errorMsg = "Date Field is Required."
                        }
                        $("#" + key + "_error").text(errorMsg);
                    });
                }

            }
        });
    }
    $('#complication_data tbody').on('click', '.delete_complication_data', function() {

        var data = $('#complication_data').DataTable().row($(this).parents('tr')).data();

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
                    url: "<?php echo url('/') ?>/deleteComplicationData",
                    data: ajaxval,
                    success: function(result) {

                        if (result.status == 1) {
                            swal("Done", result.message, "success");
                            $('#complication_data').DataTable().ajax.reload();
                        }
                        else {
                            sweetAlert("Oops...", result.message, "error");
                            $('#complication_data').DataTable().ajax.reload();
                        }
                    },
                });
            }
        })
    });
</script>
