jQuery(function ($) {


    console.log('admin js working!');

    $('#send-email-admin-id').click(function() {
        var email_address = $('input[name="gsa-email-address"]').val();
        console.log('click worked... ' + email_address);
    });

});