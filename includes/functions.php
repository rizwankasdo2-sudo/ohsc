<?php
require_once(dirname(__FILE__) . '/../admin/classes/connection.php');
// TOOLS HOST: pages that load this file keep links on tools.kasdo.org even if an older connection.php still points at the live site.
$ohscRequestHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
if (stripos($ohscRequestHost, 'tools.kasdo.org') !== false) {
    $base_url = 'https://tools.kasdo.org';
}
function getRatingCSS($star_num, $rating) {
    switch($star_num) {
        case 1:
            if ($rating == 0) {
                echo 'fa-star-o';
            } else if ($rating < 1) {
                echo 'fa-star-half-o';
            } else {
                echo 'fa-star';
            }
            break;
        case 2:
            if ($rating <= 1) {
                echo 'fa-star-o';
            } else if ($rating < 2) {
                echo 'fa-star-half-o';
            } else {
                echo 'fa-star';
            }
            break;
        case 3:
            if ($rating <= 2) {
                echo 'fa-star-o';
            } else if ($rating < 3) {
                echo 'fa-star-half-o';
            } else {
                echo 'fa-star';
            }
            break;
        case 4:
            if ($rating <= 3) {
                echo 'fa-star-o';
            } else if ($rating < 4) {
                echo 'fa-star-half-o';
            } else {
                echo 'fa-star';
            }
            break;
        default:
            if ($rating <= 4) {
                echo 'fa-star-o';
            } else if ($rating < 5) {
                echo 'fa-star-half-o';
            } else {
                echo 'fa-star';
            }
            break;
    }
}

function getRatingCSSDTwo($star_num, $rating) {
    switch($star_num) {
        case 1:
            if ($rating == 0) {
                echo 'far fa-star';
            } else if ($rating < 1) {
                echo 'fas fa-star-half-alt';
            } else {
                echo 'fa fa-star';
            }
            break;
        case 2:
            if ($rating <= 1) {
                echo 'far fa-star';
            } else if ($rating < 2) {
                echo 'fas fa-star-half-alt';
            } else {
                echo 'fa fa-star';
            }
            break;
        case 3:
            if ($rating <= 2) {
                echo 'far fa-star';
            } else if ($rating < 3) {
                echo 'fas fa-star-half-alt';
            } else {
                echo 'fa fa-star';
            }
            break;
        case 4:
            if ($rating <= 3) {
                echo 'far fa-star';
            } else if ($rating < 4) {
                echo 'fas fa-star-half-alt';
            } else {
                echo 'fa fa-star';
            }
            break;
        default:
            if ($rating <= 4) {
                echo 'far fa-star';
            } else if ($rating < 5) {
                echo 'fas fa-star-half-alt';
            } else {
                echo 'fa fa-star';
            }
            break;
    }
}

function getDiplomaModuleCount($diplomaid) {
    global $db;
    return $db->query("SELECT id FROM tbl_modules WHERE diplomaid = $diplomaid")->num_rows;
}

function getDiplomaOptionalModuleCount($diplomaid) {
    global $db;
    return $db->query("SELECT id FROM tbl_modules WHERE diplomaid = $diplomaid AND required = 0")->num_rows;
}

function getDiplomaModuleHoursCount($diplomaid) {
    global $db;
    $row = $db->query("SELECT SUM(hours) AS total_hours FROM tbl_modules WHERE diplomaid = $diplomaid")->fetch_array();
    return $row['total_hours'];
}

function getCareerCategories($cid) {
    global $db;
    $send = array();
    $result = $db->query("SELECT id, title, average_salary_from, average_salary_to, hiring_company_logo_1, hiring_company_logo_2, hiring_company_logo_3 FROM tbl_careers WHERE FIND_IN_SET($cid, REPLACE(related_categories, ' ', '')) > 0");
    while ($row = $result->fetch_array()) {
        $send[] = $row;
    }
    return $send;
}

