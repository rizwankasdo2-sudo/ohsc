<?php
if (empty($related_blogs_result) || !$related_blogs_result->num_rows) {
    return;
}

$related_blogs_items = [];
while ($blog = $related_blogs_result->fetch_assoc()) {
    $related_blogs_items[] = $blog;
}

if (empty($related_blogs_items)) {
    return;
}

$related_blogs_count = count($related_blogs_items);
$show_related_blogs_assets = true;
$use_related_blogs_carousel = $related_blogs_count > 1;
?>
<section class="blogs pb-blogs course-related-blogs">
    <div class="container">
        <div class="heading">
            <h3>Related Blog Posts</h3>
        </div>
        <div class="blogs-data">
            <div class="slider">
                <?php if ($use_related_blogs_carousel) { ?>
                <div class="owl-carousel owl-theme blogs-carousel" data-total-items="<?php echo (int) $related_blogs_count; ?>">
                    <?php foreach ($related_blogs_items as $blog) { ?>
                        <div class="item">
                            <div class="blog-box">
                                <div class="img">
                                    <figure style="margin:0;">
                                        <img src="<?php echo $base_url; ?>/images/blog/<?php echo htmlspecialchars($blog['image']); ?>" alt="<?php echo htmlspecialchars($blog['alt']); ?>" title="<?php echo htmlspecialchars($blog['image_title']); ?>">
                                        <figcaption class="visually-hidden"><?php echo htmlspecialchars($blog['image_description']); ?></figcaption>
                                    </figure>
                                </div>
                                <div class="text">
                                    <div class="blog-heading">
                                        <h2><?php echo htmlspecialchars($blog['title']); ?></h2>
                                    </div>
                                    <div class="read-btn">
                                        <a href="<?php echo $base_url; ?>/OHSC-Blog/<?php echo htmlspecialchars($blog['slug']); ?>">Read More +</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php } else { ?>
                <div class="blogs-single-item">
                    <?php $blog = $related_blogs_items[0]; ?>
                    <div class="blog-box">
                        <div class="img">
                            <figure style="margin:0;">
                                <img src="<?php echo $base_url; ?>/images/blog/<?php echo htmlspecialchars($blog['image']); ?>" alt="<?php echo htmlspecialchars($blog['alt']); ?>" title="<?php echo htmlspecialchars($blog['image_title']); ?>">
                                <figcaption class="visually-hidden"><?php echo htmlspecialchars($blog['image_description']); ?></figcaption>
                            </figure>
                        </div>
                        <div class="text">
                            <div class="blog-heading">
                                <h2><?php echo htmlspecialchars($blog['title']); ?></h2>
                            </div>
                            <div class="read-btn">
                                <a href="<?php echo $base_url; ?>/OHSC-Blog/<?php echo htmlspecialchars($blog['slug']); ?>">Read More +</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>
