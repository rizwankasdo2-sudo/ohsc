<?php
session_start();
ob_start();
if($_SESSION['adminid']== "") {
  header("location:index.php");
  exit();
}
include("classes/connection.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Socials Links</title>
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

// if(isset($_GET['social_id']) && is_numeric($_GET['social_id']))
{

   $social_id = 3;
   $query = "SELECT * FROM social_links WHERE social_id=3";
   $result = $db->query($query);

   if($result->num_rows == 0){
       header('location: pages.php');
       exit();
   }

   $page = $result->fetch_array();

  $facebook  =   $page['facebook'];
  $google =  $page['google'];
  $twitter = $page['twitter'];
  $linkedin = $page['linkedin'];
  $youtube = $page['youtube'];
  $instagram = $page['instagram'];

}

if(isset($_POST['submit'])){

	$facebook  =  $_POST['facebook'];
	$google    =  $_POST['google'];
  $twitter   =  $_POST['twitter'];
  $linkedin  =  $_POST['linkedin'];
  $youtube  =  $_POST['youtube'];
  $instagram  =  $_POST['instagram'];
	
        $sql = "UPDATE social_links SET facebook = '$facebook',
                                        google = '$google',
                                        twitter = '$twitter',
                                        youtube = '$youtube',
                                        linkedin = '$linkedin',
                                        instagram = '$instagram' 
                               
                                        WHERE social_id = '3' ";

        if(!$db->query($sql)){
            die(mysqli_error($db));
        }

    $msg = "Updated Successfully";
    echo "<script language='javascript'>window.location='edit_social.php?msg=$msg'</script>";
}

?>
       <form action="edit_social.php" method="post" enctype="multipart/form-data" name="addproduct" runat="server">
      
       <div id="midle_repeat">

         <div id="tit">

           <h3>Update Social Links</h3>

         </div>

       </div>

       <div id="right_corner">

       </div>

       <div id="main_content">

         <div id="addproducts">
             <?php
             if (isset($_GET['msg'])) {
                 echo '<p style="color:black; font-size:28px; text-align:center; font-weight:bold">';
                 echo $_GET['msg'];
                 echo '</p>';
            }
            ?>

           <div id="uper">

             <div id="uperdetail" style="border: none">

                   <div id="general">

                       <div class="fieldrepeat">
                           <p>Facebook </p>
                           <input type="text" id="facebook" name="facebook" class="productfieldlarge" style="width:600px;" value="<?php if(!empty($facebook)){ echo $facebook; }; ?>" />
                       </div>
                        <div class="fieldrepeat">
                           <p>Twitter </p>
                           <input type="text" id="twitter" name="twitter" class="productfieldlarge" style="width:600px;" value="<?php if(!empty($twitter)){ echo $twitter; }; ?>" />
                       </div>
                        <div class="fieldrepeat">
                           <p>Google+ </p>
                           <input type="text" id="google" name="google" class="productfieldlarge" style="width:600px;" value="<?php if(!empty($google)){ echo $google; }; ?>" />
                       </div>
                        <div class="fieldrepeat">
                           <p>Linkedin </p>
                           <input type="text" id="linkedin" name="linkedin" class="productfieldlarge" style="width:600px;" value="<?php if(!empty($linkedin)){ echo $linkedin; }; ?>" />
                       </div>
                       <div class="fieldrepeat">
                           <p>Youtube </p>
                           <input type="text" id="youtube" name="youtube" class="productfieldlarge" style="width:600px;" value="<?php if(!empty($youtube)){ echo $youtube; }; ?>" />
                       </div>
                       <div class="fieldrepeat">
                           <p>Instagram </p>
                           <input type="text" id="instagram" name="instagram" class="productfieldlarge" style="width:600px;" value="<?php if(!empty($instagram)){ echo $instagram; }; ?>" />
                       </div>
                       <div class="clear">
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









