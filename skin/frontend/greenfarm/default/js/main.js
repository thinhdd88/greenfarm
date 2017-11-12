;(function ($) {
    'use strict';
    var site = {
        homeBanner: function(){
            $('.home-banner-slider').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                dots: true,
                //autoplay: true,
                autoplaySpeed: 5000,
            });
        },

        setFooter: function(){
            if( $(document).outerHeight() < window.innerHeight) {
                var contentHeight =  $(document).outerHeight() - ($('.header-grid').outerHeight() + $('#header').outerHeight() + $('.footer').outerHeight() );
                $('.main').css({
                    'min-height': contentHeight
                })
            }
        },

        menuMobile: function(){
            $('#header-nav li').each(function(){
                $(this).on('click', function(){
                    $(this).find('.sub-menu').toggle(300);
                });
            });
        },

        init: function () {
            this.homeBanner();
            this.menuMobile();
        },
    };

    $(function () {
        site.init();
    });

    $(window).on('load', function(){
        site.setFooter();
    });
}(jQuery));
