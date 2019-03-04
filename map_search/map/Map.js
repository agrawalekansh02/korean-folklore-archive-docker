KFA.Map.Map = Backbone.View.extend({

    initialize: function ($options) {
        this.contextMonitor = $options.contextMonitor;
    },

    render: function () {
        var osm = new OpenLayers.Layer.OSM("Open StreetMap");

        this._map = new OpenLayers.Map(this.el, {
            layers: [osm]
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
                var cluster = new OpenLayers.Geometry.Collection();
                cluster.addComponents($f.cluster.map(function ($feature) {
                        return $feature.geometry;
                    })
                );

                // Necessary pre-emptive step because bounds aren't always
                // calculated automatically
                cluster.calculateBounds();
                var bbox = cluster.getBounds();

                // Compensate for any clusters that have only a single feature
                bbox.left -= 10;
                bbox.bottom -= 10;
                bbox.right += 10;
                bbox.top += 10;

                bbox.transform('EPSG:900913', 'EPSG:4326');
                self.contextMonitor.set('bbox', 
                    bbox.left + ',' + bbox.bottom + ',' + bbox.right + ',' + bbox.top
                );
            },
            onUnselect: function ($f) {
                self.contextMonitor.set("bbox", '');
            }
        });
        this._map.addControl(this._selectControl);
        this._selectControl.activate();
    }
});

