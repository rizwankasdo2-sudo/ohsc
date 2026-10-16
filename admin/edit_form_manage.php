<?php
session_start();
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
<title>Form Manage</title>
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



  // $id = $_GET['id'];
   $query = "SELECT * FROM form_manage WHERE id=2";
   $result = $db->query($query);

   if($result->num_rows == 0){
       header('location: edit_form_manage.php');
       exit();
   }

   $row = $result->fetch_array();

$banner_img = $row['banner_img'];
$form_content = htmlentities($row['form_content']);
$form_content2 = htmlentities($row['form_content2']);
$guidelines_one = htmlentities($row['guidelines_one']);
$guidelines_two = htmlentities($row['guidelines_two']);
$footer_img = $row['footer_img'];
$heading1  = $row['form_heading'];
$heading2  = $row['form_heading2'];



if(isset($_POST['submit'])){

	  $form_heading = $_POST['form_heading'];
    $form_content = $_POST['form_content'];
    $form_heading2 = $_POST['form_heading2'];
    $form_content2 = $_POST['form_content2'];
    $guidelines_one = $_POST['guidelines_one'];
    $guidelines_two = $_POST['guidelines_two'];

    if(isset($_FILES['banner_img']['name']) && !empty($_FILES['banner_img']['name'])){

        $tmpName = $_FILES['banner_img']['tmp_name'];
        $image = time().$_FILES['banner_img']['name'];
        move_uploaded_file($tmpName, '../images/form_banner/'.$image);

    } else {

        $image = $_POST['banner_img1'];

    }

       if(isset($_FILES['footer_img']['name']) && !empty($_FILES['footer_img']['name'])){

        $tmpName = $_FILES['footer_img']['tmp_name'];
        $image1 = time().$_FILES['footer_img']['name'];
        move_uploaded_file($tmpName, '../images/form_banner/'.$image1);

    } else {

        $image1 = $_POST['footer_img1'];

    }

        $sql = "UPDATE form_manage SET form_heading = '$form_heading',
                                form_content = '$form_content',
                                form_heading2 = '$form_heading2',
                                form_content2 = '$form_content2',
                                guidelines_one = '$guidelines_one',
                                guidelines_two = '$guidelines_two',
                                footer_img    = '$image1',
                                banner_img    ='$image'
                                
                                WHERE id = 2";

        if(!$db->query($sql)){
            die(mysqli_error($db));
        }

    $msg = "Updated Successfully";
    echo "<script language='javascript'>window.location='edit_form_manage.php?msg=$msg'</script>";
}



?>
      

       <div id="midle_repeat">

         <div id="tit">

           <h3>Form Manage</h3>

         </div>

       </div>


       <div id="right_corner">

       </div>

       <div id="main_content">

       <?php if (isset($_GET['msg'])) {

      echo '<p style="color:black; font-size:28px; text-align:center; font-weight:bold">';

      echo $_GET['msg'];

      echo '</p>';

    }

    elseif (isset($_GET['no'])) {

      echo $no;

    }

    elseif (isset($_GET['invalid'])) {

      echo '<p style="color:black; font-size:28px; text-align:center; font-weight:bold">';

      echo $_GET['invalid'];

      echo '</p>';

    }

?>

         <div id="addproducts">

         

           <div id="uper">

             

             <div id="uperdetail" style="border: none">
<!--   <form id="imageform" method="post" enctype="multipart/form-data" action='ajaximage.php'>
Upload your image <input type="file" name="photoimg" id="photoimg" />
</form> -->

