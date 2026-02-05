<style>
.bgcoloruser {
    background-color: #9796d7;
}

.bgcoloradmin {
    background-color: #6f7470;
}
</style>
<div class="content-body">
    <div class="container-fluid pt-2">
        <div class="row" style="">
            <div class="col-md-12">
                <div id="patient-reference" class="tab-pane fade active show">
                    <div class="row">
                        <div class="col-xl-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class=" mb-5">
                                        <!-- <form action="#" name="frm" id="frm" method="POST"> -->
                                        <div class="form-group">
                                            <div class="search">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                                <input type="search" name="chat_name" id="chat_name" maxlength="90"
                                                    class="form-control" placeholder="Search User" keyup="searchuser()">
                                            </div>
                                        </div>
                                        <div class="form-group">

                                            <div class="listuser">

                                            </div>
                                        </div>
                                        <!-- </form> -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class=" col-xl-9">

                            <div class="card">
                                <div class="card_header">
                                    <div style="background-color: #9796d7;height: 50px;" class="text-center"
                                        id="div_chat" data-id="0">
                                        <label for="" class="p-1 mt-2 text-white" id="lbl_Chat"> <b>Chat</b> </label>
                                    </div>

                                </div>
                                <div class="chatlist card-body">

                                </div>
                                <div class="crad-footer">
                                    <div class="row ml-1 ">
                                        <div class="col-10">
                                            <input type="text" id="replayMessage" name="replayMessage"
                                                class="form-control mb-2" style="padding: inherit;">

                                        </div>
                                        <div class="col-2 ">
                                            <button type="button" class="btn btn-success btn-sm"
                                                onclick="replayData()">Send</button>
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
</div>
<input type="hidden" name="hidOrderId1" id="hidOrderId1" value="0">

@include('frames/footer');
<link rel="stylesheet" href="./vendor/select2/css/select2.min.css">
<link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
<script src='date_picker/js/bootstrap-datepicker.min.js' type='text/javascript'></script>
<script src="./vendor/select2/js/select2.full.min.js"></script>
<script src="./js/plugins-init/select2-init.js"></script>

<script>
$(document).ready(function() {
    loadgetuser();
});
function loadgetuser(){
    $.ajax({
        url: "<?php echo url('/') ?>/ChatManagement/getLIveChat",
        type: "POST",
        success: function(response) {
            $(".listuser").empty();

            let data = response;
            console.log(data);
            $.each(data, function(key, value) {

                    var user_data =  `'${value.name}', '${value.user_id}', '${value.patient_id}'`;
                    var htmldata =
                        '<div class="card mb-2" style="background-color: #9796d7; "onclick="getchat(' +
                        user_data + ');"><div class="p-3 text-white">' + value.name +
                        '</div></div>';
                    $(".listuser").append(htmldata);
                    $('#loader').hide();

                }

            );
        },
    })

}

function getchat(user_name, user_id,patient_id) {

    $.ajax({
        url: "<?php echo url('/') ?>/ChatManagement/getmessage",
        type: "POST",
        data: {
            user_id: patient_id
        },
        success: function(response) {
            let data2 = response;
            $(".chatlist").empty();
            
            $.each(data2, function(key, value) {
                var msgtxt = value.message_text;
                let cls_align = value.admin_id > 0 ? 'd-flex flex-row-reverse' : 'row';
                let cls_color = value.admin_id > 0 ? 'bgcoloradmin' : 'bgcoloruser';

                var htmldata = '<div class="' + cls_align + '"><div class="card p-2 ' + cls_color +
                    '" ><label class="text-white" for="">' + msgtxt +
                    '</label><label for="" class="d-flex flex-row-reverse text-white " style="font-size: 10px">' +
                    value.created_at + '</label> </div></div>';
                $(".chatlist").append(htmldata);
            });
            $('#loader').hide();
            $("#div_chat").attr("data-id", user_id);
            $("#lbl_Chat").html(user_name);
            $('#hidOrderId1').val(patient_id);

        },
    }).done(function() {
        $('html, body').scrollTop($(".chatlist").height());
    });

}

function replayData() {
    let user_idv = $("#div_chat").attr("data-id");
    var patientId = $('#hidOrderId1').val();
    var inputValue = document.getElementById("replayMessage").value;

    if (user_idv == 0) {
        alert("Please Select A chat")
    } else if (inputValue == "") {
        alert("Please Enter Data")
    } else {
        $.ajax({
            url: "<?php echo url('/') ?>/ChatManagement/savemessage",
            type: "POST",
            data: {
                inputValue: inputValue,
                user_id: user_idv,
                patientId:patientId
            },
            success: function(response) {
                let data = response;
            },
        }).done(function() {
            $("#replayMessage").val("");
            let user_namev = $("#lbl_Chat").html();
            getchat(user_namev, user_idv,patientId);
        });
    }
}
$("#replayMessage").on('keyup', function(e) {
    if (e.which == 13) {
        replayData();
    }
});



document.getElementById('chat_name').addEventListener('keyup', function(event) {
    let userInput = event.target.value;
    if(userInput==""){ 
        // $(".chatlist").empty();

        loadgetuser();
        }
        else{
            searchUserData(userInput);
}

});
 function searchUserData(userInput){
    $.ajax({
        url: "<?php echo url('/') ?>/ChatManagement/searchUserData",
        type: "POST",
        data: {
            inputValue: userInput,
        },
        success: function(response) {
            $(".listuser").empty();

            let data = response;
            $.each(data, function(key, value) {

                    var user_data = '\'' + value.name.toString() + '\',\'' + value.user_id
                        .toString() + '\'';
                    var htmldata =
                        '<div class="card mb-2" style="background-color: #9796d7; "onclick="getchat(' +
                        user_data + ');"><div class="p-3 text-white">' + value.name +
                        '</div></div>';
                    $(".listuser").append(htmldata);
                    $('#loader').hide();
                }

            );

        },
    }).done(function() {})

 }
</script>