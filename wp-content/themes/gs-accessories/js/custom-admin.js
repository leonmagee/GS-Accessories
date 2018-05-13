jQuery(function ($) {


    console.log('admin js working!');

    $('#send-email-admin').click(function() {
        var email_address = $('input[name="gsa-email-address-admin"]').val();
        var post_id = $('input[name="gsa-hidden-post-id"]').val();
        //console.log('click worked... ' + email_address);

        var spinner = $(this).parent().find('.gsa_spinner');
        var success = $(this).parent().find('.success');
        var alert = $(this).parent().find('.alert');

        spinner.show();
        success.hide();
        alert.hide();


        var rest_url = 'https://www.gs-accessories.dev/wp-json/process_emails/admin/' + post_id + '/' + email_address;

        $.ajax({
            type: 'GET',
            url: rest_url,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data, textStatus, XMLHttpRequest) {
                console.log('worked?', data);
                spinner.hide();
                if ( data === true ) {
                    success.show();
                } else {
                    alert.show();
                }

            },
            error: function (MLHttpRequest, textStatus, errorThrown) {
                //alert(errorThrown);
                spinner.hide();
                alert.show();
            }
        });





    });





    //rest url
    //https://www.gs-accessories.dev/wp-json/process_emails/info/3/leonmagee33@gmail.com






});