$(function () {
KFA.ResultList.SummaryItem = Backbone.View.extend({

    template: $("#kfa-summary-item-template").html(),

    render: function () {
        return Mustache.render(this.template, this.model.toJSON());
    }

});
});