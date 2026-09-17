import WOW from 'wow.js/dist/wow.min.js';
import 'jquery.scrollbar';
import 'jquery-match-height';
import 'slick-carousel';
import '@fancyapps/fancybox';

let keys = {37: 1, 38: 1, 39: 1, 40: 1};

function preventDefault(e) {
    e.preventDefault();
}

function preventDefaultForScrollKeys(e) {
    if (keys[e.keyCode]) {
        preventDefault(e);
        return false;
    }
}

let supportsPassive = false;
try {
    window.addEventListener("test", null, Object.defineProperty({}, 'passive', {
        get: function () { supportsPassive = true; }
    }));
} catch(e) {}

let wheelOpt = supportsPassive ? { passive: false } : false;
let wheelEvent = 'onwheel' in document.createElement('div') ? 'wheel' : 'mousewheel';

function disableScroll() {
    window.addEventListener('DOMMouseScroll', preventDefault, false); // older FF
    window.addEventListener(wheelEvent, preventDefault, wheelOpt); // modern desktop
    //window.addEventListener('touchmove', preventDefault, wheelOpt); // mobile
    window.addEventListener('keydown', preventDefaultForScrollKeys, false);
}

function enableScroll() {
    window.removeEventListener('DOMMouseScroll', preventDefault, false);
    window.removeEventListener(wheelEvent, preventDefault, wheelOpt);
    //window.removeEventListener('touchmove', preventDefault, wheelOpt);
    window.removeEventListener('keydown', preventDefaultForScrollKeys, false);
}
let lastScrollTop = 0;


