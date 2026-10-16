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

<title>CMS</title>

<link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />

<script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>

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
       
       <div id="midle_repeat">
       
         <div id="homeimage">
         </div>
         
         <div id="title" style="float:left">
         
           <h3>Dashboard</h3>
         
         </div>
       
       </div>
       
       <div id="right_corner">
       </div>
       
       <div id="main_content">
         
         <ul style="margin-left:30px; list-style-type:none; margin-top:15px;">
           <li style="float:left; margin-right:25px;">
           <a href="home_page.php"><img src="../Resources/cms_images/home_tab.png" /></a>
          </li>
         <li style="float:left; margin-right:25px;">
           <a href="contactus.php"><img src="../Resources/cms_images/contactus.jpg" /></a>
          </li>
        
          
         <li style="float:left; margin-right:25px;">

     <a href="footer.php"><img src="../Resources/cms_images/footerlinks.jpg" /></a>

          </li>
           <li style="float:left; margin-right:25px;">
           <a href="about_page.php"><img src="../Resources/cms_images/aboutus.jpg" /></a>
          </li>
          
         
          
<li style="float:left; margin-right:25px;"><a href="accerdetion.php"><img src="../Resources/cms_images/accreditations.jpg" /></a></li>
<li style="float:left; margin-right:25px;"><a href="pay.php"><img src="../Resources/cms_images/how-to-pay.jpg" /></a></li>
<li style="float:left; margin-right:25px;"><a href="dlearning.php"><img src="../Resources/cms_images/distance-learning.jpg" /></a></li>
<li style="float:left; margin-right:25px;"><a href="seo.php"><img src="../Resources/cms_images/banners(161x200).jpg" /></a></li>
<li style="float:left; margin-right:25px;"><a href="blog.php"><img src="../Resources/cms_images/blog-icon.png" /></a></li>
<li style="float:left; margin-right:25px;"><a href="pages.php"><img src="../Resources/cms_images/pages-icon.png" /></a></li>

     </ul>
         
         <div class="clear">&nbsp;
         </div>
       
       </div>
     
     </div>
     
     <?php include ("includes/admin-footer.php"); ?>
   
   </div>
 
 </div>

</body>
</html>