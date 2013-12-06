KFA.Map.ResultLayer = Backbone.View.extend({
    initialize: function ($options) {
        // TODO: add stylemap
        this._layer = new OpenLayers.Layer.Vector("Search Results");
        this._format = new VC.SearchResultsFormat();
        this.listenTo(this.model, "change", this._searchResultsChanged);
    },

    _refreshLayer: function ($data) {
        this._layer.addFeatures(this._format.read($data));
    },

    _searchResultsChanged: function () {
        $.get("search/search.php", this.model.toJSON(), this._refreshLayer, "json");
    }
});

