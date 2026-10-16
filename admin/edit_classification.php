<?php
session_start();
ob_start();
exit;
if ($_SESSION['adminid'] == "") {
    header("location:index.php");
    exit();
}
include "classes/connection.php";
$id = $_GET['id'];
if (isset($_POST['update'])) {
    $tag_line = $_POST['tag_line'];
    $header_excerpt = $_POST['header_excerpt'];
    $header_description = $_POST['header_description'];
    $excerpt = $_POST['excerpt'];
    $description = $_POST['description'];
    $show_at_frontend = $_POST['show_at_frontend'];
    $sort_order = $_POST['sort_order'];
    $page_title = $_POST['page_title'];
    $meta_desc = $_POST['meta_desc'];
    $meta_key = $_POST['meta_key'];
    $robots = $_POST['robots'];
    $db->query("UPDATE classification SET tag_line = '$tag_line', header_excerpt = '$header_excerpt', header_description = '$header_description', excerpt = '$excerpt', description = '$description', show_at_frontend = '$show_at_frontend', sort_order = '$sort_order', page_title = '$page_title', meta_desc = '$meta_desc', meta_key = '$meta_key', robots = '$robots' WHERE id = $id") or die("Update query error.") . mysqli_error($db);
    header('location:classification.php?msg=1');
    exit;
}
$classification_row = $db->query("SELECT * FROM classification WHERE id = $id")->fetch_array();
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Edit Classification</title>
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
include "includes/admin-header.php";
include "includes/admin-menu.php";
?>
        <div id="outer_contanier">
            <div id="board">
                <div id="left_corner">
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div id="midle_repeat">
                        <div id="tit">
                            <h3>Edit Classification</h3>
                        </div>
                    </div>
                    <div id="right_corner">
                    </div>
                    <div id="main_content">
                        <div id="addproducts">
                            <div id="uper">
                                <div id="upertop">
                                    <h4>Edit Classification</h4>
                                </div>
                                <div id="uperdetail">
                                    <div id="general">
                                        <div class="fieldrepeat">
                                            <p>Name : </p>
                                            <?php echo $classification_row['name']; ?>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Tagline : </p>
                                            <textarea name="tag_line" id="tag_line" cols="60" rows="10"
                                                maxlength="250"><?php echo $classification_row['tag_line']; ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <div><strong>Header Content Excerpt :</strong> </div>
                                            <div><textarea id="header_excerpt" name="header_excerpt" class="ckeditor" cols="30"
                                                    rows="15"><?php echo $classification_row['header_excerpt']; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <div><strong>Header Content Detail :</strong> </div>
                                            <div><textarea id="header_description" name="header_description" class="ckeditor"
                                                    cols="30"
                                                    rows="15"><?php echo $classification_row['header_description']; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <div><strong>Central Content Excerpt :</strong> </div>
                                            <div><textarea id="excerpt" name="excerpt" class="ckeditor" cols="30"
                                                    rows="15"><?php echo $classification_row['excerpt']; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <div><strong>Central Content Detail :</strong> </div>
                                            <div><textarea id="description" name="description" class="ckeditor"
                                                    cols="30"
                                                    rows="15"><?php echo $classification_row['description']; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Show at Front-end : </p>
                                            <select name="show_at_frontend" id="show_at_frontend">
                                                <option value="1"
                                                    <?php echo ($classification_row['show_at_frontend'] == 1) ? 'selected' : ''; ?>>
                                                    Yes</option>
                                                <option value="0"
                                                    <?php echo ($classification_row['show_at_frontend'] != 1) ? 'selected' : ''; ?>>
                                                    No</option>
                                            </select>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Sord Order : </p>
                                            <select name="sort_order" id="sort_order">
                                                <?php for ($i = 1; $i <= 100; $i++) { ?>
                                                <option value="<?php echo $i; ?>"
                                                    <?php echo ($classification_row['sort_order'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Page Title : </p>
                                            <textarea class="seo_text" name="page_title" id="page_title" rows="3"
                                                style="width: 645px; padding:10px;"
                                                required><?php echo $classification_row['page_title']; ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Meta Keywords : </p>
                                            <textarea class="seo_text" name="meta_key" id="meta_key" rows="3"
                                                style="width: 645px; padding:10px;"
                                                required><?php echo $classification_row['meta_key']; ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Meta Description : </p>
                                            <textarea class="seo_text" name="meta_desc" id="meta_desc" rows="3"
                                                style="width: 645px; padding:10px;"
                                                required><?php echo $classification_row['meta_desc']; ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Robots : </p>
                                            <label><input type="radio" name="robots"
                                                    value="Index,Nofollow" <?php echo ($classification_row['robots'] == 'Index,Nofollow') ? 'checked' : ''; ?> />Index</label>
                                            <label><input type="radio" name="robots"
                                                    value="Noindex,Follow" <?php echo ($classification_row['robots'] == 'Noindex,Follow') ? 'checked' : ''; ?> />Follow</label>
                                            <label><input type="radio" name="robots"
                                                    value="Noindex,Nofollow" <?php echo ($classification_row['robots'] == 'Noindex,Nofollow') ? 'checked' : ''; ?> />Disallow</label>
                                            <label><input type="radio" name="robots"
                                                    value="Index,Follow" <?php echo ($classification_row['robots'] == 'Index,Follow') ? 'checked' : ''; ?> />Allow</label>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div id="buttons" style=" width:713px; margin:0 auto; margin-top:10px;">
                                            <input type="submit" name="update" id="update" value="update" />
                                            <input type="button" value="Cancel"
                                                onClick="window.location.href='classification.php';" />
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
</body>

</html>