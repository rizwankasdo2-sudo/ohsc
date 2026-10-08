<header class="blog-header">
    <div class="blog-container">
        <div class="blog-header__inner">
            <a href="<?php echo $base_url; ?>" class="blog-header__logo">
                <img src="<?php echo $pdots; ?>assets/img/OHSC-Logo.webp" alt="OHSC Oxford Home Study Centre" width="210" height="79">
            </a>
            <button class="blog-header__menu-toggle" type="button" aria-label="Toggle menu" data-blog-menu-toggle>
                <i class="fa-solid fa-bars"></i>
            </button>
            <ul class="blog-header__nav" data-blog-menu>
                <li><a href="<?php echo $base_url; ?>">Home</a></li>
                <li class="blog-nav-dropdown">
                    <button class="blog-nav-dropdown__toggle" type="button">Free Courses <i class="fa-solid fa-chevron-down" style="font-size:11px;"></i></button>
                    <ul class="blog-nav-dropdown__menu">
                        <li><a href="<?php echo $base_url; ?>/free-online-courses-with-certificates">All Free Courses</a></li>
                        <?php
                        $free_courses_nav = $db->query("SELECT cname, slug FROM tbl_courses WHERE IStatus = 1 AND previous_free_category = 0 ORDER BY ordernumber ASC LIMIT 8");
                        while ($free_course_nav = $free_courses_nav->fetch_array()) {
                        ?>
                        <li><a href="<?php echo $base_url . '/courses/' . $free_course_nav['slug']; ?>"><?php echo $free_course_nav['cname']; ?></a></li>
                        <?php } ?>
                    </ul>
                </li>
                <li class="blog-nav-dropdown">
                    <button class="blog-nav-dropdown__toggle" type="button">Career Expert <i class="fa-solid fa-chevron-down" style="font-size:11px;"></i></button>
                    <ul class="blog-nav-dropdown__menu">
                        <li><a href="<?php echo $base_url; ?>/OHSC-Blog">Blog</a></li>
                        <li><a href="<?php echo $base_url; ?>/faq">FAQ</a></li>
                    </ul>
                </li>
                <li><a href="<?php echo $base_url; ?>/cpd-courses">CPD Courses</a></li>
                <li class="blog-nav-dropdown">
                    <button class="blog-nav-dropdown__toggle" type="button">About OHSC <i class="fa-solid fa-chevron-down" style="font-size:11px;"></i></button>
                    <ul class="blog-nav-dropdown__menu">
                        <li><a href="<?php echo $base_url; ?>/about-us">About Us</a></li>
                        <li><a href="<?php echo $base_url; ?>/contact">Contact Us</a></li>
                    </ul>
                </li>
                <li><a href="<?php echo $base_url; ?>/contact">Contact us</a></li>
            </ul>
            <div class="blog-header__actions">
                <button class="blog-header__search" type="button" aria-label="Search" onclick="document.getElementById('blogSearchInput').focus();">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="<?php echo $base_url; ?>/applyonline" class="blog-btn blog-btn--primary">Sign Up Free</a>
            </div>
        </div>
    </div>
</header>
