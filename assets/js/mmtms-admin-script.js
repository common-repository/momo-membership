/**jslint browser:true, devel:true */
/*global jQuery*/
/*global define */
/*global window */
/*jslint this*/
/*global tinymce*/
/*global document*/
/*global mmtms_admin_ajax*/
/*global wp*/
/**
 * ADMIN jQuery functions
 */
jQuery(document).ready(function ($) {
    "use strict";
    function changeAdminTab(hash) {
        var mmtmsTable = $('.mmtms-admin-table');
        mmtmsTable.find('.mmtms-admin-content.active').removeClass('active');
        var ul = mmtmsTable.find('ul.mmtms-admin-tab');
        ul.find('li a').removeClass('active');
        $(ul).find('a[href=\\' + hash + ']').addClass('active');
        mmtmsTable.find(hash).addClass('active');
        $("html, body").animate({
            scrollTop: 0
        }, 1000);
    }
    function doNothing() {
        return;
    }
    function init() {
        var hash = window.location.hash;
        if (hash === '' || hash === 'undefined') {
            doNothing();
        } else {
            changeAdminTab(hash);
        }
        var value = $('body').find('select[name="mmtms_billing_type"]').val();
        if (value === 'payment') {
            $('body').find('.mmtms_payment_box').css('display', 'block');
        } else {
            $('body').find('.mmtms_payment_box').css('display', 'none');
        }
        $('#mmtms-admin-form .switch-input').each(function () {
            var toggleContainer = $(this).parents('.mmtms-admin-toggle-container');
            var afteryes = toggleContainer.attr('afteryes');
            if ($(this).is(":checked")) {
                $('#' + afteryes).addClass('active');
                $(this).val('on');
            } else {
                $('#' + afteryes).removeClass('active');
                $(this).val('off');
            }
        });
    }
    init();
    /*** Handlebar toggle ***/
    $('body').on('click', '.mmtms-handlediv', function (e) {
        e.preventDefault();
        var $parent = $(this).closest('.postbox');
        var $inside = $parent.find('.inside');
        if ($parent.hasClass('mmtms-closed')) {
            $parent.removeClass('mmtms-closed');
            $inside.slideDown();
        } else {
            $parent.addClass('mmtms-closed');
            $inside.slideUp();
        }
    });
    var file_frame;
    $('.mmtms-additional-user-image').on('click', function (e) {
        e.preventDefault();
        var imgContainer = $(this).closest('.mmtms-user-profile-img').find('.mmtms-img-container');
        var imgInput = $(this).closest('.mmtms-user-profile-img').find('input[name="mmtms_p_image"]');
        // If the media frame already exists, reopen it.
        if (file_frame) {
            file_frame.open();
            return;
        }
        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: $(this).data('title'),
            button: {
                text: $(this).data('btext')
            },
            multiple: false
        });
        file_frame.on('select', function () {
            var attachment = file_frame.state().get('selection').first().toJSON();
            imgContainer.html('<img src="' + attachment.url + '" alt="" style="height:100px;"/>');
            imgInput.val(attachment.url);
        });
        file_frame.open();
    });
    $('body').on('change', '#mmtms-admin-form  .switch-input', function () {
        var toggleContainer = $(this).parents('.mmtms-admin-toggle-container');
        var afteryes = toggleContainer.attr('afteryes');
        if ($(this).is(":checked")) {
            $('#' + afteryes).addClass('active');
            $(this).val('on');
        } else {
            $('#' + afteryes).removeClass('active');
            $(this).val('off');
        }
    });
    $('.mmtms-admin-table').on('click', 'ul.mmtms-admin-tab li a', function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        changeAdminTab(href);
        window.location.hash = href;
    });
    $('.mmtms-admin-content-box').on('click', '.toggle-indicator , .postbox h2', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $postbox = $btn.closest('.postbox');
        var $button = $postbox.find('.handlediv');
        var aria = $button.attr('aria-expanded');
        if (aria === 'true') {
            $postbox.addClass('closed');
            $button.attr('aria-expanded', 'false');
        } else {
            $postbox.removeClass('closed');
            $button.attr('aria-expanded', 'true');
        }
    });
    $('.mmtms-admin-content-box').on('click', 'a.tab_click', function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        changeAdminTab(href);
        window.location.hash = href;
    });
    $('.mmtms-display-popup').on('click', function (e) {
        e.preventDefault();
        var attr = $(this).attr('href');
        $(attr).find('.mmtms-ajax-add-new-level').css('display', 'inline-block');
        $(attr).find('.mmtms-ajax-edit-level').css('display', 'none');
        $(attr).find('input[name="mmtms_new_level_name"]').val('');
        $(attr).find('input[name="mmtms_new_level_price"]').val('');
        var $editor = tinymce.get('mmtms_new_level_desc');
        if ($editor) {
            $editor.setContent('');
        } else {
            $('#mmtms_new_level_desc').val('');
        }
        //tinymce.get('mmtms_new_level_desc').setContent('');
        $('body').find(attr).addClass('show');
        $('body').addClass('mmtms-admin-lb-body-overflow');
    });
    $('.mmtms-admin-lb-close').on('click', function (e) {
        e.preventDefault();
        var $parent = $(this).closest('.mmtms-admin-lightbox');
        $parent.removeClass('show');
        $('body').removeClass('mmtms-admin-lb-body-overflow');
    });
    $('select[name="mmtms_billing_type"]').on('change', function () {
        var value = $(this).val();
        var FORM = $(this).closest('#admin_add_new_level');
        if (value === 'payment') {
            FORM.find('.mmtms_payment_box').css('display', 'block');
        } else {
            FORM.find('.mmtms_payment_box').css('display', 'none');
        }
    });
    function admin_error_msg_lb(form, msg) {
        var msgBox = form.find('.mmtms-lb-msgbox');
        var loader = form.find('.mmtms-admin-loader');
        msgBox.html(msg);
        if (msg === null) {
            msgBox.html('');
            msgBox.removeClass('red');
            msgBox.css('display', 'none');
            return;
        }
        msgBox.addClass('red');
        msgBox.css('display', 'block');
        loader.css('display', 'none');
    }
    function show_admin_lb_loading(form) {
        var loader = form.find('.mmtms-admin-loader');
        loader.css('display', 'block');
    }
    function hide_admin_lb_loading(form) {
        var loader = form.find('.mmtms-admin-loader');
        loader.css('display', 'none');
    }
    function show_tr_loading(tr) {
        tr.addClass('admin-tr-loading');
    }
    function hide_tr_loading(tr) {
        tr.removeClass('admin-tr-loading');
    }
    function show_tab_page_loading(form) {
        var loader = form.find('.mmtms-admin-tab-loading');
        loader.css('display', 'block');
    }
    function hide_tab_page_loading(form) {
        var loader = form.find('.mmtms-admin-tab-loading');
        loader.css('display', 'none');
    }
    function refresh_admin_level_table() {
        var $tableContainer = $('body').find('.mmtms-admin-levels-table');
        var ajaxdata = {};
        ajaxdata.action = 'mmtms_generate_admin_level_table';
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        $.ajax({
            beforeSend: function () {
                doNothing();
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'good') {
                    $tableContainer.html(data.level_table);
                }
            }
        });
    }
    function refresh_admin_role_table() {
        var $tableContainer = $('body').find('.mmtms-admin-roles-table');
        var ajaxdata = {};
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_generate_admin_roles_table';
        $.ajax({
            beforeSend: function () {
                doNothing();
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'good') {
                    $tableContainer.html(data.role_table);
                }
            }
        });
    }
    function refresh_admin_member_table() {
        var $tableContainer = $('body').find('.mmtms-admin-user-table');
        var ajaxdata = {};
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_generate_admin_user_table';
        $.ajax({
            beforeSend: function () {
                doNothing();
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'good') {
                    $tableContainer.html(data.user_table);
                }
            }
        });
    }
    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return regex.test(email);
    }
    $('body').on('click', '.save-redirection-settings', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $form = $btn.closest('.mmtms-admin-redirection-form');
        var loginRedirect = $form.find('select[name="login_redirect_page_id"]').val();
        var logoutRedirect = $form.find('select[name="logout_redirect_page_id"]').val();
        var ajaxdata = {};
        ajaxdata.login_redirect = loginRedirect;
        ajaxdata.logout_redirect = logoutRedirect;
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_save_redirection_settings';
        $.ajax({
            beforeSend: function () {
                show_tab_page_loading($form);
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function () {
                return;
            },
            complete: function () {
                hide_tab_page_loading($form);
            }
        });
    });
    $('body').on('click', '.save-invoice-settings', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $form = $btn.closest('.mmtms-admin-invoice-form');
        var invoiceYN = $form.find('input[name="mmtms_enable_invoice"]').val();
        var businessName = $form.find('input[name="mmtms_inv_bname"]').val();
        var email = $form.find('input[name="mmtms_inv_email"]').val();
        var address = $form.find('input[name="mmtms_inv_address"]').val();
        var phone = $form.find('input[name="mmtms_inv_phone"]').val();
        var businessLogo = $form.find('input[name="mmtms_business_logo"]').val();
        var emailSub = $form.find('input[name="mmtms_inv_email_sub"]').val();
        var ajaxdata = {};
        ajaxdata.enable_invoice = invoiceYN;
        ajaxdata.business_name = businessName;
        ajaxdata.email = email;
        ajaxdata.address = address;
        ajaxdata.phone = phone;
        ajaxdata.business_logo = businessLogo;
        ajaxdata.mmtms_inv_email_sub = emailSub;
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_save_invoice_settings';
        $.ajax({
            beforeSend: function () {
                show_tab_page_loading($form);
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function () {
                return;
            },
            complete: function () {
                hide_tab_page_loading($form);
            }
        });
    });
    $('body').on('click', '.save-email-settings', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $form = $btn.closest('.mmtms-admin-email-form');
        var mecl = $form.find('input[name="mmtms_email_confirmation_link"]').val();
        var merpl = $form.find('input[name="mmtms_email_reset_password_link"]').val();
        var menanm = $form.find('input[name="mmtms_email_notify_admin_new_member"]').val();
        var mes = $form.find('input[name="mmtms_ecl_subject"]').val();
        var meb = $form.find('textarea[name="mmtms_ecl_body"]').val();
        var msn = $form.find('input[name="mmtms_sender_name"]').val();
        var mse = $form.find('input[name="mmtms_sender_email"]').val();
        var ajaxdata = {};
        ajaxdata.mmtms_email_confirmation_link = mecl;
        ajaxdata.mmtms_email_reset_password_link = merpl;
        ajaxdata.mmtms_email_notify_admin_new_member = menanm;
        ajaxdata.mmtms_ecl_subject = mes;
        ajaxdata.mmtms_ecl_body = meb;
        ajaxdata.mmtms_sender_name = msn;
        ajaxdata.mmtms_sender_email = mse;
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_save_email_settings';
        $.ajax({
            beforeSend: function () {
                show_tab_page_loading($form);
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function () {
                return;
            },
            complete: function () {
                hide_tab_page_loading($form);
            }
        });
    });
    $('body').on('click', '.save-paypal-settings', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $form = $btn.closest('.mmtms_payment_form');
        var ppYesNo = $form.find('input[name="mmtms_payment_paypal_yn"]').val();
        var ppTitle = $form.find('input[name="mmtms_paypal_title"]').val();
        var ppDesc = $form.find('textarea[name="mmtms_paypal_description"]').val();
        var ppEmail = $form.find('input[name="mmtms_paypal_email"]').val();
        var sbYesNo = $form.find('input[name="mmtms_payment_paypal_sb_yn"]').val();
        var liveID = $form.find('input[name="mmtms_paypal_live_id"]').val();
        var liveSecret = $form.find('input[name="mmtms_paypal_live_secret"]').val();
        var sbID = $form.find('input[name="mmtms_paypal_sb_id"]').val();
        var sbSecret = $form.find('input[name="mmtms_paypal_sb_secret"]').val();
        var ajaxdata = {};
        ajaxdata.enable_pp = ppYesNo;
        ajaxdata.title = ppTitle;
        ajaxdata.desc = ppDesc;
        ajaxdata.email = ppEmail;
        ajaxdata.enable_sandbox = sbYesNo;
        ajaxdata.live_id = liveID;
        ajaxdata.live_secret = liveSecret;
        ajaxdata.sandbox_id = sbID;
        ajaxdata.sandbox_secret = sbSecret;
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_save_paypal_settings';
        $.ajax({
            beforeSend: function () {
                show_tab_page_loading($form);
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function () {
                return;
            },
            complete: function () {
                hide_tab_page_loading($form);
            }
        });
    });
    $('body').on('click', '.save-uninstall-settings', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $form = $btn.closest('.mmtms_uninstall_form');
        var uninstall = $form.find('input[name="mmtms_delete_uninstall"]').val();
        var ajaxdata = {};
        ajaxdata.uninstall = uninstall;
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_save_plugin_settings';
        $.ajax({
            beforeSend: function () {
                show_tab_page_loading($form);
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function () {
                return;
            },
            complete: function () {
                hide_tab_page_loading($form);
            }
        });
    });
    $('.mmtms_level_table_delete').on('click', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var slug = $btn.attr('data-slug');
        var tr = $btn.closest('tr');
        var ajaxdata = {};
        ajaxdata.slug = slug;
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_delete_level_by_slug';
        $.ajax({
            beforeSend: function () {
                show_tr_loading(tr);
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'good') {
                    tr.remove();
                }
                hide_tr_loading(tr);
            },
            complete: function () {
                hide_tr_loading(tr);
            }
        });
    });
    $('body').on('click', '.mmtms_level_table_edit', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var slug = $btn.attr('data-slug');
        var tr = $btn.closest('tr');
        var ajaxdata = {};
        ajaxdata.slug = slug;
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_get_levels_by_slug';
        $.ajax({
            beforeSend: function () {
                show_tr_loading(tr);
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'good') {
                    var $parent = $btn.closest('.mmtms-admin-content');
                    var FORM = $parent.find('.mmtms-admin-lightbox');
                    FORM.find('input[name="mmtms_new_level_name"]').val(data.level.level_name);
                    FORM.find('select[name="mmtms_billing_type"]').val(data.level.billing_type);
                    FORM.find('select[name="mmtms_wp_level_role"]').val(data.level.wp_capability);
                    FORM.find('input[name="mmtms_new_level_price"]').val(data.level.level_price);
                    //FORM.find('#mmtms_new_level_desc').val(data.level.description);
                    var $editor = tinymce.get('mmtms_new_level_desc');
                    if ($editor) {
                        $editor.setContent(data.level.description);
                    } else {
                        $('#mmtms_new_level_desc').val(data.level.description);
                    }
                    //tinymce.get('mmtms_new_level_desc').setContent(data.level.description);
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'level_slug_hidden',
                        name: 'level_slug_hidden',
                        value: data.level.level_slug
                    }).appendTo(FORM);
                    FORM.find('.mmtms-ajax-add-new-level').css('display', 'none');
                    FORM.find('.mmtms-ajax-edit-level').css('display', 'inline-block');
                    $(FORM).addClass('show');
                    $('body').addClass('mmtms-admin-lb-body-overflow');
                    hide_tr_loading(tr);
                }
            },
            complete: function () {
                hide_tr_loading(tr);
            }
        });
    });
    $('.mmtms-ajax-edit-level').on('click', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var FORM = $btn.closest('#admin_add_new_level');
        var ajaxdata = {};
        var errors = 0;
        FORM.find('input').each(function () {
            if ($(this).val() === '') {
                errors = errors + 1;
            } else {
                ajaxdata[$(this).attr('name')] = $(this).val();
            }
        });
        var bt = FORM.find('select[name="mmtms_billing_type"]');
        var bp = FORM.find('input[name="mmtms_new_level_price"]');
        ajaxdata.mmtms_billing_type = bt.val();
        if (bt.val() !== 'payment' && bp.val() === '') {
            errors = errors - 1;
        }
        if (errors > 0) {
            var msg = mmtms_admin_ajax.requierd_field_msg;
            admin_error_msg_lb(FORM, msg);
            return false;
        }
        ajaxdata.mmtms_wp_level_role = FORM.find('select[name="mmtms_wp_level_role"]').val();
        ajaxdata.action = 'mmtms_ajax_edit_level';
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        //ajaxdata.description = tinymce.get('mmtms_new_level_desc').getContent();
        var $editor = tinymce.get('mmtms_new_level_desc');
        if ($editor) {
            ajaxdata.description = $editor.getContent();
        } else {
            ajaxdata.description = $('#mmtms_new_level_desc').val();
        }
        $.ajax({
            beforeSend: function () {
                show_admin_lb_loading(FORM);
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'bad') {
                    admin_error_msg_lb(FORM, data.msg);
                } else if (data.status === 'good') {
                    hide_admin_lb_loading(FORM);
                    FORM.closest('.mmtms-admin-lightbox').removeClass('show');
                    refresh_admin_level_table();
                }
            },
            complete: function () {
                hide_admin_lb_loading(FORM);
            }
        });
    });
    $('.mmtms-ajax-add-new-level').on('click', function (e) {
        e.preventDefault();
        var BTN = $(this);
        var FORM = BTN.closest('#admin_add_new_level');
        var ajaxdata = {};
        // field validation
        var errors = 0;
        FORM.find('input').each(function () {
            if ($(this).val() === '') {
                errors = errors + 1;
            } else {
                ajaxdata[$(this).attr('name')] = $(this).val();
            }
        });
        var bt = FORM.find('select[name="mmtms_billing_type"]');
        var bp = FORM.find('input[name="mmtms_new_level_price"]');
        ajaxdata.mmtms_billing_type = bt.val();
        if (bt.val() !== 'payment' && bp.val() === '') {
            errors = errors - 1;
        }
        if (errors > 0) {
            var msg = mmtms_admin_ajax.requierd_field_msg;
            admin_error_msg_lb(FORM, msg);
            return false;
        }
        ajaxdata.mmtms_wp_level_role = FORM.find('select[name="mmtms_wp_level_role"]').val();
        ajaxdata.action = 'mmtms_ajax_add_new_level';
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        //ajaxdata.description = tinymce.get('mmtms_new_level_desc').getContent();
        var $editor = tinymce.get('mmtms_new_level_desc');
        if ($editor) {
            ajaxdata.description = $editor.getContent();
        } else {
            ajaxdata.description = $('#mmtms_new_level_desc').val();
        }
        $.ajax({
            beforeSend: function () {
                show_admin_lb_loading(FORM);
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'bad') {
                    admin_error_msg_lb(FORM, data.msg);
                } else if (data.status === 'good') {
                    hide_admin_lb_loading(FORM);
                    FORM.closest('.mmtms-admin-lightbox').removeClass('show');
                    refresh_admin_level_table();
                }
            },
            complete: function () {
                hide_admin_lb_loading(FORM);
            }
        });
    });

    /** ROLE PAGE */
    $('body').on('click', '.mmtms-cpb-load-more', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $moreDiv = $btn.prev('.mmtms-admin-cpb-hide');
        $btn.next('.mmtms-cpb-load-less').css('display', 'block');
        $btn.css('display', 'none');
        $moreDiv.slideDown();
    });
    $('body').on('click', '.mmtms-cpb-load-less', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $moreDiv = $btn.prev().prev('.mmtms-admin-cpb-hide');
        $btn.prev('.mmtms-cpb-load-more').css('display', 'block');
        $btn.css('display', 'none');
        $moreDiv.slideUp();
    });
    $('body').on('click', '.mmtms-display-role-lb', function (e) {
        e.preventDefault();
        var $form = $('body').find('#admin_add_new_role');
        $form.find('input[name="mmtms_new_role_id"]').val('');
        $form.find('input[name="mmtms_new_role_name"]').val('');
        $form.find('.mmtms-lb-msgbox').html('').css('display', 'none');
        var attr = $(this).attr('href');
        $('body').find(attr).addClass('show');
        $('body').addClass('mmtms-admin-lb-body-overflow');
    });
    $('body').on('click', '.mmtms-ajax-add-new-role', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $form = $btn.closest('#admin_add_new_role');
        var roleID = $form.find('input[name="mmtms_new_role_id"]').val();
        var roleName = $form.find('input[name="mmtms_new_role_name"]').val();
        if (roleID === '' || roleName === '') {
            var msg = mmtms_admin_ajax.requierd_field_msg;
            admin_error_msg_lb($form, msg);
            return false;
        }
        var letters = /^[0-9a-zA-Z\-_]+$/;
        if (!roleID.match(letters)) {
            var rmsg = mmtms_admin_ajax.role_id_msg;
            admin_error_msg_lb($form, rmsg);
            return false;
        }
        var ajaxdata = {};
        ajaxdata.role_id = roleID;
        ajaxdata.role_name = roleName;
        ajaxdata.action = 'mmtms_admin_add_new_role';
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        $.ajax({
            beforeSend: function () {
                show_admin_lb_loading($form);
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'bad') {
                    admin_error_msg_lb($form, data.msg);
                } else if (data.status === 'good') {
                    hide_admin_lb_loading($form);
                    $form.closest('.mmtms-admin-lightbox').removeClass('show');
                    $('body').removeClass('mmtms-admin-lb-body-overflow');
                    refresh_admin_role_table();
                }
            },
            complete: function () {
                hide_admin_lb_loading($form);
            }
        });
    });
    $('body').on('click', '.mmtms_role_table_delete', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var slug = $btn.attr('data-slug');
        var tr = $btn.closest('tr');
        var ajaxdata = {};
        ajaxdata.slug = slug;
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_delete_role_by_id';
        $.ajax({
            beforeSend: function () {
                show_tr_loading(tr);
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'good') {
                    tr.remove();
                }
                hide_tr_loading(tr);
            },
            complete: function () {
                hide_tr_loading(tr);
            }
        });
    });
    $('body').on('click', '.mmtms_role_table_edit', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var slug = $btn.attr('data-slug');
        var tr = $btn.closest('tr');
        var ajaxdata = {};
        ajaxdata.slug = slug;
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_get_role_name_by_id';
        $.ajax({
            beforeSend: function () {
                show_tr_loading(tr);
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'good') {
                    var $parent = $btn.closest('.mmtms-admin-content');
                    var $form = $parent.find('#admin_add_new_role');
                    $form.find('input[name="mmtms_new_role_id"]').val(slug);
                    $form.find('input[name="mmtms_new_role_id"]').prop('readonly', 'true');
                    $form.find('input[name="mmtms_new_role_name"]').val(data.role_name);
                    $form.find('.mmtms-ajax-add-new-role').css('display', 'none');
                    $form.find('.mmtms-ajax-edit-role').css('display', 'inline-block');
                    $($form).addClass('show');
                    $('body').addClass('mmtms-admin-lb-body-overflow');
                    hide_tr_loading(tr);
                }
            },
            complete: function () {
                hide_tr_loading(tr);
            }
        });
    });
    $('body').on('click', '.mmtms-ajax-edit-role', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $form = $btn.closest('#admin_add_new_role');
        var roleID = $form.find('input[name="mmtms_new_role_id"]').val();
        var roleName = $form.find('input[name="mmtms_new_role_name"]').val();
        if (roleID === '' || roleName === '') {
            var msg = mmtms_admin_ajax.requierd_field_msg;
            admin_error_msg_lb($form, msg);
            return false;
        }
        var ajaxdata = {};
        ajaxdata.role_id = roleID;
        ajaxdata.role_name = roleName;
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_update_role_name';
        $.ajax({
            beforeSend: function () {
                show_admin_lb_loading($form);
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'bad') {
                    admin_error_msg_lb($form, data.msg);
                } else if (data.status === 'good') {
                    hide_admin_lb_loading($form);
                    $form.closest('.mmtms-admin-lightbox').removeClass('show');
                    $('body').removeClass('mmtms-admin-lb-body-overflow');
                    refresh_admin_role_table();
                }
            },
            complete: function () {
                hide_admin_lb_loading($form);
            }
        });
    });
    $('body').on('click', '.mmtms_cap_table_edit', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var role = $btn.attr('data-slug');
        var tr = $btn.closest('tr');
        var $parent = $btn.closest('.mmtms-admin-content');
        var $form = $parent.find('#admin_edit_role_capabilities');
        var ajaxdata = {};
        ajaxdata.role_id = role;
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_ajax_generate_capabilities_by_role';
        $.ajax({
            beforeSend: function () {
                show_tr_loading(tr);
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'good') {
                    $form.find('.mmtms-admin-lb-content').html(data.content);
                    $($form).addClass('show');
                    $('body').addClass('mmtms-admin-lb-body-overflow');
                    hide_tr_loading(tr);
                }
            },
            complete: function () {
                hide_tr_loading(tr);
            }
        });
    });
    $('body').on('click', '.mmtms-ajax-edit-capabilities', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $form = $btn.closest('#admin_edit_role_capabilities');
        var ajaxdata = {};
        var cap = [];
        ajaxdata.role = $form.find('input[name="mmtms-lb-role-name"]').val();
        $form.find('input[type="checkbox"]:checked').each(function () {
            cap.push($(this).attr('name'));
        });
        ajaxdata.cap = cap;
        ajaxdata.action = 'mmtms_update_role_capabilities';
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        $.ajax({
            beforeSend: function () {
                show_admin_lb_loading($form);
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'good') {
                    $form.closest('.mmtms-admin-lightbox').removeClass('show');
                    $('body').removeClass('mmtms-admin-lb-body-overflow');
                    refresh_admin_role_table();
                }
            },
            complete: function () {
                hide_admin_lb_loading($form);
            }
        });
    });
    /** ENDS ROLE PAGE */

    /**Begins User Page */
    $('body').on('click', '.mmtms-display-user-lb', function (e) {
        e.preventDefault();
        var $form = $('body').find('#admin_add_new_user');
        /* $form.find('input[name="mmtms_new_role_id"]').val('');
        $form.find('input[name="mmtms_new_role_name"]').val(''); */
        $form.find('.mmtms-lb-msgbox').html('').css('display', 'none');
        $form.find('.mmtms-password-hide-edit').css('display', 'block');
        var attr = $(this).attr('href');
        $('body').find(attr).addClass('show');
        $('body').addClass('mmtms-admin-lb-body-overflow');
    });
    $('body').on('click', '.mmtms-ajax-add-new-user', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $form = $btn.closest('#admin_add_new_user');
        var ajaxdata = {};
        var errors = 0;
        $form.find('input').each(function () {
            if ($(this).val() === '') {
                errors = errors + 1;
            } else {
                ajaxdata[$(this).attr('name')] = $(this).val();
            }
        });
        if (errors > 0) {
            var msg = mmtms_admin_ajax.requierd_field_msg;
            admin_error_msg_lb($form, msg);
            return false;
        }
        if (ajaxdata.mmtms_new_user_cpassword !== ajaxdata.mmtms_new_user_password) {
            var pmsg = mmtms_admin_ajax.password_mismatch_msg;
            admin_error_msg_lb($form, pmsg);
            return false;
        }
        if (!isEmail(ajaxdata.mmtms_new_user_email)) {
            var emsg = mmtms_admin_ajax.user_invalid_email;
            admin_error_msg_lb($form, emsg);
            return false;
        }
        admin_error_msg_lb($form, null);
        ajaxdata.mmtms_wp_new_user_role = $form.find('select[name="mmtms_wp_new_user_role"]').val();
        ajaxdata.mmtms_wp_new_user_level = $form.find('select[name="mmtms_wp_new_user_level"]').val();
        ajaxdata.action = 'mmtms_admin_add_new_user';
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        $.ajax({
            beforeSend: function () {
                show_admin_lb_loading($form);
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'good') {
                    $form.closest('.mmtms-admin-lightbox').removeClass('show');
                    $('body').removeClass('mmtms-admin-lb-body-overflow');
                    refresh_admin_member_table();
                } else if (data.status === 'bad') {
                    admin_error_msg_lb($form, data.msg);
                    if (data.mid === 1) {
                        $form.find('input[name="mmtms_new_user_name"]').focus().select();
                    }
                    if (data.mid === 2) {
                        $form.find('input[name="mmtms_new_user_email"]').focus().select();
                    }
                }
            },
            complete: function () {
                hide_admin_lb_loading($form);
            }
        });
    });
    $('body').on('click', '.mmtms_member_table_edit', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var userID = $btn.attr('data-slug');
        var tr = $btn.closest('tr');
        var ajaxdata = {};
        ajaxdata.user_id = userID;
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_user_detail_by_user_id';
        $.ajax({
            beforeSend: function () {
                show_tr_loading(tr);
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'good') {
                    var $parent = $btn.closest('.mmtms-admin-content');
                    var $form = $parent.find('#admin_add_new_user');
                    $form.find('input[name="mmtms_new_user_name"]').val(data.user.username);
                    $form.find('input[name="mmtms_new_user_name"]').prop('disabled', true);
                    $form.find('input[name="mmtms_new_user_email"]').val(data.user.email);
                    $form.find('input[name="mmtms_new_user_fname"]').val(data.user.fname);
                    $form.find('input[name="mmtms_new_user_lname"]').val(data.user.lname);
                    $form.find('select[name="mmtms_wp_new_user_role"]').val(data.user.role);
                    $form.find('select[name="mmtms_wp_new_user_level"]').val(data.user.level);
                    $('<input>').attr({
                        type: 'hidden',
                        id: 'user_id_hidden',
                        name: 'user_id_hidden',
                        value: data.user.id
                    }).appendTo($form);
                    $form.find('.mmtms-password-hide-edit').css('display', 'none');
                    $form.find('.mmtms-ajax-add-new-user').css('display', 'none');
                    $form.find('.mmtms-ajax-edit-user').css('display', 'inline-block');
                    $($form).addClass('show');
                    $('body').addClass('mmtms-admin-lb-body-overflow');
                    hide_tr_loading(tr);
                }
            },
            complete: function () {
                hide_tr_loading(tr);
            }
        });
    });
    $('body').on('click', '.mmtms_member_table_delete', function (e) {
        e.preventDefault();
    });
    $('body').on('click', '.mmtms-ajax-edit-user', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $form = $btn.closest('#admin_add_new_user');
        var ajaxdata = {};
        var errors = 0;
        $form.find('input[type="text"], input[type="email"], input[type="hidden"]').each(function () {
            if ($(this).val() === '') {
                errors = errors + 1;
            } else {
                ajaxdata[$(this).attr('name')] = $(this).val();
            }
        });
        if (errors > 0) {
            var msg = mmtms_admin_ajax.requierd_field_msg;
            admin_error_msg_lb($form, msg);
            return false;
        }
        if (ajaxdata.mmtms_new_user_cpassword !== ajaxdata.mmtms_new_user_password) {
            var pmsg = mmtms_admin_ajax.password_mismatch_msg;
            admin_error_msg_lb($form, pmsg);
            return false;
        }
        if (!isEmail(ajaxdata.mmtms_new_user_email)) {
            var emsg = mmtms_admin_ajax.user_invalid_email;
            admin_error_msg_lb($form, emsg);
            return false;
        }
        admin_error_msg_lb($form, null);
        ajaxdata.mmtms_wp_new_user_role = $form.find('select[name="mmtms_wp_new_user_role"]').val();
        ajaxdata.mmtms_wp_new_user_level = $form.find('select[name="mmtms_wp_new_user_level"]').val();
        ajaxdata.action = 'mmtms_admin_edit_user_by_id';
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        $.ajax({
            beforeSend: function () {
                show_admin_lb_loading($form);
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'good') {
                    $form.closest('.mmtms-admin-lightbox').removeClass('show');
                    $('body').removeClass('mmtms-admin-lb-body-overflow');
                    refresh_admin_member_table();
                } else if (data.status === 'bad') {
                    admin_error_msg_lb($form, data.msg);
                }
            },
            complete: function () {
                hide_admin_lb_loading($form);
            }
        });
    });
    $('body').on('change', '#mb_levels_select_mmtms', function () {
        var sVal = $(this).val();
        if (sVal === '0') {
            return false;
        }
        var $parent = $(this).closest('.mb_select_container_mmtms');
        var $showOrBlock = $parent.find('select[name="mb_show_or_block_mmtms"]');
        var sbVal = $showOrBlock.val();
        var optionText = $('#mb_levels_select_mmtms option:selected').text();
        var $levelInput, $tagPH;
        var content = '';
        if (sbVal === 's') {
            $levelInput = $('#mmtms_mb').find('input[name="mb_levels_arr_show"]');
            $tagPH = $('#mmtms_mb').find('.mmtms_admin_tags_list_mb_show');
            content = '<span class="mmtms_mb_tag" data-mval="' + sVal + '">' + optionText + '<i class="mmtms-icon-cancel-circled remove_mb_mmts_tag show"></i></span>';
        } else {
            $levelInput = $('#mmtms_mb').find('input[name="mb_levels_arr_block"]');
            $tagPH = $('#mmtms_mb').find('.mmtms_admin_tags_list_mb_block');
            content = '<span class="mmtms_mb_tag" data-mval="' + sVal + '">' + optionText + '<i class="mmtms-icon-cancel-circled remove_mb_mmts_tag block"></i></span>';
        }
        //var oldVal = $levelInput.html();
        var oldVal = $levelInput.val();
        var newVal = '';
        var isThere = false;
        if (oldVal === '') {
            newVal = $(this).val();
            $tagPH.append(content);
        } else {
            var spi = oldVal.split(",");
            $.each(spi, function (index, value) {
                if (sVal === value) {
                    isThere = true;
                    index = index;
                }
            });
            if (isThere === false) {
                newVal = oldVal + ',' + $(this).val();
                $tagPH.append(content);
            } else {
                newVal = oldVal;
            }
        }
        //$levelInput.html(newVal);
        $levelInput.val(newVal);
        $(this).val('0');
    });
    $('body').on('click', '.remove_mb_mmts_tag.show', function (e) {
        e.preventDefault();
        var sVal = $(this).parent("span").data('mval');
        var $levelInput = $('#mmtms_mb').find('input[name="mb_levels_arr_show"]');
        var oldVal = $levelInput.val();
        var spi = oldVal.split(",");
        $.each(spi, function (index, value) {
            if (sVal === value) {
                spi.splice(index, 1);
            }
        });
        var str = spi.join(',');
        $levelInput.val(str);
        $(this).parent("span").fadeOut(200);
    });
    $('body').on('click', '.remove_mb_mmts_tag.block', function (e) {
        e.preventDefault();
        var sVal = $(this).parent("span").data('mval');
        var $levelInput = $('#mmtms_mb').find('input[name="mb_levels_arr_block"]');
        var oldVal = $levelInput.val();
        var spi = oldVal.split(",");
        $.each(spi, function (index, value) {
            if (sVal === value) {
                spi.splice(index, 1);
            }
        });
        var str = spi.join(',');
        $levelInput.val(str);
        $(this).parent("span").fadeOut(200);
    });
    $('body').on('click', '.mmtms-admin-reinstall-page', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var slug = $btn.data('slug');
        var pageID = $btn.data('pid');
        var tr = $btn.closest('tr');
        var ajaxdata = {};
        ajaxdata.slug = slug;
        ajaxdata.page_id = pageID;
        ajaxdata.action = 'mmtms_ajax_reinstall_page';
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        $.ajax({
            beforeSend: function () {
                show_tr_loading(tr);
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'good') {
                    tr.html(data.content);
                }
            },
            complete: function () {
                hide_tr_loading(tr);
            }
        });
    });
    /** Ends User Page */
    $('.invoice_business_logo_upload').click(function (e) {
        e.preventDefault();
        var custom_uploader;
        custom_uploader = wp.media({
            title: 'Business Logo',
            button: {
                text: 'Upload Image'
            },
            multiple: false  // Set this to true to allow multiple files to be selected
        })
            .on('select', function () {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $('.business_logo').attr('src', attachment.url);
                $('.business_logo_url').val(attachment.url);

            })
            .open();
    });
    $('body').on('click', '.btn-prev-verification-email', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var target = $btn.data('target');
        var $target = $('body').find(target);
        var ajaxdata = {};
        ajaxdata.type = 'verification';
        ajaxdata.action = 'mmtms_generate_email_preview';
        ajaxdata.mmtms_nonce = mmtms_admin_ajax.ajax_nonce;
        $.ajax({
            beforeSend: function () {
                $target.addClass('show');
                show_admin_lb_loading($target);
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_admin_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {

                $target.find('.mmtms-admin-lb-content').find('.mmtms-preview-email-message').html(data.content.message);
                $target.find('.mmtms-admin-lb-content').find('.mmtms-peh-from-email i').text(data.content.from);
                $target.find('.mmtms-admin-lb-content').find('.mmtms-peh-from-subject i').html(data.content.subject);
            },
            complete: function () {
                hide_admin_lb_loading($target);
            }
        });
    });
});