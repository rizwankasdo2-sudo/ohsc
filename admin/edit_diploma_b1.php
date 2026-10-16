<?php
session_start();
ob_start();
if($_SESSION['adminid']== "")
{
    header("location:index.php");
    exit();
}
$newsid=$_GET['id'];
if(isset($_GET['cid']))
{
    $cid = $_GET['cid'];
}
include("classes/connection.php");
$qry=$db->query("select * from  tbl_diploma where diplomaid=$newsid");
$newsreslt = $qry->fetch_array();
$certificate_ids = $newsreslt['certificate_ids'];
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
if(isset($_POST['update'])){
    $catname=$_POST['catname'];
    if (!isset ($_POST['slug']) || $_POST['slug'] == '')
    {
        $slug = str_replace(' ', '-', strtolower($catname));
    }
    else
    {
        $slug = str_replace(' ', '-', strtolower($_POST['slug']));
    }
    $order = $_POST['order'];
    $portal_course_id = empty($_POST['portal_course_id']) ? 0 : $_POST['portal_course_id'];
    $alternate = $_POST['alternate'];
    $color = $_POST['color'];
    $status  =  ($_SESSION['adminType']== 2) ? $_POST['status'] : $newsreslt['IStatus'];
    $famous_course  =  $_POST['famous_course'];
    $cid = $_POST['cid'];
    $course_state = (int)$_POST['course_state'];
    $show_in_category = (int)$_POST['show_in_category'];
    $thumbnail = $_FILES['thumbnail']['name'];
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
    $classification = $_POST['classification'];
    $certificate_ids = ($_POST['certificate_ids']) ? implode(', ', $_POST['certificate_ids']) : array();
    $query_extend = '';
    // Begin square thumbnail handling
    if (isset($_FILES['square_icon']['name']) && !empty($_FILES['square_icon']['name'])) {
        $square_icon = time() . rand(999, 99999) . rand(999, 99999) . $_FILES['square_icon']['name'];
        move_uploaded_file($_FILES['square_icon']['tmp_name'], '../images/subject/' . $square_icon);
        $query_extend = ", square_icon = '$square_icon' ";
    }
    // End square thumbnail handling
    
    $msg="Slug Already Exists";
    if(isset($_FILES['thumbnail']['name']) && !empty($_FILES['thumbnail']['name'])){
        $thumbnail = time().$_FILES['thumbnail']['name'];
        $tmp_name = $_FILES['thumbnail']['tmp_name'];
        $dir_name = '../images/subject';
        $destination = $dir_name.'/'.$thumbnail;
        move_uploaded_file($tmp_name, $destination);
        $qry = $db->query("UPDATE tbl_diploma SET name='$catname', cid='$category_edit', slug='$slug',ordernumber=$order,IStatus=$status,famous_course = '$famous_course', thumbnail='$thumbnail', alternate='$alternate', course_state='$course_state', show_in_category = $show_in_category, color = '$color', type='$type', admin_total_enrolled = '$admin_total_enrolled', admin_rating = '$admin_rating', total_ratings = '$total_ratings', total_ratings_five = '$total_ratings_five', total_ratings_four = '$total_ratings_four', total_ratings_three = '$total_ratings_three', total_ratings_two = '$total_ratings_two', total_ratings_one = '$total_ratings_one', added_on_portal = '$added_on_portal', classification = '$classification', portal_course_id = $portal_course_id, certificate_ids = '$certificate_ids' $query_extend WHERE diplomaid=$newsid") or die("<script language='javascript'>window.location='diploma.php?cid=$cid&msg=$msg'</script>").mysqli_error($db);
    } else {
        $qry = $db->query("UPDATE tbl_diploma SET name='$catname', cid='$category_edit', slug='$slug',ordernumber=$order,IStatus=$status, famous_course = '$famous_course', alternate='$alternate', course_state='$course_state', show_in_category = $show_in_category, color = '$color', type='$type', admin_total_enrolled = '$admin_total_enrolled', admin_rating = '$admin_rating', total_ratings = '$total_ratings', total_ratings_five = '$total_ratings_five', total_ratings_four = '$total_ratings_four', total_ratings_three = '$total_ratings_three', total_ratings_two = '$total_ratings_two', total_ratings_one = '$total_ratings_one', added_on_portal = '$added_on_portal', classification = '$classification', portal_course_id = $portal_course_id, certificate_ids = '$certificate_ids' $query_extend  WHERE diplomaid=$newsid");
        if(!$qry){
            die(mysqli_error($db));
        }
    }
    $msg="Updated Sucssefully";
    echo "<script language='javascript'>window.location='diploma.php?cid=$cid&msg=$msg'</script>";
}
$certificate_ids = explode(',', $certificate_ids);
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
                                            <input type="text" id="slug" name="slug"
                                                value="<?php echo $newsreslt['slug'];?>" class="productfieldlarge"
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
                                            <p>Course Thumbnail :<br />(70 x 70) </p>
                                            <input type="file" id="square_icon" name="square_icon" />
                                        </div>
                                        <div class="clear"> &nbsp;</div>
                                        <div class="fieldrepeat">
                                            <p>Alternative Text : </p>
                                            <div style="float:left">
                                                <input type="text" name="alternate"
                                                    value="<?php if(!empty($newsreslt['alternate'])){ echo $newsreslt['alternate']; }?>" />
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <?php if ($_SESSION['adminType'] == 2) { ?>
                                        <div class="fieldrepeat">
                                            <p>Status : </p>
                                            <select name="status" id="status" class="status" style="width:150px;">
                                                <?php
                            if ($newsreslt['IStatus'] == 1)
                            {
                                echo "<option value=".$newsreslt['IStatus']." 'selected'>Enable</option>";
                                echo "<option value='0'>Disable</option>";
                            }
                            else
                            {
                                echo "<option value=".$newsreslt['IStatus']." 'selected'>Disable</option>";
                                echo "<option value='1'>Enable</option>";
                            }
                            ?>
                                            </select>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <?php } ?>
                                        <div class="fieldrepeat">
                                            <p>Category : </p>
                                            <select name="category" id="category" class="status" style="width:150px;">
                                                <?php
                            $cours_cat_sql = "SELECT cid, cname FROM tbl_courses";
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
                                        <div class="fieldrepeat">
                                            <p>Classification : </p>
                                            <select name="classification" id="classification" style="width:140px;">
                                                <option value="0">--Select Classification --</option>
                                                <?php
                            $classification_result = $db->query("SELECT * FROM classification ORDER BY name ASC");
                            while ($classification_row = $classification_result->fetch_array()) {
                            ?>
                                                <option value="<?php echo $classification_row['id']; ?>"
                                                    <?php echo ($newsreslt['classification'] == $classification_row['id']) ? 'selected' : ''; ?>>
                                                    <?php echo $classification_row['name']; ?></option>
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
</body>
</html>