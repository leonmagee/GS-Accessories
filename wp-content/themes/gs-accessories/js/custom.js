jQuery(function ($) {

    console.log('custom.js is loading');

    $('#product_select_field').change(function() {
    	console.log($(this).val());

    	var selected_slug = $(this).val();
    	$('.color-select').hide();
    	$('.color-select.' + selected_slug).show();
    });

});