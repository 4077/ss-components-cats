// head {
var __nodeId__ = "ss_components_cats_cp__grid";
var __nodeNs__ = "ss_components_cats_cp";
// }

(function (__nodeNs__, __nodeId__) {
    $.widget(__nodeNs__ + "." + __nodeId__, $.ewma.node, {
        options: {},

        __create: function () {
            var w = this;
            var o = w.options;
            var $w = w.element;

            $("input.limit_value", $w).rebind("blur keyup", function (e) {
                if (e.type === 'blur' || e.which === 13) {
                    var value = $(this).val();

                    w.r('updateLimitValue', {value: value});
                }
            });

            $("input.limit_load_value", $w).rebind("blur keyup", function (e) {
                if (e.type === 'blur' || e.which === 13) {
                    var value = $(this).val();

                    w.r('updateLimitLoadValue', {value: value});
                }
            });
        },

        savedHighlight: function (field) {
            var widget = this;
            var $widget = widget.element;

            var $field = $("input[field='" + field + "']", $widget);

            $field.removeClass("updating").addClass("saved");

            setTimeout(function () {
                $field.removeClass("saved");
            }, 1000);
        }
    });
})(__nodeNs__, __nodeId__);
