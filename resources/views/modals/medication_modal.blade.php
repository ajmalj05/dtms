<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }
</style>
<div id="medication-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        
            <div class="modal-content">
                <div class="modal-header">
                    <div class="card-title">
                        <p>{{$title}}</p>
                       
                    </div>
                </div>
                <div class="modal-body">
                <div class="main-content" id="htmlContent">

<div class="page-content">
    <div class="container-fluid">
  
        <!-- start page title -->
        <!-- <div class="page-title-box">
            <div class="row align-items-center">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <h4>Payment Gateway</h4>
                    <input type="hidden" id="path" value="{{ url('/') }}"> 
                </div> 
            </div>
        </div> -->
        <!-- end page title -->
       
                <div class="card card-sm">
                    <div class="text-center p-3 ">

                       


                        <section id="tabs">
                            <div class="container-fluid">
                               
                                <div class="row">
                                    <div class="col-md-12 ">
                                       
                                       
                                            
                                           
                                            <div class="row">
                                             

                                            <div class="col-xl-9 col-lg-12 col-sm-12">
                    <div class="card card-sm">
                        <div class="card-header">
                            <h4 class="card-title">Medications</h4>
                            <div >
                              
                            
                            </div>
                        </div>
                        <div class="card-body medication_add">
                            
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                
                                    <select id="search_drug" >
                                        <option>Select Drug</option>
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
                            <div class="form-row"></div>

                            <table class="table tablstriped">
                                <tbody id="append_data">
                                </tbody>
                            </table>
                            <input type="submit" class="btn btn-success" value="Save">



                        
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-lg-12 col-sm-12">
                    <div class="card card-sm">
                        <div class="card-header">
                            <h4 class="card-title">Medications Listing</h4>
                        </div>
                        <div class="card-body">
                            <table id="example7" class="display">
                                <thead>
                                    <tr>
                                        <th>Drug Name</th>
                                        <th>Route</th>
                                        <th>Duration</th>
                                        <th>Qty</th>
                                        <th>Instructions</th>

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
                        </section>
                        <!-- Tabs content -->
                    </div>
                   
                                        </div>
                                   
                               
                    <!-- End Page-content -->
            </div>
                </div>
            </div>
      
    </div>
</div>

</div>
</div>
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
    #example5 td {

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



<script>
    $(document).ready(function(){
        table = $('#example7').DataTable();
    });
 
    
    $('#search_drug').on('select2:select', function (e) {
        var data = e.params.data;
      
        add_duplicate(data.text,'TSTCDN','500');

    });
    function add_duplicate(name,code,amt){
        var html="";
        html+="<tr>";
          
            html+=" <td><input type='text' class='form-control' id='patient_name' name='patient_name' placeholder='Drug Name' value="+name+" readonly></td>";
            html+="<td><input type='text' name='last_name' id='last_name' value='' class='form-control' placeholder='Route' ></td>";
            html+="<td><input type='text' name='last_name' id='last_name' value='' class='form-control' placeholder='Duration' ></td>";
          
         
            html+="<td><input type='text' name='last_name' id='last_name' value='' class='form-control' placeholder='Quantity' ></td>";
            html+="<td><input type='text' name='last_name' id='last_name' value='' class='form-control' placeholder='Notes' ></td>";
          

           
            html+="<td><i style='color:red' class='fa fa-trash' onclick=removeFiled(this);></i></td>";

        html+="</tr>"


        $('#append_data').append(html);



        
    }
    function removeFiled(thiss){
        $(thiss).parent().parent().remove();
    }
</script>
  