// JavaScript Document
$(document).ready(function () {
  // Menu
  $(".navbar-toggler").click(function () {
		$(this).toggleClass("open");
		$("html").toggleClass("overflow-hidden");
	});
	function initSlickSlider() {
		var windowWidth = $(window).width();

		
		if ($('.stories-slider').hasClass('slick-initialized')) {
			$('.stories-slider').slick('unslick');
		}
    
		$('.stories-slider').slick({
			dots: false,
			arrows: false,
			slidesToShow: windowWidth > 767 ? 2 : 1, 
			slidesToScroll: 1,
			rows: windowWidth > 767 ? 1 : 2,      
			slidesPerRow: 1                         
		});
	}



	// Initialize on page load
	$(document).ready(function () {
		initSlickSlider();

		// Reinitialize on window resize
		$(window).on('resize', function () {
			clearTimeout(window.slickResizeTimer); 
			window.slickResizeTimer = setTimeout(function () {
				initSlickSlider();
			}, 900);
		});
	});


	// Refresh-slick on tabing
	$("#pills-stories-tab").click(function () {
		$('.stories-slider').slick('refresh');
	});

	// Refresh-slick on tabing
	// Scroll It Start
	$(function () {
		$.scrollIt({
			// easing: 'linear',
			scrollTime: 400,
			activeClass: 'active',
			topOffset: -90,
		});
	});

  // OFI Browser
  objectFitImages();
});
