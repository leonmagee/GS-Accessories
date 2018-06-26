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
        if ( quantity_number > max_quantity ) {
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
        var item_quantity_1 = $('input[name="item_quantity_1"]').val();
        var item_name_1 = $('input[name="item_name_1"]').val();
        var item_price_1 = $('input[name="item_price_1"]').val();
        var item_serial_1 = $('input[name="item_serial_1"]').val();
        var item_po_number_1 = $('input[name="item_po_number_1"]').val();
        var item_date_1 = $('input[name="item_date_1"]').val();
        var item_description_1 = $('input[name="item_description_1"]').val();

        //console.log('user id: ' + logged_in_user);
        // required inputs
        var conditional_inputs = (email_address && first_name && last_name && phone_number && address && city && state && zip);
        
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
            
            formdata.append("item_quantity_1", item_quantity_1);
            formdata.append("item_name_1", item_name_1);
            formdata.append("item_price_1", item_price_1);
            formdata.append("item_serial_1", item_serial_1);
            formdata.append("item_po_number_1", item_po_number_1);
            formdata.append("item_date_1", item_date_1);
            formdata.append("item_description_1", item_description_1);

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
            $('.uploads-spinner').hide();
            $('.mp-required-fields').show();
        }
    });


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

});




