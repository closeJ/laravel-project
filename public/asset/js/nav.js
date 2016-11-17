$(document).ready(function() {
    $('.navbar-twitch').toggleClass('open');
    $('.container-fluid .col-md-10').css({'margin-left':'16%'});
    $('.container-fluid .col-lg-9').css({'margin-left':'20%'});
    $('.container-fluid .col-md-9').css('margin-left','22%');
    $('.col-lg-8.col-md-8').css('margin-left','20%');
    $('[data-toggle="tooltip"]').tooltip();
    var toggle = 0;
    $('.navbar-twitch-toggle').on('click', function(event) {
        event.preventDefault();
        $('.navbar-twitch').toggleClass('open');
        $('.navbar-purple').fadeIn();
        $('[data-toggle="tooltip"]').tooltip('hide');
        if (toggle == 0){
        	$('.container-fluid .col-md-10').animate({'right':'13%','width':'83%'});
            $('.container-fluid .col-md-9').animate({'right':'9%'});
            $('.container .col-md-8').animate({'right':'8%'});
            $('.col-lg-8.col-md-8').animate({'right':'9%'});
        	toggle = 1;
        } else if(toggle == 1){
        	$('.container-fluid .col-md-10').animate({'right':'2%','width':'83exit%'});
            $('.container-fluid .col-md-9').animate({'right':'-1%'});
            $('.container .col-md-8').animate({'right':'-1%'});
            $('.col-lg-8.col-md-8').animate({'right':'-4%'});
        	toggle = 0;
        }
    });
    $('.col-md-10 .form-group').css({'display':'inline-block'});
    $(".tree input[type=checkbox]").css({'width':'37px','height':'17px'});
    $("#cancel").click(function(){
        window.location.href= document.referrer;
    });

});