<?php
if (empty($show_related_courses_assets)) {
    return;
}

$asset_prefix = !empty($pdots) ? $pdots : '/';
?>
<link rel="stylesheet" href="<?php echo $asset_prefix; ?>assets/css/related-courses.css">
<link rel="stylesheet" href="<?php echo $asset_prefix; ?>assets/css/owl.carousel.css">
<script>
(function () {
    var owlScriptUrl = '<?php echo $asset_prefix; ?>assets/js/owl.carousel.min.js';
    var appScriptUrl = '<?php echo $asset_prefix; ?>assets/js/app.js';
    var initialized = false;

    function initRelatedCoursesCarousel() {
        if (initialized) {
            return;
        }

        if (typeof jQuery === 'undefined' || typeof makeAllCarousels !== 'function') {
            return false;
        }

        if (!jQuery('.course-related-courses .all-carousel').length) {
            initialized = true;
            return true;
        }

        makeAllCarousels();
        initialized = true;
        return true;
    }

    function loadScript(url, onLoad) {
        if (document.querySelector('script[src="' + url + '"]')) {
            if (onLoad) {
                onLoad();
            }
            return;
        }

        var script = document.createElement('script');
        script.src = url;
        script.onload = function () {
            if (onLoad) {
                onLoad();
            }
        };
        document.body.appendChild(script);
    }

    function bootRelatedCoursesCarousel() {
        if (initRelatedCoursesCarousel()) {
            return;
        }

        loadScript(owlScriptUrl, function () {
            loadScript(appScriptUrl, function () {
                initRelatedCoursesCarousel();
            });
        });
    }

    function scheduleBoot() {
        window.setTimeout(bootRelatedCoursesCarousel, 50);
    }

    if (typeof jQuery !== 'undefined') {
        jQuery(scheduleBoot);
    } else if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', scheduleBoot);
    } else {
        scheduleBoot();
    }

    window.addEventListener('load', bootRelatedCoursesCarousel);
})();
</script>
