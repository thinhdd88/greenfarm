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

        init: function () {
            this.homeBanner();
        },
    };

    $(function () {
        site.init();
    });

    $(window).on('load', function(){
        site.setFooter();
    });
}(jQuery));
