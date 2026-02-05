<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }
</style>
<div id="diagnosis-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form name="diagnosisfrm" id="diagnosisfrm" action="#" >
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
                                                        Add New - Diagnosis
                                                    </h4>
                                                </div>
                                                <div class="card-body">

                                                    <div class=" mb-5">
                                                        <div class="form-group ">
                                                            <label class="text-label">New Diagnosis <span class="required">*</span></label>
                                                            <select id="diagnosis_name" name="diagnosis_name" class="form-control">
                                                                <option  value="" selected>Choose...</option>
                                                                @foreach($diagnosis_data as $diagnosis)
                                                                    <option value="{{$diagnosis['id']}}">{{$diagnosis['diagnosis_name']}}</option>
                                                                @endforeach
                                                            </select>
                                                            <small id="diagnosis_name_error" class="form-text text-muted error"></small>
                                                        </div>

                                                        <div class="form-group">
                                                            <input type="checkbox"  name="diag_check_bx" id="diag_check_bx"> <label>Add Question Mark</label>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-label">ICD Diagnosis</label><br>
                                                            <a href="https://www.aapc.com/codes/icd-10-codes-range/" target="_blank" class="custom-a" >View ICD Diagnosis</a>


                                                            {{-- <input type="text" name="icd_diagnosis" id="icd_diagnosis" class="form-control" value="" placeholder=""> --}}
                                                            {{-- <small id="icd_diagnosis_error" class="form-text text-muted error"></small> --}}
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="text-label">ICD Code</label>
                                                            <input type="text" name="icd_code" id="icd_code" value="" class="form-control" placeholder="" >
                                                            <small id="icd_code_error" class="form-text text-muted error"></small>
                                                        </div>

                                                        <div class="form-group ">
                                                            <label class="text-label">Year of Diagnosis <span class="required">*</span></label>
                                                            <select class="form-control" name="diagnosis_year" id="diagnosis_year">
                                                                <option data-display="Select Year" value="">Select Year</option>
                                                                @for ($i = date('Y'); $i>=1960; $i--)
                                                                    <option value="{{$i}}">{{$i}}</option>
                                                                @endfor
                                                            </select>
                                                            <small id="diagnosis_year_error" class="form-text text-muted error"></small>
                                                        </div>


                                                        <div class="form-group ">
                                                            <label class="text-label">Month of Diagnosis </label>
                                                            <select class="form-control" name="diagnosis_month" id="diagnosis_month">
                                                                <option data-display="Select Year" value="">Select Month</option>
                                                                @for ($month = 1; $month <= 12; $month++)
                                                                @php
                                                                    $monthName = date("F", mktime(0, 0, 0, $month, 1));
                                                                @endphp
                                                                <option value="{{ $month }}">{{ $monthName  }}</option>
                                                            @endfor
                                                            </select>

                                                        </div>

                                                        <div class="form-group">
                                                            <input type="checkbox"  name="newly_diag_check_bx" id="newly_diag_check_bx"> <label>Newly diagnosed</label>
                                                        </div>





                                                        <div class="form-group">
                                                            <label class="text-label">Date<span class="required">*</span></label>
                                                            <input type="text" class="form-control" name="diagnosis_date" value="<?=date('d-m-Y');?>" id="diagnosis_date">
                                                            <small id="diagnosis_date_error" class="form-text text-muted error"></small>

                                                        </div>

                                                        <div class="d-flex flex-wrap align-content-center h-100">
                                                            <button type="button" class="btn btn-sm btn-primary mt-1" onclick="saveDiagnosis(1)">Save</button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-8">
                                            <div class="card card-sm">
                                                <div class="card-header">
                                                    <h4 class="card-title">Diagnosis Listing</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive pt-3">
                                                        <table id="diagnosis_data" class="display table" style="width:100%">
                                                            <thead>
                                                            <tr>
                                                                <th> Sl No</th>
                                                                <th> Diagnosis Name</th>
                                                                <th> Duration</th>
                                                               <!--<th> Diagnosis Month</th>-->
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

        $("#diagnosis_name").on("change",function() {
            $("#diagnosis_name_error").html("");
        });
        $("#diagnosis_year").on("change",function() {
            $("#diagnosis_year_error").html("");
        });
        $("#diagnosis_date").on("change",function() {
            $("#diagnosis_date_error").html("");
        });

        $('#diagnosis_date').datepicker({
            autoclose: true,
            // endDate: '+10d',
            startDate: '+0d',
            format: 'dd-mm-yyyy'

        });

    });

</script>

<script>

    function saveDiagnosis(crude=1)
    {
        $("[id*='_error']").text('');
        var form = $('#diagnosisfrm')[0];
        var formData = new FormData(form);
        formData.append('crude', crude);
        url='{{route('saveDiagnosisList')}}';

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
                    var form = $('#diagnosisfrm')[0];
                    document.getElementById("diagnosisfrm").reset();
                    $('#diagnosis_name, #SubDiagnosisSelect,#icd_diagnosis, #icd_code, #diagnosis_year,#diagnosis_date').val('').selectpicker('refresh');
                    $('#diagnosis_data').DataTable().ajax.reload();

                }
                else if (result.status == 2) {
                    swal("Done", result.message, "success");

                    document.getElementById("diagnosisfrm").reset();
                    $('#diagnosis_name, #SubDiagnosisSelect, #icd_diagnosis,#icd_code,#diagnosis_year,#diagnosis_date').val('').selectpicker('refresh');
                    $('#complication_data').DataTable().ajax.reload();

                }
                else {
                    sweetAlert("Oops...", result.message, "error");
                    $('#diagnosis_data').DataTable().ajax.reload();

                }

               // $('#diagnosis_data').DataTable().ajax.reload();

                // $('#diagnosis_date').val();
                $( "#diagnosis_date" ).datepicker({dateFormat:"dd-mm-yyyy"}).datepicker("setDate",new Date());


            },
            error: function(result,jqXHR, textStatus, errorThrown){
                if( result.status === 422 ) {
                    result=result.responseJSON;
                    var error=result.errors;
                    $.each(error, function (key, val) {
                        console.log([key,val]);
                        let errorMsg = "This field is required";
                        if(key == 'diagnosis_name') {
                            errorMsg = "New Diagnosis Field is Required."
                        }
                        if(key == 'subdiagnosis_id') {
                            errorMsg = "Sub Complication Field is Required."
                        }
                        if(key == 'diagnosis_year') {
                            errorMsg = "Year of Diagnosis Field is Required."
                        }
                        if(key == 'diagnosis_date') {
                            errorMsg = "Date Field is Required."
                        }
                        $("#" + key + "_error").text(errorMsg);
                    });
                }

            }
        });
    }
    $('#diagnosis_data tbody').on('click', '.delete_diagnosis_data', function() {

        var data = $('#diagnosis_data').DataTable().row($(this).parents('tr')).data();

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
                    url: "<?php echo url('/') ?>/deleteDiagnosisData",
                    data: ajaxval,
                    success: function(result) {

                        if (result.status == 1) {
                            swal("Done", result.message, "success");
                            $('#diagnosis_data').DataTable().ajax.reload();
                        }
                        else {
                            sweetAlert("Oops...", result.message, "error");
                            $('#diagnosis_data').DataTable().ajax.reload();
                        }
                    },
                });
            }
        })
    });
</script>
