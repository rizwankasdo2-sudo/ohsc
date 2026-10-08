<?php
include('admin/classes/connection.php');
include_once('includes/functions.php');
require_once('includes/blog-landing-helpers.php');
require_once('includes/blog-category-helpers.php');

$category_slug = isset($_GET['slug']) ? preg_replace('/[^a-z0-9\-]/i', '', (string) $_GET['slug']) : '';
if ($category_slug === '') {
    include('page-not-found-404.php');
    exit();
}

$category_slug_sql = mysqli_real_escape_string($db, $category_slug);
$category_row = $db->query("SELECT id, name, slug, tag_line, description_excerpt, description_detail, benefit_icon_1, benefit_icon_2, benefit_icon_3, benefit_icon_4, benefit_icon_1_alt, benefit_icon_2_alt, benefit_icon_3_alt, benefit_icon_4_alt, benefit_1, benefit_2, benefit_3, benefit_4, pagetitle, metatitle, metadescription, robots FROM blog_categories WHERE slug = '$category_slug_sql' LIMIT 1")->fetch_array();
if (!$category_row) {
    header('HTTP/1.1 404 Not Found');
    header('Refresh:0; url=' . rtrim($base_url, '/') . '/page-not-found');
    exit();
}

$category_name = trim((string) $category_row['name']);
$category_tag_line = trim((string) $category_row['tag_line']);
$category_description_excerpt = trim((string) $category_row['description_excerpt']);
$category_description_detail = trim((string) $category_row['description_detail']);
$category_has_read_more = trim(strip_tags($category_description_detail)) !== '';
$category_benefits = [];
for ($benefit_index = 1; $benefit_index <= 4; $benefit_index++) {
    $icon_file = trim((string) $category_row['benefit_icon_' . $benefit_index]);
    $category_benefits[] = [
        'icon' => $icon_file,
        'icon_alt' => trim((string) $category_row['benefit_icon_' . $benefit_index . '_alt']),
        'text' => trim((string) $category_row['benefit_' . $benefit_index]),
        'icon_src' => $icon_file !== '' ? $pdots . 'images/blog_categories/' . $icon_file : '',
    ];
}

$category_id = (int) $category_row['id'];
$category_posts_per_page = 5;
$category_posts_page = 1;
$category_posts_sort = 'latest';
$category_posts_total = countBlogCategoryArticles($db, $category_id);
$category_posts = loadBlogCategoryArticles($db, $category_id, $category_posts_page, $category_posts_per_page, $category_posts_sort);
$popular_articles = loadBlogLandingPopularArticles($db, 0, $category_id);
$popular_has_slider = count($popular_articles) > 3;
$category_page_title = trim((string) $category_row['pagetitle']);
if ($category_page_title === '') {
    $category_page_title = $category_name . ' | OHSC Blog | Oxford Home Study Centre';
}
$category_meta_description = trim((string) $category_row['metadescription']);
if ($category_meta_description === '') {
    $category_meta_description = $category_tag_line !== '' ? $category_tag_line : 'Browse ' . $category_name . ' articles on the OHSC Blog.';
}
$category_meta_keywords = trim((string) $category_row['metatitle']);
$category_robots = trim((string) $category_row['robots']);
if ($category_robots === '') {
    $category_robots = 'Index,Follow';
}
$category_canonical_url = getBlogCategoryProductionUrl($category_slug);
$blog_listing_url = getBlogListingLandingUrl();
$blog_is_test_mode = isBlogLandingTestMode();
$category_page_url = getBlogCategoryLandingUrl($category_slug);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars(strip_tags($category_page_title), ENT_QUOTES, 'UTF-8'); ?></title>
    <meta property="og:title" content="<?php echo htmlspecialchars(strip_tags($category_page_title), ENT_QUOTES, 'UTF-8'); ?>" />
    <meta name="description" content="<?php echo htmlspecialchars(strip_tags($category_meta_description), ENT_QUOTES, 'UTF-8'); ?>" />
    <meta property="og:description" content="<?php echo htmlspecialchars(strip_tags($category_meta_description), ENT_QUOTES, 'UTF-8'); ?>" />
    <meta property="og:url" content="<?php echo htmlspecialchars($category_page_url, ENT_QUOTES, 'UTF-8'); ?>" />
    <meta property="og:site_name" content="Oxford Home Study College" />
    <?php if ($category_meta_keywords !== '') { ?>
    <meta name="keywords" content="<?php echo htmlspecialchars(strip_tags($category_meta_keywords), ENT_QUOTES, 'UTF-8'); ?>" />
    <?php } ?>
    <meta name="robots" content="<?php echo $blog_is_test_mode ? 'noindex, nofollow' : htmlspecialchars(strip_tags($category_robots), ENT_QUOTES, 'UTF-8'); ?>" />
    <link rel="canonical" href="<?php echo htmlspecialchars(getCanonicalURL('/OHSC-Blog/' . $category_slug), ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo $pdots; ?>assets/css/blog-category.css?v=<?php echo time(); ?>">
    <?php include 'includes/header-home-new.php'; ?>
