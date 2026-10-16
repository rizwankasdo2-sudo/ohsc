<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

} 

 $newsid=$_GET['id'];
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



<title>SEO</title>



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

	$did = $_POST['did'];
	$cid = $_POST['cid'];

	$NewsTitle=$_POST['NewsTitle'];

	$Newsdiscription = $_POST['Newsdiscription'];

	$NewsDetail = $_POST['NewsDetail'];

	$NewsDetail = $_POST['NewsDetail'];
    
    $robots = $_POST['Robot'];

	$NewsUpdate=$db->query("UPDATE tbl_caourseseo SET pagetitle='$NewsTitle', metatitle='$Newsdiscription',metadescription='$NewsDetail', robots='$robots' where courseseo=$newsid") or die("update query error").mysqli_error($db);

	$msg="Updated Sucssefully";

	echo "<script language='javascript'>window.location='diploma.php?cid=$cid&msg=$msg'</script>";

	

	}

?>



       

       <form action="" method="post" enctype="multipart/form-data" name="addproduct" runat="server">

       

       <div id="midle_repeat">

         

         <div id="tit">

         

           <h3>Update Testimonail</h3>

         

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

			  

			   $newsqry="SELECT * FROM `tbl_caourseseo` WHERE `courseseo`=$newsid";

			   $reslt=$db->query($newsqry);

			   $row= $reslt->fetch_array();

			   ?>

                     

                       <div class="fieldrepeat">

                       

                         <p>Page Title : </p>

                         <textarea id="NewsTitle" name="NewsTitle" cols="60" rows="10" ><?php echo $row['pagetitle']; ?></textarea>

                       

                       </div>

                       

                             <div class="clear">

                                 </div>

                       

                       <div class="fieldrepeat">

                       

                         <p> Meta Keywords</p>

                         <label for="textfield"></label>

                         <textarea name="Newsdiscription" id="Newsdiscription"  cols="60" rows="10" ><?php echo $row['metatitle']; ?></textarea>

                       </div>

<div class="fieldrepeat" style="margin-top:10px;">



<p>Meta Description</p>

<label for="textarea"></label>

<textarea name="NewsDetail" id="NewsDetail" cols="60" rows="10"><?php echo $row['metadescription']; ?></textarea>



</div>



         <div class="clear">

         </div>

<div class="fieldrepeat" style="margin-top:10px;">

<p>Robots  : </p>

<label><input type="radio" name="Robot" value="Index,Nofollow" <?php if($row['robots']=="Index,Nofollow"){ echo "checked"; } ?>/>Index</label>
<label><input type="radio" name="Robot" value="Noindex,Follow" <?php if($row['robots']=="Noindex,Follow"){ echo "checked"; } ?> />Follow</label>
<label><input type="radio" name="Robot" value="Noindex,Nofollow" <?php if($row['robots']=="Noindex,Nofollow"){ echo "checked"; } ?> />Disallow</label>
<label><input type="radio" name="Robot" value="Index,Follow" <?php if($row['robots']=="Index,Follow"){ echo "checked"; } ?> />Allow</label> 


</div>



<div class="clear">

</div>



                       

                       <div class="fieldrepeat">

                       

                         <p>Status : </p>

                         

                         <select name="status" id="status" class="status" style="width:150px;">

                   

					   <?php

					   

					           if ($rowofbanner['IStatus'] == 1)

							   {

								   echo "<option value='0'>Disable</option>";

								   echo "<option value=".$row['IStatus']." 'selected'>Enable</option>";

							   }

							   else

							   {

								   echo "<option value='1'>Enable</option>";

								   echo "<option value=".$rowofbanner['IStatus']." 'selected'>Disable</option>";

							   }

					   

					   ?>

                     

                 </select>

               

                       

                       </div>

                       
<input type="hidden" name="did" value="<?php echo $did; ?>" />
<input type="hidden" name="cid" value="<?php echo $cid; ?>" />
                       

                                 <div class="clear">

                                 </div>

                    <div id="buttons" style=" width:713px; margin:0 auto; margin-top:15px;"> 

                    <input type="submit" name="update" id="update" value="update" />

                      <input type="button" value="Cancel" onClick="window.location.href='diploma.php?cid=<?php echo $cid; ?>'" />

                 

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









