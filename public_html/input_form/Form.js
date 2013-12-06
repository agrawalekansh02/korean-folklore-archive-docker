KFA.InputForm.Form = Backbone.View.extend({
    template: $("#kfa-input-form-template").html(),

    _addMultiSelects: function ($selectors) {
        for (var i = 0; i < $selectors.length; i++) {
            this.$el.find($selectors[i])
                    .multiselect().multiselect("uncheckAll");
        }
    },

    render: function () {
        this.$el.append(Mustache.compile(this.template));
        this._addMultiSelects([
            '.collector-gender', '.consultant-gender',
            ".context-event-type", ".context-time-of-day", ".collection-method",
            '.collection-place-type', '.media'
        ]);
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

    _updateModel: function ($e) {
        var get = function ($el) { this.$el.find($el).val(); },
            newData = {
                collector_gender: this.getMultipleResultsFrom(".collector-gender"), 
                collector_occupation: get(".collector-gender"),
                collector_age: get(".collector-age"),
                collector_languages_spoken: get(".collector-languages-spoken"),
                // consultant
                consultant_gender: this.getMultipleResultsFrom(".consultant-gender"),
                consultant_occupation: get(".consultant-occupation"),
                consultant_languages_spoken: get(".consultant-languages-spoken"),
                consultant_city: get(".consultant-city"),
                consultant_immigration_status: get(".consultant-immigration-status"),

                // context
                context_name: get(".context-name"),
                context_event_type: get(".context-event-type"),
                collection_time_of_day: get(".collection-time-of-day"),
                collection_date: get(".collection-date"),
                collection_weather: get(".collection-weather"),
                collection_language: get(".collection-language"),
                collection_place_type: this.getMultipleResultsFrom(".collection-place-type"),
                collection_others_present: get(".collection-others-present"), // note that NULL means it is not present
                collection_method : this.getMultipleValuesFrom(".collection-method"),
                collection_description: get(".collection-description"),

                // field data
                project_title: get(".project-title"),
                media: this.getMultipleValuesFrom(".media"),
                description: get(".description") 
            };
        var updateData = {};
        for (var i in newData) {
            var field = newData[i];
            if (field == null) {

            } else if (typeof field === 'string') {
                if (field !== '') updateData[i] = field;
            } else if (typeof field === 'array') {
                if (field !== []) updateData[i] = field;
            } else if (typeof field == 'object') {
                if (field != {}) updateData[i] = field;
            } else if (typeof field === 'Number') {
                updateData[i] = field;
            }
        }
        // Then update object
        $e.preventDefault();
    },

    events: {
        "submit.search" : "_updateModel"
    }
});

