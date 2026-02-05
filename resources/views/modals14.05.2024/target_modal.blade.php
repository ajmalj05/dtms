<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }
</style>
<div id="target-modal" class="modal fade " role="dialog">
    <div class="modal-dialog ">
        <form name="target-form" id="target-form" action="#" >
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
                    <div class="container-fluid pt-2">

                            <div class="row">
                                <div class="col-xl-12 col-xxl-12 col-lg-12 col-sm-12">
                                    <div class="">

                                        <div class="">
                                            <section>
                                                <div class="row">
                                                    <div class="col-xl-12 col-md-12 mb-6">
                                                        <div class="form-group ">
                                                                <label class="text-label">Select Test</label>
                                                                <select class="search-test-name-ajax search_test_name" id="search_test_name" name="search_test_name" >
                                                                </select>
                                                        </div>
                                                    </div>
{{--                                                    <div class="col-xl-12 col-md-12 mb-6">--}}
{{--                                                        <div class="form-group ">--}}
{{--                                                            <label class="text-label">Test Name</label>--}}
                                                            <input type="hidden" class="form-control" id="test_name_selected" name="test_name_selected" value="" readonly>

{{--                                                        </div>--}}
{{--                                                    </div>--}}
                                                    <div class="col-xl-12 col-md-12 mb-6">
                                                        <div class="form-group ">
                                                                <label class="text-label">Target</label>
                                                            <input type="text" class="form-control" id="target_value" name="target_value" >

                                                        </div>
                                                    </div>
                                                    <div class="col-xl-12 col-md-12 mb-6">
                                                        <div class="form-group ">
                                                                <label class="text-label">Present</label>
                                                            <input type="text" class="form-control" id="present_value" name="present_value" >

                                                        </div>
                                                    </div>
                                                    </div>
                                                    <div class="row">
                                                    <div class="col-xl-3 col-md-6 mb-2">
                                                        <div class="d-flex flex-wrap align-content-center h-100">
                                                            <button type="button" class="btn btn-sm btn-primary mt-1" onclick="saveTargetData(1)">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        </section>
                                    </div>
                                </div>
                            </div>

                    </div>
                </div>
            </div>
    </div>
    </form>
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

<script>

    {{--$(document).ready(function(){--}}
    {{--    $(document).ajaxSend(function(){--}}
    {{--        $('#loader').hide();--}}
    {{--    });--}}

    {{--    $(".search-test-name-ajax").select2({--}}
    {{--        placeholder: "Search Test",--}}
    {{--        ajax: {--}}
    {{--            url: "{{ route('search-test-names') }}",--}}
    {{--            type: "post",--}}
    {{--            dataType: 'json',--}}
    {{--            data: function (params) {--}}
    {{--                return {--}}
    {{--                    searchTerm: params.term, // search term--}}
    {{--                };--}}
    {{--            },--}}
    {{--            results: function (response) {--}}
    {{--                console.log(response);--}}
    {{--                return {--}}
    {{--                    results: response--}}
    {{--                };--}}
    {{--            },--}}
    {{--            cache: true--}}
    {{--        },--}}
    {{--        templateSelection: function (repo) {--}}
    {{--            // $('#test_name_selected').val(repo.text);--}}

    {{--            return repo.full_name || repo.text;--}}
    {{--        }--}}
    {{--    });--}}


    {{--});--}}


    $(document).ready(function(){
        $(document).ajaxSend(function(){
            $('#loader').hide();
        });

        $(".search-test-name-ajax").select2({
            placeholder: "Search Test",
            ajax: {
                url: "{{ route('search-test-names') }}",
                type: "post",
                dataType: 'json',
                data: function (params) {
                    return {
                        searchTerm: params.term, // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });


    });

    function saveTargetData(crude=1)
    {
        $("[id*='_error']").text('');
        var form = $('#target-form')[0];
        var formData = new FormData(form);
        formData.append('crude', crude);
        url='{{route('save-target-data')}}';

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            processData: false,
            contentType: false,
            success: function(result){
                if (result.status == 1) {
                    // console.log()
                    swal("Done", result.message, "success");
                    var form = $('#target-form')[0];
                    document.getElementById("target-form").reset();
                    // update target
                    var html='';
                    if (result.patient_test_targets.length>0){
                        $.each(result.patient_test_targets, function(index, element) {
                            html+="<tr>";
                            html+="<td>"+element.TestName+"</td>";
                            html+="<td><input type='text' class='form-control custom-box patient_target_"+element.test_master_id +"' name='patient_target_"+element.test_master_id+"' readonly value='"+element.target_value+"'></td>";
                            html+="<td><input type='text' class='form-control custom-box patient_present_"+element.test_master_id +"' name='patient_present_"+element.test_master_id+"'  value='"+element.present_value+"'></td>";
                            html+="</tr>";
                        });
                    }
                    // Target Details
                    if (result.patient_target_details) {
                        html += "<tr>";
                        html += "<td>Weight</td>";
                        html += "<td><input type='text' class='form-control custom-box' name='weight_target'  value='"+result.patient_target_details.weight_target+"'></td>";
                        html += "<td><input type='text' class='form-control custom-box ' name='weight_present'  value='"+result.patient_target_details.weight_present+"'></td>";
                        html += "</tr>";
                        html += "<tr>";
                        html += "<td>Action Plan</td>";
                        html += "<td colspan='2'><textarea cols='2' rows='2' name='action_plan' id='action_plan' class='form-control custom-box' style='height: 78px;'>"+result.patient_target_details.action_plan+"</textarea></td>";
                        html += "</tr>"
                        //html += "<td>Fibro scan value</td>";
                        //html += "<td colspan='2'><textarea cols='2' rows='2' name='fibro_scan' id='fibro_scan' class='form-control custom-box' style='height: 78px;'>"+result.patient_target_details.fibro_scan+"</textarea></td>";
                        html += "</tr>";;

                    }
                    $('.target_data').empty().append(html);

                    $('#target-modal').modal('toggle');
                }
                else if (result.status == 2) {
                    swal("Done", result.message, "success");
                    // location.reload();
                    document.getElementById("target-form").reset();
                    $('#target-modal').modal('toggle');

                }
                else {
                    sweetAlert("Oops...", result.message, "error");

                }


            },
            error: function(result,jqXHR, textStatus, errorThrown){
                if( result.status === 422 ) {
                    result=result.responseJSON;
                    var error=result.errors;
                    $.each(error, function (key, val) {
                        $("#" + key + "_error").text(val[0]);
                    });
                }

            }
        });
    }
</script>


