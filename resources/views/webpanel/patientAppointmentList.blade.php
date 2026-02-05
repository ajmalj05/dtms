<link href='date_picker/css/bootstrap-datepicker.min.css' rel='stylesheet' type='text/css'>

<div class="content-body">
    <div class="container-fluid pt-2">



            <form name="frm" id="frm" action="#" >


                      <div class="col-xl-12 col-xxl-12 col-lg-12 col-sm-12">



                          <section>
                                      <div class="row">

                                          <div class="col-xl-3 col-md-3 mb-2">
                                            <div class="form-group">
                                                <label class="text-label">UHID No.</label>
                                                    <input type="text" class="form-control search_by" name="uhid" id="s_uhid" value="" >


                                            </div>
                                          </div>
                                          <div class="col-xl-3 col-md-6 mb-2">
                                              <div class="form-group">
                                                  <label class="text-label">From Date</label>
                                                  <input type="text" name="from_date" id="from_date" class="form-control"  value="<?=date('d-m-Y',strtotime("-1 day"))?>" placeholder="" >
                                                  <small id="name_error" class="form-text text-muted error"></small>
                                              </div>
                                          </div>


                                          <div class="col-xl-3 col-md-3 mb-2">
                                              <div class="form-group">
                                                  <label class="text-label">To Date </label>


                                                  <input type="text" name="to_date" id="to_date"  value="<?=date('d-m-Y',strtotime("7 day"));?>" class="form-control" placeholder="" >
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
                                                  <div class="d-flex"  data-toggle="tooltip" data-placement="bottom" title="Create new Appointment">
                                                <a href="{{route('appointment')}}" class="edit btn btn-primary shadow btn-xs sharp mr-1"> <i class="fa fa-plus"></i></a></div>
                                                </div>
                                              </div>




                                      </div>

                          </section>
                      </div>


            </form>
            <div style="min-height:900px">
            <div class="row">
              <div class="col-md-12">
                  <div class="card">

                      <div class="card-body">
                          <div class="table-responsive">
                              <table id="example4" class="display" style="min-width: 845px">
                                  <thead>
                                      <tr>
                                          <th>UHID No</th>
                                          <th>Name</th>
                                          <th>Age</th>
                                          <th>Sex </th>
                                          <th>Mobile No.</th>
                                          <th>Department </th>
                                          <th>Consultant</th>
                                          <th>Appointment Status</th>
                                          <th>Appointment Date</th>
                                          <th>Appointment Type</th>
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
<style>
 .modal-lg {
    max-width: 80% !important;
}
.search-btn{
  padding:0.375rem 0.75rem!important;
}
</style>
@include('frames/footer');

<link rel="stylesheet" href="{{asset('/vendor/select2/css/select2.min.css')}}">
<link href="{{asset('/vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
<script src="{{asset('date_picker/js/bootstrap-datepicker.min.js')}}" type='text/javascript'></script>
<script src="{{asset('./vendor/select2/js/select2.full.min.js')}}"></script>
<script src="./js/plugins-init/select2-init.js"></script>

