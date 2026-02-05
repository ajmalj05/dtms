<style>
    table.dataTable
    {
        width: 99% !important;
    }
</style>
<div class="content-body">
  <div class="container-fluid pt-2">
    <div class="row">
      <div class="col-md-12">


              <div id="patient-reference" class="tab-pane fade active show">

                <div class="row">
                  <div class="col-xl-4">
                    <div class="card">
                      <div class="card-body">

                        <div class=" mb-5">

                        <form action=""  name="frm" id="frm" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="hidid" id="hidid">
                            <!-- Select Date -->
                            <div class="form-group">
                                <label for="select_date">Select Date</label>
                                <input type="date" value="<?= date('Y-m-d')?>" class="form-control" id="select_date" name="select_date">
                            </div>
                            <!-- Letter title -->
                            <div class="form-group">
                                <label class="text-label">News Letter Title<span
                                class="required">*</span></label>
                                <input type="text" name="letter_title" id="letter_title"   class="form-control" placeholder="">
                                <small id="letter_title_error" class="letter_title_error text-danger"></small>
                            </div>
                            <div class="form-group">
                                <label class="text-label">News Letter Description</label>
                                <textarea class="form-control"  id="news_description" name="news_description"></textarea>
                            </div>

                            <div class="form-group">
                                <label class="text-label">News Letter  url</label>
                                <input type="text" name="letter_url" id="letter_url"   class="form-control" placeholder="">
                            </div>
                            <div class="form-group" id="validation">
                                <label class="text-label">Image to Upload</label>
                                <input type="file" accept="image/png, image/jpeg, image/jpg" class="file-input"onchange="selectImage(this)" id="images" name="images" />
                            </div>



                            <div class="form-check">
                                <input type="checkbox" name="display_status" id="display_status" class="form-check-input" checked >
                                <label class="form-check-label" for="display_status">Display Status</label>
                            </div>

                            <div id="crude">
                                <button type="button" class="btn btn-sm btn-primary my-2 pull-right" onclick="saveNewsLetters(1,1)" id="save-btn" name="save-btn">Save</button>
                            </div>
                        </form>

                        </div>

                    </div>
                    </div>
                  </div>
                  <div class="col-xl-8">
                    <div class="card">
                      <div class="card-body">
                        <div class="table-responsive pt-3">
                          <table id="newsLetterTable" class="display">
                            <thead>
                              <tr>
                                <th>Sl No.</th>
                                <th>News Letter Title</th>
                                <th>Date</th>
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

@include('frames/footer');
@include('modals/view_NewsLetter_blade',['title'=>'Images','data'=>'dfsds'])

<link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
<link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
<script src='date_picker/js/bootstrap-datepicker.min.js' type='text/javascript'></script>
<script src="./vendor/select2/js/select2.full.min.js"></script>
<script src="./js/plugins-init/select2-init.js"></script>

<script>
    var inputLocalFont = document.getElementById("images");
    //----save form data into database table app_newsletters----//

    function saveNewsLetters(page,crude) {
        $("[id*='_error']").text('');
        var url="";
        if(page==1)
        {
            url="{{route('saveNewsLetters')}}";
            var form = $('#frm')[0];
        }
        var formData = new FormData(form);
        formData.append('crude', crude);
        for (var pair of formData.entries()) {
            console.log(pair[0]+ ', ' + pair[1]);
        }
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
                                document.getElementById("frm").reset();
                                
                                table.ajax.reload();

                        
                                crude_btn_manage(1,page)
                            }
                            // else if(result.status==2){
                            //     sweetAlert("Oops...",result.message, "error");
                            // }
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



    //------Data table-----------/



    $(document).ready(function() {
        table = $('#newsLetterTable').DataTable({
            'ajax': {
                url: "<?php echo url('/') ?>/appManagement/listNewsLetters",
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
            "data": "titles"
            },
            {
            "data": "news_date"
            },

            {
            "data": "id",
            "render": function(display_status, type, full, meta) {
                return '<div class="d-flex"><a href="#" id="edit" class="edit btn btn-primary shadow btn-xs sharp mr-1"><i class="fa fa-pencil"></i></a><a href="#" id="delete" class="delete btn btn-danger shadow btn-xs sharp"><i class="fa fa-trash"></i></div>'
            }
        },
            ]
            });
    });



    //----------edit--------------------------------//
    $('#newsLetterTable').on('click', '#edit', function() {
        $("[id*='_error']").text('');
        var data = table.row($(this).parents('tr')).data();
        console.log(data);
        $("#hidid").val(data.id);

        $("#letter_title").val(data.titles);
        $("#news_description").val(data.news_description);
        $("#letter_url").val(data.redirect_url);
       
       

        $('#select_date').val(data.news_date);
        if(data.display_status==1)  $('#display_status').prop("checked", true); else  $('#display_status').prop("checked", false);
	    crude_btn_manage(2,1);


        });


    function crude_btn_manage(type,page)
    {
    if(page==1)
    {
    if(type==1) $('#crude').html('<button type="button" id="save-btn" class="btn btn-sm btn-primary my-2 pull-right"  onclick="saveNewsLetters(\''+page+'\',\''+type+'\')" >Save</button>');
    else if(type==2)  $('#crude').html('<button type="button" id="save-btn" class="btn btn-sm btn-primary my-2 pull-right"   onclick="saveNewsLetters(\''+page+'\',\''+type+'\')" >Update</button>');

    }

    }



    // $('#newsLetterTable tbody').on('click', '#delete', function() {
    //     var data = table.row($(this).parents('tr')).data();
    //     let ajaxval = {
    //         id: data.id,
    //     };
    //     console.log(ajaxval);
    //             $.ajax({
    //                 type: "POST",
    //                 url: "<?php echo url('/') ?>/appManagement/deleteNewsLetter",
    //                 data: ajaxval,
    //                 success: function(result) {

    //                     if (result.status == 1) {
    //                         swal("Done", result.message, "success");
    //                         table.ajax.reload();
    //                     } else {
    //                         sweetAlert("Oops...", result.message, "error");
    //                     }
    //                 },
    //             });


    //     });



        $('#newsLetterTable tbody').on('click', '#delete', function() {
        var data = table.row($(this).parents('tr')).data();
        let ajaxval = {
            id: data.id,
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
                    url: "<?php echo url('/') ?>/appManagement/deleteNewsLetter",
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
        })




    });
    function selectImage(input) {
        $(".text-danger").remove();
            var documentDoc = input.files[0];
            let img = new Image();
            img.src = window.URL.createObjectURL(documentDoc)
            img.onload = () => {
                var imgwidth = img.width;
                var imgheight = img.height;
                 if (imgwidth != 540 || imgheight != 312) {
                    // alert("Please upload an image with a resolution of 540x312 pixels"); 
                    $("#validation").append('<label class="text-danger">Please upload an image with a resolution of 540x312 pixels</label>');
                }
               
            }
    }

    // $('#newsLetterTable tbody').on('click', '.pictures', function() {
    //       $('#newsletterpicture-modal').modal();

       
    //   });


</script>
