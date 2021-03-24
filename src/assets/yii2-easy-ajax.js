/*
 *
 *  * @package   yii2-easy-ajax
 *  * @author    Gianpaolo Scrigna <letsjump@gmail.com>
 *  * @link https://github.com/letsjump/yii2-easy-ajax
 *  * @copyright Copyright &copy; Gianpaolo Scrigna, beintech.it, 2017-2020
 *  * @version   1.0.1
 *
 */

window.yii.easyAjax = (function ($) {

    "use strict";

    var options = yea_options;

    var modal = jQuery('#' + options.modal.id);

    var methods;

    var pub = {

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
        request: function (element) {

            var req_params = requestor(element, options.modal.id);

            var request = $.ajax({
                url:      req_params.target,
                data:     req_params.data,
                type:     req_params.method,
                dataType: "json"
            });

            request.done(function (data) {
                pub.response(data);
            });

            request.fail(function (jqXHR, textStatus) {
                console.log(textStatus)
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
                        jQuery.each(value, function (method, params) {
                            if (typeof methods[method] === "function") {
                                methods[method](params);
                                //if(callback()) return;
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
         * @param params.url string
         * @param params.processResponse bool If true invoke another easyajax method with the response data
         */
        yea_confirm: function (params) {
            if (window.confirm(params.message)) {
                jQuery.get(params.url, function (response) {
                    if (typeof params.processResponse && params.processResponse === true) {
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
         * @param params.url
         * @param params.ajax bool If false, do a javascript redirection
         * @param params.processResponse bool If true invoke another easyajax method with the response data
         */
        yea_redirect: function (params) {
            if (params.ajax === true) {
                jQuery.get(params.url, function (response) {
                    if (typeof params.processResponse && params.processResponse === true) {
                        pub.response(response);
                    }
                });
            } else {
                window.location.href = params.url;
            }
        },

        /**
         * Content Replace
         *
         * Set the HTML contents of each element in the set of matched elements.
         *
         * @param params.id string Id of the div to replace
         * @param params.tagContent string content to insert
         *
         * Accept an array of contents to be replaced:
         * {tag_id: tag_html_content, tag_id: tag_html_content, ...}
         *
         */
        yea_content_replace: function (params) {
            jQuery.each(params, function (tagId, tagContent) {
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
         * @param params.formId string
         * @param params.formErrors array of errors grouped by field ID
         */
        yea_form_validation: function (params) {
            jQuery.each(params, function (formId, formErrors) {
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
         * @param params array|string Id or array to reload
         */
        yea_pjax_reload: function (params) {
            //Rebuild container_id as object if necessary
            jQuery.each(params, function (index, container) {
                if (!jQuery.isPlainObject(container)) {
                    params[index] = {};
                    params[index].container = container;
                }
                //Add timeout if not exist in the container object
                if (!("timeout" in params[index])) {
                    params[index].timeout = jQuery(params[index].container).attr("data-pjax-timeout");
                }
                //Removes elements to reload passed from the controller that not exist in page avoid javascript error in console
                //Happen when same controller is used in different page and so need to reload different elements in different pages
                if (!jQuery(container).length) {
                    params.splice(index, 1);
                }
            });
            if (params.length) {
                //Reload containers when the previous end loading
                jQuery.each(params, function (index, container) {
                    if (index + 1 < params.length) {
                        jQuery(container.container).one("pjax:end", function (xhr, options) {
                            jQuery.pjax.reload(params[index + 1]);
                        });
                    }
                });
                jQuery.pjax.reload(params[0]);
            }
        },

        /**
         * Modal
         *
         * Configure, adds content, title, an optional footer and open the bootstrap modal
         *
         * @param params.title string
         * @param params.content string
         * @param params.footer string
         * @param params.size string
         * @param params.addClass string
         *
         * If easyAjax.options.autofocus is TRUE, set the cursor on the first input of its form
         */
        yea_modal: function (params) {

            pub.resetModal();

            if (typeof params.title !== "undefined") {
                modal.find(options.modal.title_id).html(params.title);
            }

            if (typeof params.content !== "undefined") {
                modal.find(options.modal.content_id).html(params.content);
            }

            if (typeof params.footer !== "undefined") {
                modal.find(options.modal.footer_id).html(params.footer);
            }

            if (typeof params.size !== "undefined") {
                modal.find(".modal-dialog")
                    .addClass(params.size);
            }

            if (typeof params.addClass !== "undefined") {
                jQuery.each(params.addClass, function (key, val) {
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
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        },

        /**
         * Trigger for jQuery Bootstrap Notify
         *
         * @see http://bootstrap-notify.remabledesigns.com/ for its configuration
         *
         * @param params
         */
        yea_notify: function (params) {
            jQuery.notify(params.options, params.settings);
        },

        /**
         * Bootstrap Tabs
         *
         * @param params.tab string ID of the tab
         * @param params.content string content to update
         */
        yea_tab: function (params) {
            if (params.tab !== 'undefined') {
                var pane = '#' + params.tab;
                if (params.content !== 'undefined') {
                    jQuery(pane).html(params.content);
                }
                jQuery(pane).tab('show');
            }
        }
    }

    return pub;

})(window.jQuery);

/**
 *
 * @param element The element clicked
 * @param modal_id YiiEasyAjax options
 * @returns {{method: string, data: [], form_id: boolean|string, target: null|string}}
 */
var requestor;
requestor = function (element, modal_id) {

    var object = {
        method:  'get',
        data:    [],
        target:  null,
        form_id: false,
    }

    var target_attribute = 'href';

    // set request method
    if (element.hasAttribute("data-yea-method")) {
        object.method = element.getAttribute("data-yea-method");
    }

    if (element.hasAttribute("data-href")) {
        target_attribute = "data-href";
    }

    object.target = element.getAttribute(target_attribute);

    if (element.hasAttribute("data-form-id")) {
        object.form_id = element.getAttribute("data-form-id");
    } else if (
        element.closest("#" + modal_id) !== null
        && document.getElementById(modal_id).getElementsByTagName('form').length > 0
    ) {
        object.form_id = document.getElementById(modal_id).getElementsByTagName('form')[0].getAttribute('id');
    }
    if (object.form_id !== null && object.form_id !== false) {
        var form = $("#" + object.form_id);
        if (form.attr('method') !== null) {
            object.method = form.attr('method');
        }
        object.target = form.attr('action');
        object.data = form.serializeArray();
        object.data.push({name: "yea-submit", value: true});
    }

    return object;
};

jQuery(document).ready(function () {

    // https://stackoverflow.com/questions/28322636/synchronous-xmlhttprequest-warning-and-script

    yii.easyAjax.init(yea_options.extends);

    jQuery(document)
        .on("click", "[" + yea_options.trigger + "='1']", function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            if (jQuery(this)[0].hasAttribute("data-yea-confirm")) {
                if (window.confirm(jQuery(this).attr("data-yea-confirm"))) {
                    yii.easyAjax.request(this);
                }
            } else {
                yii.easyAjax.request(this);
            }
        })
        .keypress(function (e) {
            if (e.which === 13 && ($("#" + yea_options.modal.id).data("bs.modal") || {}).isShown && !$("textarea").is(":focus")) {
                e.preventDefault();
                e.stopImmediatePropagation();
                $(".modalform-submit").click();
            }
        })
});