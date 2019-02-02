// head {
var __nodeId__ = "ss_components_cats_cp__tile";
var __nodeNs__ = "ss_components_cats_cp";
// }

(function (__nodeNs__, __nodeId__) {
    $.widget(__nodeNs__ + "." + __nodeId__, $.ewma.node, {
        options: {},

        __create: function () {
            var widget = this;

            widget.bind();
        },

        bind: function () {
            var widget = this;
            var $widget = widget.element;

            $("input.cartbutton_label", $widget).rebind("blur cut paste keyup", function (e) {
                if (e.which != 9) {
                    var value = $(this).val();

                    request(widget.options.paths.updateCartbuttonLabel, {
                        value: value,
                        pivot: widget.options.pivot
                    });

                    $(".preview .add_to_cart_button").html(value);
                }
            });

            $("input.image_dimension[field]", $widget).rebind("blur cut paste", function (e) {
                if (e.which != 9) {
                    updateImageDimension($(this));
                }
            });

            $("input.image_dimension[field]", $widget).rebind("keyup", function (e) {
                if (e.which == 13) {
                    var field = $(this).attr("field");
                    var value = $(this).val();

                    request(widget.options.paths.updateImageDimension, {
                        field: field,
                        value: value,
                        pivot: widget.options.pivot
                    });

                    $(this).addClass("updating");
                }
            });

            var updateTimeout;

            function updateImageDimension($field) {
                var field = $field.attr("field");
                var value = $field.val();

                clearTimeout(updateTimeout);
                updateTimeout = setTimeout(function () {
                    request(widget.options.paths.updateImageDimension, {
                        field: field,
                        value: value,
                        pivot: widget.options.pivot
                    });

                    $field.addClass("updating");
                }, 400);
            }
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
