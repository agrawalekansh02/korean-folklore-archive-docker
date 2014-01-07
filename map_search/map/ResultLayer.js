KFA.Map.ResultLayer = Backbone.View.extend({

    initialize: function ($options) {
        var style = new OpenLayers.StyleMap({
            pointRadius : '5',
            fillColor : 'red',
            strokeColor: 'red',
            fillOpacity: 0.5
        });
        this._layer = new OpenLayers.Layer.Vector("Search Results", {
            strategies: [
                new OpenLayers.Strategy.Cluster()
            ],
            styleMap: style
        });
        this._format = new VC.SearchResultsFormat();
        this.listenTo(this.model, "change", this._searchResultsChanged);
        // TODO: add select control
    },

    _refreshLayer: function ($data) {
        this._layer.addFeatures(this._format.read($data.features));
    },

    _searchResultsChanged: function () {
        this._layer.removeAllFeatures();

        // Don't query if the model is now empty
        if (_.isEmpty(this.model.toJSON())) {
            return;
        }

        $.get("../map_search/search/search.php", this.model.toJSON(), 
            function ($context) {
                return function ($data) {
                    $context._refreshLayer($data)
                };
            }(this), "json");
    }
});

