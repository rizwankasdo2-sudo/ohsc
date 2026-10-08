<?php
$discount_row = $db->query("SELECT amount FROM tbl_discount WHERE id = '1' ")->fetch_array();
$social_row = $db->query("SELECT * FROM social_links")->fetch_array();
?>
<!-- style.css -->
<link rel="stylesheet" href="./assets/css/style.css">
<!-- bootstrap css -->
<link rel="stylesheet" href="./assets/css/bootstrap.min.css">
<!-- font awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css" />
<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">
<!-- Montserrat font-->
<link
    href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">
<!--owl carousel-->
<link rel="stylesheet" href="./assets/css/owl.carousel.css">
<link rel="stylesheet" href="./assets/css/owl.theme.min.css">
<!--  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.css"
    integrity="sha512-rd0qOHVMOcez6pLWPVFIv7EfSdGKLt+eafXh4RO/12Fgr41hDQxfGvoi1Vy55QIVcQEujUE1LQrATCLl2Fs+ag=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="icon" type="image/x-icon" href="images/favicon.png">
</head>
<body>
    <header class="header">
        <nav class="navbar navbar-expand-lg ">
            <div class="container">
                <a class="navbar-brand" href="<?php echo $base_url ?>"><img src="./assets/img/OHSC Logo.png" alt="logo"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <div class="custom-dropdown dropdown">
                                <div class="dropdown-toggle nav-link" data-bs-toggle="dropdown" aria-expanded="false">
                                    Categories
                                </div>
                                <ul class="dropdown-menu">
                                    <?php
                                    $classification_result = $db->query("SELECT * FROM classification WHERE show_at_frontend = 1");
                                    $_SESSION['total_classifications'] = $classification_result->num_rows;
                                    while ($classification_row = $classification_result->fetch_array()) {
                                        $catgories_result = $db->query("SELECT cname, slug, icon FROM tbl_courses WHERE IStatus = 1 AND classification_id = " . $classification_row['id'] . " ORDER BY ordernumber ASC");
                                        if (!$catgories_result->num_rows) {
                                    ?>
                                            <li class="dropdown-item">
                                                <div class="head">
                                                    <a
                                                        href="<?php echo $classification_row['slug']; ?>"><?php echo $classification_row['name'] . ' ';
                                                                                                            echo ($classification_row['id'] == 1 || $classification_row['id'] == 2) ? ' Qualification' : ' Courses'; ?></a>
                                                    <span class="submenu-arrow"><i class="fas fa-chevron-right"></i></span>
                                                </div>
                                            </li>
                                            <?php } else { ?>
                                            <li class="dropdown-item">
                                                <div class="head">
                                                    <a
                                                        href="<?php echo $classification_row['slug']; ?>"><?php echo $classification_row['name'] . ' ';
                                                                                                            echo ($classification_row['id'] == 1 || $classification_row['id'] == 2) ? ' Qualification' : ' Courses'; ?></a>
                                                    <span class="submenu-arrow"><i class="fas fa-chevron-right"></i></span>
                                                </div>
                                                <ul class="submenu list-unstyled "
                                                    style="min-height:<?php echo floor($_SESSION['total_classifications'] * 35.6); ?>px;">
                                                    <?php while ($catgories_row = $catgories_result->fetch_array()) { ?>
                                                        <li><a
                                                                href="<?php echo $classification_row['slug'] . '/' . $catgories_row['slug']; ?>"><?php echo $catgories_row['cname']; ?></a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                            <?php }} ?>
                                            <li class="dropdown-item">
                                                <a
                                                    href="<?php echo $base_url; ?>/free-online-courses-with-certificates">Free Courses</a>
                                            </li>
                                </ul>
                            </div>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="#">Careers</a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $base_url . '/OHSC-Blog' ?>">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $base_url . '/contact' ?>">Contact us</a>
                        </li>
                    </ul>
                    <div class="login-btns">
                        <div class="login">
                            <a href="<?php echo $base_url . '/lms' ?>">Login</a>
                        </div>
                        <div class="signup">
                            <a href="<?php echo $base_url . '/apply.php' ?>">Signup</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <!-- Begin AdSense -->
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
    </div>