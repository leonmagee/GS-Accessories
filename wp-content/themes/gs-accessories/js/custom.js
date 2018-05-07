jQuery(function ($) {


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
        var company = $(".registration-input-wrap input.company_name").val();
        var tin_ein_or_ssn = $(".registration-input-wrap input.tin_ein_or_ssn").val();
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
            var conditional_inputs = (username && password && email_address && first_name && last_name && phone_number && company && address && city && state && zip);
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

});