KFA.InputForm.Query = Backbone.Model.extend({
    // TODO: convert to function
    defaults: function () {
        return {
            // collector
            collector_gender: '',
            collector_occupation: '',
            collector_age: '',
            collector_languages_spoken: [],

            // consultant
            consultant_gender: '',
            consultant_occupation: '',
            consultant_languages_spoken: [],
            consultant_city: '',
            consultant_immigration_status: '',

            // context
            context_bbox: '',
            context_name: '',
            context_event_type: '',
            collection_time_of_day: '',
            context_date_from: '',
            context_date_to: '',
            collection_weather: '',
            collection_language: '',
            collection_place_type: '',
            collection_others_present: null, // note that NULL means it is not present
            collection_method : '',
            collection_description: '',

            // field data
            project_title: '',
            media: '',
            description: ''
        }
    }
});

