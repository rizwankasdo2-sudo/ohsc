<?php
session_start();
ob_start();
if ($_SESSION['adminid'] == "") {
    header("location:index.php");
    exit();
}
$_POST['show_in_faq_topics'] = array();
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Add FAQ</title>
    <link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />
    <link rel="stylesheet" href="../Resources/admin_css/add-product.css" />
    <script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>
    <script type="text/javascript" src="cms/ckeditor/ckeditor.js"></script>
    <script src="cms/ckeditor/sample.js" type="text/javascript"></script>
    <!--[if lte IE 6]>
        <script type="text/javascript" src="../Resources/admin_js/supersleight-min.js"></script>
        <![endif]-->
    <?php include 'includes/related-diploma-styles.php'; ?>
</head>
<body>
    <div id="wrap_dashboard">
        <?php
include "classes/connection.php";
include "includes/admin-header.php";
include "includes/admin-menu.php";
?>
        <div id="outer_contanier">
            <div id="board">
                <div id="left_corner">
                </div>
                <form action="insert_faq.php" method="post" enctype="multipart/form-data" name="addproduct"
                    runat="server">
                    <div id="midle_repeat">
                        <div id="tit">
                            <h3>Add FAQ</h3>
                        </div>
                        <div id="buttons">
                            <ul style="float:right;">
                                <li><input type="submit" value="Save" class="add_cancel_button" /></li>
                                <li><a href="faq.php">Cancel</a></li>
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
                                            <p>FAQ Category : </p>
                                            <select name="category_id" id="category_id">
                                                <option selected value="0">Select Category</option>
                                                <?php
$category_result = $db->query("SELECT cid, cname FROM tbl_courses WHERE IStatus = '1' ORDER BY cname ASC");
while ($category_row = $category_result->fetch_array()) {
    ?>
                                                <option value="<?php echo $category_row['cid']; ?>">
                                                    <?php echo $category_row['cname']; ?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>FAQ Free Course :</p>
                                            <select name="diploma_id" id="diploma_id">
                                                <option value="0">Select Free Course Category</option>
                                                <?php
                                                $diploma_result = $db->query("SELECT diplomaid, name FROM tbl_diploma WHERE type = 3 ORDER BY name ASC");
                                                
                                                while ($diploma_row = $diploma_result->fetch_array()) {
                                                    ?>
                                                    <option value="<?php echo $diploma_row['diplomaid']; ?>"
                                                        <?php echo ($diploma_row['diplomaid'] == $newsreslt['course_id']) ? 'selected' : ''; ?>>
                                                        <?php echo $diploma_row['name']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Related Course Subcategories : </p>
                                            <input type="radio" name="show_subcats" onclick="document.getElementById('pcatdiv').style.display = 'flex';" /> Show Subcategories &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="show_subcats" onclick="document.getElementById('pcatdiv').style.display = 'none';" checked /> Hide Subcategories
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat" style="display:none;" id="pcatdiv">
                                            <p> &nbsp;</p>
                                            <div style="font-family:Tahoma, arial;font-size:12px;">
                                                <?php
                                                $i = 0;
                                                $parent_ids_result = $db->query("SELECT cid, cname FROM tbl_courses WHERE parent_ids <> '' ORDER BY cname ASC");
                                                while ($parent_ids_row = $parent_ids_result->fetch_array()) {
                                                    $i++;
                                                ?>
                                                <input type="checkbox" name="subcat_ids[]" value="<?php echo $parent_ids_row['cid']; ?>" /> <?php echo $parent_ids_row['cname']; ?>
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
                                            <p>Related Course Categories : </p>
                                            <input type="radio" name="show_cats" onclick="document.getElementById('catdiv').style.display = 'flex';" /> Show categories &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="show_cats" onclick="document.getElementById('catdiv').style.display = 'none';" checked /> Hide categories
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat" style="display:none;" id="catdiv">
                                            <p> &nbsp;</p>
                                            <div style="font-family:Tahoma, arial;font-size:12px;">
                                                <?php
                                                $i = 0;
                                                $cat_ids_result = $db->query("SELECT cid, cname FROM tbl_courses WHERE parent_ids = '' ORDER BY cname ASC");
                                                while ($cat_ids_row = $cat_ids_result->fetch_array()) {
                                                    $i++;
                                                ?>
                                                <input type="checkbox" name="cat_ids[]" value="<?php echo $cat_ids_row['cid']; ?>" /> <?php echo $cat_ids_row['cname']; ?>
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
                                        <?php
                                        $related_diplomas = [];
                                        include 'includes/related-diploma-field.php';
                                        ?>
                                        <div class="fieldrepeat">
                                            <p>FAQ Description : </p>
                                            <textarea name="NewsDetail" id="NewsDetail" cols="60" rows="10"></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>FAQ Detail : </p>
                                            <div style="width:80%; float:left">
                                                <textarea id="description" name="description" class="ckeditor" cols="30"
                                                    rows="15">
                                                    </textarea>
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Status : </p>
                                            <select name="status" id="status" class="productfieldlarge"
                                                style="width:140px;">
                                                <option value="1">Enable</option>
                                                <option value="0">Disable</option>
                                            </select>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p style="margin-top:-2px;">Show In Free Courses : </p>
                                            <input type="checkbox" name="show_in_free_courses" value="1" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p style="margin-top:-2px;">Show on Home Page : </p>
                                            <input type="checkbox" name="show_on_home_page" value="1" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat" style="display:flex;">
                                            <p>Show in FAQ Topics : </p>
                                            <div style="font-family:Tahoma, arial;font-size:12px;">
                                                <?php
                                                $i = 0;
                                                $topic_result = $db->query("SELECT id, `name` FROM tbl_faq_topics ORDER BY `name` ASC");
                                                while ($topic_row = $topic_result->fetch_array()) {
                                                    $i++;
                                                ?>
                                                <input type="checkbox" name="show_in_faq_topics[]" value="<?php echo $topic_row['id']; ?>" <?php echo (in_array($topic_row['id'], $_POST['show_in_faq_topics'])) ? 'checked' : ''; ?> /> <?php echo $topic_row['name']; ?>
                                                <?php
                                                    if ($i < 4) {
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
                                        <div class="fieldrepeat" style="display:flex;">
                                            <p>Show on Main Courses Page : </p>
                                            <div style="font-family:Tahoma, arial;font-size:12px;">
                                                <input type="checkbox" name="show_in_classifications" value="1" <?php echo ($_POST['show_in_classifications']) ? 'checked' : ''; ?> />
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Order Number : </p>
                                            <input name="ordernumber" type="number" value="1" />
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
    <?php include 'includes/related-diploma-script.php'; ?>
</body>
</html>