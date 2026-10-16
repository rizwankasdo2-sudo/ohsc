<?php
session_start();
ob_start();
if($_SESSION['adminid']== "")
{
	header("location:index.php");
	exit();
} 
   include("classes/connection.php");
   $id = $_GET['id'];
if(isset($_POST['banner_submit'])){
    if(isset($_FILES['banerpath']['name']) && !empty($_FILES['banerpath']['name'])){
        $image = time().$_FILES['banerpath']['name'];
        
        $dir_name = '../images/banner';
        $tmp_name = $_FILES['banerpath']['tmp_name'];
        $destination = $dir_name.'/'.$image;
        if(move_uploaded_file($tmp_name, $destination)){
            $error = 0;
        } else {
            $error = 1;
        }
        $banner_heading = $_POST['banner_heading'];
        $banner_link = $_POST['banner_link'];
        $page_link = $_POST['page_link'];
        $banner_status = $_POST['status'];
        $sql = "UPDATE tbl_banner SET IBannerPath='$image', 
                                IStatus='$banner_status',
                                 banner_content='$banner_heading', 
                                 banner_link='$banner_link', 
                                 page_link='$page_link' 
                WHERE IBannerID = '$id'";
        $result = $db->query($sql);
        if(!$result){
            die(mysqli_error($db));
        }
        header('location:banner.php?msg=1');
    } else {
        $banner_heading = $_POST['banner_heading'];
        $banner_link = $_POST['banner_link'];
        $page_link = $_POST['page_link'];
        $banner_status = $_POST['status'];
        
        $sql = "UPDATE tbl_banner SET IStatus = '$banner_status',
                                 banner_content = '$banner_heading', 
                                 banner_link = '$banner_link', 
                                 page_link = '$page_link' 
                WHERE IBannerID = '$id'";
        $result = $db->query($sql);
        if(!$result){
            die(mysqli_error($db));
        }
        header('location:banner.php?msg=1');
    }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Banner Manger</title>

<link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />

<link rel="stylesheet" href="../Resources/admin_css/baner.css" />

<script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>

<script type="text/javascript" src="../Resources/admin_js/myform.js"></script>

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
       
       <div id="midle_repeat">
         
         <div id="bannerheading">
         
           <h3>Banner  Manager</h3>
         
         </div>
         
         <div id="addsave">
         
           <!--<ul>
           
             <li><a href="#">Save</a></li>
             <li><a href="#">Cancel</a></li>
           
           </ul>-->
         
         </div>
       
       </div>
       
       <div id="right_corner">
       </div>
       
       <div id="main_content">
       
         <div id="addbaners">           
           
           <div id="banerhead">
           
             <div id="banertop">
             
               <h4>Update Banner</h4>
             
             </div>
             
 <div id="banerdetail">
             
               <div id="uploadbanner">
			 
			 <?php
             
			          
					  $select = $db->query("select * from tbl_banner where IBannerID=$id");
					  $rowofbanner = $select->fetch_array();
			 ?>
             
             <form action="" method="post" enctype="multipart/form-data">
             
             
             
             <div id="uploadfield">
               <label>Image </label>
                 <input type="file" class="banerimgfield" name="banerpath" id="banerpath"/>
                 
                  <!--<strong style="float:left; font-family:Verdana, Geneva, sans-serif; font-size:12px; margin-top:15px;">(983 x 305)</strong>-->
               
               </div>
               
            <div id="uploadfield">
           <label for="banner_heading">Banner Heading</label>
             <input type="text" class="banerimgfield" name="banner_heading" id="banner_heading" value="<?php echo $rowofbanner['banner_content'];  ?>" style="padding: 5px 5px; height: 35px; border: 1px solid #ddd" />
             
              <!--<strong style="float:left; font-family:Verdana, Geneva, sans-serif; font-size:12px; margin-top:15px;">(983 x 305)</strong>-->
           
           </div>
           
           <div id="uploadfield">
           <label for="banner_link">Banner Link</label>
             <input type="text" class="banerimgfield" name="banner_link" id="banner_link" value="<?php echo $rowofbanner['banner_link'];  ?>" style="padding: 5px 5px; height: 35px; border: 1px solid #ddd" />
             
              <!--<strong style="float:left; font-family:Verdana, Geneva, sans-serif; font-size:12px; margin-top:15px;">(983 x 305)</strong>-->
           
           </div>
           
           
           <div id="uploadfield">
           <label for="page_link">Page Link</label>
             <input type="text" class="banerimgfield" name="page_link" value="<?php echo $rowofbanner['page_link'];  ?>" id="page_link" style="padding: 5px 5px; height: 35px; border: 1px solid #ddd" />
             
              <!--<strong style="float:left; font-family:Verdana, Geneva, sans-serif; font-size:12px; margin-top:15px;">(983 x 305)</strong>-->
           
           </div>
               
            
           <div id="uploadfield" style="margin-top: 15px;">
           
             <label>Status :</label>
             
             <select name="status" id="status" class="stat" style="padding: 5px 5px; height: 35px; border: 1px solid #ddd">
             
                <?php
					   		echo $rowofbanner['IStatus'];
					           if ($rowofbanner['IStatus'] == 1)
							   {
								    echo "<option value=".$rowofbanner['IStatus']." 'selected'>Enable</option>";
								   echo "<option value='0'>Disable</option>";
								  
							   }
							   else
							   {
								   echo "<option value=".$rowofbanner['IStatus']." 'selected'>Disable</option>";
								   echo "<option value='1'>Enable</option>";
								   
							   }
					   
					   ?>
             
             </select>
           
           </div>
           
           
           <div id="uploadfield">
               
                 <input type="submit" value="Add" class="browse" name="banner_submit" style="margin:7px 0px 0 20px;" />
               
               </div>
           
             
             </form>
             
             <div class="clear"></div>
             
             </div>
             
             </div>
           
           </div>
         
         </div>
              
       </div>
     
     </div>

     <?php include("includes/admin-footer.php"); ?>
	 
   </div>
  
  </div>
     
</body>
</html>