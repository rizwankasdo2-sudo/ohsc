<?php
session_start();
ob_start();
if ($_SESSION['adminid'] == "") {
    header("location:index.php");
    exit();
}
$newsid = (int) $_GET['id'];
$err_msg = '';
if (isset($_GET['cid'])) {
    $cid = $_GET['cid'];
}
include("classes/connection.php");
include("includes/related-diploma-helper.php");

function applyEditDiplomaPostToRecord(&$record, $post)
{
    if (isset($post['catname'])) {
        $record['name'] = $post['catname'];
    }
    if (isset($post['order'])) {
        $record['ordernumber'] = $post['order'];
    }
    if (isset($post['portal_course_id'])) {
        $record['portal_course_id'] = $post['portal_course_id'];
    }
    if (isset($post['alternate'])) {
        $record['alternate'] = $post['alternate'];
    }
    if (isset($post['thumbnail_title'])) {
        $record['thumbnail_title'] = $post['thumbnail_title'];
    }
    if (isset($post['thumbnail_description'])) {
        $record['thumbnail_description'] = $post['thumbnail_description'];
    }
    if (isset($post['square_icon_alt'])) {
        $record['square_icon_alt'] = $post['square_icon_alt'];
    }
    if (isset($post['square_icon_title'])) {
        $record['square_icon_title'] = $post['square_icon_title'];
    }
    if (isset($post['square_icon_description'])) {
        $record['square_icon_description'] = $post['square_icon_description'];
    }
    if (isset($post['category'])) {
        $record['cid'] = $post['category'];
    }
    $record['parent_ids'] = (!empty($post['parent_ids']) && is_array($post['parent_ids'])) ? implode(',', $post['parent_ids']) : '';
    $record['cid_free'] = (!empty($post['cid_free'])) ? $post['cid_free'] : 0;
    $record['course_state'] = isset($post['course_state']) ? 1 : 0;
    $record['show_in_category'] = isset($post['show_in_category']) ? 1 : 0;
    if (isset($post['color'])) {
        $record['color'] = $post['color'];
    }
    if (isset($post['famous_course'])) {
        $record['famous_course'] = $post['famous_course'];
    }
    if (isset($post['top_diploma'])) {
        $record['top_diploma'] = $post['top_diploma'];
    }
    if (isset($post['top_certification'])) {
        $record['top_certification'] = $post['top_certification'];
    }
    if (isset($post['new_course'])) {
        $record['new_course'] = $post['new_course'];
    }
    if (isset($post['type'])) {
        $record['type'] = $post['type'];
    }
    if (isset($post['admin_total_enrolled'])) {
        $record['admin_total_enrolled'] = $post['admin_total_enrolled'];
    }
    if (isset($post['admin_rating'])) {
        $record['admin_rating'] = $post['admin_rating'];
    }
    if (isset($post['total_ratings'])) {
        $record['total_ratings'] = $post['total_ratings'];
    }
    if (isset($post['total_ratings_five'])) {
        $record['total_ratings_five'] = $post['total_ratings_five'];
    }
    if (isset($post['total_ratings_four'])) {
        $record['total_ratings_four'] = $post['total_ratings_four'];
    }
    if (isset($post['total_ratings_three'])) {
        $record['total_ratings_three'] = $post['total_ratings_three'];
    }
    if (isset($post['total_ratings_two'])) {
        $record['total_ratings_two'] = $post['total_ratings_two'];
    }
    if (isset($post['total_ratings_one'])) {
        $record['total_ratings_one'] = $post['total_ratings_one'];
    }
    if (isset($post['added_on_portal'])) {
        $record['added_on_portal'] = $post['added_on_portal'];
    }
    if (isset($post['certification'])) {
        $record['certification'] = $post['certification'];
    }
    if (!empty($post['certificate_ids']) && is_array($post['certificate_ids'])) {
        $record['certificate_ids'] = implode(', ', $post['certificate_ids']);
    } else {
        $record['certificate_ids'] = '';
    }
    $exclude_id = isset($record['diplomaid']) ? (int) $record['diplomaid'] : 0;
    if (!empty($post['related_diploma_ids']) && is_array($post['related_diploma_ids'])) {
        $record['related_diploma_ids'] = formatRelatedDiplomaIds($post['related_diploma_ids'], $exclude_id);
    } else {
        $record['related_diploma_ids'] = '';
    }
}

function rebuildRelatedDiplomasFromPost($db, $post, $newsid)
{
    if (empty($post['related_diploma_ids']) || !is_array($post['related_diploma_ids'])) {
        return [];
    }

    $related_diplomas = [];
    $related_diploma_ids_raw = formatRelatedDiplomaIds($post['related_diploma_ids'], $newsid);
    $name_map = [];

    foreach (buildRelatedDiplomasFromIds($db, str_replace(', ', ',', $related_diploma_ids_raw)) as $related_row) {
        $name_map[$related_row['diplomaid']] = $related_row['name'];
    }

    foreach ($post['related_diploma_ids'] as $related_id) {
        $related_id = (int) $related_id;
        if ($related_id > 0 && isset($name_map[$related_id])) {
            $related_diplomas[] = [
                'diplomaid' => $related_id,
                'name' => $name_map[$related_id],
            ];
        } elseif ($related_id > 0) {
            $related_row = $db->query("SELECT diplomaid, name FROM tbl_diploma WHERE diplomaid = $related_id")->fetch_array();
            if ($related_row) {
                $related_diplomas[] = $related_row;
            }
        } else {
            $related_diplomas[] = [
                'diplomaid' => '',
                'name' => '',
            ];
        }
    }

    return $related_diplomas;
}

