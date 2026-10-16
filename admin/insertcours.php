<?php
session_start();
ob_start();
if ($_SESSION['adminid'] == "") {
    header("location:index.php");
    exit();
}

include "classes/connection.php";
include "includes/course-slug-validator.php";

function redirectAddCourseFormError($msgCode)
{
    $_SESSION['add_course_form'] = $_POST;
    header('location: add_course.php?msg=' . $msgCode);
    exit;
}

if (isset($_POST['coursename']) && isset($_POST['order']) && isset($_POST['content'])) {
    // Begin thumbnail validation
    if (!empty($_FILES['thumbnail']['name']) && $db->query("SELECT cid FROM tbl_courses WHERE thumbnail = '" . $_FILES['thumbnail']['name'] . "' ")->fetch_array()) {
            redirectAddCourseFormError(1);
    }
    // End thumbnail validation
    // Begin icon validation
    if (!empty($_FILES['icon']['name']) && $db->query("SELECT cid FROM tbl_courses WHERE icon = '" . $_FILES['icon']['name'] . "' ")->fetch_array()) {
            redirectAddCourseFormError(2);
    }
    // End icon validation
    $parent_ids = ($_POST['parent_ids']) ? implode(',', $_POST['parent_ids']) : '';
    $name = $_POST['coursename'];

    $slug = normalizeCourseSlug(isset($_POST['slug']) ? $_POST['slug'] : '', $name);

    $order = $_POST['order'];
    $previous_free_category = $_POST['previous_free_category'];

    $slugValidation = validateNewCourseSlug($db, $slug, $previous_free_category);
    if (!$slugValidation['valid']) {
        redirectAddCourseFormError($slugValidation['code']);
    }

    $msg = "Slug Already Exists";
    $promo_text_heading = mysqli_real_escape_string($db, filter_var(trim($_POST['promo_text_heading']), FILTER_SANITIZE_STRING));
    $promo_text = mysqli_real_escape_string($db, filter_var(trim($_POST['promo_text']), FILTER_SANITIZE_STRING));
    $header_excerpt = $_POST['header_excerpt'];
    $header_content = $_POST['header_content'];
    $excerpt = $_POST['excerpt'];
    $course_content = $_POST['content'];
    $thumbnail = $_FILES['thumbnail']['name'];
    $thumbnail_alt = mysqli_real_escape_string($db, filter_var(trim($_POST['thumbnail_alt']), FILTER_SANITIZE_STRING));
    $thumbnail_title = mysqli_real_escape_string($db, filter_var(trim($_POST['thumbnail_title']), FILTER_SANITIZE_STRING));
    $thumbnail_description = mysqli_real_escape_string($db, filter_var(trim($_POST['thumbnail_description']), FILTER_SANITIZE_STRING));
    $status = ($_SESSION['adminType'] == 2) ? $_POST['status'] : 1;
    $famous_category = $_POST['famous_category'];
    $cvideo = $_POST['cvideo'];
    $expected_growth = $_POST['expected_growth'];
    $current_growth_rate = $_POST['current_growth_rate'];
    $icon = '';
    // Begin small icon handling
    if (isset($_FILES['icon']['name']) && !empty($_FILES['icon']['name'])) {
        $icon = $_FILES['icon']['name'];
        move_uploaded_file($_FILES['icon']['tmp_name'], '../images/nd/category/' . $icon);
    }
    // End small icon handling
    $icon_alt = mysqli_real_escape_string($db, filter_var(trim($_POST['icon_alt']), FILTER_SANITIZE_STRING));
    $icon_title = mysqli_real_escape_string($db, filter_var(trim($_POST['icon_title']), FILTER_SANITIZE_STRING));
    $icon_description = mysqli_real_escape_string($db, filter_var(trim($_POST['icon_description']), FILTER_SANITIZE_STRING));
    $path_to_image_directory = '../images/';
    $path_to_thumbs_directory = '../images/thumbs/';

    function createThumbnail($filename)
    {
        $path_to_image_directory = '../images/';
        $path_to_thumbs_directory = '../images/thumbs/';
        if (preg_match('/[.](jpg)$/', $filename)) {
            $im = imagecreatefromjpeg($path_to_image_directory . $filename);
        } else if (preg_match('/[.](gif)$/', $filename)) {
            $im = imagecreatefromgif($path_to_image_directory . $filename);
        } else if (preg_match('/[.](png)$/', $filename)) {
            $im = imagecreatefrompng($path_to_image_directory . $filename);
        } else if (preg_match('/[.](webp)$/', $filename)) {
            $im = imagecreatefromwebp($path_to_image_directory . $filename);
        }

        $ox = imagesx($im);
        $oy = imagesy($im);
        $final_width_of_image = 264;
        $nx = $final_width_of_image;
        $ny = 100;

        $nm = imagecreatetruecolor($nx, $ny);

        imagecopyresampled($nm, $im, 0, 0, 0, 0, $nx, $ny, $ox, $oy);

        if (!file_exists($path_to_thumbs_directory)) {
            if (!mkdir($path_to_thumbs_directory)) {
                die("There was a problem. Please try again!");
            }
        }

        imagejpeg($nm, $path_to_thumbs_directory . $filename);
    }
    if (isset($_FILES['thumbnail'])) {

        if (preg_match('/[.](jpg)|(gif)|(png)|(webp)$/', $_FILES['thumbnail']['name'])) {
            $filename = $thumbnail;
            $source = $_FILES['thumbnail']['tmp_name'];
            $target = $path_to_image_directory . $filename;

            move_uploaded_file($source, $target);

            createThumbnail($filename);
        }
    }
    $qry = $db->query("insert into tbl_courses(parent_ids, cname,slug,ordernumber, previous_free_category, promo_text_heading,promo_text,header_excerpt,header_content,excerpt,content,thumbnail, thumbnail_alt, thumbnail_title, thumbnail_description, IStatus, famous_category, cvideo, icon, icon_alt, icon_title, icon_description, expected_growth, current_growth_rate) values('$parent_ids', '$name','$slug',$order, $previous_free_category, '$promo_text_heading','$promo_text','$header_excerpt','$header_content','$excerpt','$course_content','$thumbnail', '$thumbnail_alt', '$thumbnail_title', '$thumbnail_description', $status, '$famous_category', '$cvideo', '$icon', '$icon_alt', '$icon_title', '$icon_description', '$expected_growth', '$current_growth_rate')") or die("<script language='javascript'>window.location='cours.php?msg=$msg'</script>") . mysqli_error($db);
    unset($_SESSION['add_course_form']);
    $msg = "Inserted Successfully";
    header("location: cours.php?msg=$msg");
    echo "<script language='javascript'>window.location='cours.php?msg=$msg'</script>";
} else {
    $msg = "Not allowed empty fields";
    header("location: cours.php?msg=$msg");
    echo "<script language='javascript'>window.location='cours.php?msg=$msg'</script>";
}
