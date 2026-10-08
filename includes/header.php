<?php
require_once(dirname(__FILE__) . '/constants.php');
$discount_row = $db->query("SELECT amount FROM tbl_discount WHERE id = '1' ")->fetch_array();
if ($uri_array['2']) {
    $head_catrow = $db->query("SELECT special_discount FROM tbl_courses WHERE slug = '" . $uri_array['2'] . "' ")->fetch_array();
    $special_discount = $head_catrow['special_discount'];
}
?>
<?php include dirname(__FILE__) . '/favicon.php'; ?>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<?php
echo '<style>';
require_once(dirname(__FILE__) . '/../css/nd/main_styles.css');
require_once(dirname(__FILE__) . '/../css/nd/responsive.css');
require_once(dirname(__FILE__) . '/../css/nd/developer.css');
require_once(dirname(__FILE__) . '/../appleple-modal-video-810fe8f/css/modal-video.min.css');
require_once(dirname(__FILE__) . '/../jquery-modal-master/jquery.modal.min.css');
require_once(dirname(__FILE__) . '/../OwlCarousel2-2.3.4/docs/assets/owlcarousel/assets/owl.carousel.min.css');
require_once(dirname(__FILE__) . '/../OwlCarousel2-2.3.4/docs/assets/owlcarousel/assets/owl.theme.default.min.css');
echo '</style>';
$social_row = $db->query("SELECT * FROM social_links")->fetch_array();
?>
</head>

<body>
    <div class="super_container">
        <!-- Begin top menu bar -->
        <div class="text-center topheadsec">
            <div class="main-menu">
                <div style="margin-right:auto;">
                    <a href="<?php echo $base_url ?>"><img src="<?php echo $pdots; ?>/images/thesitelogo.webp" alt="Logo" border="0" width="227" height="85"></a>
                </div>
                <div class="dropdown">
                    <div class="vamiddle mrl40 menutxt bmoption curp">Courses</div>
                    <!-- begin new menu -->
                    <div class="cm-e-menu">
                        <ul>
                            <?php
                            $categories_result = $db->query("SELECT cname, slug FROM tbl_courses WHERE IStatus = 1 AND previous_free_category = 0 ORDER BY ordernumber ASC");
                            while ($categories_row = $categories_result->fetch_array()) {
                            ?>
                            <li class="dropdown-item">
                                <div class="head">
                                    <a
                                        href="<?php echo $base_url . '/courses/' . $categories_row['slug']; ?>"><?php echo $categories_row['cname']; ?></a>
                                </div>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- end new menu -->
                </div>
                <!-- <div class="vamiddle mrl40 bmoption">
                        <form method="post" action="https://www.oxfordhomestudy.com/search.php" name="searchfrm">
                            <div class="mfouter">
                                <div style="color:rgb(45, 47, 49) !important;margin-top:3px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="feather feather-search curp" fill="none" height="20" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="20" onclick="searchfrm.submit();"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
                                </div>
                                <div class="tmsearch"><input type="text" placeholder="Search any course" class="nobdr noutline tmstext" name="course_name" /></div>
                            </div>
                        </form>
                    </div> -->
                <div class="vamiddle mrl40 menutxt bmoption"><a href="<?php echo $base_url . '/OHSC-Blog' ?>">Blog</a></div>
                <div class="vamiddle mrl40 menutxt bmoption" style="margin-right:220px"><a href="<?php echo $base_url . '/contact' ?>">Contact Us</a></div>
                <div class="vamiddle mrl40 bmoption" style="margin-left:auto;">
                    <a href="<?php echo $base_url . '/lms' ?>" class="clbtn">Log in</a>
                </div>
                <div class="vamiddle mrl5 bmoption">
                    <a href="<?php echo $base_url . '/applyonline' ?>" class="blbtn">Sign up</a>
                </div>
                <div class="vamiddle smoption mrla fnt30">
                    <a href="<?php echo $base_url . '/ajax/general.php?task=show_main_menu' ?>" rel="modal:open" aria-label="Show Main Menu">
                        <i class="fa fa-bars"></i>
                    </a>
                </div>
            </div>
        </div>
        <!-- End top menu bar -->
        <?php
        /*$show_discount_banner = 'yes';
        if (strtolower(basename($_SERVER['PHP_SELF'])) == 'course.php') {
            $temp_row = $db->query("SELECT course_state FROM `tbl_diploma` WHERE slug='" . $_GET['slug'] . "'")->fetch_array();
            if ($temp_row['course_state'] != 0) {
                $show_discount_banner = 'no';
            }
        }*/
        $ads_class = 'ads_nodiscount';
        /*if (($special_discount) || ($discount_row['amount'] != 0 && strtolower(basename($_SERVER['PHP_SELF'])) != 'free-courses.php' && $show_discount_banner == 'yes')) {
            $ads_class = 'ads_discount';
        ?>
            <div class="alert alert-warning txtcen topbanner_discount gentxt_fbasic">
                <strong>Get <?php echo ($special_discount) ? $special_discount : $discount_row['amount']; ?>% Discount on all Courses, Offer Ending Soon.</strong>
            </div>
            <?php } ?>
            <!-- Begin Adsense Code -->*/ ?>
            <div class="text-center <?php echo $ads_class; ?>" id="mainadsdiv"></div>
            <?php /*<!-- End Adsense Code -->
            <div align="center">
                <a href="https://www.learnartificialintelligence.ai/" aria-label="Go to LAI Website">
                  <picture>
                    <!-- Small phones -->
                    <source 
                      media="(max-width: 412px)" 
                      srcset="
                        <?php echo $base_url; ?>/images/LAI_header_new_412.webp 1x,
                        <?php echo $base_url; ?>/images/LAI_header_new_824.webp 2x
                      " 
                      type="image/webp">
                
                    <!-- Larger phones -->
                    <source 
                      media="(max-width: 576px)" 
                      srcset="
                        <?php echo $base_url; ?>/images/LAI_header_new_576.webp 1x,
                        <?php echo $base_url; ?>/images/LAI_header_new_1152.webp 2x
                      " 
                      type="image/webp">
                
                    <!-- Tablets -->
                    <source 
                      media="(max-width: 768px)" 
                      srcset="
                        <?php echo $base_url; ?>/images/LAI_header_new_768.webp 1x,
                        <?php echo $base_url; ?>/images/LAI_header_new_1536.webp 2x
                      " 
                      type="image/webp">
                
                    <!-- Small laptops -->
                    <source 
                      media="(max-width: 992px)" 
                      srcset="
                        <?php echo $base_url; ?>/images/LAI_header_new_992.webp 1x,
                        <?php echo $base_url; ?>/images/LAI_header_new_1984.webp 2x
                      " 
                      type="image/webp">
                
                    <!-- Fallback -->
                    <img 
                      src="<?php echo $base_url; ?>/images/LAI_header_new.webp"
                      width="1200" height="128"
                      alt="LAI Website"
                      style="width:100%; max-width:1200px; height:auto; display:block;"
                      fetchpriority="high" loading="eager" decoding="async" />
                  </picture>
                </a>
            </div>*/ ?>