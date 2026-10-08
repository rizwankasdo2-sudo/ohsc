<?php
include('admin/classes/connection.php');
include_once('includes/functions.php');
require_once('includes/blog-landing-helpers.php');

$blog_categories = [];
$blog_categories_result = $db->query("SELECT id, name, slug, short_intro, tag_line, icon, icon_alt FROM blog_categories ORDER BY `order` ASC, name ASC LIMIT 6");
if ($blog_categories_result) {
    while ($blog_category_row = $blog_categories_result->fetch_array()) {
        $blog_categories[] = $blog_category_row;
    }
}

$popular_articles = loadBlogLandingPopularArticles($db);
$popular_has_slider = count($popular_articles) > 3;
$featured_center_posts = loadBlogLandingFeaturedCenterPosts($db, 4);
$featured_main_post = isset($featured_center_posts[0]) ? $featured_center_posts[0] : null;
$featured_list_posts = array_slice($featured_center_posts, 1);
$latest_articles = loadBlogLandingLatestArticles($db);
$blog_is_test_mode = isBlogLandingTestMode();
$blog_header_nav_url = getBlogListingLandingUrl();
$blog_listing_url = $blog_header_nav_url;
$blog_listing_seo_row = $db->query('SELECT * FROM tbl_seo WHERE seoid = 10 LIMIT 1')->fetch_array();
if (!$blog_listing_seo_row) {
    $blog_listing_seo_row = [
        'pagetitle' => 'Oxford Home Study Centre - Blog | Home Study College',
        'metadiscription' => 'Oxford Home Study College.  View our Blog listings about home study and the range of professional courses we offer across various subjects.',
        'metatitle' => 'Oxford Home Study Centre, Blog, OHSC, blog posts, OHSC articles.',
        'robots' => 'Index,Follow',
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo strip_tags($blog_listing_seo_row['pagetitle']); ?></title>
    <meta property="og:title" content="<?php echo htmlspecialchars(strip_tags($blog_listing_seo_row['pagetitle']), ENT_QUOTES, 'UTF-8'); ?>" />
    <meta name="description" content="<?php echo htmlspecialchars(strip_tags($blog_listing_seo_row['metadiscription']), ENT_QUOTES, 'UTF-8'); ?>" />
    <meta property="og:description" content="<?php echo htmlspecialchars(strip_tags($blog_listing_seo_row['metadiscription']), ENT_QUOTES, 'UTF-8'); ?>" />
    <meta property="og:url" content="<?php echo htmlspecialchars($blog_listing_url, ENT_QUOTES, 'UTF-8'); ?>" />
    <meta property="og:site_name" content="Oxford Home Study College" />
    <meta name="keywords" content="<?php echo htmlspecialchars(strip_tags($blog_listing_seo_row['metatitle']), ENT_QUOTES, 'UTF-8'); ?>" />
    <meta name="robots" content="<?php echo $blog_is_test_mode ? 'noindex, nofollow' : htmlspecialchars(strip_tags($blog_listing_seo_row['robots']), ENT_QUOTES, 'UTF-8'); ?>" />
    <link rel="canonical" href="<?php echo htmlspecialchars(getCanonicalURL('/OHSC-Blog'), ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $pdots; ?>assets/css/owl.carousel.css">
    <link rel="stylesheet" href="<?php echo $pdots; ?>assets/css/owl.theme.default.css">
    <link rel="stylesheet" href="<?php echo $pdots; ?>assets/css/blog-landing.css?v=<?php echo time(); ?>">
    <?php include 'includes/header-home-new.php'; ?>
<div class="blog-landing-page">
<main>
    <section class="blog-hero">
        <div class="blog-container">
            <div class="blog-hero__grid">
                <div class="blog-hero__content">
                    <h1 class="blog-hero__title">OHSC Blog</h1>
                    <p class="blog-hero__subtitle">Insights, guides and learning resources to help you grow your skills and your career.</p>
                    <ul class="blog-hero__features">
                        <li><span class="blog-hero__feature-icon"><i class="fa-regular fa-lightbulb"></i></span> Expert insights</li>
                        <li><span class="blog-hero__feature-icon"><i class="fa-solid fa-book-open"></i></span> Practical guides</li>
                        <li><span class="blog-hero__feature-icon"><i class="fa-solid fa-chart-line"></i></span> Career growth</li>
                    </ul>
                </div>
                <div class="blog-hero__image">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=800&q=80" alt="Student learning online with OHSC" width="560" height="420">
                </div>
            </div>
        </div>
    </section>

    <section class="blog-section blog-section--tight-top">
        <div class="blog-container">
            <div class="blog-section__head">
                <h2 class="blog-section__title">Explore Blog Categories</h2>
            </div>
            <div class="blog-categories">
                <?php foreach ($blog_categories as $index => $category) {
                    $meta = getBlogLandingCategoryMeta($index);
                    $category_name = $category['name'];
                    $category_desc = trim((string) $category['short_intro']);
                    if ($category_desc === '' && !empty($category['tag_line'])) {
                        $category_desc = trim(strip_tags($category['tag_line']));
                    }
                    if ($category_desc === '') {
                        $category_desc = $meta['desc'];
                    }
                    $category_url = getBlogCategoryLandingUrl($category['slug']);
                    $icon_file = trim((string) $category['icon']);
                    $icon_alt = trim((string) $category['icon_alt']);
                    if ($icon_alt === '') {
                        $icon_alt = $category_name;
                    }
                    $icon_class = 'blog-category-card__icon blog-category-card__icon--' . $meta['class'];
                    if ($icon_file !== '') {
                        $icon_class .= ' blog-category-card__icon--has-image';
                    }
                    ?>
                <article class="blog-category-card">
                    <div class="<?php echo htmlspecialchars($icon_class); ?>">
                        <?php if ($icon_file !== '') { ?>
                            <img src="<?php echo htmlspecialchars($pdots . 'images/blog_categories/' . $icon_file); ?>" alt="" width="65" height="65">
                        <?php } else { ?>
                            <i class="fa-solid <?php echo htmlspecialchars($meta['icon']); ?>"></i>
                        <?php } ?>
                    </div>
                    <h3 class="blog-category-card__title"><?php echo htmlspecialchars($category_name); ?></h3>
                    <p class="blog-category-card__desc"><?php echo htmlspecialchars($category_desc); ?></p>
                    <a href="<?php echo htmlspecialchars($category_url); ?>" class="blog-category-card__link">View articles &rarr;</a>
                </article>
                <?php } ?>
            </div>
        </div>
    </section>

    <section class="blog-section">
        <div class="blog-container">
            <div class="blog-section__head">
                <h2 class="blog-section__title">Featured Articles</h2>
            </div>
            <div class="blog-featured">
                <?php if ($featured_main_post) {
                    $featured_main_url = getBlogPostLandingUrl($featured_main_post);
                    $featured_main_image = getBlogLandingMainImageUrl($featured_main_post);
                    $featured_main_alt = trim((string) $featured_main_post['alt']);
                    if ($featured_main_alt === '') {
                        $featured_main_alt = $featured_main_post['title'];
                    }
                    $featured_main_cat = getBlogLandingCategoryMetaForCategoryId($db, $featured_main_post['blog_category']);
                    $featured_main_date = formatBlogLandingPublishDate($featured_main_post['publish_date']);
                    $featured_main_excerpt = getBlogLandingPostExcerpt($featured_main_post, 200);
                    $featured_main_read = getBlogLandingMinutesRead($featured_main_post);
                    ?>
                <article class="blog-featured__main">
                    <a href="<?php echo htmlspecialchars($featured_main_url); ?>">
                        <div class="blog-featured__main-image">
                            <img src="<?php echo htmlspecialchars($featured_main_image); ?>" alt="<?php echo htmlspecialchars($featured_main_alt); ?>" width="464" height="271">
                        </div>
                        <div class="blog-featured__main-body">
                            <div class="blog-meta">
                                <span class="blog-meta__cat blog-meta__cat--<?php echo htmlspecialchars($featured_main_cat['class']); ?>"><?php echo htmlspecialchars(getBlogCategoryLabel($db, $featured_main_post['blog_category'])); ?></span>
                                <?php if ($featured_main_date !== '') { ?><span><?php echo htmlspecialchars($featured_main_date); ?></span><?php } ?>
                                <?php if ($featured_main_read !== '') { ?><span><?php echo htmlspecialchars($featured_main_read); ?></span><?php } ?>
                            </div>
                            <h3 class="blog-article-title"><?php echo htmlspecialchars($featured_main_post['title']); ?></h3>
                            <?php if ($featured_main_excerpt !== '') { ?>
                            <p class="blog-article-excerpt"><?php echo htmlspecialchars($featured_main_excerpt); ?></p>
                            <?php } ?>
                            <span class="blog-read-more">Read more &rarr;</span>
                        </div>
                    </a>
                </article>
                <?php } ?>

                <div class="blog-featured__list">
                    <?php foreach ($featured_list_posts as $featured_list_post) {
                        $featured_list_url = getBlogPostLandingUrl($featured_list_post);
                        $featured_list_image = getBlogLandingImage80_64Url($featured_list_post);
                        $featured_list_alt = trim((string) $featured_list_post['alt']);
                        if ($featured_list_alt === '') {
                            $featured_list_alt = $featured_list_post['title'];
                        }
                        $featured_list_cat = getBlogLandingCategoryMetaForCategoryId($db, $featured_list_post['blog_category']);
                        $featured_list_date = formatBlogLandingPublishDate($featured_list_post['publish_date']);
                        ?>
                    <article class="blog-featured__item">
                        <a href="<?php echo htmlspecialchars($featured_list_url); ?>"><img src="<?php echo htmlspecialchars($featured_list_image); ?>" alt="<?php echo htmlspecialchars($featured_list_alt); ?>" width="80" height="64"></a>
                        <div>
                            <div class="blog-meta">
                                <span class="blog-meta__cat blog-meta__cat--<?php echo htmlspecialchars($featured_list_cat['class']); ?>"><?php echo htmlspecialchars(getBlogCategoryLabel($db, $featured_list_post['blog_category'])); ?></span>
                                <?php if ($featured_list_date !== '') { ?><span><?php echo htmlspecialchars($featured_list_date); ?></span><?php } ?>
                            </div>
                            <h3 class="blog-article-title blog-article-title--sm"><a href="<?php echo htmlspecialchars($featured_list_url); ?>"><?php echo htmlspecialchars($featured_list_post['title']); ?></a></h3>
                            <a href="<?php echo htmlspecialchars($featured_list_url); ?>" class="blog-read-more">Read more &rarr;</a>
                        </div>
                    </article>
                    <?php } ?>
                </div>

                <aside class="blog-sidebar">
                    <div class="blog-sidebar-card">
                        <h3 class="blog-sidebar-card__title">Popular Articles</h3>
                        <div class="blog-popular-slider<?php echo $popular_has_slider ? ' blog-popular-slider--active' : ''; ?>">
                            <div class="blog-popular-slider__viewport">
                                <ul class="blog-popular blog-popular-slider__track">
                            <?php if (!empty($popular_articles)) {
                                foreach ($popular_articles as $popular_post) {
                                    $popular_url = getBlogPostLandingUrl($popular_post);
                                    $popular_thumb = getBlogLandingPopularThumbUrl($popular_post);
                                    $popular_alt = trim((string) $popular_post['alt']);
                                    if ($popular_alt === '') {
                                        $popular_alt = $popular_post['title'];
                                    }
                                    $popular_date = formatBlogLandingPublishDate($popular_post['publish_date']);
                                    ?>
                            <li class="blog-popular__item">
                                <a href="<?php echo htmlspecialchars($popular_url); ?>"><img src="<?php echo htmlspecialchars($popular_thumb); ?>" alt="<?php echo htmlspecialchars($popular_alt); ?>" width="80" height="64"></a>
                                <div>
                                    <h4 class="blog-popular__title"><a href="<?php echo htmlspecialchars($popular_url); ?>"><?php echo htmlspecialchars($popular_post['title']); ?></a></h4>
                                    <?php if ($popular_date !== '') { ?>
                                    <span class="blog-popular__date"><?php echo htmlspecialchars($popular_date); ?></span>
                                    <?php } ?>
                                </div>
                            </li>
                            <?php }
                            } else { ?>
                            <li class="blog-popular__item"><p>No popular articles yet.</p></li>
                            <?php } ?>
                                </ul>
                            </div>
                            <?php if ($popular_has_slider) { ?>
                            <div class="blog-popular-slider__controls">
                                <button type="button" class="blog-popular-slider__btn blog-popular-slider__btn--prev" aria-label="Show previous popular articles" disabled>
                                    <i class="fa-solid fa-chevron-up" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="blog-popular-slider__btn blog-popular-slider__btn--next" aria-label="Show more popular articles">
                                    <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                                </button>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <section class="blog-section">
        <div class="blog-container">
            <div class="blog-section__head">
                <h2 class="blog-section__title">Latest Articles</h2>
            </div>
            <div class="blog-latest-slider">
                <?php if (!empty($latest_articles)) { ?>
                <div class="blog-latest owl-carousel owl-theme blog-latest-carousel">
                    <?php foreach ($latest_articles as $latest_post) {
                        $latest_url = getBlogPostLandingUrl($latest_post);
                        $latest_image = getBlogLandingImage272_170Url($latest_post);
                        $latest_alt = trim((string) $latest_post['alt']);
                        if ($latest_alt === '') {
                            $latest_alt = $latest_post['title'];
                        }
                        $latest_cat = getBlogLandingCategoryMetaForCategoryId($db, $latest_post['blog_category']);
                        $latest_date = formatBlogLandingPublishDate($latest_post['publish_date']);
                        $latest_read = getBlogLandingMinutesRead($latest_post);
                        ?>
                    <div class="item">
                        <article class="blog-latest-card">
                            <a href="<?php echo htmlspecialchars($latest_url); ?>"><img src="<?php echo htmlspecialchars($latest_image); ?>" alt="<?php echo htmlspecialchars($latest_alt); ?>" width="272" height="170"></a>
                            <div class="blog-latest-card__body">
                                <div class="blog-latest-card__category">
                                    <span class="blog-meta__cat blog-meta__cat--<?php echo htmlspecialchars($latest_cat['class']); ?>"><?php echo htmlspecialchars(getBlogCategoryLabel($db, $latest_post['blog_category'])); ?></span>
                                </div>
                                <div class="blog-meta blog-latest-card__meta">
                                    <?php if ($latest_date !== '') { ?><span><?php echo htmlspecialchars($latest_date); ?></span><?php } ?>
                                    <?php if ($latest_read !== '') { ?><span><?php echo htmlspecialchars($latest_read); ?></span><?php } ?>
                                </div>
                                <div class="blog-latest-card__title">
                                    <h3 class="blog-article-title blog-article-title--sm"><a href="<?php echo htmlspecialchars($latest_url); ?>"><?php echo htmlspecialchars($latest_post['title']); ?></a></h3>
                                </div>
                                <a href="<?php echo htmlspecialchars($latest_url); ?>" class="blog-read-more">Read more &rarr;</a>
                            </div>
                        </article>
                    </div>
                    <?php } ?>
                </div>
                <?php } else { ?>
                <p class="blog-latest-empty">No latest articles to show.</p>
                <?php } ?>
            </div>
        </div>
    </section>
</main>
</div>
<?php include 'includes/footer-new.php'; ?>
<script>
jQuery(function ($) {
    var $popularSlider = $('.blog-popular-slider--active');
    if ($popularSlider.length) {
        var $viewport = $popularSlider.find('.blog-popular-slider__viewport');
        var $track = $popularSlider.find('.blog-popular-slider__track');
        var $items = $track.children('.blog-popular__item');
        var $prevBtn = $popularSlider.find('.blog-popular-slider__btn--prev');
        var $nextBtn = $popularSlider.find('.blog-popular-slider__btn--next');
        var visibleCount = 3;
        var currentIndex = 0;
        var maxIndex = Math.max(0, $items.length - visibleCount);

        function getItemGap() {
            var trackStyles = window.getComputedStyle($track[0]);
            var gapValue = trackStyles.rowGap || trackStyles.gap || '16px';
            return parseFloat(gapValue) || 16;
        }

        function setViewportHeight() {
            var itemGap = getItemGap();
            var height = 0;

            for (var i = currentIndex; i < currentIndex + visibleCount && i < $items.length; i++) {
                height += $items.eq(i).outerHeight();
            }

            height += itemGap * (visibleCount - 1);
            $viewport.height(height);
        }

        function updatePopularSlider() {
            var itemGap = getItemGap();
            var offset = 0;

            for (var i = 0; i < currentIndex; i++) {
                offset += $items.eq(i).outerHeight() + itemGap;
            }

            $track.css('transform', 'translateY(-' + offset + 'px)');
            $prevBtn.prop('disabled', currentIndex === 0);
            $nextBtn.prop('disabled', currentIndex >= maxIndex);
            setViewportHeight();
        }

        $nextBtn.on('click', function () {
            if (currentIndex < maxIndex) {
                currentIndex++;
                updatePopularSlider();
            }
        });

        $prevBtn.on('click', function () {
            if (currentIndex > 0) {
                currentIndex--;
                updatePopularSlider();
            }
        });

        updatePopularSlider();
        $(window).on('resize', updatePopularSlider);
    }

    var $latestCarousel = $('.blog-latest-carousel');
    if (!$latestCarousel.length || !$latestCarousel.children('.item').length) {
        return;
    }
    var slideCount = $latestCarousel.children('.item').length;
    $latestCarousel.owlCarousel({
        dots: true,
        loop: slideCount > 4,
        margin: 22,
        nav: true,
        navText: [
            '<span class="blog-latest-carousel__nav" aria-hidden="true">&lsaquo;</span>',
            '<span class="blog-latest-carousel__nav" aria-hidden="true">&rsaquo;</span>'
        ],
        responsive: {
            0: {
                items: 1,
                stagePadding: 36
            },
            600: {
                items: 2,
                stagePadding: 24
            },
            992: {
                items: 3
            },
            1200: {
                items: 4
            }
        }
    });
});
</script>