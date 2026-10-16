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



<title>Delivery price</title>



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

       <?php

if(isset($_POST['update'])){

	

	$NewsTitle=$_POST['NewsTitle'];

	$maxprice = $_POST['max'];
	$price = $_POST['price'];

	$status  =  $_POST['status'];

	$NewsUpdate=$db->query("UPDATE tbl_zip SET Startzip='$NewsTitle',Endzip='$maxprice',price=$price,IStatus='$status' where zipid=$newsid");

	$msg="Updated Sucssefully";

	echo "<script language='javascript'>window.location='zipcode.php?msg=$msg'</script>";

	

	}

?>



       

       <form action="" method="post" enctype="multipart/form-data" name="addproduct" runat="server">

       

       <div id="midle_repeat">

         

         <div id="tit">

         

           <h3>Delivery price</h3>

         

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

			  

			   $newsqry="select * from tbl_zip where zipid=$newsid";

			   $reslt=$db->query($newsqry);

			   $row= $reslt->fetch_array();

			   ?>

                     

                       <div class="fieldrepeat">

                       

                         <p>Start Zip : </p>

                         <input type="text" id="NewsTitle" name="NewsTitle" class="productfieldlarge" style="width:300px;" value="<?php echo $row['Startzip']; ?>" />

                       

                       </div>

                       

                                 <div class="clear">

                                 </div>
				
                
                       <div class="fieldrepeat">

                       

                         <p>End Zip : </p>

                         <input type="text" id="max" name="max" class="productfieldlarge" style="width:300px;" value="<?php echo $row['Endzip']; ?>" />

                       

                       </div>

                       

                                 <div class="clear">

                                 </div>
                                 
                                  <div class="fieldrepeat">

                       

                         <p>Price : </p>

                         <input type="text" id="price" name="price" class="productfieldlarge" style="width:300px;" value="<?php echo $row['price']; ?>" />

                       

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

                    <div id="buttons" style="width:710px; margin:0 auto; margin-top:15px;"> 

                    <input type="submit" name="update" id="update" value="update" class="add_cancel_button" style="float:left;" />

                      <input type="button" value="Cancel" onClick="window.location.href='zipcode.php'" class="add_cancel_button" />

                 

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









