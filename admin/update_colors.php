<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

} 

 $newsid=$_GET['id'];

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">



<head>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<title>Update colors</title>



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

       <?php

if(isset($_POST['update'])){

	 $cid = $_POST['cid'];
	   $id = $_POST['id'];

	$name=$_POST['catname'];

	$detail = $_POST['order'];

 $img = $_FILES['image']['name'];

	$status  =  $_POST['status'];
	echo $size = $_POST['size'];

	$selct = $db->query("select * from tbl_color WHERE colorid=$newsid");
			   $result = $selct->fetch_array();
			    $image = $result['Image'];

if($img==""){

	$db->query ("update tbl_color set itemID=$id, colorname='$name',colorprice=$detail,Image='$image',IStatus=$status,sizeid='$size' where colorid=$newsid");

	$msg="Updated Sucssefully";

	echo "<script language='javascript'>window.location='colors.php?id=$id&msg=$msg'</script>";

}

else{

	if (($_FILES["image"]["type"] == "image/jpeg") || ($_FILES["image"]["type"] == "image/pjpeg") || ($_FILES["image"]["type"] == "image/png"))

			   {

				   if ($_FILES["image"]["error"] > 0)

				   {

					   $no = "Error: ".$_FILES["image"]["error"] . "<br />";

					   echo "<script language='javascript'>window.location='colors.php?id=$id&no=$no'</script>";

				   }

				   else

				   {

					  

					   move_uploaded_file($_FILES["image"]["tmp_name"], "../Resources/color/" . $img);

					   $updatebanners = $db->query("update tbl_color set itemID=$id, colorname='$name',colorprice=$detail,Image='$img',IStatus=$status,sizeid='$size' where colorid=$newsid");

					   

					   $updatebannerTOtable = $db->query($updatebanners);

					   $msg="Updated sucessfully";

					 echo "<script language='javascript'>window.location='colors.php?id=$id&msg=$msg'</script>";

				   }

			   }

			   

			   else

			   {

				   $invalid = "Invalid file";

				   echo "<script language='javascript'>window.location='colors.php?id=$id&invalid=$invalid'</script>";

			   }

		   

	

	

	}

	}

?>



       

       <form action="" method="post" enctype="multipart/form-data" name="addproduct" runat="server">

       

       <div id="midle_repeat">

         

         <div id="tit">

         

           <h3>Update colors</h3>

         

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

                   

               <?php 
			
			$qry=$db->query("select * from  tbl_color where colorid=$newsid");
			
			$newsreslt= $qry->fetch_array();
			 $itemID = $newsreslt['ItemID'];
			 $sizeids = $newsreslt['sizeid'];
			?>

                     

                       <div class="fieldrepeat">

                       

                         <p>Name : </p>

                         <input type="text" id="catname" name="catname" class="productfieldlarge" style="width:300px;" value="<?php echo $newsreslt['colorname'];?>" />
                         <input type="hidden" value="<?php echo $itemID; ?>" name="id" />
                       <input type="hidden" value="<?php echo $id; ?>" name="cid" />

                       

                       </div>

                       

                                 <div class="clear">

                                 </div>

                         <div class="fieldrepeat">

                       

                         <p>Color Price : </p>

                         <input type="text" id="order" name="order" class="productfieldlarge" style="width:300px;" value="<?php echo $newsreslt['colorprice'];?>" />

                       

                       </div>

                       

                                 <div class="clear">

                                 </div>


                       

                     <div class="fieldrepeat">

                       

                       <p>Upload Image</p>

                       <label for="fileField"></label>

                         <input type="file" name="image" id="image" size="35" />

                       </div>

                       <div class="fieldrepeat">

                       

                         <p>Size Name : </p>

                         

                         <select name="size" id="size" class="productfieldlarge" style="width:140px;">
 			<?php 
					$qry = $db->query("select * from tbl_size where ItemID=$itemID");
					while($result = $qry->fetch_array())
					{
						$itemsid = $result['itemID'];
						$sizeid = $result['sizeid'];
					
					
					?>  
                           <option value="<?php echo $result['sizeid']; ?>" <?php if($sizeid==$sizeids){ echo 'selected';}?>><?php echo $result['sizename']; ?></option>
		<?php } ?>

                         </select>

                       

                       </div>

                       

                                 <div class="clear">

                                 </div>
                    
                       

                       <div class="fieldrepeat">

                       

                         <p>Status : </p>

                         

                         <select name="status" id="status" class="status" style="width:150px;">

                   

					   <?php

					   

					           if ($newsreslt['IStatus'] == 1)

							   {
								 echo "<option value=".$newsreslt['IStatus']." 'selected'>Enable</option>";
								   echo "<option value='0'>Disable</option>";

								  

							   }

							   else

							   {
								 echo "<option value=".$newsreslt['IStatus']." 'selected'>Disable</option>";
								   echo "<option value='1'>Enable</option>";

								  

							   }

					   

					   ?>

                     

                 </select>

               

                       

                       </div>

                       

                       

                                 <div class="clear">

                                 </div>

                    <div id="buttons" style=" width:713px; margin:0 auto; margin-top:15px;"> 

                    <input type="submit" name="update" id="update" value="update" />

                      <input type="button" value="Cancel" onClick="window.location.href='product.php'" />

                 

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

     

     <?php include("includes/admin-footer.php"); ?>

     

    </div>

     

  </div>  


</body>

</html>









