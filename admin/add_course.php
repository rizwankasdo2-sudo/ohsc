<?php
session_start();
ob_start();
if ($_SESSION['adminid'] == "") {
    header("location:index.php");
    exit();
}
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Add Courses</title>
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

$form = array();
if (!empty($_SESSION['add_course_form'])) {
    $form = $_SESSION['add_course_form'];
    unset($_SESSION['add_course_form']);
}

function addCourseFormValue($key, $default = '')
{
    global $form;

    if (!isset($form[$key])) {
        return htmlspecialchars($default, ENT_QUOTES, 'UTF-8');
    }

    return htmlspecialchars($form[$key], ENT_QUOTES, 'UTF-8');
}

function addCourseFormSelected($key, $value)
{
    global $form;

    return (isset($form[$key]) && (string) $form[$key] === (string) $value) ? 'selected' : '';
}

function addCourseFormChecked($parentId)
{
    global $form;

    if (!isset($form['parent_ids'])) {
        return '';
    }

    $parentIds = is_array($form['parent_ids']) ? $form['parent_ids'] : array($form['parent_ids']);

    return in_array((string) $parentId, array_map('strval', $parentIds), true) ? 'checked' : '';
}

$catType = isset($form['cat_type']) ? $form['cat_type'] : '0';

include "includes/admin-header.php";

include "includes/admin-menu.php";

