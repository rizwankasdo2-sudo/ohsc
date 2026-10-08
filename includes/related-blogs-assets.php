<?php
if (empty($show_related_blogs_assets) || empty($use_related_blogs_carousel)) {
    return;
}
$asset_prefix = !empty($pdots) ? $pdots : '/';
$related_blogs_count = isset($related_blogs_count) ? (int) $related_blogs_count : 0;
?>
<link rel="stylesheet" href="<?php echo $asset_prefix; ?>assets/css/owl.carousel.css">
<link rel="stylesheet" href="<?php echo $asset_prefix; ?>assets/css/related-blogs.css">
<script>
(function () {
    var owlScriptUrl = '<?php echo $asset_prefix; ?>assets/js/owl.carousel.min.js';
    var totalItems = <?php echo $related_blogs_count; ?>;
    var maxVisibleItems = 4;
    var initialized = false;

    function getVisibleItems(maxItems) {
        return Math.min(maxItems, totalItems);
    }

    function canLoop(itemsVisible) {
        return totalItems >= (itemsVisible * 2);
    }

    function initRelatedBlogsCarousel() {
        if (initialized) {
            return;
        }

        if (typeof jQuery === 'undefined' || !jQuery.fn.owlCarousel) {
            return false;
        }

        var $carousel = jQuery('.course-related-blogs .blogs-carousel');
        if (!$carousel.length || $carousel.hasClass('owl-loaded')) {
            initialized = true;
            return true;
        }

        var desktopItems = getVisibleItems(maxVisibleItems);

        $carousel.owlCarousel({
            dots: totalItems > desktopItems,
            loop: canLoop(desktopItems),
            rewind: true,
            margin: 10,
            nav: totalItems > desktopItems,
            navText: [
                '<i class="fa-solid fa-chevron-left" aria-hidden="true"></i>',
                '<i class="fa-solid fa-chevron-right" aria-hidden="true"></i>'
            ],
            mouseDrag: true,
            touchDrag: true,
            slideBy: 1,
            responsive: {
                0: {
                    items: getVisibleItems(1),
                    stagePadding: totalItems > 1 ? 50 : 0,
                    loop: canLoop(1),
                    nav: totalItems > 1,
                    dots: totalItems > 1
                },
                600: {
                    items: getVisibleItems(2),
                    stagePadding: totalItems > 1 ? 40 : 0,
                    loop: canLoop(2),
                    nav: totalItems > 2,
                    dots: totalItems > 2
                },
                960: {
                    items: desktopItems,
                    loop: canLoop(desktopItems),
                    nav: totalItems > desktopItems,
                    dots: totalItems > desktopItems
                },
                1200: {
                    items: desktopItems,
                    loop: canLoop(desktopItems),
                    nav: totalItems > desktopItems,
                    dots: totalItems > desktopItems
                }
            }
        });

        initialized = true;
        return true;
    }

    function loadOwlCarousel() {
        if (typeof jQuery === 'undefined') {
            return false;
        }

        if (jQuery.fn.owlCarousel) {
            return initRelatedBlogsCarousel();
        }

        if (document.querySelector('script[data-related-blogs-owl="1"]')) {
            return false;
        }

        var script = document.createElement('script');
        script.src = owlScriptUrl;
        script.setAttribute('data-related-blogs-owl', '1');
        script.onload = function () {
            initRelatedBlogsCarousel();
        };
        document.body.appendChild(script);
        return true;
    }

    function bootRelatedBlogsCarousel() {
        if (initRelatedBlogsCarousel()) {
            return;
        }

        loadOwlCarousel();
    }

    function scheduleBoot() {
        window.setTimeout(bootRelatedBlogsCarousel, 50);
    }

    if (typeof jQuery !== 'undefined') {
        jQuery(scheduleBoot);
    } else if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', scheduleBoot);
    } else {
        scheduleBoot();
    }

    window.addEventListener('load', bootRelatedBlogsCarousel);
})();
</script>
