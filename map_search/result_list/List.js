KFA.ResultList.List = Backbone.View.extend({

    rpp: 20,

    currentPage: 0,

    initialize: function () {
        this.$results = this.$el.find(".search-results");
        this.listenTo(this.model, "change", this._updateList);
    },

    _refreshList: function ($data) {
        // add to collection
        var items = new KFA.ResultList.Collection($data.results);
        // iterate through and render
        var views = items.map(function ($model) {
            var view = new KFA.ResultList.SummaryItem({
                model: $model
            });

            this.$results.append(view.render());
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
        criteria.page = this.currentPage;
        criteria.rpp = this.rpp;

        $.get("search/item_list.php", criteria, this._refreshList, "json");
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
        "click.prev-page": "_prevPage",
        "click.next-page": "_nextPage"
    }

});