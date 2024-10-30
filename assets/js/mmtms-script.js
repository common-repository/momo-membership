/**jslint browser:true, devel:true */
/*global jQuery*/
/*global define */
/*global window */
/*jslint this*/
/*global tinymce*/
/*global document*/
/*global mmtms_ajax*/
/**
 * Frontend jQuery functions
 */
jQuery(document).ready(function ($) {
    'use strict';
    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return regex.test(email);
    }
    function changeTab(ref, $parent) {
        $parent.find('.mmtms-tabcontent.active').removeClass('active');
        $parent.find('.mmtms-tablinks.active').removeClass('active');
        $parent.find(ref).addClass('active');
        $parent.find('[data-ref="' + ref + '"]').addClass('active');
        $("html, body").animate({
            scrollTop: 0
        }, 1000);
    }
    function mmtmsRemoveClass($form, cssClass) {
        $form.find('input').each(function () {
            $(this).removeClass(cssClass);
        });
    }
    $('.mmtms-fe-lb-close').on('click', function (e) {
        e.preventDefault();
        var $parent = $(this).closest('.mmtms-fe-lightbox');
        $parent.removeClass('show');
        $('body').removeClass('mmtms-fe-lb-body-overflow');
    });
    $('body').on('click', '.fe-edit-p-name', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var uid = $btn.data('uid');
        var $parent = $btn.closest('.mmtms-fe-user-profile');
        var $loading = $parent.find('.mrf-loading');
        var $lightbox = $('body').find('.mmtms-fe-lightbox');
        var ajaxdata = {};
        ajaxdata.uid = uid;
        ajaxdata.mmtms_nonce = mmtms_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_generate_name_change_form';
        $.ajax({
            beforeSend: function () {
                $loading.css('display', 'block');
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                var $container = $lightbox.find('.mmtms-fe-lb-container');
                if (data.status === 'good') {
                    $container.html(data.content);
                    $lightbox.addClass('show');
                    $('body').addClass('mmtms-fe-lb-body-overflow');
                }
            },
            complete: function () {
                $loading.css('display', 'none');
            }
        });
    });
    $('body').on('click', '.fe-edit-p-image', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var uid = $btn.data('uid');
        var imageUrl = $btn.data('image');
        var $parent = $btn.closest('.mmtms-fe-user-profile');
        var $loading = $parent.find('.mrf-loading');
        var $lightbox = $('body').find('.mmtms-fe-lightbox');
        var ajaxdata = {};
        ajaxdata.uid = uid;
        ajaxdata.mmtms_nonce = mmtms_ajax.ajax_nonce;
        ajaxdata.image_url = imageUrl;
        ajaxdata.action = 'mmtms_generate_image_change_form';
        $.ajax({
            beforeSend: function () {
                $loading.css('display', 'block');
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                var $container = $lightbox.find('.mmtms-fe-lb-container');
                if (data.status === 'good') {
                    $container.html(data.content);
                    $lightbox.addClass('show');
                    $('body').addClass('mmtms-fe-lb-body-overflow');
                }
            },
            complete: function () {
                $loading.css('display', 'none');
            }
        });
    });
    $('body').on('click', '.mmtms-fe-lb-footer .btn-change-iu', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $form = $btn.closest('.mmtms-fe-lb-content');
        var $parent = $form.closest('.mmtms-fe-lb-wrapper');
        var $loading = $parent.find('.mmtms-fe-lb-loader');
        var userID = $form.find('input[name="fe_cp_form_user_id"]').val();
        var imageUrl = $form.find('input[name="fe_cp_form_user_image_url"]').val();
        if (imageUrl === '') {
            $form.find('input[name="fe_cp_form_user_image_url"]').select().focus();
            return;
        }
        var ajaxdata = {};
        ajaxdata.user_id = userID;
        ajaxdata.image_url = imageUrl;
        ajaxdata.mmtms_nonce = mmtms_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_change_user_image_url';
        $.ajax({
            beforeSend: function () {
                $loading.css('display', 'block');
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'good') {
                    $('body').find('.mmtms-profile-img').find('img').attr('src', imageUrl);
                    $('.mmtms-fe-lb-close').trigger('click');
                }
            },
            complete: function () {
                $loading.css('display', 'none');
            }
        });
    });
    $('body').on('click', '.mmtms-fe-lb-footer .btn-change-name', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $form = $btn.closest('.mmtms-fe-lb-content');
        var $parent = $form.closest('.mmtms-fe-lb-wrapper');
        var $loading = $parent.find('.mmtms-fe-lb-loader');
        var firstName = $form.find('input[name="fe_cn_form_first_name"]').val();
        var lastName = $form.find('input[name="fe_cn_form_last_name"]').val();
        var userID = $form.find('input[name="fe_cn_form_user_id"]').val();
        if (firstName === '') {
            $form.find('input[name="fe_cn_form_first_name"]').focus().select();
            return;
        }
        if (lastName === '') {
            $form.find('input[name="fe_cn_form_last_name"]').focus().select();
            return;
        }
        var ajaxdata = {};
        ajaxdata.first_name = firstName;
        ajaxdata.last_name = lastName;
        ajaxdata.user_id = userID;
        ajaxdata.mmtms_nonce = mmtms_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_change_user_fl_name';
        $.ajax({
            beforeSend: function () {
                $loading.css('display', 'block');
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'good') {
                    $('body').find('.mmtms-profile-name').find('span').html(data.fullname);
                    $('.mmtms-fe-lb-close').trigger('click');
                }
            },
            complete: function () {
                $loading.css('display', 'none');
            }
        });
    });
    $('body').on('click', '.fe-edit-p-email', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var uid = $btn.data('uid');
        var $parent = $btn.closest('.mmtms-tab-container');
        var $loading = $parent.find('.mrf-loading');
        var $lightbox = $('body').find('.mmtms-fe-lightbox');
        var ajaxdata = {};
        ajaxdata.uid = uid;
        ajaxdata.mmtms_nonce = mmtms_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_generate_email_change_form';
        $.ajax({
            beforeSend: function () {
                $loading.css('display', 'block');
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                var $container = $lightbox.find('.mmtms-fe-lb-container');
                if (data.status === 'good') {
                    $container.html(data.content);
                    $lightbox.addClass('show');
                    $('body').addClass('mmtms-fe-lb-body-overflow');
                }
            },
            complete: function () {
                $loading.css('display', 'none');
            }
        });
    });
    $('body').on('click', '.mmtms-fe-lb-footer .btn-change-email', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $form = $btn.closest('.mmtms-fe-lb-content');
        var $parent = $form.closest('.mmtms-fe-lb-wrapper');
        var $msgbox = $parent.find('.mmtms-fe-lb-msgbox');
        var $loading = $parent.find('.mmtms-fe-lb-loader');
        var userID = $form.find('input[name="fe_ce_form_user_id"]').val();
        var oldEmail = $form.find('input[name="fe_ce_form_user_email"]').val();
        var newEmail = $form.find('input[name="fe_ce_form_user_email_new"]').val();
        if (oldEmail === newEmail) {
            $msgbox.html(mmtms_ajax.same_email_address);
            $msgbox.css('display', 'block');
            $form.find('input[name="fe_ce_form_user_email_new"]').focus().select();
            return;
        }
        if (newEmail === '') {
            $msgbox.html(mmtms_ajax.requierd_field_msg);
            $msgbox.css('display', 'block');
            $form.find('input[name="fe_ce_form_user_email_new"]').focus().select();
            return;
        }
        if (!isEmail(newEmail)) {
            $msgbox.html(mmtms_ajax.registration_email_err);
            $msgbox.css('display', 'block');
            $form.find('input[name="fe_ce_form_user_email_new"]').focus().select();
            return;
        }
        var ajaxdata = {};
        ajaxdata.user_id = userID;
        ajaxdata.new_email = newEmail;
        ajaxdata.action = 'mmtms_change_user_email';
        ajaxdata.mmtms_nonce = mmtms_ajax.ajax_nonce;
        $.ajax({
            beforeSend: function () {
                $loading.css('display', 'block');
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'good') {
                    $('body').find('.mmtms-profile-email').find('em').html(data.email);
                    $('.mmtms-fe-lb-close').trigger('click');
                } else if (data.status === 'bad') {
                    $msgbox.html(data.msg);
                    $msgbox.css('display', 'block');
                }
            },
            complete: function () {
                $loading.css('display', 'none');
            }
        });
    });
    $('body').on('click', '.fe-edit-p-password', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var uid = $btn.data('uid');
        var $parent = $btn.closest('.mmtms-tab-container');
        var $loading = $parent.find('.mrf-loading');
        var $lightbox = $('body').find('.mmtms-fe-lightbox');
        var ajaxdata = {};
        ajaxdata.uid = uid;
        ajaxdata.mmtms_nonce = mmtms_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_generate_password_change_form';
        $.ajax({
            beforeSend: function () {
                $loading.css('display', 'block');
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                var $container = $lightbox.find('.mmtms-fe-lb-container');
                if (data.status === 'good') {
                    $container.html(data.content);
                    $lightbox.addClass('show');
                    $('body').addClass('mmtms-fe-lb-body-overflow');
                    //$container.find('input[name="fe_cp_form_user_pwd"]').foucs().select();
                }
            },
            complete: function () {
                $loading.css('display', 'none');
            }
        });
    });
    $('body').on('click', '.mmtms-fe-lb-footer .btn-change-password', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $form = $btn.closest('.mmtms-fe-lb-content');
        var $parent = $form.closest('.mmtms-fe-lb-wrapper');
        var $msgbox = $parent.find('.mmtms-fe-lb-msgbox');
        var $loading = $parent.find('.mmtms-fe-lb-loader');
        var userID = $form.find('input[name="fe_cp_form_user_id"]').val();
        var pwd = $form.find('input[name="fe_cp_form_user_pwd"]').val();
        var pwd2 = $form.find('input[name="fe_cp_form_user_pwd2"]').val();
        if (pwd === '') {
            $msgbox.html(mmtms_ajax.requierd_field_msg);
            $msgbox.css('display', 'block');
            $form.find('input[name="fe_cp_form_user_pwd"]').focus().select();
            return;
        }
        if (pwd2 === '') {
            $msgbox.html(mmtms_ajax.requierd_field_msg);
            $msgbox.css('display', 'block');
            $form.find('input[name="fe_cp_form_user_pwd2"]').focus().select();
            return;
        }
        if (pwd !== pwd2) {
            $msgbox.html(mmtms_ajax.registration_password_err);
            $msgbox.css('display', 'block');
            $form.find('input[name="fe_cp_form_user_pwd"]').focus().select();
            return;
        }
        var ajaxdata = {};
        ajaxdata.user_id = userID;
        ajaxdata.password = pwd;
        ajaxdata.action = 'mmtms_change_user_password';
        ajaxdata.mmtms_nonce = mmtms_ajax.ajax_nonce;
        $.ajax({
            beforeSend: function () {
                $loading.css('display', 'block');
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'good') {
                    $('.mmtms-fe-lb-close').trigger('click');
                } else if (data.status === 'bad') {
                    $msgbox.html(data.msg);
                    $msgbox.css('display', 'block');
                }
            },
            complete: function () {
                $loading.css('display', 'none');
            }
        });
    });
    $('body').on('click', '.user-subs-delete', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var userID = $btn.data('uid');
        var levelSlug = $btn.data('slug');
        var $parent = $btn.closest('.mmtms-tab-container');
        var $loading = $parent.find('.mrf-loading');
        var ajaxdata = {};
        ajaxdata.user_id = userID;
        ajaxdata.level_slug = levelSlug;
        ajaxdata.action = 'mmtms_remove_level_by_slug_uid';
        ajaxdata.mmtms_nonce = mmtms_ajax.ajax_nonce;
        $.ajax({
            beforeSend: function () {
                $loading.css('display', 'block');
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                $parent.find('.mmtms-table-container').html(data.content);
            },
            complete: function () {
                $loading.css('display', 'none');
            }
        });
    });
    $('body').on('click', '.mmtms_pe_oi', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var userID = $btn.data('uid');
        var $parent = $btn.closest('.mmtms-fe-user-profile');
        var $loading = $parent.find('.mrf-loading');
        var $lightbox = $('body').find('.mmtms-fe-lightbox');
        var ajaxdata = {};
        ajaxdata.user_id = userID;
        ajaxdata.mmtms_nonce = mmtms_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_display_oi_edit_form';
        $.ajax({
            beforeSend: function () {
                $loading.css('display', 'block');
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                var $container = $lightbox.find('.mmtms-fe-lb-container');
                if (data.status === 'good') {
                    $container.html(data.content);
                    $lightbox.addClass('show');
                    $('body').addClass('mmtms-fe-lb-body-overflow');
                }
            },
            complete: function () {
                $loading.css('display', 'none');
            }
        });
    });
    $('body').on('click', '.btn-change-oe', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $form = $btn.closest('.mmtms-fe-lb-content');
        var $parent = $form.closest('.mmtms-fe-lb-wrapper');
        var $loading = $parent.find('.mmtms-fe-lb-loader');
        var userID = $form.find('input[name="fe_cp_form_user_id"]').val();
        var address = $form.find('input[name="fe_cp_form_user_address"]').val();
        var city = $form.find('input[name="fe_cp_form_user_city"]').val();
        var state = $form.find('input[name="fe_cp_form_user_state"]').val();
        var zip = $form.find('input[name="fe_cp_form_user_zip"]').val();
        var country = $form.find('select[name="fe_cp_form_user_country"]').val();
        var phone = $form.find('input[name="fe_cp_form_user_phone"]').val();
        var about = $form.find('textarea[name="fe_cp_form_user_about"]').val();
        var ajaxdata = {};
        ajaxdata.user_id = userID;
        ajaxdata.address = address;
        ajaxdata.city = city;
        ajaxdata.state = state;
        ajaxdata.zip = zip;
        ajaxdata.country = country;
        ajaxdata.phone = phone;
        ajaxdata.about = about;
        ajaxdata.action = 'mmtms_change_user_oi';
        ajaxdata.mmtms_nonce = mmtms_ajax.ajax_nonce;
        $.ajax({
            beforeSend: function () {
                $loading.css('display', 'block');
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'good') {
                    var $content = $('body').find('.mmtms_p_oi');
                    $content.html(data.content);
                    $('.mmtms-fe-lb-close').trigger('click');
                }
            },
            complete: function () {
                $loading.css('display', 'none');
            }
        });
    });
    $('.mmtms-tablinks').on('click', function (e) {
        e.preventDefault();
        var ref = $(this).data('ref');
        var $parent = $(this).closest('.mmtms-tab-container');
        changeTab(ref, $parent);
        window.location.hash = ref;
    });
    function mmtms_assign_level_to_user(data, $form) {
        var $parent = $form.closest('.mmtms-registration-form-wrapper');
        var $loading = $parent.find('.mrf-loading');
        var ajaxdata = {};
        ajaxdata.uid = data.uid;
        ajaxdata.level = data.level;
        ajaxdata.mmtms_nonce = mmtms_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_fe_assign_new_user_level';
        $.ajax({
            beforeSend: function () {
                $loading.css('display', 'block');
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'good') {
                    $parent.html(data.content).show();
                }
            },
            complete: function () {
                $loading.css('display', 'none');
            }
        });
    }
    $('body').on('click', '.mrf-footer-signup', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $form = $btn.closest('.mmtms-registration-form');
        var $parent = $btn.closest('.mmtms-registration-form-wrapper');
        var $loading = $parent.find('.mrf-loading');
        var errors = 0;
        var ajaxdata = {};
        var ret = false;
        $form.find('input').each(function () {
            if ($(this).val() === '') {
                errors = errors + 1;
                $(this).focus().select();
                $form.find('.mrf-err-box').html(mmtms_ajax.requierd_field_msg).css('display', 'block');
                ret = false;
            } else {
                $form.find('.mrf-err-box').html('').css('display', 'none');
                ajaxdata[$(this).attr('name')] = $(this).val();
                ret = true;
            }
            if (ret === false) {
                $parent.effect('shake');
                return false;
            }
        });
        if (!isEmail(ajaxdata.mrf_i_email)) {
            $form.find('.mrf-err-box').html(mmtms_ajax.registration_email_err).css('display', 'block');
            $form.find('input[name="mrf_i_email"]').focus().select();
            $parent.effect('shake');
            return false;
        }
        if (ajaxdata.mrf_i_password !== ajaxdata.mrf_i_cpassword) {
            $form.find('.mrf-err-box').html(mmtms_ajax.registration_password_err).css('display', 'block');
            $form.find('input[name="mrf_i_password"]').focus().select();
            $parent.effect('shake');
            return false;
        }
        ajaxdata.mmtms_nonce = mmtms_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_fe_add_new_user';
        $.ajax({
            beforeSend: function () {
                $loading.css('display', 'block');
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'bad') {
                    switch (data.mid) {
                    case 1:
                        $parent.effect('shake');
                        $form.find('.mrf-err-box').html(data.msg).css('display', 'block');
                        $form.find('input[name="mrf_i_username"]').focus().select();
                        break;
                    case 2:
                        $parent.effect('shake');
                        $form.find('.mrf-err-box').html(data.msg).css('display', 'block');
                        $form.find('input[name="mrf_i_email"]').focus().select();
                        break;
                    }
                } else if (data.status === 'good') {
                    var userID = data.uid;
                    var $paymentForm = $parent.find('.mmtms_payment_form');
                    if (ajaxdata.mmtms_selected_plan && 'free' !== data.billing ) {
                        var ajaxdataa = {};
                        ajaxdataa.user_id = userID;
                        ajaxdataa.level_slug = ajaxdata.mmtms_selected_plan;
                        ajaxdataa.mmtms_selected_plan = ajaxdata.mmtms_selected_plan;
                        ajaxdataa.mmtms_nonce = mmtms_ajax.ajax_nonce;
                        ajaxdataa.action = 'mmtms_fe_display_payment_form';
                        $.ajax({
                            beforeSend: function () {
                                $loading.css('display', 'block');
                            },
                            type: 'POST',
                            dataType: 'json',
                            url: mmtms_ajax.ajaxurl,
                            data: ajaxdataa,
                            success: function (data) {
                                $paymentForm.html(data.content).show();
                            }
                        });
                    } else if ('free' === data.billing) {
                        mmtms_assign_level_to_user(data, $form);
                    }
                    $form.css('display', 'none');
                }
            },
            complete: function () {
                $loading.css('display', 'none');
            }
        });
    });
    var animating;
    $('body').on('click', '.mslb-loggedin-subs', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var levelSlug = $btn.data('mls');
        var userID = $btn.data('uid');
        var $parent = $btn.closest('.mmtms-payment-form-wrapper');
        var $form = $parent.find('.mmtms-subscription-plan');
        var $paymentForm = $parent.find('.mmtms_payment_form');
        var $loading = $parent.find('.mrf-loading');
        var ajaxdata = {};
        ajaxdata.user_id = userID;
        ajaxdata.level_slug = levelSlug;
        ajaxdata.mmtms_nonce = mmtms_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_generate_loggedin_payment_form';
        $.ajax({
            beforeSend: function () {
                $loading.css('display', 'block');
                $('body').trigger('mmtms_add_overflow');
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'good') {
                    $paymentForm.html(data.content);
                }
            },
            complete: function () {
                $paymentForm.show();
                $form.show();
                $form.animate({opacity: 0}, {
                    step: function (now) {
                        var scale = 1 - (1 - now) * 0.2;
                        var left = (now * 50) + "%";
                        var opacity = 1 - now;
                        $form.css({
                            'transform': 'scale(' + scale + ')',
                            'position': 'absolute'
                        });
                        $paymentForm.css({'left': left, 'opacity': opacity});
                    },
                    duration: 800,
                    complete: function () {
                        $form.hide();
                        animating = false;
                    }
                });
                $loading.css('display', 'none');
                $('body').trigger('mmtms_add_overflow_');
            }
        });
    });
    $('body').on('click', '.mslb-sun', function (e) {
        e.preventDefault();
        if (animating) {
            return false;
        }
        animating = true;
        var $btn = $(this);
        var $form = $btn.closest('.mmtms-subscription-plan');
        var $parent = $form.closest('.mmtms-registration-form-wrapper');
        var $next_form = $parent.find('.mmtms-registration-form');
        var $loading = $parent.find('.mrf-loading');
        var levelSlug = $btn.data('mls');
        var ajaxdata = {};
        ajaxdata.level_slug = levelSlug;
        ajaxdata.mmtms_nonce = mmtms_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_generate_payment_info_on_form';
        $.ajax({
            beforeSend: function () {
                $loading.css('display', 'block');
                $('body').trigger('mmtms_add_overflow');
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'good') {
                    $next_form.find('.mrf-payment-type').html(data.content);
                    /* $next_form.find('.mmtms-registration-form').html(data.form); */
                }
            },
            complete: function () {
                $next_form.show();
                $form.animate({opacity: 0}, {
                    step: function (now) {
                        var scale = 1 - (1 - now) * 0.2;
                        var left = (now * 50) + "%";
                        var opacity = 1 - now;
                        $form.css({
                            'transform': 'scale(' + scale + ')',
                            'position': 'absolute'
                        });
                        $next_form.css({'left': left, 'opacity': opacity});
                    },
                    duration: 800,
                    complete: function () {
                        $form.hide();
                        animating = false;
                    }
                });
                $loading.css('display', 'none');
                $('body').trigger('mmtms_add_overflow_');
            }
        });
    });
    $('body').on('click', '.mmtms-mpt-btn', function (e) {
        if (animating) {
            return false;
        }
        animating = true;
        e.preventDefault();
        var $btn = $(this);
        var $form = $btn.closest('.mmtms-registration-form');
        var $parent = $form.closest('.mmtms-registration-form-wrapper');
        var $prev_form = $parent.find('.mmtms-subscription-plan');
        $prev_form.show();
        $form.animate({opacity: 0}, {
            step: function (now) {
                var scale = 0.8 + (1 - now) * 0.2;
                var left = ((1 - now) * 50) + "%";
                var opacity = 1 - now;
                $form.css({
                    'left': left
                });
                $prev_form.css({'transform': 'scale(' + scale + ')', 'opacity': opacity, 'position': 'relative'});
            },
            duration: 800,
            complete: function () {
                $form.hide();
                animating = false;
            }
        });
    });
    $('body').on('click', '.mmtms-submit-pwd-reset', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $parent = $btn.closest('.mmtms-pwd-reset-form');
        var $form = $btn.closest('#mmtms-pwd-reset-form');
        var $msgBox = $parent.find('.mmtms-fe-msg');
        var $loading = $parent.find('.mlf-loading');
        var userOrEmail = $form.find('input[name="mmtms_pr_ue"]').val();
        if (userOrEmail === '') {
            $form.find('input[name="mmtms_pr_ue"]').effect('shake', {times: 3}, 800);
            $form.find('input[name="mmtms_pr_ue"]').addClass('mmtms-input-highlight');
            $form.find('input[name="mmtms_pr_ue"]').focus().select();
            return;
        }
        var ajaxdata = {};
        ajaxdata.user_login = userOrEmail;
        ajaxdata.mmtms_nonce = mmtms_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_fe_reset_password';
        $.ajax({
            beforeSend: function () {
                $loading.css('display', 'block');
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'bad') {
                    $msgBox.html(data.msg);
                    $msgBox.addClass('warning');
                    $msgBox.css('display', 'block');
                    $form.find('input[name="mmtms_pr_ue"]').focus().select();
                } else if (data.status === 'good') {
                    $msgBox.html(data.msg);
                    $msgBox.addClass('success');
                    $msgBox.css('display', 'block');
                    $form.find('input[name="mmtms_pr_ue"]').val('');
                }
            },
            complete: function () {
                $loading.css('display', 'none');
            }
        });
    });
    $('body').on('keydown', 'input[name="mmtms_login_name"]', function (e) {
        if (e.which === '13') {
            $('.mmtms-submit-login').trigger('click');
        }
    });
    $('body').on('keydown', 'input[name="mmtms_login_password"]', function (e) {
        if (e.which === '13') {
            $('.mmtms-submit-login').trigger('click');
        }
    });
    $('body').on('click', '.mmtms-submit-login', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $parent = $btn.closest('.mmtms-login-form');
        var $form = $btn.closest('#mmtms-login-form');
        var $msgbox = $parent.find('.mmtms-login-msg');
        var $loading = $parent.find('.mlf-loading');
        var referer = $parent.data('referer');
        $form.find('input[name="mmtms_login_name"]').removeClass('mmtms-input-highlight');
        $form.find('input[name="mmtms_login_password"]').removeClass('mmtms-input-highlight');
        var username = $form.find('input[name="mmtms_login_name"]').val();
        var password = $form.find('input[name="mmtms_login_password"]').val();
        var rm = $form.find('input[name="mmtms_login_rememberme"]').val();
        if (username === '') {
            $form.find('input[name="mmtms_login_name"]').effect('shake', {times: 3}, 800);
            $form.find('input[name="mmtms_login_name"]').addClass('mmtms-input-highlight');
            $form.find('input[name="mmtms_login_name"]').focus().select();
            return;
        }
        if (password === '') {
            $form.find('input[name="mmtms_login_password"]').effect('shake', {times: 3}, 800);
            $form.find('input[name="mmtms_login_password"]').addClass('mmtms-input-highlight');
            $form.find('input[name="mmtms_login_password"]').focus().select();
            return;
        }
        var ajaxdata = {};
        ajaxdata.username = username;
        ajaxdata.password = password;
        ajaxdata.referer = referer;
        ajaxdata.rm = rm;
        ajaxdata.mmtms_nonce = mmtms_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_fe_login_user';
        $.ajax({
            beforeSend: function () {
                $loading.css('display', 'block');
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'bad') {
                    $msgbox.html(data.content);
                    $msgbox.css('display', 'block');
                    $form.find('input[name="mmtms_login_name"]').focus().select();
                } else if (data.status === 'good') {
                    if (referer !== '' || data.referer !== false) {
                        document.location.href = data.referer;
                    } else {
                        document.location.href = data.login_redirect;
                    }
                }
            },
            complete: function () {
                $loading.css('display', 'none');
            }
        });
    });
    $('body').on('keydown', 'input[name="mmtmsw-username"]', function (e) {
        if (e.which === '13') {
            $('.btn-mmtms-widget-login').trigger('click');
        }
    });
    $('body').on('keydown', 'input[name="mmtmsw-password"]', function (e) {
        if (e.which === '13') {
            $('.btn-mmtms-widget-login').trigger('click');
        }
    });
    $('body').on('click', '.btn-mmtms-widget-login', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $form = $btn.closest('.widget-login-form');
        var $loading = $form.find('.mlf-loading');
        var userOrEmail = $form.find('input[name="mmtmsw-username"]').val();
        var password = $form.find('input[name="mmtmsw-password"]').val();
        if (userOrEmail === '') {
            $form.effect('shake');
            $form.find('input[name="mmtmsw-username"]').focus().addClass('mmtms-focus');
            return;
        }
        if (password === '') {
            $form.effect('shake');
            $form.find('input[name="mmtmsw-password"]').focus().addClass('mmtms-focus');
            return;
        }
        mmtmsRemoveClass($form, 'mmtms-focus');
        var ajaxdata = {};
        ajaxdata.user_email = userOrEmail;
        ajaxdata.password = password;
        ajaxdata.mmtms_nonce = mmtms_ajax.ajax_nonce;
        ajaxdata.action = 'mmtms_fewidget_login_user';
        $.ajax({
            beforeSend: function () {
                $loading.css('display', 'block');
            },
            type: 'POST',
            dataType: 'json',
            url: mmtms_ajax.ajaxurl,
            data: ajaxdata,
            success: function (data) {
                if (data.status === 'bad') {
                    $form.find('.mmtms-fe-msg').html(data.content).addClass('warning').css('display', 'block');
                    $form.find('input[name="mmtmsw-username"]').select().focus();
                } else {
                    $form.closest('aside').html(data.content);
                }
            },
            complete: function () {
                $loading.css('display', 'none');
            }
        });
    });
    $('body').on('mmtms_add_overflow', function () {
        $('body').addClass('mmtms_overflow');
        $('html').addClass('mmtms_overflow');
    });
    $('body').on('mmtms_add_overflow', function () {
        $('body').removeClass('mmtms_overflow');
        $('html').removeClass('mmtms_overflow');
    });
});