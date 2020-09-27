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
        //     this._super()
        //     this.onUpdate(this.getInitialValue())
        //     return this;
        // },

        /**
         * On value change handler.
         *
         * @param {number} value
         */
        onUpdate: function (value) {
            this.loadFields(value);
            return this._super();
        },
        loadFields: function (value) {
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
            if (value === 0) {
                show_header.hide();
                show_left.hide();
                show_right.hide();
                show_content.hide();
                show_footer.hide();
                width_left.hide();
                width_content.hide();
                width_right.hide();
                width_image.hide();
                image.hide();

            } else if (value === 1) {
                show_header.show();
                show_left.show();
                show_right.show();
                show_content.show();
                show_footer.show();
                width_left.show();
                width_content.show();
                width_right.show();
                width_image.hide();
                image.hide();
            } else if (value === 2) {
                width_image.show();
                image.show();
                show_header.hide();
                show_left.hide();
                show_right.hide();
                show_content.hide();
                show_footer.hide();
                width_left.hide();
                width_content.hide();
                width_right.hide();
            }
            return this;
        },
    });
});
