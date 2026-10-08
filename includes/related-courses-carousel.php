<?php
if (empty($related_courses_result) || !$related_courses_result->num_rows) {
    return;
}

$related_courses_items = [];
while ($related_course = $related_courses_result->fetch_assoc()) {
    $related_courses_items[] = $related_course;
}

if (empty($related_courses_items)) {
    return;
}

$related_courses_count = count($related_courses_items);
$related_courses_title = isset($related_courses_title) ? $related_courses_title : 'RELATED COURSES';
$show_related_courses_assets = true;
?>
<section class="all-cources-slider home-course course-related-courses">
    <div class="container">
        <div class="heading">
            <h3><?php echo htmlspecialchars($related_courses_title); ?></h3>
        </div>
        <div class="slider">
            <div class="row">
                <div class="owl-carousel owl-theme all-carousel">
                    <?php foreach ($related_courses_items as $diploma) {
                        $related_category_slug = !empty($diploma['category_slug']) ? $diploma['category_slug'] : (isset($catSlug) ? $catSlug : '');
                        $course_url = rtrim($base_url, '/') . '/courses/' . $related_category_slug . '/' . $diploma['slug'];
                        $course_name = htmlspecialchars($diploma['name']);
                        $course_name_display = (strlen($course_name) <= 30) ? $course_name : substr($course_name, 0, 30) . '...';
                        ?>
                    <div class="item">
                        <div class="card-data">
                            <div class="cource-box">
                                <div class="img">
                                    <?php if (!empty($diploma['thumbnail'])) { ?>
                                    <figure style="margin:0;">
                                        <img src="<?php echo $base_url; ?>/images/subject/<?php echo htmlspecialchars($diploma['thumbnail']); ?>" alt="<?php echo htmlspecialchars($diploma['alternate']); ?>" title="<?php echo htmlspecialchars($diploma['thumbnail_title']); ?>">
                                        <figcaption class="visually-hidden"><?php echo htmlspecialchars($diploma['thumbnail_description']); ?></figcaption>
                                    </figure>
                                    <?php } ?>
                                </div>
                                <div class="data">
                                    <div class="data-text">
                                        <div class="head coname">
                                            <h2><?php echo $course_name_display; ?></h2>
                                        </div>
                                        <div class="stars">
                                            <ul>
                                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                <li><img src="<?php echo $base_url; ?>/assets/img/Star-3.png" alt=""></li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        <div class="cource-data">
                                            <div class="data-point">
                                                <div class="icon">
                                                    <img src="<?php echo $base_url; ?>/assets/img/bx-user.png" alt="">
                                                </div>
                                                <div class="name">
                                                    <strong><?php echo (int) $diploma['admin_total_enrolled']; ?> Enrolled</strong>
                                                </div>
                                            </div>
                                            <div class="data-point">
                                                <div class="icon">
                                                    <img src="<?php echo $base_url; ?>/assets/img/bx-laptop.png" alt="">
                                                </div>
                                                <div class="name">
                                                    <strong>Online</strong>
                                                </div>
                                            </div>
                                            <div class="data-point">
                                                <div class="icon">
                                                    <img src="<?php echo $base_url; ?>/assets/img/bx-time-five.png" alt="">
                                                </div>
                                                <div class="name">
                                                    <strong>No timeframe - Self Paced</strong>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="price">
                                            <div class="current-fee">
                                                <strong>
                                                    <?php
                                                    if (!empty($diploma['free_course']) && $diploma['free_course'] == 1) {
                                                        echo 'FREE';
                                                    } else {
                                                        $related_course_price = getCoursePrice($diploma['diplomaid']);
                                                        echo ($related_course_price == 0) ? 'FREE' : '&pound;' . $related_course_price;
                                                    }
                                                    ?>
                                                </strong>
                                            </div>
                                            <?php
                                            $prev_price = getCoursePricePrev($diploma['diplomaid']);
                                            if (!empty($prev_price)) {
                                            ?>
                                            <div class="previous-fee">
                                                <strike>&pound;<?php echo number_format($prev_price, 2); ?></strike>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="view-btn">
                                            <a href="<?php echo htmlspecialchars($course_url); ?>">View Course &gt;&gt;</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
