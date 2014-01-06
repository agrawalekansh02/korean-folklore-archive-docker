KFA.ResultList.Result = Backbone.Model.extend({
    defaults: function () {
        return {
            url: '',
            projectTitle: '',
            date: null,
            city: '',
            description: ''
        };
    }
});

