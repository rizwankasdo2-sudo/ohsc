<?php
session_start();
ob_start();
include "classes/connection.php";
if ($_SESSION['adminid'] == "") {
    header("location:index.php");
    exit();
}
$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
$career_row = $db->query("SELECT * FROM tbl_careers WHERE id = $id ")->fetch_array();
if (!$career_row) {
    header("location:careers.php");
    exit();
}
$title = $career_row['title'];
$average_salary_from = $career_row['average_salary_from'];
$average_salary_to = $career_row['average_salary_to'];
$hiring_company_logo_1 = $career_row['hiring_company_logo_1'];
$hiring_company_logo_2 = $career_row['hiring_company_logo_2'];
$hiring_company_logo_3 = $career_row['hiring_company_logo_3'];
$related_categories = $career_row['related_categories'];
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Update Career</title>
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
    $average_salary_from = mysqli_real_escape_string($db, filter_var($_POST['average_salary_from'], FILTER_SANITIZE_NUMBER_FLOAT));
    $average_salary_to = mysqli_real_escape_string($db, filter_var($_POST['average_salary_to'], FILTER_SANITIZE_NUMBER_FLOAT));
    list($width_one, $height_one) = (is_uploaded_file($_FILES['hiring_company_logo_1']['tmp_name'])) ? getimagesize($_FILES['hiring_company_logo_1']['tmp_name']) : '';
    list($width_two, $height_two) = (is_uploaded_file($_FILES['hiring_company_logo_2']['tmp_name'])) ? getimagesize($_FILES['hiring_company_logo_2']['tmp_name']) : '';
    list($width_three, $height_three) = (is_uploaded_file($_FILES['hiring_company_logo_3']['tmp_name'])) ? getimagesize($_FILES['hiring_company_logo_3']['tmp_name']) : '';
    $related_categories = ($_POST['cid']) ? implode(', ', $_POST['cid']) : array();
    
    if (!$title) {
        $error = 'Title is must.';
    } else if (!$average_salary_from) {
        $error = 'Enter minimum value for average salary.';
    } else if (!$average_salary_to) {
        $error = 'Enter maximum value for average salary.';
    } else if (is_uploaded_file($_FILES['hiring_company_logo_1']['tmp_name']) && $_FILES['hiring_company_logo_1']['type'] != 'image/webp') {
        $error = 'Company logo one must be a webp image.';
    } else if (is_uploaded_file($_FILES['hiring_company_logo_1']['tmp_name']) && ($width_one != 100 || $height_one != 100)) {
        $error = 'Company logo one must be 100 x 100 in size.';
    } else if (is_uploaded_file($_FILES['hiring_company_logo_2']['tmp_name']) && $_FILES['hiring_company_logo_2']['type'] != 'image/webp') {
        $error = 'Company logo two must be a webp image.';
    } else if (is_uploaded_file($_FILES['hiring_company_logo_2']['tmp_name']) && ($width_two != 100 || $height_two != 100)) {
        $error = 'Company logo two must be 100 x 100 in size.';
    } else if (is_uploaded_file($_FILES['hiring_company_logo_3']['tmp_name']) && $_FILES['hiring_company_logo_3']['type'] != 'image/webp') {
        $error = 'Company logo three must be a webp image.';
    } else if (is_uploaded_file($_FILES['hiring_company_logo_3']['tmp_name']) && ($width_three != 100 || $height_three != 100)) {
        $error = 'Company logo three must be 100 x 100 in size.';
    }
    if (empty($error)) {
        // Begin uploading company logo one
        if (is_uploaded_file($_FILES['hiring_company_logo_1']['tmp_name'])) {
            unlink('../images/hiring_company_logos/' . $hiring_company_logo_1);
            $hiring_company_logo_1 = $_FILES['hiring_company_logo_1']['name'];
            $hiring_company_logo_1 = str_replace(' ', '_', $hiring_company_logo_1);
            $hiring_company_logo_1 = rand(0, 9) . rand(0, 9) . rand(0, 99) . time() . '_' . $hiring_company_logo_1;
            move_uploaded_file($_FILES['hiring_company_logo_1']['tmp_name'], '../images/hiring_company_logos/' . $hiring_company_logo_1);
        }
        // End uploading company logo one
        // Begin uploading company logo two
        if (is_uploaded_file($_FILES['hiring_company_logo_2']['tmp_name'])) {
            unlink('../images/hiring_company_logos/' . $hiring_company_logo_2);
            $hiring_company_logo_2 = $_FILES['hiring_company_logo_2']['name'];
            $hiring_company_logo_2 = str_replace(' ', '_', $hiring_company_logo_2);
            $hiring_company_logo_2 = rand(0, 9) . rand(0, 99) . rand(0, 9) . time() . '_' . $hiring_company_logo_2;
            move_uploaded_file($_FILES['hiring_company_logo_2']['tmp_name'], '../images/hiring_company_logos/' . $hiring_company_logo_2);
        }
        // End uploading company logo two
        // Begin uploading company logo three
        if (is_uploaded_file($_FILES['hiring_company_logo_3']['tmp_name'])) {
            unlink('../images/hiring_company_logos/' . $hiring_company_logo_3);
            $hiring_company_logo_3 = $_FILES['hiring_company_logo_3']['name'];
            $hiring_company_logo_3 = str_replace(' ', '_', $hiring_company_logo_3);
            $hiring_company_logo_3 = rand(0, 99) . rand(0, 9) . rand(0, 9) . time() . '_' . $hiring_company_logo_3;
            move_uploaded_file($_FILES['hiring_company_logo_3']['tmp_name'], '../images/hiring_company_logos/' . $hiring_company_logo_3);
        }
        // End uploading company logo three
        $db->query("UPDATE tbl_careers SET title = '$title', average_salary_from = $average_salary_from, average_salary_to = $average_salary_to, hiring_company_logo_1 = '$hiring_company_logo_1', hiring_company_logo_2 = '$hiring_company_logo_2', hiring_company_logo_3 = '$hiring_company_logo_3', related_categories = '$related_categories' WHERE id = $id");
        header("location:careers.php?msg=Career updated successfully.");
        exit();
    }
}
// End form handling
$related_categories = explode(',', $related_categories);
?>
        <div id="outer_contanier">
            <div id="board">
                <div id="left_corner">
                </div>
                <form action="edit_career.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data"
                    runat="server">
                    <div id="midle_repeat">
                        <div id="tit">
                            <h3>Update Career</h3>
                        </div>
                        <div id="buttons">
                            <ul style="float:right;">
                                <li><input type="submit" value="Save" class="add_cancel_button" /></li>
                                <li><a href="careers.php">Cancel</a></li>
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
                                    <h4>Update Career</h4>
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
                                            <p>Average Salary From :<br />(GBP per Anum) </p>
                                            <input name="average_salary_from" id="average_salary_from" type="number" value="<?php echo $average_salary_from; ?>" /> Thousand
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Average Salary To :<br />(GBP per Anum) </p>
                                            <input name="average_salary_to" id="average_salary_to" type="number" value="<?php echo $average_salary_to; ?>" /> Thousand
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Hiring Company Logo One :<br />100 x 100 Format:webp </p>
                                            <input name="hiring_company_logo_1" id="hiring_company_logo_1" type="file" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Hiring Company Logo Two :<br />100 x 100 Format:webp </p>
                                            <input name="hiring_company_logo_2" id="hiring_company_logo_2" type="file" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Hiring Company Logo Three :<br />100 x 100 Format:webp </p>
                                            <input name="hiring_company_logo_3" id="hiring_company_logo_3" type="file" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat" style="display:flex;">
                                            <p>Related Categories : </p>
                                            <div style="font-family:Tahoma, arial;font-size:12px;">
                                                <?php
                                                $i = 0;
                                                $category_result = $db->query("SELECT cid, cname FROM tbl_courses ORDER BY cname ASC");
                                                while ($category_row = $category_result->fetch_array()) {
                                                    $i++;
                                                ?>
                                                <input type="checkbox" name="cid[]" value="<?php echo $category_row['cid']; ?>" <?php echo (in_array($category_row['cid'], $related_categories)) ? 'checked' : ''; ?> /> <?php echo $category_row['cname']; ?>
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