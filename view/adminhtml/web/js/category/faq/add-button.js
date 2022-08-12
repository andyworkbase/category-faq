/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */

define([
    'Magento_Ui/js/form/components/button',
    'underscore'
], function (Button, _) {
    'use strict';

    return Button.extend({
        defaults: {
            categoryId: null,
            storeId: null
        },

        /**
         * Apply action on target component,
         * but previously create this component from template if it is not existed
         *
         * @param {Object} action - action configuration
         */
        applyAction: function (action) {
            if (action.params && action.params[0]) {
                action.params[0]['category_id'] = this.categoryId;
                action.params[0]['store_id'] = this.storeId;
            } else {
                action.params = [{
                    'category_id': this.categoryId,
                    'store_id': this.storeId
                }];
            }

            this._super();
        }
    });
});