$qry = $db->query("select * from  tbl_diploma where diplomaid=$newsid");
$newsreslt = $qry->fetch_array();
$certificate_ids = array_filter(array_map('trim', explode(',', str_replace(', ', ',', $newsreslt['certificate_ids']))));
$parent_ids = ($newsreslt['parent_ids'] === '' || $newsreslt['parent_ids'] === null) ? [] : explode(',', $newsreslt['parent_ids']);
$related_diploma_ids_raw = isset($newsreslt['related_diploma_ids']) ? $newsreslt['related_diploma_ids'] : '';
$related_diplomas = buildRelatedDiplomasFromIds($db, $related_diploma_ids_raw);
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Udate Diploma</title>
    <link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />
    <link rel="stylesheet" href="../Resources/admin_css/add-product.css" />
    <script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>
    <script type="text/javascript" src="cms/ckeditor/ckeditor.js"></script>
    <script src="cms/ckeditor/sample.js" type="text/javascript"></script>
    <!--[if lte IE 6]>
    <script type="text/javascript" src="../Resources/admin_js/supersleight-min.js"></script>
    <![endif]-->
    <style type="text/css">
        #related-diploma-rows {
            float: left;
            width: 700px;
        }
        .related-diploma-row {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        .related-diploma-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .related-diploma-search {
            width: 300px;
        }
        .related-diploma-clear,
        .related-diploma-remove-row {
            border: 0;
            background: transparent;
            color: #c00;
            font-size: 20px;
            line-height: 1;
            cursor: pointer;
            padding: 0 6px;
        }
        .related-diploma-remove-row {
            margin-left: 4px;
        }
        .related-diploma-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            width: 300px;
            max-height: 220px;
            overflow-y: auto;
            margin: 0;
            padding: 0;
            list-style: none;
            border: 1px solid #ccc;
            background: #fff;
            display: none;
        }
        .related-diploma-suggestions li {
            padding: 6px 10px;
            cursor: pointer;
            font-family: Tahoma, Arial, sans-serif;
            font-size: 12px;
        }
        .related-diploma-suggestions li:hover {
            background: #f2f2f2;
        }
        #add-related-diploma-row {
            margin-top: 4px;
            padding: 4px 10px;
            cursor: pointer;
            font-family: Tahoma, Arial, sans-serif;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div id="wrap_dashboard">
        <?php
include("includes/admin-header.php");
include("includes/admin-menu.php");
?>
        <div id="outer_contanier">
            <div id="board">
                <div id="left_corner">
                </div>
                <?php
