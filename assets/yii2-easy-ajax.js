/*
 *
 *  * @package   yii2-easy-ajax
 *  * @author    Gianpaolo Scrigna <letsjump@gmail.com>
 *  * @link https://github.com/letsjump/yii2-easy-ajax
 *  * @copyright Copyright &copy; Gianpaolo Scrigna, beintech.it, 2017-2020
 *  * @version   1.0.0
 *
 */

window.yii.easyAjax = (function ($) {

    "use strict";

    var options = yea_options;

    var modal = jQuery('#' + options.modal.id);

    var methods;

    var pub = {

        //modal: modal,
        /**
         * Function init
         *
         * @param extra_methods
         * Add any additional methods to the main script
         */
        init: function (extra_methods) {
            options.modal.snapshot = modal.clone();
            methods = Object.assign(default_methods, extra_methods);
        },

        /**
         * Main request
         *
         * @param type
         * @param url
         * @param data
         * @returns {*}
         */
        request: function (type, url, data) {
            return $.ajax({
                url:      url,
                dataType: 'json',
                type:     type,
                data: data
            }).done(function (data) {
                pub.response(data);
            });
        },

        /**
         * Main response
         * @param data
         */
        response: function (data) {
            if (data && typeof data !== "undefined") {
                jQuery.each(data, function (key, value) {
                    if (typeof value === "object") {
                        jQuery.each(value, function (myFunction, parameters) {
                            if (typeof methods[myFunction] === "function") {
                                methods[myFunction](parameters);
                            }
                        });
                    }
                });
            }
        },

        /**
         * resetModal
         *
         * Remove the main modal from the DOM and replace with the original copy (snapshot)
         */
        resetModal: function () {
            modal.remove();
            jQuery(".modal-backdrop").remove();
            jQuery("body").append(options.modal.snapshot.clone());
            modal = jQuery('#' + options.modal.id);
        }

    };

    /**
     * Default methods
     *
     * @type {{yea_redirect: yea_redirect, yea_tab: yea_tab, yea_confirm: yea_confirm, yea_form_validation: yea_form_validation, yea_content_replace: yea_content_replace, yea_modal: yea_modal, yea_pjax_reload: yea_pjax_reload, yea_modal_close: yea_modal_close, yea_notify: yea_notify}}
     */
    var default_methods = {

        /**
         * Confirm
         *
         * Explicitly makes a request after a javascript confirm popup
         *
         * @param data.url string
         * @param data.processResponse bool If true invoke another easyajax method with the response data
         */
        yea_confirm: function (data) {
            if (window.confirm(data.message)) {
                jQuery.get(data.url, function (response) {
                    if (typeof data.processResponse && data.processResponse === true) {
                        pub.response(response);
                    }
                });
            }
        },

        /**
         * Redirect
         *
         * Redirect to a page, a controller action or to another EasyAjax method
         *
         * @param data.url
         * @param data.ajax bool If false, do a javascript redirection
         * @param data.processResponse bool If true invoke another easyajax method with the response data
         */
        yea_redirect: function (data) {
            if (data.ajax === true) {
                jQuery.get(data.url, function (response) {
                    if (typeof data.processResponse && data.processResponse === true) {
                        pub.response(response);
                    }
                });
            } else {
                window.location.href = data.url;
            }
        },

        /**
         * Content Replace
         *
         * Set the HTML contents of each element in the set of matched elements.
         *
         * @param data.id string Id of the div to replace
         * @param data.tagContent string content to insert
         *
         * Accept an array of contents to be replaced:
         * {tag_id: tag_html_content, tag_id: tag_html_content, ...}
         *
         */
        yea_content_replace: function (data) {
            jQuery.each(data, function (tagId, tagContent) {
                jQuery(tagId).html(tagContent);
            });
        },

        /**
         * Form validation
         *
         * Display the form errors in the Yii2 way
         * Please refer to the official Yii2 documentation
         * @link https://www.yiiframework.com/doc/api/2.0/yii-widgets-activeform#validate()-detail
         *
         * @param data.formId string
         * @param data.formErrors array of errors grouped by field ID
         */
        yea_form_validation: function (data) {
            jQuery.each(data, function (formId, formErrors) {
                jQuery.each(formErrors, function (key, val) {
                    jQuery(formId).yiiActiveForm("updateAttribute", key, [val]);
                });
            });
        },

        /**
         * Pjax Reload
         *
         * Reload the content of one or more pjax containers based on their ID
         *
         * @param data array|string Id or array to reload
         */
        yea_pjax_reload: function (data) {
            //Rebuild container_id as object if necessary
            jQuery.each(data, function (index, container) {
                if (!jQuery.isPlainObject(container)) {
                    data[index] = {};
                    data[index].container = container;
                }
                //Add timeout if not exist in the container object
                if (!("timeout" in data[index])) {
                    data[index].timeout = jQuery(data[index].container).attr("data-pjax-timeout");
                }
                //Removes elements to reload passed from the controller that not exist in page avoid javascript error in console
                //Happen when same controller is used in different page and so need to reload different elements in different pages
                if (!jQuery(container).length) {
                    data.splice(index, 1);
                }
            });
            if (data.length) {
                //Reload containers when the previous end loading
                jQuery.each(data, function (index, container) {
                    if (index + 1 < data.length) {
                        jQuery(container.container).one("pjax:end", function (xhr, options) {
                            jQuery.pjax.reload(data.yea_pjax_reload[index + 1]);
                        });
                    }
                });
                jQuery.pjax.reload(data[0]);
            }
        },

        /**
         * Modal
         *
         * Configure, adds content, title, an optional footer and open the bootstrap modal
         *
         * @param data.title string
         * @param data.content string
         * @param data.footer string
         * @param data.size string
         * @param data.addClass string
         *
         * If easyAjax.options.autofocus is TRUE, set the cursor on the first input of its form
         */
        yea_modal: function (data) {

            pub.resetModal();

            if (typeof data.title !== "undefined") {
                modal.find(options.modal.title_id).html(data.title);
            }

            if (typeof data.content !== "undefined") {
                modal.find(options.modal.content_id).html(data.content);
            }

            if (typeof data.footer !== "undefined") {
                modal.find(options.modal.footer_id).html(data.footer);
            }

            if (typeof data.size !== "undefined") {
                modal.find(".modal-dialog")
                    .addClass(data.size);
            }

            if (typeof data.addClass !== "undefined") {
                jQuery.each(data.addClass, function (key, val) {
                    modal.addClass(val);
                });
            }

            modal.modal();

            if (options.modal.autofocus === true) {
                modal.find("[autofocus]").focus();
                if (modal.find("[autofocus]").not(":focus")) {
                    setTimeout(function () {
                        modal.find("[autofocus]").focus();
                    }, 200);
                }
            }

        },

        /**
         * Modal Close
         *
         * Close a modal
         */
        yea_modal_close: function () {
            $("[data-dismiss=modal]").trigger({type: "click"});
        },

        /**
         * Trigger for jQuery Bootstrap Notify
         *
         * @see http://bootstrap-notify.remabledesigns.com/ for its configuration
         *
         * @param data
         */
        yea_notify: function (data) {
            jQuery.notify(data.options, data.settings);
        },

        /**
         * Bootstrap Tabs
         *
         * @param data.tab string ID of the tab
         * @param data.content string content to update
         */
        yea_tab: function (data) {
            if (data.tab !== 'undefined') {
                var pane = '#' + data.tab;
                if (data.content !== 'undefined') {
                    jQuery(pane).html(data.content);
                }
                jQuery(pane).tab('show');
            }
        }
    }

    return pub;

})(window.jQuery);

