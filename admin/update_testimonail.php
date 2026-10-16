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

<title>Update Testmonial</title>

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

	$name=$_POST['name'];
  
  $rating = $_POST['rating'];

	$description = $_POST['description'];

	$detail = $_POST['detail'];

	//$NewsDetail = $_POST['NewsDetail'];

	$IStatus  =  $_POST['IStatus'];
  

	$NewsUpdate=$db->query("UPDATE tbl_testimonial SET name='$name', description='$description',detail='$detail',IStatus='$IStatus', rating='$rating', time=now() where testimonialid=$newsid") or die("update query error").mysqli_error($db);

	$msg="Testmonial Updated Sucssefully";

	echo "<script language='javascript'>window.location='testemonial.php?msg=$msg'</script>";

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

			   $newsqry="SELECT * FROM `tbl_testimonial` WHERE `testimonialid`=$newsid";

			   $reslt=$db->query($newsqry);

			   $row= $reslt->fetch_array();

			   ?>

                       <div class="fieldrepeat">
                         <p>Name : </p>

                         <input type="text" id="name" name="name" class="productfieldlarge" style="width:300px;" value="<?php echo $row['name']; ?>" />

                       </div>

                                 <div class="clear">

                                 </div>

                       <div class="fieldrepeat">

                         <p> Discription</p>

                         <label for="textfield"></label>

                         <input type="text" name="description" id="description"  class="productfield" size="300"  cols="60" rows="10" value="<?php echo $row['description']; ?>"/>

                       </div>

                     <div class="fieldrepeat" style="margin-top:10px;">


                       <p> Detail</p>

                       <label for="textarea"></label>

   <textarea name="detail" id="detail" cols="60" rows="10"><?php echo $row['detail']; ?></textarea>

                       </div>

                              <div class="clear">

                                 </div>

                       <div class="fieldrepeat">
                         <p>Status : </p>
                         <?php $st = $row['IStatus'];?>
                         <select name="IStatus" id="IStatus" class="status" style="width:150px;">
                          <?php if($st == 1){?>
                          <option value="1">Enable</option>
                          <option value="0">Disable</option>
                          <?php } else{?>
                          
                          <option value="0">Disable</option>
                          <option value="1">Enable</option>
                          <?php }?>
                         </select>
                      

                       </div>

                         <div class="clear">

                                 </div>

                                 <div class="fieldrepeat">               
<p>Rating : </p>
            <label ><input type="radio" <?php echo($row['rating']==1)?'checked':''; ?> name="rating" value="1"  required/><img src="../images/rating/1.jpg"></label>
            <label ><input type="radio"  <?php echo($row['rating']==2)?'checked':''; ?> name="rating" value="2"/><img src="../images/rating/2.jpg"></label>
            <label ><input type="radio"  <?php echo($row['rating']==3)?'checked':''; ?> name="rating" value="3"/><img src="../images/rating/3.jpg"></label>
            <label ><input type="radio"  <?php echo($row['rating']==4)?'checked':''; ?> name="rating" value="4" /><img src="../images/rating/4.jpg"></label>
            <label ><input type="radio"  <?php echo($row['rating']==5)?'checked':''; ?> name="rating" value="5" /><img src="../images/rating/5.jpg"></label>
        
                       
</div>


                    <div id="buttons" style=" width:713px; margin:0 auto; margin-top:15px;"> 

                    <input type="submit" name="update" id="update" value="update" />

                      <input type="button" value="Cancel" onClick="window.location.href='testimonial.php'" />

                 

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