<div class="blog-category-page">
<div class="bcat-breadcrumb">
    <div class="bcat-container">
        <a href="/">Home</a><span class="bcat-breadcrumb__sep">&gt;</span><a href="<?php echo htmlspecialchars($blog_listing_url); ?>">OHSC Blog</a><span class="bcat-breadcrumb__sep">&gt;</span><span><?php echo htmlspecialchars($category_name); ?></span>
    </div>
</div>

<section class="bcat-hero">
    <div class="bcat-hero__bg" aria-hidden="true"></div>
    <div class="bcat-hero__overlay" aria-hidden="true"></div>
    <div class="bcat-container">
        <div class="bcat-hero__inner">
            <div class="bcat-hero__badge"><i class="fa-solid fa-briefcase"></i> OHSC BLOG</div>
            <h1 class="bcat-hero__title"><?php echo htmlspecialchars($category_name); ?></h1>
            <?php if ($category_tag_line !== '') { ?>
            <p class="bcat-hero__desc"><?php echo htmlspecialchars($category_tag_line); ?></p>
            <?php } ?>
            <ul class="bcat-hero__topics">
                <?php foreach ($category_benefits as $category_benefit) { ?>
                <li>
                    <div class="bcat-hero__topic">
                        <span class="bcat-hero__topic-icon">
                            <?php if ($category_benefit['icon_src'] !== '') { ?>
                            <img src="<?php echo htmlspecialchars($category_benefit['icon_src']); ?>" alt="" width="50" height="50">
                            <?php } ?>
                        </span>
                        <span><?php echo htmlspecialchars($category_benefit['text']); ?></span>
                    </div>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</section>

<main class="bcat-main">
    <div class="bcat-container">
        <div class="bcat-layout">
            <div class="bcat-content">
                <section class="bcat-content__intro">
                    <h2 class="bcat-content__intro-title">About This Category</h2>
                    <?php if ($category_description_excerpt !== '' && trim(strip_tags($category_description_excerpt)) !== '') { ?>
                    <div class="bcat-content__intro-text"><?php echo $category_description_excerpt; ?></div>
                    <?php } ?>
                    <?php if ($category_has_read_more) { ?>
                    <div id="bcatCategoryDetail" class="bcat-content__intro-detail" hidden>
                        <?php echo $category_description_detail; ?>
                    </div>
                    <button type="button" class="bcat-content__read-more" aria-expanded="false" aria-controls="bcatCategoryDetail">
                        Read more <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                    </button>
                    <?php } ?>
                </section>

                <div class="bcat-toolbar">
                    <span class="bcat-toolbar__summary"><?php echo getBlogCategoryArticlesSummaryText($category_posts_page, $category_posts_per_page, $category_posts_total); ?></span>
                    <label class="bcat-toolbar__sort">
                        Sort by:
                        <select id="bcatSortSelect" class="bcat-toolbar__sort-select" aria-label="Sort articles">
                            <option value="latest" selected>Latest</option>
                            <option value="oldest">Oldest</option>
                        </select>
                    </label>
                </div>

                <div class="bcat-articles" id="bcatArticles" data-category-id="<?php echo $category_id; ?>" data-per-page="<?php echo $category_posts_per_page; ?>">
                    <?php echo renderBlogCategoryArticlesHtml($category_posts, $category_name); ?>
                </div>

                <div id="bcatPaginationWrap">
                    <?php echo renderBlogCategoryPaginationHtml($category_posts_page, $category_posts_per_page, $category_posts_total); ?>
                </div>
            </div>

            <aside class="bcat-sidebar">
                <div class="bcat-widget">
                    <h3 class="bcat-widget__title">Popular Articles</h3>
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

    var $categoryReadMore = $('.bcat-content__read-more');
    if ($categoryReadMore.length) {
        $categoryReadMore.on('click', function () {
            var $button = $(this);
            var $detail = $('#bcatCategoryDetail');
            var isExpanded = $button.attr('aria-expanded') === 'true';

            $detail.prop('hidden', isExpanded);
            $button.attr('aria-expanded', isExpanded ? 'false' : 'true');
            $button.html(
                (isExpanded ? 'Read more' : 'Read less') +
                ' <i class="fa-solid fa-chevron-' + (isExpanded ? 'down' : 'up') + '" aria-hidden="true"></i>'
            );
        });
    }

    var $articlesWrap = $('#bcatArticles');
    if ($articlesWrap.length) {
        var categoryPostsState = {
            categoryId: parseInt($articlesWrap.data('category-id'), 10) || 0,
            perPage: parseInt($articlesWrap.data('per-page'), 10) || 5,
            page: 1,
            sort: 'latest',
            loading: false
        };
        var ajaxBase = '<?php echo rtrim($base_url, '/'); ?>/ajax/blog-category-posts.php';

        function setCategoryPostsLoading(isLoading) {
            categoryPostsState.loading = isLoading;
            $articlesWrap.toggleClass('is-loading', isLoading);
        }

        function loadCategoryPosts(page) {
            if (categoryPostsState.loading || !categoryPostsState.categoryId) {
                return;
            }

            setCategoryPostsLoading(true);

            $.getJSON(ajaxBase, {
                category_id: categoryPostsState.categoryId,
                page: page,
                sort: categoryPostsState.sort
            }).done(function (response) {
                if (!response) {
                    return;
                }

                categoryPostsState.page = response.page || page;
                $articlesWrap.html(response.articles_html || '');
                $('#bcatPaginationWrap').html(response.pagination_html || '');
                $('.bcat-toolbar__summary').html(response.summary_html || '');
            }).fail(function () {
                $articlesWrap.html('<p class="bcat-articles-empty">Unable to load articles. Please try again.</p>');
                $('#bcatPaginationWrap').empty();
            }).always(function () {
                setCategoryPostsLoading(false);
            });
        }

        $('#bcatSortSelect').on('change', function () {
            categoryPostsState.sort = $(this).val() === 'oldest' ? 'oldest' : 'latest';
            loadCategoryPosts(1);
        });

        $(document).on('click', '#bcatPaginationWrap .bcat-pagination__num, #bcatPaginationWrap .bcat-pagination__link', function (event) {
            event.preventDefault();
            var nextPage = parseInt($(this).data('page'), 10);
            if (!nextPage || nextPage === categoryPostsState.page) {
                return;
            }
            loadCategoryPosts(nextPage);
            $('html, body').animate({ scrollTop: $articlesWrap.offset().top - 120 }, 250);
        });
    }
});
</script>