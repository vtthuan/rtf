jQuery(document).ready(function () {

    $("#contactForm").submit(function (e) {
        e.preventDefault();
        $("#small-dialog4").hide();
 
        $.ajax({
            type: $(this).attr('method'),
            url: Routing.generate('addContactMessage'),
            data: $(this).serialize()
        })
        .done(function (data) {
            if (typeof data.message !== 'undefined') {
                alert(data.message);
            }
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            if (typeof jqXHR.responseText !== 'undefined') {
                 var responseJSON = jQuery.parseJSON(jqXHR.responseText);
                if (responseJSON.hasOwnProperty('form')) {
                    $('.feedback-grid').html(responseJSON.form);
                }
 
                $('.form_error').html(responseJSON.message);
 
            } else {
                alert(errorThrown);
            }
 
        });
    });

    $('.popup-with-zoom-anim').magnificPopup({
        type: 'inline',
        fixedContentPos: false,
        fixedBgPos: true,
        overflowY: 'auto',
        closeBtnInside: true,
        preloader: false,
        midClick: true,
        removalDelay: 300,
        mainClass: 'my-mfp-zoom-in'
    });

});