/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */

define([
    'Magento_Ui/js/form/components/insert-listing',
    'underscore'
], function (Insert, _) {
    'use strict';

    return Insert.extend({

        /**
         * On action call
         *
         * @param {Object} data - category faq data and actions
         */
        onAction: function (data) {
            this[data.action + 'Action'].call(this, data.data);
        },

        /**
         * On mass action call
         *
         * @param {Object} data - category faq data
         */
        onMassAction: function (data) {
            this[data.action + 'Massaction'].call(this, data.data);
        }
    });
});
