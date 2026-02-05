<style>
    table.dataTable
    {
        width: 100% !important;
    }
</style>
<div class="content-body">
  <div class="container-fluid pt-2">
    <div class="row">
      <div class="col-md-12">


          <div class="custom-tab-1">
            <ul class="nav nav-tabs">

              <li class="nav-item"><a href="#subgroups" data-toggle="tab" class="nav-link active show">Test Procedure</a>  </li>

            </ul>
            <div class="tab-content pt-3">

              <div id="subgroups" class="tab-pane fade active show">
                <div class="row">
                  <div class="col-xl-6">
                    <div class="card">
                      <div class="card-body">

                        <div class=" mb-5">
                            <form action="#"  name="frm1" id="frm1" action="" method="POST">
                                <input type="hidden" name="hid_edu_id" id="hid_edu_id">

                                <div class="row">

                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label class="text-label">Select  Groups <span class="required">*</span></label>

                                        <select class="form-control" name="test_group" id="test_group" onchange="loadTest(this.value)">
                                            <option value="">Select</option>
                                            {{LoadCombo("test_group_master","id","groupname",'','where display_status=1 AND is_deleted=0',"order by id desc");}}

                                        </select>
                                          <small id="test_group_error" class="form-text text-muted error"></small>

                                    </div>
                                  </div>

                                  <div class="col-md-6">
                                    <div class="form-group">
                                      <label class="text-label">Select  Test/Sub Group <span class="required">*</span></label>

                                        <select class="form-control" name="test_id" id="test_id">
                                            <option value="">Select</option>
                                        </select>
                                          <small id="test_id_error" class="form-text text-muted error"></small>

                                    </div>
                                  </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                          <label class="text-label">Test Procedure Name <span class="required">*</span></label>
                                          <input type="text" name="procedure_name" id="procedure_name" onKeyPress="return blockSpecialChar(event)" maxlength="50" class="form-control" placeholder="" required>
                                          <small id="procedure_name_error" class="form-text text-muted error"></small>


                                        </div>



                                    </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="text-label">Test Unit <span class="required">*</span></label>
                                        <input type="text" name="test_unit" id="test_unit" onKeyPress="return blockSpecialChar(event)" maxlength="50" class="form-control" placeholder="" required>
                                        <small id="test_unit_error" class="form-text text-muted error"></small>


                                      </div>
                                  </div>

                                </div>

                                <br>





                              <div class="form-group">
                                <label class="text-label">Report Data</label>

                                <textarea class="form-control" id="report_data" placeholder="Enter the Description" name="report_data"></textarea>
                              </div>

                              <div class="form-group">
                                <label class="text-label">Method</label>

                                <textarea class="form-control" id="method" placeholder="Enter the Description" name="method"></textarea>
                              </div>

                              <div class="form-check">

                                @php
                                $cond=array('display_status'=>1,'is_deleted'=>0);
                                $datas=getTable('test_subranges_master','id',$cond);
                                @endphp
                                @if ($datas)
                                  <div class="row">
                                    @foreach ($datas as $input)

                                      <div class="col-md-6">

                                        <input type="radio" name="" id=""  class="form-check-input radioclick"  >
                                        <label class="form-check-label" for="">{{$input->test_subrange}} </label>
                                      </div>
                                    @endforeach
                                  </div>

                                @endif

                               </div>





                            <div id="crude_edu">
                                <button type="button" type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="saveProcedure(1)" >Save</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-xl-6">
                    <div class="card">
                      <div class="card-body">
                        <div class="table-responsive pt-3">
                          <table id="education-table" class="display">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>Procedure Name</th>
                                <th>Display Status</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>


                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>

      </div>


    </div>
  </div>


</div>
</div>



@include('frames/footer');

<link rel="stylesheet" href="../vendor/select2/css/select2.min.css">
<link href="../vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
<script src="../vendor/select2/js/select2.full.min.js"></script>
{{-- <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script> --}}
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

