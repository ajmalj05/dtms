<link href='date_picker/css/bootstrap-datepicker.min.css' rel='stylesheet' type='text/css'>
<style>
    .highlight{
        background-color: #6b69eb !important;
    }
</style>
<div class="content-body">
    <div class="container-fluid pt-2">

        <form name="frm" id="frm" action="#" >


            <div class="col-xl-12 col-xxl-12 col-lg-12 col-sm-12">



                <section>
                            <div class="row">

                                <div class="col-xl-2 col-md-3 mb-2">
                                  <div class="form-group">
                                      <label class="text-label">UHID No.</label>
                                          <input type="text" class="form-control" name="uhid" id="s_uhid" value="" >


                                  </div>
                                </div>
                                <div class="col-xl-2 col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="text-label">Patient Name.</label>
                                            <input type="text" class="form-control" name="s_patient_name" id="s_patient_name" value="" >


                                    </div>
                                  </div>
                                <div class="col-xl-2 col-md-6 mb-2">
                                    <div class="form-group">
                                        <label class="text-label">From Date</label>
                                        <input type="text" name="from_date" id="from_date" class="form-control" value=""   placeholder="" >
                                        <small id="name_error" class="form-text text-muted error"></small>
                                    </div>
                                </div>


                                <div class="col-xl-2 col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="text-label">To Date </label>


                                        <input type="text" name="to_date" id="to_date"  value="<?=date('d-m-Y');?>" class="form-control" placeholder="" >
                                        </div>
                                  </div>
                                  <div class="col-xl-2 col-md-3 mb-2">
                                    <div class="form-group">
                                      <label class="text-label" style="opacity:0">Search</label>
                                      <br>
                                      <button class="btn btn-primary search-btn">Search</button>
                                    </div>
                                  </div>


                                  <div class="col-xl-1 col-md-3 mb-2 ">
                                      <div class="form-group">
                                        <label class="text-label" style="opacity:0">Search</label>
                                        <br>

                                      </div>
                                    </div>




                            </div>

                </section>
            </div>


    </form>
    <div style="min-height: 900px">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-sm-12">
                <div class="card card-sm ">
                    <div class="card-header">
                        <h4 class="card-title">Visits Listing</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                         <table id="visit_lists" class="display">
                            <thead>
                                <tr>
                                    <th width="10%">UHID No.</th>
                                    <th>Category</th>
                                    <th>Division</th>
                                    <th  width="10%">Sub Division</th>
                                    <th>Patient Name</th>
                                    <th>Doctor</th>
                                    <th  width="10%">Visit Type</th>
                                    <th>Date</th>
                                   <th>Action</th>


                                </tr>
                            </thead>
                            <tbody id="search_filter">




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
@include('frames/footer');
@include('modals/visitlist_modal',['title'=>'Visit List','data'=>'dfsds'])

<style>
    .table-profile td {
        padding :0 9px !important;
        border-top:unset !important;
        font-size: 13px!important;
    }
    /* .list-group-item:first-child  {
        border-top-left-radius: 0.25rem!important;
        border-top-right-radius: 0.25rem!important;
    }
    .list-group-item:last-child{
        border-bottom-left-radius: 0.25rem!important;
        border-bottom-right-radius: 0.25rem!important;
    } */
    .list-group-item {
        padding: 0.5rem 1.5rem;
        text-align: left;
        font-size: 14px;
    }
    .list-group-item:hover{
        background: #6b69eb;
        color: #fff;
    }
    .list-group-item.disabled{
        color: #fff;
        background-color: #abb2b8!important;
        border-color: #ced5db;
    }
    .btn {
        padding: 0.38rem 1.5rem;
    }
    #visit_lists td {

        padding: 5px 9px;
    }
    .form-control {
        height: 30px;
    }
    .medication_add{
        max-height: 60vh!important;
        overflow: auto;
    }
</style>

<link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
<link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
<script src='date_picker/js/bootstrap-datepicker.min.js' type='text/javascript'></script>
<script src="./vendor/select2/js/select2.full.min.js"></script>
<script src="./js/plugins-init/select2-init.js"></script>
<script src="{{asset('/js/jquery-redirect.js')}}"></script>


