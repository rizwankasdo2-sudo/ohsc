<?php
session_start();
ob_start();
if($_SESSION['adminid']== "")
{
    header("location:index.php");
    exit();
}
if(isset($_GET['cid']))
{
    $cid = $_GET['cid'];
}
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Add Diploma</title>
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
include("classes/connection.php");
include("includes/admin-header.php");
include("includes/admin-menu.php");
?>
        <div id="outer_contanier">
            <div id="board">
                <div id="left_corner">
                </div>
                <form action="insertdiploma.php" method="post" enctype="multipart/form-data" name="addproduct"
                    runat="server">
                    <div id="midle_repeat">
                        <div id="tit">
                            <h3>Add Diploma</h3>
                        </div>
                        <input type="hidden" name="cid" value="<?php echo $cid; ?>" />
                        <div id="buttons">
                            <ul style="float:right;">
                                <li><input type="submit" value="Save" class="add_cancel_button" /></li>
                                <li><a href="diploma.php?cid=<?php echo $cid; ?>">Cancel</a></li>
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
                                            <p>Name : </p>
                                            <input type="text" id="coursename" name="coursename"
                                                class="productfieldlarge" style="width:300px;" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Slug : </p>
                                            <input type="text" id="slug" name="slug" class="productfieldlarge"
                                                style="width:300px;" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Order Number : </p>
                                            <input type="number" id="order" name="order" class="productfieldlarge"
                                                style="width:300px;" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Portal Course ID : </p>
                                            <input type="text" id="portal_course_id" name="portal_course_id" class="productfieldlarge"
                                                style="width:300px;" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Thumbnail: </p>
                                            <input type="file" id="slug" name="thumbnail" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Alternative Text : </p>
                                            <div style="float:left">
                                                <input type="text" name="alternate"
                                                    value="<?php if(!empty($newsreslt['alternate'])){ echo $newsreslt['alternate']; }?>" />
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Course Thumbnail :<br />(70 x 70) </p>
                                            <input type="file" id="square_icon" name="square_icon" />
                                        </div>
                                        <div class="clear"> &nbsp;</div>
                                        <?php if ($_SESSION['adminType'] == 2) { ?>
                                        <div class="fieldrepeat">
                                            <p>Status : </p>
                                            <select name="status" id="status" class="productfieldlarge"
                                                style="width:140px;">
                                                <option value="1">Enable</option>
                                                <option value="0">Disable</option>
                                            </select>
                                        </div>
                                        <?php } ?>
                                        <div class="fieldrepeat">
                                            <p>Popular Course : </p>
                                            <select name="famous_course" id="famous_course" class="productfieldlarge"
                                                style="width:140px;">
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Type : </p>
                                            <select name="type" id="type" class="productfieldlarge"
                                                style="width:140px;">
                                                <option value="1">Paid Course</option>
                                                <option value="2">Free Course</option>
                                                <option value="3">Free Course Category</option>
                                            </select>
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Total Students : </p>
                                            <input type="number" name="admin_total_enrolled" autocomplete="off" />
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Overall Rating : </p>
                                            <select name="admin_rating" id="admin_rating" class="productfieldlarge"
                                                style="width:140px;">
                                                <?php for ($i = 5; $i > 4; $i -= 0.10) { ?>
                                                <option value="<?php echo number_format($i, 1, '.', ''); ?>">
                                                    <?php echo number_format($i, 1, '.', ''); ?></option>
                                                <?php } ?>
                                                <?php for ($i = 4; $i >= 0; $i--) { ?>
                                                <option value="<?php echo number_format($i, 1, '.', ''); ?>">
                                                    <?php echo number_format($i, 1, '.', ''); ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Total Ratings : </p>
                                            <input type="number" name="total_ratings" autocomplete="off" />
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Total 5 Star Ratings : </p>
                                            <input type="number" name="total_ratings_five" autocomplete="off" />
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Total 4 Star Ratings : </p>
                                            <input type="number" name="total_ratings_four" autocomplete="off" />
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Total 3 Star Ratings : </p>
                                            <input type="number" name="total_ratings_three" autocomplete="off" />
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Total 2 Star Ratings : </p>
                                            <input type="number" name="total_ratings_two" autocomplete="off" />
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Total 1 Star Ratings : </p>
                                            <input type="number" name="total_ratings_one" autocomplete="off" />
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Available on Portal : </p>
                                            <select name="added_on_portal" id="added_on_portal" style="width:140px;">
                                                <option value="0">--Select Portal --</option>
                                                <option value="1">E-Learning College</option>
                                                <option value="2">OHSC</option>
                                            </select>
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Classification : </p>
                                            <select name="classification" id="classification" style="width:140px;"
                                                required>
                                                <option value="0">--Select Classification --</option>
                                                <?php
                            $classification_result = $db->query("SELECT * FROM classification ORDER BY name ASC");
                            while ($classification_row = $classification_result->fetch_array()) {
                            ?>
                                                <option value="<?php echo $classification_row['id']; ?>">
                                                    <?php echo $classification_row['name']; ?> Course</option>
                                                <?php } ?>
                                            </select>
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
                                                <input type="checkbox" name="certificate_ids[]" value="<?php echo $certificate_row['id']; ?>" /> <?php echo $certificate_row['title']; ?>
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
            <?php include("includes/admin-footer.php"); ?>
        </div>
    </div>
</body>
</html>