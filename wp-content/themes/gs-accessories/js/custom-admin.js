jQuery(function ($) {


    console.log('admin js working!');

    $('#send-email-admin-id').click(function() {
        var email_address = $('input[name="gsa-email-address"]').val();
        console.log('click worked... ' + email_address);


        var formdata = new FormData();

        formdata.append("gsa_send_email_click", 'click');

        formdata.append("email_address", email_address);

        var rest_url = 'https://www.gs-accessories.dev/wp-json/process_emails/info/3/leonmagee33@gmail.com';

        $.ajax({
            type: 'GET',
            url: rest_url,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data, textStatus, XMLHttpRequest) {
                //console.log( 'made it to success????');
                //$('.register-user-email-taken').hide();
                //$('.uploads-spinner').hide();
                console.log('worked?', data);

                // if (data === 'email_already_taken') {
                //     $('.register-user-email-taken').show();
                // } else if (data === 'invalid_email_address')  {
                //     $('.register-user-email-invalid').show();
                // } else {
                //     $('.mp-update-success').show();
                // }
            },
            error: function (MLHttpRequest, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });





    });





    //rest url
    //https://www.gs-accessories.dev/wp-json/process_emails/info/3/leonmagee33@gmail.com






});