<script>






  $(document).ready(function() {

    table = $('#group_table').DataTable({
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    'ajax': {
        url: "<?php echo url('/') ?>/masterData/getTestRanges",
        type: 'POST',
        "data": function(d) {}
    },
    "columns": [{
            "data": "id",
            render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
            "data": "test_range"
        },
        {
            "data": "test_subrange"
        },
        {
            "data": "display_status",
            "render": function(display_status, type, full, meta) {
                if (display_status == 1) return '<span class="badge badge-rounded badge-success">Active</span>';
                else return '<span class="badge badge-rounded badge-danger">Inactive</span>';
            }
        },
        {
            "data": "subid",
            "render": function(display_status, type, full, meta) {
                return '<div class="d-flex"><a href="#" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a><a href="#" class="delete btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
            }
        },
    ]
});

$('#education-table').DataTable();
   //////////////////////////////////////////////////////////////
// table2 = $('#education-table').DataTable({
//     dom: 'Bfrtip',
//     buttons: [
//         'copy', 'csv', 'excel', 'pdf', 'print'
//     ],
//     'ajax': {
//         url: "<?php echo url('/') ?>/masterData/getEducations",
//         type: 'POST',
//         "data": function(d) {}
//     },
//     "columns": [{
//             "data": "id",
//             render: function(data, type, row, meta) {
//                 return meta.row + meta.settings._iDisplayStart + 1;
//             }
//         },
//         {
//             "data": "education_name"
//         },
//         {
//             "data": "display_status",
//             "render": function(display_status, type, full, meta) {
//                 if (display_status == 1) return '<span class="badge badge-rounded badge-success">Active</span>';
//                 else return '<span class="badge badge-rounded badge-danger">Inactive</span>';
//             }
//         },
//         {
//             "data": "id",
//             "render": function(display_status, type, full, meta) {
//                 return '<div class="d-flex"><a href="#" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a><a href="#" class="delete btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></a></div>'
//             }
//         },
//     ]
// });




$('#group_table tbody').on('click', '.edit', function() {


         var data = table.row($(this).parents('tr')).data();
         console.log(data);
        $("#hid_met_id").val(data.id);
        $('#hid_met_subid').val(data.subid);
        // $("#test_range").val(data.test_range.trim());
        $("#ranges_value").val(data.test_subrange.trim());
        if(data.display_status==1)  $('#display_status').prop("checked", true); else  $('#display_status').prop("checked", false);
        crude_btn_manage(2,1);

        $(".js-example-tags").val(data.id).trigger("change");


        $('.showdata').addClass('hidedata');

        // $(".js-example-tags").select2({tags:true});

    });




});




$('#group_table tbody').on('click', '.delete', function() {
        var data = table.row($(this).parents('tr')).data();
        deletedata(1,data);
});



////save

function saveProcedure(crude=1)
{

            url='{{route('saveTestProcedure')}}';
            var form = $('#frm1')[0];
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
                                document.getElementById("frm1").reset();
                                swal("Done", result.message, "success");
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

function submit_form(page,crude)
    {
        var url="";
        if(page==1)
        {
            url='{{route('saveTestRanges')}}';
            var form = $('#frm')[0];
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

                                //document.getElementById("frm1").reset();
                                if(page==1)
                                {
                                    table.ajax.reload();
                                    fetchOptions();
                                    $(".js-example-tags").val(null).trigger("change");
                                    $('#duplicate_range').empty();
                                    document.getElementById("frm").reset();
                                }
                                else if(page==2)
                                {
                                    table2.ajax.reload();
                                    document.getElementById("frm1").reset();
                                }
                                else if(page==3)
                                {
                                    table3.ajax.reload();
                                    document.getElementById("frm2").reset();
                                }


                                crude_btn_manage(1,page)
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

        $('.showdata').removeClass('hidedata');

    }

function crude_btn_manage(type=1,page)
{

    var crude;
    if(page==1)
    {
        crude="crude";
    }
    else if(page==2)
    {
        crude="crude_edu";
    }
    else if(page==3)
    {
        crude="crude_occ";
    }
    else if(page==4)
    {
        crude="crude_blood";
    }
    else if(page==5)
    {
        crude="crude_incom";
    }


    if(type==1) $('#'+crude).html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  onclick="submit_form(\''+page+'\',\''+type+'\')" >Save</button>');
    else if(type==2)  $('#'+crude).html('<button type="button" class="btn btn-sm btn-primary my-2 pull-right"  o onclick="submit_form(\''+page+'\',\''+type+'\')" >Update</button>');

}

function deletedata(page,data)
{
    url="";
    if(page==1){
        url='{{route('deleteTestRanges')}}';
    }


    let ajaxval = {
        id: data.subid,
    };
    swal({
        title: 'Are you sure?',
        text: "You won't be able to recover this data!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, Delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: url,
                data: ajaxval,
                success: function(result) {

                    if (result.status == 1) {
                        swal("Done", result.message, "success");
                       table.ajax.reload();


                    } else {
                        sweetAlert("Oops...", result.message, "error");
                    }
                },
            });
        }
        });



}

