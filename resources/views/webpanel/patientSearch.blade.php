
<link href='date_picker/css/bootstrap-datepicker.min.css' rel='stylesheet' type='text/css'>
<div class="content-body">
    <div class="container-fluid pt-2">


        <div class="row">
            <div class="col-md-12">


            <div class="card">
                <div class="card-body">

                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label>UHID No.</label>
                             <!---01.11.24-->
                    &nbsp;<input type="checkbox" id="searchFilter" name="searchFilter"> Search Filter
                            <input type="text" class="form-control search_by " id="uhid" name="uhid" placeholder="UHID No">
                        </div>

                        <div class="form-group col-md-2">
                            <label>Mobile Number</label>
                            <input type="text" class="form-control search_by" id="mobile_number" name="mobile_number" placeholder="Mobile Number">
                        </div>


                        <div class="form-group col-md-2">
                            <label>Patient Type</label>
                            <select id="patient_type" name="patient_type" class="form-control">
                                <option  value="" selected>Choose...</option>
                                {{LoadCombo("patient_type_master","id","patient_type_name",'','where display_status=1 AND is_deleted=0',"order by id desc");}}

                            </select>
                        </div>





                        <div class="form-group col-md-2">
                            <label>Patient Name</label>
                            <input type="text" class="form-control search_by" id="patient_name" name="patient_name" placeholder="Patient Name">
                        </div>


                        <div class="form-group col-md-2">
                            <label>Surname</label>
                            <input type="text" name="last_name" id="last_name" value="" class="form-control search_by" placeholder="Surname">
                        </div>

                        <div class="form-group col-md-2">
                            <label>Gender</label>
                            <select id="gender"  name="gender" class="form-control">
                                <option  value=""  selected>Choose...</option>
                                 <option value="m">Male</option>
                                <option value="f">Female</option>
                                <option value="o">Other</option>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label>Age</label>
                            <input type="number" name="age" value="" id="age" class="form-control search_by" placeholder="Age">

                        </div>
                        <div class="form-group col-md-4">
                            <label>Address</label>
                            <input type="text"  name="address" value="" id="address" class="form-control search_by" placeholder="Address">

                        </div>


                        <div class="form-group col-md-2">
                            <label>Reg From Date</label>
                            <input type="text" class="form-control" id="from_date" name="from_date" placeholder="From Date" value="">
{{--                            <input type="text" class="form-control" id="from_date" name="from_date" placeholder="From Date">--}}
                        </div>
                        <div class="form-group col-md-2">
                            <label>Reg To Date</label>
                            <input type="text" value="<?= date('d-m-Y')?>" class="form-control" id="to_date" name="to_date" placeholder="To Date">
                        </div>
                        <div id="crud">
                        <div class="form-group col-md-2 align-items-center justify-content-sm-center">
                            <br>
                            <a id="search_btn" class="btn btn-primary inline-flex items-center px-4 py-2" style="color:white">Search</a>
                        </div>
                    </div>

                    </div>

                    <div class="form-row">




                    </div>

                </div>
            </div>
        </div>
        </div>


        <div class="row" style="min-height: 800px">
            <div class="col-md-12">
                <div class="card">
                    {{-- <div class="card-header">
                        <h4 class="card-title">Fees Collection</h4>
                    </div> --}}
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example4" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>UHID No</th>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th>Sex </th>
                                        <th width="25%">Address </th>
                                        <th>Mobile No</th>
                                        <th>Admission Date</th>
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

@include('frames/footer');
<link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
<link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
<script src='date_picker/js/bootstrap-datepicker.min.js' type='text/javascript'></script>
<script src="./vendor/select2/js/select2.full.min.js"></script>
<script src="./js/plugins-init/select2-init.js"></script>
<!-- <script src='date_picker/js/bootstrap-datepicker.min.js' type='text/javascript'></script> -->

