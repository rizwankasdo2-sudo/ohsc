<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

} 

if(isset($_GET['id']))
{
$id = $_GET['id'];	
	
}


?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">



<head>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<title>Add colors</title>




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

       

       <form action="insert_colors.php" method="post" enctype="multipart/form-data" name="addproduct" runat="server">

       

       <div id="midle_repeat">

         

         <div id="tit">

         

           <h3>Add colors</h3>

         

         </div>

         

         <div id="buttons">

         

           <ul style="float:right;">

           

             <li><input type="submit" value="Save" class="add_cancel_button" /></li>

             <li><a href="colors.php?id=<?php echo $id; ?>">Cancel</a></li>

           

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

                       

                         <p>Name : </p>

                         <input type="text" id="catname" name="catname" class="productfieldlarge" style="width:300px;" />

                       <input type="hidden" value="<?php echo $id; ?>" name="id" />

                     </div>

                       

                                 <div class="clear">

                                 </div>

                       

                          <div class="fieldrepeat">

                       

                         <p>Price : </p>

                         <input type="text" id="order" name="order" class="productfieldlarge" style="width:300px;" />

                       

                       </div>

                       

                                 <div class="clear">

                                 </div>


                       <div class="fieldrepeat">

                       

                         <p>Upload Image</p>

                         <label for="fileField"></label>

                         <input type="file" name="image" id="image" size="35" />
                         ( 306 x 157 )
                       </div>
                       
                          <div class="fieldrepeat">

                       

                         <p>Size Name : </p>

                         

                         <select name="size" id="size" class="productfieldlarge" style="width:140px;">
                          <option value="">Please select size</option>
 			<?php 
					$qry = $db->query("select * from tbl_size where ItemID=$id");
					while($result = $qry->fetch_array())
					{
					
					
					?>  
                           <option value="<?php echo $result['sizeid']; ?>"><?php echo $result['sizename']; ?></option>
		<?php } ?>

                         </select>

                       

                       </div>

                       

                                 <div class="clear">

                                 </div>



                       <div class="fieldrepeat">

                       

                         <p>Status : </p>

                         

                         <select name="status" id="status" class="productfieldlarge" style="width:140px;">

                           <option value="1">Enable</option>

                           <option value="0">Disable</option>

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

     

     <?php include("includes/admin-footer.php"); ?>

     

    </div>

     

  </div>

  

</body>

</html>