jQuery(function ($) {

    console.log('isotope.js is loading!');

    // init Isotope
    /**
     * Initialize Isotope
     */
    var $container = $('.isotope').isotope({
        itemSelector: '.isotope-item'
    });

    /**
     * Global strings for filter values
     */
    var filter_global = '';
    var filter_status = '';

    /**
     * Function called in filterPrice function
     * this checks to verify that snippets have the classes in the
     * filter_global variable - if not it returns false - this will be
     * called for each item.
     * @param classes
     * @returns {boolean}
     */
    function filterGlobalVars(classes) {

        /**
         * Create an array of filter classes
         * @type {Array}
         */
        var filter_array = filter_global.split('.');

        /**
         * Remove first element of array (empty string)
         */
        filter_array.shift();

        /**
         * Create array of classes assigned to snippet
         * @type {*|Array}
         */
        var classes_array = classes.split(' ');

        /**
         * Loop through filter_array to verify that each class exist in
         * snippet classes - return false if not
         */
        for (var i = 0; i < filter_array.length; ++i) {

            if ($.inArray(filter_array[i], classes_array) === -1) {

                return false;
            }
        }
        /**
         * Return true if every class matches
         */
        return true;
    }

    

    /**
     * Reset Everything
     */
    $('#isotope-reset').click(function () {
    //$('#isotope-reset, #warning-reset').click(function () {

        $('.skyrises-alert').hide();
        //filter_price_min_value = '';
        //filter_price_max_value = '';
        filter_global = '';
        // $('.isotope-select-element').each(function () {

        //     $(this).val($('option:first', this).val());
        // });
        $('.isotope-button-group a.button').removeClass('active');
        $('.isotope-button-group a.button').removeClass('not-active');
        //$('.isotope-select-element option.for-sale').show();
        //$('.isotope-select-element option.rentals').show();
        $container.isotope({filter: '*'});
    });

    // $('.isotope-select-element#price-min').change(function () {

    //     $('.skyrises-alert').hide();
    //     var filterValue = $('option:selected', this).attr('data-filter');

    //     if (filterValue === '*') {

    //         filter_price_min_value = '';

    //         if (( filter_price_min_value !== '') || ( filter_price_max_value !== '' )) {

    //             $container.isotope({filter: filterPrice});

    //         } else {

    //             $container.isotope({filter: filter_global});
    //         }

    //     } else {

    //         filter_price_min_value = parseInt(filterValue.replace('.price-', ''));

    //         $container.isotope({filter: filterPrice});
    //     }

    // });


    // $('.isotope-select-element#price-max').change(function () {

    //     $('.skyrises-alert').hide();
    //     var filterValue = $('option:selected', this).attr('data-filter');

    //     if (filterValue === '*') {

    //         filter_price_max_value = '';

    //         if (( filter_price_min_value !== '') || ( filter_price_max_value !== '' )) {

    //             $container.isotope({filter: filterPrice});

    //         } else {

    //             $container.isotope({filter: filter_global});
    //         }

    //     } else {

    //         filter_price_max_value = parseInt(filterValue.replace('.price-', ''));

    //         $container.isotope({filter: filterPrice});
    //     }

    // });

    /**
    *  Toggle active class
    **/
    $('.isotope-button-group a.button').click(function () {

        $('.isotope-button-group a.button').removeClass('active');
        $('.isotope-button-group a.button').addClass('not-active');

        $(this).addClass('active').removeClass('not-active');
    });


    /**
     *  Isotope Categories Togle
     *  @todo using 'filter_global' here isn't really necessary unless
     *  I want to be able to select multiple choies at once. 
     */
    $('.isotope-button-group a.button.cat').click(function () {

        $('.skyrises-alert').hide();

        var filterValue = $(this).attr('data-filter');

        filter_global = filter_global.replace(filter_status, '');

        filter_global = filter_global + filterValue;

        filter_status = filterValue;

        //console.log('filter global', filter_global);

        $container.isotope({filter: filter_global});
    });

    /**
     * Display message when filter hits zero
     */
    function count_isotope_items() {

        var number_of_items = $container.data('isotope').filteredItems.length;

        if (number_of_items === 0) {
            $('.skyrises-alert').fadeIn('medium');
        }
    }

    count_isotope_items();

    function onLayout() {
        count_isotope_items();
    }

    $container.isotope('on', 'layoutComplete', onLayout);

    /**
     * Trigger Isotope Reset on page load to fix layout errors
     */
    // setTimeout(function () {
    //     $('a#isotope-reset').click();
    // }, 200);


});