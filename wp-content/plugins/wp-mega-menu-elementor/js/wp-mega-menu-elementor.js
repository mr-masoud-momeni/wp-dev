jQuery(function($){
    var openDelay = 200;
    var closeDelay = 220;
    var timers = new WeakMap();

    // --- position & open/close code (همان کد اصلی شما) ---
    function positionMega($li){
        var $mega = $li.children('.custom-mega-menu');
        var $a = $li.children('a, .elementor-item');
        if(!$mega.length || !$a.length) return;

        var rect = $a.get(0).getBoundingClientRect();
        var rtl = $('html').attr('dir') === 'rtl';

        $mega.css({
            position: 'fixed',
            top: rect.bottom + 'px',
            left: rtl ? 'auto' : '0px',
            right: rtl ? '0px' : 'auto',
            width: '100vw'
        });

        var mid = rect.left + ($a.outerWidth()/2);
        var arrow = $mega.find('.mega-arrow');
        if(!rtl){
            arrow.css({ left: (mid - $mega.offset().left - 8) + 'px', right: 'auto' });
        } else {
            arrow.css({ right: ($mega.outerWidth() - (mid - $mega.offset().left) - 8) + 'px', left: 'auto' });
        }
    }

    function openMega($li){
        $li.siblings('.mega-open').removeClass('mega-open');
        $li.addClass('mega-open');
        positionMega($li);
    }

    function closeMega($li){
        $li.removeClass('mega-open');
    }

    $('ul.menu, .elementor-nav-menu').on('mouseenter', 'li.menu-item', function(){
        var $li = $(this);
        if(!$li.children('.custom-mega-menu').length) return;

        clearTimeout(timers.get(this));
        var t = setTimeout(function(){ openMega($li); }, openDelay);
        timers.set(this, t);
    }).on('mouseleave', 'li.menu-item', function(){
        var $li = $(this);
        if(!$li.children('.custom-mega-menu').length) return;

        clearTimeout(timers.get(this));
        (function($liCopy){
            var t = setTimeout(function(){ closeMega($liCopy); }, closeDelay);
            timers.set($liCopy.get(0), t);
        })($li);
    });

    $(document).on('mouseenter', '.custom-mega-menu', function(){
        var $li = $(this).closest('li.menu-item');
        clearTimeout(timers.get($li.get(0)));
    }).on('mouseleave', '.custom-mega-menu', function(){
        var $li = $(this).closest('li.menu-item');
        (function($liCopy){
            var t = setTimeout(function(){ closeMega($liCopy); }, closeDelay);
            timers.set($liCopy.get(0), t);
        })($li);
    });

    $(document).on('click', 'li.menu-item > a, li.menu-item > .elementor-item', function(e){
        var $li = $(this).parent('li.menu-item');
        if($li.children('.custom-mega-menu').length){
            e.preventDefault();
            if($li.hasClass('mega-open')) closeMega($li); else openMega($li);
        }
    });

    $(window).on('resize', function(){
        $('.mega-open').each(function(){ positionMega($(this)); });
    });

});
