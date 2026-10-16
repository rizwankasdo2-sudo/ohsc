<?php
session_start();
ob_start();
if ($_SESSION['adminid'] == "") {
    header("location:index.php");
    exit();
}

include("classes/connection.php");
include("includes/diploma-slug-validator.php");

function redirectAddDiplomaFormError($cid, $errors)
{
    $_SESSION['add_diploma_form'] = $_POST;
    $_SESSION['add_diploma_form_errors'] = is_array($errors) ? $errors : array($errors);
    header("location: add_diploma.php?cid=" . (int) $cid);
    exit;
}

if (isset($_POST['coursename']) && isset($_POST['order']) && isset($_POST['cid'])) {
    $cid = (int) $_POST['cid'];
    $name = $_POST['coursename'];
    $slug = normalizeDiplomaSlug(isset($_POST['slug']) ? $_POST['slug'] : '', $name);

    $slugValidation = validateNewDiplomaSlug($db, $slug);
    if (!$slugValidation['valid']) {
        redirectAddDiplomaFormError($cid, $slugValidation['message']);
    }

    if (!empty($_FILES['thumbnail']['name']) && $db->query("SELECT diplomaid FROM tbl_diploma WHERE thumbnail = '" . $_FILES['thumbnail']['name'] . "' ")->fetch_array()) {
        redirectAddDiplomaFormError($cid, 'Image already exists.');
    }

    if (!empty($_FILES['square_icon']['name']) && $db->query("SELECT diplomaid FROM tbl_diploma WHERE square_icon = '" . $_FILES['square_icon']['name'] . "' ")->fetch_array()) {
        redirectAddDiplomaFormError($cid, 'Thumbnail already exists.');
    }

    $order = $_POST['order'];
    $portal_course_id = empty($_POST['portal_course_id']) ? 0 : $_POST['portal_course_id'];
    $alternate = mysqli_real_escape_string($db, filter_var(trim($_POST['alternate']), FILTER_SANITIZE_STRING));
    $thumbnail_title = mysqli_real_escape_string($db, filter_var(trim($_POST['thumbnail_title']), FILTER_SANITIZE_STRING));
    $thumbnail_description = mysqli_real_escape_string($db, filter_var(trim($_POST['thumbnail_description']), FILTER_SANITIZE_STRING));
    $status = ($_SESSION['adminType'] == 2) ? $_POST['status'] : 1;
    $famous_course = $_POST['famous_course'];
    $top_diploma = $_POST['top_diploma'];
    $top_certification = $_POST['top_certification'];
    $new_course = $_POST['new_course'];
    $parent_ids = ($_POST['parent_ids']) ? implode(',', $_POST['parent_ids']) : '';
    $cid_free = (!empty($_POST['cid_free'])) ? $_POST['cid_free'] : 0;
    $type = $_POST['type'];
    $admin_total_enrolled = empty($_POST['admin_total_enrolled']) ? 0 : $_POST['admin_total_enrolled'];
    $admin_rating = empty($_POST['admin_rating']) ? 0 : $_POST['admin_rating'];
    $total_ratings = empty($_POST['total_ratings']) ? 0 : $_POST['total_ratings'];
    $total_ratings_five = empty($_POST['total_ratings_five']) ? 0 : $_POST['total_ratings_five'];
    $total_ratings_four = empty($_POST['total_ratings_four']) ? 0 : $_POST['total_ratings_four'];
    $total_ratings_three = empty($_POST['total_ratings_three']) ? 0 : $_POST['total_ratings_three'];
    $total_ratings_two = empty($_POST['total_ratings_two']) ? 0 : $_POST['total_ratings_two'];
    $total_ratings_one = empty($_POST['total_ratings_one']) ? 0 : $_POST['total_ratings_one'];
    $added_on_portal = $_POST['added_on_portal'];
    $square_icon = '';
    $thumbnail = '';

    if (isset($_FILES['square_icon']['name']) && !empty($_FILES['square_icon']['name'])) {
        $fileMime = mime_content_type($_FILES['square_icon']['tmp_name']);
        $imageInfo = getimagesize($_FILES['square_icon']['tmp_name']);
        if ($fileMime != 'image/webp') {
            redirectAddDiplomaFormError($cid, 'Thumbnail must be in webp format.');
        } elseif ($imageInfo['0'] != 272 || $imageInfo['1'] != 153) {
            redirectAddDiplomaFormError($cid, 'Thumbnail dimensions must be 272 x 153.');
        }
        $square_icon = $_FILES['square_icon']['name'];
        move_uploaded_file($_FILES['square_icon']['tmp_name'], '../images/subject/' . $square_icon);
    }

    if (!empty($_FILES['thumbnail']['name'])) {
        $thumbnail = $_FILES['thumbnail']['name'];
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], '../images/subject/' . $thumbnail);
    }

    $square_icon_alt = mysqli_real_escape_string($db, filter_var(trim($_POST['square_icon_alt']), FILTER_SANITIZE_STRING));
    $square_icon_title = mysqli_real_escape_string($db, filter_var(trim($_POST['square_icon_title']), FILTER_SANITIZE_STRING));
    $square_icon_description = mysqli_real_escape_string($db, filter_var(trim($_POST['square_icon_description']), FILTER_SANITIZE_STRING));
    $certificate_ids = ($_POST['certificate_ids']) ? implode(', ', $_POST['certificate_ids']) : '';
    $certification = $_POST['certification'];
    $related_diploma_ids = '';
    if (!empty($_POST['related_diploma_ids']) && is_array($_POST['related_diploma_ids'])) {
        $related_ids = array_filter(array_map('intval', $_POST['related_diploma_ids']));
        $related_diploma_ids = implode(',', $related_ids);
    }

    $qry = $db->query("insert into tbl_diploma(cid, parent_ids, cid_free, name,slug,ordernumber,thumbnail,alternate,thumbnail_title,thumbnail_description,IStatus,famous_course,top_diploma,top_certification,new_course,type,square_icon,square_icon_alt, square_icon_title, square_icon_description, admin_total_enrolled, admin_rating, total_ratings, total_ratings_five, total_ratings_four, total_ratings_three, total_ratings_two, total_ratings_one, added_on_portal, portal_course_id, certificate_ids, certification, related_diploma_ids) values($cid, '$parent_ids', $cid_free, '$name','$slug',$order,'$thumbnail','$alternate','$thumbnail_title','$thumbnail_description',$status,'$famous_course', $top_diploma,$top_certification,$new_course,'$type', '$square_icon', '$square_icon_alt', '$square_icon_title', '$square_icon_description', '$admin_total_enrolled', '$admin_rating', '$total_ratings', '$total_ratings_five', '$total_ratings_four', '$total_ratings_three', '$total_ratings_two', '$total_ratings_one', '$added_on_portal', $portal_course_id, '$certificate_ids', $certification, '$related_diploma_ids')") or die(mysqli_error($db));

    unset($_SESSION['add_diploma_form']);
    unset($_SESSION['add_diploma_form_errors']);

    $msg = "Inserted Successfully";
    header("location: diploma.php?cid=$cid&msg=$msg");
    echo "<script language='javascript'>window.location='diploma.php?cid=$cid&msg=$msg'</script>";
} else {
    $msg = "Not allowed empty fields";
    header("location: cours.php?msg=$msg");
    echo "<script language='javascript'>window.location='cours.php?msg=$msg'</script>";
}