<script>
    $(document).ready(function(){
        $("#visit_lists").dataTable().fnDestroy()
        table = $('#visit_lists').DataTable({
            "order": [] ,
            "autoWidth": false,
            "dom": 'Blfrtip',
            "destroy" : true,

        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        'ajax': {
            url: "<?php echo url('/') ?>/getVisitLists",
            type: 'POST',
            "data": function(d) {

                d._token= "{{ csrf_token() }}";
                d.uhid= $('#s_uhid').val();
                // d.mobile_number= $('#s_mobile_number').val();
                // d.patient_type= $('#patient_type').val();
                d.patient_name= $('#s_patient_name').val();
                // d.last_name= $('#last_name').val();
                // d.gender= $('#gender').val();
                // d.age= $('#age').val();
                // d.address= $('#address').val();
                d.from_date= $('#from_date').val();
                d.to_date= $('#to_date').val();

            }
        },
        "columns": [
            {
                "data": "uhidno",
                // render: function(data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }
                render: function(data, type, row, meta) {
                    if(data==0){
                        return '---';
                    }
                    return "<a href='#' class='rowclick-dtmshome'>"+ data+"</a>";
                }
            },
            {
                "data":"patient_type_name"
            },
            {
                "data":"division"
            },
            {
                "data":"sub_division_name"
            },
            {
                "data": "full_name",
                "render": function(data, type, full, meta) {
                    return "<a href='#' class='rowclick-dtmshome'>"+ data+"</a>";

                }
            },
            {
                "data":"drname"
            },
            {
                "data": "visit_type_name",
                "render": function(data, type, full, meta) {
                    return "<a href='#' class='rowclick-dtmshome'>"+ data+"</a>";

                }
            },


            {
                "data": "visit_date",
                "render": function(data, type, full, meta) {

                    if(data){
                        time=full.appointment_time ? full.appointment_time :"" ;
                        hrs="";
                        if(time!=""){
                            time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

                            if (time.length > 1) { // If time format correct
                            time = time.slice (1);  // Remove full string match value
                            time[5] = +time[0] < 12 ? ' AM' : ' PM'; // Set AM/PM
                            time[0] = +time[0] % 12 || 12; // Adjust hours
                            }
                            hrs= time.join ('');


                        }
                        var date = new Date(data);
                        var month = date.getMonth() + 1;
                        return "<a href='#' class='rowclick-dtmshome'>"+ ('0' + date.getDate()).slice(-2) + "-" + (month.toString().length > 1 ? month : "0" + month) + "-" +   date.getFullYear() + ' '+ hrs+"</a>";


                    }else{
                        return '';
                    }






                },
            },
            // {
            //     "data": "notes",

            // },
            // {
            //     "data": "id",
            //     "render": function(display_status, type, full, meta) {

            //         if(full.patient_id ==""){
            //             return '<div class="d-flex"><a data-toggle="tooltip" data-placement="bottom" title="Edit Appointment"  href="#" class="edit btn btn-primary shadow btn-xs sharp mr-1 edit_details"><i class="fa fa-pencil"></i></a> <a href="#" data-toggle="tooltip" data-placement="bottom" title="Link Registration" class="edit btn btn-primary shadow btn-xs sharp mr-1 link_details"><i class="fa fa-link"></i></a></div>'
            //         }else{

            //             return '<div class="d-flex"><a href="#" class="edit btn btn-primary shadow btn-xs sharp mr-1 edit_details"><i class="fa fa-pencil"></i></a> <a href="#" data-toggle="tooltip" data-placement="bottom" title="Edit patient details" class="edit btn btn-primary shadow btn-xs sharp mr-1 patient_details"><i class="fa fa-user-circle-o"></i></a></div>'
            //         }

            //     }
            // },
            {
                "data": "id",
                "render": function(data, type, full, meta) {
                    return '<div class="d-flex"><a href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Edit"class="edit_visit_list btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil" ></i></a><a class="opdbilling btn btn-primary shadow btn-xs sharp mr-1" href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Billing"><i class="fa fa-file"></i></a><a class="labbilling btn btn-warning shadow btn-xs sharp mr-1" href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Lab Billing"><i class="fa fa-flask"></i></a></div>'
                }
            },
        ],

        rowCallback: function(row, data) {
        if (data.ip_admission_id > 0) {
            $(row).addClass('highlight');
        }
    }

    });
    });


    {{--$('#visit_lists tbody').on('click', '.rowclick', function() {--}}
    {{--    console.log($(this).parent());--}}
    {{--   // return;--}}
    {{--    var data = table.row($(this).parent().parent()).data();--}}
    {{--   // console.log(data);--}}
    {{--     window.location.href = '{{url("dtmshome")}}/'+data.patient_id;--}}
    {{--    // window.location.href = '{{url("dtmshome")}}/180';--}}


    {{--});--}}
    $('#visit_lists tbody').on('click', '.rowclick-dtmshome', function() {
        console.log($(this).parent());
       // return;
        var data = table.row($(this).parent().parent()).data();
       // console.log(data);
         window.location.href = '{{url("dtmshome")}}/'+data.patient_id;
        // window.location.href = '{{url("dtmshome")}}/180';


    });
    $(document).ready(function() {


        $('.search-btn').click(function(e){
            e.preventDefault();
            table.ajax.reload();
        })

        $('#to_date').datepicker({
                autoclose: true,
                // endDate: '+0d',
                format: 'dd-mm-yyyy'
        });
        $('#from_date').datepicker({
                autoclose: true,
                // endDate: '+0d',
                format: 'dd-mm-yyyy'
        });
    });

    $('#visit_lists tbody').on('click', '.edit_visit_list', function() {
        $("[id*='_error']").text('');
         var data = table.row($(this).parents('tr')).data();
        $("#hid_visit_id").val(data.id);
        $("#visit_type_id").val(data.visit_type_id).change();
        $("#specialist").val(data.specialist_id).change();
        $('#visit_date').val(data.visit_date);
        $('#visit-list-modal').modal();

    });
    $('#visit_lists tbody').on('click', '.opdbilling', function() {
        var data = table.row($(this).parent().parent().parent()).data();
        var billingType = 1;
        $.redirect('{{url("opdbilling")}}', { _token: "{{ csrf_token() }}", patient_id: data.patient_id, ipd_id: data.ip_admission_id, visit_id: data.id, billing_type: billingType}, 'POST');

    });

    $('#visit_lists tbody').on('click', '.labbilling', function() {

        var data = table.row($(this).parent().parent().parent()).data();
        var billingType = 3;
        $.redirect('{{url("opdbilling")}}', { _token: "{{ csrf_token() }}", patient_id: data.patient_id, ipd_id: data.ip_admission_id, visit_id: data.id, billing_type: billingType}, 'POST');

    });

</script>
