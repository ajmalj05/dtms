<style>
    #example4 tr:hover {
        background-color: #d7dae3!important;
    }
    #example4 tr:hover td {
        background-color: transparent!important; /* or #000 */
    }
    </style>
    <div id="addvisit_modal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                  {{$title}}
                </div>
    
           
            
                <form name="visitform" id="visitform" action="{{route('saveVisit')}}"method="post" >
                    @csrf
                      
                    <div class="col-xl-12 col-xxl-12 col-lg-12 col-sm-12">
                              
                                  
    
                        <section>
                            <div class="row">
                                             
                                <div class="col-xl-12 col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="text-label">Visit Type</label>
                                        <select id="a_specialist_id" name="a_specialist_id" class="form-control">
                                            <option  value="" selected>Choose...</option>
                                            {{LoadCombo("visit_type_master","id","visit_type_name",isset($patient_data)?$patient_data->visit_type_id:'','where display_status=1 AND is_deleted=0',"order by id desc");}}
                                        </select>
                                                    
                
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="text-label">Date<span class="required">*</span></label>
                                            <input type="text" class="form-control" name="visit_date" id="visit_date"  value="{{ date('d-m-Y')}}"  >
                                        <small id="visit_date_error" class="form-text text-muted error"></small>
    
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-6 mb-2">
                                    <div class="form-group">
                                        <label class="text-label">Remarks </label>
                                        <input type="text" name="name" id="s_patient_name" class="form-control"  placeholder="" >
                                        <small id="name_error" class="form-text text-muted error"></small>
                                    </div>
                                </div>  
                                <div class="col-xl-3 col-md-3 mb-2">
                                    <div class="form-group">
                                    
                                      <button class="btn btn-primary search-btn" type="submit" onclick="submitVisitForms()">Save</button>
                                    </div>
                                </div>
                                          
                                             
                                <input type="hidden" name="pid" value="{{$patient_data->id}}">
                                             
                                              
                                              
                                             
                                              
                                           
                            </div>
                                          
                        </section>
                    </div>
                     
                      
                </form>
              
            </div>
         </div>
    </div>
    <style>
     #addvisit_modal .modal-lg {
        max-width: 40% !important;
    }
    .search-btn{
      padding:0.375rem 0.75rem!important;
    }
    </style>
    <script>
     
     function submitVisitForms(){
       
        document.getElementById("visitform").submit();
    }
    
     
    </script>