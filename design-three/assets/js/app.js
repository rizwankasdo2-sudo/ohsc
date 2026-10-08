  // Ensure only the hovered dropdown menu is active
  document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
    dropdown.addEventListener('mouseover', () => {
        dropdown.querySelector('.dropdown-menu').classList.add('show');
    });
    dropdown.addEventListener('mouseleave', () => {
        dropdown.querySelector('.dropdown-menu').classList.remove('show');
    });
});
function initializeSwipers() {
    // Select all swiper containers on the page
    const swiperContainers = document.querySelectorAll('.swiper-container');
    
    // Initialize a separate Swiper instance for each container
    swiperContainers.forEach((container) => {
        // Check if the Swiper instance is already initialized
        if (!container.swiper) {
            new Swiper(container, {
                slidesPerView: 1,
                loop: true,
                spaceBetween: 10,
                pagination: {
                    el: container.querySelector('.swiper-pagination'),
                    clickable: true,
                },
                navigation: {
                    nextEl: container.querySelector('.swiper-button-next'),
                    prevEl: container.querySelector('.swiper-button-prev'),
                },
            });
        }
    });
}

function destroySwipers() {
    // Destroy all swiper instances
    const swiperContainers = document.querySelectorAll('.swiper-container');
    swiperContainers.forEach((container) => {
        if (container.swiper) {
            container.swiper.destroy(true, true);
        }
    });
}

function handleResize() {
    const isMobile = window.innerWidth <= 768; // Mobile screen breakpoint

    if (isMobile) {
        // Initialize Swiper for all containers
        initializeSwipers();
    } else {
        // Destroy Swiper instances on larger screens
        destroySwipers();

        // Reset swiper-wrapper styles for large screens
        const swiperWrappers = document.querySelectorAll('.swiper-wrapper');
        swiperWrappers.forEach((wrapper) => {
            wrapper.style.transition = ''; // Reset transition
            wrapper.style.transform = ''; // Reset transform
            wrapper.style.display = 'flex'; // Ensure it's displayed as flex on large screens
        });
    }
}

// Initialize or destroy Swiper on load and resize
window.addEventListener('load', handleResize);
window.addEventListener('resize', handleResize);


// Home Page Owl Carousel
var homeOwl = $('.home-carousel');
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
$(document).ready(function () {
    $(".other-carousel").owlCarousel({
        dots: false,
        infinite: true,
        loop:true,
        speed: 300,
        slidesToShow: 1,
        nav:true,
        centerMode: true,
        variableWidth: true,
        autoWidth:true,
        navText : [
            '<i class="fa-solid fa-chevron-left"></i>',
            '<i class="fa-solid fa-chevron-right"></i>',
        ],
        responsive: {
            0: {
                items: 1,
            },
            600: {
                items: 1
            },
            960: {
                items: 2
            },
            1200: {
                items: 1
            }
        }
    });
});
$(document).ready(function () {
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
});
$(document).ready(function () {
    $(".demand-carousel").owlCarousel({
        dots: true,
        loop:true,
        margin:10,
        nav:false,
        stagePadding:55,
        responsive: {
            0: {
                items: 1,
                stagePadding:85,
            },
            600: {
                items: 2
            },
            960: {
                items: 2,
                stagePadding:80,
            },
            1200: {
                items: 3
            }
        }
    });
});
$(document).ready(function () {
    $(".all-carousel").owlCarousel({
        dots: false,
        loop:true,
        margin:10,
        nav:true,
        navText: [
            '<i class="fa-solid fa-angle-left" aria-hidden="true"></i>',
            '<i class="fa-solid fa-angle-right" aria-hidden="true"></i>'
        ],
        responsive: {
            0: {
                items: 1,
                stagePadding:30
            },
            600: {
                items: 2,
                stagePadding:40,
            },
            960: {
                items: 3
            },
            1200: {
                items: 4
            }
        }
    });
});
$(document).ready(function () {
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
});
// faq pages link slider
let swiper
function initSwiper() {
  if (window.innerWidth <= 767 && !swiper) {
    swiper = new Swiper(".swiper-container", {
      slidesPerView: 1, 
      spaceBetween: 10, 
      slidesOffsetAfter: 80, 
      pagination: {
        el: ".swiper-pagination", 
        clickable: true, 
      },
    });
  } else if (window.innerWidth > 767 && swiper) {
    swiper.destroy(true, true);
    swiper = null;
    const container = document.querySelector(".swiper-wrapper");
    container.style.removeProperty("transform");
  }
}
window.addEventListener("load", initSwiper);
window.addEventListener("resize", initSwiper);
// custom slider
$(document).ready(function () {
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
        navText: [
            '<i class="fa-solid fa-chevron-left"></i>',
            '<i class="fa-solid fa-chevron-right"></i>',
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
        navText: [
            '<i class="fa-solid fa-chevron-left"></i>',
            '<i class="fa-solid fa-chevron-right"></i>',
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
});