$(document).ready(function () {
    $('.scroll').on('click', function(e) {
        $('html, body').stop().animate({
            scrollTop: $($(this).attr('href')).offset().top
        }, 850);
        e.preventDefault();
    });

    $(document).on('click', '.toggle__item-header', function (e) {
        let parent = $(this).closest('.toggle__item');
        if(!parent.hasClass('active')){
            $('.toggle__item').removeClass('active');
            $('.toggle__item-content').slideUp();
            parent.addClass('active');
            parent.find('.toggle__item-content').slideDown();
        }
        e.preventDefault();
    });

    if($('.partners__slider').length){
        $('.partners__slider').slick({
            dots: false,
            prevArrow: '.sc__box-partners .nav__prev',
            nextArrow: '.sc__box-partners .nav__next',
            speed: 600,
            slidesToShow: (($('.partners__item').length >= 5) ? 5 : $('.partners__item').length),
            slidesToScroll: 1,
            infinite: true
        });
    }

    if($('.promo__slider').length){
        $('.nav__count').text(('0' + $('.promo__item').length).slice(-2));

        $('.promo__slider').slick({
            dots: false,
            prevArrow: '.promo__nav-prev',
            nextArrow: '.promo__nav-next',
            speed: 800,
            infinite: true
        });

        $('.promo__slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
            $('.nav__current').text(('0' + (nextSlide + 1)).slice(-2));
        });
    }

    if($('.news__item-title').length){
        $('.news__item-title').matchHeight();
    }

    $('.scroll').on('click', function(e) {
        $('html, body').stop().animate({
            scrollTop: $($(this).attr('href')).offset().top + "px"
        }, 850);
        e.preventDefault();
    });

    if($('.wow').length){
        let wow = new WOW(
            {
                animateClass: 'animated',
                mobile:       true,
                offset:       100,
                callback:     function(box) {

                }
            }
        );
        wow.init();
    }

    $(document).on('keydown', function(e) {
        if($(window).scrollTop() <= $(window).outerHeight() * 0.75){
            if(e.keyCode === 37) {
                $('.promo__slider').slick('slickPrev');
            }
            if(e.keyCode === 39) {
                $('.promo__slider').slick('slickNext');
            }
        }

    });

    $(document).on('click', '.toggle__content-js', function (e) {
        let parent = $(this).closest('.cat__toggle-item');
        if(parent.hasClass('active')){
            parent.removeClass('active');
            parent.find('.cat__toggle-content').slideUp();
        }else{
            $('.cat__toggle-item').removeClass('active');
            $('.cat__toggle-content').slideUp();
            parent.addClass('active');
            parent.find('.cat__toggle-content').slideDown();
        }
        e.preventDefault();
    });

    /*$(document).on('click', '.filter__list a', function (e) {
        if(!$(this).closest('li').hasClass('active')){
            $('.filter__list li').removeClass('active');
            $(this).closest('li').addClass('active');
        }
        e.preventDefault();
    });*/

    if($('.media__slider').length){
        $('.media__slider').each(function () {
           let _this = $(this);
            _this.slick({
                dots: false,
                prevArrow: _this.closest('.media__slider-box').find('.nav__prev'),
                nextArrow: _this.closest('.media__slider-box').find('.nav__next'),
                speed: 600,
                infinite: true
            });
        });
    }

    $(document).on('click', '.tabs__list a', function (e) {
        let parent = $(this).closest('li');
        if(!parent.hasClass('active')){
            $('.tabs__list li').removeClass('active');
            parent.addClass('active');
            $('.tab__pane').removeClass('active');
            $($(this).attr('href')).addClass('active');
        }
        e.preventDefault();
    });

    $(document).on('click', '.video__play', function (e) {
        let video = $(this).attr('data-video');
        let parent = $(this).closest('.video__box');



        if(video.includes('embed')){
            parent.find('.video__frame').html('<iframe id="player" src="' + video + '?enablejsapi=1&autoplay=1&rel=0&showinfo=0"></iframe>');
        }else{
            let video_arr = video.split('?v=');
            parent.find('.video__frame').html('<iframe id="player" src="https://www.youtube.com/embed/' + video_arr[1] + '?enablejsapi=1&autoplay=1&rel=0&showinfo=0"></iframe>');
        }


        parent.find('.video__pl').addClass('active');
        player = new YT.Player( 'player', {
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });

        function onPlayerStateChange(event) {
            switch(event.data) {
                case 0:
                    parent.find('.video__pl').removeClass('active');
                    parent.find('.video__frame').html('');
                    break;
            }
        }


        e.preventDefault();
    });

    $(document).on('click', '.form__tabs a', function (e) {
        let parent = $(this).closest('li');
        if(!parent.hasClass('active')){
            $('.form__tabs li').removeClass('active');
            parent.addClass('active');
            $('.form__tab-pane').removeClass('active');
            $($(this).attr('href')).addClass('active');
        }
        e.preventDefault();
    });

    if($('.wpcf7').length){
        let wpcf7Elm = document.querySelector( '.wpcf7' );
        wpcf7Elm.addEventListener( 'wpcf7mailsent', function( event ) {
            $.fancybox.open({
                src: '#thanks-modal',
                closeExisting: true
            });
            setTimeout(function () {
                $.fancybox.close(true);
            }, 3500);
        }, false );
    }

    $(document).on('click', '.modal__close', function (e) {
        $.fancybox.close(true);
        e.preventDefault();
    });

    $(document).on('click', '.nav__cat-item-header a', function (e) {
        let parent = $(this).closest('.nav__cat-item');
        if(parent.hasClass('active')){
            parent.removeClass('active');
            parent.find('.nav__cat-item-inside').slideUp();
        }else{
            $('.nav__cat-item').removeClass('active');
            $('.nav__cat-item-inside').slideUp();
            parent.addClass('active');
            parent.find('.nav__cat-item-inside').slideDown();
        }
        e.preventDefault();
    });

    if($('.nav__cat-scroll').length){
        $('.nav__cat-scroll').scrollbar();
    }

    $('.has-submenu').mouseenter(function(){
        $('body').addClass('submenu-open-mode');
    });

    $('.has-submenu').mouseleave(function(){
        $('body').removeClass('submenu-open-mode');
    });

    $(document).on('click', '.load-items-js', function (e) {
        let parent = $(this).attr('data-load');
        $('#' + parent).find('.hidden').removeClass('hidden');
        let wrap = $(this).closest('.pager__wrap');
        wrap.remove();

        e.preventDefault()
    });

    $('[data-fancybox]').fancybox({
        beforeShow: function(e) {
            if(e.current.src === '#order-modal'){
                let trigger = $.fancybox.getInstance().current.opts.$orig;
                $('#order-modal .modal__custom-title').text(trigger.attr('data-modal-title'));
                $('#order-modal [name="source"]').val(trigger.attr('data-modal-scr'));
            }
        }
    });
});

function onPlayerReady(event) {
    event.target.playVideo();
}

$(window).scroll(function () {
   let y = $(window).scrollTop();


});