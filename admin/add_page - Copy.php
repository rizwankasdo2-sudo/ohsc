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
<title>Add Page</title>
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
if(isset($_POST['submit'])){

	$title  =   $_POST['title'];
	$name =  $_POST['name'];
	$content =  $_POST['content'];
    $slug = $_POST['slug'];
	$status  =  $_POST['status'];
    $meta_title =  $_POST['meta_title'];
    $meta_keywords = $_POST['meta_keywords'];
    $meta_description = $_POST['meta_description'];
    $robots = $_POST['robots'];
    $alt = $_POST['alt'];
    $videoId = $_POST['videoId'];

    $tmpName = $_FILES['image']['tmp_name'];
    $image = time().$_FILES['image']['name'];

    if(move_uploaded_file($tmpName, '../images/pages/'.$image)){

        $sql = "INSERT INTO pages (title, slug,  name, content, status, meta_title, meta_description, meta_keywords, robots, image, alt, videoId) VALUES
           ('$title', '$slug', '$name', '$content', '$status', '$meta_title', '$meta_description', '$meta_keywords', '$robots', '$image', '$alt', '$videoId')";

        if(!$db->query($sql)){
            die(mysqli_error($db));
        }

    }

    $msg="Added Successfully";
    echo "<script language='javascript'>window.location='pages.php?msg=$msg'</script>";

}

?>
       <form action="" method="post" enctype="multipart/form-data" name="addproduct" runat="server">

       <div id="midle_repeat">

         <div id="tit">

           <h3>Add Page</h3>

         </div>

       </div>

       <div id="right_corner">

       </div>

       <div id="main_content">

       

         <div id="addproducts">

         

           <div id="uper">

             

             <div id="uperdetail" style="border: none">

                   

                   <div id="general">

                       <div class="fieldrepeat">
                           <p>Name </p>
                           <input type="text" id="name" name="name" class="productfieldlarge" style="width:300px;" value="<?php if(!empty($name)){ echo $name; }; ?>" required/>
                       </div>
                       <div class="clear">
                       </div>

                       <div class="fieldrepeat">
                        <p>Title </p>
                           <input type="text" id="title" name="title" class="productfieldlarge" style="width:300px;" value="<?php if(!empty($title)){ echo $title; }; ?>" required/>
                        </div>
                         <div class="clear">
                         </div>

                       

                       <div class="fieldrepeat">
                           <p>Slug : </p>
                           <input type="text" id="slug" name="slug" class="productfieldlarge" style="width:300px;" value="<?php if(!empty($slug)){ echo $slug; }; ?>" required/>
                       </div>
                       <div class="clear">
                       </div>
                       
                       <div class="fieldrepeat">
                           <p>Description : </p>
                           <div style="width:80%; float:left">
                               <textarea id="content" name="content" class="ckeditor" cols="30" rows="15" required>
                                   <?php if(!empty($content)){ echo $content; }; ?>
                               </textarea>
                           </div>
                       </div>

                       <div class="fieldrepeat">
                           <p>Thumbnail : </p>
                           <input type="file" id="image" name="image" class="productfieldlarge" style="width:300px;" required/>
                       </div>
                       <div class="fieldrepeat">
                           <p>Alt : </p>
                           <input type="text" id="alt" name="alt" class="productfieldlarge" style="width:300px;" value="<?php if(!empty($alt)){ echo $alt; }; ?>" required/>
                       </div>
                       <div class="clear">
                       </div>

                       <div class="fieldrepeat">
                         <p>Status : </p>
                       <select name="status" id="status" class="status" style="width:150px;" required>
                           <option value="1">Enable</option>
                           <option value="0">Disable</option>
                        </select>
                       </div>
                       <div class="fieldrepeat">
                         <p>Video Id : </p>
                       <input type="text" id="videoId" name="videoId" class="productfieldlarge" style="width:300px;" value="<?php if(!empty($videoId)){ echo $videoId; }; ?>" />
                       </div>
                         <div class="clear">
                         </div>

                       <div class="fieldrepeat">
                           <p>Page Title : </p>
                           <textarea class="seo_text" name="meta_title" id="meta_title" rows="3" style="width: 645px; padding:10px;" required><?php if(isset($meta_title)){ echo $meta_title; } ?></textarea>
                       </div>

                       <div class="clear">
                       </div>

                       <div class="fieldrepeat">
                           <p>Meta Keywords : </p>
                           <textarea class="seo_text" name="meta_keywords" id="meta_keywords" rows="3" style="width: 645px; padding:10px;" required><?php if(isset($meta_keywords)){ echo $meta_keywords; } ?></textarea>
                       </div>

                       <div class="clear">
                       </div>

                       <div class="fieldrepeat">
                           <p>Meta Description : </p>
                           <textarea class="seo_text" name="meta_description" id="meta_description" rows="3" style="width: 645px; padding:10px;" required><?php if(isset($meta_description)){ echo $meta_description; } ?></textarea>
                       </div>

                       <div class="clear">
                       </div>

                       <div class="fieldrepeat">
                           <p>Robots  : </p>
                           <label><input type="radio" name="robots" value="Index,Nofollow" />Index</label>
                           <label><input type="radio" name="robots" value="Noindex,Follow" />Follow</label>
                           <label><input type="radio" name="robots" value="Noindex,Nofollow" />Disallow</label>
                           <label><input type="radio" checked="" name="robots" value="Index,Follow" />Allow</label>
                       </div>

                    <div id="buttons" style=" width:713px; margin:0 auto; margin-top:15px;"> 

                    <input type="submit" name="submit" id="submit" value="Submit" />

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









