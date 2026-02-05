<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }


    #nav-tabContent textarea.form-control {
    height: 90px !important;
}
    </style>

<div id="medical-history-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

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


                    <div class="text-center p-3 {{ is_null($medical_history) ? '' : 'medical-history-edit'}}">




                        <section id="tabs">
                            <div class="container-fluid">

                                <div class="row">
                                    <div class="col-md-12 ">
                                        <nav>
                                            <!-- <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Medical History</a>
{{--                                                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Diet Plan</a>--}}

                                            </div> -->
                                        </nav>
                                        <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                            <form action="#"  name="medical_form" id="medical_form" action="" method="POST">
                                            <input type ="hidden" class="form-control" id="history_id" value="{{ old('history_id',isset($medical_history) ? $medical_history->id : '') }}">
                                            <div class="row">

                                            <div class="col-xl-3 col-md-3 mb-2">
                                                        <div class="form-group">
                                                            <label class="text-label">Chief Complaints</label>
                                                            <textarea class="form-control disabled" id="chief_complaints" name="chief_complaints" rows="4">{{ old('chief_complaints',isset($medical_history) ? $medical_history->chief_complaints : '') }}</textarea>


                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-xl-3 col-md-3 mb-2">
                                                        <div class="form-group">
                                                            <label class="text-label">History of present Illness</label>
                                                            <textarea class="form-control" id="present_illness" name="present_illness" rows="4">{{ old('present_illness',isset($medical_history) ? $medical_history->present_illness : '') }}</textarea>


                                                        </div>
                                                    </div> -->
                                                    <div class="col-xl-3 col-md-3 mb-2">
                                                        <div class="form-group">
                                                            <label class="text-label">Past History</label>
                                                            <textarea class="form-control" id="past_illness" name="past_illness" rows="4">{{ old('past_illness',isset($medical_history) ? $medical_history->past_illness : '') }}</textarea>


                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-3 mb-2">
                                                        <div class="form-group">
                                                            <label class="text-label">Present Medication</label>
                                                            <textarea class="form-control" id="present_medication" name="present_medication" rows="4">{{ old('present_medication',isset($medical_history) ? $medical_history->present_medication : '') }}</textarea>


                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-3 mb-2">
                                                        <div class="form-group">
                                                            <label class="text-label">Past Medication</label>
                                                            <textarea class="form-control" id="past_medication" name="past_medication" rows="4">{{ old('past_medication',isset($medical_history) ? $medical_history->past_medication : '') }}</textarea>


                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-3 mb-2">
                                                        <div class="form-group">
                                                            <label class="text-label">Family History</label>
                                                            <textarea class="form-control" id="family_history" name="family_history" rows="4">{{ old('family_history',isset($medical_history) ? $medical_history->family_history : '') }}</textarea>


                                                        </div>
                                                    </div>
                                                    </div>
                                                    <h5> System Evaluation</h5>
                                                        <br>
                                                    <div class="row">

                                                    <div class="col-xl-3 col-md-3 mb-2">
                                                        <div class="form-group">
                                                            <label class="text-label">Head/Ears/Nose/Throat/Neck</label>
                                                            <textarea class="form-control" id="head_ears_nose" name="head_ears_nose" rows="3">{{ old('head_ears_nose',isset($medical_history) ? $medical_history->head_ears_nose : '') }}</textarea>


                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-3 mb-2">
                                                        <div class="form-group">
                                                            <label class="text-label">Respiratory System</label>
                                                            <textarea class="form-control" id="respiratory_system" name="respiratory_system" rows="3">{{ old('respiratory_system',isset($medical_history) ? $medical_history->respiratory_system : '') }}</textarea>


                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-3 mb-2">
                                                        <div class="form-group">
                                                            <label class="text-label">Cardiovascular System</label>
                                                            <textarea class="form-control" id="cardiovascular_system" name="cardiovascular_system" rows="3">{{ old('cardiovascular_system',isset($medical_history) ? $medical_history->cardiovascular_system : '') }}</textarea>


                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-3 mb-2">
                                                        <div class="form-group">
                                                            <label class="text-label">Gastrointestinal System incl. mouth</label>
                                                            <textarea class="form-control" id="gastrointestinal" name="gastrointestinal" rows="3">{{ old('gastrointestinal',isset($medical_history) ? $medical_history->gastrointestinal : '') }}</textarea>


                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-3 mb-2">
                                                        <div class="form-group">
                                                            <label class="text-label">Musculoskeletal System</label>
                                                            <textarea class="form-control" id="musculoskeletal_system" name="musculoskeletal_system" rows="3">{{ old('musculoskeletal_system',isset($medical_history) ? $medical_history->musculoskeletal_system : '') }}</textarea>


                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-3 mb-2">
                                                        <div class="form-group">
                                                            <label class="text-label">Central and Peripheral Nervous System</label>
                                                            <textarea class="form-control" id="central_nervous_system" name="central_nervous_system" rows="3">{{ old('central_nervous_system',isset($medical_history) ? $medical_history->central_nervous_system : '') }}</textarea>


                                                        </div>
                                                    </div>

                                                    <div class="col-xl-3 col-md-3 mb-2">
                                                        <div class="form-group">
                                                            <label class="text-label">General appearance</label>
                                                            <textarea class="form-control" id="general_apperance" name="general_apperance" rows="3">{{ old('general_apperance',isset($medical_history) ? $medical_history->general_apperance : '') }}</textarea>


                                                        </div>
                                                    </div>

                                                    <div class="col-xl-3 col-md-3 mb-2">
                                                        <div class="form-group">
                                                            <label class="text-label">Thyroid gland</label>
                                                            <textarea class="form-control" id="thyroid_gland" name="thyroid_gland" rows="3">{{ old('thyroid_gland',isset($medical_history) ? $medical_history->thyroid_gland : '') }}</textarea>


                                                        </div>
                                                    </div>
                                                    <div class="col-xl-3 col-md-3 mb-2">
                                                        <div class="form-group">
                                                            <label class="text-label">Lymph node Palpation</label>
                                                            <textarea class="form-control" id="node_palpation" name="node_palpation" rows="3">{{ old('node_palpation',isset($medical_history) ? $medical_history->node_palpation : '') }}</textarea>


                                                        </div>
                                                    </div>
                                                </div>
                                                @if(! is_null($medical_history))
                                                <div class="row">
{{--                                                    <p>{{ isset($medical_history)? $medical_history->name : ''}} updated the medical history at {{ isset($medical_history)? $medical_history_date : '' }}</p>--}}
                                                    <p>{{ isset($medical_history)? $medical_history->name . ' updated the medical history at ' .
                                                        date('d-m-Y h:i:sa', strtotime($medical_history->created_at)) : ''}} </p>
                                                </div>
                                                @endif

                                                <div class="row" id="crud">
                                                    <div class="col-xl-12 col-md-12 mb-2">
                                                        <div class="d-flex flex-wrap float-right h-100">
                                                            @if(is_null($medical_history))
                                                                <button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(1,1)" >Submit</button>
                                                            @else
                                                                <button type="button" class="btn btn-sm btn-primary my-2 pull-right"  id="edit-medical-history" onclick="editMedicalHistory()" >Edit</button>
                                                                &nbsp;<button type="button" class="btn btn-sm btn-primary my-2 pull-right" id="update-medical-history"  onclick="submit_form(1,2)" style="display: none" >Update</button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
{{--                                                <div id="crud">--}}
{{--                                                            @if(is_null($medical_history))--}}
{{--                                                                <button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(1,1)" >Submit</button>--}}
{{--                                                            @else--}}
{{--                                                            <button type="button" class="btn btn-sm btn-primary my-2 pull-right" id="update-medical-history"  onclick="submit_form(1,2)" style="display: none" >Update</button>--}}
{{--                                                            &nbsp;<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  id="edit-medical-history" onclick="editMedicalHistory()" >Edit</button>--}}
{{--                                                            @endif--}}
{{--                                                        </div>--}}
                                                        </form>




                                            </div>
{{--                                            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">--}}
{{--                                            <form action="#"  name="diet_form" id="diet_form" action="" method="POST">--}}
{{--                                            <input type ="hidden" class="form-control" id="diet_id" value="@if($diet_history)--}}
{{--                                                                              {{  $diet_history[0]['patient_id']}}  @endif">--}}
{{--                                                    <table class="table ">--}}
{{--                                                        <thead>--}}
{{--                                                            <tr>--}}
{{--                                                                <th>Question</td>--}}
{{--                                                                <th width="20%">Answer</th>--}}
{{--                                                                <th width="30%">Notes</th>--}}
{{--                                                            </tr>--}}
{{--                                                        </thead>--}}
{{--                                                        <tbody>--}}
{{--                                                  --}}


{{--                                                        @foreach($question_data as $key => $questions)--}}
{{--                                                      --}}
{{--                                                            <tr>--}}
{{--                                                                <td>{{$questions->question}}</td>--}}
{{--                                                                --}}
{{--                                                                <td>--}}
{{--                                                                @foreach($questions->sub_question as $label)--}}
{{--                                                                        <input type="radio" name="dietplan_answer_{{$questions->id}}" value="{{$label->id}}"--}}
{{--                                                                        @if($diet_history)--}}
{{--                                                                        @for($i=0;$i<count($diet_history);$i++)--}}
{{--                                                                                {{($diet_history[$i]['dietplan_answer']==$label->id)? "checked" : "" }}--}}
{{--                                                                            @endfor --}}
{{--                                                                       --}}
{{--                                                                           @endif--}}
{{--                                                                         >{{$label->label}} &nbsp;&nbsp;--}}
{{--                                                                       --}}

{{--                                                                @endforeach--}}
{{--                                                                </td>--}}
{{--                                                              --}}
{{--                                                                <td><input type="text" class="form-control" name="notes_{{$questions->id}}" id="notes" --}}
{{--                                                                value="@if($diet_history)--}}
{{--                                                                            @for($i=0;$i<count($diet_history);$i++)--}}
{{--                                                                            @if($key==$i)--}}
{{--                                                                          {{$diet_history[$i]['notes']}}--}}
{{--                                                                          @endif--}}
{{--                                                                            @endfor --}}
{{--                                                                        @endif"--}}
{{--                                                               >--}}
{{--                                                            </td>--}}
{{--                                                                </textarea>--}}
{{--                                                            </td>--}}
{{--                                                            </tr>--}}
{{--                                                           @endforeach  --}}

{{--                                                        </tbody>--}}
{{--                                                    </table>--}}
{{--                                                    <div id="crud_diet">--}}
{{--                                                --}}
{{--                                                                <button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(2,1)" >Save</button>--}}
{{--                                                          --}}
{{--                                                               --}}
{{--                                                          --}}
{{--                                                            </div>--}}
{{--                                                            </form>--}}
{{--                                            </div>--}}

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
<script>
      function submit_form(page,crude)
    {
        var url="";
       var history_id=$('#history_id').val();
       // var diet_id=$('#diet_id').val();
        if(page==1)
        {
            url='{{route('saveMedicalHistory')}}';
            var form = $('#medical_form')[0];
        }
        {{--if(page==2)--}}
        {{--{--}}
        {{--    url='{{route('savePatientDietPlan')}}';--}}
        {{--    var form = $('#diet_form')[0];--}}
        {{--}--}}


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
                              if(history_id){
                                crude_btn_manage(2,page);
                              }
                              // else{
                              //   crude_btn_manage(1,page);
                              // }

                              $("#medical-history-modal").modal("hide");

                                location.reload();
                            }
                            else if(result.status==2){
                                sweetAlert("Oops...",result.message, "error");
                            }
                            else{
                                sweetAlert("Oops...",result.message, "error");
                            }
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
        if(type==1) $('#crud').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\''+page+'\',\''+type+'\')" >Save</button>');
	   else if(type==2)  $('#crud').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\''+page+'\',\''+type+'\')" >Update</button>');
    }


}

      function editMedicalHistory(){
          $(".medical-history-edit :input").attr("readonly", false);
          $("#update-medical-history").css("display", "block");
      }
</script>
<style>
    .content-body .container{
        margin-top:0px!important;
    }
    .table  tbody td {
        padding:2px 9px;
    }
    .table  tr td{
        text-align:left;

    }
    .table tbody tr td{

        font-size: 13px;
    }

    .form-control {
        height: 30px;
    }
    .btnexpand{
        width: 100%;
        padding: 0.2rem 1.5rem!important;
        border-radius: 5px;
    }
</style>