?>
        <div id="outer_contanier">
            <div style="color:red;font-size:30px;font-weight:bold;text-align:center;">
                <?php
                switch (isset($_GET['msg']) ? $_GET['msg'] : '') {
                    case '1':
                        echo 'Thumbnail image already exists!';
                        break;
                    case '2':
                        echo 'Icon image already exists!';
                        break;
                    case '3':
                        echo 'This slug already exists in courses.';
                        break;
                    case '4':
                        echo 'This slug conflicts with a reserved site URL path (see .htaccess).';
                        break;
                    case '5':
                        echo 'This slug already exists in CMS pages.';
                        break;
                    case '6':
                        echo 'Slug cannot be empty.';
                        break;
                    default:
                        break;
                }
                ?>
            </div>
            <div id="board">
                <div id="left_corner">
                </div>
                <form action="insertcours.php" method="post" enctype="multipart/form-data" name="addproduct"
                    runat="server">
                    <div id="midle_repeat">
                        <div id="tit">
                            <h3>Add Courses</h3>
                        </div>
                        <div id="buttons">
                            <ul style="float:right;">
                                <li><input type="submit" value="Save" class="add_cancel_button" /></li>
                                <li><a href="cours.php">Cancel</a></li>
                            </ul>
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
                                <div id="uperdetail">
                                    <div id="general">
                                        <div class="fieldrepeat">
                                            <p>Type : </p>
                                            <select name="cat_type" id="cat_type" class="productfieldlarge" style="width:140px;" onchange="if (this.value == 1) { document.getElementById('pcatdiv').style.display = 'flex'; } else { document.getElementById('pcatdiv').style.display = 'none'; }">
                                                <option value="0" <?php echo addCourseFormSelected('cat_type', '0'); ?>>Main Category</option>
                                                <option value="1" <?php echo addCourseFormSelected('cat_type', '1'); ?>>Subcategory</option>
                                            </select>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat" style="display:<?php echo ($catType == '1') ? 'flex' : 'none'; ?>;" id="pcatdiv">
                                            <p>Parent Categories : </p>
                                            <div style="font-family:Tahoma, arial;font-size:12px;">
                                                <?php
                                                $i = 0;
                                                $parent_ids_result = $db->query("SELECT cid, cname FROM tbl_courses WHERE parent_ids = '' ORDER BY cname ASC");
                                                while ($parent_ids_row = $parent_ids_result->fetch_array()) {
                                                    $i++;
                                                ?>
                                                <input type="checkbox" name="parent_ids[]" value="<?php echo $parent_ids_row['cid']; ?>" <?php echo addCourseFormChecked($parent_ids_row['cid']); ?> /> <?php echo $parent_ids_row['cname']; ?>
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
                                            <p>Course Name : </p>
                                            <input type="text" id="coursename" name="coursename"
                                                class="productfieldlarge" style="width:300px;" value="<?php echo addCourseFormValue('coursename'); ?>" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Slug : </p>
                                            <input type="text" id="slug" name="slug" class="productfieldlarge"
                                                style="width:300px;" value="<?php echo addCourseFormValue('slug'); ?>" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Order Number : </p>
                                            <input type="text" id="order" name="order" class="productfieldlarge"
                                                style="width:300px;" value="<?php echo addCourseFormValue('order'); ?>" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>This is Previous Free Category : </p>
                                            <select name="previous_free_category" id="previous_free_category" class="productfieldlarge" style="width:140px;">
                                                <option value="0" <?php echo addCourseFormSelected('previous_free_category', '0'); ?>>No</option>
                                                <option value="1" <?php echo addCourseFormSelected('previous_free_category', '1'); ?>>Yes</option>
                                            </select>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Promo Text Heading : </p>
                                            <textarea name="promo_text_heading" id="promo_text_heading" cols="60"
                                                rows="5" maxlength="100"><?php echo addCourseFormValue('promo_text_heading'); ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Promo Text : </p>
                                            <textarea name="promo_text" id="promo_text" cols="60" rows="10"
                                                maxlength="350"><?php echo addCourseFormValue('promo_text'); ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Header Content Excerpt : </p>
                                            <div style="width:80%; float:left">
                                                <textarea id="header_excerpt" name="header_excerpt" class="ckeditor" cols="30"
                                                    rows="15"><?php echo addCourseFormValue('header_excerpt'); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Header Content Detail : </p>
                                            <div style="width:80%; float:left">
                                                <textarea id="header_content" name="header_content" class="ckeditor" cols="30"
                                                    rows="15"><?php echo addCourseFormValue('header_content'); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Central Content Excerpt : </p>
                                            <div style="width:80%; float:left">
                                                <textarea id="content" name="excerpt" class="ckeditor" cols="30"
                                                    rows="15"><?php echo addCourseFormValue('excerpt'); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Central Content Detail : </p>
                                            <div style="width:80%; float:left">
                                                <textarea id="description" name="content" class="ckeditor" cols="30"
                                                    rows="15"><?php echo addCourseFormValue('content'); ?></textarea>
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
                                                rows="5"><?php echo addCourseFormValue('thumbnail_alt'); ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Thumbnail Title : </p>
                                            <textarea name="thumbnail_title" id="thumbnail_title" cols="60"
                                                rows="5"><?php echo addCourseFormValue('thumbnail_title'); ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Thumbnail Description : </p>
                                            <textarea name="thumbnail_description" id="thumbnail_description" cols="60"
                                                rows="5"><?php echo addCourseFormValue('thumbnail_description'); ?></textarea>
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
                                                rows="5"><?php echo addCourseFormValue('icon_alt'); ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Small Icon Title : </p>
                                            <textarea name="icon_title" id="icon_title" cols="60"
                                                rows="5"><?php echo addCourseFormValue('icon_title'); ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Small Icon Description : </p>
                                            <textarea name="icon_description" id="icon_description" cols="60"
                                                rows="5"><?php echo addCourseFormValue('icon_description'); ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <?php if ($_SESSION['adminType'] == 2) {?>
                                        <div class="fieldrepeat">
                                            <p>Status : </p>
                                            <select name="status" id="status" class="productfieldlarge"
                                                style="width:140px;">
                                                <option value="1" <?php echo addCourseFormSelected('status', '1'); ?>>Enable</option>
                                                <option value="0" <?php echo addCourseFormSelected('status', '0'); ?>>Disable</option>
                                            </select>
                                        </div>
                                        <?php }?>
                                        <div class="fieldrepeat">
                                            <p>Popular Category : </p>
                                            <select name="famous_category" id="famous_category"
                                                class="productfieldlarge" style="width:140px;">
                                                <option value="1" <?php echo addCourseFormSelected('famous_category', '1'); ?>>Yes</option>
                                                <option value="0" <?php echo addCourseFormSelected('famous_category', '0'); ?>>No</option>
                                            </select>
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Video : </p>
                                            <input type="text" name="cvideo" value="<?php echo addCourseFormValue('cvideo'); ?>" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Expected Growth :<br /> (By next yearend) </p>
                                            <input type="number" step="0.01" name="expected_growth" value="<?php echo addCourseFormValue('expected_growth'); ?>" /> Billion
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Current Growth Rate : </p>
                                            <input type="number" step="0.01" name="current_growth_rate" value="<?php echo addCourseFormValue('current_growth_rate'); ?>" /> Percent
                                        </div>
                                        <div class="clear">
                                        </div>
                                    </div>
                                </div>
                                <div class="clear">&nbsp;</div>
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