<div class="content-body">
    <div class="container-fluid pt-2" style="height: 1200px">

        <style>
            .main-box {
                background: #FFFFFF;
                box-shadow: 1px 1px 2px 0 #cccccc;
                margin-bottom: 16px;
                /* overflow: hidden; */
                border-radius: 3px;
                background-clip: padding-box;
            }

            .profile-box-contact .main-box-body {
                padding: 0;
            }

            .profile-box-contact .profile-box-header {
                padding: 30px 20px;
                color: #fff;
                border-radius: 3px 3px 0 0;
                background-clip: padding-box;
                max-height: 188px;
                /* stops bg color from leaking outside the border: */
            }

            .profile-box-contact .profile-img {
                border-radius: 50%;
                background-clip: padding-box;
                /* stops bg color from leaking outside the border: */
                width: 110px;
                height: 110px;
                float: left;
                margin-right: 15px;
                border: 5px solid #fff;
            }

            .profile-box-contact h2 {
                padding: 8px 0 3px;
                margin: 0;
                display: inline-block;
                font-weight: 400;
                line-height: 1.1;
            }

            .profile-box-contact .job-position {
                font-weight: 300;
                font-size: 0.875em;
            }

            .profile-box-contact .profile-box-footer {
                padding-top: 10px;
                padding-bottom: 15px;
            }

            .profile-box-contact .profile-box-footer a {
                display: block;
                width: 33%;
                width: 33.33%;
                float: left;
                text-align: center;
                padding: 15px 10px;
                color: #344644;
            }

            .profile-box-contact .profile-box-footer a:hover {
                text-decoration: none;
            }

            .profile-box-contact .profile-box-footer .value {
                display: block;
                font-size: 1.8em;
                font-weight: 300;
            }

            .profile-box-contact .profile-box-footer .label {
                display: block;
                font-size: 1em;
                font-weight: 300;
                color: #344644;
            }

            .profile-box-contact .contact-details {
                padding: 4px 0 0;
                margin: 0;
                list-style: none;
                font-size: 0.875em;
                font-weight: 300;
            }

            .profile-box-contact .contact-details li i {
                width: 12px;
                text-align: center;
                margin-right: 3px;
            }

            .gray-bg {
                background-color: #01579b  !important;
            }

            .pname {
                color: #FFF;
            }
            .pid
            {
                font-size: 18px;
            }

        </style>
