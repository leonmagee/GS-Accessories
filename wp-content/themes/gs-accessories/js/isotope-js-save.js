jQuery(function ($) {

    // init Isotope
    /**
     * Initialize Isotope
     */
    var $container = $('.isotope').isotope({
        //itemSelector: '.skyrises-snippet'
        itemSelector: '.isotope-item'
    });

    /**
     * Demo Filters...
     */
    //var filterFns = {
    //    // show if number is greater than 50
    //    numberGreaterThan50: function () {
    //        var number = $(this).find('.number').text();
    //        return parseInt(number, 10) > 50;
    //    },
    //
    //    price: function() {
    //        // _this_ is the item element. Get text of element's .number
    //        var number = $(this).find('.number').text();
    //        // return true to show, false to hide
    //        return parseInt( number, 10 ) > 50;
    //    }
    //};


    /**
     *  Trigger filter on click - just effects the reset button
     *  for now. Global not required for reset
     */
    //$('#filters').on('click', '.isotope-link', function () {
    //    var filterValue = $(this).attr('data-filter');
    //    $container.isotope({filter: filterValue});
    //    /**
    //     *  I might need a conditional here?
    //     */
    //    $('.isotope-select-element option.for-sale').show();
    //    $('.isotope-select-element option.rentals').show();
    //});    //$('#filters').on('click', '.isotope-link', function () {
    //    var filterValue = $(this).attr('data-filter');
    //    $container.isotope({filter: filterValue});
    //    /**
    //     *  I might need a conditional here?
    //     */
    //    $('.isotope-select-element option.for-sale').show();
    //    $('.isotope-select-element option.rentals').show();
    //});


    /**
     * Global strings for filter values
     */
    var filter_global = '';
    var filter_city = '';
    var filter_beds = '';
    var filter_baths = '';
    var filter_sqft = '';
    var filter_status = '';
    var filter_price_min_value = '';
    var filter_price_max_value = '';

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
     * Verify range of price from Price Min and Price Max
     * @returns {boolean}
     */
    function filterPrice() {

        /**
         * Get classes assigned to each element
         * this variable will be passed to filterGlobalVars()
         * @type {*|jQuery}
         */
        var classes = $(this).attr('class');

        /**
         *  Get price class - prefixed by 'price-'
         */
        var class_only = classes.match(/price-[^\s]+/);

        /**
         * Get price value only - convert to number
         * @type {Number}
         */
        var price = parseInt(class_only[0].replace('price-', ''));

        /**
         *  Run comparison of price - will only return true if all conditions are met
         *  Price Min: filter_price_min_value
         *  Price Max: filter_price_max_value
         */
        if (( filter_price_min_value !== '' ) && ( filter_price_max_value !== '' )) {

            if (( ( price > filter_price_min_value ) || ( price == filter_price_min_value ) ) &&
                ( ( price < filter_price_max_value ) || ( price == filter_price_max_value ) )) {

                if (filterGlobalVars(classes)) {

                    return true;

                } else {

                    return false;
                }
            }

        } else if (filter_price_min_value !== '') {

            if (( price > filter_price_min_value ) || ( price == filter_price_min_value )) {

                if (filterGlobalVars(classes)) {

                    return true;

                } else {

                    return false;
                }
            }

        } else if (filter_price_max_value !== '') {

            if (( price < filter_price_max_value ) || ( price == filter_price_max_value )) {

                if (filterGlobalVars(classes)) {

                    return true;

                } else {

                    return false;
                }
            }
        }
    }

    $('.isotope-select-element#city').change(function () {

        $('.skyrises-alert').hide();
        var filterValue = $('option:selected', this).attr('data-filter');
        filter_global = filter_global.replace(filter_city, '');

        if (filterValue !== '*') {
            filter_global = filter_global + filterValue;
            filter_city = filterValue;
        }

        if (( filter_price_min_value !== '') || ( filter_price_max_value !== '' )) {

            $container.isotope({filter: filterPrice});

        } else {

            $container.isotope({filter: filter_global});
        }
    });

    $('.isotope-select-element#beds').change(function () {

        $('.skyrises-alert').hide();
        var filterValue = $('option:selected', this).attr('data-filter');
        filter_global = filter_global.replace(filter_beds, '');

        if (filterValue !== '*') {
            filter_global = filter_global + filterValue;
            filter_beds = filterValue;
        }

        if (( filter_price_min_value !== '') || ( filter_price_max_value !== '' )) {

            $container.isotope({filter: filterPrice});

        } else {

            $container.isotope({filter: filter_global});
        }
    });

    $('.isotope-select-element#baths').change(function () {

        $('.skyrises-alert').hide();
        var filterValue = $('option:selected', this).attr('data-filter');
        filter_global = filter_global.replace(filter_baths, '');

        if (filterValue !== '*') {
            filter_global = filter_global + filterValue;
            filter_baths = filterValue;
        }

        if (( filter_price_min_value !== '') || ( filter_price_max_value !== '' )) {

            $container.isotope({filter: filterPrice});

        } else {

            console.log('so far');
            $container.isotope({filter: filter_global});
        }
    });

    $('.isotope-select-element#sqft').change(function () { // this layout is better than the others...

        $('.skyrises-alert').hide();
        var filterValue = $('option:selected', this).attr('data-filter');
        if (filter_sqft) {
            filter_global = filter_global.replace(filter_sqft, '');
        }
        if (filterValue !== '*') {
            filter_global = filter_global + filterValue;
            filter_sqft = filterValue;
        }

        if (( filter_price_min_value !== '') || ( filter_price_max_value !== '' )) {

            $container.isotope({filter: filterPrice});

        } else {

            $container.isotope({filter: filter_global});
        }
    });

    /**
     * Reset Everything
     */
    $('#isotope-reset, #warning-reset').click(function () {

        $('.skyrises-alert').hide();
        filter_price_min_value = '';
        filter_price_max_value = '';
        filter_global = '';
        $('.isotope-select-element').each(function () {

            $(this).val($('option:first', this).val());
        });
        $('.isotope-button-group a.button').removeClass('active');
        $('.isotope-button-group a.button').removeClass('not-active');
        $('.isotope-select-element option.for-sale').show();
        $('.isotope-select-element option.rentals').show();
        $container.isotope({filter: '*'});
    });

    $('.isotope-select-element#price-min').change(function () {

        $('.skyrises-alert').hide();
        var filterValue = $('option:selected', this).attr('data-filter');

        if (filterValue === '*') {

            filter_price_min_value = '';

            if (( filter_price_min_value !== '') || ( filter_price_max_value !== '' )) {

                $container.isotope({filter: filterPrice});

            } else {

                $container.isotope({filter: filter_global});
            }

        } else {

            filter_price_min_value = parseInt(filterValue.replace('.price-', ''));

            $container.isotope({filter: filterPrice});
        }

    });


    $('.isotope-select-element#price-max').change(function () {

        $('.skyrises-alert').hide();
        var filterValue = $('option:selected', this).attr('data-filter');

        if (filterValue === '*') {

            filter_price_max_value = '';

            if (( filter_price_min_value !== '') || ( filter_price_max_value !== '' )) {

                $container.isotope({filter: filterPrice});

            } else {

                $container.isotope({filter: filter_global});
            }

        } else {

            filter_price_max_value = parseInt(filterValue.replace('.price-', ''));

            $container.isotope({filter: filterPrice});
        }

    });


    /**
     *  Isotope For Sale / Rental toggle
     */

    $('.isotope-button-group a.button').click(function () {

        $('.skyrises-alert').hide();

        var filterValue = $(this).attr('data-filter');

        filter_global = filter_global.replace(filter_status, '');

        filter_global = filter_global + filterValue;

        filter_status = filterValue;

        if (( filter_price_min_value !== '') || ( filter_price_max_value !== '' )) {

            $container.isotope({filter: filterPrice});

        } else {

            $container.isotope({filter: filter_global});
        }

        $('.isotope-button-group a.button').removeClass('active');
        $('.isotope-button-group a.button').addClass('not-active');

        $(this).addClass('active').removeClass('not-active');
    });

    /**
     *  Change Dropdown values of price range on click
     */
    $('.isotope-button-group a.button.for-sale').click(function () {

        //$('.skyrises-alert').hide();
        $('.isotope-select-element option.rentals').hide();
        $('.isotope-select-element option.for-sale').show();
    });

    $('.isotope-button-group a.button.rentals').click(function () {

        $('.isotope-select-element option.for-sale').hide();
        $('.isotope-select-element option.rentals').show();
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
    setTimeout(function () {
        $('a#isotope-reset').click();
    }, 200);


});