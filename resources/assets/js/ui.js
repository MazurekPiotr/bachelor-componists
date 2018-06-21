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
        target = $("#about-link").position().top;
        $('html, body').stop().animate({
            scrollTop: target
        }, 1000);
    });
    $("#scroll-arrow-2").click(function() {
        target = $("#join").position().top;
        $('html, body').stop().animate({
            scrollTop: target
        }, 1000);
    });
    $("#scroll-arrow-3").click(function() {
        target = $("#recent-projects").position().top;
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

    $('#addPost').submit(function(event) {

      var formData = {
           'projectId'              : $('input[name=projectId]').val(),
           'body'             : $('input[name=body]').val(),
           'userId'           : $('input[name=userId]').val()
       };
      $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : '/componists/projects/test/addPost', // the url where we want to POST
            data        : $(this).serialize(), // our data object
            dataType    : 'json', // what type of data do we expect back from the server
                        encode          : true
        })
            // using the done promise callback
        .done(function(data) {
        $('<div class="post row"><div class="col s1 offset-s3"><img style="width:100%" src="' + data.imageURL + '" alt="user-avatar"></div><div class="col s5"><p>'+data.body+'</p><hr></div></div>').appendTo('.posts').fadeIn('slow');
            // here we will handle errors and validation messages
            console.log(data);
        });
        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();

    });


    var $image = $('#setprofilepic');

    $image.cropper({
      aspectRatio: 16 / 16,
      crop: function(event) {
        console.log(event.detail.x);
        console.log(event.detail.y);
        console.log(event.detail.width);
        console.log(event.detail.height);
        console.log(event.detail.rotate);
        console.log(event.detail.scaleX);
        console.log(event.detail.scaleY);
      }
    });
});
