<?php

session_start();

ob_start();

 include("classes/connection.php");


if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();


} 

  $ID = $_GET['uid'];



 if($_POST['submit']){

    
      $diploma_id = $_POST['diploma_id'];
      $rating = $_POST['rating'];
      $name =   mysqli_real_escape_string($db, $_POST['name']);

      $description = mysqli_real_escape_string($db, $_POST['description']);

        $detail = mysqli_real_escape_string($db, $_POST['detail']);

      $IStatus  =  $_POST['IStatus'];
     $date = date('Y-m-d H:i:s');

     $qry="insert into tbl_testimonial(name, detail,description, IStatus,time, diploma_id, rating)
                                  values ('$name', '$detail','$description','$IStatus','$date', '$diploma_id', '$rating')";

      $db->query($qry) or die(mysqli_error($db));

      echo $msg = "Inserted Successfully";

    echo "<script language='javascript'>window.location='testemonial.php?msg=$msg'</script>";



}


?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">



<head>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<title>Add Testimonail</title>



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

       

       <form action="" method="post" enctype="multipart/form-data" name="addproduct" runat="server">

       

       <div id="midle_repeat">

         

         <div id="tit">

         

           <h3>Add Testimonail</h3>

         

         </div>

         

         <div id="buttons">

         

           <ul style="float:right;">

           

             <li><input type="submit" value="Save" name="submit" class="add_cancel_button" /></li>

             <li><a href="testimonial.php">Cancel</a></li>

           

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

                       

                      
                       

                                 <div class="clear">

                                 </div>

                       

                       <div class="fieldrepeat">

                       

                         <p>Name</p>

                         <label for="textfield"></label>
                        <input type="hidden" value="<?php echo $ID; ?>" name="diploma_id">
                         <input type="text" name="name" id="name"  class="productfield" size="300"/>

                       </div>

                     <div class="fieldrepeat" style="margin-top:10px;">

                       

                       <p>description</p>

                       <label for="textarea"></label>

                 <textarea name="description" id="description" cols="60" rows="10"></textarea>

                       </div>

                       

                                 <div class="clear">

                                 </div>

                        <div class="fieldrepeat" style="margin-top:10px;">

                       

                       <p>Detial</p>

                       <label for="textarea"></label>

                 <textarea name="detail" id="detail" cols="60" rows="10"></textarea>

                       </div>


                       <div class="fieldrepeat">

                       

                         <p>Status : </p>

                         

                         <select name="IStatus" id="IStatus" class="productfieldlarge" style="width:140px;">

                           <option value="1">Enable</option>

                           <option value="0">Disable</option>

                         </select>

                       

                       </div>

                       

                                 <div class="clear">

                                 </div>

                                         <div class="fieldrepeat">               
<p>Rating : </p>
            <label ><input type="radio" <?php echo($emailRecord['rating']==1)?'checked':''; ?> name="rating" value="1"  required/><img src="../images/rating/1.jpg"></label>
            <label ><input type="radio"  <?php echo($emailRecord['rating']==2)?'checked':''; ?> name="rating" value="2"/><img src="../images/rating/2.jpg"></label>
            <label ><input type="radio"  <?php echo($emailRecord['rating']==3)?'checked':''; ?> name="rating" value="3"/><img src="../images/rating/3.jpg"></label>
            <label ><input type="radio"  <?php echo($emailRecord['rating']==4)?'checked':''; ?> name="rating" value="4" /><img src="../images/rating/4.jpg"></label>
            <label ><input type="radio"  <?php echo($emailRecord['rating']==5)?'checked':''; ?> name="rating" value="5" /><img src="../images/rating/5.jpg"></label>
        
                       
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