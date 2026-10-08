<?php
$discount_row = $db->query("SELECT amount FROM tbl_discount WHERE id = '1' ")->fetch_array();
?>

<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="preload" href="/css/nd/font-awesome-4.7.0/css/font-awesome.min.css" as="style"
    onload="this.onload=null;this.rel='stylesheet'">
<?php
        $social_row = $db->query("SELECT * FROM social_links")->fetch_array();
        echo '<style>';
        require_once('./css/nd/main_styles.css');
        require_once('./css/nd/developer.css');
        require_once('./css/custom.css');
        require_once('./css/fonts.googleapis.css');
        require_once('./jquery-modal-master/jquery.modal.min.css');
        //require_once('./OwlCarousel2-2.3.4/docs/assets/owlcarousel/assets/owl.carousel.min.css');
        $carousel_css = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/OwlCarousel2-2.3.4/docs/assets/owlcarousel/assets/owl.carousel.min.css');
        $carousel_css = str_replace("owl.video.play.png", $pdots . "OwlCarousel2-2.3.4/docs/assets/owlcarousel/assets/owl.video.play.png", $carousel_css);
        echo $carousel_css;
        //require_once('./OwlCarousel2-2.3.4/docs/assets/owlcarousel/assets/owl.theme.default.min.css');
        $carousel_css = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/OwlCarousel2-2.3.4/docs/assets/owlcarousel/assets/owl.theme.default.min.css');
        $carousel_css = str_replace("owl.video.play.png", $pdots . "OwlCarousel2-2.3.4/docs/assets/owlcarousel/assets/owl.video.play.png", $carousel_css);
        echo $carousel_css;
        echo '</style>';
        ?>
<link rel="icon" type="image/x-icon" href="images/favicon.png">

</head>
<body>
    <div class="super_container">
        <!-- Begin top menu bar -->
        <div class="text-center">
            <div class="main-menu">
                <div>
                    <a href="/"><img src="images/thesitelogo.webp" alt="Logo" border="0" width="227"
                            height="85"></a>
                </div>
                <div class="dropdown" style="margin-left:220px">
                    <div class="vamiddle mrl40 menutxt bmoption curp">Categories</div>
                    <!-- begin new menu -->
                    <div class="cm-e-menu">
                        <ul>
                            <?php
                            $classification_result = $db->query("SELECT * FROM classification WHERE show_at_frontend = 1");
                            $_SESSION['total_classifications'] = $classification_result->num_rows;
                            while ($classification_row = $classification_result->fetch_array()) {
                                $catgories_result = $db->query("SELECT cname, slug, icon FROM tbl_courses WHERE IStatus = 1 AND classification_id = " . $classification_row['id'] . " ORDER BY ordernumber ASC");
                                if (!$catgories_result->num_rows) {
                            ?>
                            <li><a
                                    href="<?php echo $classification_row['slug']; ?>"><?php echo $classification_row['name'] . ' ';echo ($classification_row['id'] == 1 || $classification_row['id'] == 2) ? ' Qualification' : ' Courses'; ?></a>
                            </li>
                            <?php } else { ?>
                            <li class="topmenu">
                                <div class="liarrow liarrow-right liarrow-pos"></div>
                                <a
                                    href="<?php echo $classification_row['slug']; ?>"><?php echo $classification_row['name'] . ' ';echo ($classification_row['id'] == 1 || $classification_row['id'] == 2) ? ' Qualification' : ' Courses'; ?></a>
                                <ul class="submenu"
                                    style="min-height:<?php echo floor($_SESSION['total_classifications'] * 35.6); ?>px;">
                                    <?php while ($catgories_row = $catgories_result->fetch_array()) { ?>
                                    <li><a
                                            href="<?php echo $classification_row['slug'] . '/' . $catgories_row['slug']; ?>"><?php echo $catgories_row['cname']; ?></a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php }} ?>
                        </ul>
                    </div>
                    <!-- end new menu -->
                </div>
                <!-- <div class="vamiddle mrl40 bmoption">
                    <form method="post" action="search.php" name="searchfrm">
                        <div class="mfouter">
                            <div style="color:rgb(45, 47, 49) !important;margin-top:3px;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="feather feather-search curp" fill="none"
                                    height="20" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" viewBox="0 0 24 24" width="20" onclick="searchfrm.submit();">
                                    <circle cx="11" cy="11" r="8" />
                                    <line x1="21" x2="16.65" y1="21" y2="16.65" />
                                </svg>
                            </div>
                            <div class="tmsearch"><input type="text" placeholder="Search any course"
                                    class="nobdr noutline tmstext" name="course_name" /></div>
                        </div>
                    </form>
                </div> -->
                <div class="vamiddle mrl40 menutxt bmoption"><a href="<?php echo $base_url . '/OHSC-Blog' ?>">Blog</a></div>
                <div class="vamiddle mrl40 menutxt bmoption" style="margin-right:220px"><a href="<?php echo $base_url . '/contact' ?>">Contact Us</a>
                </div>
                <div class="vamiddle mrl40 bmoption">
                    <a href="<?php echo $base_url . '/lms' ?>" class="clbtn"><span>Log in</span></a>
                </div>
                <div class="vamiddle mrl5 bmoption">
                    <a href="<?php echo $base_url . '/applyonline' ?>" class="blbtn"><span>Sign up</span></a>
                </div>
                <div class="vamiddle smoption mrla fnt30">
                    <a href="<?php echo $base_url . '/ajax/general.php?task=show_main_menu' ?>" rel="modal:open" aria-label="Show Menu">
                        <i class="fa fa-bars"></i>
                    </a>
                </div>
            </div>
        </div>
        <!-- End top menu bar -->
        <?php /*<!-- Begin AdSense -->
        <div align="center">
            <!-- Horizontal Ads 1 -->
            <ins class="adsbygoogle"
                style="display:block"
                data-ad-client="ca-pub-8850775732328921"
                data-ad-slot="1209776678"
                data-ad-format="auto"
                data-full-width-responsive="true"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
        <!-- End AdSense -->
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
        