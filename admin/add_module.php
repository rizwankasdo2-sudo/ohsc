<?php
session_start();
ob_start();
include "classes/connection.php";
if ($_SESSION['adminid'] == "") {
    header("location:index.php");
    exit();
}
$diplomaid = $_GET['did'];
$diploma_row = $db->query("SELECT name FROM tbl_diploma WHERE diplomaid = '$diplomaid'")->fetch_array();
if (!$diploma_row) {
    header("location:cours.php");
    exit();
}
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Add Module</title>
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
    $excerpt = mysqli_real_escape_string($db, filter_var($_POST['excerpt'], FILTER_SANITIZE_STRING));
    $description = mysqli_real_escape_string($db, filter_var($_POST['description'], FILTER_SANITIZE_STRING));
    $required = mysqli_real_escape_string($db, filter_var($_POST['required'], FILTER_SANITIZE_NUMBER_INT));
    $hours = mysqli_real_escape_string($db, filter_var($_POST['hours'], FILTER_SANITIZE_NUMBER_FLOAT));
    
    if (!$title) {
        $error = 'Title is must.';
    } else if (!$excerpt) {
        $error = 'Enter excerpt.';
    } else if (!$description) {
        $error = 'Enter module detail.';
    } else if (!$hours) {
        $error = 'You must enter number of hours for this module.';
    }
    if (empty($error)) {
        $db->query("INSERT INTO tbl_modules SET diplomaid = '$diplomaid', title = '$title', excerpt = '$excerpt', description = '$description', required = '$required', hours = '$hours' ");
        header("location:modules.php?did=" . $diplomaid . "&msg=Module added successfully.");
        exit();
    }
}
// End form handling
?>
        <div id="outer_contanier">
            <div id="board">
                <div id="left_corner">
                </div>
                <form action="add_module.php?did=<?php echo $diplomaid; ?>" method="post" enctype="multipart/form-data"
                    runat="server">
                    <div id="midle_repeat">
                        <div id="tit">
                            <h3>Add Module</h3>
                        </div>
                        <div id="buttons">
                            <ul style="float:right;">
                                <li><input type="submit" value="Save" class="add_cancel_button" /></li>
                                <li><a href="modules.php?did=<?php echo $diplomaid; ?>">Cancel</a></li>
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
                                    <h4>Add Module</h4>
                                </div>
                                <div id="uperdetail">
                                    <div id="general">
                                        <div class="fieldrepeat">
                                            <p>Title : </p>
                                            <input name="title" id="title" type="text" value="<?php echo $_POST['title']; ?>" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Excerpt : </p>
                                            <textarea id="excerpt" name="excerpt" cols="60" rows="10"><?php echo $_POST['excerpt']; ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Detail : </p>
                                            <textarea id="description" name="description" cols="60" rows="10"><?php echo $_POST['description']; ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Required </p>
                                            <div style="width:80%; float:left">
                                                <select name="required" id="required">
                                                    <option value="0" <?php echo (isset($_POST['required']) && $_POST['required'] == '0') ? 'selected' : ''; ?>>Optional</option>
                                                    <option value="1" <?php echo (!empty($_POST['required']) && $_POST['required'] == '1') ? 'selected' : ''; ?>>Required</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Hours : </p>
                                            <input name="hours" id="hours" type="number" value="<?php echo $_POST['hours']; ?>" />
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