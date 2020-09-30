/**
 * System configuration usability and workflow improvements.
 * Some parts require custom admin CSS styling.
 *
 * - Works for DE/EN translated admin views.
 * - This script is performance optimized as much as possible.
 *   Activate only the settings your shop needs.
 *
 * @version   Magento 1.5.1.0
 * @since     Magento 1.4.1.1 / 2010-08-06
 * @category  design
 */
document.observe('dom:loaded', function() {
    'use strict';

    var n, selectFieldRows, selectFieldRowCount;


    // --------------------------------------------------------------------------------------------------- Configuration

    /**
     * System configuration table rows that contain multi-select fields.
     * Pseudo selector for example:
     *
     *   tr#row_* td.value select[multiple] ...
     */
    selectFieldRows = [
        // --- system_config/edit/section/general/
        'row_general_country_allow',
        'row_general_country_optional_zip_countries',

        // --- system_config/edit/section/carriers/
        'row_carriers_flatrate_specificcountry',
        'row_carriers_tablerate_specificcountry',
        'row_carriers_freeshipping_specificcountry',
        //'row_carriers_ups_allowed_methods',
        //'row_carriers_ups_specificcountry',
        //'row_carriers_usps_allowed_methods',
        //'row_carriers_usps_specificcountry',
        //'row_carriers_fedex_allowed_methods',
        //'row_carriers_fedex_specificcountry',
        //'row_carriers_dhl_specificcountry',

        // --- system_config/edit/section/payment/
        //'row_payment_ccsave_specificcountry',
        'row_payment_bankpayment_specificcountry',
        //'row_payment_cashticket_specificcountry',
        //'row_payment_checkmo_specificcountry',
        'row_payment_free_specificcountry',
        //'row_payment_authorizenet_directpost_specificcountry',
        //'row_payment_authorizenet_specificcountry',
        'row_payment_s_creditcard_form_specificcountry',
        //'row_payment_s_directdebit_form_specificcountry',
        'row_payment_s_giropay_form_specificcountry',

        // --- system_config/edit/section/google/
        //'row_google_checkout_shipping_flatrate_specificcountry_1',
        //'row_google_checkout_shipping_flatrate_specificcountry_2',
        //'row_google_checkout_shipping_flatrate_specificcountry_3',

        // --- system_config/edit/section/enhancedgrid/
        'row_enhancedgrid_columns_showcolumns',

        // --- system_config/edit/section/system/
        'row_system_currency_installed'
    ];


    // ------------------------------------------------------------------------------------------------------- Functions
    // ------------------------------------------------------------------------------ Select options

    /**
     * Resize select fields to always show all options at once.
     *
     * Scope : (all)
     * URL   : /system_config/edit/section/...
     */
    function resizeSelectField (id) {
        var i, el, option, optionCount,
            scopeLabel, select;

        if (!document.getElementById(id)) {
            return;
        }


        option      = $$('#' + id + ' select[multiple] option');
        optionCount = option.length;

        if (!optionCount) {
            return;
        }


        scopeLabel = $$('#' + id + ' td.scope-label')[0];
        select     = $$('#' + id + ' select[multiple]')[0];

        select.setAttribute('size', optionCount);
        scopeLabel.innerHTML += '<br />';


        for (i = 0; i < optionCount; i ++) {
            el = option[i];

            if (el.selected) {
                scopeLabel.innerHTML += el.innerHTML + '<br />';
            }
        }
    }


    // ------------------------------------------------------------------ Highlight inactive modules

    /**
     * Highlight inactive module options.
     * Use this class in your custom CSS styling.
     *
     * Scope : (all)
     * URL   : /system_config/edit/section/advanced/
     */
    function highlightInactiveModules () {
        var i, el, select, selectCount;

        if (!document.getElementById('advanced_modules_disable_output')) {
            return;
        }


        select      = $$('fieldset#advanced_modules_disable_output option[selected="selected"]');
        selectCount = select.length;

        if (!selectCount) {
            return;
        }


        for (i = 0; i < selectCount; i ++) {
            el = select[i];

            if (   el.innerHTML !== 'Enable'
                && el.innerHTML !== 'Aktivieren') {
                el.parentNode.addClassName('module-inactive');
            }
        }
    }


    // ------------------------------------------------------------------------------------------------------------- Run

    selectFieldRowCount = selectFieldRows.length;

    for (n = 0; n < selectFieldRowCount; n++) {
        resizeSelectField(selectFieldRows[n]);
    }

    highlightInactiveModules();

});

