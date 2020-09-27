define([
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select',
    'Magento_Ui/js/modal/modal'
], function (_, uiRegistry, select, modal) {
    'use strict';

    return select.extend({


        /**
         * Invokes initialize method of parent class,
         * contains initialization logic
         */
        // initialize: function () {
        //
        //     this._super()
        //     this.onUpdate(this.getInitialValue())
        //     return this;
        // },

        /**
         * On value change handler.
         *
         * @param {String} value
         */
        onUpdate: function (value) {
            console.log('Selected Value: ' + value);

            let show_header = uiRegistry.get('index = show_header');
            let show_left = uiRegistry.get('index = show_left');
            let show_content = uiRegistry.get('index = show_content');
            let show_right = uiRegistry.get('index = show_right');
            let show_footer = uiRegistry.get('index = show_footer');
            let width_left = uiRegistry.get('index = width_left');
            let width_content = uiRegistry.get('index = width_content');
            let width_right = uiRegistry.get('index = width_right');
            let image = uiRegistry.get('index = image');
            let width_image = uiRegistry.get('index = width_image');
            if (value == 1) {
                show_header.show();
                show_left.show();
                show_right.show();
                show_content.show();
                show_footer.show();
                width_left.show();
                width_content.show();
                width_right.show();
            } else {
                show_header.hide();
                show_left.hide();
                show_right.hide();
                show_content.hide();
                show_footer.hide();
                width_left.hide();
                width_content.hide();
                width_right.hide();
            }
            if (value == 2) {
                image.show();
                width_image.show();
            } else {
                image.hide();
                width_image.hide();
            }

            return this._super();
        },
    });
});
