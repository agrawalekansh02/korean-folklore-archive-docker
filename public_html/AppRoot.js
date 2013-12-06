var KFA = {
    el: "body",
    InputForm: {},
    Map: {},
    ResultList: {},
    AppRoot: Backbone.View.extend({
        render: function () {
            var search = new KFA.InputForm.Query();
            // create search form
            var searchForm = new KFA.InputForm.Form({
                model: search
            });
            $("#search-wrapper").append(searchForm.render());

            var map = new KFA.Map.Map({
                el: "#map"
            });
            map.render();
            var resultLayer = new KFA.Map.ResultLayer({
                model: search
            });
            map.addLayer(resultLayer);

            var resultList = new KFA.ResultList.ResultList({
                model: search
            });
            $("#result-list-wrapper").append(resultList.render());
        }
    })
};


$(function () {
    var root = new KFA.AppRoot();
    root.render();
});