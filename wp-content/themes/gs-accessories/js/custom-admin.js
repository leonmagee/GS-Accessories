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
        var post_id = $('input[name="gsa-hidden-post-id"]').val();
        
        var spinner = $(this).parent().find('.gsa_spinner');
        var success = $(this).parent().find('.success');
        var alert = $(this).parent().find('.alert');

        spinner.show();
        success.hide();
        alert.hide();

        var site_url = 'https://mygsaccessories.com';
        //var site_url = 'https://www.gs-accessories.dev';

        var rest_url = site_url + '/wp-json/process_emails/user/' + post_id + '/' + email_address;
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

    $('#send-email-user-tracking').click(function() {

        /**
        * @todo we need a NEW rest endpoint for this one... 
        */

        var email_address = $('input[name="gsa-email-address-user-tracking"]').val();
        var post_id = $('input[name="gsa-hidden-post-id"]').val();
        var tracking_number = $('input[name="gsa-tracking-number"]').val();
        var shipping_service = $('input[name="gsa-shipping-service"]').val();
        
        // if ( $('textarea[name="gsa-tracking-number"]').val() ) {
        //     //var tracking_number = encodeURI($('textarea[name="gsa-tracking-number"]').val());
        //     var tracking_number = $('textarea[name="gsa-tracking-number"]').val();
        //     console.log(tracking_number);
        //     // var single = 'sdfsf';
        //     // console.log(single);
        // } else {
        //     var tracking_number = 'xxx';
        // }
        
        //console.log(tracking_number + ' ' + email_address);
        var spinner = $(this).parent().find('.gsa_spinner');
        var success = $(this).parent().find('.success');
        var alert = $(this).parent().find('.alert');

        spinner.show();
        success.hide();
        alert.hide();

        var site_url = 'https://mygsaccessories.com';
        //var site_url = 'https://www.gs-accessories.dev';

        var rest_url = site_url + '/wp-json/process_emails/user/' + post_id + '/' + email_address + '/' + tracking_number + '/' + shipping_service;
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


    $('#send-rma-email').click(function() {

        var email_address = $('input[name="gsa-email-address-user"]').val();
        var post_id = $('input[name="gsa-hidden-post-id"]').val();
        
        var spinner = $(this).parent().find('.gsa_spinner');
        var success = $(this).parent().find('.success');
        var alert = $(this).parent().find('.alert');

        spinner.show();
        success.hide();
        alert.hide();

        var site_url = 'https://mygsaccessories.com';
        //var site_url = 'https://www.gs-accessories.dev';

        var rest_url = site_url + '/wp-json/process_rma/user/' + post_id + '/' + email_address;
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



    // Activate Datepicker
    $('#datepicker_start, #datepicker_end').datepicker();






});