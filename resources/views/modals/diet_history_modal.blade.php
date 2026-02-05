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

<div id="diet-history-modal" class="modal fade" role="dialog">

    <div class="modal-dialog modal-lg" >

        <div class="modal-content" style=" height: 80vh;overflow-y: auto;">


            <div class="modal-header">
                <div class="card-title">
                    <p>{{$title}}</p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
                </div>
            </div>


            <div class="modal-body" >
                <div class="main-content" id="htmlContent">

                    <form action="#"  name="diet_form" id="diet_form" action="" method="POST">
                        @csrf
                        <input type ="hidden" class="form-control" id="diet_id" value="@if($diet_history)
                                                          {{  $diet_history[0]['patient_id']}}  @endif">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Question</td>
                                            <th width="50%">Answer</th>
                                            <th width="20%">Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                    <?php $i=0; ?>
                                    @foreach($question_data as $key => $questions)
                                    <?php $i++; ?>
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{$questions->question}}</td>

                                            <td>
                                            @foreach($questions->sub_question as $label)
                                                    <input type="checkbox" class="radio" name="dietplan_answer_{{$questions->id}}" value="{{$label->id}}"
{{--                                                    @if($diet_history)--}}
{{--                                                    @for($i=0;$i<count($diet_history);$i++)--}}
{{--                                                            {{($diet_history[$i]['dietplan_answer']==$label->id)? "checked" : "" }}--}}
{{--                                                        @endfor--}}

{{--                                                       @endif--}}
                                                     > {{$label->label}} &nbsp;&nbsp;


                                            @endforeach
                                            </td>

                                            <td><input type="text" class="form-control" name="notes_{{$questions->id}}" id="notes"
{{--                                            value="@if($diet_history)--}}
{{--                                                        @for($i=0;$i<count($diet_history);$i++)--}}
{{--                                                        @if($key==$i)--}}
{{--                                                      {{$diet_history[$i]['notes']}}--}}
{{--                                                      @endif--}}
{{--                                                        @endfor--}}
{{--                                                    @endif"--}}
                                           >
                                        </td>
                                            </textarea>
                                        </td>
                                        </tr>
                                       @endforeach

                                    </tbody>
                                </table>

                                        </form>
                </div>
            </div>

            <div class="modal-footer--sticky">
                <div id="crud_diet">
                     <button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form_diet(1,1)" >Save</button>
                    </div>
            </div>


        </div>
    </div>
 {{-- modal lg --}}

</div>
<script>
      function submit_form_diet(page,crude)
    {
        var url="";
       var diet_id=$('#diet_id').val();
        if(page==1)
        {
            url='{{route('savePatientDietPlan')}}';
            var form = $('#diet_form')[0];
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
                                crude_btn_manage(1,page)
                                document.getElementById("diet_form").reset();
                                $('#diet-history-modal').modal('toggle');

                            }
                            else if(result.status==2){
                                sweetAlert("Oops...",result.message, "error");
                                document.getElementById("diet_form").reset();
                                $('#diet-history-modal').modal('toggle');

                            }
                            else{
                                sweetAlert("Oops...",result.message, "error");
                                document.getElementById("diet_form").reset();
                                $('#diet-history-modal').modal('toggle');

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
    if(type==1) $('#crud_diet').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form_diet(\''+page+'\',\''+type+'\')" >Save</button>');
        // else if(type==2)
        //     $('#crud_diet').html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form_diet(\''+page+'\',\''+type+'\')" >Update</button>');
    }


}

      $("input:checkbox").on('click', function() {
          var $box = $(this);
          if ($box.is(":checked")) {
              var group = "input:checkbox[name='" + $box.attr("name") + "']";
              $(group).prop("checked", false);
              $box.prop("checked", true);
          } else {
              $box.prop("checked", false);
          }
      });
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

