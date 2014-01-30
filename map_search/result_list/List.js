KFA.ResultList.List = Backbone.View.extend({

    rpp: 20,

    currentPage: 1,

    initialize: function ($options) {
        this.$results = this.$el.find(".result-list");
        this.contextMonitor = $options.contextMonitor;
        this.listenTo(this.model, "change", this._updateList);
        this.listenTo(this.contextMonitor, "change", this._updateList);
    },

    _refreshList: function ($data) {
        // add to collection
        var items = new KFA.ResultList.Collection();
        items.add($data.results);
        // iterate through and render
        var self = this;
        var views = items.map(function ($model) {
            var view = new KFA.ResultList.SummaryItem({
                model: $model
            });

            self.$results.append(view.render());
        });

        if ($data.nextPage) {
            this.$el.find(".next-page").addClass("active");
        } else {
            this.$el.find(".next-page").removeClass("active");
        }

        if (this.currentPage > 1) {
            this.$el.find(".prev-page").addClass("active");
        } else {
            this.$el.find(".prev-page").removeClass("active");
        }
    },

    _updateList: function () {
        // clear list
        this.$results.empty();
        // if the model is now empty, do nothing
        //this.currentPage = 1;
        if (_.isEmpty(this.model.toJSON())) {
            return;
        }

        var criteria = this.model.toJSON();
        if (!this.contextMonitor.get('bbox')) {
            return;
        }
        criteria.context_bbox = this.contextMonitor.get('bbox');

        criteria.page = this.currentPage;
        criteria.rpp = this.rpp;

        $.get("./map_search/search/result_list.php", criteria, 
            // This construct is necessary to ensure that the context of
            // _refreshList is properly bound to this model
            function ($context) {
                return function ($data) {
                    $context._refreshList($data);
                };
            }(this), "json");
    },

    _prevPage: function ($e) {
        if (this.currentPage > 1) {
            this.currentPage -= 1;
            this._updateList();
        }
        $e.preventDefault();
    },

    _nextPage: function ($e) {
        this.currentPage += 1;
        this._updateList();
        $e.preventDefault();
    },

    events: {
        "click .prev-page.active": "_prevPage",
        "click .next-page.active": "_nextPage"
    }

});
