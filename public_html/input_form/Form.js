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

    _updateModel: function ($e) {
        // 
        $e.preventDefault();
    },

    events: {
        "submit.search" : "_updateModel"
    }
});

