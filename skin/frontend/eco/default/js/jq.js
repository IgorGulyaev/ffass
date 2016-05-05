jQuery(document).ready(function ($) {

    var activeurl = window.location;
    $('.dropdown-menu.account-nav li a[href="'+activeurl+'"]').parent().addClass('active');

    $('.modal-ajax [data-dismiss="modal"]').on('click', function() {
        $('.modal-ajax').removeClass('in');
        $('.add-cart .btn-cart').prop('disabled', false);
    });

    /* subscribed account-create checkbox fixed */
    $('.account-create label[for="is_subscribed"], .opc-wrapper-opc label[for="is_subscribed"], [for*="remember_me"], .link-tip').each(function(){
        $(this).appendTo($(this).parent().find('.input-box'));
    });

    /* scroll to top */
    $('.btn-scroll-top').on('click', function(e) {
        $('html, body').animate({scrollTop : 0}, 500);
        e.preventDefault();
    });
    /* / */

    var opcRequired = $('.opc .input-text.required-entry, .opc select.validate-select');
    $('body').on('blur', '.opc .input-text.required-entry, .opc select.validate-select', function () {
        $(this).parents('li').prevAll('li').find('.input-text.required-entry, select.validate-select').change();
        $(this).change();
    });
    opcRequired.on('change', function () {
        var $this = $(this);
        var validParent = $this.parent();
        var validType = $this.attr('type');
        var validFailed = '<i class="icon-no valid"></i>';
        var validPassed = '<i class="icon-checkmark valid"></i>';

        var re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;

        validParent.find('.valid').remove();

        if (validType == 'text' && !$this.hasClass('validate-email')) {
            if ($this.val() == '') {
                validParent.append(validFailed);
            } else {
                validParent.append(validPassed);
            }
        }
        if ($this.hasClass('validate-select') == true) {
            if ($this.val() == '') {
                validParent.append(validFailed);
            } else {
                validParent.append(validPassed);
            }
        }
        if ($this.hasClass('validate-email')) {
            if ($this.val() == '' || !re.test($this.val())) {
                validParent.append(validFailed);
            } else {
                validParent.append(validPassed);
            }
        }
        if (validType == 'tel') {
            if ($this.val() == '') {
                validParent.append(validFailed);
            } else {
                validParent.append(validPassed);
            }
        }
        $this.parents('.field, .wide').addClass('passed');
    });

    var ccInput = '#co-payment-form .tab-content .input-text';
    var ccSelect = '#co-payment-form .tab-content .input-text';

    jQuery('body').on('focus', ccSelect, function () {
        jQuery(this).addClass('act');
    });
    jQuery('body').on('blur', ccSelect, function () {
        if (jQuery(this).val() == '') {
            jQuery(this).removeClass('act');
        } else {
            jQuery(this).addClass('act');
        }
    });
    jQuery('body').on('focus', ccInput, function () {
        jQuery(this).siblings('.cc-label').addClass('min');
    });
    jQuery('body').on('blur', ccInput, function () {
        if (jQuery(this).val() == '') {
            jQuery(this).siblings('.cc-label').removeClass('min');
        }
    });

    /* bootstrap tabs cookie */
    var tabId = $('.tabpanel-cookie').attr('id');
    $('.tabpanel-cookie [data-toggle="tab"]').on('shown.bs.tab', function(e){
        $.cookie(tabId, $(e.target).attr('href'));
    });
    var lastTab = $.cookie(tabId);
    if (lastTab) {
        $('.tabpanel-cookie .nav-tabs').children().removeClass('active');
        $('.tabpanel-cookie [href='+ lastTab +']').parents('li:first').addClass('active');
        $('.tabpanel-cookie .tab-content').children().removeClass('active');
        $('.tabpanel-cookie ' + lastTab).addClass('active');
    }
    $('[data-toggle="tab"]').on('shown.bs.tab', function () {
        var target = this.href.split('#');
        $('.nav-tabs a').filter('[href="#'+target[1] +'"]').tab('show');
    });
    $('.tabpanel').on('shown.bs.collapse', function (e) {
        $('.tabpanel .panel-heading a').removeClass('active');
        $(e.target).prev('.panel-heading').find('a').addClass('active');
    });
    /* / */


    $('.cms-page-view .page-title').prependTo('.row.main');
    $('.contacts-index-index .page-title').prependTo('.row.contact-row');

    /* nice alerts + confirm (bootstrap modal) */
    $(document).on('click', '[data-confirm]', function (e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var message = $(this).attr('data-confirm');
        bootbox.confirm(message, function(result) {
            if (result) {
                document.location.href = href;
            }
        });
    });
    window.alert = function(message){
        bootbox.alert(message);
    };
    /* / */

    $(document).mouseup(function (e) {
        var container = $('#ajaxcartpro-add-confirm');
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.attr('aria-expanded', 'false');
        }
    });

    /* remove alerts */
    $('input.input-text, textarea.input-text, select.input-text').on('keyup', function() {
        $(this).parent().find('.validation-advice').fadeOut('200');
    });
    $('select.form-control').on('change', function() {
        $(this).parent().find('.validation-advice').fadeOut('200');
    });
    $('body').on('click', function() {
        $('.messages, .opc-messages').fadeOut('3000');
    });
    /* / */

    /* aids */
    $('textarea').textareaAutoSize();
    $('[data-toggle="popover"]').popover();
    $('input.qty').spinner();
    $('.accordion').on('shown.bs.collapse', function (e) {
        $(e.target).prev('.panel-heading').find('a').addClass('active');
    });
    $('.accordion').on('hidden.bs.collapse', function (e) {
        $(e.target).prev('.panel-heading').find('a').removeClass('active');
    });
    /* / */

    /* modal */

    function modalReposition() {
        var modal = $(this),
            dialog = modal.find('.modal-dialog');
        modal.css('display', 'block');
        dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 2));
    }

    $('.modal.modal-center').on('show.bs.modal', modalReposition);

    $(window).on('resize', function() {
        $('.modal.modal-center:visible').each(modalReposition);
    });
    /**/

    /* form submit */

    $(document).on('submit', '.submit-disabled', function() {
        $(this).find('button[type=submit]').attr('disabled', 'disabled');
    });

    /**/

    /* carousel */

    $('.carousel .next').on('click', function(e) {
        $(this).parent().find('.owl-carousel').trigger('next.owl');
        e.preventDefault();
    });
    $('.carousel .prev').on('click', function(e) {
        $(this).parent().find('.owl-carousel').trigger('prev.owl');
        e.preventDefault();
    });

    $('.section-products .owl-carousel').each(function () {
        if ($(this).find('.products-item').length > 4) {
            var $center = true;
        } else {
            var $center = false;
        }
        $(this).owlCarousel({
            margin: 0,
            loop: true,
            nav: false,
            center: true,
            dots: false,
            thumbs: false,
            responsive: {
                0:{
                    items: 1
                },
                767:{
                    items: 2
                },
                991:{
                    items: 3
                },
                1200:{
                    items: 4
                },
                1500:{
                    items: 5
                }
            }
        });
    });

    /**/

    /* mobile */

    $('.navbar').on('show.bs.collapse', function () {
        $('body').addClass('navbar-active');
    }).on('hide.bs.collapse', function () {
        $('body').removeClass('navbar-active');
    });

    $('.col-left').on('show.bs.collapse', function () {
        $('.button-filter').addClass('active');
    }).on('hide.bs.collapse', function () {
        $('.button-filter').removeClass('active');
    });

    $('.backdrop, .active-navbar').on('click', function() {
        $('.navbar, .col-left').removeClass('in');
        $('body').removeClass('navbar-active');
    });

    $(document).on('click', '.top-link-cart', function () {
        dropdownActive();
        $('body').addClass('navbar-active');
    });

    $('.irs-from').bind('DOMSubtreeModified', function () {
        $('input.price-from').val($('.irs-from').html()).change();
        $('#priceSubmit').trigger('click');
        console.log('ranged');
    });
    $('.irs-to').bind('DOMSubtreeModified', function () {
        $('input.price-to').val($('.irs-to').html()).change();
        $('#priceSubmit').trigger('click');
        console.log('ranged');
    });

    /*function dropdownActive() {
        if(!$('.top-link-cart').parent('.dropdown').hasClass('open')) {
            $('body').addClass('navbar-active');
        } else {
            $('body').removeClass('navbar-active');
        }
    }*/

    $('.btn-back').on('click', function(e) {
        e.preventDefault();
        history.back();
    });

    $('.ratings-bar a[role="tab"]').click(function () {
        var tabTop = $('.section-tab').offset().top;
        $('html, body').animate({
            scrollTop: tabTop
        },500, 'easeOutExpo');

    });

    /**/

    /* responsive */

    var windowWidth = $(window).width();
    responsive();
    $(window).resize(responsive);

    function responsive() {
        if(window.matchMedia('(max-width: 767px)').matches) {
            $(document).on('click', '.navbar .navbar-nav .dropdown > a', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).parent().siblings().removeClass('open');
                $(this).parent().toggleClass('open');
            });
            $('.cart .cart-row').on('swipeleft', function(e) {
                e.preventDefault();
                $(this).addClass('remove');
            }).on('swiperight', function(e) {
                e.preventDefault();
                $(this).removeClass('remove');
            });
            $('.navbar').on('affix.bs.affix affix-top.bs.affix', function () {
                $('body').css('padding-top', 0);
            });
            $('footer .footer-left').appendTo('.navbar .navbar-footer');

            /* Accordeons */
            $('body:not(.catalog-product-view,  .checkout-onepage-index) .nav.nav-tabs').tabCollapse();
            /* End Accordeons */
        } else if(window.matchMedia('(min-width: 768px)').matches) {
            $('[data-hover="dropdown"]').dropdownHover();
            $('.navbar').on('affix.bs.affix affix-top.bs.affix', function (e) {
                var padding = e.type === 'affix' ? $(this).height() : '';
                $('body').css('padding-top', padding);
            });
            $('.navbar .footer-left').appendTo('.row-footer .first');
        }
    }
    /**/
});
(function ($, window, undefined) {
    var $allDropdowns = $();
    $.fn.dropdownHover = function (options) {
        $allDropdowns = $allDropdowns.add(this.parent());
        return this.each(function () {
            var $this = $(this),
                $parent = $this.parent(),
                defaults = {
                    delay: 100,
                    hoverDelay: 100,
                    instantlyCloseOthers: true
                },
                data = {
                    delay: $(this).data('delay'),
                    hoverDelay: $(this).data('hover-delay'),
                    instantlyCloseOthers: $(this).data('close-others')
                },
                showEvent   = 'show.bs.dropdown',
                hideEvent   = 'hide.bs.dropdown',
                settings = $.extend(true, {}, defaults, options, data),
                timeout, timeoutHover;
            $parent.hover(function (event) {
                openDropdown(event);
            }, function () {
                window.clearTimeout(timeoutHover);
                timeout = window.setTimeout(function () {
                    $this.attr('aria-expanded', 'false');
                    $parent.removeClass('open');
                    $this.trigger(hideEvent);
                }, settings.delay);
            });
            $this.hover(function (event) {
                openDropdown(event);
            });
            $parent.find('.dropdown').each(function (){
                var $this = $(this);
                var subTimeout;
                $this.hover(function () {
                    window.clearTimeout(subTimeout);
                    $this.addClass('open');
                }, function () {
                    subTimeout = window.setTimeout(function () {
                        $this.removeClass('open');
                    }, 0);
                });
            });
            function openDropdown(event) {
                window.clearTimeout(timeout);
                window.clearTimeout(timeoutHover);
                timeoutHover = window.setTimeout(function () {
                    $allDropdowns.find(':focus').blur();
                    if(settings.instantlyCloseOthers === true)
                        $allDropdowns.removeClass('open');
                    window.clearTimeout(timeoutHover);
                    $this.attr('aria-expanded', 'true');
                    $parent.addClass('open');
                    $this.trigger(showEvent);
                }, settings.hoverDelay);
            }
        });
    };
})(jQuery, window);