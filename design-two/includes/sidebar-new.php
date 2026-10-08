<div class="col-lg-4 col-md-4">
    <?php
    $current_page = basename($_SERVER['PHP_SELF']);
    if ($current_page == 'faq-new.php' || $current_page == 'faq.php') {
    ?>
        <div class="faqs-links">
            <div class="faq-box">
                <div class="links">
                    <a href="faq.php" style="text-decoration:none; color:white">
                        <div class="link-box">
                            <h3>FAQs</h3>
                        </div>
                    </a>
                    <?php
                    $faq_result = $db->query("SELECT name, image, slug FROM tbl_faq_topics ORDER BY order_number");
                    while ($faq_row = $faq_result->fetch_array()) {
                    ?>
                    <div class="link-box">
                        <a href="<?php echo $base_url . '/faq/' . $faq_row['slug']; ?>"><img src="<?php echo $pdots; ?>assets/img/<?php echo $faq_row['image']; ?>"><?php echo $faq_row['name']; ?></a>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php
    }
    ?>
    <div class="links-slider">
        <div class="swiper-head">
            <h3>
                <?php
                echo ($current_page == 'faq-new.php' || $current_page == 'faq.php') ? "Category Specific FAQs" : "Category";
                ?>
            </h3>
        </div>
        <div class="swiper-container link-container">
            <div class="swiper-wrapper links">
                <?php
                $menuqry = $db->query("SELECT * FROM `tbl_courses` WHERE IStatus = 1 AND previous_free_category = 0 ORDER BY ordernumber ASC");
                if ($menuqry->num_rows > 0) {
                    while ($meuresult = $menuqry->fetch_assoc()) {
                ?>
                        <div class="link-box swiper-slide">
                            <a href="<?php echo $base_url; ?>/courses/<?php echo $meuresult['slug']; ?>">
                                <img src="<?php echo $pdots; ?>assets/img/Arrow 1.png">
                                <?php echo htmlspecialchars($meuresult['cname']); ?>
                            </a>
                            <?php /*<a href="faq.php?category=<?php echo urlencode($meuresult['slug']); ?>">
                                <img src="./assets/img/Arrow 1.png">
                                <?php echo htmlspecialchars($meuresult['cname']); ?>
                            </a>*/ ?>
                        </div>
                <?php
                    }
                } else {
                    echo "<p>No categories found.</p>";
                }
                ?>
            </div>
            <div class="faq-nav">
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    </div>
</div>
