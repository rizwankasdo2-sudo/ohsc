<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

} 

 $testi=$_GET['id'];

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">



<head>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<title>Edit Product</title>



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

	

		$name = $_POST['pname'];
		
		$category = $_POST['category'];
		
		$price = $_POST['price'];
	$detail = $_POST['detail'];

	$img = $_FILES['image']['name'];

	$status  =  $_POST['status'];
	$bestseller = $_POST['best'];
	
	
	$qry=$db->query("select * from tbl_product where ItemID=$testi");

	$row= $qry->fetch_array();

	$image=$row['Image'];

if($img==""){

$tetimonial=$db->query("UPDATE tbl_product SET IItemName='$name', Image='$image',price=$price,Description='$detail',IStatus=$status,bestseller=$bestseller where ItemID=$testi");

	$msg="Updated Sucssefully";

	echo "<script language='javascript'>window.location='product.php?idees=$category&msg=$msg'</script>";

}

else{

	if (($_FILES["image"]["type"] == "image/jpeg") || ($_FILES["image"]["type"] == "image/pjpeg") || ($_FILES["image"]["type"] == "image/png"))

			   {

				   if ($_FILES["image"]["error"] > 0)

				   {

					   $no = "Error: ".$_FILES["image"]["error"] . "<br />";

					   echo "<script language='javascript'>window.location='product.php?no=$no'</script>";

				   }

				   else

				   {

					 
						unlink("../Resources/product/".$image);
					   move_uploaded_file($_FILES["image"]["tmp_name"], "../Resources/product/" . $img);

					  $tetimonial=$db->query("UPDATE tbl_product SET IItemName='$name', Image='$img',price=$price,Description='$detail',IStatus=$status,bestseller=$bestseller where ItemID=$testi");

	$msg="Updated Sucssefully";

	echo "<script language='javascript'>window.location='product.php?idees=$category&msg=$msg'</script>";

				   }

			   }

			   

			   else

			   {

				   $invalid = "Invalid file";

				   echo "<script language='javascript'>window.location='indexmenu.php?invalid=$invalid'</script>";

			   }

		   

	

	

	}

	}

?>



       

       <form action="" method="post" enctype="multipart/form-data" name="addproduct" runat="server">

       

       <div id="midle_repeat">

         

         <div id="tit">

         

           <h3>Edit Product</h3>

         

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

			  

			   $newsqry="select * from tbl_product where ItemID=$testi";

			   $reslt=$db->query($newsqry);

			   $row= $reslt->fetch_array();
			   $cateid = $row['ICatID'];

			   ?>

                     
				  
                       <div class="fieldrepeat">

                       

                         <p>Product Name : </p>

                  <input type="text" id="pname" name="pname" class="productfieldlarge" value="<?php echo $row['IItemName']; ?>" style="width:300px;" />


                       

         </div>

                       

                                 <div class="clear">

                                 </div>
                           
                       <input type="hidden" name="category" value="<?php echo $cateid; ?>" />

                                 <div class="clear">

                                 </div>
				
              
                       <div class="fieldrepeat">

                       

                         <p>Price : </p>

                  <input type="text" id="price" name="price" class="productfieldlarge" value="<?php echo $row['price']; ?>" style="width:300px;" />


                       

         </div>

                       

                                 <div class="clear">

                                 </div>


                     <div class="fieldrepeat">

                       

                       <p>Upload Image</p>

                       <label for="fileField"></label>

                         <input type="file" name="image" id="image" size="35" />

                       </div>
                       
                        <div class="clear">

                                 </div>
                       
                          <div class="fieldrepeat">

                       

                         <p>Add in bestseller : </p>

                         

                         <select name="best" id="best" class="status" style="width:300px;">

                   

					   <?php

					   

					           if ($row['bestseller'] == 0)

							   {

								  

								   echo "<option value=".$row['IStatus']." 'selected'>No bestseller</option>";
								    echo "<option value='1'>Best seller</option>";

							   }

							   elseif($row['bestseller']==1)

							   {

								   

								   echo "<option value=".$row['IStatus']." 'selected'>Bestseller</option>";
								   echo "<option value='0'>No bestseller</option>";

							   }

					   

					   ?>

                     

                 </select>

               

                       

                       </div>

                       

                       

                                 <div class="clear">

                                 </div>

                     
                       
                       <div class="fieldrepeat">
                       
                         <p>Description : </p>
                         <div style="width:80%; float:left">
                   <textarea id="detail" name="detail" class="ckeditor" cols="30" rows="15"><?php echo $row['Description']; ?></textarea>
                         </div>
                       
                       </div>

                       

                                 <div class="clear">

                                 </div>

                       

                       <div class="fieldrepeat">

                       

                         <p>Status : </p>

                         

                         <select name="status" id="status" class="status" style="width:150px;">

                   

					   <?php

					   

					           if ($row['IStatus'] == 1)

							   {

								  

								   echo "<option value=".$row['IStatus']." 'selected'>Enable</option>";
								    echo "<option value='0'>Disable</option>";

							   }

							   else

							   {

								   

								   echo "<option value=".$row['IStatus']." 'selected'>Disable</option>";
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

   <script>
	CKEDITOR.replace( 'detail',
    {
        filebrowserBrowseUrl : 'cms/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Images',
        filebrowserFlashBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Flash',
        filebrowserUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        filebrowserFlashUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
    });
	</script>  

</body>

</html>









