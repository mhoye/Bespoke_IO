popupEnabled = 0;

function centerPopup() {
    var windowWidth = document.documentElement.clientWidth;
    var windowHeight = document.documentElement.clientHeight;
    var popupHeight = $("#repackingPopup").height();
    var popupWidth = $("#repackingPopup").width();
    $("#repackingPopup").css({
        "position":"fixed",
        "top": windowHeight/2-popupHeight/2,
        "left": windowWidth/2-popupWidth/2
    });
}

function displayPopup() {
    if (popupEnabled == 0) {
        $("#repackingPopup").fadeIn(1000);
        popupEnabled = 1;
    }   
}

function closePopup() {
    if (popupEnabled == 1) {
        $("#repackingPopup").fadeOut("slow");
        popupEnabled = 0;
    }
}

$(document).ready(function () {
    $("#confirm").click(function() {
        centerPopup();
        displayPopup();
    });
    $("#popupClose").click(function (){
        closePopup();
    });
});
