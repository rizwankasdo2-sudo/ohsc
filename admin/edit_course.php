<?php
session_start();
ob_start();
if ($_SESSION['adminid'] == "") {
    header("location:index.php");
    exit();
}
$newsid = (int) $_GET['id'];
$error_list = array();

function applyEditCoursePostToRecord(&$record, $post)
{
    $record['cname'] = isset($post['catname']) ? $post['catname'] : $record['cname'];
    $record['ordernumber'] = isset($post['order']) ? $post['order'] : $record['ordernumber'];
    $record['previous_free_category'] = isset($post['previous_free_category']) ? $post['previous_free_category'] : $record['previous_free_category'];
    $record['promo_text_heading'] = isset($post['promo_text_heading']) ? $post['promo_text_heading'] : $record['promo_text_heading'];
    $record['promo_text'] = isset($post['promo_text']) ? $post['promo_text'] : $record['promo_text'];
    $record['header_excerpt'] = isset($post['header_excerpt']) ? $post['header_excerpt'] : $record['header_excerpt'];
    $record['header_content'] = isset($post['header_content']) ? $post['header_content'] : $record['header_content'];
    $record['excerpt'] = isset($post['excerpt']) ? $post['excerpt'] : $record['excerpt'];
    $record['content'] = isset($post['content']) ? $post['content'] : $record['content'];
    $record['thumbnail_alt'] = isset($post['thumbnail_alt']) ? $post['thumbnail_alt'] : $record['thumbnail_alt'];
    $record['thumbnail_title'] = isset($post['thumbnail_title']) ? $post['thumbnail_title'] : $record['thumbnail_title'];
    $record['thumbnail_description'] = isset($post['thumbnail_description']) ? $post['thumbnail_description'] : $record['thumbnail_description'];
    $record['icon_alt'] = isset($post['icon_alt']) ? $post['icon_alt'] : $record['icon_alt'];
    $record['icon_title'] = isset($post['icon_title']) ? $post['icon_title'] : $record['icon_title'];
    $record['icon_description'] = isset($post['icon_description']) ? $post['icon_description'] : $record['icon_description'];
    $record['famous_category'] = isset($post['famous_category']) ? $post['famous_category'] : $record['famous_category'];
    $record['cvideo'] = isset($post['cvideo']) ? $post['cvideo'] : $record['cvideo'];
    $record['expected_growth'] = isset($post['expected_growth']) ? $post['expected_growth'] : $record['expected_growth'];
    $record['current_growth_rate'] = isset($post['current_growth_rate']) ? $post['current_growth_rate'] : $record['current_growth_rate'];

    if (isset($post['cat_type']) && $post['cat_type'] == 1) {
        $record['parent_ids'] = !empty($post['parent_ids']) ? implode(',', $post['parent_ids']) : '';
    } elseif (isset($post['cat_type']) && $post['cat_type'] == 0) {
        $record['parent_ids'] = '';
    }
}
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Udate Course</title>
    <link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />
    <link rel="stylesheet" href="../Resources/admin_css/add-product.css" />
    <script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>
    <script type="text/javascript" src="cms/ckeditor/ckeditor.js"></script>
    <script src="cms/ckeditor/sample.js" type="text/javascript"></script>
    <!--[if lte IE 6]>
	<script type="text/javascript" src="../Resources/admin_js/supersleight-min.js"></script>
<![endif]-->
</head>
<body>
    <div id="wrap_dashboard">
        <?php
include "classes/connection.php";
include "includes/course-slug-validator.php";

$courseQuery = $db->query("select * from tbl_courses where cid=$newsid");
$newsreslt = $courseQuery ? $courseQuery->fetch_array() : null;
if (!$newsreslt) {
    header('location: cours.php');
    exit();
}

include "includes/admin-header.php";
include "includes/admin-menu.php";
?>
        <div id="outer_contanier">
            <div id="board">
                <div id="left_corner">
                </div>
                <?php
