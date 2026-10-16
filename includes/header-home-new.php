<?php
$discount_row = $db->query("SELECT amount FROM tbl_discount WHERE id = '1' ")->fetch_array();
$social_row = $db->query("SELECT * FROM social_links")->fetch_array();
?>
<?php include dirname(__FILE__) . '/favicon.php'; ?>
<?php if (isset($_SERVER['HTTP_HOST']) && stripos($_SERVER['HTTP_HOST'], 'tools.kasdo.org') !== false) { ?>
<meta name="robots" content="noindex, nofollow">
<?php } ?>
<link rel="preload" href="<?php echo $pdots; ?>fonts/UcCo3FwrK3iLTcviYwY.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="<?php echo $pdots; ?>fonts/JTUSjIg1_i6t8kCHKm459Wlhyw.woff2" as="font" type="font/woff2" crossorigin>
<style>
<?php
// ORIGINAL: a missing stylesheet stopped the whole page with a fatal error.
// require_once(dirname(__FILE__) . '/../assets/css/style.css');
// require_once(dirname(__FILE__) . '/../assets/css/bootstrap.min.css');
// require_once(dirname(__FILE__) . '/../css/nd/developer.css');
foreach (array(
    dirname(__FILE__) . '/../assets/css/style.css',
    dirname(__FILE__) . '/../assets/css/bootstrap.min.css',
    dirname(__FILE__) . '/../css/nd/developer.css',
) as $ohscStylesheet) {
    if (is_file($ohscStylesheet)) {
        require $ohscStylesheet;
    }
}
?>
@font-face {
  font-family: 'Inter';
  font-style: normal;
  font-weight: 100 900;
  font-display: swap;
  src: url(<?php echo $pdots; ?>fonts/UcCo3FwrK3iLTcviYwY.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
@font-face {
  font-family: 'Montserrat';
  font-style: normal;
  font-weight: 100 900;
  font-display: swap;
  src: url(<?php echo $pdots; ?>fonts/JTUSjIg1_i6t8kCHKm459Wlhyw.woff2) format('woff2');
  unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
}
</style>
<link rel="preload" href="<?php echo $pdots; ?>assets/css/owl.carousel.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>
<body>
    <header class="header">
        <nav class="navbar navbar-expand-lg ">
            <div class="container">
                <a class="navbar-brand" href="<?php echo $base_url ?>"><img src="<?php echo $pdots; ?>assets/img/OHSC-Logo.png" alt="logo" width="210" height="79"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <div class="custom-dropdown dropdown" style="margin-top:0px;">
                                <a
  class="dropdown-toggle nav-link"
  href="#"
  role="button"
  data-bs-toggle="dropdown"
  aria-expanded="false"
  aria-haspopup="true"
  id="categoriesDropdown">
  Courses
</a>
                                <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
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
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo isset($blog_header_nav_url) ? $blog_header_nav_url : (rtrim($base_url, '/') . '/OHSC-Blog'); ?>">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $base_url . '/contact' ?>">Contact us</a>
                        </li>
                        <?php include dirname(__FILE__) . '/../tools/includes/nav-item.php'; ?>
                    </ul>
                    <div class="login-btns">
                        <div class="login">
                            <a href="<?php echo $base_url . '/lms' ?>">Login</a>
                        </div>
                        <div class="signup">
                            <a href="<?php echo $base_url . '/applyonline' ?>">Signup</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
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
              width="1200" height="112"
              alt="LAI Website"
              style="width:100%; max-width:1200px; height:auto; display:block;aspect-ratio:1200/112;"
              fetchpriority="high" loading="eager" decoding="async" />
          </picture>
        </a>
    </div>*/ ?>