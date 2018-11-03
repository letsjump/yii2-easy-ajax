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


var yiiEasyAjaxResponse = function (data) {

    if (data && typeof data !== "undefined") {

        /** Modal management */
        if (data.yea_modal && data.yea_modal !== "undefined" && data.yea_modal !== "close") {
            modal.resetModal();
            jQuery(".modal-backdrop").remove();
            if (typeof data.yea_modal.title !== "undefined") {
                modal.find(".modal-header h4").html(data.yea_modal.title);
            }
            if (typeof data.yea_modal.body !== "undefined") {
                modal.find(".modal-body").html(data.yea_modal.body);
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
        } else if ("close" === data.yea_modal) {
            modal.modal("hide");
        }

        //Handle the phantomContent empty and fill
        if (data.phantomContent && data.phantomContent !== "undefined" && data.phantomContent !== "close") {
            var phantomContent = modal.find(".phantom-body");
            phantomContent.empty();
            phantomContent.append(data.phantomContent);
            jQuery(".phantom-content .close").trigger("click");
        } else if ("close" === data.phantomContent) {
            jQuery(".phantom-content .close").trigger("click");
        }

        // // adding notifications growl style to page
        if (data.yea_notify && typeof data.yea_notify !== "undefined") {
            console.log(data.yea_notify);
            jQuery.notify(data.yea_notify.options, data.yea_notify.settings);
        }

        if (typeof data.jsFunction !== "undefined") {
            eval(data.jsFunction);
        }

        // reload gridViews
        // refer to: http://stackoverflow.com/questions/31985286/how-to-reload-multiple-pjax
        if (data.yea_pjax_reload && typeof data.yea_pjax_reload !== "undefined") {
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
        }

        //Reload active modal tab and delete cache of specified tabs
        if (data.yea_tabs_reload && typeof data.yea_tabs_reload !== "undefined") {
            modal.find(".tabs-krajee").tabsX("flushCache", data.yea_tabs_reload);
            jQuery(modal).find("li.active a").trigger("click");
        }

        // multiple form validation
        if (data.yea_form_validation && typeof data.yea_form_validation !== "undefined") {
            jQuery.each(data.yea_form_validation, function (formId, formErrors) {
                jQuery.each(formErrors, function (key, val) {
                    jQuery(formId).yiiActiveForm("updateAttribute", key, [val]);
                });
            });
        }

        // replace content
        if (data.yea_replace && typeof data.yea_replace !== "undefined") {
            jQuery.each(data.yea_replace, function (tagId, tagContent) {
                jQuery(tagId).html(tagContent);
            });
        }

        // redirect javascript stle
        if (data.yea_js_redirect && typeof  data.yea_js_redirect !== "undefined") {
            window.location.href = data.yea_js_redirect;
        }

        // redirect ajax style
        if (data.yea_ajax_redirect && typeof  data.yea_ajax_redirect !== "undefined") {
            jQuery.get(data.yea_ajax_redirect);
        }

        // open a confirm window.
        if (data.yea_confirm && typeof  data.yea_confirm !== "undefined") {
            if (confirm(data.yea_confirm.message)) {
                jQuery.get(data.yea_confirm.url);
            }
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

jQuery(document).ready(function () {

    jQuery(document).on("click", ".open-modal, [data-ajax='1']", function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var attribute = jQuery(this)[0].hasAttribute("data-href") ? "data-href" : "href";
        jQuery.get(jQuery(this).attr(attribute), function (data) {
            yiiEasyAjaxResponse(data);
        });
    });

    $(document)
        .keypress(function (e) {
            if (e.which === 13 && ($("#" + yea-modalid).data("bs.modal") || {}).isShown && !$("textarea").is(":focus")) {
                e.preventDefault();
                e.stopImmediatePropagation();
                $("#modalform-submit").click();
            }
        })
        .on("click", "#modalform-submit", function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var form = $("#"+$(this).data("formid"));
            var data = form.serializeArray();
            data.push({name: "save", value: true});
            $.ajax({
                type:    form.attr("method"),
                url:     form.attr("action"),
                data:    data,
                success: function (data) {
                    yiiEasyAjaxResponse(data);
                    if (data.yea_success === true) {
                        $("[data-dismiss=modal]").trigger({type: "click"});
                    }
                }
            });
        });
});