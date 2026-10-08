<?php
if (empty($blog_carousel_articles)) {
    return;
}

$blog_carousel_title = isset($blog_carousel_title) ? $blog_carousel_title : 'Related Blog Posts';
?>
<section class="blog-section">
    <div class="blog-container">
        <div class="blog-section__head">
            <h2 class="blog-section__title"><?php echo htmlspecialchars($blog_carousel_title); ?></h2>
        </div>
        <div class="blog-latest-slider">
            <div class="blog-latest owl-carousel owl-theme blog-latest-carousel">
                <?php foreach ($blog_carousel_articles as $carousel_post) {
                    $carousel_url = getBlogPostLandingUrl($carousel_post);
                    $carousel_image = getBlogLandingImage272_170Url($carousel_post);
                    $carousel_alt = trim((string) $carousel_post['alt']);
                    if ($carousel_alt === '') {
                        $carousel_alt = $carousel_post['title'];
                    }
                    $carousel_cat = getBlogLandingCategoryMetaForCategoryId($db, $carousel_post['blog_category']);
                    $carousel_date = formatBlogLandingPublishDate($carousel_post['publish_date']);
                    $carousel_read = getBlogLandingMinutesRead($carousel_post);
                    ?>
                <div class="item">
                    <article class="blog-latest-card">
                        <a href="<?php echo htmlspecialchars($carousel_url); ?>"><img src="<?php echo htmlspecialchars($carousel_image); ?>" alt="<?php echo htmlspecialchars($carousel_alt); ?>" width="272" height="170"></a>
                        <div class="blog-latest-card__body">
                            <div class="blog-latest-card__category">
                                <span class="blog-meta__cat blog-meta__cat--<?php echo htmlspecialchars($carousel_cat['class']); ?>"><?php echo htmlspecialchars(getBlogCategoryLabel($db, $carousel_post['blog_category'])); ?></span>
                            </div>
                            <div class="blog-meta blog-latest-card__meta">
                                <?php if ($carousel_date !== '') { ?><span><?php echo htmlspecialchars($carousel_date); ?></span><?php } ?>
                                <?php if ($carousel_read !== '') { ?><span><?php echo htmlspecialchars($carousel_read); ?></span><?php } ?>
                            </div>
                            <div class="blog-latest-card__title">
                                <h3 class="blog-article-title blog-article-title--sm"><a href="<?php echo htmlspecialchars($carousel_url); ?>"><?php echo htmlspecialchars($carousel_post['title']); ?></a></h3>
                            </div>
                            <a href="<?php echo htmlspecialchars($carousel_url); ?>" class="blog-read-more">Read more &rarr;</a>
                        </div>
                    </article>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
