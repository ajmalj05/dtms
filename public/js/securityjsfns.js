// JavaScript Document  page developed By AKhil A.P

// disable speacial charecters exept space
function blockSpecialChar(event) {
    var regex = new RegExp("^[0-9a-zA-Z-. \b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
}

function blockWithoutSlash(event) {
    var regex = new RegExp("^[A-Z0-9a-z/\//\]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);

    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
}
// enable only number
function emailOnly(event) {
    var regex = new RegExp("^[A-Z0-9a-z@\._]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }

}

function hifenCharecters(event) {

    var regex = new RegExp("^[a-z\d\-]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
}

function Onlycharecters(event) {
    var regex = new RegExp("^[A-Za-z \b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
}

function onlyAddress(event) {
    var regex = new RegExp("^[0-9a-zA-Z-. /\b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
}

function onlyFilepath(event) {

    var regex = new RegExp("^[A-Z0-9a-z@#\._]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }

}

// enable only number

function onlyNumbers(event) {
    var regex = new RegExp("^[0-9 \b]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
}

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 46 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function alphanumeric(evt) {

    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        return false;
    }
    return true;
}


function onlyDotnumbers(event) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 46 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function clearError(eid) {

    var errorid = eid + "_error";
    $("#" + errorid).html("");
}

$(".form-control").keyup(function() {
    var eid = $(this).attr("id");
    clearError(eid);
});

$(".form-control").change(function() {
    var eid = $(this).attr("id");
    clearError(eid);
});