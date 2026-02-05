<div class="content-body" style="min-height: 800px">
    <div class="container-fluid pt-2">


        <div class="row">
            <div class="col-md-12">


            <div class="card">
                <div class="card-body">

                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label>UHID No.</label>
                            <input type="text" class="form-control" id="uhid" name="uhid" placeholder="UHID No">
                        </div>

                        <div class="form-group col-md-2">
                            <label>Mobile Number</label>
                            <input type="text" class="form-control" id="mobile_number" name="mobile_number" placeholder="Mobile Number">
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
                            <input type="text" class="form-control" id="patient_name" name="patient_name" placeholder="Patient Name">
                        </div>


                        <div class="form-group col-md-2">
                            <label>Surname</label>
                            <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="Surname">
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
                            <input type="number" name="age" value="" id="age" class="form-control" placeholder="Age">

                        </div>
                        <div class="form-group col-md-4">
                            <label>Address</label>
                            <input type="text"  name="address" value="" id="address" class="form-control" placeholder="Address">

                        </div>


                        <div class="form-group col-md-2">
                            <label>Reg From Date</label>
                            <input type="date" class="form-control" id="from_date" name="from_date" placeholder="From Date" value="<?= date('Y-m-d',strtotime("-1 days"))?>">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Reg To Date</label>
                            <input type="date" value="<?= date('Y-m-d')?>" class="form-control" id="to_date" name="to_date" placeholder="To Date">
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


        <div class="row" style="">
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
                                        <th>Address </th>
                                        <th>Mobile No</th>
                                        <th>Admission Date</th>
                                        <th>Actions</th>

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
<!-- <script src='date_picker/js/bootstrap-datepicker.min.js' type='text/javascript'></script> -->

<script>
  $(document).ready(function() {

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

            }
        },
        "columns": [
            {
                "data": "id",
                // render: function(data, type, row, meta) {
                //     return meta.row + meta.settings._iDisplayStart + 1;
                // }
                render: function(data, type, row, meta) {
                    return "<?= Session::get('current_branch_code') ?> -"+ data;
                }
            },
            {
                "data": "name"
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
                return age ;
                }
            },
            {
                "data": "gender",
                "render": function(data, type, full, meta) {
                    return (data==0) ? 'NA' :data;
                }
            },
            {
                "data": "address"
            },
            {
                "data": "mobile_number"
            },
            {
                "data": "created_at",
                "render": function(data, type, full, meta) {
                    var date = new Date(data);
                    var month = date.getMonth() + 1;
                    return date.getDate() + "/" + (month.toString().length > 1 ? month : "0" + month) + "/" +   date.getFullYear();


                },
            },

            {
                "data": "id",
                "render": function(display_status, type, full, meta) {
                    return " <div class='dropdown'><button class='btn btn-success dropdown-toggle' type='button' data-toggle='dropdown'>Actions<span class='caret'></span></button><ul class='dropdown-menu'><li><a href='#' class='billing'>Billing</a></li><li><a href='#' class='result'>Test Result</a></li></ul></div>";
                }
            },
        ]
    });
});


    $('#example4 tbody').on('click', '.edit', function() {

         var data = table.row($(this).parents('tr')).data();
         window.location.href = '{{url("patientRegistration")}}?id='+data.id;
        // $("#hid_dep_id").val(data.id);
        // $("#department_name").val(data.department_name.trim());
        // if(data.display_status==1)  $('#display_status').prop("checked", true); else  $('#display_status').prop("checked", false);
        // crude_btn_manage(2,1);
    });

    $('#search_btn').click(function(e){

        table.ajax.reload();
    });

    $('#example4 tbody').on('click', '.billing', function () {


        var data=table.row($(this).parents('tr')).data();
        var id=data.id;
        window.location.href = '{{url("getPatientLabdetails")}}?id='+data.id;

    });
    $('#example4 tbody').on('click', '.result', function () {


        var data=table.row($(this).parents('tr')).data();
        var id=data.id;
        window.location.href = '{{url("resultentry")}}?id='+data.id;

    });

</script>

<style>
    .dropdown-menu li{
        padding:10px;
    }
    .btn{
        padding:5px;
    }
</style>
