<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

} 
if(isset($_GET['did']) && $_GET['cid'])
{
$did = $_GET['did'];
$cid = $_GET['cid'];	
	
}
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">



<head>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<title>Add SEO</title>



<link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />



<link rel="stylesheet" href="../Resources/admin_css/add-product.css" />





<script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>



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

       

       <form action="insert_courseseo.php" method="post" enctype="multipart/form-data" name="addproduct" runat="server">

       

       <div id="midle_repeat">

         

         <div id="tit">

         

           <h3>Add SEO</h3>

         

         </div>

         

         <div id="buttons">

         

           <ul style="float:right;">

           

             <li><input type="submit" value="Save" class="add_cancel_button" /></li>

             <li><a href="courseseo.php">Cancel</a></li>

           

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

                       

                         <p>Page Title : </p>

                         <input type="text" id="NewsTitle" name="NewsTitle" class="productfieldlarge" style="width:300px;" />

                       

                       </div>

                       <input type="hidden" name="did" value="<?php echo $did?>" />
                       <input type="hidden" name="cid" value="<?php echo $cid; ?>" />

                                 <div class="clear">

                                 </div>

                       

                       <div class="fieldrepeat">

                       

                         <p>Meta Title</p>

                         <input type="text" name="Newsdiscription" id="Newsdiscription"  class="productfield" size="300"/>

                       </div>

                     <div class="fieldrepeat" style="margin-top:10px;">

                       

                       <p>Meta Description</p>

                     
                 <textarea name="NewsDetail" id="NewsDetail" cols="60" rows="10"></textarea>

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