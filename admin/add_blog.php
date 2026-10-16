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
    include("classes/connection.php");
    include("includes/related-diploma-helper.php");
    include("includes/blog-helper.php");
    include("includes/admin-header.php");
    include("includes/admin-menu.php");
?>
   <div id="outer_contanier">
     <div id="board">
       <div id="left_corner">
       </div>
<?php
$form_errors = [];
$title = $slug = $excerpt = $content = $meta_title = $meta_keywords = $meta_description = '';
$blog_category = '';
$subcat_ids = [];
$selected_categories = [];
$related_diplomas = [];
$publish_date = $alt = $video = '';
$robots = 'Index,Follow';
$status = 1;
$show_in_free_courses = 0;
$show_in_all_courses = 0;
$short_content = '';
$minutes_read = 1;
$popular = 0;
$featured = 0;
$image_title = $image_description = '';

if (isset($_POST['submit'])) {
    $form_errors = [];
	$title  =  $_POST['title'];
	$excerpt = $_POST['excerpt'];
	$content = $_POST['content'];
    $slug = normalizeBlogSlug($_POST['slug']);
    $status  =  ($_SESSION['adminType']== 2) ? $_POST['status'] : 1;
    $meta_title =  $_POST['meta_title'];
    $meta_keywords = $_POST['meta_keywords'];
    $meta_description = $_POST['meta_description'];
    $selected_categories = (isset($_POST['category']) && is_array($_POST['category'])) ? array_map('strval', $_POST['category']) : [];
    $category = !empty($selected_categories) ? implode(', ', $selected_categories) : '';
    $blog_category = $_POST['blog_category'];
    $subcat_ids = (isset($_POST['subcat_ids']) && is_array($_POST['subcat_ids'])) ? array_filter($_POST['subcat_ids']) : [];
    $subcat_ids_value = !empty($subcat_ids) ? implode(',', $subcat_ids) : '';
    $publish_date = $_POST['publish_date'];
    $robots = $_POST['robots'];
    $alt = $_POST['alt'];
    $video = $_POST['video'];
    $show_in_free_courses  =  (!empty($_POST['show_in_free_courses']) && $_POST['show_in_free_courses'] == 1) ? 1 : 0;
    $show_in_all_courses = (!empty($_POST['show_in_all_courses']) && $_POST['show_in_all_courses'] == 1) ? 1 : 0;
    $related_diploma_ids = formatRelatedDiplomaIds(isset($_POST['related_diploma_ids']) ? $_POST['related_diploma_ids'] : []);
    $related_diplomas = buildRelatedDiplomasFromIds($db, $related_diploma_ids);
    $extra = collectBlogExtraFormData($db);
    $short_content = isset($_POST['short_content']) ? $_POST['short_content'] : '';
    $minutes_read = isset($_POST['minutes_read']) ? (int) $_POST['minutes_read'] : 1;
    $popular = (!empty($_POST['popular']) && $_POST['popular'] == '1') ? 1 : 0;
    $featured = (!empty($_POST['featured']) && $_POST['featured'] == '1') ? 1 : 0;
    $slugValidation = validateNewBlogSlug($db, $slug);
    if (!$slugValidation['valid']) {
        $form_errors[] = $slugValidation['message'];
    }
    $form_errors = array_merge($form_errors, validateBlogPopularFeaturedLimits($db, $extra['popular'], $extra['featured'], 0));
    $form_errors = array_merge($form_errors, validateBlogImageUploads(0, []));
    $image_title = mysqli_real_escape_string($db, filter_var(trim($_POST['image_title']), FILTER_SANITIZE_STRING));
    $image_description = mysqli_real_escape_string($db, filter_var(trim($_POST['image_description']), FILTER_SANITIZE_STRING));
    if (empty($form_errors)) {
        $uploadedImages = saveBlogImageUploads(getBlogUploadDir());
        if (!blogImageUploadsComplete($uploadedImages)) {
            $form_errors[] = 'All 4 blog images are required.';
        } else {
            $sql = "INSERT INTO blog (title, slug, excerpt, short_content, minutes_read, popular, featured, content, status, category, blog_category, subcat_ids, publish_date, meta_title, meta_description, meta_keywords, robots, image, image_272_170, image_240_181, image_80_64, image_title, image_description, alt, video, show_in_free_courses, show_in_all_courses, related_diploma_ids) VALUES
            ('$title', '$slug', '$excerpt', '{$extra['short_content']}', {$extra['minutes_read']}, {$extra['popular']}, {$extra['featured']}, '$content', '$status', '$category', $blog_category, '$subcat_ids_value', '$publish_date', '$meta_title', '$meta_description', '$meta_keywords', '$robots', '{$uploadedImages['image']}', '{$uploadedImages['image_272_170']}', '{$uploadedImages['image_240_181']}', '{$uploadedImages['image_80_64']}', '$image_title', '$image_description', '$alt', '$video', '$show_in_free_courses', '$show_in_all_courses', '$related_diploma_ids')";
            if(!$db->query($sql)){
                die(mysqli_error($db));
            }

            $msg="Added Successfully.";
            echo "<script language='javascript'>window.location='blog.php?msg=$msg'</script>";
        }
    }
}
    $category_sql = "SELECT cid, cname FROM tbl_courses ORDER BY ordernumber";
    $result = $db->query($category_sql);
    if(!$result){
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
             <form action="" method="post" enctype="multipart/form-data" name="addproduct" runat="server">
                   <div id="general">
                       <div class="fieldrepeat">
                        <p>Title </p>
                           <input type="text" id="title" name="title" class="productfieldlarge" style="width:300px;" value="<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>" required/>
                        </div>
                         <div class="clear">
                         </div>
                       <div class="fieldrepeat">
                           <p>Slug : </p>
                           <input type="text" id="slug" name="slug" class="productfieldlarge" style="width:300px;" value="<?php echo htmlspecialchars($slug, ENT_QUOTES, 'UTF-8'); ?>" required/>
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
                                   <option value="<?php echo $blog_category_row['id']; ?>" <?php echo ((string) $blog_category === (string) $blog_category_row['id']) ? 'selected' : ''; ?>><?php echo $blog_category_row['name']; ?></option>
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
                        <?php
                        $related_diplomas = [];
                        include 'includes/related-diploma-field.php';
                        ?>
                       <div class="fieldrepeat">
                           <p>Secondary Tag : </p>
                           <p style="font-family:Tahoma, arial;font-size:12px;width:80%;">
                                <?php
                                $i = 0;
                                while ($blog_row = $result->fetch_array()) {
                                    $i++;
                                ?>
                                <input type="checkbox" name="category[]" value="<?php echo $blog_row['cid']; ?>" <?php echo in_array((string) $blog_row['cid'], $selected_categories, true) ? 'checked' : ''; ?> /> <?php echo $blog_row['cname']; ?>
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
                           'short_content' => isset($short_content) ? $short_content : '',
                           'minutes_read' => isset($minutes_read) ? $minutes_read : 1,
                           'popular' => isset($popular) ? $popular : 0,
                           'featured' => isset($featured) ? $featured : 0,
                       ]);
                       ?>
                       <div class="fieldrepeat">
                           <p>Image Alt : </p>
                           <input type="text" id="alt" name="alt" class="productfieldlarge" style="width:300px;" value="<?php echo htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'); ?>" required/>
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
                           <input type="date" id="publish_date" name="publish_date" class="productfieldlarge" style="width:300px;" value="<?php echo htmlspecialchars($publish_date, ENT_QUOTES, 'UTF-8'); ?>" required />
                       </div>
                       <div class="clear">
                       </div>
                       <?php if ($_SESSION['adminType'] == 2) { ?>
                       <div class="fieldrepeat">
                         <p>Status : </p>
                       <select name="status" id="status" class="status" style="width:150px;" required>
                           <option value="1" <?php echo ((string) $status === '1') ? 'selected' : ''; ?>>Enable</option>
                           <option value="0" <?php echo ((string) $status === '0') ? 'selected' : ''; ?>>Disable</option>
                        </select>
                       </div>
                         <div class="clear">
                         </div>
                        <?php } ?>
                       <div class="fieldrepeat">
                           <p>Video : </p>
                           <input type="text" id="video" name="video" class="productfieldlarge" style="width:300px;" value="<?php echo htmlspecialchars($video, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off" />
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
