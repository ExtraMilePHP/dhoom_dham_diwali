$('head').append( $('<link rel="stylesheet" type="text/css" />').attr('href', 'css/em-popup.css') );

var emSettings = {
    disableOutsideClick: false,
    redirectUrl: '',
    reload:false
};

var disableOutsideClick=false;
function emPop(message,params={}){
    // disableOutsideClick=locked;

    $.extend(emSettings, params);
    console.log(params);
    $(".em-popup").css({"opacity": "1", "pointer-events": "auto"});
    $(".em-icon-container img").css({"top": "0px"});
    $(".em-content").css({"transform": "rotate(-3deg)"});
    $(".em-content").html(message);
}

$(".em-popup").click(function(){
    if(!emSettings.disableOutsideClick){
         emReset();
    }
});

$(".em-popup-button").click(function(){
         emReset();
         if(emSettings.redirectUrl!=''){
            if(emSettings.reload){
                location.reload();
            }else{
                location.href=(emSettings.redirectUrl);
            }
         }
         if(emSettings.reload){
            location.reload();
        }
});


function emReset(){
    $(".em-popup").css({"opacity": "0", "pointer-events": "none"});
    $(".em-icon-container img").css({"top": "-20px"});
    $(".em-content").css({"transform": "rotate(3deg)"});
}