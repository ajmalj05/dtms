<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }
    table.dataTable
    {
        width: 100% !important;
    }
</style>
<div id="patient-reminders-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <form name="patient_reminder_form" id="patient_reminder_form" action="#" >
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
                                                        Add New - Patient Reminders
                                                    </h4>
                                                </div>
                                                <div class="card-body">

                                                    <div class=" mb-5">
                                                            <input type="hidden" name="hid_patient_reminder_id" id="hid_patient_reminder_id">
                                                            <div class="form-group">
                                                                <label class="text-label">Date<span class="required">*</span></label>
                                                                <input type="text" name="patient_reminder_date" id="patient_reminder_date" value="<?=date('d-m-Y');?>"  onKeyPress="return blockSpecialChar(event)" maxlength="50" class="form-control" placeholder="" required readonly>
                                                                <small id="patient_reminder_date_error" class="form-text text-muted error"></small>

                                                            </div>
                                                            <div class="form-group">
                                                                <label class="text-label">Details<span class="required">*</span></label>
                                                                <input type="text" name="patient_reminder_details" id="patient_reminder_details" value="Called by " onKeyPress="return blockSpecialChar(event)"  class="form-control" placeholder="" required>
                                                                <small id="patient_reminder_details_error" class="form-text text-muted error"></small>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="text-label">Remark<span class="required">*</span></label>
                                                                <input type="text" name="patient_reminder_remark" id="patient_reminder_remark" onKeyPress="return blockSpecialChar(event)"  class="form-control" placeholder="" required>
                                                                <small id="patient_reminder_remark_error" class="form-text text-muted error"></small>
                                                            </div>

                                                            <div id="crud_patient_reminders">
                                                                <button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="savePatientReminders(1,1)" >Save</button>
                                                            </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-8">
                                            <div class="card card-sm">
                                                <div class="card-header">
                                                    <h4 class="card-title">Patient Reminders Listing</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive pt-3">
                                                        <table id="PatientReminder" class="display">
                                                            <thead>
                                                            <tr>
                                                                <th>Sl No.</th>
                                                                <th>UHID</th>
                                                                <th>Date</th>
                                                                <th>Details</th>
                                                                <th>Remark</th>
                                                                <th>User</th>
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



    $(document).ready(function() {

        $("#patient_reminder_date").on("change",function() {
            $("#patient_reminder_date_error").html("");
        });
        $("#patient_reminder_details").keyup(function() {
            $("#patient_reminder_details_error").html("");
        });
        $("#patient_reminder_remark").keyup(function() {
            $("#patient_reminder_remark_error").html("");
        });

        $('#patient_reminder_date').datepicker({
            autoclose: true,
            // endDate: '+10d',
            // startDate: '+0d',
            format: 'dd-mm-yyyy'

        });

    });

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [day,month,year ].join('-');
    }



    $('#PatientReminder tbody').on('click', '.edit_reminders', function() {
        $("[id*='_error']").text('');
        var data = table.row($(this).parents('tr')).data();
        $("#hid_patient_reminder_id").val(data.id);



        var sDate =formatDate(data.date);


        $('#patient_reminder_date').val(sDate);

        // $('#patient_reminder_date').val(formatDate('dd-mm-yy', new Date(data.date)));


        // $('#patient_reminder_date').datepicker({ dateFormat: 'dd-mm-yy' });

        $("#patient_reminder_details").val(data.details.trim());
        $("#patient_reminder_remark").val(data.remarks.trim());
        crude_btn_manage(2,1);
    });



    $('#PatientReminder tbody').on('click', '.delete_reminders', function() {
        var data = table.row($(this).parents('tr')).data();

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
                    url: "<?php echo url('/') ?>/deletePatientReminders",
                    data: ajaxval,
                    success: function(result) {

                        if (result.status == 1) {
                            swal("Done", result.message, "success");
                            table.ajax.reload();
                        } else {
                            sweetAlert("Oops...", result.message, "error");
                        }
                    },
                });
            }
        })
    });

    function savePatientReminders(page,crude)
    {
        $("[id*='_error']").text('');
        var url="";
        if(page==1)
        {
            url='{{route('savePatientReminders')}}';
            var form = $('#patient_reminder_form')[0];
        }

        var formData = new FormData(form);
        formData.append('crude', crude);
        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(result){

                if(result.status==1)
                {
                    swal("Done", result.message, "success");
                    document.getElementById("patient_reminder_form").reset();
                    table.ajax.reload();
                    crude_btn_manage(1,page)
                }
                else if(result.status==2){
                    sweetAlert("Oops...",result.message, "error");
                }
                else{
                    sweetAlert("Oops...",result.message, "error");
                }

                $( "#patient_reminder_date" ).datepicker({dateFormat:"dd-mm-yyyy"}).datepicker("setDate",new Date());


            },
            error: function(result,jqXHR, textStatus, errorThrown){
                if( result.status === 422 ) {
                    result=result.responseJSON;
                    var error=result.errors;
                    $.each(error, function (key, val) {
                        console.log(key);
                        $("#" + key + "_error").text(val[0]);
                    });
                }

            }
        });
    }

    function crude_btn_manage(type=1,page)
    {
        if(page==1)
        {
            if(type==1) $('#crud_patient_reminders').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="savePatientReminders(\''+page+'\',\''+type+'\')" >Save</button>');
            else if(type==2)  $('#crud_patient_reminders').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="savePatientReminders(\''+page+'\',\''+type+'\')" >Update</button>');
        }

    }


</script>