<div id='preview' style="width:200px; padding: 5px; margin-top: 10px;"></div>

                   

                   <div id="general">
                    <form action="" method="post" enctype="multipart/form-data" name="addproduct" runat="server">

                       <div class="fieldrepeat">
                        <p>Form Heading </p>
                           <input type="text" id="form_heading" name="form_heading" class="productfieldlarge" style="width:300px;" value="<?php if(!empty($heading1)){ echo $heading1; }; ?>" required/>
                        </div>
                         <div class="clear">
                         </div>

            
                      
                       <div class="fieldrepeat">
                           <p>Form Content : </p>
                           <div style="width:80%; float:left">
                               <textarea id="form_content" name="form_content" class="ckeditor" cols="30" rows="15" required>
                                   <?php if(!empty($form_content)){ echo $form_content; }; ?>
                               </textarea>
                           </div>
                       </div>

                        <div class="fieldrepeat">
                        <p>Form Heading 2 </p>
                           <input type="text" id="form_heading2" name="form_heading2" class="productfieldlarge" style="width:300px;" value="<?php if(!empty($heading2)){ echo $heading2; }; ?>" required/>
                        </div>

                          <div class="fieldrepeat">
                           <p>Form Content 2 : </p>
                           <div style="width:80%; float:left">
                               <textarea id="con" name="form_content2" class="ckeditor" cols="30" rows="15" required>
                                   <?php if(!empty($form_content2)){ echo $form_content2; }; ?>
                               </textarea>
                           </div>
                       </div>

                         <div class="fieldrepeat">
                           <p>Guide Lines For what did you Like most about the course : </p>
                           <div style="width:80%; float:left">
                               <textarea id="NewsDetail" name="guidelines_one" class="ckeditor" cols="30" rows="15" required>
                                   <?php if(!empty($guidelines_one)){ echo $guidelines_one; }; ?>
                               </textarea>
                           </div>
                       </div>

                       <div class="fieldrepeat">
                           <p>Guide Lines For What did you like at least : </p>
                           <div style="width:80%; float:left">
                               <textarea id="excerpt" name="guidelines_two" class="ckeditor" cols="30" rows="15" required>
                                   <?php if(!empty($guidelines_two)){ echo $guidelines_two; }; ?>
                               </textarea>
                           </div>
                       </div>

                      

                       <div class="fieldrepeat">

                           <p>Banner Image : </p>
                          
                            <?php if(!empty($banner_img)){ ?>
                        <img src="../images/form_banner/<?php echo $banner_img; ?>" style="width: 230px; height: 100px;"/>
                        <a href="remove_form_banner.php?id=2" class="btn default fileinput-exists" data-dismiss="fileinput">
                             Remove
                          </a> 
                        <?php } ?>
                         <input type="file" id="banner_img" name="banner_img" class="productfieldlarge" style="width:300px;" />
                           <input type="hidden" name="banner_img1" value="<?php echo $banner_img; ?>" />


                       </div>

                       <div class="fieldrepeat">

                           <p>Footer Image : </p>

                           <input type="file" id="footer_img" name="footer_img" class="productfieldlarge" style="width:300px;" />
                           <input type="hidden" name="footer_img1" value="<?php echo $footer_img; ?>" />
                             <?php if(!empty($footer_img)){ ?>
                        <img style="width: 230px; height: 100px;" src="../images/form_banner/<?php echo $footer_img; ?>" />
                         <a href="remove_form_footer_image.php?id=2" class="btn default fileinput-exists" data-dismiss="fileinput">
                             Remove
                          </a> 
                        <?php } ?>
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

   <script>
      CKEDITOR.replace( 'con',
          {
              filebrowserBrowseUrl : 'cms/ckfinder/ckfinder.html',
              filebrowserImageBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Images',
              filebrowserFlashBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Flash',
              filebrowserUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
              filebrowserImageUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
              filebrowserFlashUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
          });
  </script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
  <script type="text/javascript" src="js/scripts/jquery.min.js"></script>
<script type="text/javascript" src="js/scripts/jquery.form.js"></script>
    <script type="text/javascript" >
 $(document).ready(function() { 
    
            $('#photoimg').live('change', function()      { 
                 $("#preview").html('');
          $("#preview").html('<img src="loader.gif" alt="Uploading...."/>');
      $("#imageform").ajaxForm({
            target: '#preview'
    }).submit();
    
      });
        }); 
</script>

</body>

</html>









