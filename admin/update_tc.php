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



<title>Update Terms and Conditions</title>



<link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />



<link rel="stylesheet" href="../Resources/admin_css/add-product.css" />

<script type="text/javascript" src="cms/ckeditor/ckeditor.js"></script>
<script src="cms/ckeditor/sample.js" type="text/javascript"></script>



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

       <?php

if(isset($_POST['update'])){

	
$ptxt = $_POST['ptxt'];

	$status  =  $_POST['status'];

	$NewsUpdate=$db->query("UPDATE tandc SET tctxt='$ptxt',IStatus='$status' where tcid=0") or die("update query error").mysqli_error($db);

	$msg="Policy Statement Updated Sucssefully";

	echo "<script language='javascript'>window.location='dashboard.php?msg=$msg'</script>";
	

	}

?>



       

       <form action="" method="post" enctype="multipart/form-data" name="addproduct" runat="server">

       

       <div id="midle_repeat">

         

         <div id="tit">

         

           <h3>Update Terms and Conditions</h3>

         

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

			  

			   $newsqry="SELECT * FROM tandc WHERE tcid=0";

			   $reslt=$db->query($newsqry);

			   $row= $reslt->fetch_array();

			   ?>

                     

                     <div class="fieldrepeat">
                       
                        <p> Description</p>
                         <div style="width:80%; float:left">
                         <textarea id="ptxt" name="ptxt" class="ckeditor" cols="30" rows="15">
                         <?php echo $row['tctxt']; ?>
                         </textarea>
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

                      <input type="button" value="Cancel" onClick="window.location.href='dashboard.php'" />

                 

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
	CKEDITOR.replace( 'ptxt',
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






