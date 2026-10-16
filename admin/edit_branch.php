<?php

session_start();

ob_start();

if($_SESSION['adminid']== "") {
	header("location:index.php");
	exit();
}

$testi=$_GET['id'];

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">



<head>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<title>Branch</title>



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

	


	 $detail = $_POST['detail'];

	 $map = $_POST['map'];
	 
	 $order = $_POST['order'];

	 $status  =  $_POST['status'];
	 
	 $image = $_FILES['image']['name'];
	 
	 $qry = $db->query("select * from tbl_branches where IBranchid=$testi");
	 $qryresult = $qry->fetch_array();
	 $img = $qryresult['IImage'];
	 
	 if($image=='')
	 {
	
	$tetimonial=$db->query("UPDATE tbl_branches SET IBranchMap='$map',            
	
	
	IBranchAddress='$detail',IOrder=$order,IImage='$img',IStatus=$status where IBranchid=$testi");

	$msg="Branches Updated Sucssefully";

	echo "<script language='javascript'>window.location='branches.php?msg=$msg'</script>";
	 }
	 else
	 {
		 
	unlink("../Resources/branch/".$img);
	move_uploaded_file($_FILES['image']['tmp_name'],"../Resources/branch/".$image);	 
	$tetimonial=$db->query("UPDATE tbl_branches SET IBranchMap='$map',            
	
	
	IBranchAddress='$detail',IOrder=$order,IImage='$image',IStatus=$status where IBranchid=$testi");

	$msg="Branches Updated Sucssefully";

	echo "<script language='javascript'>window.location='branches.php?msg=$msg'</script>";
	
		 }


	}

?>



       

       <form action="" method="post" enctype="multipart/form-data" name="addproduct" runat="server">

       

       <div id="midle_repeat">

         

         <div id="tit">

         

           <h3>Update Branch</h3>

         

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

			  

			   $newsqry="select * from tbl_branches where IBranchid=$testi";

			   $reslt=$db->query($newsqry);

			   $row= $reslt->fetch_array();

			   ?>

                     


                     <div class="fieldrepeat">

                       

                       <p>Edit Branch</p>

                    
                        <textarea id="map" name="map"  cols="30" rows="15"><?php echo $row['IBranchMap']; ?></textarea>

                       </div>
                       
                       
                       <div class="fieldrepeat">

                       

                       <p>Order </p>

                       <input type="text" name="order" value="<?php echo $row['IOrder']; ?>" class="productfieldlarge" />

                       </div>

                     <div class="fieldrepeat">

                       

                       <p>Upload Image</p>
					   <label for="image"></label>
						 <input type="file" name="image" id="image" />
                       </div>
                       
                       <div class="fieldrepeat">
                       
                         <p>Description : </p>
                         <div style="width:80%; float:left">
                   <textarea id="detail" name="detail" class="ckeditor" cols="30" rows="15"><?php echo $row['IBranchAddress']; ?></textarea>
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

                      <input type="button" value="Cancel" onClick="window.location.href='branches.php'" />

                 

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









