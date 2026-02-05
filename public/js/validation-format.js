
function blockWithoutSlashAndOnlyCharacter(event) {
    var regex = new RegExp("^[A-Za-z \b/\//\]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);

    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
}

function onlyNumbersAndCharacter(event) {
    var regex = new RegExp("^[a-zA-Z0-9 ]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
}

function blockWithoutPercentageAndOnlyNumbers(event) {
    var regex = new RegExp("^[0-9 \b/\%\]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
}