function getFooterLinksSimple($sec_num)
{
    global $db;
    global $base_url;
    $send = '';
    $row = $db->query("SELECT id FROM home_tab WHERE FIND_IN_SET($sec_num, show_in_footer_sections)")->fetch_array();
    if ($row) {
        $send .= '<li><a href="' . $base_url . '">Home</a></li>';
    }
    $row = $db->query("SELECT payid FROM tbl_howtopay WHERE FIND_IN_SET($sec_num, show_in_footer_sections)")->fetch_array();
    if ($row) {
        $send .= '<li><a href="' . $base_url . '/how-to-pay">How to Pay</a></li>';
    }
    $row = $db->query("SELECT ohlid FROM tbl_ohltv WHERE FIND_IN_SET($sec_num, show_in_footer_sections)")->fetch_array();
    if ($row) {
        $send .= '<li><a href="' . $base_url . '/free-online-courses-with-certificates">Free Courses</a></li>';
    }
    $row = $db->query("SELECT cAboutusID FROM tbl_aboutus WHERE FIND_IN_SET($sec_num, show_in_footer_sections)")->fetch_array();
    if ($row) {
        $send .= '<li><a href="' . $base_url . '/aboutus">About Us</a></li>';
    }
    $row = $db->query("SELECT did FROM tbl_dlearning WHERE FIND_IN_SET($sec_num, show_in_footer_sections)")->fetch_array();
    if ($row) {
        $send .= '<li><a href="' . $base_url . '/distance-learning">How Distance Learning Works</a></li>';
    }
    $row = $db->query("SELECT IContactID FROM tbl_contactus WHERE FIND_IN_SET($sec_num, show_in_footer_sections)")->fetch_array();
    if ($row) {
        $send .= '<li><a href="' . $base_url . '/contact">Contact Us</a></li>';
    }
    $result = $db->query("SELECT name, slug FROM pages WHERE status = 1 AND FIND_IN_SET($sec_num, show_in_footer_sections)");
    while ($row = $result->fetch_array()) {
        $send .= '<li><a href="' . $base_url . '/' . $row['slug'] . '">' . $row['name'] . '</a></li>';
    }
    echo $send;
}

function getFooterLinks($sec_num)
{
    global $db;
    global $base_url;
    $send = '';
    $row = $db->query("SELECT id FROM home_tab WHERE FIND_IN_SET($sec_num, show_in_footer_sections)")->fetch_array();
    if ($row) {
        $send .= '<li class="footer_list_item"><a href="' . $base_url . '" class="gentxt_fbasic">Home</a></li>';
    }
    $row = $db->query("SELECT payid FROM tbl_howtopay WHERE FIND_IN_SET($sec_num, show_in_footer_sections)")->fetch_array();
    if ($row) {
        $send .= '<li class="footer_list_item"><a href="' . $base_url . '/how-to-pay" class="gentxt_fbasic">How to Pay</a></li>';
    }
    $row = $db->query("SELECT ohlid FROM tbl_ohltv WHERE FIND_IN_SET($sec_num, show_in_footer_sections)")->fetch_array();
    if ($row) {
        $send .= '<li class="footer_list_item"><a href="' . $base_url . '/free-online-courses-with-certificates" class="gentxt_fbasic">Free Courses</a></li>';
    }
    $row = $db->query("SELECT cAboutusID FROM tbl_aboutus WHERE FIND_IN_SET($sec_num, show_in_footer_sections)")->fetch_array();
    if ($row) {
        $send .= '<li class="footer_list_item"><a href="' . $base_url . '/aboutus" class="gentxt_fbasic">About Us</a></li>';
    }
    $row = $db->query("SELECT did FROM tbl_dlearning WHERE FIND_IN_SET($sec_num, show_in_footer_sections)")->fetch_array();
    if ($row) {
        $send .= '<li class="footer_list_item"><a href="' . $base_url . '/distance-learning" class="gentxt_fbasic">How Distance Learning Works</a></li>';
    }
    $row = $db->query("SELECT IContactID FROM tbl_contactus WHERE FIND_IN_SET($sec_num, show_in_footer_sections)")->fetch_array();
    if ($row) {
        $send .= '<li class="footer_list_item"><a href="' . $base_url . '/contact" class="gentxt_fbasic">Contact Us</a></li>';
    }
    $result = $db->query("SELECT name, slug FROM pages WHERE status = 1 AND FIND_IN_SET($sec_num, show_in_footer_sections)");
    while ($row = $result->fetch_array()) {
        $send .= '<li class="footer_list_item"><a href="' . $base_url . '/' . $row['slug'] . '" class="gentxt_fbasic">' . $row['name'] . '</a></li>';
    }
    echo $send;
}

// ORIGINAL: every canonical URL pointed at the production domain.
// function getCanonicalURL($full_url) {
//     $scheme = 'https';
//     $host   = 'www.oxfordhomestudy.com';
//     $path   = strtok($full_url, '?'); // strip query params
//     if ($path !== '/' && substr($path, -1) === '/') {
//         $path = rtrim($path, '/');
//     }
//     return $scheme . '://' . $host . $path;
// }
// TOOLS HOST: a request on tools.kasdo.org keeps that domain. Every other host still uses www.oxfordhomestudy.com.
function getCanonicalURL($full_url) {
    $scheme = 'https';
    $requestHost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
    if (stripos($requestHost, 'tools.kasdo.org') !== false) {
        $host = 'tools.kasdo.org';
    } else {
        $host = 'www.oxfordhomestudy.com';
    }
    $path   = strtok($full_url, '?'); // strip query params
    if ($path !== '/' && substr($path, -1) === '/') {
        $path = rtrim($path, '/');
    }
    return $scheme . '://' . $host . $path;
}

function echoCanonicalTag($path = null) {
    if ($path === null) {
        $path = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    }

    echo '<link rel="canonical" href="' . htmlspecialchars(getCanonicalURL($path), ENT_QUOTES, 'UTF-8') . '">' . "\n";
}

