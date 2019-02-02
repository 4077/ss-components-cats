// head {
var __nodeId__ = "ss_components_cats_ui__grid";
var __nodeNs__ = "ss_components_cats_ui";
// }

(function (__nodeNs__, __nodeId__) {
    $.widget(__nodeNs__ + "." + __nodeId__, $.ewma.node, {
        options: {},

        __create: function () {
            var w = this;
            var o = w.options;
            var $w = w.element;

            w.bind();
            w.bindEvents();
        },

        bind: function () {
            var w = this;
            var o = w.options;
            var $w = w.element;

            $(".load_more_button", $w).click(function () {
                w.r('loadTiles', {
                    offset: o.offset
                });
            });
        },

        loadDataUpdate: function (data) {
            var w = this;
            var o = w.options;
            var $w = w.element;

            o.offset = data.offset;

            var $loadButton = $(".load_more_button", $w);

            $loadButton.find(".limit").html(data.limit);
            $loadButton.find(".less .value").html(data.less);

            if (data.less <= data.limit) {
                $loadButton.find(".less").hide();
            }

            if (data.less <= 0) {
                $loadButton.hide();
            }
        },

        renderName: function (data) {
            $("> .name", this.element).html(data.name);
        },

        renderDescription: function (data) {
            $("> .description", this.element).html(data.description);
        },

        bindEvents: function () {
            var w = this;
            var o = w.options;
            var $w = w.element;

            w.e('ss/page/update_pages.' + o.catId, function (data) {
                if (o.parentCatId === data.id) {
                    w.r('reload');
                }
            });

            w.e('ss/container/update.' + o.catId, function (data) {
                if (isset(data.name)) {
                    $("> .name", $w).html(data.name);
                }

                if (isset(data.description)) {
                    $("> .description", $w).html(data.description);
                }
            });

            w.e('ss/page/update.' + o.catId, function (data) {
                var $tile = $(".tile[product_id='" + data.id + "']", $w);

                if ($tile.length) {
                    if (isset(data.published)) {
                        $tile.find(".not_published_mark").toggle(!data.published);
                    }

                    if (isset(data.name)) {
                        $(".ss_components_products_ui__tile[instance='" + o.productId + "'] .name_container > .name", $w).html(data.name);
                    }

                    if (isset(data.images)) {
                        w.mr('reload');
                    }
                }
            });
        }
    });
})(__nodeNs__, __nodeId__);
