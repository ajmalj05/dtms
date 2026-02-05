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

<div id="pep-modal" class="modal fade" role="dialog">

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

                    <form action="#"  name="pep_form" id="pep_form" action="" method="POST">
                        @csrf
                        <input type ="hidden" class="form-control" id="pep_id" value="@if($pep_history)
                        {{  $pep_history[0]['patient_id']}}  @endif">
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
                            @foreach($pep_questions_data as $key => $questions)
                                <?php $i++; ?>
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$questions->question}}</td>

                                    <td>
                                        @foreach($questions->sub_question as $label)
                                            <input type="checkbox" class="radio" name="pep_answer_{{$questions->id}}" value="{{$label->id}}"
{{--                                            @if($pep_history)--}}
{{--                                                @for($i=0;$i<count($pep_history);$i++)--}}
{{--                                                    {{($pep_history[$i]['answer']==$label->id)? "checked" : "" }}--}}
{{--                                                    @endfor--}}
{{--                                                @endif--}}
                                            > {{$label->label}} &nbsp;&nbsp;


                                        @endforeach
                                    </td>

                                    <td><input type="text" class="form-control" name="notes_{{$questions->id}}" id="notes"
{{--                                               value="@if($pep_history)--}}
{{--                                               @for($i=0;$i<count($pep_history);$i++)--}}
{{--                                               @if($key==$i)--}}
{{--                                               {{isset($pep_history[$i]['notes'])?$pep_history[$i]['notes']:''}}--}}
{{--                                               @endif--}}
{{--                                               @endfor--}}
{{--                                               @endif"--}}
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
                <div id="crud_pep">
                    <button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_pep()">Save</button>
                </div>
            </div>


        </div>
    </div>
    {{-- modal lg --}}

</div>
<script>
    function submit_pep()
    {
        var url="";

        var pep_id=$('#pep_id').val();

        url='{{route('savePep')}}';
        var form = $('#pep_form')[0];
        var formData = new FormData(form);

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
                    document.getElementById("pep_form").reset();
                    $("#pep-modal").modal("hide");
                }
                else if(result.status==2){
                    sweetAlert("Oops...",result.message, "error");
                    document.getElementById("pep_form").reset();
                    $("#pep-modal").modal("hide");
                }
                else{
                    sweetAlert("Oops...",result.message, "error");
                    document.getElementById("pep_form").reset();
                    $("#pep-modal").modal("hide");
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
    .medication_add{
        max-height: 60vh!important;
        overflow: auto;
    }
</style>



<script>
    $(document).ready(function(){
        table = $('#example5').DataTable();
    });

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
