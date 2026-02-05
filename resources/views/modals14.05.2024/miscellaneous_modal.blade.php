<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }

    .fieldgroup{
   float: left;
   width: auto;
   margin-left: 1em;
}

    .scroll-miscellaneous{
        display: block;
        overflow: auto;
        height: 400px;
        width: 100%;
    }
</style>
<div id="miscellaneous-modal" class="modal fade" role="dialog">
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



                <div class="">
                    <div class="text-center p-3 scroll-miscellaneous">




                        <section id="tabs">
                            <div class="container-fluid">

                                <div class="row">
                                    <div class="col-md-12 ">




                                            <div class="row">



                                                <div class="col-xl-12 col-lg-12 col-sm-12">
                                                    <form name="miscellaneousFrm" id="miscellaneousFrm" action="#" >
                                                        <div class="">

                                                            <div class="">
                                                                <div id="miscellaneous_qs_list">
                                                                </div>
                                                                <div id="loadingdata_misc">
                                                                    <h4>Loading...</h4>
                                                                </div>
                                                                <div id="hereditary_incidence" class="d-none">
                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-4">
                                                                            <label for="feFirstName">Hereditary Incidence</label>
                                                                        </div>
                                                                        <div class="form-group col-md-12 ">
                                                                            <table class='table heriditarayTable'>
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Relationship</th>
                                                                                        <th>Age</th>
                                                                                        <th>If expired, Age at death</th>
                                                                                        <th>Diabetes</th>
                                                                                        <th>CAD</th>
                                                                                        <th>CKD</th>
                                                                                        <th>CVD</th>
                                                                                        <th>Amputation</th>
                                                                                        <th>Cancer</th>
                                                                                        <th>Thyroid Disorders</th>
                                                                                        <th>HTN</th>
                                                                                        <th>Dyslipidemia</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>

                                                                                    @for ($i=1; $i<=4; $i++)

                                                                                        <tr>
                                                                                            <td><input type="text" name="relation[]" class="form-control" value="@if($i==1)  FATHER @elseif ($i==2) MOTHER @elseif ($i==3) SIBLING @elseif ($i==4) CHILDREN @endif"></td>
                                                                                            <td width="8%"><input type="text" name="age[]" class="form-control"></td>
                                                                                            <td><input type="text" name="death_age[]" class="form-control"></td>
                                                                                            <td><input type="text" name="diabetes[]" class="form-control"></td>
                                                                                            <td width="8%"><input type="text" name="cad[]" class="form-control"></td>
                                                                                            <td width="8%"><input type="text" name="ckd[]" class="form-control"></td>
                                                                                            <td width="8%"><input type="text" name="cvd[]" class="form-control"></td>
                                                                                            <td><input type="text" name="amputation[]" class="form-control"></td>
                                                                                            <td><input type="text" name="cancer[]" class="form-control"></td>
                                                                                            <td><input type="text" name="thyroid[]" class="form-control"></td>
                                                                                            <td width="8%"><input type="text" name="htn[]" class="form-control"></td>
                                                                                            <td><input type="text" name="dyslipidemia[]" class="form-control"></td>
                                                                                            @if($i==4)
                                                                                            <td><i class="fa fa-plus" onclick="duplicateHeriditary(this);"></i></td>
                                                                                            @endif
                                                                                        </tr>


                                                                                    @endfor


                                                                                </tbody>

                                                                            </table>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-4">
                                                                            <label for="feFirstName">BMI Calculator</label>
                                                                        </div>
                                                                        <div class="form-group col-md-8 ">
                                                                            <div class="row">
                                                                                <div class="col-md-4">
                                                                                    <input type="text" name="bmi_height" id="bmi_height"  onChange="CalculateBMI();" class="form-control" placeholder="HEIGHT in cm">
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <input type="text" name="bmi_weight"  id="bmi_weight" onChange="CalculateBMI();" class="form-control" placeholder="WEIGHT in Kg">
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    <input type="text" name="bmi" id="BMI" readonly class="form-control" placeholder="BMI">
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                        <div class="form-row">
                                                                            <div class="form-group col-md-4">
                                                                                <label for="feFirstName">@GFR Calculator</label>
                                                                            </div>
                                                                            <div class="form-group col-md-8 ">
                                                                                <div class="row">
                                                                                    <div class="col-md-3">
                                                                                        <select id="" name="" class="form-control">
                                                                                            <option  value="" selected>White or other</option>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <input type="text"  id="age_calculate" readonly name="" placeholder="{{ \Carbon\Carbon::parse($patient_data->dob)->diff(\Carbon\Carbon::now())->format('%y') }} yrs" class="form-control" value="{{ \Carbon\Carbon::parse($patient_data->dob)->diff(\Carbon\Carbon::now())->format('%y') }}">
                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <select id="gender_id" name="" class="form-control" readonly>
                                                                                            <option value=''>Gender</option>
                                                                                            <option  value="m" <?php if(str_contains($patient_data->gender, 'm') ) echo "selected" ?>>Male</option>
                                                                                            <option value='f' <?php if( str_contains($patient_data->gender, 'f') ) echo "selected" ?>>Female</option>
                                                                                            <option value='o' <?php if( str_contains($patient_data->gender, 'o') ) echo "selected" ?>>Others</option>
                                                                                        </select>

                                                                                    </div>
                                                                                    <div class="col-md-2">
                                                                                        <input type="text"  name="scr" class="form-control" placeholder="SCR" onchange="calculategfr()" id="scr" data-toggle="tooltip" data-placement="left" title="Press Enter to calculate GFR">
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <input type="text" name="gfr" readonly  id="GFR" class="form-control" placeholder="GFR">
                                                                                    </div>

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="col-xl-3 col-md-6 mb-2">
                                                            <div class="d-flex flex-wrap align-content-center h-100">
                                                                <button type="button" class="btn btn-sm btn-primary mt-1" onclick="saveMiscellaneous(1)">Save</button>
                                                            </div>
                                                        </div>
                                                    </form>
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
   function getMiscellaneousQuestions(){
    $('#miscellaneous_qs_list').empty();
    $.ajax({
            url: "<?php echo url('/') ?>/getMiscellaneousQuestions",
            method: 'POST',
            data: { },
            success: function(result) {
                var jsondata = $.parseJSON(result);

                // const questionarray = unique(jsondata.question);
                // const questionIdarray=  unique(jsondata.questionid);

                const questionarray = jsondata.question;
                const questionIdarray=  jsondata.questionid;

                const option_list=   jsondata.option_list;

                // var html="<table class='table_qs'>";
                    var i=1;
                    $dataAppend = '';
                $.each(questionarray, function (key,val) {

                    $questionId=questionIdarray[key];
                    $inputtype = jsondata.type[key];
                    $answerss =jsondata.answers[key];

                    $dataAppend += "<div class='form-row'><div class='form-group col-md-4'><label for='feFirstName'>"+ i +")&nbsp;" + val +"</label></div>";


                    if (option_list[key]) {

						$Listoption = option_list[key];

						$dataAppend += "<div class='form-group col-md-8 '>"+$Listoption+"</div></div>";
					} else {
						if ($inputtype == 'textarea') {
							$dataAppend += "<div class='form-group col-md-8 '><textarea class='form-control' rows='2'name='"+$questionId+"' id='"+$questionId+"'>"+$answerss+"</textarea></div></div>";
						} else {
                           // console.log($inputtype)
							$dataAppend += "	<div class='form-group col-md-8'><input type='"+$inputtype+"' class='form-control' name='"+$questionId+"' id='"+$questionId+"' value='"+$answerss+"'></div></div>";
						}
					}
					$dataAppend += "<div class='clearfix'></div>";

                    i++;


                });
                // html+= "</table>";

                $('#miscellaneous_qs_list').append($dataAppend);

                // console.log(jsondata);
            }
        });
   }

   function getHeriditarydetails(){


        $.ajax({
            url: "<?php echo url('/') ?>/getHeriditarydetails",
            method: 'POST',
            data: { },
            success: function(result) {
                var jsondata = $.parseJSON(result);
                // console.log(jsondata.length);
                if(jsondata.length !=0){
                    $('.heriditarayTable tbody').empty();
                    $.each(jsondata, function (key,val) {


                        var html=fillHeriditoryData(val);
                        // console.log(html);
                        $('.heriditarayTable tbody').append(html);


                    });
                }

                $('#hereditary_incidence').removeClass('d-none');

                $('#loadingdata_misc').addClass('d-none');


            }
        });



   }

   function fillHeriditoryData(data){
    var html="";
        html+="<tr>";
            html+="<td><input type='text' name='relation[]' class='form-control' value='"+ (data.relation || '') +"'></td>";

            html+="<td><input type='text' name='age[]' class='form-control' value='"+ (data.age || '' )+"'></td>";
            html+="<td><input type='text' name='death_age[]' class='form-control' value='"+ (data.expired_age || '' )+"'></td>";
            html+="<td><input type='text' name='diabetes[]' class='form-control' value='"+ (data.diabetes || '' )+"'></td>";
            html+="<td><input type='text' name='cad[]' class='form-control' value='"+ (data.cad || '' )+"'></td>";
            html+="<td><input type='text' name='ckd[]' class='form-control' value='"+ (data.ckd || '' )+"'></td>";
            html+="<td><input type='text' name='cvd[]' class='form-control' value='"+ (data.cvd || '' )+"'></td>";
            html+="<td><input type='text' name='amputation[]' class='form-control' value='"+ (data.amputation || '' )+"'></td>";
            html+="<td><input type='text' name='cancer[]' class='form-control' value='"+ (data.cancer || '' )+"'></td>";
            html+="<td><input type='text' name='thyroid[]' class='form-control' value='"+ (data.thyroid || '' )+"'></td>";
            html+="<td><input type='text' name='htn[]' class='form-control' value='"+ (data.htn || '' )+"'></td>";
            html+="<td><input type='text' name='dyslipidemia[]' class='form-control' value='"+ (data.dyslipidemia || '' )+"'></td>";
            html+="<td><i class='fa fa-plus' onclick='duplicateHeriditary(this);'></i></td>";
        html+="</tr>";
        return html;




   }

   function getgfrdetails(){
    $.ajax({
            url: "<?php echo url('/') ?>/getPatientGFRdetails",
            method: 'POST',
            data: { },
            success: function(result) {
                if(result){
                    var jsondata = $.parseJSON(result);
                    if(jsondata){
                        $('#bmi_height').val(jsondata.height);
                        $('#bmi_weight').val(jsondata.weight);
                        $('#BMI').val(jsondata.bmi);
                        $('#scr').val(jsondata.scr);
                        $('#GFR').val(jsondata.gfr);
                    }
                }



            }
        });
   }

   function unique(array){
    return array.filter(function(el, index, arr) {
        return index == arr.indexOf(el);
    });
    }


    function calculategfr(){
        $('#GFR').val('');
        $.ajax({
        url: "<?php echo url('/') ?>/calculategfr",
            method: 'POST',
            data: {scr:$('#scr').val(),age:$('#age_calculate').val(),gender:$('#gender_id').val() },
            success: function(result) {
                $('#GFR').val(result);
            }
        });
    }


    function saveMiscellaneous(crude){

        $("[id*='_error']").text('');
        var form = $('#miscellaneousFrm')[0];
        var formData = new FormData(form);
        formData.append('crude', crude);
        url='{{route('saveMiscellaneousData')}}';




        $(form).find(':radio:checked').each(function () {
        formData.append(this.name, $(this).val());
        });



        $(form).find(':checkbox:checked').each(function () {
        var fieldName =this.name;

        var string = $("input[name='" + fieldName + "']").map(function() {
            if($(this).prop('checked') == true){
            return this.value;
            }


            }).get().join(',');

        formData.append(this.name, string);
        });







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
                    document.getElementById("miscellaneousFrm").reset();
                    $("#miscellaneous-modal").modal("toggle");
                } else {
                    sweetAlert("Oops...",result.message, "error");
                    document.getElementById("miscellaneousFrm").reset();
                    $("#miscellaneous-modal").modal("toggle");
                }
            },
            error: function(result,jqXHR, textStatus, errorThrown){
                if( result.status === 422 ) {
                    result=result.responseJSON;
                    var error=result.errors;
                    $.each(error, function (key, val) {
                        console.log([key,val]);
                        let errorMsg = "This field is required";
                        $("#" + key + "_error").text(errorMsg);
                    });
                }

            }
        });
    }


    function duplicateHeriditary($thiss){
        var html='';


        html+="<tr>";
            html+="<td><input type='text' name='relation[]' class='form-control'></td>";

            html+="<td width='8%'><input type='text' name='age[]' class='form-control'></td>";
            html+="<td><input type='text' name='death_age[]' class='form-control'></td>";
            html+="<td><input type='text' name='diabetes[]' class='form-control'></td>";
            html+="<td width='8%'><input type='text' name='cad[]' class='form-control'></td>";
            html+="<td width='8%'><input type='text' name='ckd[]' class='form-control'></td>";
            html+="<td width='8%'><input type='text' name='cvd[]' class='form-control'></td>";
            html+="<td><input type='text' name='amputation[]' class='form-control'></td>";
            html+="<td><input type='text' name='cancer[]' class='form-control'></td>";
            html+="<td><input type='text' name='thyroid[]' class='form-control'></td>";
            html+="<td width='8%'><input type='text' name='htn[]' class='form-control'></td>";
            html+="<td><input type='text' name='dyslipidemia[]' class='form-control'></td>";
            html+="<td><i class='fa fa-plus' onclick='duplicateHeriditary(this);'></i></td>";
        html+="</tr>";


        $('.heriditarayTable tbody').append(html);
        $($thiss).remove();

    }

    function CalculateBMI(){

        var height=$('#bmi_height').val();
        var weight=$('#bmi_weight').val();
        $('#BMI').val('');

        if(height !="" &&  weight!=""){

            heightinMeter=height/100;

           var bmi= weight / (heightinMeter*heightinMeter);
           $('#BMI').val(bmi.toFixed(2));

        }
    }


</script>
