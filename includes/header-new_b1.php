<?php
require_once(dirname(__FILE__) . '/constants.php');
$discount_row = $db->query("SELECT amount FROM tbl_discount WHERE id = '1' ")->fetch_array();
$uri_array = explode('/', $_SERVER['REQUEST_URI']);
if ($uri_array['2']) {
    $head_catrow = $db->query("SELECT special_discount FROM tbl_courses WHERE slug = '" . $uri_array['2'] . "' ")->fetch_array();
    $special_discount = $head_catrow['special_discount'];
}
switch (count($uri_array)) {
    case 2:
        $pdots = '../';
        break;
    case 3:
        $pdots = '../../';
        break;
    case 4:
        $pdots = '../../../';
        break;
    case 5:
        $pdots = '../../../../';
        break;
    default:
        $pdots = './';
        break;
}
?>
<link rel="stylesheet" href="<?php echo $baseurl; ?>/assets/css/style.css">
<link rel="stylesheet" href="<?php echo $baseurl; ?>/assets/css/bootstrap.min.css">

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
<link rel="stylesheet" href="<?php echo $baseurl; ?>/assets/css/owl.carousel.css">
<link rel="stylesheet" href="<?php echo $baseurl; ?>/assets/css/owl.theme.min.css">

<!-- <link rel="stylesheet" href="../assets/css/owl.theme.min.css"> -->
<!--  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.css"
    integrity="sha512-rd0qOHVMOcez6pLWPVFIv7EfSdGKLt+eafXh4RO/12Fgr41hDQxfGvoi1Vy55QIVcQEujUE1LQrATCLl2Fs+ag=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<?php

$social_row = $db->query("SELECT * FROM social_links")->fetch_array();
?>
<link rel="icon" type="image/x-icon" href="<?php echo $pdots; ?>images/favicon.png">
</head>

<body>

    <header class="header">
        <nav class="navbar navbar-expand-lg ">
            <div class="container">
                <a class="navbar-brand" href="<?php echo $base_url ?>"><img src="../assets/img/OHSC Logo.png" alt="logo"></a>
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
                                    while ($classification_row_header = $classification_result->fetch_array()) {
                                        $catgories_result = $db->query("SELECT cname, slug, icon FROM tbl_courses WHERE IStatus = 1 AND classification_id = " . $classification_row_header['id'] . " ORDER BY ordernumber ASC");
                                        if (!$catgories_result->num_rows) {
                                    ?>
                                            <li class="dropdown-item">
                                                <div class="head">
                                                    <a href="<?php echo $base_url . '/' . $classification_row_header['slug']; ?>">
                                                        <?php echo $classification_row_header['name'] . ' ' . (($classification_row_header['id'] == 1 || $classification_row_header['id'] == 2) ? 'Qualification' : 'Courses'); ?>
                                                    </a>
                                                </div>
                                            </li>
                                        <?php } else { ?>
                                            <li class="dropdown-item">
                                                <div class="head">
                                                    <a href="<?php echo $base_url . '/' . $classification_row_header['slug']; ?>">
                                                        <?php echo $classification_row_header['name'] . ' ' . (($classification_row_header['id'] == 1 || $classification_row_header['id'] == 2) ? 'Qualification' : 'Courses'); ?>
                                                    </a>
                                                    <span class="submenu-arrow"><i class="fas fa-chevron-right"></i></span>
                                                </div>
                                                <ul class="submenu list-unstyled "
                                                    style="min-height:<?php echo floor($_SESSION['total_classifications'] * 35.6); ?>px;">
                                                    <?php while ($catgories_row = $catgories_result->fetch_array()) { ?>
                                                        <li><a href="<?php echo $base_url . '/' . $classification_row_header['slug'] . '/' . $catgories_row['slug']; ?>">
                                                                <?php echo $catgories_row['cname']; ?></a>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                    <?php }
                                    } ?>
                                </ul>
                            </div>
                        </li>
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
                            <a href="<?php echo $base_url . '/applyonline' ?>">Signup</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <!-- End top menu bar -->
    <?php
    $show_discount_banner = 'yes';
    if (strtolower(basename($_SERVER['PHP_SELF'])) == 'course.php') {
        $temp_row = $db->query("SELECT course_state FROM `tbl_diploma` WHERE slug='" . $_GET['slug'] . "'")->fetch_array();
        if ($temp_row['course_state'] != 0) {
            $show_discount_banner = 'no';
        }
    }
    $ads_class = 'ads_nodiscount';
    if (($special_discount) || ($discount_row['amount'] != 0 && strtolower(basename($_SERVER['PHP_SELF'])) != 'free-courses.php' && $show_discount_banner == 'yes')) {
        $ads_class = 'ads_discount';
    ?>
        <div class="alert alert-warning txtcen topbanner_discount gentxt_fbasic">
            <strong>Get <?php echo ($special_discount) ? $special_discount : $discount_row['amount']; ?>% Discount on all Courses, Offer Ending Soon.</strong>
        </div>
    <?php } ?>
    <!-- Begin Adsense Code -->
    <div class="text-center <?php echo $ads_class; ?>" id="mainadsdiv"></div>
    <!-- End Adsense Code -->