// ORIGINAL: price lookup assumed diploma, category, payment, and discount rows always exist.
// function getCoursePrice($diplomaid) {
//     global $db;
//     $diploma_row = $db->query("SELECT cid, free_course FROM tbl_diploma WHERE diplomaid = $diplomaid")->fetch_array();
//     $category_row = $db->query("SELECT special_discount FROM `tbl_courses` WHERE cid = " . $diploma_row['cid'])->fetch_array();
//     $payment_row = $db->query("SELECT payment, discount FROM tbl_payment WHERE diplomaid = $diplomaid")->fetch_array();
//     $discount_row = $db->query("SELECT amount FROM tbl_discount WHERE id = 1")->fetch_array();
//     $total = floatval($payment_row['payment']);
//     if ($diploma_row['free_course'] == 1) {
//         return 'Free';
//     } else if (is_numeric($payment_row['discount']) && $payment_row['discount'] != 0.00) {
//         return $total - $payment_row['discount'];
//     } else if ($category_row['special_discount']) {
//         $discount_amount = number_format(($total * $category_row['special_discount']) / 100, 2, '.', '');
//         return ($total - $discount_amount);
//     } else if ($discount_row['amount'] != 0) {
//         $discount_amount = number_format(($total * $discount_row['amount']) / 100, 2, '.', '');
//         return ($total - $discount_amount);
//     } else {
//         return $total;
//     }
// }
// LOCAL CHANGE: free courses often have no payment row. Missing rows no longer print a warning. The price is unchanged when the rows exist.
function getCoursePrice($diplomaid) {
    global $db;
    $diplomaid = (int) $diplomaid;
    $diploma_row = $db->query("SELECT cid, free_course FROM tbl_diploma WHERE diplomaid = $diplomaid")->fetch_array();
    if (!is_array($diploma_row)) {
        return 0;
    }
    if ($diploma_row['free_course'] == 1) {
        return 'Free';
    }
    $category_row = $db->query("SELECT special_discount FROM `tbl_courses` WHERE cid = " . (int) $diploma_row['cid'])->fetch_array();
    $payment_row = $db->query("SELECT payment, discount FROM tbl_payment WHERE diplomaid = $diplomaid")->fetch_array();
    $discount_row = $db->query("SELECT amount FROM tbl_discount WHERE id = 1")->fetch_array();
    $total = is_array($payment_row) ? floatval($payment_row['payment']) : 0;
    $payment_discount = is_array($payment_row) ? $payment_row['discount'] : 0;
    if (is_numeric($payment_discount) && $payment_discount != 0.00) {
        return $total - $payment_discount;
    } else if (is_array($category_row) && $category_row['special_discount']) {
        $discount_amount = number_format(($total * $category_row['special_discount']) / 100, 2, '.', '');
        return ($total - $discount_amount);
    } else if (is_array($discount_row) && $discount_row['amount'] != 0) {
        $discount_amount = number_format(($total * $discount_row['amount']) / 100, 2, '.', '');
        return ($total - $discount_amount);
    } else {
        return $total;
    }
}

// ORIGINAL: function getCoursePricePrev($diplomaid) {
//     global $db;
//     $payment_row = $db->query("SELECT payment FROM tbl_payment WHERE diplomaid = $diplomaid")->fetch_array();
//     return $payment_row['payment'];
// }
// LOCAL CHANGE: return null when a course has no payment row, instead of warning on a null result.
function getCoursePricePrev($diplomaid) {
    global $db;
    $diplomaid = (int) $diplomaid;
    $payment_row = $db->query("SELECT payment FROM tbl_payment WHERE diplomaid = $diplomaid")->fetch_array();
    return is_array($payment_row) ? $payment_row['payment'] : null;
}

function getFaqsForRelatedDiploma($db, $diploma_id) {
    $diploma_id = (int) $diploma_id;
    if ($diploma_id <= 0) {
        return false;
    }

    return $db->query(
        "SELECT * FROM tbl_faq WHERE IStatus = '1' AND related_diploma_ids IS NOT NULL AND related_diploma_ids != '' AND FIND_IN_SET('$diploma_id', REPLACE(related_diploma_ids, ' ', '')) > 0 ORDER BY ordernumber ASC"
    );
}

function getBlogsForRelatedDiploma($db, $diploma_id) {
    $diploma_id = (int) $diploma_id;
    if ($diploma_id <= 0) {
        return false;
    }

    return $db->query(
        "SELECT id, title, slug, image, alt, image_title, image_description, publish_date FROM blog WHERE status = '1' AND related_diploma_ids IS NOT NULL AND related_diploma_ids != '' AND FIND_IN_SET('$diploma_id', REPLACE(related_diploma_ids, ' ', '')) > 0 GROUP BY id ORDER BY publish_date DESC"
    );
}
