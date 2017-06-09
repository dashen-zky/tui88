yii.customerValidation = (function ($) {
    var pub = {
        isEmpty: function (value) {
            return value === null || value === undefined || value == [] || value === '';
        },

        addMessage: function (messages, message, value) {
            messages.push(message.replace(/\{value\}/g, value));
        },

        orRequired: function (value, messages, options) {
            if (options.requiredValue === undefined) {
                var isString = typeof value == 'string' || value instanceof String;
                if (!pub.isEmpty(isString ? $.trim(value) : value)) {
                    return ;
                }
            }

            var object=this.constructor;

            if (value == options.requiredValue) {
                return ;
            }

            // 通过获取field的id,找到相对应的值，来进行验证
            for(var i = 0; i < options['or_attributes'].length; i++) {
                var attribute_id = options['id_prefix'] + '-' + options['or_attributes'][i];
                var attribute = $('#' + attribute_id);
                if (!pub.isEmpty(attribute.val())) {
                    return ;
                }
            }

            pub.addMessage(messages, options.message, value);
        }
    };

    return pub;
})(jQuery);