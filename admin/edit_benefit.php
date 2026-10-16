<?php
session_start();
ob_start();
include("classes/connection.php");

if($_SESSION['adminid']== "")

{
	header("location:index.php");
	exit();
}

if (empty($_GET['id'])) {
	header("location:index.php");
	exit();
}

$id = $_GET['id'];
$benefit_result = $db->query("SELECT * FROM benefits WHERE id = '$id' ");
$benefit_row = $benefit_result->fetch_array();
$heading = $benefit_row['heading'];
$description = $benefit_row['description'];
$sort_order = $benefit_row['sort_order'];

$msg_class = 'err_msg';
if (isset($_POST['heading']) && isset($_POST['description'])) {
    $heading = trim($_POST['heading']);
    $description = trim($_POST['description']);
    $sort_order = trim($_POST['sort_order']);
    if ($heading != '' && $description != '' && $sort_order != '') {
        $extend_query = '';
        $ok = 'yes';
        $img = trim($_FILES['img']['name']);
        if ($img) {
            $img =  rand(999, 99999) . time() . $img;
            $ext = strtolower(trim(pathinfo($img, PATHINFO_EXTENSION)));
            if ($ext == 'webp') {
                @unlink('../images/nd/benefits/' . $benefit_row['img']);
                move_uploaded_file($_FILES['img']['tmp_name'], '../images/nd/benefits/' . $img);
                $extend_query = ", img = '$img' ";
            } else {
                $ok = 'no';
                $msg = 'Image must have .webp extension.';
            }
        }
        $db->query("UPDATE benefits SET heading = '$heading', description = '$description', sort_order = '$sort_order' $extend_query WHERE id = '$id' ");
        $msg = "Updated Successfully.";
        header("location:course_benefits.php?msg=$msg");
        echo "<script language='javascript'>window.location='course_benefits.php?msg=$msg'</script>";
    } else {
        $msg = 'No field can be left blank and image must have .webp extension.';
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Advantage</title>
<link rel="stylesheet" href="../Resources/admin_css/admin-style.css?v=1" />
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
       <?php if ($msg) { ?>
       <div class="<?php echo $msg_class; ?>"><?php echo $msg; ?></div>
       <?php } ?>

     

     <div id="board">

     

       <div id="left_corner">

       </div>
       <form method="post" enctype="multipart/form-data">

       

       <div id="midle_repeat">

         

         <div id="tit">

         

           <h3>Edit Benefit</h3>

         

         </div>

         

         <div id="buttons">

         

           <ul style="float:right;">

           

             <li><input type="submit" value="Save" class="add_cancel_button" /></li>

             <li><a href="course_benefits.php">Cancel</a></li>

           

           </ul>

         

         </div>

       

       </div>

       

       <div id="right_corner">

       </div>

       

       <div id="main_content">

       

         <div id="addproducts">

         

           <div id="uper">

           

             <div id="upertop">

             

               <h4>Edit Benefit</h4>

             

             </div>

             

             <div id="uperdetail">

                   

                   <div id="general">
                       <div class="fieldrepeat">
                           <p>Heading : </p>
                           <input type="text" id="heading" name="heading" class="productfieldlarge" style="width:100%;" value="<?php echo $heading; ?>" />
                       </div>
                       <div class="clear"> &nbsp;</div>
                       <div class="fieldrepeat">
                           <p>Short Description : </p>
                           <input type="text" id="description" name="description" class="productfieldlarge" style="width:100%;" value="<?php echo $description; ?>" />
                       </div>
                       <div class="clear"> &nbsp;</div>
                       <div class="fieldrepeat">
                           <p>Icon :<br />(20 x 20) </p>
                           <input type="file" id="img" name="img" class="productfieldlarge" style="width:300px;" />
                       </div>
                       <div class="clear"> &nbsp;</div>
                       <div class="fieldrepeat">
                           <p>Order : </p>
                           <input type="number" id="sort_order" name="sort_order" class="productfieldlarge" style="width:300px;" value="<?php echo $sort_order; ?>" />
                       </div>
                       <div class="clear"> &nbsp;</div>

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