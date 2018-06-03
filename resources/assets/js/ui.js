$( document ).ready(function(){
    $(document).scroll(function () {
        var scroll = $(window).scrollTop();
        if(scroll > 0) {
            $('nav').css('height', '90px');
            $('.nav-wrapper, .nav-wrapper i, .button-collapse').css('line-height', '90px');

        } else {
            $('nav').css('height', '64px');
            $('.nav-wrapper, .nav-wrapper i, .button-collapse').css('line-height', '64px');
        }
    });
    $('.button-collapse').sideNav({
        menuWidth: 300, // Default is 300
        edge: 'left', // Choose the horizontal origin
        closeOnClick: true, // Closes side-nav on <a> clicks, useful for Angular/Meteor
        draggable: true, // Choose whether you can drag to open on touch screens,
        onOpen: function(el) { }, // A function to be called when sideNav is opened
        onClose: function(el) {}, // A function to be called when sideNav is closed
    });

    $('.modal').modal({
        dismissible: true, // Modal can be dismissed by clicking outside of the modal
        opacity: .5, // Opacity of modal background
        inDuration: 300, // Transition in duration
        outDuration: 200, // Transition out duration
        startingTop: '4%', // Starting top style attribute
        endingTop: '10%', // Ending top style attribute
    });

    $("#scroll-arrow").click(function() {
        target = $("#about-link").offset().top - 60;
        $('html, body').stop().animate({
            scrollTop: target
        }, 1000);
    });
    for(var i=0;i<9;i++) {
        var x = $('.notes li')[i];
        $(x).css('-webkit-animation','music 1s '+i+'00ms  ease-in-out both infinite');
    }

    $('select').material_select();

    $(document).ready(function(){
        $('.carousel').carousel();
    });
});