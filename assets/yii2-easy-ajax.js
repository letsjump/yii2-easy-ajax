/** @var modal Bootstrap modal */
var modal = jQuery('#' + yea_modalid);

/** @var originalModal Original modal needed in case of reset */
var originalModal = modal.clone();


(function ($) {
    $.fn.resetModal = function () {
        $(this).remove();
        var myClone = originalModal.clone();
        jQuery("body").append(myClone);
    };
})(jQuery);

window.yii.easyAjax = (function ($) {

    var pub = {
        // whether this module is currently active. If false, init() will not be called for this module
        // it will also not be called for all its child modules. If this property is undefined, it means true.
        isActive: true,
        init:     function () {
            // ... module initialization code goes here ...
        },

        request: function (type, url, data) {
            return $.ajax({
                url:      url,
                dataType: 'json',
                type:     type
            }).done(function (data) {
                pub.response(data);
            });
        },

        response: function (data) {

            if (data && typeof data !== "undefined") {
                // if($.isFunction(yea_confirm));
                // jQuery.each(data, function (myfunction, parameters) {
                //     if (typeof myfunction === "function") {
                //         [myfunction](parameters);
                //     } else {
                //         console.log('function ' + myfunction + ' not found');
                //     }
                // });

                /** Modal management */
                if (data.yea_modal && data.yea_modal !== "undefined" && data.yea_modal !== "close") {
                    yea_modal(data);
                } else if ("close" === data.yea_modal) {
                    modal.modal("hide");
                }

                //Handle the phantomContent empty and fill
                // if (data.phantomContent && data.phantomContent !== "undefined" && data.phantomContent !== "close") {
                //     var phantomContent = modal.find(".phantom-body");
                //     phantomContent.empty();
                //     phantomContent.append(data.phantomContent);
                //     jQuery(".phantom-content .close").trigger("click");
                // } else if ("close" === data.phantomContent) {
                //     jQuery(".phantom-content .close").trigger("click");
                // }

                // // adding notifications growl style to page
                if (data.yea_notify && typeof data.yea_notify !== "undefined") {
                    yea_notify(data);
                }

                // if (typeof data.jsFunction !== "undefined") {
                //     eval(data.jsFunction);
                // }

                // reload gridViews
                // refer to: http://stackoverflow.com/questions/31985286/how-to-reload-multiple-pjax
                if (data.yea_pjax_reload && typeof data.yea_pjax_reload !== "undefined") {
                    yea_pjax_reload(data);
                }

                //Reload active modal tab and delete cache of specified tabs
                if (data.yea_tabs_reload && typeof data.yea_tabs_reload !== "undefined") {
                    yea_tabs_reload(data);
                }

                // multiple form validation
                if (data.yea_form_validation && typeof data.yea_form_validation !== "undefined") {
                    yea_form_validation(data);
                }

                // replace content
                if (data.yea_replace && typeof data.yea_replace !== "undefined") {
                    yea_form_validation(data);
                }

                // redirect javascript stle
                if (data.yea_js_redirect && typeof  data.yea_js_redirect !== "undefined") {
                    yea_js_redirect(data);
                }

                // redirect ajax style
                if (data.yea_ajax_redirect && typeof  data.yea_ajax_redirect !== "undefined") {
                    yea_ajax_redirect(data);
                }

                // open a confirm window.
                if (data.yea_confirm && typeof  data.yea_confirm !== "undefined") {
                    yea_confirm(data);
                }

                modal.on("shown.bs.modal", function () {
                    modal.find("[autofocus]").focus();
                    if (modal.find("[autofocus]").not(":focus")) {
                        setTimeout(function () {
                            modal.find("[autofocus]").focus();
                        }, 200);
                    }
                });


            }
        }

        // ... other public functions and properties go here ...
    };

    var yea_test = function () {
        console.log("test");
    }

    var yea_confirm = function (data) {
        if (confirm(data.yea_confirm.message)) {
            jQuery.get(data.yea_confirm.url);
        }
    };

    var yea_ajax_redirect = function (data) {
        jQuery.get(data.yea_ajax_redirect);
    };

    var yea_js_redirect = function (data) {
        window.location.href = data.yea_js_redirect;
    };

    var yea_content_replace = function (data) {
        jQuery.each(data.yea_replace, function (tagId, tagContent) {
            jQuery(tagId).html(tagContent);
        });
    };

    var yea_form_validation = function (data) {
        jQuery.each(data.yea_form_validation, function (formId, formErrors) {
            jQuery.each(formErrors, function (key, val) {
                jQuery(formId).yiiActiveForm("updateAttribute", key, [val]);
            });
        });
    };

    var yea_tabs_reload = function (data) {
        modal.find(".tabs-krajee").tabsX("flushCache", data.yea_tabs_reload);
        jQuery(modal).find("li.active a").trigger("click");
    };

    var yea_pjax_reload = function (data) {
        //Rebuild container_id  as object if necessary
        jQuery.each(data.yea_pjax_reload, function (index, container) {
            if (!jQuery.isPlainObject(container)) {
                data.yea_pjax_reload[index] = {};
                data.yea_pjax_reload[index].container = container;
            }
            //Add timeout if not exist in the container object
            if (!("timeout" in data.yea_pjax_reload[index])) {
                data.yea_pjax_reload[index].timeout = jQuery(data.yea_pjax_reload[index].container).attr("data-pjax-timeout");
            }
            //Removes elements to reload passed from the controller that not exist in page avoid javascript error in console
            //Happen when same controller is used in different page and so need to reload different elements in different pages
            if (!jQuery(container).length) {
                data.yea_pjax_reload.splice(index, 1);
            }
        });
        if (data.yea_pjax_reload.length) {
            //Reload containers when the previous end loading
            jQuery.each(data.yea_pjax_reload, function (index, container) {
                if (index + 1 < data.yea_pjax_reload.length) {
                    jQuery(container.container).one("pjax:end", function (xhr, options) {
                        jQuery.pjax.reload(data.yea_pjax_reload[index + 1]);
                    });
                }
            });
            jQuery.pjax.reload(data.yea_pjax_reload[0]);
        }
    };

    var yea_modal = function (data) {
        modal.resetModal();
        jQuery(".modal-backdrop").remove();
        if (typeof data.yea_modal.title !== "undefined") {
            modal.find(".modal-header h4").html(data.yea_modal.title);
        }
        if (typeof data.yea_modal.content !== "undefined") {
            modal.find(".modal-body").html(data.yea_modal.content);
        }
        //if exist phantomModal create all html required into the modal
        if (typeof data.yea_modal.options !== "undefined") {
            if (typeof data.yea_modal.options.phantomModal !== "undefined") {
                modal.find(".modal-content").addClass("phantom-modal");
                modal.find(".modal-body").append('<div class="phantom-content"><div class="phantom-header" /><div class="phantom-body" /></div>');
                // fix bug AdminLTE options finché non viene rilasciata la nuova versione
                // jQuery('<button type="button" class="close"' + o.phantomModal.phantomModalToggleSelector.slice(1, -1) + ' aria-hidden="true">×</button>').appendTo(modal.find('.modal-body .phantom-header'));
                jQuery('<button type="button" class="close"' + '[data-widget="phantom-content-toggle"]'.slice(1, -1) + ' aria-hidden="true">×</button>').appendTo(modal.find(".modal-body .phantom-header"));
                modal.find(".modal-body > div:first").addClass("phantom-brother");
            }
        }
        if (typeof data.yea_modal.footer !== "undefined") {
            modal.find(".modal-footer").html(data.yea_modal.footer);
        }

        if (typeof data.yea_modal.size !== "undefined") {
            modal.find(".modal-dialog")
                .addClass(data.yea_modal.size);
        }
        if (typeof data.yea_modal.addClass !== "undefined") {
            jQuery.each(data.yea_modal.addClass, function (key, val) {
                modal.addClass(val);
            });
        }

        modal.modal();
    };

    var yea_notify = function (data) {
        jQuery.notify(data.yea_notify.options, data.yea_notify.settings);
    };

    // ... private functions and properties go here ...

    return pub;

})(window.jQuery);

