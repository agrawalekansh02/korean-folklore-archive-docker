$(function () {
KFA.InputForm.Form = Backbone.View.extend({
    template: $("#kfa-input-form-template").html(),

    _addMultiSelects: function ($selectors) {
        for (var i = 0; i < $selectors.length; i++) {
            this.$el.find($selectors[i])
                    .multiselect({
                        noneSelectedText: "Select..."
                }).multiselect("uncheckAll");
        }
    },

    _addDateSelectors: function ($selectors) {
        var minDate = CONSTANTS.minDate.split('-').map(function ($el) {
            return parseInt($el);
        });
        minDate = new Date(minDate[0], minDate[1] - 1, minDate[2]);
        this.$el.find('.context-date-from').datepicker({
            defaultDate: minDate
        });
        this.$el.find('.context-date-to').datepicker();
    },

    multiSelectFields: [
        // Collector
        '.collector-gender', '.collector-language', 
        // Consultant
        '.consultant-gender', '.consultant-language',
        // Context
        ".context-event-type", ".context-time-of-day", '.collection-weather', 
        '.collection-language', '.collection-others-present', ".collection-method",
        '.collection-place-type', 
        // Data
        '.media'
    ],

    dateFields: [
        '.context-date-from', '.context-date-to'
    ],

    render: function () {
        var html = Mustache.compile(this.template);
        this.$el.append(html);
        $(".collapsible").collapsible();
        this._addMultiSelects(this.multiSelectFields);
        this._addDateSelectors();
        return this;
    },

    getMultipleValuesFrom: function ($e) {
        var items = $($e).multiselect("getChecked");
        var returnable = [];

        for (var i = 0; i < items.length; i++) {
            var item = items[i];
            returnable.push($(item).val());
        }
        return returnable;
    },

    getValuesFromForm: function ($field) {
        var key = $field.replace('.', '').replace(/-/g, '_'),
            value = null
        ;

        if (this.multiSelectFields.indexOf($field) !== -1) {
            var valuesFromMultiSelect = this.getMultipleValuesFrom(this.$el.find($field));
            if (valuesFromMultiSelect.length === 0) value = null;
            else value = valuesFromMultiSelect;
        } else if (this.dateFields.indexOf($field) !== -1) {
            value = $.datepicker.formatDate('yy-mm-dd', this.$el.find($field).datepicker("getDate"));
        } else {
            var el = this.$el.find($field),
                tagName = el.prop('tagName'),
                type = el.attr('type')
            ;
            switch (tagName) {
                case 'INPUT':
                    switch (type) {
                        case 'text':
                            value = (el.val() !== "") ? el.val() : null;
                            break;
                        case 'checkbox':
                            value = (el.is(":checked"));
                            break;
                    }
                    break; // end checking if the item is an <input>
            }
        }
        return [key, value];
    },

    _updateModel: function ($e) {
        var newData = {},
            fields = [
                // collector
                '.collector-gender', '.collector-occupation', '.collector-age',
                '.collector-language',
                // Consulant
                '.consultant-gender', '.consultant-occupation', '.consultant-age',
                '.consultant-language',
                // Context
                '.context-name', '.context-event-type', '.context-time-of-day',
                '.context-date-from', '.context-date-to', '.collection-weather', '.collection-language',
                '.collection-place-type', '.collection-others-present',
                '.collection-method', '.collection-description',
                // Data
                '.project-title', '.media', '.description'
            ];
        
        for (var i = 0; i < fields.length; i++) {
            var items = this.getValuesFromForm(fields[i]),
                key = items[0],
                value = items[1];
            if (value !== null) {
                newData[key] = value;
            }
        }

        // Then update model
        this.model.clear();
        this.model.set(newData);

        $e.preventDefault();
    },

    events: {
        "submit.search" : "_updateModel"
    }
});

});