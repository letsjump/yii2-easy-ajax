/** @var modal Bootstrap modal */
var modal = jQuery('#systemModal');

/** @var originalModal Original modal needed in case of reset */
var originalModal = modal.clone();


(function ($) {
    $.fn.resetModal = function () {
        $(this).remove();
        var myClone = originalModal.clone();
        jQuery('body').append(myClone);
    };
})(jQuery);

var ajaxResponse = function (data) {
    if (data && typeof data !== 'undefined') {

        /** Modal management */
        if (data.modal && data.modal !== 'undefined' && data.modal !== 'close') {
            modal.resetModal();
            jQuery('.modal-backdrop').remove();
            // modal = jQuery('#systemModal');
            // modal.removeData('modal');
            if (typeof data.modal.title !== 'undefined') {
                modal.find('.modal-header h4').html(data.modal.title);
            }
            if (typeof data.modal.body !== 'undefined') {
                modal.find('.modal-body').html(data.modal.body);
            }
            //if exist phantomModal create all html required into the modal
            if (typeof data.modal.options !== 'undefined') {
                if (typeof data.modal.options.phantomModal !== 'undefined') {
                    modal.find('.modal-content').addClass('phantom-modal');
                    modal.find('.modal-body').append('<div class="phantom-content"><div class="phantom-header" /><div class="phantom-body" /></div>');
                    // fix bug AdminLTE options finché non viene rilasciata la nuova versione
                    // jQuery('<button type="button" class="close"' + o.phantomModal.phantomModalToggleSelector.slice(1, -1) + ' aria-hidden="true">×</button>').appendTo(modal.find('.modal-body .phantom-header'));
                    jQuery('<button type="button" class="close"' + '[data-widget="phantom-content-toggle"]'.slice(1, -1) + ' aria-hidden="true">×</button>').appendTo(modal.find('.modal-body .phantom-header'));
                    modal.find('.modal-body > div:first').addClass('phantom-brother');
                }
            }
            if (typeof data.modal.footer !== 'undefined') {
                modal.find('.modal-footer').html(data.modal.footer);
            }

            if (typeof data.modal.size !== 'undefined') {
                modal.find('.modal-dialog')
                    .addClass(data.modal.size);
            }
            if (typeof data.modal.addClass !== 'undefined') {
                jQuery.each(data.modal.addClass, function (key, val) {
                    modal.addClass(val);
                });
            }

            modal.modal();
        } else if ('close' === data.modal) {
            modal.modal('hide');
        }

        //Handle the phantomContent empty and fill
        if (data.phantomContent && data.phantomContent !== 'undefined' && data.phantomContent !== 'close') {
            var phantomContent = modal.find('.phantom-body');
            phantomContent.empty();
            phantomContent.append(data.phantomContent);
            jQuery('.phantom-content .close').trigger('click');
        } else if ('close' === data.phantomContent) {
            jQuery('.phantom-content .close').trigger('click');
        }

        // // adding growls to page
        if (data.growl && typeof data.growl !== 'undefined') {
            jQuery.notify(data.growl.title, data.growl);
        }

        if (typeof data.jsFunction !== 'undefined') {
            eval(data.jsFunction);
        }

        // reload gridViews
        // refer to: http://stackoverflow.com/questions/31985286/how-to-reload-multiple-pjax
        if (data.pjaxReload && typeof data.pjaxReload !== 'undefined') {
            //Rebuild container_id  as object if necessary
            jQuery.each(data.pjaxReload, function (index, container) {
                if (!jQuery.isPlainObject(container)) {
                    data.pjaxReload[index] = {};
                    data.pjaxReload[index].container = container;
                }
                //Add timeout if not exist in the container object
                if (!('timeout' in data.pjaxReload[index])) {
                    data.pjaxReload[index].timeout = jQuery(data.pjaxReload[index].container).attr('data-pjax-timeout');
                }
                //Removes elements to reload passed from the controller that not exist in page avoid javascript error in console
                //Happen when same controller is used in different page and so need to reload different elements in different pages
                if (!jQuery(container).length) {
                    data.pjaxReload.splice(index, 1);
                }
            });
            if (data.pjaxReload.length) {
                //Reload containers when the previous end loading
                jQuery.each(data.pjaxReload, function (index, container) {
                    if (index + 1 < data.pjaxReload.length) {
                        jQuery(container.container).one('pjax:end', function (xhr, options) {
                            jQuery.pjax.reload(data.pjaxReload[index + 1]);
                        });
                    }
                });
                jQuery.pjax.reload(data.pjaxReload[0]);
            }
        }

        //Reload active modal tab and delete cache of specified tabs
        if (data.tabsReload && typeof data.tabsReload !== 'undefined') {
            modal.find('.tabs-krajee').tabsX('flushCache', data.tabsReload);
            jQuery(modal).find('li.active a').trigger('click');
        }

        // multiple form validation
        if (data.validation && typeof data.validation !== 'undefined') {
            jQuery.each(data.validation, function (formId, formErrors) {
                jQuery.each(formErrors, function (key, val) {
                    jQuery(formId).yiiActiveForm('updateAttribute', key, [val]);
                });
            });
        }

        // replace content
        if (data.replace && typeof data.replace !== 'undefined') {
            jQuery.each(data.replace, function (tagId, tagContent) {
                jQuery(tagId).html(tagContent);
            });
        }

        // redirect javascript stle
        if (data.redirect && typeof  data.redirect !== 'undefined') {
            window.location.href = data.redirect;
        }

        // redirect ajax style
        if (data.ajaxRedirect && typeof  data.ajaxRedirect !== 'undefined') {
            jQuery.get(data.ajaxRedirect);
        }

        // open a confirm window.
        if (data.confirm && typeof  data.confirm !== 'undefined') {
            if (confirm(data.confirm.message)) {
                jQuery.get(data.confirm.url);
            }
        }

        modal.on('shown.bs.modal', function () {
            modal.find('[autofocus]').focus();
            if (modal.find('[autofocus]').not(":focus")) {
                setTimeout(function () {
                    modal.find('[autofocus]').focus();
                }, 200);
            }
        });
    }
}

jQuery(document).ready(function () {

    jQuery(document).on('click', '.open-modal, [data-ajax="1"]', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var attribute = jQuery(this)[0].hasAttribute("data-href") ? 'data-href' : 'href';
        jQuery.get(jQuery(this).attr(attribute), function (data) {
            ajaxResponse(data);
        });
    });

    $(document)
        .keypress(function (e) {
            if (e.which === 13 && ($("#systemModal").data('bs.modal') || {}).isShown && !$("textarea").is(":focus")) {
                e.preventDefault();
                e.stopImmediatePropagation();
                $('#modalform-submit').click();
            }
        })
        .on('click', '#modalform-submit', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var form = jQuery(ajaxFormId);
            var data = form.serializeArray();
            data.push({name: 'save', value: true});
            $.ajax({
                type:    form.attr('method'),
                url:     form.attr('action'),
                data:    data,
                success: function (data) {
                    ajaxResponse(data);
                    if (data.success === true) {
                        $("[data-dismiss=modal]").trigger({type: "click"});
                    }
                }
            });
        });
});