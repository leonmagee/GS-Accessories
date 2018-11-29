jQuery(function ($) {

    /**
    * @todo localize site url
    */
    $('#send-email-admin').click(function() {

        var email_address = $('input[name="gsa-email-address-admin"]').val();
        var post_id = $('input[name="gsa-hidden-post-id"]').val();
        var spinner = $(this).parent().find('.gsa_spinner');
        var success = $(this).parent().find('.success');
        var alert = $(this).parent().find('.alert');

        spinner.show();
        success.hide();
        alert.hide();

        var site_url = 'https://mygsaccessories.com';
        //var site_url = 'https://www.gs-accessories.dev';

        var rest_url = site_url + '/wp-json/process_emails/admin/' + post_id + '/' + email_address;

        $.ajax({
            type: 'GET',
            url: rest_url,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data, textStatus, XMLHttpRequest) {
                //console.log('worked?', data);
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

    $('#send-email-user').click(function() {

        var email_address = $('input[name="gsa-email-address-user"]').val();
        var user_id = $('input[name="gsa-user-id"]').val();
        //console.log('user id: ' + user_id);
        var post_id = $('input[name="gsa-hidden-post-id"]').val();
        
        var spinner = $(this).parent().find('.gsa_spinner');
        var success = $(this).parent().find('.success');
        var alert = $(this).parent().find('.alert');

        spinner.show();
        success.hide();
        alert.hide();

        var site_url = 'https://mygsaccessories.com';
        //var site_url = 'https://www.gs-accessories.dev';

        var rest_url = site_url + '/wp-json/process_emails/user/' + post_id + '/' + email_address + '/' + user_id;

        $.ajax({
            type: 'GET',
            url: rest_url,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data, textStatus, XMLHttpRequest) {
                //console.log('worked?', data);
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

    $('#send-email-user-tracking').click(function() {

        var email_address = $('input[name="gsa-email-address-user-tracking"]').val();
        var user_id = $('input[name="gsa-user-id"]').val();
        //console.log('user id: ' + user_id);
        var post_id = $('input[name="gsa-hidden-post-id"]').val();
        var tracking_number = $('input[name="gsa-tracking-number"]').val();
        var shipping_service = $('input[name="gsa-shipping-service"]').val();
        
        //console.log(tracking_number + ' ' + email_address);
        var spinner = $(this).parent().find('.gsa_spinner');
        var success = $(this).parent().find('.success');
        var alert = $(this).parent().find('.alert');



        spinner.show();
        success.hide();
        alert.hide();

        var site_url = 'https://mygsaccessories.com';
        //var site_url = 'https://www.gs-accessories.dev';

        var rest_url = site_url + '/wp-json/process_emails/user/' + post_id + '/' + email_address + '/' + tracking_number + '/' + shipping_service + '/' + user_id;

        $.ajax({
            type: 'GET',
            url: rest_url,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data, textStatus, XMLHttpRequest) {
                //console.log('worked?', data);
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


    $('#send-rma-email').click(function() {

        console.log('approving');
        var email_address = $('input[name="gsa-email-address-user"]').val();
        var rma_message = $('textarea[name="rma-message"').val();
        if ( ! rma_message ) {
            rma_message = 'BLANK';
        }
        var post_id = $('input[name="gsa-hidden-post-id"]').val();
        var user_id = $('input[name="gsa-user-id"]').val();
        
        var spinner = $(this).parent().find('.gsa_spinner');
        var success = $(this).parent().find('.success.approve');
        var alert = $(this).parent().find('.alert');

        spinner.show();
        success.hide();
        alert.hide();

        var site_url = 'https://mygsaccessories.com';
        //var site_url = 'https://www.gs-accessories.dev';

        var rest_url = site_url + '/wp-json/process_rma/user/' + post_id + '/' + email_address +'/' + rma_message + '/' + user_id;
        //var rest_url = 'https://www.gs-accessories.dev/wp-json/process_emails/user/' + post_id + '/' + email_address + '/' + tracking_number;

        $.ajax({
            type: 'GET',
            url: rest_url,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data, textStatus, XMLHttpRequest) {
                //console.log('worked?', data);
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

    $('#send-rma-email-reject').click(function() {

        var email_address = $('input[name="gsa-email-address-user"]').val();
        var rma_message = $('textarea[name="rma-message"').val();
        if ( ! rma_message ) {
            rma_message = 'BLANK';
        }
        var post_id = $('input[name="gsa-hidden-post-id"]').val();
        var user_id = $('input[name="gsa-user-id"]').val();
        
        var spinner = $(this).parent().find('.gsa_spinner');
        var success = $(this).parent().find('.success.reject');
        var alert = $(this).parent().find('.alert');

        spinner.show();
        success.hide();
        alert.hide();

        var site_url = 'https://mygsaccessories.com';
        //var site_url = 'https://www.gs-accessories.dev';

        var rest_url = site_url + '/wp-json/process_rma_reject/user/' + post_id + '/' + email_address +'/' + rma_message + '/' + user_id;
        //var rest_url = 'https://www.gs-accessories.dev/wp-json/process_emails/user/' + post_id + '/' + email_address + '/' + tracking_number;

        $.ajax({
            type: 'GET',
            url: rest_url,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data, textStatus, XMLHttpRequest) {
                //console.log('worked?', data);
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

    $('#rma-original-email').click(function() {

        var email_address = $('input[name="gsa-email-address-user"]').val();
        //var rma_message = $('textarea[name="rma-message"').val();
        var post_id = $('input[name="gsa-hidden-post-id"]').val();
        var user_id = $('input[name="gsa-user-id"]').val();

        var spinner = $(this).parent().find('.gsa_spinner');
        var success = $(this).parent().find('.success.email-resend');
        var alert = $(this).parent().find('.alert');

        spinner.show();
        success.hide();
        alert.hide();

        var site_url = 'https://mygsaccessories.com';
        //var site_url = 'https://www.gs-accessories.dev';

        var rest_url = site_url + '/wp-json/rma_resend_email/user/' + post_id + '/' + email_address + '/' + user_id;
        //var rest_url = 'https://www.gs-accessories.dev/wp-json/process_emails/user/' + post_id + '/' + email_address + '/' + tracking_number;

        $.ajax({
            type: 'GET',
            url: rest_url,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data, textStatus, XMLHttpRequest) {
                //console.log('worked?', data);
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

    $('#rma-custom-message').click(function() {

        console.log('sending custom message');
        var email_address = $('input[name="gsa-email-address-user"]').val();
        var rma_message = $('textarea[name="rma-message"').val();
        if ( ! rma_message ) {
            // error out - this is required!
            rma_message = 'BLANK';
        }
        var post_id = $('input[name="gsa-hidden-post-id"]').val();
        var user_id = $('input[name="gsa-user-id"]').val();
        
        var spinner = $(this).parent().find('.gsa_spinner');
        var success = $(this).parent().find('.success.custom-message');
        var alert = $(this).parent().find('.alert');

        spinner.show();
        success.hide();
        alert.hide();

        //var site_url = 'https://mygsaccessories.com';
        var site_url = 'https://www.gs-accessories.dev';

        var rest_url = site_url + '/wp-json/process_rma_custom_message/user/' + post_id + '/' + email_address +'/' + rma_message + '/' + user_id;

        $.ajax({
            type: 'GET',
            url: rest_url,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data, textStatus, XMLHttpRequest) {
                //console.log('worked?', data);
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



    // Activate Datepicker
    $('#datepicker_start, #datepicker_end').datepicker();



});