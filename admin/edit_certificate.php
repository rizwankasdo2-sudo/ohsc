<?php
session_start();
ob_start();
include "classes/connection.php";
if ($_SESSION['adminid'] == "") {
    header("location:index.php");
    exit();
}
$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
$certificate_row = $db->query("SELECT * FROM certificates WHERE id = $id ")->fetch_array();
if (!$certificate_row) {
    header("location:certificates.php");
    exit();
}
$title = $certificate_row['title'];
$tag_line = $certificate_row['tag_line'];
$earn_by = $certificate_row['earn_by'];
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Update Certificate</title>
    <link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />
    <link rel="stylesheet" href="../Resources/admin_css/add-product.css" />
    <script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>
    <script type="text/javascript" src="cms/ckeditor/ckeditor.js"></script>
    <script src="cms/ckeditor/sample.js" type="text/javascript"></script>
    <link href="../lms/awesomplete-gh-pages/awesomplete.css?v=3" rel="stylesheet" type="text/css"/>
    <!--[if lte IE 6]>
        <script type="text/javascript" src="../Resources/admin_js/supersleight-min.js"></script>
        <![endif]-->
</head>
<body>
    <div id="wrap_dashboard">
        <?php
include "includes/admin-header.php";
include "includes/admin-menu.php";
// Begin form handling
if (isset($_POST['title'])) {
    $error = '';
    $title = mysqli_real_escape_string($db, filter_var($_POST['title'], FILTER_SANITIZE_STRING));
    $tag_line = mysqli_real_escape_string($db, filter_var($_POST['tag_line'], FILTER_SANITIZE_STRING));
    $earn_by = mysqli_real_escape_string($db, filter_var($_POST['earn_by'], FILTER_SANITIZE_STRING));
    list($width_one, $height_one) = (is_uploaded_file($_FILES['pic']['tmp_name'])) ? getimagesize($_FILES['pic']['tmp_name']) : '';
    
    if (!$title) {
        $error = 'Title is must.';
    } else if (!$tag_line) {
        $error = 'Enter tagline.';
    } else if (!$earn_by) {
        $error = 'Specify how to get this certificate e.g. by completing a course.';
    } else if (is_uploaded_file($_FILES['pic']['tmp_name'])) {
        if ($_FILES['pic']['type'] != 'image/webp') {
            $error = 'Certificate pic must be a webp image.';
        } else if ($width_one != 340 || $height_one != 429) {
            $error = 'Certificate pic must be 340 x 429 in size.';
        }
    }
    if (empty($error)) {
        if (is_uploaded_file($_FILES['pic']['tmp_name'])) {
            // Begin uploading certificate pic
            $pic = $_FILES['pic']['name'];
            $pic = str_replace(' ', '_', $pic);
            $pic = rand(0, 9) . rand(0, 9) . rand(0, 99) . time() . '_' . $pic;
            move_uploaded_file($_FILES['pic']['tmp_name'], '../images/certificates/' . $pic);
            unlink('../images/certificates/' . $certificate_row['pic']);
            // End uploading certificate pic
        } else {
            $pic = $certificate_row['pic'];
        }
        $db->query("UPDATE certificates SET title = '$title', tag_line = '$tag_line', earn_by = '$earn_by', pic = '$pic' WHERE id = $id");
        header("location:certificates.php?msg=Certificate updated successfully.");
        exit();
    }
}
// End form handling
?>
        <div id="outer_contanier">
            <div id="board">
                <div id="left_corner">
                </div>
                <form action="edit_certificate.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data"
                    runat="server">
                    <div id="midle_repeat">
                        <div id="tit">
                            <h3>Update Certificate</h3>
                        </div>
                        <div id="buttons">
                            <ul style="float:right;">
                                <li><input type="submit" value="Save" class="add_cancel_button" /></li>
                                <li><a href="certificates.php">Cancel</a></li>
                            </ul>
                        </div>
                    </div>
                    <div id="right_corner">
                    </div>
                    <?php if (!empty($error)) { ?>
                    <div class="err_msg"><?php echo $error; ?></div>
                    <?php } ?>
                    <div id="main_content">
                        <div id="addproducts">
                            <div id="uper">
                                <div id="upertop">
                                    <h4>Update Certificates</h4>
                                </div>
                                <div id="uperdetail">
                                    <div id="general">
                                        <div class="fieldrepeat">
                                            <p>Title : </p>
                                            <input name="title" id="title" type="text" value="<?php echo $title; ?>" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Tagline : </p>
                                            <textarea name="tag_line" id="tag_line" cols="60" rows="5" maxlength="500"><?php echo $tag_line; ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Earn By : </p>
                                            <textarea name="earn_by" id="earn_by" cols="60" rows="5" maxlength="500"><?php echo $earn_by; ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Image :<br />340 x 429 Format:webp </p>
                                            <input name="pic" id="pic" type="file" />
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
</body>
</html>