if (isset($_POST['update'])) {
    // Begin thumbnail validation
    if (!empty($_FILES['thumbnail']['name']) && $db->query("SELECT cid FROM tbl_courses WHERE thumbnail = '" . $_FILES['thumbnail']['name'] . "' AND cid <> $newsid")->fetch_array()) {
            $error_list[] = 'Thumbnail image already exists!';
    }
    // End thumbnail validation
    // Begin icon validation
    if (!empty($_FILES['icon']['name']) && $db->query("SELECT cid FROM tbl_courses WHERE icon = '" . $_FILES['icon']['name'] . "' AND cid <> $newsid ")->fetch_array()) {
            $error_list[] = 'Icon image already exists!';
    }
    // End icon validation
    $parent_ids = ($_POST['cat_type'] == 1 && $_POST['parent_ids']) ? implode(',', $_POST['parent_ids']) : '';
    if ($parent_ids) {
        $check_row = $db->query("SELECT cid FROM tbl_courses WHERE FIND_IN_SET($newsid, parent_ids)")->fetch_array();
        if ($check_row) {
            $error_list[] = 'This category cannot have parent categories because it is already added as a parent category!';
        }
    }
    $promo_text_heading = mysqli_real_escape_string($db, filter_var(trim($_POST['promo_text_heading']), FILTER_SANITIZE_STRING));
    $promo_text = mysqli_real_escape_string($db, filter_var(trim($_POST['promo_text']), FILTER_SANITIZE_STRING));
    $order = $_POST['order'];
    $previous_free_category = $_POST['previous_free_category'];
    $freeCategoryValidation = validateUpdateCourseFreeCategory($db, $newsid, $previous_free_category);
    if (!$freeCategoryValidation['valid']) {
        $error_list[] = $freeCategoryValidation['message'];
    }
    $header_excerpt = $_POST['header_excerpt'];
    $header_content = $_POST['header_content'];
    $course_excerpt = $_POST['excerpt'];
    $course_content = $_POST['content'];
    $famous_category = $_POST['famous_category'];
    $cvideo = $_POST['cvideo'];
    $catname = $_POST['catname'];
    $thumbnail = $_FILES['thumbnail']['name'];
    $thumbnail_alt = mysqli_real_escape_string($db, filter_var(trim($_POST['thumbnail_alt']), FILTER_SANITIZE_STRING));
    $thumbnail_title = mysqli_real_escape_string($db, filter_var(trim($_POST['thumbnail_title']), FILTER_SANITIZE_STRING));
    $thumbnail_description = mysqli_real_escape_string($db, filter_var(trim($_POST['thumbnail_description']), FILTER_SANITIZE_STRING));
    $course_content = $_POST['content'];
    $expected_growth = $_POST['expected_growth'];
    $current_growth_rate = $_POST['current_growth_rate'];
    // Begin small icon handling
    $query_extend = '';
    if (isset($_FILES['icon']['name']) && empty($error_list) && !empty($_FILES['icon']['name'])) {
        $icon = $_FILES['icon']['name'];
        move_uploaded_file($_FILES['icon']['tmp_name'], '../images/nd/category/' . $icon);
        $query_extend = ", icon = '$icon' ";
    }
    // End small icon handling
    $icon_alt = mysqli_real_escape_string($db, filter_var(trim($_POST['icon_alt']), FILTER_SANITIZE_STRING));
    $icon_title = mysqli_real_escape_string($db, filter_var(trim($_POST['icon_title']), FILTER_SANITIZE_STRING));
    $icon_description = mysqli_real_escape_string($db, filter_var(trim($_POST['icon_description']), FILTER_SANITIZE_STRING));
    $update_success = false;
    if (isset($_FILES['thumbnail']['name']) && empty($error_list) && !empty($_FILES['thumbnail']['name'])) {
        $msg = "Slug Already Exists";
        $dir_name = '../images';
        $destination = $dir_name . '/' . $thumbnail;
        $tmp_name = $_FILES['thumbnail']['tmp_name'];
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
        if (isset($_FILES['thumbnail']) && empty($error_list) && !empty($_FILES['thumbnail'])) {
            if (preg_match('/[.](jpg)|(gif)|(png)|(webp)$/', $_FILES['thumbnail']['name'])) {
                $filename = $thumbnail;
                $source = $_FILES['thumbnail']['tmp_name'];
                $target = $path_to_image_directory . $filename;
                if (move_uploaded_file($source, $target)) {
                    $qry = $db->query("update tbl_courses set cname='$catname',ordernumber=$order,previous_free_category = $previous_free_category, excerpt='$course_excerpt',content='$course_content',thumbnail='$thumbnail', thumbnail_alt = '$thumbnail_alt', thumbnail_title = '$thumbnail_title', thumbnail_description = '$thumbnail_description', famous_category = '$famous_category', cvideo = '$cvideo', promo_text_heading = '$promo_text_heading', promo_text = '$promo_text', expected_growth = $expected_growth, current_growth_rate = $current_growth_rate, icon_alt = '$icon_alt', icon_title = '$icon_title', icon_description = '$icon_description'  $query_extend where cid=$newsid") or die("<script language='javascript'>window.location='cours.php?msg=$msg'</script>") . mysqli_error($db);
                    $msg = "Updated Sucssefully";
                    $update_success = true;
                }
                createThumbnail($filename);
            }
        }
    } else {
        if (empty($error_list)) {
            $msg = "Slug Already Exists";
            $qry = $db->query("update tbl_courses set parent_ids = '$parent_ids', cname='$catname',ordernumber=$order, previous_free_category = $previous_free_category, header_excerpt='$header_excerpt',header_content='$header_content',excerpt='$course_excerpt',content='$course_content', famous_category = '$famous_category', cvideo = '$cvideo', promo_text_heading = '$promo_text_heading', promo_text = '$promo_text', expected_growth = $expected_growth, current_growth_rate = $current_growth_rate,  thumbnail_alt = '$thumbnail_alt', thumbnail_title = '$thumbnail_title', thumbnail_description = '$thumbnail_description', icon_alt = '$icon_alt', icon_title = '$icon_title', icon_description = '$icon_description'  $query_extend where cid=$newsid") or die("<script language='javascript'>window.location='cours.php?msg=$msg'</script>") . mysqli_error($db);
            $msg = "Updated Sucssefully";
            $update_success = true;
        }
    }

    if ($update_success) {
        echo "<script language='javascript'>window.location='cours.php?msg=$msg'</script>";
        exit();
    }

    applyEditCoursePostToRecord($newsreslt, $_POST);
}

