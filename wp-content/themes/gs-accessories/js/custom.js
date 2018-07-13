jQuery(function ($) {

    /**
    * Check quantity on form submit (or toggle?)
    */
    $('#accessory_single_order_form, form.order-form#add_to_order').submit(function(e) {

        form = this;
        $('input.quantity-input').removeClass('exceeds-quantity');
        var max_quantity = parseInt($('input.quantity-input[name="quantity"]').attr('quantity'));
        e.preventDefault();
        var quantity_number = parseInt($('input.quantity-input[name="quantity"]').val());
        if ( ( quantity_number > max_quantity ) || ( ! quantity_number ) ) {
            $('input.quantity-input[name="quantity"]').val('');
            $('input.quantity-input[name="quantity"]').addClass('exceeds-quantity');
        } else {
            //console.log('validation passed');
            form.submit();
        }
    });

    $('input.quantity-input').keypress(function() {
        $(this).removeClass('exceeds-quantity');
    });

    /**
    * Toggle Quantity Input on Single Accessories
    */
    $('.order-button-wrap select[name="color-select"]').change(function() {
        var selected_color = $(this).val();
        var color_class = selected_color.replace(' ', '-').toLowerCase();
        $('.order-button-wrap input.quantity-input').hide().attr('name', 'not-quantity');
        $('.order-button-wrap input.quantity-input.' + color_class).show().attr('name', 'quantity');
        console.log(color_class);
    });

    /**
    * Toggle Quantity Input on Add More Products
    */
    $('form.order-form#add_to_order select[name="product"]').change(function() {
        var selected_product = $(this).val();
        var selected_product_class = selected_product + '-1';
        //console.log(selected_product);


        // var color_class = selected_color.replace(' ', '-').toLowerCase();
        $('form.order-form#add_to_order input.quantity-input').hide().attr('name', 'not-quantity');
        $('form.order-form#add_to_order input.quantity-input.' + selected_product_class).show().attr('name', 'quantity');
        // console.log(color_class);
    });


    $('form.order-form#add_to_order .input-wrap.color-select select').change(function() {
        //var selected_color = $(this).val();
        var selected_product_class = $('option:selected', this).attr('item_id');

        //console.log(option + ' was selected!');

        $('form.order-form#add_to_order input.quantity-input').hide().attr('name', 'not-quantity');
        $('form.order-form#add_to_order input.quantity-input.' + selected_product_class).show().attr('name', 'quantity');
        // console.log(color_class);
    });

    //trigger form submit when paypal is clicked
    // @todo create a separate hidden field that you update first to tell the 
    // process submit function that its a paypal submission

    // $('#paypal_button_id').click(function() {
    //     console.log('working still?');
    //     $('#submit_cart_button').click();
    // });

    $('#paypal_checkout_button').click(function(e) {
        e.preventDefault();

        $('input[name="payment-type"]').val('PayPal');

        $('.paypal-wrap').addClass('paypal-visible');

        $('form#main_form_id').submit();

    });

    $('.features-section .shortcode-reviews-form a').removeAttr('href').attr('data-open', 'login-modal');


// $('form#paypal_form_id').submit(function(){
//     //alert('I do something before the actual submission');
//     $('#submit_cart_button').click();
//     //return false;
//     return true;
// });





    //console.log('custom.js is loading');

    $('.menu-toggle').click(function() {
        $('nav.main-navigation-custom').toggleClass('menu-hidden');
    });


    /**
    * Change Product Colors
    */
    $('#product_select_field').change(function() {
    	var selected_slug = $(this).val();
    	$('.color-select').hide();
    	$('.color-select.' + selected_slug).show();
    });

    /**
     * Process ajax register new user
     *
     * @todo modify this to check for and Agent Retailer
     */
     $("#register-new-user-submit").click(function (event) {

        event.preventDefault();

        $('.mp-update-success').hide();
        $('.mp-required-fields').hide();
        $('.uploads-spinner').css({'display': 'flex'});

        var username = $(".registration-input-wrap input.username").val();
        var password = $(".registration-input-wrap input.password").val();
        var email_address = $(".registration-input-wrap input.email_address").val();
        var first_name = $(".registration-input-wrap input.first_name").val();
        var last_name = $(".registration-input-wrap input.last_name").val();
        var phone_number = $(".registration-input-wrap input.phone_number").val();
        //var agency_name = $(".registration-input-wrap input.agency_name").val();
        if ( $(".registration-input-wrap input.company_name").val() ) {
            var company = $(".registration-input-wrap input.company_name").val();
        } else {
            var company = '';
        }
        if ($(".registration-input-wrap input.tin_ein_or_ssn").val()) {
            var tin_ein_or_ssn = $(".registration-input-wrap input.tin_ein_or_ssn").val();
        } else {
            var tin_ein_or_ssn = '';
        }
        var address = $(".registration-input-wrap input.address").val();
        var city = $(".registration-input-wrap input.city").val();
        var state = $(".registration-input-wrap input.state").val();
        var zip = $(".registration-input-wrap input.zip").val();

        /* @todo how to make one form optional? */

        //if ( tester.length ) { console.log('workz'); } else { console.log('nopez'); }

        var tin_ssn_field = $('input.tin_ein_or_ssn');

        if ( tin_ssn_field.length ) {

            var conditional_inputs = (username && password && email_address && first_name && last_name && phone_number && company && tin_ein_or_ssn && address && city && state && zip);
        } else {
            var conditional_inputs = (username && password && email_address && first_name && last_name && phone_number && address && city && state && zip);
        }

        
        if (conditional_inputs) {

            var formdata = new FormData();

            formdata.append("mp_register_user_click", 'click');

            formdata.append("username", username);
            formdata.append("password", password);
            formdata.append("email_address", email_address);
            formdata.append("first_name", first_name);
            formdata.append("last_name", last_name);
            formdata.append("phone_number", phone_number);
            //formdata.append("agency_name", agency_name);
            formdata.append("company", company);
            formdata.append("tin_ein_or_ssn", tin_ein_or_ssn);
            formdata.append("address", address);
            formdata.append("city", city);
            formdata.append("state", state);
            formdata.append("zip", zip);

            formdata.append("action", "lv_register_user");

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: formdata,
                contentType: false,
                processData: false,
                success: function (data, textStatus, XMLHttpRequest) {
                    //console.log( 'made it to success????');
                    $('.register-user-email-taken').hide();
                    $('.uploads-spinner').hide();
                    if (data === 'email_already_taken') {
                        $('.register-user-email-taken').show();
                    } else if (data === 'invalid_email_address')  {
                        $('.register-user-email-invalid').show();
                    } else {
                        $('.mp-update-success').show();
                    }
                },
                error: function (MLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        } else {
            $('.uploads-spinner').hide();
            $('.mp-required-fields').show();
        }
    });

    /**
    * Process ajax submit RMA form
    */
    $("#rma-form-submit").click(function (event) {

        event.preventDefault();

        $('.mp-update-success').hide();
        $('.mp-required-fields').hide();
        $('.uploads-spinner').css({'display': 'flex'});

        var email_address = $(".registration-input-wrap input.email_address").val();
        var first_name = $(".registration-input-wrap input.first_name").val();
        var last_name = $(".registration-input-wrap input.last_name").val();
        var phone_number = $(".registration-input-wrap input.phone_number").val();
        if ( $(".registration-input-wrap input.company_name").val() ) {
            var company = $(".registration-input-wrap input.company_name").val();
        } else {
            var company = '';
        }
        var address = $(".registration-input-wrap input.address").val();
        var city = $(".registration-input-wrap input.city").val();
        var state = $(".registration-input-wrap input.state").val();
        var zip = $(".registration-input-wrap input.zip").val();
        var logged_in_user = $('.logged_in_user_id').val(); // test this works with different users...
        
        // get product details

        var item_quantity = [];
        var item_name = [];
        var item_price = [];
        var item_serial = [];
        var item_po_number = [];
        var item_date = [];
        var item_description = [];

        var i;

        for ( i = 0; i < 5; i++) {
            var current = ( i + 1 );
            item_quantity[i] = $('input[name="item_quantity_' + current + '"]').val();
            item_name[i] = $('input[name="item_name_' + current + '"]').val();
            item_price[i] = $('input[name="item_price_' + current + '"]').val();
            item_serial[i] = $('input[name="item_serial_' + current + '"]').val();
            item_po_number[i] = $('input[name="item_po_number_' + current + '"]').val();
            item_date[i] = $('input[name="item_date_' + current + '"]').val();
            item_description[i] = $('textarea[name="item_description_' + current + '"]').val();
        }

        // required inputs
        // @todo change this to reflect just the inputs that will show up and are required...
        // these can be just hidden by css?
        //var conditional_inputs = (email_address && first_name && last_name && phone_number && address && city && state && zip);
        var conditional_inputs = (item_quantity[0] && item_name[0] && item_price[0] && item_serial[0] && item_po_number[0] && item_date[0] && item_description[0]);




        if (conditional_inputs) {

            var formdata = new FormData();

            formdata.append("lv_process_rma_click", 'click');

            formdata.append("email_address", email_address);
            formdata.append("first_name", first_name);
            formdata.append("last_name", last_name);
            formdata.append("phone_number", phone_number);
            formdata.append("company", company);
            formdata.append("address", address);
            formdata.append("city", city);
            formdata.append("state", state);
            formdata.append("zip", zip);
            formdata.append("user", logged_in_user);

            var i;

            for ( i = 0; i < 5; i++) {

                var current = ( i + 1 );
                formdata.append("item_quantity_" + current, item_quantity[i]);
                formdata.append("item_name_" + current, item_name[i]);
                formdata.append("item_price_" + current, item_price[i]);
                formdata.append("item_serial_" + current, item_serial[i]);
                formdata.append("item_po_number_" + current, item_po_number[i]);
                formdata.append("item_date_" + current, item_date[i]);
                formdata.append("item_description_" + current, item_description[i]);
            }

            formdata.append("action", "lv_process_rma");

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: formdata,
                contentType: false,
                processData: false,
                success: function (data, textStatus, XMLHttpRequest) {
                //success: function (response) {
                    //console.log( 'made it to success????');
                    console.log(data);
                    $('.register-user-email-taken').hide();
                    $('.uploads-spinner').hide();
                    $('body').scrollTop(0);
                    if (data === 'email_already_taken') {
                        $('.register-user-email-taken').show();
                    } else if (data === 'invalid_email_address')  {
                        $('.register-user-email-invalid').show();
                    } else {
                        $('.mp-update-success').show();
                    }
                    
                },
                error: function (MLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                }
            });
        } else {
            $('body').scrollTop(0);
            $('.uploads-spinner').hide();
            $('.mp-required-fields').show();

            if ( ! item_quantity[0]) {
                $('input[name="item_quantity_1"]').addClass('alert-warning');
            }
            if ( ! item_name[0]) {
                $('input[name="item_name_1"]').addClass('alert-warning');
            }
            if ( ! item_price[0]) {
                $('input[name="item_price_1"]').addClass('alert-warning');
            }
            if ( ! item_serial[0]) {
                $('input[name="item_serial_1"]').addClass('alert-warning');
            }
            if ( ! item_po_number[0]) {
                $('input[name="item_po_number_1"]').addClass('alert-warning');
            }
            if ( ! item_date[0]) {
                $('input[name="item_date_1"]').addClass('alert-warning');
            }
            if ( ! item_description[0]) {
                $('[name="item_description_1"]').addClass('alert-warning');
            }
        }
    });

$('.page-template-page-submit-rma input, .page-template-page-submit-rma textarea').focus(function() {
        //console.log('focus...');
        $(this).removeClass('alert-warning');
    });

$('.page-template-page-submit-rma .rma-date input').datepicker();

$('.page-template-page-order-history #datepicker_start, .page-template-page-order-history #datepicker_end').datepicker();


    /**
    * Close video modal and stop video
    */
    function stop_video() {

        $('.homepage-video-wrapper').addClass('hide-video');

        $('.homepage-video-wrapper iframe').each(function(index) {
            $(this).attr('src', $(this).attr('src'));
            return false;
        });
    }


    $('.homepage-video-wrapper .close-icon').click(function() {

        stop_video();
    });

    $(document).click(function(event) {

        if (!$(event.target).closest(".homepage-video-wrapper-inner").length) {

            stop_video();
        }
    });

    /**
    * Toggle date change form for Agent Admin page
    */
    $('.change-date-form a.toggle').click(function() {
        $('form.change-date-form-inner').toggleClass('active');
    });


/**
* send admin email from order page
*/
$('.gs-resend-order-email').click(function() {

    var email_address = $(this).parent().find('input[name="gsa-email-address-admin"]').val();
    var post_id = $(this).parent().find('input[name="gsa-hidden-post-id"]').val();
    var spinner = $(this).parent().find('.gsa_spinner');
    var success = $(this).parent().parent().parent().parent().parent().find('.callout.success');

    var alert = $(this).parent().parent().parent().parent().parent().find('.callout.alert');

    spinner.show();
    success.hide();
    alert.hide();

    //var site_url = 'https://mygsaccessories.com';
        var site_url = 'https://www.gs-accessories.dev';

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

/**
* send admin email from RMA page
*/
$('.gs-resend-rma-email').click(function() {

    console.log('clicky working');

    var email_address = $(this).parent().find('input[name="gsa-email-address-admin"]').val();
    var post_id = $(this).parent().find('input[name="gsa-hidden-post-id"]').val(); // @todo important this isn't working right...
    var spinner = $(this).parent().find('.gsa_spinner');
    var success = $(this).parent().parent().parent().parent().parent().find('.callout.success');

    var alert = $(this).parent().parent().parent().parent().parent().find('.callout.alert');

    spinner.show();
    success.hide();
    alert.hide();

        //var site_url = 'https://mygsaccessories.com';
        var site_url = 'https://www.gs-accessories.dev';

        var rest_url = site_url + '/wp-json/rma_resend_email/user/' + post_id + '/' + email_address;

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


});




