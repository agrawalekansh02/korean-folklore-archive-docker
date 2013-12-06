KFA.InputForm.Form = Backbone.View.extend({
    template: _.template($("#kfa-input-form-template").html()),
    render: function () {
        this.$el.append(this.template());
        // set multiselects
        // clone form
        // add to page
        return this;
    },

    _updateModel: function () {
        // 
    },

    events: {
        "click.search" : "_updateModel"
    }
});

