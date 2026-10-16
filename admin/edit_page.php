<?php
session_start();
ob_start();
if($_SESSION['adminid']== "")
{
  header("location:index.php");
  exit();
}
include("classes/connection.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edit Page</title>
<link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />
<link rel="stylesheet" href="../Resources/admin_css/add-product.css" />
<script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>
<script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
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
   $query = "SELECT * FROM pages WHERE id='$id'";
   $result = $db->query($query);
   if($result->num_rows == 0){
       header('location: pages.php');
       exit();
   }
   $page = $result->fetch_array();
   $title = $page['title'];
   $name = $page['name'];
   $content = $page['content'];
   $image = $page['image'];
   $schema_markup =  $page['schema_markup'];
   $meta_title = $page['meta_title'];
   $meta_keywords = $page['meta_keywords'];
   $meta_description = $page['meta_description'];
   $robots = $page['robots'];
   $status = $page['status'];
   $slug = $page['slug'];
   $alt = $page['alt'];
   $videoId = $page['videoId'];
   $show_in_footer_sections = explode(',', $page['show_in_footer_sections']);
}
if(isset($_POST['submit'])){
	$title  =   $_POST['title'];
	$name =  $_POST['name'];
	$content =  $_POST['content'];
    $schema_markup =  $_POST['schema_markup'];
    $meta_title =  $_POST['meta_title'];
    $meta_keywords = $_POST['meta_keywords'];
    $meta_description = $_POST['meta_description'];
    $robots = $_POST['robots'];
    $alt = $_POST['alt'];
    $videoId = $_POST['videoId'];
    $show_in_footer_sections = isset($_POST['show_in_footer_sections']) ? $_POST['show_in_footer_sections'] : [];
    $show_in_footer_sections = implode(',', $show_in_footer_sections);
    if(isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])){
        $tmpName = $_FILES['image']['tmp_name'];
        $image = time().$_FILES['image']['name'];
        move_uploaded_file($tmpName, '../images/pages/'.$image);
    } else {
        $image = $_POST['image1'];
    }
        $sql = "UPDATE pages SET title = '$title',
                                name = '$name',
                                content = '$content',
                                schema_markup = '$schema_markup',
                                meta_title = '$meta_title',
                                meta_description = '$meta_description',
                                meta_keywords = '$meta_keywords',
                                robots = '$robots',
                                image= '$image',
                                alt = '$alt',
                                videoId = '$videoId',
                                show_in_footer_sections = '$show_in_footer_sections' 
                                WHERE id = '$id' ";
        if(!$db->query($sql)){
            die(mysqli_error($db));
        }
    $msg = "Updated Successfully";
    echo "<script language='javascript'>window.location='pages.php?msg=$msg'</script>";
}
?>
       <form action="edit_page.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data" name="addproduct" runat="server">
       <div id="midle_repeat">
         <div id="tit">
           <h3>Add Post</h3>
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
                           <input type="text" id="slug" class="productfieldlarge" style="width:300px;" value="<?php if(!empty($slug)){ echo htmlspecialchars($slug); }; ?>" readonly="readonly" />
                       </div>
                       <div class="clear">
                       </div>
                       
                       <div class="fieldrepeat">
                           <p>Description : </p>
                           <div style="width:80%; float:left">
                               <textarea id="content" name="content" cols="30" rows="15" required>
                                   <?php if(!empty($content)){ echo $content; }; ?>
                               </textarea>
                           </div>
                       </div>
                       <div class="fieldrepeat">
                           <p>Thumbnail : </p>
                           <input type="file" id="image" name="image" class="productfieldlarge" style="width:300px;" />
                           <input type="hidden" name="image1" value="<?php echo $image; ?>" />
                       </div>
                       <div class="fieldrepeat">
                           <p>Alt: </p>
                           <input type="text" id="alt" name="alt" class="productfieldlarge" style="width:300px;" value="<?php if(!empty($alt)){ echo $alt; }; ?>" />
                       </div>
                       <div class="clear">
                       </div>
                       <div class="fieldrepeat">
                         <p>Status : </p>
                         <input type="text" id="status" class="productfieldlarge" style="width:150px;"
                             value="<?php echo ($status == 1) ? 'Enable' : 'Disable'; ?>" readonly="readonly" />
                       </div>
                       <div class="fieldrepeat">
                         <p>Video Id : </p>
                       <input type="text" id="videoId" name="videoId" class="productfieldlarge" style="width:300px;" value="<?php if(!empty($videoId)){ echo $videoId; }; ?>"/>
                       </div>
                         <div class="clear">
                         </div>
                        <div class="fieldrepeat">
                           <p>Show in Footer Sections  : </p>
                           <label><input type="checkbox" name="show_in_footer_sections[]" value="1" <?php echo (in_array(1, $show_in_footer_sections)) ? 'checked' : ''; ?> /> Leftmost Section</label>
                            <label><input type="checkbox" name="show_in_footer_sections[]" value="2" <?php echo (in_array(2, $show_in_footer_sections)) ? 'checked' : ''; ?> /> 2nd Section</label>
                            <label><input type="checkbox" name="show_in_footer_sections[]" value="3" <?php echo (in_array(3, $show_in_footer_sections)) ? 'checked' : ''; ?> /> 3rd Section</label>
                            <label><input type="checkbox" name="show_in_footer_sections[]" value="4" <?php echo (in_array(4, $show_in_footer_sections)) ? 'checked' : ''; ?> /> Rightmost Section</label>
                        </div>
                        <div class="clear"> </div>
                       <div class="fieldrepeat">
                           <p>Schema Markup : </p>
                           <textarea class="seo_text" name="schema_markup" id="schema_markup" rows="10" style="width: 645px; padding:10px;" required><?php if(isset($schema_markup)){ echo $schema_markup; } ?></textarea>
                       </div>
                        <div class="clear"> </div>
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
                           <label><input type="radio" name="robots" value="Index,Nofollow" <?php echo ($robots == 'Index,Nofollow')?'checked':''; ?> />Index</label>
                           <label><input type="radio" name="robots" value="Noindex,Follow" <?php echo ($robots == 'Noindex,Follow')?'checked':''; ?> />Follow</label>
                           <label><input type="radio" name="robots" value="Noindex,Nofollow" <?php echo ($robots == 'Noindex,Nofollow')?'checked':''; ?> />Disallow</label>
                           <label><input type="radio" name="robots" value="Index,Follow" <?php echo ($robots == 'Index,Follow')?'checked':''; ?> />Allow</label>
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
    CKEDITOR.replace( 'content',
    {
        versionCheck: false,
        extraPlugins: 'uploadimage,image2',
        removePlugins: 'image',
        height: 400,
        
        // must point to your PHP handler
        filebrowserUploadUrl: 'upload.php',
        filebrowserUploadMethod: 'form'
    });
	</script>
</body>
</html>
