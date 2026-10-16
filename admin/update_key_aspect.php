<?php
session_start();
ob_start();
include "classes/connection.php";
if ($_SESSION['adminid'] == "") {
    header("location:index.php");
    exit();
}
$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
$key_aspect_row = $db->query("SELECT * FROM key_aspects WHERE id = $id ")->fetch_array();
if (!$key_aspect_row) {
    header("location:key_aspects.php");
    exit();
}
$diploma_row = $db->query("SELECT diplomaid, name FROM tbl_diploma WHERE diplomaid = " . $key_aspect_row['diplomaid'])->fetch_array();
$diplomaid = $diploma_row['diplomaid'];
$course_name = $diploma_row['name'];
$title = $key_aspect_row['title'];
$status = $key_aspect_row['status'];
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Update Key Aspect</title>
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
// Begin course list creation
$courses_list = '';
$comma = '';
$temp_result = $db->query("SELECT diplomaid, name FROM tbl_diploma ORDER BY name ASC");
while ($temp_row = $temp_result->fetch_array()) {
    $courses_list .= $comma . '"' . $temp_row['name'] . '"';
    $comma = ', ';
}
// End course list creation
// Begin form handling
if (isset($_POST['visible_in'])) {
    $error = '';
    $diplomaid = 0;
    $visible_in = mysqli_real_escape_string($db, $_POST['visible_in']);
    $course_name = mysqli_real_escape_string($db, $_POST['course_name']);
    $title = mysqli_real_escape_string($db, $_POST['title']);
    $status = mysqli_real_escape_string($db, $_POST['status']);
    if ($visible_in == 3) {
        $diploma_row = $db->query("SELECT diplomaid FROM tbl_diploma WHERE name = '$course_name' ")->fetch_array();
        if ($diploma_row) {
            $diplomaid = $diploma_row['diplomaid'];
        }
    }
    list($width, $height) = (!empty($_FILES['icon']['tmp_name'])) ? getimagesize($_FILES['icon']['tmp_name']) : '';
    if ($visible_in == '3' && $diplomaid == 0) {
        $error = 'You need to enter a valid course name.';
    } else if (empty($title)) {
        $error = 'Key aspect cannot be empty.';
    } else if (!empty($_FILES['icon']['tmp_name']) && $_FILES['icon']['type'] != 'image/webp') {
        $error = 'Icon must be a webp image.';
    } else if (!empty($_FILES['icon']['tmp_name']) && ($width != 20 || $height != 20)) {
        $error = 'Icon must be 20 x 20 in size.';
    }
    if (empty($error)) {
        if (!empty($_FILES['icon']['tmp_name'])) {
            $icon = $_FILES['icon']['name'];
            $icon = str_replace(' ', '_', $icon);
            $icon = time() . '_' . $icon;
            move_uploaded_file($_FILES['icon']['tmp_name'], '../images/key_aspects/' . $icon);
            unlink('../images/key_aspects/' . $_POST['icon']);
        } else {
            $icon = $_POST['icon'];
        }
        $db->query("UPDATE key_aspects SET diplomaid = '$diplomaid', title = '$title', icon = '$icon', status = '$status' WHERE id = $id ");
        header("location:key_aspects.php?msg=Key aspect updated successfully.");
        exit();
    }
}
// End form handling
?>
        <div id="outer_contanier">
            <div id="board">
                <div id="left_corner">
                </div>
                <form action="update_key_aspect.php?id=<?php echo $_GET['id']; ?>" method="post" enctype="multipart/form-data"
                    runat="server">
                    <input type="hidden" name="icon" value="<?php echo $key_aspect_row['icon']; ?>" />
                    <div id="midle_repeat">
                        <div id="tit">
                            <h3>Update Key Aspect</h3>
                        </div>
                        <div id="buttons">
                            <ul style="float:right;">
                                <li><input type="submit" value="Save" class="add_cancel_button" /></li>
                                <li><a href="key_aspects.php">Cancel</a></li>
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
                                    <h4>Update Key Aspect</h4>
                                </div>
                                <div id="uperdetail">
                                    <div id="general">
                                        <div class="fieldrepeat">
                                            <p>Visible In : </p>
                                            <select name="visible_in" id="visible_in" onchange="if (this.value == 2) { document.getElementById('coursecon').style.display = 'none'; } else {  document.getElementById('coursecon').style.display = ''; }">
                                                <option value="3" <?php if (!empty($_POST['visible_in']) && $_POST['visible_in'] == '3') { echo 'selected'; } else if ($diplomaid) { echo 'selected'; } ?>>Specific Course</option>
                                                <option value="2" <?php if (!empty($_POST['visible_in']) && $_POST['visible_in'] == '2') { echo 'selected'; } else if (!$diplomaid) { echo 'selected'; } ?>>All Courses</option>
                                            </select>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat" id="coursecon">
                                            <p>Course : </p>
                                            <input name="course_name" id="course_name" type="text" value="<?php echo $course_name; ?>" style="width:100%;" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Key Aspect : </p>
                                            <input name="title" id="title" type="text" value="<?php echo $title; ?>" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Icon :<br />(20 x 20) </p>
                                            <div style="width:80%; float:left">
                                                <input type="file" name="icon" id="icon" />
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Status : </p>
                                            <select name="status" id="status" class="productfieldlarge"
                                                style="width:140px;">
                                                <option value="1" <?php echo (!empty($status) && $status == '1') ? 'selected' : ''; ?>>Enable</option>
                                                <option value="0" <?php echo ($status == '0') ? 'selected' : ''; ?>>Disable</option>
                                            </select>
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
    <script type="text/javascript" src="<?php echo $base_url; ?>/lms/awesomplete-gh-pages/awesomplete.js"></script>
    <script>
    var input = document.getElementById("course_name");
    new Awesomplete(input, {
        list: [<?php echo $courses_list; ?>]
    });
    // Begin adjusting the display of courses section
    <?php if ((!$diplomaid) || (!empty($_POST['visible_in']) && $_POST['visible_in'] == '2')) { ?>
        document.getElementById('coursecon').style.display = 'none';
    <?php } ?>
    // End adjusting the display of courses section
</script>
</body>
</html>