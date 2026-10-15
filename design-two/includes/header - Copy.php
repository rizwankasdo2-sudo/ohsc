<?php
require_once(dirname(__FILE__) . '/../../includes/constants.php');
require_once(dirname(__FILE__) . '/../../includes/functions.php');
$discount_row = $db->query("SELECT amount FROM tbl_discount WHERE id = '1' ")->fetch_array();
if ($uri_array['2']) {
    $head_catrow = $db->query("SELECT special_discount FROM tbl_courses WHERE slug = '" . $uri_array['2'] . "' ")->fetch_array();
    $special_discount = $head_catrow['special_discount'];
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php if (!empty($site_title)) { ?>
    <title><?php echo $site_title; ?></title>
    <?php
    }
    if (!empty($site_description)) {
    ?>
    <meta name="description" content="<?php echo strip_tags($site_description); ?>" />
    <?php
    }
    if (!empty($site_keywords)) {
    ?>
    <meta name="keywords" content="<?php echo strip_tags($site_keywords); ?>" />
    <?php
    }
    if (!empty($site_robots)) {
    ?>
    <meta name="robots" content="<?php echo $site_robots; ?>" />
    <?php } ?>
    <link rel="canonical" href="<?php echo htmlspecialchars(getCanonicalURL($_SERVER['REQUEST_URI']), ENT_QUOTES, 'UTF-8'); ?>">
    <!-- Favicon -->
    <link
      rel="icon"
      type="image/png"
      href="<?php echo $pdots; ?>design-two/images/favicon-96x96.png"
      sizes="96x96"
    />
    <link rel="icon" type="image/svg+xml" href="<?php echo $pdots; ?>design-two/images/favicon.svg" />
    <link rel="shortcut icon" href="<?php echo $pdots; ?>design-two/images/favicon.ico" />
    <link
      rel="apple-touch-icon"
      sizes="180x180"
      href="<?php echo $pdots; ?>design-two/images/apple-touch-icon.png"
    />
    <link rel="manifest" href="<?php echo $pdots; ?>design-two/images/site.webmanifest" />
    <link href="<?php echo $pdots; ?>css/custom.css?v=<?php echo time(); ?>" rel="stylesheet" />
    <!-- Bootstrap -->
    <link href="<?php echo $pdots; ?>design-two/css/bootstrap.min.css" rel="stylesheet" />
    <!--=== Add By Designer ===-->
    <link href="<?php echo $pdots; ?>design-two/css/style.css?v=<?php echo time(); ?>" rel="stylesheet" />
    <link href="<?php echo $pdots; ?>design-two/fonts/fonts.css" rel="stylesheet" />
    <link href="<?php echo $pdots; ?>design-two/css/responsive.css" rel="stylesheet" />
    <!-- Slick Slider -->
    <link href="<?php echo $pdots; ?>design-two/css/slick.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link href="<?php echo $pdots; ?>design-two/css/all.min.css" rel="stylesheet" />
    <!-- CSS by Developer -->
    <link href="<?php echo $pdots; ?>design-two/css/developer.css?v=<?php echo time(); ?>" rel="stylesheet" />
    <!-- CSS for JQuery modal master -->
    <!--=== Windows Phone JS Code Start ===-->
    <script>
      if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
        var msViewportStyle = document.createElement("style");
        msViewportStyle.appendChild(
          document.createTextNode("@-ms-viewport{width:auto!important}")
        );
        document.querySelector("head").appendChild(msViewportStyle);
      }
    </script>
    <style>
        .footer .footer-box ul li a
 {
    text-decoration: none;
    font-size: 14px;
    font-weight: 400;
    line-height: 17.07px;
    color: #FFFFFF;
}
    </style>
    <!--=== Windows Phone JS Code End ===-->
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <!-- Begin top menu bar -->
    <div class="text-center topheadsec">
        <div class="main-menu">
            <div>
                <a href="/"><img src="/images/thesitelogo.webp" alt="Logo" border="0" width="227" height="85"></a>
            </div>
            <div class="dropdown">
                <div class="vamiddle mrl40 menutxt bmoption curp">Categories</div>
                <!-- begin new menu -->
                <div class="cm-e-menu">
                    <ul>
                        <li class="topmenu">
                            <div class="liarrow liarrow-right liarrow-pos"></div>
                            <a href="/courses">Courses</a>
                            <ul class="submenu" style="min-height:35px;">
                                <?php
                                $catgories_result = $db->query("SELECT cname, slug, icon FROM tbl_courses WHERE IStatus = 1 ORDER BY ordernumber ASC");
                                while ($catgories_row = $catgories_result->fetch_array()) { ?>
                                <li><a href="/courses/<?php echo $catgories_row['slug']; ?>"><?php echo $catgories_row['cname']; ?></a></li>
                                <?php } ?>
                            </ul>
                        </li>
                        <li class="topmenu">
                            <?php /*<div class="liarrow liarrow-right liarrow-pos"></div>*/ ?>
                            <a href="<?php echo $base_url; ?>/free-online-courses-with-certificates">Free Courses</a>
                            <?php /*$fcc_result = $db->query("SELECT td.slug, tc.slug AS catslug, tc.cname FROM tbl_diploma AS td, tbl_courses AS tc WHERE td.type = '3' AND td.cid = tc.cid ORDER BY tc.ordernumber ASC"); ?>
                            <ul class="submenu" style="min-height:<?php echo floor($fcc_result->num_rows * 35.6); ?>px;">
                                <?php while ($fcc_row = $fcc_result->fetch_array()) { ?>
                                <li><a href="<?php echo $base_url; ?>/courses/<?php echo $fcc_row['catslug']; ?>/<?php echo $fcc_row['slug']; ?>"><?php echo $fcc_row['cname']; ?></a></li>
                                <?php } ?>
                            </ul>*/ ?>
                        </li>
                    </ul>
                </div>
                <!-- end new menu -->
            </div>
            <div class="vamiddle mrl40 bmoption">
                <form method="post" action="/search" name="searchfrm">
                    <div class="mfouter">
                        <div style="color:rgb(45, 47, 49) !important;margin-top:3px;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="feather feather-search curp" fill="none" height="20" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="20" onclick="searchfrm.submit();"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
                        </div>
                        <div class="tmsearch"><input type="text" placeholder="Search any course" class="nobdr noutline tmstext" name="course_name" /></div>
                    </div>
                </form>
            </div>
            <div class="vamiddle mrl40 menutxt bmoption"><a href="/OHSC-Blog">Blog</a></div>
            <div class="vamiddle mrl40 menutxt bmoption"><a href="/contact">Contact Us</a></div>
            <div class="vamiddle mrl40 bmoption">
                <a href="/lms/" class="clbtn">Log in</a>
            </div>
            <div class="vamiddle mrl5 bmoption">
                <a href="/apply.php" class="blbtn">Sign up</a>
            </div>
            <div class="vamiddle smoption mrla fnt30">
                <a class="curp" data-bs-toggle="modal" data-bs-target="#mainMenuModal" id="loadModalContent">
                    <i class="fa fa-bars"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- End top menu bar -->
