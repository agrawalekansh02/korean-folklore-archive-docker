KFA.ResultList.List = Backbone.View.extend({

    rpp: 20,

    currentPage: 0,

    initialize: function ($options) {
        this.$results = this.$el.find(".result-list");
        this.contextList = $options.contextList;
        this.listenTo(this.model, "change", this._updateList);
        this.listenTo(this.contextList, "change", this._updateList);
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

        if (this.currentPage > 0) {
            this.$el.find(".prev-page").addClass("active");
        } else {
            this.$el.find(".prev-page").removeClass("active");
        }
    },

    _updateList: function () {
        // clear list
        this.$results.empty();
        // if the model is now empty, do nothing
        this.currentPage = 0;
        if (_.isEmpty(this.model.toJSON())) {
            return;
        }

        var criteria = this.model.toJSON();
        var contextIds = criteria.context_ids = contextIds = this.contextList.get("contexts");
        if (_.isEmpty(contextIds)) {
            // Do nothing if no context is selected
            // Note that the result list has already been emptied above
            return;
        } 
        criteria.page = this.currentPage;
        criteria.rpp = this.rpp;

        $.get("./map_search/search/sample_result_list.json", criteria, 
            // This construct is necessary to ensure that the context of
            // _refreshList is properly bound to this model
            function ($context) {
                return function ($data) {
                    $context._refreshList($data);
                };
            }(this), "json");
    },

    _prevPage: function ($e) {
        if (this.currentPage > 0) {
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