$parent_ids = ($newsreslt['parent_ids'] === '' || $newsreslt['parent_ids'] === null)
    ? array()
    : explode(',', $newsreslt['parent_ids']);
$catType = ($newsreslt['parent_ids'] === '' || $newsreslt['parent_ids'] === null) ? '0' : '1';
if (isset($_POST['update']) && !empty($error_list) && isset($_POST['cat_type'])) {
    $catType = $_POST['cat_type'];
}
?>
                <form action="" method="post" enctype="multipart/form-data" name="addproduct" runat="server">
                    <div id="midle_repeat">
                        <div id="tit">
                            <h3>Course</h3>
                        </div>
                    </div>
                    <div id="right_corner">
                    </div>
                    <div id="main_content">
                        <div id="addproducts">
                            <div id="uper">
                                <?php if (!empty($error_list)) { ?>
                                <ul style="color:red;font-size:30px;font-weight:bold;text-align:center;list-style-type:none;">
                                    <?php foreach ($error_list as $i) { ?>
                                    <li>- <?php echo $i; ?></li>
                                    <?php } ?>
                                </ul>
                                <?php } ?>
                                <div id="upertop">
                                    <h4>Overview</h4>
                                </div>
                                <div id="uperdetail">
                                    <div id="general">
                                        <div class="fieldrepeat">
                                            <p>Type : </p>
                                            <select name="cat_type" id="cat_type" class="productfieldlarge" style="width:140px;" onchange="if (this.value == 1) { document.getElementById('pcatdiv').style.display = 'flex'; } else { document.getElementById('pcatdiv').style.display = 'none'; }">
                                                <option value="0" <?php echo ($catType == '0') ? 'selected' : ''; ?>>Main Category</option>
                                                <option value="1" <?php echo ($catType == '1') ? 'selected' : ''; ?>>Subcategory</option>
                                            </select>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat" style="display:<?php echo ($catType == '1') ? 'flex' : 'none'; ?>;" id="pcatdiv">
                                            <p>Parent Categories : </p>
                                            <div style="font-family:Tahoma, arial;font-size:12px;">
                                                <?php
                                                $i = 0;
                                                $parent_ids_result = $db->query("SELECT cid, cname FROM tbl_courses WHERE cid <> $newsid AND parent_ids = '' ORDER BY cname ASC");
                                                while ($parent_ids_row = $parent_ids_result->fetch_array()) {
                                                    $i++;
                                                ?>
                                                <input type="checkbox" name="parent_ids[]" value="<?php echo $parent_ids_row['cid']; ?>" <?php echo in_array((string) $parent_ids_row['cid'], array_map('strval', $parent_ids), true) ? 'checked' : ''; ?> /> <?php echo $parent_ids_row['cname']; ?>
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
                                            <p>Course Name: </p>
                                            <input type="text" id="catname" name="catname"
                                                value="<?php echo $newsreslt['cname']; ?>" class="productfieldlarge"
                                                style="width:300px;" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Slug: </p>
                                            <input type="text" id="slug"
                                                value="<?php echo htmlspecialchars($newsreslt['slug']); ?>" class="productfieldlarge"
                                                style="width:300px;" readonly="readonly" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Order Number : </p>
                                            <input type="text" id="order" name="order"
                                                value="<?php echo $newsreslt['ordernumber']; ?>"
                                                class="productfieldlarge" style="width:300px;" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>This is Previous Free Category : </p>
                                            <select name="previous_free_category" id="previous_free_category" class="productfieldlarge" style="width:140px;">
                                                <option value="0" <?php echo ($newsreslt['previous_free_category'] == 0) ? 'selected' : ''; ?>>No</option>
                                                <option value="1" <?php echo ($newsreslt['previous_free_category'] == 1) ? 'selected' : ''; ?>>Yes</option>
                                            </select>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Promo Text Heading : </p>
                                            <textarea name="promo_text_heading" id="promo_text_heading" cols="60"
                                                rows="5"
                                                maxlength="100"><?php echo $newsreslt['promo_text_heading']; ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Promo Text : </p>
                                            <textarea name="promo_text" id="promo_text" cols="60" rows="10"
                                                maxlength="350"><?php echo $newsreslt['promo_text']; ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Header Content Excerpt : </p>
                                            <div style="width:80%; float:left">
                                                <textarea id="header_excerpt" name="header_excerpt" class="ckeditor" cols="30"
                                                    rows="15"><?php if (!empty($newsreslt['header_excerpt'])) {echo $newsreslt['header_excerpt'];} ?></textarea>
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Header Content Detail : </p>
                                            <div style="width:80%; float:left">
                                                <textarea id="header_content" name="header_content" class="ckeditor" cols="30"
                                                    rows="15"><?php if (!empty($newsreslt['header_content'])) {echo $newsreslt['header_content'];} ?></textarea>
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Central Content Excerpt : </p>
                                            <div style="width:80%; float:left">
                                                <textarea id="content" name="excerpt" class="ckeditor" cols="30"
                                                    rows="15">
<?php if (!empty($newsreslt['excerpt'])) {echo $newsreslt['excerpt'];}?>
</textarea>
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Central Content Detail : </p>
                                            <div style="width:80%; float:left">
                                                <textarea id="description" name="content" class="ckeditor" cols="30"
                                                    rows="15">
<?php if (!empty($newsreslt['content'])) {echo $newsreslt['content'];}?>
</textarea>
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Thumbnail : </p>
                                            <div style="float:left">
                                                <input type="file" name="thumbnail" />
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Thumbnail Alt Text : </p>
                                            <textarea name="thumbnail_alt" id="thumbnail_alt" cols="60"
                                                rows="5"><?php echo $newsreslt['thumbnail_alt']; ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Thumbnail Title : </p>
                                            <textarea name="thumbnail_title" id="thumbnail_title" cols="60"
                                                rows="5"><?php echo $newsreslt['thumbnail_title']; ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Thumbnail Description : </p>
                                            <textarea name="thumbnail_description" id="thumbnail_description" cols="60"
                                                rows="5"><?php echo $newsreslt['thumbnail_description']; ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Small Icon :<br />(37 x 33) </p>
                                            <input type="file" id="icon" name="icon" />
                                        </div>
                                        <div class="clear"> &nbsp;</div>
                                        <div class="fieldrepeat">
                                            <p>Small Icon Alt Text : </p>
                                            <textarea name="icon_alt" id="icon_alt" cols="60"
                                                rows="5"><?php echo $newsreslt['icon_alt']; ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Small Icon Title : </p>
                                            <textarea name="icon_title" id="icon_title" cols="60"
                                                rows="5"><?php echo $newsreslt['icon_title']; ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Small Icon Description : </p>
                                            <textarea name="icon_description" id="icon_description" cols="60"
                                                rows="5"><?php echo $newsreslt['icon_description']; ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Status : </p>
                                            <input type="text" id="status" class="productfieldlarge" style="width:150px;"
                                                value="<?php echo ($newsreslt['IStatus'] == 1) ? 'Enable' : 'Disable'; ?>"
                                                readonly="readonly" />
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Popular Category : </p>
                                            <select name="famous_category" id="famous_category"
                                                class="productfieldlarge" style="width:140px;">
                                                <option value="1"
                                                    <?php echo ($newsreslt['famous_category'] == 1) ? 'selected' : ''; ?>>
                                                    Yes</option>
                                                <option value="0"
                                                    <?php echo ($newsreslt['famous_category'] != 1) ? 'selected' : ''; ?>>
                                                    No</option>
                                            </select>
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Video : </p>
                                            <input type="text" name="cvideo"
                                                value="<?php if (!empty($newsreslt['cvideo'])) {echo $newsreslt['cvideo'];}?>" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Expected Growth : </p>
                                            <input type="number" step="0.01" name="expected_growth" value="<?php echo $newsreslt['expected_growth']; ?>" /> Billion
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Current Growth Rate : </p>
                                            <input type="number" step="0.01" name="current_growth_rate" value="<?php echo $newsreslt['current_growth_rate']; ?>" /> Percent
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div id="buttons" style=" width:713px; margin:0 auto; margin-top:10px;">
                                            <input type="submit" name="update" id="update" value="update" />
                                            <input type="button" value="Cancel"
                                                onClick="window.location.href='cours.php'" />
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
            <?php include "includes/admin-footer.php";?>
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
</body>
</html>