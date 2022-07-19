(function ($) {
    "use strict"; // Start of use strict

    /**=========================
        input range
    =========================**/
    const allRanges = document.querySelectorAll(".range-wrap");
    allRanges.forEach(wrap => {
      const range = wrap.querySelector(".range");
      const bubble = wrap.querySelector(".bubble");

      range.addEventListener("input", () => {
        setBubble(range, bubble);
      });
      setBubble(range, bubble);
    });

    function setBubble(range, bubble) {
      const val = range.value;
      const min = range.min ? range.min : 0;
      const max = range.max ? range.max : 100;
      const newVal = Number(((val - min) * 100) / (max - min));
      bubble.innerHTML = val;

      // Sorta magic numbers based on size of the native UI thumb
      bubble.style.left = `calc(${newVal}% + (${-20 - newVal * 0.15}px))`;
    }

    /**=========================
        LOADER
    =========================**/
    $(window).on('load', function() {
        $('.loader-fix').fadeOut('slow',function(){$(this).remove();});
    });

    /**=========================
        HEADER FIXED SCROLL
    =========================**/
    $(window).on('scroll', function () {
        if($(window).scrollTop() > 400){
            $("#navigation").addClass('header-fixed');
        }else{
            $("#navigation").removeClass('header-fixed');
        }
    });

    /**=========================
        NAVBAR
    =========================**/
    $(function(){
        if($(window).width() > 768){
            $(".dropdown").hover(
            function(){
                $(this).find('.dropdown-menu').first().stop(true, true).slideDown(400);
                $(this).toggleClass('open');
                $(this).find('.dropdown-menu').parent('.nav-item.dropdown').addClass("dropdown-active");
            },
            function(){
                $(this).find('.dropdown-menu').first().stop(true, true).slideUp(100);
                $(this).toggleClass('open');
                $(this).find('.dropdown-menu').parent('.nav-item.dropdown').removeClass("dropdown-active");
            });
        };
    });
    // Closes responsive menu when a scroll trigger link is clicked
    $(".js-scroll-trigger").click(function () {
        $(".navbar-collapse").collapse("hide");
    });

    /**=========================
        EASING
    =========================**/
    $('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function () {
        if (location.pathname.replace(/^\//, "") == this.pathname.replace(/^\//, "") && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $("[name=" + this.hash.slice(1) + "]");
            if (target.length) {
                $("html, body").animate(
                    {
                        scrollTop: target.offset().top - 71,
                    },
                    1000,
                    "easeInOutExpo"
                );
                return false;
            }
        }
    });
    // Activate scrollspy to add active class to navbar items on scroll
    $("body").scrollspy({
        target: "#mainNav",
        offset: 80,
    });
    // Section animation when scroll
    AOS.init({
        easing: 'ease-in-out-sine'
    });

    /**=========================
        TOOLTIP
    =========================**/
    $('.theme-tooltip').tooltip();

    /**=========================
        CAROUSEL
    =========================**/
    // client says
    $(".testimonials-carousel").not('.slick-initialized').slick({
        slidesToShow: 1,
        dots: true,
        autoplay: true,
        pauseOnHover: false,
        pauseOnFocus: false,
    });

    // clients carousel
    $(".clients-carousel").not('.slick-initialized').slick({
        slidesToShow: 4,
        autoplay: true,
        pauseOnHover: false,
        pauseOnFocus: false,
        centerMode: true,
        responsive:[
            {
                breakpoint: 900,
                settings:{
                    slidesToShow: 2,
                    slidesToScroll: 1,
                    infinite: true,
                }
            }
        ]
    });

    /**=========================
        BACK TO TOP
    =========================**/
    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 300) {
            $('#backtotop').addClass('backtotop-bottom');
        } else {
            $('#backtotop').removeClass('backtotop-bottom');
        }
    });

})(jQuery); // End of use strict
