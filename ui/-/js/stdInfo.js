// head {
var __nodeId__ = "ss_components_cats_ui__stdInfo";
var __nodeNs__ = "ss_components_cats_ui";
// }

(function (__nodeNs__, __nodeId__) {
    $.widget(__nodeNs__ + "." + __nodeId__, $.ewma.node, {
        options: {},

        __create: function () {
            var w = this;
            var o = w.options;
            var $w = w.element;

            w.bindEvents();
        },

        bindEvents: function () {
            var w = this;
            var o = w.options;
            var $w = w.element;

            w.e('ss/container/update.' + o.panelName, function (data) {
                if (o.catId === data.id) {
                    if (isset(data.name)) {
                        $("> .name", $w).html(data.shortName);
                    }
                }
            });
        }
    });
})(__nodeNs__, __nodeId__);
