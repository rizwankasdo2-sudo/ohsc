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



<title>Footer Links</title>



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

	

	$NewsTitle=$_POST['NewsTitle'];
	
	if (!isset ($_POST['slug']) || $_POST['slug'] == '')
	{
		$slug = str_replace(' ', '-', strtolower($NewsTitle));
	}
	else
	{
		$slug = str_replace(' ', '-', strtolower($_POST['slug']));
	}

	$Newsdiscription = $_POST['Newsdiscription'];

	$NewsDetail = $_POST['description'];

	$status  =  $_POST['status'];

	$msg="Slug Already Exists";
	
	$NewsUpdate=$db->query("UPDATE tbl_footer SET footertitle='$NewsTitle',slug='$slug', footerorder='$Newsdiscription',footerdetail='$NewsDetail',IStatus='$status' where footerid=$newsid") or die("<script language='javascript'>window.location='footer.php?msg=$msg'</script>").mysqli_error($db);

	$msg=" Updated Sucssefully";

	echo "<script language='javascript'>window.location='footer.php?msg=$msg'</script>";

	

	}

?>



       

       <form action="" method="post" enctype="multipart/form-data" name="addproduct" runat="server">

       

       <div id="midle_repeat">

         

         <div id="tit">

         

           <h3>Add Footer Links</h3>

         

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

			  

			   $newsqry="select * from tbl_footer where footerid=$newsid";

			   $reslt=$db->query($newsqry);

			   $row= $reslt->fetch_array();

			   ?>

                     

                       <div class="fieldrepeat">

                       

                         <p>Footer Tiltle : </p>

                         <input type="text" id="NewsTitle" name="NewsTitle" class="productfieldlarge" style="width:300px;" value="<?php echo $row['footertitle']; ?>" />

                       

                       </div>

                       

                                 <div class="clear">

                                 </div>

                       

                       <div class="fieldrepeat">

                       

                         <p>Slug : </p>

                         <input type="text" id="slug" name="slug" class="productfieldlarge" style="width:300px;" value="<?php echo $row['slug']; ?>" />

                       

                       </div>

                       

                                 <div class="clear">

                                 </div>

                       

                       <div class="fieldrepeat">

                       

                         <p>Order Number</p>

                         <label for="textfield"></label>

                         <input type="text" name="Newsdiscription" id="Newsdiscription"  class="productfield" size="300" value="<?php echo $row['footerorder']; ?>"/>
                         
                         

                       </div>
                       
                       <div class="fieldrepeat">
                       
                         <p>Detail : </p>
                         <div style="width:80%; float:left">
                         <textarea id="description" name="description" class="ckeditor" cols="30" rows="15">
                             <?php echo $row['footerdetail']; ?>
                         </textarea>
                         </div>
                       
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

                      <input type="button" value="Cancel" onClick="window.location.href='footer.php'" />

                 

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
	CKEDITOR.replace( 'NewsDetail',
    {
        filebrowserBrowseUrl : 'cms/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Images',
        filebrowserFlashBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Flash',
        filebrowserUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        filebrowserFlashUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
    });
	</script>
     <script>
	CKEDITOR.replace( 'description',
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