jQuery(document).ready(function () {

    yii.easyAjax.init(yea_options.yea_extends);

    jQuery(document)
        .on("click", "[" + yea_options.trigger + "='1']", function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var request_method = (jQuery(this)[0].hasAttribute("data-yea-method")) ? jQuery(this).attr("data-yea-method") : "get";
            var attribute = jQuery(this)[0].hasAttribute("data-href") ? "data-href" : "href";
            if (jQuery(this)[0].hasAttribute("data-yea-confirm")) {
                if (window.confirm(jQuery(this).attr("data-yea-confirm"))) {
                    yii.easyAjax.request(request_method, jQuery(this).attr(attribute));
                }
            } else {
                yii.easyAjax.request(request_method, jQuery(this).attr(attribute));
            }
        })
        .keypress(function (e) {
            if (e.which === 13 && ($("#" + yea_modalid).data("bs.modal") || {}).isShown && !$("textarea").is(":focus")) {
                e.preventDefault();
                e.stopImmediatePropagation();
                $(".modalform-submit").click();
            }
        })
        .on("click", ".modalform-submit", function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var forms = $(this).data("formid");
            jQuery.each(forms, function (index, name) {
                var form = $("#" + name);
                var data = form.serializeArray();
                data.push({name: "yea-save", value: true});
                yii.easyAjax.request(form.attr('method'), form.attr("action"), data)
            });
        });
});