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



<title>Udate Course</title>



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

if(isset($_POST['update'])){

	if(isset($_FILES['thumbnail']['name']) && !empty($_FILES['thumbnail']['name'])){
 
   	$catname=$_POST['catname'];
	
	if (!isset ($_POST['slug']) || $_POST['slug'] == '')
	{
		$slug = str_replace(' ', '-', strtolower($catname));
	}
	else
	{
		$slug = str_replace(' ', '-', strtolower($_POST['slug']));
	}
	
	$order = $_POST['order'];
	
	$status  =  $_POST['status'];
    
    $alternate = $_POST['alternate'];
    
    $thumbnail = $_FILES['thumbnail']['name'];
    $course_content = $_POST['content'];
	
	$msg="Slug Already Exists";
	$dir_name = '../images';
    $destination = $dir_name.'/'.$thumbnail;
    $tmp_name = $_FILES['thumbnail']['tmp_name'];

          $path_to_image_directory = '../images/';
     $path_to_thumbs_directory = '../images/thumbs/';
      
      function createThumbnail($filename) {
 $path_to_image_directory = '../images/';
     $path_to_thumbs_directory = '../images/thumbs/';
if(preg_match('/[.](jpg)$/', $filename)) {
    $im = imagecreatefromjpeg($path_to_image_directory . $filename);
} else if (preg_match('/[.](gif)$/', $filename)) {
    $im = imagecreatefromgif($path_to_image_directory . $filename);
} else if (preg_match('/[.](png)$/', $filename)) {
    $im = imagecreatefrompng($path_to_image_directory . $filename);
}
 
$ox = imagesx($im);
$oy = imagesy($im);
 $final_width_of_image=264;
$nx = $final_width_of_image;
$ny = 100;
 
$nm = imagecreatetruecolor($nx, $ny);
 
imagecopyresampled($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);
 
if(!file_exists($path_to_thumbs_directory)) {
  if(!mkdir($path_to_thumbs_directory)) {
       die("There was a problem. Please try again!");
  } 
   }

imagejpeg($nm, $path_to_thumbs_directory . $filename);
}
      if(isset($_FILES['thumbnail']) && !empty($_FILES['thumbnail'])) {

if(preg_match('/[.](jpg)|(gif)|(png)$/', $_FILES['thumbnail']['name'])) {

    $filename = $_FILES['thumbnail']['name'];
    $source = $_FILES['thumbnail']['tmp_name'];   
    $target = $path_to_image_directory . $filename;
     
    if(move_uploaded_file($source, $target)){
    
	$qry = $db->query("update tbl_courses set cname='$catname',slug='$slug',ordernumber=$order,excerpt='$course_excerpt',content='$course_content',thumbnail='$thumbnail',IStatus=$status, alternate = '$alternate' where cid=$newsid") or die("<script language='javascript'>window.location='cours.php?msg=$msg'</script>").mysqli_error($db);
    

	$msg="Updated Sucssefully";
    }
    createThumbnail($filename);     
}
}
	echo "<script language='javascript'>window.location='cours.php?msg=$msg'</script>";
       
	} else {

	$catname=$_POST['catname'];
	
	if (!isset ($_POST['slug']) || $_POST['slug'] == '')
	{
		$slug = str_replace(' ', '-', strtolower($catname));
	}
	else
	{
		$slug = str_replace(' ', '-', strtolower($_POST['slug']));
	}
	
	$order = $_POST['order'];
	$course_excerpt = $_POST['excerpt'];
	$course_content = $_POST['content'];
	$status  =  $_POST['status'];
    $alternate = $_POST['alternate'];
	//$thumbnail = $_FILES['thumbnail']['name'];
    //$thumbnail ='not set img';
	$msg="Slug Already Exists";
	
	$qry = $db->query("update tbl_courses set cname='$catname',slug='$slug',ordernumber=$order,excerpt='$course_excerpt',content='$course_content',IStatus=$status, alternate = '$alternate' where cid=$newsid") or die("<script language='javascript'>window.location='cours.php?msg=$msg'</script>").mysqli_error($db);;

	$msg="Updated Sucssefully";

	echo "<script language='javascript'>window.location='cours.php?msg=$msg'</script>";

	}

	}

?>



       

       <form action="" method="post" enctype="multipart/form-data" name="addproduct" runat="server">

       

       <div id="midle_repeat">

         

         <div id="tit">

         

           <h3>Course</h3>

         

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

            <?php 
			
			$qry=$db->query("select * from  tbl_courses where cid=$newsid");
			
			$newsreslt=->fetch_array($qry);
			
			?>
                     

                     
                       <div class="fieldrepeat">

                       

                         <p>Course Name: </p>

                        <input type="text" id="catname" name="catname" value="<?php echo $newsreslt['cname'];?>" class="productfieldlarge" style="width:300px;" />

                       

         </div>

                       

                                 <div class="clear">

                                 </div>

 
                       <div class="fieldrepeat">

                       

                         <p>Slug: </p>

                        <input type="text" id="slug" name="slug" value="<?php echo $newsreslt['slug'];?>" class="productfieldlarge" style="width:300px;" />

                       

         </div>

                       

                                 <div class="clear">

                                 </div>

 
                       <div class="fieldrepeat">

                       

                         <p>Order Number : </p>

                        <input type="text" id="order" name="order" value="<?php echo $newsreslt['ordernumber'];?>" class="productfieldlarge" style="width:300px;" />

                       

         </div>

                       
<div class="clear">
</div>
<div class="fieldrepeat">
<p>Excerpt : </p>
<div style="width:80%; float:left">
<textarea id="content" name="excerpt" class="ckeditor" cols="30" rows="15">
<?php if(!empty($newsreslt['excerpt'])){ echo $newsreslt['excerpt']; }?>
</textarea>
</div>
</div>
<div class="clear">
</div>
<div class="fieldrepeat">
<p>Course Content : </p>
<div style="width:80%; float:left">
<textarea id="description" name="content" class="ckeditor" cols="30" rows="15">
<?php if(!empty($newsreslt['content'])){ echo $newsreslt['content']; }?>
</textarea>
</div>
</div>
<div class="clear">
</div>
                       
<div class="fieldrepeat">

<p>Thumbnail : </p>
<div style="float:left">
<input type="file" name="thumbnail" />
</div>

</div>

<div class="clear">
</div>
                       
<div class="fieldrepeat">

<p>Alternative Text : </p>
<div style="float:left">
<input type="text" name="alternate" value="<?php if(!empty($newsreslt['alternate'])){ echo $newsreslt['alternate']; }?>"/>
</div>

</div>

<div class="clear">
</div>
                   

                       

                       <div class="fieldrepeat">

                       

                         <p>Status : </p>

                         

                         <select name="status" id="status" class="status" style="width:150px;">

                   

					   <?php

					   

					           if ($newsreslt['IStatus'] == 1)

							   {
								   echo "<option value=".$newsreslt['IStatus']." 'selected'>Enable</option>";
								   echo "<option value='0'>Disable</option>";

								

							   }

							   else

							   {
								   
   								echo "<option value=".$newsreslt['IStatus']." 'selected'>Disable</option>";
								   echo "<option value='1'>Enable</option>";

								

							   }

					   

					   ?>

                     

                 </select>

               

                       

                       </div>

                       

                       

                                 <div class="clear">

                                 </div>

                                

                                 

                    <div id="buttons" style=" width:713px; margin:0 auto; margin-top:10px;"> 

                    <input type="submit" name="update" id="update" value="update" />

                      <input type="button" value="Cancel" onClick="window.location.href='cours.php'" />

            

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
</body>

</html>









