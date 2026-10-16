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



	$title  =  $_POST['title'];

	$excerpt = $_POST['excerpt'];

	$content = $_POST['content'];

    $slug = $_POST['slug'];

    $status  =  ($_SESSION['adminType']== 2) ? $_POST['status'] : 1;

    $meta_title =  $_POST['meta_title'];

    $meta_keywords = $_POST['meta_keywords'];

    $meta_description = $_POST['meta_description'];

    $category = empty($_POST['category']) ? 0 : $_POST['category'];

    $publish_date = $_POST['publish_date'];

    $robots = $_POST['robots'];

    $alt = $_POST['alt'];

    $video = $_POST['video'];

    $show_in_free_courses  =  ($_POST['show_in_free_courses'] == 1) ? 1 : 0;







    $tmpName = $_FILES['image']['tmp_name'];

    $image = time().$_FILES['image']['name'];



    if(move_uploaded_file($tmpName, '../images/blog/'.$image)){



        $sql = "INSERT INTO blog (title, slug, excerpt, content, status, category, publish_date, meta_title, meta_description, meta_keywords, robots, image, alt, video, show_in_free_courses) VALUES

           ('$title', '$slug', '$excerpt', '$content', '$status', '$category', '$publish_date', '$meta_title', '$meta_description', '$meta_keywords', '$robots', '$image', '$alt', '$video', '$show_in_free_courses')";



        if(!$db->query($sql)){

            die(mysqli_error($db));

        }

        

    }



    $msg="Added Successfully";

    echo "<script language='javascript'>window.location='blog.php?msg=$msg'</script>";



}



    $category_sql = "SELECT id, name FROM blog_categories ORDER BY `order` ASC";

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







             <div id="uperdetail" style="border: none">

             <form id="imageform" method="post" enctype="multipart/form-data" action='ajaximage.php'>

  Upload your image <input type="file" name="photoimg" id="photoimg" />

</form>



<div id='preview' style="width:200px; padding: 5px; margin-top: 10px;"></div>





<form action="" method="post" enctype="multipart/form-data" name="addproduct" runat="server">

                   <div id="general">



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

                           <p>Category : </p>

                           <select name="category" id="category" class="category" style="width:150px;">

                               <option value="">-- Select Category --</option>

                               <?php while($blog_row = $result->fetch_array()){ ?>

                                   <option value="<?php echo $blog_row['id']; ?>"><?php echo $blog_row['name']; ?></option>

                               <?php } ?>

                           </select>

                       </div>

                       <div class="clear">

                       </div>



                       <div class="fieldrepeat">

                           <p>Description :<br />(Type @@@ to insert AdSense) </p>

                           <div style="width:80%; float:left">

                               <textarea id="content" name="content" class="ckeditor" cols="30" rows="15" required>

                                   <?php if(!empty($content)){ echo $content; }; ?>

                               </textarea>

                           </div>

                       </div>



                       <div class="fieldrepeat">

                           <p> Excerpt : </p>

                           <div style="width:80%; float:left">

                               <textarea id="excerpt" name="excerpt" class="ckeditor" cols="30" rows="15" required>

                                   <?php if(!empty($excerpt)){ echo $excerpt; }; ?>

                               </textarea>

                           </div>

                       </div>

                       <div class="clear">

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

                           <p>Publish Date : </p>

                           <input type="date" id="publish_date" name="publish_date" class="productfieldlarge" style="width:300px;" value="<?php if(!empty($publish_date)){ echo $publish_date; }; ?>" required />

                       </div>

                       <div class="clear">

                       </div>



                       <?php if ($_SESSION['adminType'] == 2) { ?>

                       <div class="fieldrepeat">

                         <p>Status : </p>

                       <select name="status" id="status" class="status" style="width:150px;" required>

                           <option value="1">Enable</option>

                           <option value="0">Disable</option>

                        </select>

                       </div>

                         <div class="clear">

                         </div>

                        <?php } ?>



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

                       

                       <div class="fieldrepeat">

                           <p>Video : </p>

                           <input type="text" id="video" name="video" class="productfieldlarge" style="width:300px;" value="<?php if(!empty($video)){ echo $video; }; ?>" autocomplete="off" />

                       </div>

                       <div class="clear">

                       </div>

                       <div class="fieldrepeat">

                            <p style="margin-top:-2px;">Show In Free Courses : </p>

                            <input type="checkbox" name="show_in_free_courses" value="1" />

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







<script type="text/javascript">

           $("#img").on('click', function() {

    $("p").html($(this).attr('src'));

});

</script>



</body>



</html>





