//print_r($_POST);
//exit;
if (isset($_POST['update'])) {
    // Begin image validation
    if (!empty($_FILES['thumbnail']['name']) && empty($err_msg) && $db->query("SELECT diplomaid FROM tbl_diploma WHERE (thumbnail = '" . $_FILES['thumbnail']['name'] . "' OR square_icon = '" . $_FILES['thumbnail']['name'] . "') AND diplomaid <> $newsid ")->fetch_array()) {
        $err_msg = 'Image already exists.';
    }
    // End image validation
    // Begin icon validation
    if (!empty($_FILES['square_icon']['name']) && empty($err_msg) && $db->query("SELECT diplomaid FROM tbl_diploma WHERE (thumbnail = '" . $_FILES['square_icon']['name'] . "' OR square_icon = '" . $_FILES['square_icon']['name'] . "') AND diplomaid <> $newsid ")->fetch_array()) {
        $err_msg = 'Thumbnail already exists.';
    }
    // End icon validation
    $catname = $_POST['catname'];
    $order = $_POST['order'];
    $portal_course_id = empty($_POST['portal_course_id']) ? 0 : $_POST['portal_course_id'];
    $color = $_POST['color'];
    $famous_course  =  $_POST['famous_course'];
    $top_diploma  =  $_POST['top_diploma'];
    $top_certification  =  $_POST['top_certification'];
    $new_course  =  $_POST['new_course'];
    $cid = $_POST['cid'];
    $parent_ids = ($_POST['parent_ids']) ? implode(',', $_POST['parent_ids']) : '';
    $cid_free = (!empty($_POST['cid_free'])) ? $_POST['cid_free'] : 0;
    $course_state = (int)$_POST['course_state'];
    $show_in_category = (int)$_POST['show_in_category'];
    $thumbnail = $_FILES['thumbnail']['name'];
    $alternate = mysqli_real_escape_string($db, filter_var(trim($_POST['alternate']), FILTER_SANITIZE_STRING));
    $thumbnail_title = mysqli_real_escape_string($db, filter_var(trim($_POST['thumbnail_title']), FILTER_SANITIZE_STRING));
    $thumbnail_description = mysqli_real_escape_string($db, filter_var(trim($_POST['thumbnail_description']), FILTER_SANITIZE_STRING));
    $category_edit = $_POST['category'];
    $type = $_POST['type'];
    $admin_total_enrolled = $_POST['admin_total_enrolled'];
    $admin_rating = $_POST['admin_rating'];
    $total_ratings = $_POST['total_ratings'];
    $total_ratings_five = $_POST['total_ratings_five'];
    $total_ratings_four = $_POST['total_ratings_four'];
    $total_ratings_three = $_POST['total_ratings_three'];
    $total_ratings_two = $_POST['total_ratings_two'];
    $total_ratings_one = $_POST['total_ratings_one'];
    $added_on_portal = $_POST['added_on_portal'];
    $certificate_ids = ($_POST['certificate_ids']) ? implode(', ', $_POST['certificate_ids']) : '';
    $certification = $_POST['certification'];
    $related_diploma_ids = '';

    if (!empty($_POST['related_diploma_ids']) && is_array($_POST['related_diploma_ids'])) {
        $related_ids = array_filter(array_map('intval', $_POST['related_diploma_ids']), function ($id) use ($newsid) {
            return $id > 0 && $id != $newsid;
        });
        $related_diploma_ids = implode(',', $related_ids);
    }

    $query_extend = '';
    // Begin square thumbnail handling
    if (isset($_FILES['square_icon']['name']) && !empty($_FILES['square_icon']['name']) && empty($err_msg)) {
        $fileMime = mime_content_type($_FILES['square_icon']['tmp_name']);
        $imageInfo = getimagesize($_FILES['square_icon']['tmp_name']);
        if ($fileMime != 'image/webp') {
            $err_msg = 'Thumbnail must be in webp format.';
        } else if ($imageInfo['0'] != 272 || $imageInfo['1'] != 153) {
            $err_msg = 'Thumbnail dimensions must be 272 x 153.';
        } else {
            $square_icon = $_FILES['square_icon']['name'];
            move_uploaded_file($_FILES['square_icon']['tmp_name'], '../images/subject/' . $square_icon);
            $query_extend = ", square_icon = '$square_icon' ";
        }
    }
    // End square thumbnail handling
    $square_icon_alt = mysqli_real_escape_string($db, filter_var(trim($_POST['square_icon_alt']), FILTER_SANITIZE_STRING));
    $square_icon_title = mysqli_real_escape_string($db, filter_var(trim($_POST['square_icon_title']), FILTER_SANITIZE_STRING));
    $square_icon_description = mysqli_real_escape_string($db, filter_var(trim($_POST['square_icon_description']), FILTER_SANITIZE_STRING));
    
    $update_success = false;
    if (isset($_FILES['thumbnail']['name']) && !empty($_FILES['thumbnail']['name']) && empty($err_msg)) {
        $thumbnail = $_FILES['thumbnail']['name'];
        $tmp_name = $_FILES['thumbnail']['tmp_name'];
        $dir_name = '../images/subject';
        $destination = $dir_name . '/' . $thumbnail;
        move_uploaded_file($tmp_name, $destination);
        $qry = $db->query("UPDATE tbl_diploma SET name='$catname', cid='$category_edit', parent_ids = '$parent_ids', cid_free = $cid_free, ordernumber=$order, famous_course = '$famous_course', top_diploma = $top_diploma, top_certification = $top_certification, new_course = $new_course, thumbnail='$thumbnail', alternate='$alternate', thumbnail_title = '$thumbnail_title', thumbnail_description = '$thumbnail_description', course_state='$course_state', show_in_category = $show_in_category, color = '$color', type='$type', admin_total_enrolled = '$admin_total_enrolled', admin_rating = '$admin_rating', total_ratings = '$total_ratings', total_ratings_five = '$total_ratings_five', total_ratings_four = '$total_ratings_four', total_ratings_three = '$total_ratings_three', total_ratings_two = '$total_ratings_two', total_ratings_one = '$total_ratings_one', added_on_portal = '$added_on_portal', portal_course_id = $portal_course_id, certificate_ids = '$certificate_ids', related_diploma_ids = '$related_diploma_ids', square_icon_alt = '$square_icon_alt', square_icon_title = '$square_icon_title', square_icon_description = '$square_icon_description', certification = '$certification' $query_extend WHERE diplomaid=$newsid");
        $update_success = true;
    } else if (empty($err_msg)) {
        $qry = $db->query("UPDATE tbl_diploma SET name='$catname', cid='$category_edit', parent_ids = '$parent_ids', cid_free = $cid_free, ordernumber=$order, famous_course = '$famous_course', top_diploma = $top_diploma, top_certification = $top_certification, new_course = $new_course, alternate='$alternate', thumbnail_title = '$thumbnail_title', thumbnail_description = '$thumbnail_description', course_state='$course_state', show_in_category = $show_in_category, color = '$color', type='$type', admin_total_enrolled = '$admin_total_enrolled', admin_rating = '$admin_rating', total_ratings = '$total_ratings', total_ratings_five = '$total_ratings_five', total_ratings_four = '$total_ratings_four', total_ratings_three = '$total_ratings_three', total_ratings_two = '$total_ratings_two', total_ratings_one = '$total_ratings_one', added_on_portal = '$added_on_portal', portal_course_id = $portal_course_id, certificate_ids = '$certificate_ids', related_diploma_ids = '$related_diploma_ids', square_icon_alt = '$square_icon_alt', square_icon_title = '$square_icon_title', square_icon_description = '$square_icon_description', certification = '$certification' $query_extend WHERE diplomaid=$newsid");
        $update_success = true;
    }

    if ($update_success) {
        $msg = "Updated Sucssefully";
        echo "<script language='javascript'>window.location='diploma.php?cid=$cid&msg=$msg'</script>";
        exit();
    }

    applyEditDiplomaPostToRecord($newsreslt, $_POST);
    $parent_ids = (!empty($_POST['parent_ids']) && is_array($_POST['parent_ids'])) ? $_POST['parent_ids'] : [];
    $certificate_ids = (!empty($_POST['certificate_ids']) && is_array($_POST['certificate_ids'])) ? $_POST['certificate_ids'] : [];
    $related_diplomas = rebuildRelatedDiplomasFromPost($db, $_POST, $newsid);
}
?>
                <form action="" method="post" enctype="multipart/form-data" name="addproduct" runat="server">
                    <div id="midle_repeat">
                        <div id="tit">
                            <h3>Diploma</h3>
                        </div>
                    </div>
                    <div id="right_corner">
                    </div>
                    <div id="main_content">
                        <div id="addproducts">
                            <div id="uper">
                                <div id="upertop">
                                    <h4>Overview</h4>
                                </div>
                                <?php
                                if (!empty($err_msg) ) {
                                    echo '<p style="color:black; font-size:28px; text-align:center; font-weight:bold">' . $err_msg . '</p>';
                                }
                                ?>
                                <div id="uperdetail">
                                    <div id="general">
                                        <input type="hidden" name="cid" value="<?php echo $cid; ?>" />
                                        <div class="fieldrepeat">
                                            <p>Name: </p>
                                            <input type="text" id="catname" name="catname"
                                                value="<?php echo $newsreslt['name'];?>" class="productfieldlarge"
                                                style="width:300px;" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Slug: </p>
                                            <input type="text" id="slug" class="productfieldlarge"
                                                value="<?php echo htmlspecialchars($newsreslt['slug'], ENT_QUOTES, 'UTF-8'); ?>" readonly="readonly"
                                                style="width:300px;" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Order Number : </p>
                                            <input type="text" id="order" name="order"
                                                value="<?php echo $newsreslt['ordernumber'];?>"
                                                class="productfieldlarge" style="width:300px;" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Portal Course ID : </p>
                                            <input type="number" id="portal_course_id" name="portal_course_id"
                                                value="<?php echo $newsreslt['portal_course_id'];?>"
                                                class="productfieldlarge" style="width:300px;" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Course Image : </p>
                                            <input type="file" id="thumbnail" name="thumbnail" />
                                        </div>
                                        <div class="clear"> &nbsp;</div>
                                        <div class="fieldrepeat">
                                            <p>Image Alternative Text : </p>
                                            <div style="float:left">
                                                <input type="text" name="alternate"
                                                    value="<?php echo $newsreslt['alternate']; ?>" />
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Image Title : </p>
                                            <textarea name="thumbnail_title" id="thumbnail_title" cols="60"
                                                rows="5"><?php echo $newsreslt['thumbnail_title']; ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Image Description : </p>
                                            <textarea name="thumbnail_description" id="thumbnail_description" cols="60"
                                                rows="5"><?php echo $newsreslt['thumbnail_description']; ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Course Thumbnail :<br />(272 x 153) </p>
                                            <input type="file" id="square_icon" name="square_icon" />
                                        </div>
                                        <div class="clear"> &nbsp;</div>
                                        <div class="fieldrepeat">
                                            <p>Thumbnail Alt Text : </p>
                                            <textarea name="square_icon_alt" id="square_icon_alt" cols="60"
                                                rows="5"><?php echo $newsreslt['square_icon_alt']; ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Thumbnail Title : </p>
                                            <textarea name="square_icon_title" id="square_icon_title" cols="60"
                                                rows="5"><?php echo $newsreslt['square_icon_title']; ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Thumbnail Description : </p>
                                            <textarea name="square_icon_description" id="square_icon_description" cols="60"
                                                rows="5"><?php echo $newsreslt['square_icon_description']; ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <?php if ($_SESSION['adminType'] == 2) { ?>
                                        <div class="fieldrepeat">
                                            <p>Status : </p>
                                            <input type="text" id="status" class="status" style="width:150px;"
                                                value="<?php echo ($newsreslt['IStatus'] == 1) ? 'Enable' : 'Disable'; ?>" readonly="readonly" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <?php } ?>
                                        <div class="fieldrepeat">
                                            <p>Main Category : </p>
                                            <select name="category" id="category" class="status" style="width:300px;" required>
                                                <option value="">-- Select Main Category --</option>
                                                <?php
                            $cours_cat_sql = "SELECT cid, cname FROM tbl_courses WHERE previous_free_category = 0";
                            $cours_cat_result = $db->query($cours_cat_sql);
                            while($cours_cat_row = $cours_cat_result->fetch_array()){ ?>
                                                <option value="<?php echo $cours_cat_row['cid']; ?>"
                                                    <?php if($newsreslt['cid']==$cours_cat_row['cid']){ echo "selected";} ?>>
                                                    <?php echo $cours_cat_row['cname']; ?></option>
                                                <?php }
                            ?>
                                            </select>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Subcategories : </p>
                                            <input type="radio" name="show_subcats" onclick="document.getElementById('pcatdiv').style.display = 'flex';" <?php echo ($newsreslt['parent_ids'] == '') ? '' : 'checked'; ?> /> Show Subcategories &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="show_subcats" onclick="document.getElementById('pcatdiv').style.display = 'none';" <?php echo ($newsreslt['parent_ids'] != '') ? '' : 'checked'; ?> /> Hide Subcategories
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat" style="display:<?php echo ($newsreslt['parent_ids'] == '') ? 'none' : 'flex'; ?>;" id="pcatdiv">
                                            <p> &nbsp;</p>
                                            <div style="font-family:Tahoma, arial;font-size:12px;">
                                                <?php
                                                $i = 0;
                                                $parent_ids_result = $db->query("SELECT cid, cname FROM tbl_courses WHERE parent_ids <> '' ORDER BY cname ASC");
                                                while ($parent_ids_row = $parent_ids_result->fetch_array()) {
                                                    $i++;
                                                ?>
                                                <input type="checkbox" name="parent_ids[]" value="<?php echo $parent_ids_row['cid']; ?>" <?php echo (in_array($parent_ids_row['cid'], $parent_ids)) ? 'checked' : ''; ?> /> <?php echo $parent_ids_row['cname']; ?>
                                                <?php
                                                    if ($i < 3) {
                                                        echo '&nbsp&nbsp&nbsp&nbsp&nbsp';
                                                    } else {
                                                        echo '<br /><br />';
                                                        $i = 0;
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Free Category : </p>
                                            <select name="cid_free" id="cid_free" class="status" style="width:300px;">
                                                <option value="">-- Select Free Category --</option>
                                                <?php
                            $cours_cat_sql = "SELECT cid, cname FROM tbl_courses WHERE previous_free_category = 1";
                            $cours_cat_result = $db->query($cours_cat_sql);
                            while($cours_cat_row = $cours_cat_result->fetch_array()){ ?>
                                                <option value="<?php echo $cours_cat_row['cid']; ?>"
                                                    <?php if($newsreslt['cid_free'] == $cours_cat_row['cid']){ echo "selected";} ?>>
                                                    <?php echo $cours_cat_row['cname']; ?></option>
                                                <?php }
                            ?>
                                            </select>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Free Course : </p>
                                            <div style="float:left">
                                                <input type="checkbox" value="1" name="course_state"
                                                    <?php if($newsreslt['course_state']==1){ echo 'checked'; } ?> />
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Color Image : </p>
                                            <div style="float:left">
                                                <input type="text" name="color"
                                                    value="<?php if(!empty($newsreslt['color'])){ echo $newsreslt['color']; }?>" />
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Show in Category : </p>
                                            <div style="float:left">
                                                <input type="checkbox" value="1" name="show_in_category"
                                                    <?php if($newsreslt['show_in_category']==1){ echo 'checked'; } ?> />
                                            </div>
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Popular Course : </p>
                                            <select name="famous_course" id="famous_course" class="productfieldlarge"
                                                style="width:140px;">
                                                <option value="1"
                                                    <?php echo ($newsreslt['famous_course'] == 1) ? 'selected' : ''; ?>>
                                                    Yes</option>
                                                <option value="0"
                                                    <?php echo ($newsreslt['famous_course'] != 1) ? 'selected' : ''; ?>>
                                                    No</option>
                                            </select>
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Top Diploma : </p>
                                            <select name="top_diploma" id="top_diploma" class="productfieldlarge"
                                                style="width:140px;">
                                                <option value="1"
                                                    <?php echo ($newsreslt['top_diploma'] == 1) ? 'selected' : ''; ?>>
                                                    Yes</option>
                                                <option value="0"
                                                    <?php echo ($newsreslt['top_diploma'] != 1) ? 'selected' : ''; ?>>
                                                    No</option>
                                            </select>
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Top Certification : </p>
                                            <select name="top_certification" id="top_certification" class="productfieldlarge"
                                                style="width:140px;">
                                                <option value="1"
                                                    <?php echo ($newsreslt['top_certification'] == 1) ? 'selected' : ''; ?>>
                                                    Yes</option>
                                                <option value="0"
                                                    <?php echo ($newsreslt['top_certification'] != 1) ? 'selected' : ''; ?>>
                                                    No</option>
                                            </select>
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>New Course : </p>
                                            <select name="new_course" id="new_course" class="productfieldlarge"
                                                style="width:140px;">
                                                <option value="1"
                                                    <?php echo ($newsreslt['new_course'] == 1) ? 'selected' : ''; ?>>
                                                    Yes</option>
                                                <option value="0"
                                                    <?php echo ($newsreslt['new_course'] != 1) ? 'selected' : ''; ?>>
                                                    No</option>
                                            </select>
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Type : </p>
                                            <select name="type" id="type" class="productfieldlarge"
                                                style="width:140px;">
                                                <option value="1"
                                                    <?php echo ($newsreslt['type'] == 1) ? 'selected' : ''; ?>>Paid
                                                    Course</option>
                                                <option value="2"
                                                    <?php echo ($newsreslt['type'] == 2) ? 'selected' : ''; ?>>Free
                                                    Course</option>
                                                <option value="3"
                                                    <?php echo ($newsreslt['type'] == 3) ? 'selected' : ''; ?>>Free
                                                    Course Category</option>
                                                <option value="4" <?php echo ($newsreslt['type'] == 4) ? 'selected' : ''; ?>>Regulated</option>
                                                <option value="5" <?php echo ($newsreslt['type'] == 5) ? 'selected' : ''; ?>>Accredited</option>
                                                <option value="6" <?php echo ($newsreslt['type'] == 6) ? 'selected' : ''; ?>>Endorsed</option>
                                                <option value="7" <?php echo ($newsreslt['type'] == 7) ? 'selected' : ''; ?>>CPD</option>
                                            </select>
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Total Students : </p>
                                            <input type="number" name="admin_total_enrolled" autocomplete="off"
                                                value="<?php echo $newsreslt['admin_total_enrolled']; ?>" />
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Overall Rating : </p>
                                            <select name="admin_rating" id="admin_rating" class="productfieldlarge"
                                                style="width:140px;">
                                                <?php for ($i = 5; $i > 4; $i -= 0.10) { ?>
                                                <option value="<?php echo number_format($i, 1, '.', ''); ?>"
                                                    <?php echo ($newsreslt['admin_rating'] == number_format($i, 1, '.', '')) ? 'selected' : ''; ?>>
                                                    <?php echo number_format($i, 1, '.', ''); ?></option>
                                                <?php } ?>
                                                <?php for ($i = 4; $i >= 0; $i--) { ?>
                                                <option value="<?php echo number_format($i, 1, '.', ''); ?>"
                                                    <?php echo ($newsreslt['admin_rating'] == number_format($i, 1, '.', '')) ? 'selected' : ''; ?>>
                                                    <?php echo number_format($i, 1, '.', ''); ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Total Ratings : </p>
                                            <input type="number" name="total_ratings" autocomplete="off"
                                                value="<?php echo $newsreslt['total_ratings']; ?>" />
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Total 5 Star Ratings : </p>
                                            <input type="number" name="total_ratings_five" autocomplete="off"
                                                value="<?php echo $newsreslt['total_ratings_five']; ?>" />
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Total 4 Star Ratings : </p>
                                            <input type="number" name="total_ratings_four" autocomplete="off"
                                                value="<?php echo $newsreslt['total_ratings_four']; ?>" />
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Total 3 Star Ratings : </p>
                                            <input type="number" name="total_ratings_three" autocomplete="off"
                                                value="<?php echo $newsreslt['total_ratings_three']; ?>" />
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Total 2 Star Ratings : </p>
                                            <input type="number" name="total_ratings_two" autocomplete="off"
                                                value="<?php echo $newsreslt['total_ratings_two']; ?>" />
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Total 1 Star Ratings : </p>
                                            <input type="number" name="total_ratings_one" autocomplete="off"
                                                value="<?php echo $newsreslt['total_ratings_one']; ?>" />
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Available on Portal : </p>
                                            <select name="added_on_portal" id="added_on_portal" style="width:140px;">
                                                <option value="0">--Select Portal --</option>
                                                <option value="1"
                                                    <?php echo ($newsreslt['added_on_portal'] == 1) ? 'selected' : ''; ?>>
                                                    E-Learning College</option>
                                                <option value="2"
                                                    <?php echo ($newsreslt['added_on_portal'] == 2) ? 'selected' : ''; ?>>
                                                    OHSC</option>
                                            </select>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Related Courses : </p>
                                            <div id="related-diploma-rows">
                                                <?php if (empty($related_diplomas)) { ?>
                                                <div class="related-diploma-row" data-row-type="primary">
                                                    <div class="related-diploma-input-wrap">
                                                        <input type="text" class="related-diploma-search productfieldlarge" autocomplete="off" placeholder="Start typing diploma name..." />
                                                        <input type="hidden" name="related_diploma_ids[]" class="related-diploma-id" value="" />
                                                        <ul class="related-diploma-suggestions"></ul>
                                                    </div>
                                                </div>
                                                <?php } else { ?>
                                                <?php foreach ($related_diplomas as $index => $related_diploma) { ?>
                                                <div class="related-diploma-row" data-row-type="<?php echo ($index === 0) ? 'primary' : 'additional'; ?>">
                                                    <div class="related-diploma-input-wrap">
                                                        <?php if ($index > 0) { ?>
                                                        <button type="button" class="related-diploma-clear" title="Clear selection">&times;</button>
                                                        <?php } ?>
                                                        <input type="text" class="related-diploma-search productfieldlarge" autocomplete="off" placeholder="Start typing diploma name..." value="<?php echo htmlspecialchars($related_diploma['name'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo ($index > 0) ? 'readonly="readonly"' : ''; ?> />
                                                        <input type="hidden" name="related_diploma_ids[]" class="related-diploma-id" value="<?php echo (int) $related_diploma['diplomaid']; ?>" />
                                                        <ul class="related-diploma-suggestions"></ul>
                                                    </div>
                                                    <?php if ($index > 0) { ?>
                                                    <button type="button" class="related-diploma-remove-row" title="Remove course">&times;</button>
                                                    <?php } ?>
                                                </div>
                                                <?php } ?>
                                                <?php } ?>
                                            </div>
                                            <button type="button" id="add-related-diploma-row">Associate Another Course</button>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat" style="display:flex;">
                                            <p>Related Certificates : </p>
                                            <div style="font-family:Tahoma, arial;font-size:12px;">
                                                <?php
                                                $i = 0;
                                                $certificate_result = $db->query("SELECT id, title FROM certificates ORDER BY title ASC");
                                                while ($certificate_row = $certificate_result->fetch_array()) {
                                                    $i++;
                                                ?>
                                                <input type="checkbox" name="certificate_ids[]" value="<?php echo $certificate_row['id']; ?>" <?php echo (in_array($certificate_row['id'], $certificate_ids)) ? 'checked' : ''; ?> /> <?php echo $certificate_row['title']; ?>
                                                <?php
                                                    if ($i < 2) {
                                                        echo '&nbsp&nbsp&nbsp&nbsp&nbsp';
                                                    } else {
                                                        echo '<br /><br />';
                                                        $i = 0;
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Certification : </p>
                                            <select name="certification" id="certification" style="width:140px;">
                                                <option value="0">--Select Certification --</option>
                                                <option value="1" <?php echo ($newsreslt['certification'] == '1') ? 'selected' : ''; ?>>Certificate</option>
                                                <option value="2" <?php echo ($newsreslt['certification'] == '2') ? 'selected' : ''; ?>>Diploma</option>
                                            </select>
                                        </div>
                                        <div id="buttons" style=" width:713px; margin:0 auto; margin-top:10px;">
                                            <input type="submit" name="update" id="update" value="update" />
                                            <input type="button" value="Cancel"
                                                onClick="window.location.href='diploma.php?cid=<?php echo $cid; ?>'" />
                                        </div>
                                    </div>
                                </div>
                                <div class="clear">&nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clear">&nbsp;</div>
                </form>
            </div>
            <div class="clear">&nbsp;</div>
            <?php include("includes/admin-footer.php"); ?>
        </div>
    </div>
    <script>
    CKEDITOR.replace('description', {
        filebrowserBrowseUrl: 'cms/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl: 'cms/ckfinder/ckfinder.html?Type=Images',
        filebrowserFlashBrowseUrl: 'cms/ckfinder/ckfinder.html?Type=Flash',
        filebrowserUploadUrl: 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl: 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        filebrowserFlashUploadUrl: 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
    });
    </script>
    <script type="text/javascript">
        (function () {
            var rowsContainer = document.getElementById('related-diploma-rows');
            var addRowButton = document.getElementById('add-related-diploma-row');
            var debounceTimer = null;
            var selectedIds = {};
            var excludeDiplomaId = <?php echo (int) $newsid; ?>;

            function getSelectedIds(excludeRow) {
                var ids = {};
                var rows = rowsContainer.querySelectorAll('.related-diploma-row');

                for (var i = 0; i < rows.length; i++) {
                    if (excludeRow && rows[i] === excludeRow) {
                        continue;
                    }

                    var hiddenInput = rows[i].querySelector('.related-diploma-id');
                    if (hiddenInput && hiddenInput.value) {
                        ids[hiddenInput.value] = true;
                    }
                }

                return ids;
            }

            function hideSuggestions(list) {
                list.innerHTML = '';
                list.style.display = 'none';
            }

            function toggleClearButton(row) {
                var clearButton = row.querySelector('.related-diploma-clear');
                var hiddenInput = row.querySelector('.related-diploma-id');

                if (!clearButton) {
                    return;
                }

                clearButton.style.display = hiddenInput.value ? 'inline-block' : 'none';
            }

            function clearSelection(row) {
                var searchInput = row.querySelector('.related-diploma-search');
                var hiddenInput = row.querySelector('.related-diploma-id');
                var suggestions = row.querySelector('.related-diploma-suggestions');

                if (hiddenInput.value) {
                    delete selectedIds[hiddenInput.value];
                }

                searchInput.value = '';
                hiddenInput.value = '';
                searchInput.readOnly = false;
                hideSuggestions(suggestions);
                toggleClearButton(row);
            }

            function selectSuggestion(row, item) {
                var searchInput = row.querySelector('.related-diploma-search');
                var hiddenInput = row.querySelector('.related-diploma-id');
                var suggestions = row.querySelector('.related-diploma-suggestions');
                var currentId = hiddenInput.value;
                var isPrimaryRow = row.getAttribute('data-row-type') === 'primary';

                if (currentId && currentId !== String(item.id)) {
                    delete selectedIds[currentId];
                }

                searchInput.value = item.name;
                hiddenInput.value = item.id;
                searchInput.readOnly = !isPrimaryRow;
                selectedIds[item.id] = true;
                hideSuggestions(suggestions);
                toggleClearButton(row);
            }

            function renderSuggestions(row, items) {
                var suggestions = row.querySelector('.related-diploma-suggestions');
                var takenIds = getSelectedIds(row);

                suggestions.innerHTML = '';

                for (var i = 0; i < items.length; i++) {
                    if (takenIds[String(items[i].id)]) {
                        continue;
                    }

                    var listItem = document.createElement('li');
                    listItem.textContent = items[i].name;
                    listItem.setAttribute('data-id', items[i].id);
                    listItem.setAttribute('data-name', items[i].name);
                    listItem.addEventListener('mousedown', function (event) {
                        event.preventDefault();
                        selectSuggestion(row, {
                            id: this.getAttribute('data-id'),
                            name: this.getAttribute('data-name')
                        });
                    });
                    suggestions.appendChild(listItem);
                }

                suggestions.style.display = suggestions.children.length ? 'block' : 'none';
            }

            function fetchSuggestions(row, query) {
                if (!query) {
                    hideSuggestions(row.querySelector('.related-diploma-suggestions'));
                    return;
                }

                var url = 'ajax/diploma_suggest.php?q=' + encodeURIComponent(query);

                if (excludeDiplomaId > 0) {
                    url += '&exclude_id=' + excludeDiplomaId;
                }

                fetch(url)
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (data) {
                        renderSuggestions(row, data || []);
                    })
                    .catch(function () {
                        hideSuggestions(row.querySelector('.related-diploma-suggestions'));
                    });
            }

            function bindRow(row) {
                var searchInput = row.querySelector('.related-diploma-search');
                var hiddenInput = row.querySelector('.related-diploma-id');
                var suggestions = row.querySelector('.related-diploma-suggestions');
                var clearButton = row.querySelector('.related-diploma-clear');
                var removeButton = row.querySelector('.related-diploma-remove-row');

                searchInput.addEventListener('input', function () {
                    if (hiddenInput.value) {
                        delete selectedIds[hiddenInput.value];
                        hiddenInput.value = '';
                        searchInput.readOnly = false;
                        toggleClearButton(row);
                    } else if (row.getAttribute('data-row-type') !== 'primary') {
                        searchInput.readOnly = false;
                    }

                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(function () {
                        fetchSuggestions(row, searchInput.value.trim());
                    }, 300);
                });

                searchInput.addEventListener('blur', function () {
                    setTimeout(function () {
                        hideSuggestions(suggestions);
                    }, 150);
                });

                if (clearButton) {
                    clearButton.addEventListener('click', function () {
                        clearSelection(row);
                    });
                }

                if (removeButton) {
                    removeButton.addEventListener('click', function () {
                        if (hiddenInput.value) {
                            delete selectedIds[hiddenInput.value];
                        }
                        row.parentNode.removeChild(row);
                    });
                }
            }

            function createAdditionalRow() {
                var row = document.createElement('div');
                row.className = 'related-diploma-row';
                row.setAttribute('data-row-type', 'additional');
                row.innerHTML = ''
                    + '<div class="related-diploma-input-wrap">'
                    + '<button type="button" class="related-diploma-clear" style="display:none;" title="Clear selection">&times;</button>'
                    + '<input type="text" class="related-diploma-search productfieldlarge" autocomplete="off" placeholder="Start typing diploma name..." />'
                    + '<input type="hidden" name="related_diploma_ids[]" class="related-diploma-id" value="" />'
                    + '<ul class="related-diploma-suggestions"></ul>'
                    + '</div>'
                    + '<button type="button" class="related-diploma-remove-row" title="Remove course">&times;</button>';

                rowsContainer.appendChild(row);
                bindRow(row);
            }

            var existingRows = rowsContainer.querySelectorAll('.related-diploma-row');

            for (var i = 0; i < existingRows.length; i++) {
                bindRow(existingRows[i]);

                var existingHiddenInput = existingRows[i].querySelector('.related-diploma-id');
                if (existingHiddenInput && existingHiddenInput.value) {
                    selectedIds[existingHiddenInput.value] = true;
                    toggleClearButton(existingRows[i]);
                }
            }

            addRowButton.addEventListener('click', createAdditionalRow);
        })();
    </script>
</body>
</html>