jQuery(document).ready(function () {

    jQuery(document).on("click", ".open-modal, [data-ajax='1']", function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var request_method = (jQuery(this)[0].hasAttribute("data-yea-method") && jQuery(this).attr("data-yea-method") === "post") ? "post" : "get"
        var attribute = jQuery(this)[0].hasAttribute("data-href") ? "data-href" : "href";
        if (jQuery(this)[0].hasAttribute("data-yea-confirm")) {
            if (confirm(jQuery(this).attr("data-yea-confirm"))) {
                yii.easyAjax.request(request_method, jQuery(this).attr(attribute))
            }
        } else {
            yii.easyAjax.request(request_method, jQuery(this).attr(attribute))
        }
    });

    $(document)
        .keypress(function (e) {
            if (e.which === 13 && ($("#" + yea - modalid).data("bs.modal") || {}).isShown && !$("textarea").is(":focus")) {
                e.preventDefault();
                e.stopImmediatePropagation();
                $("#modalform-submit").click();
            }
        })
        .on("click", ".modalform-submit, #modalform-submit", function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var forms = $(this).data("formid");
            jQuery.each(forms, function (index, name) {
                var form = $("#" + name);
                var data = form.serializeArray();
                data.push({name: "yea-save", value: true});
                $.ajax({
                    type:    form.attr("method"),
                    url:     form.attr("action"),
                    data:    data,
                    success: function (data) {
                        yii.easyAjax.response(data)
                        if (data.yea_success === true) {
                            $("[data-dismiss=modal]").trigger({type: "click"});
                        }
                    }
                });
            });
        });
});