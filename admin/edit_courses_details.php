<?php
include("classes/connection.php");

if($_SESSION['adminid']== "") {
	header("location:index.php");
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Courses Details</title>
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
   include("includes/admin-header.php");
   include("includes/admin-menu.php");
?>

   <div id="outer_contanier">
     <div id="board">
       <div id="left_corner">
       </div>
<?php

if(isset($_GET['id']) && is_numeric($_GET['id'])){

   $id = $_GET['id'];
   $query = "SELECT * FROM courses_details WHERE id='$id'";
   $result = $db->query($query);

   if($result->num_rows == 0){
       header('location: courses_details.php');
       exit();
   }

   $page = $result->fetch_array();

  $details  =   $page['details'];

  

}

if(isset($_POST['submit'])){

	$details  =   $_POST['details'];
  
	
	
        $sql = "UPDATE courses_details SET details = '$details'
                                 
                              
                               
                                WHERE id = '$id' ";

        if(!$db->query($sql)){
            die(mysqli_error($db));
        }

    $msg = "Updated Successfully";
    echo "<script language='javascript'>window.location='courses_details.php?msg=$msg'</script>";
}

?>
       <form action="edit_courses_details.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data" name="addproduct" runat="server">
      <input type="hidden" name="id" value="<?php echo $id;?>">
       <div id="midle_repeat">

         <div id="tit">

           <h3>Update Courses Details</h3>

         </div>

       </div>

       <div id="right_corner">

       </div>

       <div id="main_content">

       

         <div id="addproducts">

         

           <div id="uper">

             

             <div id="uperdetail" style="border: none">

                   

                   <div id="general">

                     <!--   <div class="fieldrepeat">
                           <p> </p>
                           <input type="text" id="video" name="video" class="productfieldlarge" style="width:300px;" value="<?php if(!empty($video)){ echo $video; }; ?>" />
                       </div> -->
                       <div class="fieldrepeat">

    <p>Courses Details : </p>
    <div style="width:80%; float:left">
        <textarea id="details" name="details" class="ckeditor" cols="30" rows="15">
            <?php echo  $details;?>
        </textarea>
    </div>

</div>
                      
                      
                         <div class="clear">
                         </div>

                    <div id="buttons" style=" width:713px; margin:0 auto; margin-top:15px;"> 

                    <input type="submit" name="submit" id="submit" value="Submit" />

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

  <script>
      CKEDITOR.replace( 'excerpt',
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









