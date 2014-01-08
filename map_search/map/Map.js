KFA.Map.Map = Backbone.View.extend({
    render: function () {
        var gsat = new OpenLayers.Layer.Google("Google Satellite", {
            type: google.maps.MapTypeId.SATELLITE
        });

        this._map = new OpenLayers.Map(this.el, {
            layers: [gsat]
        });

        var center = new OpenLayers.LonLat(-118, 34)
                .transform("EPSG:4326", "EPSG:900913");
        this._map.setCenter(center, 12);
        this._map.addControl(new OpenLayers.Control.LayerSwitcher());
        return this;
    },

    addLayer: function ($layer) {
        this._map.addLayer($layer._layer);
        var self = this;
        this._selectControl = new OpenLayers.Control.SelectFeature($layer._layer, {
            onSelect: function ($f) {
                self.model.set("contexts", $f.cluster.map(function ($f) {
                    return $f.data.id;
                }));
            },
            onUnselect: function ($f) {
                self.model.set("contexts", []);
            }
        });
        this._map.addControl(this._selectControl);
        this._selectControl.activate();
    }
});