<script>
  $(document).ready(function() {

    tablereload();
    $('.search-btn').click(function(e){
        e.preventDefault();
        table.ajax.reload();
    })
    $('#to_date').datepicker({
            autoclose: true,
            // endDate: '+3d',
            format: 'dd-mm-yyyy'
    });
    $('#from_date').datepicker({
            autoclose: true,
            // endDate: '+0d',
            format: 'dd-mm-yyyy'
    });
      $(".search_by").keydown(function(event) {
          if (event.which === 13)
          {
              event.preventDefault();
              table.ajax.reload();

          }
      });
  });

  function tablereload(){
      $("#example4").dataTable().fnDestroy()
      table = $('#example4').DataTable({
            // scrollY: 470,
            "order": [] ,
            "autoWidth": false,
            "dom": 'Blfrtip',
            "destroy" : true,

        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        'ajax': {
            url: "<?php echo url('/') ?>/searchPatientAppointment",
            type: 'POST',
            "data": function(d) {

                d._token= "{{ csrf_token() }}";
                d.uhid= $('#s_uhid').val();
                d.mobile_number= $('#s_mobile_number').val();
                d.patient_type= $('#patient_type').val();
                d.patient_name= $('#s_patient_name').val();
                d.last_name= $('#last_name').val();
                d.gender= $('#gender').val();
                d.age= $('#age').val();
                d.address= $('#address').val();
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
                {{--render: function(data, type, row, meta) {--}}
                {{--    if(data==0){--}}
                {{--        return '---';--}}
                {{--    }--}}
                {{--    return "<?= Session::get('current_branch_code') ?> -"+ data;--}}
                {{--}--}}
            },
            {
                "data": "patientname"
            },
            {
                "data": "dob",
                "render":function(dob, type, full, meta)
                {
                    if(dob){
                    var tempdob=dob;
                        dob = new Date(dob);
                        var today = new Date();
                        var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
                        //  return age + "- " +tempdob;
                        return age +' yrs' ;
                    }else{
                        return '';
                    }
                }
            },
            {
                "data": "gender",
                "render": function(data, type, full, meta) {
                    return (data==0) ? 'NA' :data;
                }
            },

            {
                "data": "mobile_number"
            },
            {
                "data": "department_name"
            },
            {
                "data": "specialist_name"
            },
            {
                "data": "is_cancellled",
                "render": function(data, type, row, meta) {
                if(data==1)
                {
                 return 'Appointment Cancelled';
            }
                else  
                {
                    return '   ---   ';
            
            }
                
                }
            },
            {
                "data": "appointment_date",
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
                        return date.getDate() + "/" + (month.toString().length > 1 ? month : "0" + month) + "/" +   date.getFullYear() + ' '+ hrs

                    }else{
                        return '';
                    }
                },
            },
            {
                "data": "appointment_type",
                "render": function(appointment_type, type, full, meta) {
                    if (appointment_type == 1){
                        return '<span>Online</span>';
                    } 
                    else if(appointment_type == 2){
                        return '<span>Offline</span>';
                    } 
                    else if(appointment_type == 3){
                        return '<span>Mobile app</span>';
                    }
                }
            },

            {
                "data": "id",
                "render": function(display_status, type, full, meta) {

                    if(full.patient_id ==""){
                        return '<div class="d-flex"><a data-toggle="tooltip" data-placement="bottom" title="Edit Appointment"  href="#" class="edit btn btn-primary shadow btn-xs sharp mr-1 edit_details"><i class="fa fa-pencil"></i></a> <a href="#" data-toggle="tooltip" data-placement="bottom" title="Link Registration" class="edit btn btn-primary shadow btn-xs sharp mr-1 link_details"><i class="fa fa-link"></i></a></div>'
                    }else{

                        return '<div class="d-flex"><a href="#" class="edit btn btn-primary shadow btn-xs sharp mr-1 edit_details"><i class="fa fa-pencil"></i></a> <a href="#" data-toggle="tooltip" data-placement="bottom" title="Edit patient details" class="edit btn btn-primary shadow btn-xs sharp mr-1 patient_details"><i class="fa fa-user-circle-o"></i></a> <a href="#" data-toggle="tooltip" data-placement="bottom" title="Open DTMS" class="edit btn btn-primary shadow btn-xs sharp mr-1 dtms_home"><i class="fa fa-hospital-o"></i></a></div>'
                    }

                }
            },
        ]
        });

  }

  $('#example4 tbody').on('click', '.edit_details', function() {
        var data = table.row($(this).parents('tr')).data();

        window.location.href = '{{url("appointment")}}?id='+data.id;
  });
  $('#example4 tbody').on('click', '.dtms_home', function() {
        var data = table.row($(this).parents('tr')).data();
      window.location.href = '{{url("dtmshome")}}/'+data.patient_id;
  });

  $('#example4 tbody').on('click', '.link_details', function() {

        var data = table.row($(this).parents('tr')).data();
        _token="{{csrf_token()}}";
        $.ajax({
		    type: "POST",
			url: '{{route('linkpatientAppointment')}}',
			data: {id:data.id,_token:_token},

			success: function(result){
                    if (result.status == 1) {

                        swal("Done", result.message, "success");

                    }
                    else if (result.status == 2) {
                        swal("Done", result.message, "success");

                    }
                    else {
                        sweetAlert("Oops...", result.message, "failed");
                    }
                    table.ajax.reload();
                }

			 });


  });

  $('#example4 tbody').on('click', '.patient_details', function() {

        var data = table.row($(this).parents('tr')).data();
        window.location.href = '{{url("patientRegistration")}}?id='+ data.patient_id;
  });


  $('#example4 tbody').on('click', 'tr', function() {
         var data = table.row($(this)).data();
        //  console.log(data);
         $('#a_salutation_id').val(data.salutation_id);
         $('#a_patientname').val(data.name);
         $('#a_last_name').val(data.last_name);
         $('#a_gender').val(data.gender?.trim());
         $('#a_dob').val(data.dob);
         $('#a_age').val(data.age);
         $('#a_mobile_number').val(data.mobile_number);
         $('#a_email_address').val(data.email);
        //  $('#patientname').val(data.name);


         $('#addModal').modal('toggle');
  });
</script>