<script>
  $(document).ready(function() {
      $("#example4").dataTable().fnDestroy()
    table = $('#example4').DataTable({
        // scrollY: 470,
        "order": [] ,
        "autoWidth": false,
        "dom": 'Blfrtip',

    // dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    'ajax': {
        url: "<?php echo url('/') ?>/searchPatient",
        type: 'POST',
        "data": function(d) {

            d._token= "{{ csrf_token() }}";
            d.uhid= $('#uhid').val();
            d.mobile_number= $('#mobile_number').val();
            d.patient_type= $('#patient_type').val();
            d.patient_name= $('#patient_name').val();
            d.last_name= $('#last_name').val();
            d.gender= $('#gender').val();
            d.age= $('#age').val();
            d.address= $('#address').val();
            d.from_date= $('#from_date').val();
            d.to_date= $('#to_date').val();
            d.searchFilter = $('#searchFilter').is(':checked');  //<!---01.11.24-->
        }
    },
    "columns": [
        {
    "data": "uhidno",
    "render": function(data, type, row, meta) {
        var currentBranchCode = "<?php echo Session::get('current_branch_code'); ?>";
        return '<a href="#' + currentBranchCode + '-' + data + '" class="open_dtms">' + data + '</a>';
    }
},

        {
            "data": "name",
            "render": function(data, type, full, meta) {
                    return '<a href="#" class="open_dtms">' + data + "</a>";
    }
        },
        {
            "data": "dob",
            "render":function(dob, type, full, meta)
            {
                var tempdob=dob;
                dob = new Date(dob);
                var today = new Date();
                var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
              //  return age + "- " +tempdob;
              return '<a href="#" class="open_dtms">' + age + "</a>";
            }
        },
        {
            "data": "gender",
            "render": function(data, type, full, meta) {
                if (data == 0) {
                    return 'NA';
                } else {
                    return '<a href="#" class="open_dtms">' + data + "</a>";
                }
            }
        },
        {
            "data": "address",
            "render": function(data, type, full, meta) {
                    return '<a href="#" class="open_dtms">' + data + "</a>";
    }
        },
        {
                "data": "mobile_number",
                "render": function(data, type, full, meta) {
                    return '<a href="#" class="open_dtms">' + data + "</a>";
    }
},
        {
            "data": "created_at",
            "render": function(data, type, full, meta) {
                var date = new Date(data);
                var month = date.getMonth() + 1;
                return '<a href="#" class="open_dtms">' + date.getDate() + "/" + (month.toString().length > 1 ? month : "0" + month) + "/" +   date.getFullYear() +"</a>";


            },
        },

        {
            "data": "id",
            "render": function(display_status, type, full, meta) {
                return '<div class="d-flex"><a href="#" class="edit btn btn-primary shadow btn-xs sharp mr-1 view_details" ><i class="fa fa-pencil" data-toggle="tooltip" data-placement="bottom" title="Edit Patient Details"></i></a> <a href="#" class="edit btn btn-primary shadow btn-xs sharp mr-1 open_dtms" data-toggle="tooltip" data-placement="bottom" title="Open DTMS"><i class="fa fa fa-hospital-o"></i></a></div>'
            }
        },
    ]
    ,
    "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

        switch(aData['status']){
            // alert(aData['status']);
            case 1:
                $('td', nRow).addClass('tablerowActive');
                break;
            case 0:
                $('td', nRow).addClass('tablerowInActive');
                break;
            case 2:
                $('td', nRow).addClass('tablerowExpired');
                break;
        }
    },
    });
});


    $('#example4 tbody').on('click', '.edit', function() {
         var data = table.row($(this).parents('tr')).data();
         window.location.href = '{{url("patientRegistration")}}?id='+data.id;

    });
    $('#search_btn').click(function(e){

        table.ajax.reload();
    });

    $('#example4 tbody').on('click', '.open_dtms', function() {
         var data = table.row($(this).parents('tr')).data();
         window.location.href = '{{url("dtmshome")}}/'+data.id;

    });

  $(".search_by").keydown(function(event) {
      if (event.which === 13)
      {
          event.preventDefault();
          table.ajax.reload();

      }
  });

  $(document).ready(function() {

      $('#from_date').datepicker({
          autoclose: true,
          // endDate: '+0d',
          format: 'dd-mm-yyyy'
      });
      $('#to_date').datepicker({
          autoclose: true,
          // endDate: '+0d',
          format: 'dd-mm-yyyy'
      });
  });
</script>
<style>


  table.dataTable tbody tr, table.dataTable tbody td{
    background-color: unset!important;
  }
  table.dataTable tbody .tablerowActive{
    color:  green!important;
  }
  table.dataTable tbody .tablerowInActive{
    color:  blue!important;
  }
  table.dataTable tbody .tablerowExpired{
    color:  red!important;

  }

  </style>

