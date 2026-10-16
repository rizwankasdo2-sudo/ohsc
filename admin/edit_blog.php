<?php
session_start();
include("classes/connection.php");
include("includes/related-diploma-helper.php");
include("includes/blog-helper.php");
if($_SESSION['adminid']== "") {
	header("location:index.php");
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Add Post</title>
<link rel="stylesheet" href="../Resources/admin_css/admin-style.css?v=<?php echo time(); ?>" />
<link rel="stylesheet" href="../Resources/admin_css/add-product.css" />
<script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>
<script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
<!--[if lte IE 6]>
<script type="text/javascript" src="../Resources/admin_js/supersleight-min.js"></script>
<![endif]-->
<?php include 'includes/related-diploma-styles.php'; ?>
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
$form_errors = [];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
   $id = $_GET['id'];
   $query = "SELECT * FROM blog WHERE id='$id'";
   $result = $db->query($query);
   if($result->num_rows == 0){
       header('location: blog.php');
       exit();
   }
   $blog = $result->fetch_array();
   $title = $blog['title'];
   $excerpt = $blog['excerpt'];
   $short_content = isset($blog['short_content']) ? $blog['short_content'] : '';
   $minutes_read = isset($blog['minutes_read']) ? (int) $blog['minutes_read'] : 1;
   $popular = isset($blog['popular']) ? (int) $blog['popular'] : 0;
   $featured = isset($blog['featured']) ? (int) $blog['featured'] : 0;
   $content = $blog['content'];
   $image = $blog['image'];
   $image_272_170 = isset($blog['image_272_170']) ? $blog['image_272_170'] : '';
   $image_240_181 = isset($blog['image_240_181']) ? $blog['image_240_181'] : '';
   $image_80_64 = isset($blog['image_80_64']) ? $blog['image_80_64'] : '';
   $image_title = $blog['image_title'];
   $image_description = $blog['image_description'];
   $category = $blog['category'];
   $blog_category = $blog['blog_category'];
   $category_ids = array_filter(array_map('trim', explode(',', $category)));
   $subcat_ids = array_filter(array_map('trim', explode(',', $blog['subcat_ids'])));
   $subcat_ids_value = $blog['subcat_ids'];
   $publish_date = $blog['publish_date'];
   $meta_title = $blog['meta_title'];
   $meta_keywords = $blog['meta_keywords'];
   $meta_description = $blog['meta_description'];
   $robots = $blog['robots'];
   $status = $blog['status'];
   $slug = $blog['slug'];
   $alt = $blog['alt'];
   $video = $blog['video'];
   $show_in_free_courses = $blog['show_in_free_courses'];
   $show_in_all_courses = isset($blog['show_in_all_courses']) ? (int) $blog['show_in_all_courses'] : 0;
   $related_diplomas = buildRelatedDiplomasFromIds($db, isset($blog['related_diploma_ids']) ? $blog['related_diploma_ids'] : '');
}
if (isset($_POST['submit'])) {
	$title  =   $_POST['title'];
	$excerpt =  $_POST['excerpt'];
	$content =  $_POST['content'];
    $meta_title =  $_POST['meta_title'];
    $meta_keywords = $_POST['meta_keywords'];
    $meta_description = $_POST['meta_description'];
    $category = ($_POST['category']) ? implode(', ', $_POST['category']) : '';
    $category_ids = isset($_POST['category']) && is_array($_POST['category']) ? $_POST['category'] : [];
    $blog_category = $_POST['blog_category'];
    $subcat_ids = (isset($_POST['subcat_ids']) && is_array($_POST['subcat_ids'])) ? array_filter($_POST['subcat_ids']) : [];
    $subcat_ids_value = !empty($subcat_ids) ? implode(',', $subcat_ids) : '';
    $publish_date = $_POST['publish_date'];
    $robots = $_POST['robots'];
    $alt = $_POST['alt'];
    $video = $_POST['video'];
    $show_in_free_courses = (!empty($_POST['show_in_free_courses']) && $_POST['show_in_free_courses'] == 1) ? 1 : 0;
    $show_in_all_courses = (!empty($_POST['show_in_all_courses']) && $_POST['show_in_all_courses'] == 1) ? 1 : 0;
    $related_diploma_ids = formatRelatedDiplomaIds(isset($_POST['related_diploma_ids']) ? $_POST['related_diploma_ids'] : []);
    $related_diplomas = buildRelatedDiplomasFromIds($db, $related_diploma_ids);
    $extra = collectBlogExtraFormData($db);
    $short_content = isset($_POST['short_content']) ? $_POST['short_content'] : $short_content;
    $minutes_read = isset($_POST['minutes_read']) ? (int) $_POST['minutes_read'] : $minutes_read;
    $popular = isset($_POST['popular']) ? (int) $_POST['popular'] : $popular;
    $featured = isset($_POST['featured']) ? (int) $_POST['featured'] : $featured;
    $form_errors = array_merge($form_errors, validateBlogPopularFeaturedLimits($db, $extra['popular'], $extra['featured'], $id));
    $image_title = isset($_POST['image_title']) ? $_POST['image_title'] : $image_title;
    $image_description = isset($_POST['image_description']) ? $_POST['image_description'] : $image_description;
    $existingImages = [
        'image' => $blog['image'],
        'image_272_170' => isset($blog['image_272_170']) ? $blog['image_272_170'] : '',
        'image_240_181' => isset($blog['image_240_181']) ? $blog['image_240_181'] : '',
        'image_80_64' => isset($blog['image_80_64']) ? $blog['image_80_64'] : '',
    ];
    $form_errors = array_merge($form_errors, validateBlogImageUploads($id, $existingImages, false));
    $image_title_sql = mysqli_real_escape_string($db, filter_var(trim($image_title), FILTER_SANITIZE_STRING));
    $image_description_sql = mysqli_real_escape_string($db, filter_var(trim($image_description), FILTER_SANITIZE_STRING));
    if (empty($form_errors)) {
        $uploadedImages = saveBlogImageUploads(getBlogUploadDir(), $existingImages, $db, $id);
        if (!blogImageUploadsComplete($uploadedImages)) {
            $form_errors[] = 'All 4 blog images are required.';
        } else {
        $sql = "UPDATE blog SET title = '$title',
                                excerpt = '$excerpt',
                                short_content = '{$extra['short_content']}',
                                minutes_read = {$extra['minutes_read']},
                                popular = {$extra['popular']},
                                featured = {$extra['featured']},
                                content = '$content',
                                category = '$category',
                                blog_category = $blog_category,
                                subcat_ids = '$subcat_ids_value',
                                publish_date = '$publish_date',
                                meta_title = '$meta_title',
                                meta_description = '$meta_description',
                                meta_keywords = '$meta_keywords',
                                robots = '$robots',
                                image = '{$uploadedImages['image']}',
                                image_272_170 = '{$uploadedImages['image_272_170']}',
                                image_240_181 = '{$uploadedImages['image_240_181']}',
                                image_80_64 = '{$uploadedImages['image_80_64']}',
                                image_title = '$image_title_sql',
                                image_description = '$image_description_sql',
                                alt = '$alt',
                                video = '$video',
                                show_in_free_courses = '$show_in_free_courses',
                                show_in_all_courses = '$show_in_all_courses',
                                related_diploma_ids = '$related_diploma_ids'
                                WHERE id = '$id' ";
        if(!$db->query($sql)){
            die(mysqli_error($db));
        }
        $msg = "Updated Successfully";
        echo "<script language='javascript'>window.location='blog.php?msg=$msg'</script>";
        }
    }
}
$category_sql = "SELECT cid, cname FROM tbl_courses ORDER BY ordernumber";
$category_result = $db->query($category_sql);
if(!$category_result){
    die(mysqli_error($db));
}
?>
      
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
            <?php renderBlogFormErrors($form_errors); ?>
             
  <div id="uperdetail" style="border: none">
                   <div id="general">
                    <form action="edit_blog.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data" name="addproduct" runat="server">
                       <div class="fieldrepeat">
                        <p>Title </p>
                           <input type="text" id="title" name="title" class="productfieldlarge" style="width:300px;" value="<?php if(!empty($title)){ echo $title; }; ?>" required/>
                        </div>
                         <div class="clear">
                         </div>
                       <div class="fieldrepeat">
                           <p>Slug : </p>
                           <input type="text" id="slug" class="productfieldlarge" style="width:300px;" value="<?php if(!empty($slug)){ echo htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'); }; ?>" readonly="readonly" />
                       </div>
                       <div class="clear">
                       </div>
                       <div class="fieldrepeat">
                           <p>Primary Category : </p>
                           <select name="blog_category" id="blog_category" class="category" style="width:200px;" required>
                               <option value="">-- Select Primary Category --</option>
                               <?php
                               $blog_category_result = $db->query("SELECT id, name FROM blog_categories ORDER BY name");
                               while($blog_category_row = $blog_category_result->fetch_array()){ ?>
                                   <option value="<?php echo $blog_category_row['id']; ?>" <?php if($blog_category == $blog_category_row['id']){ echo 'selected'; } ?>><?php echo $blog_category_row['name']; ?></option>
                               <?php } ?>
                           </select>
                       </div>
                       <div class="clear">
                       </div>
                       <div class="fieldrepeat">
                            <p>Related Course Subcategories : </p>
                            <input type="radio" name="show_subcats" onclick="document.getElementById('pcatdiv').style.display = 'flex';" <?php echo empty($subcat_ids) ? '' : 'checked'; ?> /> Show Subcategories &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="show_subcats" onclick="document.getElementById('pcatdiv').style.display = 'none';" <?php echo empty($subcat_ids) ? 'checked' : ''; ?> /> Hide Subcategories
                        </div>
                        <div class="clear">
                        </div>
                        <div class="fieldrepeat" style="display:<?php echo empty($subcat_ids) ? 'none' : 'flex'; ?>;" id="pcatdiv">
                            <p> &nbsp;</p>
                            <div style="font-family:Tahoma, arial;font-size:12px;">
                                <?php
                                $i = 0;
                                $parent_ids_result = $db->query("SELECT cid, cname FROM tbl_courses WHERE parent_ids <> '' ORDER BY cname ASC");
                                while ($parent_ids_row = $parent_ids_result->fetch_array()) {
                                    $i++;
                                ?>
                                <input type="checkbox" name="subcat_ids[]" value="<?php echo $parent_ids_row['cid']; ?>" <?php echo in_array((string) $parent_ids_row['cid'], array_map('strval', $subcat_ids), true) ? 'checked' : ''; ?> /> <?php echo $parent_ids_row['cname']; ?>
                                <?php
                                    if ($i < 3) {
                                        echo '&nbsp&nbsp&nbsp&nbsp&nbsp';
                                    } else {
                                        echo '<br /><br />';
                                        $i = 0;
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="clear">
                        </div>
                        <?php include 'includes/related-diploma-field.php'; ?>
                       <div class="fieldrepeat">
                           <p>Secondary Tag : </p>
                           <p style="font-family:Tahoma, arial;font-size:12px;width:80%;">
                                <?php
                                $i = 0;
                                while ($blog_row = $category_result->fetch_array()) {
                                    $i++;
                                ?>
                                <input type="checkbox" name="category[]" value="<?php echo $blog_row['cid']; ?>" <?php echo in_array((string) $blog_row['cid'], array_map('strval', $category_ids), true) ? 'checked' : ''; ?> /> <?php echo $blog_row['cname']; ?>
                                <?php
                                    if ($i < 2) {
                                        echo '&nbsp&nbsp&nbsp&nbsp&nbsp';
                                    } else {
                                        echo '<br /><br />';
                                        $i = 0;
                                    }
                                }
                                ?>
                            </p>
                       </div>
                       <div class="clear">
                       </div>
                       <div class="fieldrepeat">
                           <p>Description :<br />(Type @@@ to insert AdSense) </p>
                           <div style="width:80%; float:left">
                               <textarea id="content" name="content" cols="30" rows="15" required>
                                   <?php if(!empty($content)){ echo $content; }; ?>
                               </textarea>
                           </div>
                       </div>
                       <div class="fieldrepeat">
                           <p> Excerpt : </p>
                           <div style="width:80%; float:left">
                               <textarea id="excerpt" name="excerpt" cols="30" rows="15" required>
                                   <?php if(!empty($excerpt)){ echo $excerpt; }; ?>
                               </textarea>
                           </div>
                       </div>
                       <div class="clear">
                       </div>
                       <?php
                       renderBlogExtraFields([
                           'short_content' => $short_content,
                           'minutes_read' => $minutes_read,
                           'popular' => $popular,
                           'featured' => $featured,
                           'image' => $image,
                           'image_272_170' => $image_272_170,
                           'image_240_181' => $image_240_181,
                           'image_80_64' => $image_80_64,
                       ], true);
                       ?>
                       <div class="fieldrepeat">
                           <p>Alt: </p>
                           <input type="text" id="alt" name="alt" class="productfieldlarge" style="width:300px;" value="<?php if(!empty($alt)){ echo $alt; }; ?>" required/>
                       </div>
                       <div class="clear">
                       </div>
                       <div class="fieldrepeat">
                            <p>Image Title : </p>
                            <textarea name="image_title" id="image_title" cols="60"
                                rows="5"><?php if(!empty($image_title)){ echo $image_title; }; ?></textarea>
                        </div>
                        <div class="clear">
                        </div>
                        <div class="fieldrepeat">
                            <p>Image Description : </p>
                            <textarea name="image_description" id="image_description" cols="60"
                                rows="5"><?php if(!empty($image_description)){ echo $image_description; }; ?></textarea>
                        </div>
                        <div class="clear">
                        </div>
                       <div class="fieldrepeat">
                           <p>Publish Date : </p>
                           <input type="date" id="publish_date" name="publish_date" class="productfieldlarge" style="width:300px;" value="<?php if(!empty($publish_date)){ echo $publish_date; }; ?>" required />
                       </div>
                       <?php if ($_SESSION['adminType'] == 2) { ?>
                       <div class="fieldrepeat">
                         <p>Status : </p>
                         <input type="text" id="status" class="productfieldlarge" style="width:150px;"
                             value="<?php echo ($status == 1) ? 'Enable' : 'Disable'; ?>" readonly="readonly" />
                       </div>
                         <div class="clear">
                         </div>
                        <?php } ?>
                       <div class="fieldrepeat">
                           <p>Video : </p>
                           <input type="text" id="video" name="video" class="productfieldlarge" style="width:300px;" value="<?php if(!empty($video)){ echo $video; }; ?>"  autocomplete="off" />
                       </div>
                       <div class="clear">
                       </div>
                       <div class="fieldrepeat">
                            <p style="margin-top:-2px;">Show In Free Courses : </p>
                            <input type="checkbox" name="show_in_free_courses" value="1" <?php echo ($show_in_free_courses == 1) ? 'checked' : ''; ?> />
                        </div>
                        <div class="clear">
                        </div>
                       <div class="fieldrepeat">
                            <p style="margin-top:-2px;">Show on All Courses Page : </p>
                            <input type="checkbox" name="show_in_all_courses" value="1" <?php echo ($show_in_all_courses == 1) ? 'checked' : ''; ?> />
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
                           <label><input type="radio" name="robots" value="Index,Nofollow" <?php echo ($robots === 'Index,Nofollow') ? 'checked' : ''; ?> />Index</label>
                           <label><input type="radio" name="robots" value="Noindex,Follow" <?php echo ($robots === 'Noindex,Follow') ? 'checked' : ''; ?> />Follow</label>
                           <label><input type="radio" name="robots" value="Noindex,Nofollow" <?php echo ($robots === 'Noindex,Nofollow') ? 'checked' : ''; ?> />Disallow</label>
                           <label><input type="radio" name="robots" value="Index,Follow" <?php echo ($robots === 'Index,Follow') ? 'checked' : ''; ?> />Allow</label>
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
    <script>
    CKEDITOR.replace( 'excerpt',
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
    <?php initBlogShortContentEditor(); ?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
  <script type="text/javascript" src="js/scripts/jquery.min.js"></script>
<?php include 'includes/related-diploma-script.php'; ?>
</body>
</html>
