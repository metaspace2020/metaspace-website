/*ignore jslint start*/
requirejs(['jquery'], function( $ ) {
    'use strict';

    var $window   = $(window);
    var $document = $(document);
    var $body     = $('body');
    var $content  = $('.js-content-start');
    var $header   = $('.b-header');
    var active    = 'active';
    var contentOffset;
    var offset    = [];
    var winHash   = window.location.hash;

    if (winHash === '#contacts') {
        console.log($('.l-wrapper').height());
        setTimeout(function() {
            console.log($('.l-wrapper').height());
            $window.scrollTop($('.l-wrapper').height());
        }, 300);
    }

    function getTltPos() {
        $('.js-title').each(function() {
            var $this      = $(this);
            var eachOffset = $this.offset().top;

            offset.push(eachOffset);

            return offset;
        });
    }

    setTimeout(function() {
        contentOffset = $content.offset().top;

        getTltPos();
        $window.scroll();
    }, 300);

    (function($mainScreen) {
        if (!$mainScreen.length) {
            return;
        }
        var winOffset = $window.scrollTop();
        var $title    = $mainScreen.find('.js-main-screen__tlt');
        var $block    = $mainScreen.find('.js-main-screen__block');
        var $btn      = $mainScreen.find('.js-main-screen__btn');
        var scrollStatus = true;
        var animateStatus = false;

        if (winOffset > 0) {
            skipAnimation();
        } else {
            $mainScreen.on('wheel', onWheel);
        }

        function skipAnimation() {
            $title.removeClass('blured');
            $block.addClass('animate');

            $btn.css({
                display : 'inline-block',
                opacity: 1
            });

            $mainScreen.addClass('animate');

            scrollStatus = false;
        }

        function onWheel(e) {
            var delta = e.deltaY || e.detail || e.wheelDelta;

            if (scrollStatus) {
                e.preventDefault();

                if (!animateStatus) {
                    animateMain();
                }
            }
        }

        function animateMain() {
            animateStatus = true;
            $mainScreen.addClass('animate');
            $title.removeClass('blured');

            setTimeout(function() {
                $block.addClass('animate');

                setTimeout(function() {
                    $btn.css('display', 'inline-block').stop().animate({
                        opacity: 1
                    }, function() {
                        scrollStatus = false;
                    });
                }, 500);

            }, 1500);
        }

        function setScreenHeight() {
            var winHeight = $window.outerHeight();

            $mainScreen.height(winHeight - 62);
        }

        function AnimateSecondTlt() {
            var $title    = $('.js-second-title');
            var winHeight = $window.outerHeight();
            var tltOffset = $title.offset().top;
            var tltHeight = $title.height();
            var scrollTop = $window.scrollTop();

            if (scrollTop + winHeight >= tltOffset + tltHeight) {
                $title.removeClass('blured');
            }
        }

        setScreenHeight();

        $window.on('resize', setScreenHeight);

        $window.on('scroll', AnimateSecondTlt);
    })($('.js-main-screen'));

    $window.on('scroll', function() {
        var winOfftop = $window.scrollTop();
        var isFixed   = winOfftop > 0 ? 'addClass' : 'removeClass';

        $body[isFixed]('fixed');
    });

    (function($wrapper) {
        if (!$wrapper.length) {
            return;
        }
        var $tabsWrap   = $wrapper.find('.js-tabs__links');
        var $blocksWrap = $wrapper.find('.js-tabs__blocks');
        var $tabs       = $wrapper.find('.js-tabs__link');
        var $blocks     = $wrapper.find('.js-tabs__block');

        $tabs.on('click', function(e) {
            var $self      = $(this);
            var index      = $self.index();
            var $selfBlock = $blocks.eq(index);

            e.preventDefault();

            if ($self.hasClass(active)) {
                return;
            }

            $tabs.removeClass(active);
            $self.addClass(active);
            $blocks.filter(':visible').stop().animate({
                opacity: 0
            }, function() {
                $blocks.hide();
                $selfBlock.show().stop().animate({
                    opacity: 1
                });
            });
        });
    })($('.js-tabs'));

    // Адаптивная карусель картинок
    (function($carousel) {
        if (!$carousel.length) {
            return;
        }
        require(['carousel'], function() {
            $carousel.each(function() {
                var $self     = $(this);
                var $item     = $self.find('.b-slider__item');
                var count     = $item.length;
                var $carousel = $self.find('.js-slider');
                var $btn      = $self.find('.js-btn');
                var $btnNext  = $btn.filter('.owl-btn_next');
                var $btnPrev  = $btn.filter('.owl-btn_prev');
                var disabled  = 'disabled';

                if (count < 3 ) {
                    $btn.hide();
                }

                $carousel.owlCarousel({
                    items      : 3,
                    rewindNav  : false,
                    nav        : true,
                    navText    : '',
                    margin     : 60,
                    loop       : (count > 3) ? true : false
                });

                function toggleArrows() {
                    if ($carousel.find(".owl-item").last().hasClass('active') &&
                       $carousel.find(".owl-item.active").index() == $carousel.find(".owl-item").first().index()) {
                        $btnNext.addClass(disabled);
                        $btnPrev.addClass(disabled);
                    } else if($carousel.find(".owl-item").last().hasClass('active')){
                        $btnNext.addClass(disabled);
                        $btnPrev.removeClass(disabled);
                    } else if($carousel.find(".owl-item.active").index() == $carousel.find(".owl-item").first().index()) {
                        $btnNext.removeClass(disabled);
                        $btnPrev.addClass(disabled);
                    } else {
                        $btnNext.removeClass(disabled);
                        $btnPrev.removeClass(disabled);
                    }
                }

                $carousel.on('translated.owl.carousel', function (event) {
                    toggleArrows();
                });

                $btn.on('mousedown', function() {
                    $(this).addClass('pushed');

                    if ($(this).hasClass('owl-btn_prev')) {
                        $carousel.trigger('prev.owl.carousel');

                        return;
                    }

                    $carousel.trigger('next.owl.carousel');
                });

                $btn.on('mouseup', function() {
                    $(this).removeClass('pushed');
                });
            });
        });
    })($('.js-carousel-wrapper'));

    // tweets
    // if ($('#tweets').length) {
    //     require(['twitter'], function() {
    //         twttr.widgets.createTweet(
    //           'kosteQQ',
    //           document.getElementById('tweets'),
    //           {
    //             theme: 'dark'
    //           }
    //         );
    //     });
    // }

    // Page scroll

    (function($scrollLink) {
        var navbar    = 62;
        var winHeight = $window.height();
        var hash      = window.location.hash;
        var speed;
        var status    = true;

        $scrollLink.on('click', function(e) {
            var $this     = $(this);
            var url       = $this.attr('href');
            var $el       = $(url);
            var offsetTop = $el.offset().top - navbar;
            var scrollTop = $window.scrollTop();
            var distance  = Math.abs(offsetTop - scrollTop);

            status = false;

            if ($this.hasClass('js-fast-list')) {
                speed = 25;
            } else {
                speed = 2.5;
            }

            var time      = Math.round(distance / speed);

            $scrollLink.removeClass(active);
            if (!$this.hasClass('js-fast-list')) {
                $this.addClass(active);
            } else {
                $scrollLink.last().addClass(active);
            }

            $('html, body').animate({
                scrollTop: offsetTop
            }, time, function() {
                status = true;
            });
        });

        $window.on('scroll', function() {
            var curOffset = $window.scrollTop() + navbar;

            if (status) {
                for(var i = 0; i < offset.length; i++) {
                    if (curOffset >= offset[i]) {
                        $scrollLink.removeClass(active);
                        $scrollLink.eq(i).addClass(active);
                    }
                }
            }

        });

    })($('.js-scroll-link'));

    (function($marks) {
        if (!$marks.length) {
            return;
        }
        var constWidth;

        function setWidth() {
            var winWidth = $window.width();
            if (winWidth > 1283) {
                constWidth = 480;
            } else {
                constWidth = 550;
            }
            var markWidth = constWidth - ((1900 - winWidth) / 2);

            if (winWidth > 1200) {
                $marks.css('width', markWidth);
            }
        }

        setWidth();

        $window.on('resize', setWidth);
    })($('.js-marks'));


    return {};
});
/*ignore jslint end*/
