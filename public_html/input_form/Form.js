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
        // set multiselects
//        this.$el.find(".context-event-type").multiselect().multiselect("uncheckAll");
//        this.$el.find(".context-time-of-day").multiselect();
        this._addMultiSelects([
            ".context-event-type", ".context-time-of-day", ".collection-method",
            '.collection-place-type', '.media'
        ]);
        // clone form
        // add to page
        return this;
    },

    _updateModel: function () {
        // 
    },

    events: {
        "submit.search" : "_updateModel"
    }
});

