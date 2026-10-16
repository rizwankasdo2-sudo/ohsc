<?php
session_start();
ob_start();
if ($_SESSION['adminid']== "") {
	header("location:index.php");
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Add Blog Category</title>
<link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />
<link rel="stylesheet" href="../Resources/admin_css/add-product.css" />
<script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>
<script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
<!--[if lte IE 6]>
<script type="text/javascript" src="../Resources/admin_js/supersleight-min.js"></script>
<![endif]-->
</head>
<body>
<div id="wrap_dashboard">
<?php
    include("classes/connection.php");
    include("includes/blog-category-helper.php");
    include("includes/admin-header.php");
    include("includes/admin-menu.php");

    $form_values = [
        'name' => '',
        'slug' => '',
        'order' => '',
        'tag_line' => '',
        'benefit_1' => '',
        'benefit_2' => '',
        'benefit_3' => '',
        'benefit_4' => '',
        'description_excerpt' => '',
        'description_detail' => '',
        'short_intro' => '',
    ];

    $err_msgs = [];

if (isset($_POST['submit'])) {
	$name  = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $slug  = preg_replace('/[^a-z0-9\-]/i', '', $_POST['slug']);
    $order = filter_var($_POST['order'], FILTER_VALIDATE_INT, [
        'options' => ['default' => 0, 'min_range' => 0]
    ]);
    $form_data = collectBlogCategoryFormData($db);
    $form_values = array_merge($form_values, $form_data, [
        'name' => $name,
        'slug' => $slug,
        'order' => $order,
    ]);

    $upload_errors = validateBlogCategoryIconUploads(false);
    if (!empty($upload_errors)) {
        $err_msgs = array_merge($err_msgs, $upload_errors);
    }

    $alt_errors = validateBlogCategoryAltFields();
    if (!empty($alt_errors)) {
        $err_msgs = array_merge($err_msgs, $alt_errors);
    }

    $check_row = $db->query("SELECT id FROM blog_categories WHERE slug = '$slug' ")->fetch_array();
    if ($check_row) {
        $err_msgs[] = 'Slug already assigned to another blog category.';
    }

    if (empty($err_msgs)) {
        $uploadDir = getBlogCategoryUploadDir();
        $icons = saveBlogCategoryIconUploads($uploadDir);

        if (empty($icons['icon'])) {
            $err_msgs[] = 'Unable to upload category icon.';
        } else {
            for ($i = 1; $i <= 4; $i++) {
                if (empty($icons['benefit_icon_' . $i])) {
                    $err_msgs[] = 'Unable to upload benefit icon ' . $i . '.';
                }
            }
        }
    }

    if (empty($err_msgs)) {
        $sql = "INSERT INTO blog_categories SET
            `order` = '$order',
            name = '$name',
            slug = '$slug',
            tag_line = '{$form_data['tag_line']}',
            benefit_1 = '{$form_data['benefit_1']}',
            benefit_2 = '{$form_data['benefit_2']}',
            benefit_3 = '{$form_data['benefit_3']}',
            benefit_4 = '{$form_data['benefit_4']}',
            benefit_icon_1 = '{$icons['benefit_icon_1']}',
            benefit_icon_2 = '{$icons['benefit_icon_2']}',
            benefit_icon_3 = '{$icons['benefit_icon_3']}',
            benefit_icon_4 = '{$icons['benefit_icon_4']}',
            description_excerpt = '{$form_data['description_excerpt']}',
            description_detail = '{$form_data['description_detail']}',
            icon = '{$icons['icon']}',
            icon_alt = '{$form_data['icon_alt']}',
            benefit_icon_1_alt = '{$form_data['benefit_icon_1_alt']}',
            benefit_icon_2_alt = '{$form_data['benefit_icon_2_alt']}',
            benefit_icon_3_alt = '{$form_data['benefit_icon_3_alt']}',
            benefit_icon_4_alt = '{$form_data['benefit_icon_4_alt']}',
            short_intro = '{$form_data['short_intro']}',
            pagetitle = '{$form_data['pagetitle']}',
            metatitle = '{$form_data['metatitle']}',
            metadescription = '{$form_data['metadescription']}',
            robots = '{$form_data['robots']}'";

        if (!$db->query($sql)) {
            die(mysqli_error($db));
        }

        header('location:blog_categories.php?msg=Blog category added successfully.');
        exit;
    }
}
?>
       <div id="outer_contanier">
     <div id="board">
       <div id="left_corner">
       </div>
       <div id="midle_repeat">
         <div id="tit">
           <h3>Add Blog Category</h3>
         </div>
       </div>
       <div id="right_corner">
       </div>
       <div id="main_content">
         <div id="addproducts">
           <div id="uper">
             <div id="uperdetail" style="border: none">
            <?php renderBlogCategoryErrors($err_msgs); ?>
<form action="" method="post" enctype="multipart/form-data" name="addproduct" runat="server">
                   <div id="general">
                       <?php renderBlogCategoryFields($form_values); ?>
                    <div id="buttons" style=" width:713px; margin:0 auto; margin-top:15px;">
                    <input type="submit" name="submit" id="submit" value="Submit" />
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="js/scripts/jquery.min.js"></script>
<?php initBlogCategoryEditors(); ?>
</body>
</html>