<script type="text/javascript" src="webcam/webcamjs/webcam.min.js"></script>

        <form action=""  method="GET" id="get-search-patient-data">
        @if(session()->has('message'))
                            <div class="alert alert-success">

                                        <i class="icon-ok-sign"></i><strong></strong> {{ Session::get('message') }}
                                    </div>
                            @endif
                            @if(session()->has('error'))
                            <div class="alert alert-danger">

                                        <i class="icon-ok-sign"></i><strong></strong> {{ Session::get('error') }}
                                    </div>
                            @endif
            <div id="search-form">
{{--                <form action="" method="POST" name="frm" id="frm">--}}


                    <div class="form-group">
                                    <div class="row">
{{--                            <div class="col-md-3">--}}
{{--                                <div class="radio">--}}
{{--                                    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1"--}}
{{--                                        checked="">--}}
{{--                                    <label for="optionsRadios1">--}}
{{--                                        Newly Added Patients--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-3">--}}
{{--                                <div class="radio">--}}
{{--                                    <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">--}}
{{--                                    <label for="optionsRadios2">--}}
{{--                                        Recently Visited Patients--}}
{{--                                    </label>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <a href="patientRegistration"><button type="button" class="btn btn-info btn-xs">ADD New</button></a>--}}
                        </div>
                         <div class="input-group">
                            <input type="text" name="search" id="search" class="form-control input-lg"
                                placeholder="Search using Name / Mobile Number/Email/UHID"
                                 value="{{ isset($_GET['search'])?base64_decode($_GET['search']):''}}" autocomplete="off">
                            <div class="input-group-btn">
                                <button class="btn btn-square btn-primary btn-sm" type="submit" >
                                    <i class="fa fa-search"></i> Search
                                </button>
                            </div>
                    </div>

            </div>
{{--        </form>--}}

        <br>
    </div>
    <div class="row">
            @foreach($patient_data as $data)
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12" style="cursor: pointer;height:240px">

            <div class="main-box clearfix profile-box-contact">
                <div class="main-box-body clearfix">
                    <div class="profile-box-header gray-bg clearfix">
                        @php
                            $condition=array('patient_id'=>$data->id);
                            $checked=getASingleValueByorderLimit("patient_gallery",$condition,'id');
                            $image = (!is_null($checked)) ? $checked->image : '';
                        @endphp
                        @if(! is_null($checked))
                            <img src="{{ url('/images/'.$image) }}" alt="" class="profile-img img-responsive">
                        @elseif(trim($data->gender)=='m')
                            <img src="images/user-male.png" alt="" class="profile-img img-responsive">
                        @elseif(trim($data->gender)=='f')
                            <img src="images/user-female.png" alt="" class="profile-img img-responsive">
                        @else
                            <img src="images/user.png" alt="" class="profile-img img-responsive">
                        @endif

                        <div class="job-position" style="font-size:20px;font-weight: 600;margin-top: -6%;">
                            UHID No:{{$data->uhidno}}
                        </div>

                        <h2 class="pname" style="font-size: 20px;">
                            @php
                                if (strlen($data->name) > 23)
                                    $str = substr($data->name, 0, 20);
                            @endphp
                            {{$str}}
                        </h2>
                        <ul class="contact-details" style="font-size:14px;">
                            <li style="font-weight: 500"> </li>
                            <li><i class="fa fa-phone"></i> {{$data->mobile_number}}</li>
                            <li><i class="fa  fa-envelope"></i>
                                @if(isset($data->email))
                                    @if (strpos($data->email, '@') !== false)
                                        {{ $data->email }}
                                    @else
                                        {{ $data->email . $data->extension }}
                                    @endif
                                @else
                                    {{ '' }}
                                @endif
                            </li>
                        </ul>
                        <div class="bootstrap-badge">
                          <a href="{{ URL('/patientRegistration?id='.$data->id )}}">  <span class="badge light badge-primary" title="Patient Edit"><i class="fa fa-pencil"></i></span></a>


                          <span class=" myModal_btn badge light badge-secondary" data_id="{{$data->id}}" data-toggle="modal" title=" Patient WebCam"><i class="fa fa-camera"></i></span>

{{--                            <span class=" myModal_btn1 badge light badge-secondary" onclick="open_patient_images({{$data->id}})" data_id="{{$data->id}}" data-toggle="modal" title="Patient Gallery"><i class="fa fa-eye"></i></span>--}}


                           <a href="{{URL('/PdfGenerator/idcard/'.$data->id )}}" target="_blank"> <span class="badge light badge-info" title="Patient QR Code Generator"><i class="fa fa-qrcode"></i></span></a>

                           <a href="{{URL('/dtmshome/'.$data->id )}}" > <span class="badge light badge-info" data-toggle="tooltip" data-placement="bottom" title="Open DTMS"><i class="fa fa-hospital-o"></i></span></a>

                        </div>

                    </div>


                </div>
            </div>

        </div>
            @endforeach






    </div>


    </form>
</div>
</div>
@include('frames/footer');
<!-- Modal -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog "modal-dialog modal-lg role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Web Cam</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="frm" id="frm" action="{{URL('saveImages')}}" method="POST" enctype=multipart/form-data>
                    @csrf
                    <input type="hidden" id="patient_id" name="patient_id">
                    <div class="row d-flex justify-content-center">
                        <!-- <img id="previewImg" src="images/user.png" alt="Placeholder" style="height:150px;width:150px"> -->
                        @if(isset($gallery))

                            <img id="previewImg" src="{{ url('/images/'.$gallery->image) }}" alt="Placeholder" style="height:150px;width:150px">
                        @else



                            <img id="previewImg" src="images/user.png" alt="Placeholder" style="height:150px;width:150px">
                        @endif

                    </div>
                    <br>
                    <br>
                    <a type="button" class="btn btn-primary btn-xs take_snapshot" onClick="take_snapshot()">Take Snapshot</a>
                    <input type="file" id="imgupload" style="display:none" name="patient_image" accept="image/png, image/gif, image/jpeg" onchange="preview_image()"/>
                    <a type="button" class="btn btn-info btn-xs" onClick="open_gallery()">Open From Gallery</a>
                    <br>  <br>
                    <div class="row d-flex justify-content-center">
                        <div id="results" ></div>

                    </div>


            </div>
            <div class="modal-footer">
                <a type="button" class="btn btn-danger light" data-dismiss="modal">Close</a>
                <button type="submit" class="btn btn-primary" >Save changes</button>
            </div>
        </div>
        </form>
    </div>
</div>
<!-- Modal -->
<div id="myModal1" class="modal fade" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog "modal-dialog modal-lg role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Images</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="test"></div>
            </div>
        </div>
    </div>
</div>
<script src="/js/jsSlider.js"></script>
<script>



$(function(){
            var images = [
                {src: 'https://source.unsplash.com/1600x1200/?fitness', alt: 'image1'},
                {src: 'https://source.unsplash.com/1600x1200/?workout', alt: 'image2'},
                {src: 'https://source.unsplash.com/1600x1200/?yoga', alt: 'image3'},
                {src: 'https://source.unsplash.com/1600x1200/?running', alt: 'image4'},
                {src: 'https://source.unsplash.com/1600x1200/?pilates', alt: 'image5'}
            ]

            $('.test').jsSlider(images, 500);
            $(".viewFull").hide();
        })


    function form_submit() {
        var patient_id = $("#patient_id").val();
        var patient_image = $("#imgupload").val();
        _token="{{csrf_token()}}";

        $.ajax({
                    type: 'POST', //THIS NEEDS TO BE GET
                    url:"{{url('savetImages')}}",
                    dataType: 'json',
                    data: {patient_id:patient_id,patient_image:patient_image, _token:_token},
                    success: function (data) {

                    },error:function(){
                        console.log();
                    }
                });
    }
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
    function take_snapshot() {
        // play sound effect
        shutter.play();

        // take snapshot and get image data
        Webcam.snap( function(data_uri) {

            console.log(data_uri);
            // display results in page
            document.getElementById('results').innerHTML =
            '<img src="'+data_uri+'"/>';
            $('#imgupload').val('');
            $('#patient_snapshot').val(data_uri);
            $('#pro_pic').attr('src',data_uri);
            $('#pro_pic').attr('width','150');
        } );

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
    }
    }
    $(document).ready(function(){
       $('.myModal_btn').click(function(e){

           e.preventDefault();

          $("#patient_id").val( $(this).attr('data_id'));
           $('#myModal').modal('show');
       })
    })
    // $(document).ready(function(){
    //    $('.myModal_btn1').click(function(e){

    //        e.preventDefault();


    //        $('#myModal1').modal('show');
    //    })
    // })

    function open_patient_images(id)
    {

        ajaxval={
            id:id
        };
        $.ajax({
                type: "POST",
                url: "<?php echo url('/') ?>/patientGallery",
                data: ajaxval,
                success: function(result) {

                },
            });
    }
    function preview_image() {

        var file =  $('#imgupload').get(0).files[0];

        if(file){
            var reader = new FileReader();

            reader.onload = function(){
                $("#previewImg").attr("src", reader.result);
            }

            reader.readAsDataURL(file);
        }

   }

$("#get-search-patient-data").submit(function(e){
    e.preventDefault();
    var data = $("#search").val();
    window.location.href = '{{url("patientBooks")}}?search='+ window.btoa(data);
});

$(document).keydown(function(event) {
    if (event.which === 13)
    {
        event.preventDefault();
        var data = $("#search").val();
        window.location.href = '{{url("patientBooks")}}?search='+ window.btoa(data);
    }
});
</script>
