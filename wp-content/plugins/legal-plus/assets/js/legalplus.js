var Legalplus;
Legalplus = {
    init: function () {
        this.initPrivacityListener();
        //this.isCookiesAccepted();
        this.centerPopup();
        this.cookiesDenied();
        this.replaceBannerLink();
        this.deleteAllCookies();
        setTimeout(function(){
            Legalplus.deleteAllCookies();
        }, 1500);
        this.moveCookiesBanner();
        this.initCookiesAceptada();
        jQuery('#create-account_form').on('submit', function(){
            setTimeout(function(){
                Legalplus.initPrivacityListener();
            }, 1500);
        });
        this.setcookie();
    },

    setcookie: function(){
        var date = new Date();
        date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
        var expires = "; expires=" + date.toGMTString();

        document.cookie = 'legalpluscookie=accepted' + expires + "; path=/";
    },

    cookiesDenied: function(){
        jQuery('#legalplus_deny_cookies').on('click', function(e){
            e.preventDefault();
            jQuery.getJSON( '/wp-admin/admin-ajax.php' , {
                    action : 'legalplus_clear_cookies' ,
                    cookie : 'legalplus' } ,
                function( data ) {
                    if(data.success) window.location = 'http://www.google.com';
                });
        });
    },


    replaceBannerLink: function(){
        var elem = jQuery('#legal_cookies_banner_text');
        elem.html(elem.html().replace(/\[legalplus_CONDITIONS_LINK\]/g, '<a href="'+elem.data('cookies_link')+'" target="_blank">política de privacidad y cookies</a>'));
    },

    deleteAllCookies: function() {
        if(jQuery('#legalplus-cookies-banner').data('cookies_accepted') != 'accepted') {
            var cookies = document.cookie.split(";");
            for (var i = 0; i < cookies.length; i++) {
                var cookie = cookies[i];
                var eqPos = cookie.indexOf("=");
                var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
                name = name.replace(/^\ /g,'');
                if(name != 'legalpluscookie'){
                    document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:01 GMT; domain="+document.domain;
                }
            }
        }
    },

    moveCookiesBanner:function() {
        var that = this;
        jQuery('body').prepend(jQuery('#legalplus-cookies-banner'));
        if(jQuery('#legalplus-cookies-banner').data('cookies_accepted') != 'accepted') {
            jQuery('#legalplus-cookies-banner').css('display', 'block');
        }
    },

    initCookiesAceptada: function() {
        jQuery('#legalplus_accept_cookies').on('click', function(event){
            event.preventDefault();
            jQuery('#legalplus-cookies-banner').slideUp();
        });
    },

    isCookiesAccepted: function() {
        var cookies = document.cookie.split(";");
        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i];
            var eqPos = cookie.indexOf("=");
            var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
            name = name.replace(/^\ /g,'');
            if(name == 'legalpluscookie'){
                jQuery('#legalplus-cookies-banner').data('cookies_accepted', 'accepted');
            }
        }
    },

    formsControl: [
        '#form-wysija-2', 'wpcf7-form'
    ],
    ajaxFormControl: [
        '.wpcf7-submit', 'wpcf7-form'
    ],
    confirmed: false,

    initPrivacityListener: function() {
        var that = this;
        jQuery.each( this.formsControl, function( unused, form ) {
            jQuery(form).on('submit', function(ev) {
                if(!that.confirmed) {
                    that.callPopup(form, 'form');
                    ev.preventDefault();
                }
            });
        });
        jQuery.each( this.ajaxFormControl, function( unused, form ) {
            jQuery(form).on('click', function(ev) {
                if(that.confirmed) {
                    return true;
                }
                that.callPopup(form, 'btn');
                return false;
            });
        });
    },

    callPopup: function(form, type){
        var that = this;
        jQuery("#legal-confirm").css("display", "block");

        jQuery("#legal-confirm #legal-accept").on('click', function() {
            jQuery("#legal-confirm").css({"display": "none"});
            if(type == 'form') {
                that.confirmed = true;
                jQuery(form).find('[type="submit"]').click();
            } else if(type == 'btn') {
                that.confirmed = true;
                jQuery('.wpcf7-submit').click();
            }
        });

        jQuery("#legal-confirm #legal-deny").on('click', function() {
            jQuery("#legal-confirm").css({"display": "none"});
        });
    },

    centerPopup: function() {
        jQuery("#legal-confirm").css({"margin-left": "calc(50% - "+String(jQuery('#legal-confirm').width()/2)+"px)"});
        jQuery( window ).resize( function(){
            jQuery("#legal-confirm").css({"margin-left": "calc(50% - "+String(jQuery('#legal-confirm').width()/2)+"px)"});
        });
    }
};
Legalplus.init();
