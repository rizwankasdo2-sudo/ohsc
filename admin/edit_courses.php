<?php
session_start();
ob_start();
if($_SESSION['adminid']== "")
{
	header("location:index.php");
	exit();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Courses Page - CMS</title>

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
				 $id = $_GET['id'];
				 $select = $db->query("SELECT * FROM ourcourses where ourcourseid=$id");
				 $rowofcat = $select->fetch_array();
	   ?>
       
       <form action="update_courses.php" method="post" enctype="multipart/form-data" name="addproduct">
       
       <div id="midle_repeat">
         
         <div id="tit">
         
           <h3>Edit Courses</h3>
         
         </div>
         
         <div id="buttons">
         
           <ul style="float:right;">
           
             <li><input type="submit" value="Save" class="add_cancel_button" /></li>
             <li><a href="ourcourse.php">Cancel</a></li>
           
           </ul>
         
         </div>
       
       </div>
       
       <div id="right_corner">
       </div>
       
       <div id="main_content">
       
         <div id="addproducts">
         
           <div id="uper">
           
             <div id="upertop">
             
               <h4>Courses</h4>
             
             </div>
             
             <div id="uperdetail">
                   
                   <div id="general">
                       
                       <div class="fieldrepeat">
                        
                         <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                       
                       
                       
                       </div>
                       
                                 <div class="clear">
                                 </div>
                       
                       <div class="fieldrepeat">
                       
                         <p>Description : </p>
                         <div style="width:80%; float:left">
                         <textarea id="description" name="description" class="ckeditor" cols="30" rows="15">
                           <?php echo $rowofcat['ourcoursedetail']; ?>
                         </textarea>
                         </div>
                       
                       </div>
                       
     
                       
                                 <div class="clear">
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
