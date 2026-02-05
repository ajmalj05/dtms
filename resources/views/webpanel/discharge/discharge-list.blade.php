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







                                      </div>

                          </section>
                      </div>


            </form>
            <div class="row" style="">
              <div class="col-md-12">
                  <div class="card">

                      <div class="card-body">
                          <div class="table-responsive">
                              <table id="ipd-table" class="display" style="min-width: 845px">
                                  <thead>
                                      <tr>
                                          <th>UHID No</th>
                                          <th>Name</th>
                                          <th>Age</th>
                                          <th>Sex </th>
                                          <th>Mobile No.</th>
                                          <th>Department </th>
                                          <th>Consultant</th>
                                          <th>Admit Date</th>
                                          <th>Discharge Date</th>
                                          <th>Ward No</th>
                                          <th>Bed No</th>
                                          <th>Policy No</th>
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
<style>
 .modal-lg {
    max-width: 80% !important;
}
.search-btn{
  padding:0.375rem 0.75rem!important;
}
</style>
@include('frames/footer');

<link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
<link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
<script src='date_picker/js/bootstrap-datepicker.min.js' type='text/javascript'></script>
<script src="./vendor/select2/js/select2.full.min.js"></script>
<script src="./js/plugins-init/select2-init.js"></script>
<script src="{{asset('/js/jquery-redirect.js')}}"></script>

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

      $('#discharge_date').datepicker({
          autoclose: true,
          // endDate: '+10d',
          startDate: '+0d',
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
      $("#ipd-table").dataTable().fnDestroy()
      table = $('#ipd-table').DataTable({
            // scrollY: 470,
            "order": [] ,
            "autoWidth": false,
            "dom": 'Blfrtip',
            "destroy" : true,

        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        'ajax': {
            url: "<?php echo url('/') ?>/search-discharge",
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
                {{--"data": "patient_id",--}}
                {{--render: function(data, type, row, meta) {--}}
                {{--    return "<?= Session::get('current_branch_code') ?> -"+ data;--}}
                {{--}--}}
            },
            {
                "data": "name"
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
                "data": "admission_date",
                "render": function(data, type, full, meta) {
                        var date = new Date(data);
                        var month = date.getMonth() + 1;
                        return ('0' + date.getDate()).slice(-2) + "/" + (month.toString().length > 1 ? month : "0" + month) + "/" +   date.getFullYear()
                },
            },
            {
                "data": "discharge_date",
                "render": function(data, type, full, meta) {
                        var date = new Date(data);
                        var month = date.getMonth() + 1;
                        return ('0' + date.getDate()).slice(-2) +  "/" + (month.toString().length > 1 ? month : "0" + month) + "/" +   date.getFullYear()
                },
            },
            {
                "data": "ward_number",
            },
            {
                "data": "bed_number",
            },
            {
                "data": "policy_number",
            },

            {
                "data": "id",
                "render": function(display_status, type, full, meta) {
                        return '<div class="d-flex"><a href="#" data-toggle="tooltip" data-placement="bottom" title="Edit patient details" class="edit btn btn-primary shadow btn-xs sharp mr-1 patient_details"><i class="fa fa-user-circle-o"></i></a> <a class="ipdbilling btn btn-primary shadow btn-xs sharp mr-1" href="javascript:void(0)" data-toggle="tooltip" data-placement="right" title="Billing"><i class="fa fa-file"></i></a></div>'
                }
            },
        ]
        });

  }



  $('#ipd-table tbody').on('click', '.ipdbilling', function() {
        var data = table.row($(this).parents('tr')).data();
        var billingType = 2;
      $.redirect('{{url("ipd-billing")}}', { _token: "{{ csrf_token() }}", patient_id: data.patient_id, ipd_id: data.id, billing_type: billingType}, 'POST');

  });


  $('#ipd-table tbody').on('click', '.patient_details', function() {

        var data = table.row($(this).parents('tr')).data();
        window.location.href = '{{url("patientRegistration")}}?id='+ data.patient_id;
  });


  $('#ipd-table tbody').on('click', 'tr', function() {
         var data = table.row($(this)).data();
          var dob = new Date(data.dob);
          var today = new Date();
          var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
         $('#a_salutation_id').val(data.salutation_id);
         $('#a_patientname').val(data.name);
         $('#a_last_name').val(data.last_name);
         $('#a_gender').val(data.gender?.trim());
         $('#a_dob').val(data.dob);
         $('#a_age').val(age);
         $('#a_mobile_number').val(data.mobile_number);
         $('#a_email_address').val(data.email);
        //  $('#patientname').val(data.name);


         $('#addModal').modal('toggle');
  });
</script>
