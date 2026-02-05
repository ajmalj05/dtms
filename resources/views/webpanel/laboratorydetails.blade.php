<div class="content-body" >
    <div class="container-fluid pt-2" >


        <div class="row">
            <div class="col-md-12">


            <div class="card">
                <div class="card-body">

                    <div class="form-row">
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Patient Name</label>
                                </div>
                                <div class="col-md-4">
                                    : <b>Siaz</b>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Age</label>
                                </div>
                                <div class="col-md-4">
                                    : 36 yrs
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Consultant</label>
                                </div>
                                <div class="col-md-4">
                                    : Dr.XYZ
                                </div>
                            </div>
                           
                       
                        </div>
                        <div class="col-md-2">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Date</label>
                                </div>
                                <div class="col-md-8">
                                    : <?= date('d-m-Y')?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Bill No</label>
                                </div>
                                <div class="col-md-8">
                                    : T134321
                                </div>
                            </div>

                           
                        </div>
                    </div>

                    <div class="form-row">
                        {{-- <div class="form-group col-md-2">
                            <label>Date.</label>
                            <input type="date" class="form-control" id="from_date" name="from_date" placeholder="From Date" value="<?= date('Y-m-d',strtotime("-1 days"))?>">
                        </div>

                        <div class="form-group col-md-2">
                            <label>Bill No.</label>
                            <input type="text" class="form-control" id="mobile_number" name="mobile_number" value="T134321">
                        </div> --}}

                        {{-- <div class="form-group col-md-2">
                            <label>Category</label>
                            <select id="patient_type" name="patient_type" class="form-control">
                                <option  value="" selected>Choose...</option>
                                {{LoadCombo("patient_type_master","id","patient_type_name",'','where display_status=1 AND is_deleted=0',"order by id desc");}}

                            </select>
                        </div> --}}
                        {{-- <div class="form-group col-md-2">
                            <label>Payment</label>
                            <select id="gender"  name="gender" class="form-control">
                                <option  value=""  selected>Choose...</option>
                                 <option value="m">Normal</option>
                               
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label>Doctor Name</label>
                            <select id="gender"  name="gender" class="form-control">
                                <option  value=""  selected>Choose...</option>
                                 <option value="m">Normal</option>
                               
                            </select>
                        </div> --}}

                    </div>


                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label style="opacity: 0">Test Name</label>
                            <select id="search" >
                                <option>Select Test</option>
                                <option value="Cultures">Cultures</option>
                                <option>Hemoglobin A1C</option>
                                <option>Urinalysis</option>
                                <option>Liver Panel</option>
                                <option>Lipid Panel</option>
                                <option>Comprehensive Metabolic Panel</option>
                                <option>CBC</option>
                                <option>LFT</option>
                                <option>HB</option>
                            </select>
                            
                        </div>
                    </div>
                    <div class="form-row">
                        

                       





                        {{-- <div class="form-group col-md-2">
                            <label>Test Name</label>
                            <input type="text" class="form-control" id="test_name" name=""  readonly placeholder="Name">
                        </div>


                        <div class="form-group col-md-2">
                            <label>Item Code</label>
                            <input type="text" name="test_code" id="test_code" readonly class="form-control" placeholder="Code">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Quantity</label>
                            <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="Quantity">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Amount</label>
                            <input type="text" name="test_amt" id="test_amt" readonly value="" class="form-control" placeholder="Amount">
                        </div>
                        <div class="form-group col-md-1">
                            <label>Dis in %</label>
                            <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="Dsc %">
                        </div>
                        <div class="form-group col-md-1">
                            <label>Dis Amt</label>
                            <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="Dsc Amt">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Net Amt</label>
                            <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="Amt">
                        </div> --}}
                       

                        

                        
                        
                    </div>

                    <table class="table table-striped">
                        <tbody id="append_data">
                        </tbody>
                    </table>
                   

                   

                    <hr>
                    {{-- <div id="crud">
                        <div class="form-group col-md-2 align-items-center justify-content-sm-center">
                            <br>
                            <a id="add_duplicate" class="btn btn-primary inline-flex items-center px-4 py-2" style="color:white">Add</a>
                        </div>

                    </div> --}}


                    <div class="details row">
                       

                        <div class="form-group col-md-2">
                            <label>Total Amt.</label>
                            <input type="text" class="form-control" readonly id="total_amount" name="total_amount" placeholder="">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Payment Mode </label>
                            <select id="patient_type" name="patient_type" class="form-control">
                                <option  value="" selected>Choose...</option>
                                {{LoadCombo("patient_type_master","id","patient_type_name",'','where display_status=1 AND is_deleted=0',"order by id desc");}}

                            </select>
                        </div>
                       
                        <div class="form-group col-md-2">
                            <label>Dis in %</label>
                            <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                        </div>

                        <div class="form-group col-md-2">
                            <label>Total Bill Amt</label>
                            <input type="text" name="totalbill_amount" id="totalbill_amount" value="" class="form-control" placeholder="" readonly>
                        </div>

                        <div class="form-group col-md-2">
                            <label>Total Paid</label>
                            <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group col-md-2">
                            <label>Transaction ID</label>
                            <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                        </div>

                    </div>
                    {{-- <div class="details row">

                        <div class="form-group col-md-3">
                            <label>Dis in %</label>
                            <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group col-md-3">
                            <label></label>
                            <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group col-md-3">
                            <label></label>
                            <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group col-md-3">
                            <label></label>
                            <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                        </div>

                        
                    </div> --}}
                    {{-- <div class="details row">

                        <div class="form-group col-md-3">
                            <label>Total Paid</label>
                            <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                        </div>
                        
                        <div class="form-group col-md-3">
                            <label>Single Payment Mode 2</label>
                            <select id="patient_type" name="patient_type" class="form-control">
                                <option  value="" selected>Choose...</option>
                                {{LoadCombo("patient_type_master","id","patient_type_name",'','where display_status=1 AND is_deleted=0',"order by id desc");}}

                            </select>
                        </div>
                     

                        <div class="form-group col-md-3">
                            <label>Total Credit</label>
                            <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                        </div>
                    </div>
                    <div class="details row">
                        <div class="form-group col-md-3">
                            <label>Total Pending</label>
                            <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                        </div>
                       
                        <div class="form-group col-md-3">
                            <label>IPD Bill No.</label>
                            <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Edit Bill</label>
                            <input type="text" name="last_name" id="last_name" value="" class="form-control" placeholder="">
                        </div>
                    </div> --}}


