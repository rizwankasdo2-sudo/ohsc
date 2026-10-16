<?php
session_start();
ob_start();

if($_SESSION['adminid']== "") {

	header("location:index.php");
	exit();

}
 
 if(isset($_GET['id']) ) {

    $did = $_GET['id'];
    $cid = $_GET['cid'];

} ?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Udate Testmonial</title>

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

      // print_r($_POST);

if(isset($_POST['update'])){

	 $name =  $_POST['name'];

      $description = $_POST['description'];

        $detail = $_POST['detail'];

      $IStatus  =  $_POST['IStatus'];
     $time = date('Y-m-d H:i:s');
     $did = $_POST['diploma_id'];
     $rating = $_POST['rating'];

                                  
    $sql = "SELECT COUNT(*) FROM tbl_testimonial WHERE diploma_id='$did'";
    $result = $db->query($sql);

    $row = $result->fetch_array();
    if($row[0]){
        $sql = "UPDATE tbl_testimonial SET name='$name', description = '$description', detail = '$detail',IStatus='$IStatus',rating='$rating' WHERE diploma_id = '$did'";
        if(!$db->query($sql)){
            die(mysqli_error($db));
        }
    } else {
        $sql = "INSERT INTO tbl_testimonial (diploma_id,name, detail, description,IStatus,time,rating) VALUES ('$did', '$name', '$detail','$description',$IStatus,'$time','$rating')";
        if(!$db->query($sql)){
            die(mysqli_error($db));
        }
    }

	$msg="Updated Sucssefully";
	echo "<script language='javascript'>window.location='diploma.php?cid=$cid&msg=$msg'</script>";
	exit();
	} ?>



       

       <form action="testmonials.php?did=<?php echo $did ?>&cid=<?php echo $cid; ?>" method="post" enctype="multipart/form-data" name="addproduct" runat="server">

       <input type="hidden" value="<?php echo $did;?>" name="diploma_id">

       <div id="midle_repeat">

         

         <div id="tit">

         

           <h3>Testmonial</h3>

         

         </div>

         

        

       

       </div>

       

       <div id="right_corner">

       </div>

       

       <div id="main_content">

       

         <div id="addproducts">

         

           <div id="uper">

           

             <div id="upertop">

             

               <h4>Testmonial</h4>

             

             </div>

             

             <div id="uperdetail">

                   

                   <div id="general">

            <?php
			$qry=$db->query("select * from  tbl_testimonial where diploma_id='$did'");
			$emailRecord = $qry->fetch_array(); ?>
                     

                     
                       <div class="fieldrepeat">


                         <p>Name : </p>

                        <input type="text" id="name" name="name" value="<?php echo $emailRecord['name'];?>" class="productfieldlarge" style="width:300px;" />

                       

         </div>
<div class="fieldrepeat">

                       

                         <p> Discription</p>

                         <label for="textfield"></label>

                         <input type="text" name="description" id="description"  class="productfield" size="300"  cols="60" rows="10" value="<?php echo $emailRecord['description']; ?>"/>

                       </div>

                     <div class="fieldrepeat" style="margin-top:10px;">

                       

                       <p> Detail</p>

                       <label for="textarea"></label>

   <textarea name="detail" id="detail" cols="60" rows="10"><?php echo $emailRecord['detail']; ?></textarea>

                 

                       </div>

                       

                                 <div class="clear">

                                 </div>
                       
<!-- 
                                 <div class="clear">

                                 </div>

 
                       <div class="fieldrepeat">

                       

                         <p>Detail : </p>

                        <div style="width: 78%;padding-top:10px;margin-left: 183px;">
                            <textarea id="body"  name="detail" cols="3" rows="4" class="ckeditor"><?php echo $emailRecord['detail'];?></textarea>
                        </div>

                       

         </div> -->
                       

                         <div class="clear">

                         </div>

                         <div class="fieldrepeat">
                          <p>Status : </p>
                           <select name="IStatus">
                          <option value="1">Enable</option>
                          <option value="0">Disable</option>
                           </select>
                         </div>

        <div class="fieldrepeat">               
<p>Rating : </p>
            <label ><input type="radio" <?php echo($emailRecord['rating']==1)?'checked':''; ?> name="rating" value="1"  required/><img src="../images/rating/1.jpg"></label>
            <label ><input type="radio"  <?php echo($emailRecord['rating']==2)?'checked':''; ?> name="rating" value="2"/><img src="../images/rating/2.jpg"></label>
            <label ><input type="radio"  <?php echo($emailRecord['rating']==3)?'checked':''; ?> name="rating" value="3"/><img src="../images/rating/3.jpg"></label>
            <label ><input type="radio"  <?php echo($emailRecord['rating']==4)?'checked':''; ?> name="rating" value="4" /><img src="../images/rating/4.jpg"></label>
            <label ><input type="radio"  <?php echo($emailRecord['rating']==5)?'checked':''; ?> name="rating" value="5" /><img src="../images/rating/5.jpg"></label>
        
                       
</div>
                                 <div class="clear">

                                 </div>

                                

                                 

                    <div id="buttons" style=" width:713px; margin:0 auto; margin-top:10px;"> 

                    <input type="submit" name="update" id="update" value="update" />

                      <input type="button" value="Cancel" onClick="window.location.href='diploma.php?cid=<?php echo $cid;?>'" />

            

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
	CKEDITOR.replace( 'body',
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









