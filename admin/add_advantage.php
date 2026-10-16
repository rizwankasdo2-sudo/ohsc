<?php
session_start();
ob_start();
include("classes/connection.php");

if($_SESSION['adminid']== "")

{
	header("location:index.php");
	exit();
}

if (isset($_POST['advantage']) && isset($_POST['sort_order'])) {
    $advantage =   $_POST['advantage'];
    $sort_order =   $_POST['sort_order'];
    $db->query("INSERT INTO advantages SET advantage = '$advantage', sort_order = '$sort_order' ");
    $msg = "Inserted Successfully";
    header("location: course_advantages.php?msg=$msg");
    echo "<script language='javascript'>window.location='course_advantages.php?msg=$msg'</script>";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Add Advantage</title>
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

       

       <form method="post" enctype="multipart/form-data" name="addproduct" runat="server">

       

       <div id="midle_repeat">

         

         <div id="tit">

         

           <h3>Add Advantage</h3>

         

         </div>

         

         <div id="buttons">

         

           <ul style="float:right;">

           

             <li><input type="submit" value="Save" class="add_cancel_button" /></li>

             <li><a href="course_advantages.php">Cancel</a></li>

           

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
                           <p>Advantage : </p>
                           <input type="text" id="advantage" name="advantage" class="productfieldlarge" style="width:300px;" />
                       </div>
                       <div class="clear"> &nbsp;</div>
                       <div class="fieldrepeat">
                           <p>Order : </p>
                           <input type="number" id="sort_order" name="sort_order" class="productfieldlarge" style="width:300px;" />
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