function duplicate(){


    var html="<div class='row form-group'><div class='col-md-11'><label class='text-label'>Ranges in Value </label><input type='text' name='ranges_value[]' id='ranges_value'  maxlength='250' class='form-control' placeholder='' required></div>";

    html+="<div class='col-md-1'><label style='opacity:0;' class='text-label'>Ranges in Value </label><i class='fa fa-trash' style='position: absolute;bottom: 12px;cursor:pointer' onClick='remove_duplicate(this)'></i></div></div>";
    $('#duplicate_range').append(html);

}
function remove_duplicate(thiss){
    $(thiss).parent().parent().remove();
}


$(document).ready(function(){
    fetchOptions();
});

$(".js-example-tags").select2({

    createTag: function (params) {

        var term = $.trim(params.term);

        if (term === '') {
            return null;
        }

        return {
            id: term,
            text: term,
            newTag: true // add additional parameters
        }
    },
    tags:true,

});

function fetchOptions(){

    var url='{{route('getTestMasterOptions')}}';


            $.ajax({
                type: "POST",
                url: url,
                data: {},
                success: function(result) {

                    $('#test_range').empty().append(result);
                },
            });

}



</script>

<style>
    .select2-container--default .select2-selection--single{
        height:40px!important;
    }


    .hidedata{
        display:none;
    }

</style>

<script>
 $(function(){

// editor = CKEDITOR.replace('report_data', {
//       // uiColor: '#1b3075',
//       removeButtons: 'Save',
//       removePlugins: 'Save'
//   });

//   CKEDITOR.replace('method', {
//       // uiColor: '#1b3075',
//       removeButtons: 'Save',
//       removePlugins: 'Save'
//   });


});


function Adddata(){
  var html="<div class='row'>";
  html+="<div class='col-md-3'><div class='form-group'><label class='text-label'>From Age</label><input type='text' name='education_name' id='education_name' onKeyPress='return blockSpecialChar(event)' maxlength='50' class='form-control' placeholder=''></div></div>";

  html+="<div class='col-md-2'><div class='form-group'><label class='text-label'>To Age</label><input type='text' name='education_name' id='education_name' onKeyPress='return blockSpecialChar(event)' maxlength='50' class='form-control' placeholder=''></div></div>";

  html+="<div class='col-md-2'><div class='form-group'><label class='text-label'>Gender</label><input type='text' name='education_name' id='education_name' onKeyPress='return blockSpecialChar(event)' maxlength='50' class='form-control' placeholder=''></div></div>";

  html+="<div class='col-md-2'><div class='form-group'><label class='text-label'>From Rng</label><input type='text' name='education_name' id='education_name' onKeyPress='return blockSpecialChar(event)' maxlength='50' class='form-control' placeholder=''></div></div>";

  html+="<div class='col-md-2'><div class='form-group'><label class='text-label'>To Range</label><input type='text' name='education_name' id='education_name' onKeyPress='return blockSpecialChar(event)' maxlength='50' class='form-control' placeholder=''></div></div>";
  html+="<div class='col-md-1'><div class='form-group'><label style='opacity:0' class='text-label'>To Range</label><i style='color:red' class='fa fa-trash' onclick=removeFiled(this);></i></div>";

  html+="</div>";
  $('#append_data').append(html);

}



function removeFiled(thiss){
  $(thiss).parent().parent().parent().remove();
}


function loadTest(groupId)
{
    groupId=parseInt(groupId);
    $.ajax({
      type: "POST",
      url:   url='{{route("getTestByGroup")}}',
      data: {groupId:groupId},
      success: function(result){
        $('#test_id').html('');
        $('#test_id').append(result).selectpicker('refresh').trigger('change');
      }
    });





}
  </script>
