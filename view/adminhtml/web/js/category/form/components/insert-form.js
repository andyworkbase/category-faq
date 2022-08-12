/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */

define([
    'Magento_Ui/js/form/components/insert-form'
], function (Insert) {
    'use strict';

    return Insert.extend({
        defaults: {
            listens: {
                responseData: 'onResponse'
            },
            modules: {
                faqListing: '${ $.faqListingProvider }',
                faqModal: '${ $.faqModalProvider }'
            }
        },

        /**
         * Close modal, reload category faq listing and save category faq
         *
         * @param {Object} responseData
         */
        onResponse: function (responseData) {
            var self = this;
            if (!responseData.error) {
                // set min timeout to allow the user to view the success message and listing refresh
                setTimeout(function () {
                    self.faqModal().closeModal();
                    setTimeout(function () {
                        self.faqListing().reload({
                            refresh: true
                        });
                    }, 1000);
                }, 1000);

            }
        },
    });
});
