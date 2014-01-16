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
        return this;
    },

    addLayer: function ($layer) {
        this._map.addLayer($layer._layer);
        var self = this;
        this._selectControl = new OpenLayers.Control.SelectFeature($layer._layer, {
            onSelect: function ($f) {
                var bbox = $f.geometry.bounds.transform('EPSG:900913', 'EPSG:4326');
                self.model.set('context_bbox', 
                    bbox.left + ',' + bbox.bottom + ',' + bbox.right + ',' + bbox.top
                );
            },
            onUnselect: function ($f) {
                self.model.set("context_bbox", '');
            }
        });
        this._map.addControl(this._selectControl);
        this._selectControl.activate();
    }
});

