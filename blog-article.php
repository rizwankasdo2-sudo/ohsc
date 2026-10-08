<?php
include('admin/classes/connection.php');
include_once('includes/functions.php');
require_once('includes/blog-landing-helpers.php');
require_once('includes/blog-category-helpers.php');

$category_slug = isset($_GET['category_slug']) ? preg_replace('/[^a-z0-9\-]/i', '', (string) $_GET['category_slug']) : '';
$slug = isset($_GET['slug']) ? preg_replace('/[^a-z0-9\-]/i', '', (string) $_GET['slug']) : '';

if ($slug === '') {
    header('HTTP/1.1 404 Not Found');
    header('Refresh:0; url=' . rtrim($base_url, '/') . '/page-not-found');
    exit();
}

$blog = loadPublishedBlogPostBySlug($db, $slug, $category_slug);

if (!$blog) {
    header('HTTP/1.1 404 Not Found');
    header('Refresh:0; url=' . rtrim($base_url, '/') . '/page-not-found');
    exit();
}
$blog_title = trim((string) $blog['title']);
$blog_meta_title = trim((string) $blog['meta_title']);
if ($blog_meta_title === '') {
    $blog_meta_title = $blog_title;
}
$blog_meta_description = trim((string) $blog['meta_description']);
$blog_meta_keywords = trim((string) $blog['meta_keywords']);
$blog_robots = trim((string) $blog['robots']);
if ($blog_robots === '') {
    $blog_robots = 'Index,Follow';
}
$blog_category_name = trim((string) $blog['category_name']);
$blog_category_url = getBlogCategoryLandingUrl($blog['category_slug']);
$blog_listing_url = getBlogListingLandingUrl();
$blog_is_test_mode = isBlogLandingTestMode();
$blog_canonical_url = getBlogPostProductionUrl($blog);
$blog_hero_image = getBlogLandingMainImageUrl($blog);
$blog_hero_alt = trim((string) $blog['alt']);
if ($blog_hero_alt === '') {
    $blog_hero_alt = $blog_title;
}
$blog_hero_width = 420;
$blog_hero_height = 260;
$blog_image_file = trim((string) $blog['image']);
if ($blog_image_file !== '') {
    $blog_image_path = dirname(__FILE__) . '/images/blog/' . $blog_image_file;
    if (is_file($blog_image_path)) {
        $blog_image_size = @getimagesize($blog_image_path);
        if ($blog_image_size) {
            $blog_hero_width = (int) $blog_image_size[0];
            $blog_hero_height = (int) $blog_image_size[1];
        }
    }
}
$blog_excerpt = renderBlogStoredHtml($blog['excerpt']);
$blog_last_updated = formatBlogCategoryLastUpdatedDate($blog['last_updated']);
$blog_minutes_read = getBlogLandingMinutesRead($blog);
$blog_share_url = getBlogPostLandingUrl($blog);
$blog_share_url_encoded = rawurlencode($blog_share_url);
$blog_share_title_encoded = rawurlencode($blog_title);
$blog_share_facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $blog_share_url_encoded;
$blog_share_twitter_url = 'https://twitter.com/intent/tweet?url=' . $blog_share_url_encoded . '&text=' . $blog_share_title_encoded;
$blog_share_linkedin_url = 'https://www.linkedin.com/sharing/share-offsite/?url=' . $blog_share_url_encoded;
$category_id = (int) $blog['blog_category'];
$popular_articles = loadBlogLandingPopularArticles($db, 0, $category_id);
$popular_articles = array_values(array_filter($popular_articles, function ($post) use ($blog) {
    return (int) $post['id'] !== (int) $blog['id'];
}));
$popular_has_slider = count($popular_articles) > 3;
$blog_article_prepared = prepareBlogArticleContent($blog['content']);
$blog_content = $blog_article_prepared['content'];
$blog_content_headings = $blog_article_prepared['headings'];
$related_articles = loadBlogLandingLatestArticles($db, 50, $category_id, (int) $blog['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($blog_meta_title, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta property="og:title" content="<?php echo htmlspecialchars(strip_tags($blog_meta_title), ENT_QUOTES, 'UTF-8'); ?>" />
    <meta name="description" content="<?php echo htmlspecialchars(strip_tags($blog_meta_description), ENT_QUOTES, 'UTF-8'); ?>" />
    <meta property="og:description" content="<?php echo htmlspecialchars(strip_tags($blog_meta_description), ENT_QUOTES, 'UTF-8'); ?>" />
    <meta property="og:url" content="<?php echo htmlspecialchars($blog_share_url, ENT_QUOTES, 'UTF-8'); ?>" />
    <meta property="og:site_name" content="Oxford Home Study College" />
    <meta name="keywords" content="<?php echo htmlspecialchars(strip_tags($blog_meta_keywords), ENT_QUOTES, 'UTF-8'); ?>" />
    <meta name="robots" content="<?php echo $blog_is_test_mode ? 'noindex, nofollow' : htmlspecialchars($blog_robots, ENT_QUOTES, 'UTF-8'); ?>" />
    <link rel="canonical" href="<?php echo htmlspecialchars(getCanonicalURL('/OHSC-Blog/' . preg_replace('/[^a-z0-9\-]/i', '', (string) $blog['slug'])), ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $pdots; ?>assets/css/owl.theme.default.css">
    <link rel="stylesheet" href="<?php echo $pdots; ?>assets/css/blog-landing.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo $pdots; ?>assets/css/blog-article.css?v=<?php echo time(); ?>">
    <?php include 'includes/header-home-new.php'; ?>
<div class="blog-article-page">
<div class="bpost-breadcrumb">
    <div class="bpost-container">
        <a href="/">Home</a><span class="bpost-breadcrumb__sep">&gt;</span><a href="<?php echo htmlspecialchars($blog_listing_url); ?>">OHSC Blog</a><span class="bpost-breadcrumb__sep">&gt;</span><a href="<?php echo htmlspecialchars($blog_category_url); ?>"><?php echo htmlspecialchars($blog_category_name); ?></a><span class="bpost-breadcrumb__sep">&gt;</span><span><?php echo htmlspecialchars($blog_title); ?></span>
    </div>
</div>

<section class="bpost-top">
    <div class="bpost-container">
        <div class="bpost-top__grid">
            <div class="bpost-top__content">
                <span class="bpost-badge"><?php echo htmlspecialchars($blog_category_name); ?></span><br />
                <strong class="bpost-title"><?php echo htmlspecialchars($blog_title); ?></strong><br /><br />
                <?php if ($blog_excerpt !== '') { ?>
                <div class="bpost-lead"><?php echo $blog_excerpt; ?></div>
                <?php } ?>
                <div class="bpost-meta">
                    <?php /*if ($blog_last_updated !== '') { ?><span><i class="fa-regular fa-calendar"></i><?php echo htmlspecialchars($blog_last_updated); ?></span><?php }*/ ?>
                    <?php if ($blog_minutes_read !== '') { ?><span><i class="fa-regular fa-clock"></i><?php echo htmlspecialchars($blog_minutes_read); ?></span><?php } ?>
                </div>
                <div class="bpost-share">
                    <span>Share this article:</span>
                    <div class="bpost-share__icons">
                        <a href="<?php echo htmlspecialchars($blog_share_facebook_url); ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="<?php echo htmlspecialchars($blog_share_twitter_url); ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on X"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="<?php echo htmlspecialchars($blog_share_linkedin_url); ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="bpost-top__image">
                <img src="<?php echo htmlspecialchars($blog_hero_image); ?>" alt="<?php echo htmlspecialchars($blog_hero_alt); ?>" width="<?php echo $blog_hero_width; ?>" height="<?php echo $blog_hero_height; ?>">
            </div>
        </div>
    </div>
</section>

<main class="bpost-main">
    <div class="bpost-container">
        <div class="bpost-layout">
            <div class="bpost-article-area<?php echo empty($blog_content_headings) ? ' bpost-article-area--no-toc' : ''; ?>">
                <?php if (!empty($blog_content_headings)) { ?>
                <nav class="bpost-toc" aria-label="In this article">
                    <h2 class="bpost-toc__title">In this article</h2>
                    <ul class="bpost-toc__list">
                        <?php foreach ($blog_content_headings as $heading) { ?>
                        <li><a href="#<?php echo htmlspecialchars($heading['id']); ?>"><?php echo htmlspecialchars($heading['text']); ?></a></li>
                        <?php } ?>
                    </ul>
                </nav>
                <?php } ?>

                <article class="bpost-body">
                    <?php if ($blog_content !== '') { ?>
                    <?php echo $blog_content; ?>
                    <?php } ?>

                    <div class="bpost-cta-banner">
                        <div class="bpost-cta-banner__icon"><i class="fa-solid fa-graduation-cap"></i></div>
                        <div class="bpost-cta-banner__text">
                            <h3>Want to Improve Your Career Prospects?</h3>
                            <p>Explore our free online courses and build the skills employers are looking for.</p>
                        </div>
                        <a href="<?php echo rtrim($base_url, '/'); ?>/free-online-courses-with-certificates" class="bpost-btn bpost-btn--primary">Explore Free Courses</a>
                    </div>
                </article>
            </div>

            <aside class="bpost-sidebar">
                <div class="bpost-widget">
                    <h3 class="bpost-widget__title">Popular Articles</h3>
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

                <div class="bpost-widget bpost-widget--cta">
                    <div class="bpost-widget__cta-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                    <h3 class="bpost-widget__title">Study Free Courses Online</h3>
                    <p class="bpost-widget__cta-text">Develop in-demand skills with our free online courses.</p>
                    <a href="<?php echo rtrim($base_url, '/'); ?>/free-online-courses-with-certificates" class="bpost-btn bpost-btn--white bpost-btn--block">Explore Free Courses <i class="fa-solid fa-arrow-right"></i></a>
                </div>

                <div class="bpost-widget">
                    <h3 class="bpost-widget__title">Share this article</h3>
                    <div class="bpost-share-grid">
                        <a href="<?php echo htmlspecialchars($blog_share_facebook_url); ?>" target="_blank" rel="noopener noreferrer"><span class="bpost-share-grid__icon"><i class="fa-brands fa-facebook-f"></i></span>Facebook</a>
                        <a href="<?php echo htmlspecialchars($blog_share_twitter_url); ?>" target="_blank" rel="noopener noreferrer"><span class="bpost-share-grid__icon"><i class="fa-brands fa-x-twitter"></i></span>Twitter</a>
                        <a href="<?php echo htmlspecialchars($blog_share_linkedin_url); ?>" target="_blank" rel="noopener noreferrer"><span class="bpost-share-grid__icon"><i class="fa-brands fa-linkedin-in"></i></span>LinkedIn</a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</main>

<section class="blog-section">
    <div class="blog-container">
        <div class="blog-section__head">
            <h2 class="blog-section__title">You May Also Like</h2>
        </div>
        <div class="blog-latest-slider">
            <?php if (!empty($related_articles)) { ?>
            <div class="blog-latest owl-carousel owl-theme blog-latest-carousel">
                <?php foreach ($related_articles as $related_post) {
                    $related_url = getBlogPostLandingUrl($related_post);
                    $related_image = getBlogLandingImage272_170Url($related_post);
                    $related_alt = trim((string) $related_post['alt']);
                    if ($related_alt === '') {
                        $related_alt = $related_post['title'];
                    }
                    $related_cat = getBlogLandingCategoryMetaForCategoryId($db, $related_post['blog_category']);
                    $related_date = formatBlogLandingPublishDate($related_post['publish_date']);
                    $related_read = getBlogLandingMinutesRead($related_post);
                    ?>
                <div class="item">
                    <article class="blog-latest-card">
                        <a href="<?php echo htmlspecialchars($related_url); ?>"><img src="<?php echo htmlspecialchars($related_image); ?>" alt="<?php echo htmlspecialchars($related_alt); ?>" width="272" height="170"></a>
                        <div class="blog-latest-card__body">
                            <div class="blog-latest-card__category">
                                <span class="blog-meta__cat blog-meta__cat--<?php echo htmlspecialchars($related_cat['class']); ?>"><?php echo htmlspecialchars(getBlogCategoryLabel($db, $related_post['blog_category'])); ?></span>
                            </div>
                            <div class="blog-meta blog-latest-card__meta">
                                <?php if ($related_date !== '') { ?><span><?php echo htmlspecialchars($related_date); ?></span><?php } ?>
                                <?php if ($related_read !== '') { ?><span><?php echo htmlspecialchars($related_read); ?></span><?php } ?>
                            </div>
                            <div class="blog-latest-card__title">
                                <h3 class="blog-article-title blog-article-title--sm"><a href="<?php echo htmlspecialchars($related_url); ?>"><?php echo htmlspecialchars($related_post['title']); ?></a></h3>
                            </div>
                            <a href="<?php echo htmlspecialchars($related_url); ?>" class="blog-read-more">Read more &rarr;</a>
                        </div>
                    </article>
                </div>
                <?php } ?>
            </div>
            <?php } else { ?>
            <p class="blog-latest-empty">No related articles to show.</p>
            <?php } ?>
        </div>
    </div>
</section>

</div>
<script>
document.querySelectorAll('.bpost-toc__list a[href^="#"]').forEach(function (link) {
    link.addEventListener('click', function (event) {
        var targetId = link.getAttribute('href').slice(1);
        var target = document.getElementById(targetId);
        if (!target) {
            return;
        }

        event.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        if (history.replaceState) {
            history.replaceState(null, '', '#' + targetId);
        }
    });
});

document.querySelectorAll('[data-bpost-accordion] .bpost-accordion__trigger').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var item = btn.closest('.bpost-accordion__item');
        var isOpen = item.classList.contains('is-open');
        item.parentElement.querySelectorAll('.bpost-accordion__item').forEach(function (el) {
            el.classList.remove('is-open');
        });
        if (!isOpen) {
            item.classList.add('is-open');
        }
    });
});
</script>
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
    if ($latestCarousel.length && $latestCarousel.children('.item').length) {
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
    }
});
</script>