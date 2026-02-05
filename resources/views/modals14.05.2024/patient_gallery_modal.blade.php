<style>
    #searchpatient_MDT tr:hover {
        background-color: #d7dae3!important;
    }
    #searchpatient_MDT tr:hover td {
        background-color: transparent!important; /* or #000 */
    }
</style>
<div id="patient-gallery-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

            <div class="modal-content">
                <div class="modal-header">
                    <div class="card-title">
                        <p>{{$title}}</p>

                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <div class="main-content" id="htmlContent">

<div class="page-content">
    <div class="container-fluid">

    <div class="row">
                                <div class="col-xl-12 col-xxl-12 col-lg-12 col-sm-12">

                                            <section>
                                            <form name="frm" id="document_gallery_form"  method="POST">
                                                <div class="row d-flex justify-content-center">

                                                        <div id="my_camera"></div>
                                                        <div class="preview-area" ></div><div id="results" ></div>

                                                </div>
                                                <br>
                                                <div class="row d-flex justify-content-center">


                                                            <button type="button" class="openwebcam btn btn-primary btn-xs" onClick="openwebcam()">Open Webcam</button>

                                                    <button type="button" class="take_snapshot hidedata btn btn-primary btn-xs" onClick="take_snapshot()">Take Snapshot</button>




                                                        <input type="file" id="imgupload" style="display:none" name="patient_image[]" multiple >

                                                        <input type="hidden" id="patient_snapshot" style="display:none" name="patient_snapshot" />

                                                        <button type="button" class="btn btn-info btn-xs" onClick="open_gallery()">Open From Gallery</button>
                                                        </div>

                                                        <div class="row d-flex justify-content-center">
                                                            </div>
                                                <div class="row d-flex justify-content-center">
                                                    <textarea id="patient_snapshot_new" name="patient_snapshot_new" style="display:none" ></textarea>

                                                            <button type="button" class="btn btn-sm btn-primary mt-1" onclick="submit_photo()">Save</button>

                                                </div>
                                            </div>
</form>
                                        </section>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            <div class="col-xl-12 col-lg-12 col-sm-12">

                                        <table id="patient_gallery_data" class="display table" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th> Sl No</th>
                                                <th> Name</th>
                                                <th> Type</th>
                                                <th>Created Date</th>
                                                <th>Action</th>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>

                            </div>
                        </div>



        </div>


                    <!-- End Page-content -->
            </div>

    </div>
</div>

</div>
</div>
<script>

  var inputLocalFont = document.getElementById("imgupload");
inputLocalFont.addEventListener("change",previewImages,false);
function submit_photo()
    {
        var url="";



            url='{{route('save-patient-gallery')}}';

            var form = $('#document_gallery_form')[0];
            var formData = new FormData(form);
            let TotalFiles =inputLocalFont.files.length;

                let files = inputLocalFont;
                for (let i = 0; i < TotalFiles; i++) {
                formData.append('files' + i, files.files[i]);
                }
                formData.append('TotalFiles', TotalFiles);


        $.ajax({
				 type: "POST",
				 url: url,
				 data: formData,
				 processData: false,
				 contentType: false,
				 success: function(result){

                            if(result.status==1)
                            {
                                document.getElementById("document_gallery_form").reset();
                                swal("Done", result.message, "success");
                                $('#patient_gallery_data').DataTable().ajax.reload();
                            }
                            else if(result.status==2){
                                sweetAlert("Oops...",result.message, "error");
                                table.ajax.reload();
                            }
                            else{
                                sweetAlert("Oops...",result.message, "error");
                                 table.ajax.reload();

                            }
                            $('.preview-area').html('');
                            $('#results').html('');

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
    function previewImages() {


        var fileList = this.files;

        var anyWindow = window.URL || window.webkitURL;
        for(var i = 0; i < fileList.length; i++){
          var objectUrl = anyWindow.createObjectURL(fileList[i]);
          $('#previewImg').attr("style", 'display:none;height:150px;width:150px');
              $('.preview-area').html('<img src="' + objectUrl + '" style="height:50px;width:50px"/>');
          window.URL.revokeObjectURL(fileList[i]);
        }
}
$('#patient_gallery_data tbody').on('click', '.delete_photo_gallery', function() {

    var data = $('#patient_gallery_data').DataTable().row($(this).parents('tr')).data();

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
                url: "<?php echo url('/') ?>/delete-patient-gallery",
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


function openwebcam(){
$('#my_camera').removeClass('hidedata');
$('.openwebcam').addClass('hidedata');
$('.take_snapshot').removeClass('hidedata');
Webcam.set({
width: 320,
height: 240,
image_format: 'jpeg',
jpeg_quality: 90
});
Webcam.attach( '#my_camera' );

$('#previewImg').attr("style", 'display:none');
}
function open_gallery() {
$('.openwebcam').removeClass('hidedata');
$('.take_snapshot').addClass('hidedata');
$('#previewImg').attr("style", 'display:block;height:150px;width:150px');
$('#my_camera').addClass('hidedata');


$('#imgupload').trigger('click');
if ( $('#imgupload').files &&  $('#imgupload').files [0]) {
var reader = new FileReader();

reader.onload = function (e) {
$("#previewImg").attr("src", reader.result);
}

reader.readAsDataURL(input.files[0]);

$('#patient_snapshot').val('');
$('#patient_snapshot_new').val('');
}
}
    function take_snapshot() {
    // play sound effect
    // shutter.play();

    // take snapshot and get image data
        Webcam.snap( function(data_uri) {

        console.log(data_uri);
        $('#patient_snapshot_new').val(data_uri);
        // display results in page
        document.getElementById('results').innerHTML =
        '<img src="'+data_uri+'"/>';
        $('#imgupload').val('');

        $('#pro_pic').attr('src',data_uri);
        $('#pro_pic').attr('width','150');
        } );

    }
</script>
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
    .hidedata{
                display:none;
            }
</style>


<link rel="stylesheet" href="{{asset('/vendor/select2/css/select2.min.css')}}">
<link href="{{asset('/vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">
<script src="{{asset('date_picker/js/bootstrap-datepicker.min.js')}}" type='text/javascript'></script>
<script src="{{asset('./vendor/select2/js/select2.full.min.js')}}"></script>
<script src="../js/plugins-init/select2-init.js"></script>
<script type="text/javascript" src="../webcam/webcamjs/webcam.min.js"></script>
<script>

</script>
