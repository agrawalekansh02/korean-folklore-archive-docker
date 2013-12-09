KFA.ResultList.Result = Backbone.Model.extend({
    defaults: function () {
        return {
            projectTitle: '',
            date: null,
            city: '',
            description: ''
        };
    }
});