<br>
                    <a class="btn btn-primary inline-flex items-center px-4 py-2" style="color:white">Save</a>
                        
                    </div>

                   

                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid pt-2" >
        <div class="row" >
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
                                        <th>Date</th>
                                        <th>Bill No.</th>
                                        <th>Category </th>
                                        <th>Item</th>
                                       
                                        <th>Item Code </th>
                                        <th>Qty</th>
                                        <th>Amt</th>
                                        <th>Dis %</th>
                                        <th>Dis Amt</th>
                                        <th>Net Amt</th>
                                        <th>Payment Type</th>
                                        <th>Doctor Name</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        <th>Actions</th>

                                    </tr>
                                </thead>
                                <tbody id="search_filter">

                                    <tr>
                                        <td>23-09-2021</td>
                                        <td>LAE</td>
                                        <td>LAB</td>
                                        <td>HbA1c</td>
                                        <td></td>
                                        <td>1</td>
                                        <td>100</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>100</td>
                                        <td>Normal</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><i class="fa fa-print"></i>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" >&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-trash" style="color:red;"></i></td>
                                       

                                    </tr>


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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


<script>
  $(document).ready(function() {

    table = $('#example4').DataTable();

    // table = $('#example4').DataTable({
    //     // scrollY: 470,
    //     "order": [] ,
    //     "autoWidth": false,
    //     "dom": 'Blfrtip',

    // // dom: 'Bfrtip',
    //     buttons: [
    //         'copy', 'csv', 'excel', 'pdf', 'print'
    //     ],
    //     'ajax': {
    //         url: "<?php echo url('/') ?>/searchPatient",
    //         type: 'POST',
    //         "data": function(d) {

    //             d._token= "{{ csrf_token() }}";
    //             d.uhid= $('#uhid').val();
    //             d.mobile_number= $('#mobile_number').val();
    //             d.patient_type= $('#patient_type').val();
    //             d.patient_name= $('#patient_name').val();
    //             d.last_name= $('#last_name').val();
    //             d.gender= $('#gender').val();
    //             d.age= $('#age').val();
    //             d.address= $('#address').val();
    //             d.from_date= $('#from_date').val();
    //             d.to_date= $('#to_date').val();

    //         }
    //     },
    //     "columns": [
    //         {
    //             "data": "id",
    //             // render: function(data, type, row, meta) {
    //             //     return meta.row + meta.settings._iDisplayStart + 1;
    //             // }
    //             render: function(data, type, row, meta) {
    //                 return "<?= Session::get('current_branch_code') ?> -"+ data;
    //             }
    //         },
    //         {
    //             "data": "name"
    //         },
    //         {
    //             "data": "dob",
    //             "render":function(dob, type, full, meta)
    //             {
    //                 var tempdob=dob;
    //                 dob = new Date(dob);
    //                 var today = new Date();
    //                 var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
    //             //  return age + "- " +tempdob;
    //             return age ;
    //             }
    //         },
    //         {
    //             "data": "gender",
    //             "render": function(data, type, full, meta) {
    //                 return (data==0) ? 'NA' :data;
    //             }
    //         },
    //         {
    //             "data": "address"
    //         },
    //         {
    //             "data": "mobile_number"
    //         },
    //         {
    //             "data": "created_at",
    //             "render": function(data, type, full, meta) {
    //                 var date = new Date(data);
    //                 var month = date.getMonth() + 1;
    //                 return date.getDate() + "/" + (month.toString().length > 1 ? month : "0" + month) + "/" +   date.getFullYear();


    //             },
    //         },

    //         // {
    //         //     "data": "id",
    //         //     "render": function(display_status, type, full, meta) {
    //         //         return '<div class="d-flex"><a href="#" class="edit btn btn-primary shadow btn-xs sharp mr-1 view_details"><i class="fa fa-pencil"></i></a></div>'
    //         //     }
    //         // },
    //     ]
    // });
});


    $('#example4 tbody').on('click', '.edit', function() {
         var data = table.row($(this).parents('tr')).data();
         window.location.href = '{{url("patientRegistration")}}?id='+data.id;
        // $("#hid_dep_id").val(data.id);
        // $("#department_name").val(data.department_name.trim());
        // if(data.display_status==1)  $('#display_status').prop("checked", true); else  $('#display_status').prop("checked", false);
        // crude_btn_manage(2,1);
    });

    // $('#add_duplicate').click(function(e){
    function add_duplicate(name,code,amt){
        var html="";
        html+="<tr>";
          
            html+=" <td><input type='text' class='form-control' id='patient_name' name='patient_name' placeholder='Name' value="+name+" readonly></td>";
            
            html+="<td><input type='text' name='last_name' id='last_name'  class='form-control' placeholder='Code' value="+code+" readonly></td>";
         
            html+="<td><input type='text' name='last_name' id='last_name' value='' class='form-control' placeholder='Quantity' ></td>";

            html+="<td><input type='text' name='last_name' id='last_name'  class='form-control totalamt' placeholder='Amount' value="+amt+" readonly></td>";

            html+="<td><input type='text' name='last_name' id='last_name' value='' class='form-control' placeholder='Dsc %'></td>";

            html+="<td><input type='text' name='last_name' id='last_name' value='' class='form-control' placeholder='Dsc Amt'></td>";

            html+="<td><input type='text' name='last_name' id='last_name' value='' class='form-control' placeholder='Amt'></td>";

            html+="<td><i style='color:red' class='fa fa-trash' onclick=removeFiled(this);></i></td>";

        html+="</tr>"


        $('#append_data').append(html);



        
        }

    $('#example4 tbody').on('click', 'tr', function () {
        var data=table.row(this).data();
        var id=data.id;
        window.location.href = '{{url("getPatientLabdetails")}}?id='+data.id;

    });


    $('#search').on('select2:select', function (e) {
        var data = e.params.data;
      
        add_duplicate(data.text,'TSTCDN','500');

        var sum_value=0;
        $('.totalamt').each(function(){
            sum_value += +$(this).val();
            $('#total_amount').val(sum_value);
            $('#totalbill_amount').val(sum_value);
        })



    });

    function removeFiled(thiss){
        $(thiss).parent().parent().remove();
    }
  
    

</script>
<script type="text/javascript">
  $('#search').select2({
    placeholder: 'Select Test',
  });
  
</script>
<style>
    .details .form-group{
        margin-bottom: unset!important;
    }
    input[type="checkbox"]:after{
        background: unset;
    }
    .select2-container .select2-selection--single{
        height: 39px;
        padding: 5px;

    }
     .table td {
        padding: 3px 9px;
    }
</style>


