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



<title>Companies</title>



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

	

	$name=$_POST['name'];

	$company = $_POST['company'];

	 $img = $_FILES['image']['name'];

	$status  =  $_POST['status'];

	$qry=$db->query("select * from tbl_borucher where IBroucherID=$testi");

	$row= $qry->fetch_array();

	$image=$row['IBroucher'];

if($img==""){

	$tetimonial=$db->query("UPDATE tbl_borucher SET ICompanyID=$company,IBroucherName='$name',            
	
	
	IBroucher='$image',BStatus='$status' where IBroucherID=$testi");

	$msg="Brochure Updated Sucssefully";

	echo "<script language='javascript'>window.location='broucher.php?msg=$msg'</script>";

}

else{
	
	

	if (($_FILES["image"]["type"] == "image/jpeg") || ($_FILES["image"]["type"] == "image/pjpeg") || ($_FILES["image"]["type"] == "image/png"))

			   {

				   if ($_FILES["image"]["error"] > 0)

				   {

					   $no = "Error: ".$_FILES["image"]["error"] . "<br />";

					   echo "<script language='javascript'>window.location='broucher.php?no=$no'</script>";

				   }

				   else

				   {
					   
					 

	 			

					   unlink("../Resources/broucher/".$image);

					   move_uploaded_file($_FILES["image"]["tmp_name"], "../Resources/broucher/" . $img);

					   $updatebanners = $db->query("UPDATE tbl_borucher SET ICompanyID=$company,IBroucherName='$name',            
	
	
	IBroucher='$img',BStatus='$status' where IBroucherID=$testi");

					   

					   $updatebannerTOtable = $db->query($updatebanners);

					   $msg="Brochure Updated sucessfully";

					 echo "<script language='javascript'>window.location='broucher.php?msg=$msg'</script>";

				   }

			   }

			   

			   else

			   {

				   $invalid = "Invalid file";

				   echo "<script language='javascript'>window.location='broucher.php?invalid=$invalid'</script>";

			   }

		   

	

	

	}

	}

?>



       

       <form action="" method="post" enctype="multipart/form-data" name="addproduct" runat="server">

       

       <div id="midle_repeat">

         

         <div id="tit">

         

           <h3>Update Brochure</h3>

         

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

			  

			   $newsqry="select * from tbl_borucher where IBroucherID=$testi";

			   $reslt=$db->query($newsqry);

			   $row= $reslt->fetch_array();
			   
			

			   ?>

                     

                       <div class="fieldrepeat">

                       

                         <p>Name : </p>

                         <input type="text" id="name" name="name" class="productfieldlarge" style="width:300px;" value="<?php echo $row['IBroucherName']; ?>" />

                       

                       </div>

                       

                                 <div class="clear">

                                 </div>

                       

              
                     <div class="fieldrepeat">

                       

                       <p>Upload Image</p>

                       <label for="fileField"></label>

                         <input type="file" name="image" id="image" size="35" />

                       </div>

                     
                              	
                 <div class="fieldrepeat">

                       

                         <p>Company : </p>

                         

                         <select name="company" id="company" class="productfieldlarge" style="width:140px;">
				
                
                <?php 
				$company=$db->query("select ICompanyID,ICompanyHeading from tbl_company order by ICompanyID desc");
				
				
				while($result1= $company->fetch_array()){
					
					echo $result1['ICompanyID'];
				
				?>
                
                           <option value="<?php echo $result1['ICompanyID'];?>" <?php if($row['ICompanyID']==$result1['ICompanyID']) {echo "selected";}?>><?php echo $result1['ICompanyHeading']; ?></option>
<?php }?>

                         </select>

                       

                       </div>

                       

                                 <div class="clear">

                                 </div>
                        

                       

                       <div class="fieldrepeat">

                       

                         <p>Status : </p>

                         

                         <select name="status" id="status" class="status" style="width:150px;">

                   

					   <?php

					   

					           if ($row['BStatus'] == 1)

							   {
								 echo "<option value=".$row['BStatus']." 'selected'>Enable</option>";
								   echo "<option value='0'>Disable</option>";

								  

							   }

							   else

							   {
								  echo "<option value=".$row['BStatus']." 'selected'>Disable</option>";
								   echo "<option value='1'>Enable</option>";

								 

							   }

					   

					   ?>

                     

                 </select>

               

                       

                       </div>

                       

                       

                                 <div class="clear">

                                 </div>

                    <div id="buttons" style=" width:713px; margin:0 auto; margin-top:15px;"> 

                    <input type="submit" name="update" id="update" value="update" />

                      <input type="button" value="Cancel" onClick="window.location.href='broucher.php'" />

                 

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









