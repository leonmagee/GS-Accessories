jQuery(function ($) {

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

        if (username && password && email_address && first_name && last_name) {

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


});