function learnerCarousel() {
    $(".learner_carousel").owlCarousel({
        dots: true,
        loop:true,
        margin:40,
        nav:false,
        responsive: {
            0: {
                items: 1,
            },
            600: {
                items:2,
            },
            960: {
                items:2,
                stagePadding:65,
            },
            1200: {
                items: 3
            }
        }
    });
}
function makeAllCarousels(){
    var $carousel = $(".all-carousel");
    $carousel.owlCarousel({
        dots: false,
        loop: false, // start with loop disabled
        margin: 10,
        nav: false, // start with nav disabled
        navText: [
            '<i class="fa-solid fa-angle-left" aria-hidden="true"></i>',
            '<i class="fa-solid fa-angle-right" aria-hidden="true"></i>'
        ],
        responsive: {
            0: {
                items: 1,
                stagePadding: 30
            },
            600: {
                items: 2,
                stagePadding: 40
            },
            960: {
                items: 3
            },
            1200: {
                items: 4
            }
        },
        onInitialized: checkNav,
        onResized: checkNav
    });
}
function makeStudentReviewsSlider(){
    // Begin student reviews owlcarousel
    var stdrevcarousel = $('#stdrevslider');
    stdrevcarousel.owlCarousel({
        loop: false,
        margin: 10,
        nav: true,
        responsive: {
            0: {
                items: 1
            },
            805: {
                items: 2
            },
            1205: {
                items: 2
            },
            1300: {
                items: 3
            }
        }
    });
    // End student reviews owlcarousel
}
function checkNav(event) {
    var items = event.item.count;       // total items
    var shown = event.page.size;        // items allowed in current breakpoint
    var $carouselEl = $(event.target);

    if (items > shown) {
        // Enable nav + loop if more items than allowed
        $carouselEl.trigger('refresh.owl.carousel');
        $carouselEl.find('.owl-nav').show();
    } else {
        // Disable nav + loop if fewer items
        $carouselEl.trigger('refresh.owl.carousel');
        $carouselEl.find('.owl-nav').hide();

        // Fix gap issue by forcing "autoWidth = false"
        $carouselEl.find('.owl-stage').css('transform', 'translate3d(0px, 0px, 0px)');
    }
}
function blogsCarousel() {
    $(".blogs-carousel").owlCarousel({
        dots: true,
        loop:true,
        margin:10,
        nav:false,
        navText : [
            '<i class="fa-solid fa-angle-left"></i>',
            '<i class="fa-solid fa-angle-right"></i>',
        ],
        responsive: {
            0: {
                items: 1,
                stagePadding:50
            },
            600: {
                items: 2,
                stagePadding:40,
            },
            960: {
                items: 4
            },
            1200: {
                items: 4
            }
        }
    });
}

function blogsCarouselControlled(total_items) {
    let items_two = (total_items > 1) ? 2 : 1;
    let items_more = 1;
    if(total_items == 2) {
        items_more = 2;
    } else if(total_items == 3) {
        items_more = 3;
    } else if (total_items > 3) {
        items_more = 4;
    }
    $(".blogs-carousel").owlCarousel({
        dots: true,
        loop:true,
        margin:10,
        nav:false,
        navText : [
            '<i class="fa-solid fa-angle-left"></i>',
            '<i class="fa-solid fa-angle-right"></i>',
        ],
        responsive: {
            0: {
                items: 1,
                stagePadding:50
            },
            600: {
                items: items_more,
                stagePadding:40,
            },
            960: {
                items: items_more
            },
            1200: {
                items: items_more
            }
        }
    });
}

function categoriesSlider() {
    // Initialize both carousels with the same settings
    var carousel1 = $(".categories-slider").eq(0).owlCarousel({
        dots: false,
        infinite: true,
        loop: true,
        speed: 300,
        slidesToShow: 2,
        nav: false,
        stagePadding: 30,
        autoWidth: true,
        navText : [
            '<i class="fa-solid fa-angle-left"></i>',
            '<i class="fa-solid fa-angle-right"></i>',
        ],
        responsive: {
            0: {
                items: 1,
                autoplay: 3000,
            },
            600: {
                items: 1,
            },
            960: {
                items: 2,
            },
            1200: {
                items: 2,
            },
        },
    });
    var carousel2 = $(".categories-slider").eq(1).owlCarousel({
        dots: false,
        infinite: true,
        loop: true,
        speed: 300,
        slidesToShow: 2,
        nav: false,
        stagePadding: 30,
        autoWidth: true,
        navText : [
            '<i class="fa-solid fa-angle-left"></i>',
            '<i class="fa-solid fa-angle-right"></i>',
        ],
        responsive: {
            0: {
                items: 1,
                autoplay: 1000,
            },
            600: {
                items: 1,
            },
            960: {
                items: 2,
            },
            1200: {
                items: 2,
            },
        },
    });

    // Shared navigation button for both carousels
    $('.carousel-nav').click(function () {
        carousel1.trigger('next.owl.carousel');
        carousel2.trigger('next.owl.carousel');
    });
    $('.carousel-nav1').click(function () {
        // Example action: Move carousels backward
        carousel1.trigger('prev.owl.carousel');
        carousel2.trigger('prev.owl.carousel');
    });
}

function loadNewCarousel(carouselName) {
    var homeOwl = $('.' + carouselName);
    homeOwl.owlCarousel({
        loop: true,
        nav: true,
        dots: false,
        autoplayHoverPause: true,
        margin: 10,
        stagePadding: 15,
        mouseDrag  : false,
        navText : [
            '<i class="fa-solid fa-chevron-left"></i>',
            '<i class="fa-solid fa-chevron-right"></i>',
        ],
        responsive: {
            0: {
                items: 1,
                stagePadding: 75,
            },
            600: {
                items:2,
                stagePadding: 55,
            },
            960: {
                items: 3
            },
            1200: {
                items: 4
            }
        }
    });
}