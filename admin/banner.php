<?php
session_start();
ob_start();
if($_SESSION['adminid']== "")
{
	header("location:index.php");
	exit();
}
$error = 0;
$msg = 0;
include("classes/connection.php");
if(isset($_POST['banner_submit'])){
    if(isset($_FILES['banerpath']['name']) && !empty($_FILES['banerpath']['name'])){
        $image = time().$_FILES['banerpath']['name'];
        
        $dir_name = '../images/banner';
        $tmp_name = $_FILES['banerpath']['tmp_name'];
        $destination = $dir_name.'/'.$image;
        
        
    } else {
        $error = 1;
    }
    
    if(isset($_POST['banner_heading']) && !empty($_POST['banner_heading'])){
        $banner_heading = $_POST['banner_heading'];
    } else {
        $error = 1;
    }
    
    if(isset($_POST['banner_link']) && !empty($_POST['banner_link'])){
        $banner_link = $_POST['banner_link'];
    } else {
        $error = 1;
    }
    
    if(isset($_POST['page_link']) && !empty($_POST['page_link'])){
        $page_link = $_POST['page_link'];
    } else {
        $error = 1;
    }
    
     if(isset($_POST['status']) && !empty($_POST['status'])){
        $banner_status = $_POST['status'];
    } else {
        $error = 1;
    }
    
    if($error==0){
        $sql = "INSERT INTO tbl_banner (IBannerPath, IStatus, banner_content, banner_link, page_link) VALUES ('$image', '$banner_status', '$banner_heading', '$banner_link', '$page_link' )";
        $result = $db->query($sql);
        if(!$result){
            die(mysqli_error($db));
        }
        if(move_uploaded_file($tmp_name, $destination)){
            $error = 0;
        } else {
            $error = 1;
        }
        $msg = 1;
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
       <?php
					  
		   
           if(isset($_GET['msg']) && $_GET['msg'] == 1){
             echo '<div style="color: green; margin: 5px auto; width: 400px">Banner Successfully Updated</div>';
             };		  
		   
           if(isset($_GET['msg']) && $_GET['msg'] == 2){
             echo '<div style="color: green; margin: 5px auto; width: 400px">Banner Successfully Deleted</div>';
             };
             ?>
     <div id="board">
     
       <div id="left_corner">
       </div>
       
       <div id="midle_repeat">
         
         <div id="bannerheading">
         
           <h3>Banner Manager</h3>
         
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
           
             <?php
			 
			          if (isset($_GET['no']))
					  {
						  echo $no;
					  }
					  elseif (isset($_GET['invalid']))
					  {
						  echo '<p style="color:black; font-size:28px; text-align:center; font-weight:bold">';
						  echo $_GET['invalid'];
						  echo '</p>';
					  }
					  
			 ?>
             
             <div id="banertop">
             
               <h4>Add New Banner</h4>
             
             </div>
             
             <div id="banerdetail">
             <?php
             if($error == 1){
             echo '<div style="color: red; margin: 5px">All Fields are Required</div>';
             };
             
             if($msg == 1){
             echo '<div style="color: green; margin: 5px">Banner Successfully Added</div>';
             };
             
             ?>
               <div id="uploadbanner">
               
                 <form action="" method="post" enctype="multipart/form-data">
                 
                   
                   
                   <div id="uploadfield">
                   <label>Image </label>
                     <input type="file" class="banerimgfield" name="banerpath" id="banerpath"/>
                     
                      <!--<strong style="float:left; font-family:Verdana, Geneva, sans-serif; font-size:12px; margin-top:15px;">(983 x 305)</strong>-->
                   
                   </div>
                   
                   
                   
                   <div id="uploadfield">
                   <label for="banner_heading">Banner Heading</label>
                     <input type="text" class="banerimgfield" name="banner_heading" id="banner_heading" style="padding: 5px 5px; height: 35px; border: 1px solid #ddd" />
                     
                      <!--<strong style="float:left; font-family:Verdana, Geneva, sans-serif; font-size:12px; margin-top:15px;">(983 x 305)</strong>-->
                   
                   </div>
                   
                   <div id="uploadfield">
                   <label for="banner_link">Banner Link</label>
                     <input type="text" class="banerimgfield" name="banner_link" id="banner_link" style="padding: 5px 5px; height: 35px; border: 1px solid #ddd" />
                     
                      <!--<strong style="float:left; font-family:Verdana, Geneva, sans-serif; font-size:12px; margin-top:15px;">(983 x 305)</strong>-->
                   
                   </div>
                   
                   <div id="uploadfield">
                   <label for="page_link">Page Link</label>
                     <input type="text" class="banerimgfield" name="page_link" id="page_link" style="padding: 5px 5px; height: 35px; border: 1px solid #ddd" />
                     
                      <!--<strong style="float:left; font-family:Verdana, Geneva, sans-serif; font-size:12px; margin-top:15px;">(983 x 305)</strong>-->
                   
                   </div>
                   
                   
                   <div id="uploadfield" style="margin-top: 15px;">
                   
                     <label>Status :</label>
                     
                     <select name="status" id="status" class="stat" style="padding: 5px 5px; height: 35px; border: 1px solid #ddd">
                     
                       <option selected="selected" value="2">Select Status</option>
                       <option value="1">Enable</option>
                       <option value="0">Disable</option>
                     
                     </select>
                   
                   </div>
                   
                   <div id="uploadfield">
                   
                     <input type="submit" value="Add" class="browse" name="banner_submit" style="margin:7px 0px 0 20px;" />
                   
                   </div>
                 
                 </form>
               
               </div>
             
             </div>
           
           </div>
           
           
           <div class="clear">&nbsp;</div>
           
         
           <div id="banerhead">
           
             <div id="banertop">
             
               <h4>Banners</h4>
             
             </div>
             
             <div id="banerdetail22">
             
               <div id="listofbanners">
             
               <table width="100%" border="0" cellspacing="0" cellpadding="0">
               
               
                 <tr id="top_row">
                   <th scope="col" class="lasttwo">Sr.</th>
                   <th scope="col" class="thumbnailshow">Image</th>
                   <th scope="col" class="lasttwo">Status</th>
                   <th scope="col" class="lasttwo" style="border-right:none;">Action</th>
                 </tr>
                 
                   <?php
			 			$counter=1;
			            $bannerslist = $db->query("SELECT * FROM tbl_banner order by IBannerID desc");
				        while($row= $bannerslist->fetch_array())
						{
							$id = $row['IBannerID'];
				   
				   ?>
                 
                 <tr id="rowdownrepeat">
                    <td class="last2"><?php echo $counter; ?></td>
                   <td class="bigimg"><img src="../images/banner/<?php echo $row['IBannerPath'];?>" alt="" width="300" height="80" /></td>
                   <td class="last2">
                   
                     <?php
                            if ($row['IStatus'] == 1)
							{
								echo "Enable";
							}
							elseif (($row['IStatus']) == 0)
							{
								echo "Disable";
							}
					 ?>
                   
                   </td>
                   
                   <td align="center" class="last2">
                   
                        <a href="banner_Edit.php?id=<?php echo $id; ?>" class="et"></a>
                        <a href="delete_banner.php?id=<?php echo $id; ?>" class="dl"></a>
                   
                   </td>
                   
                 </tr>
                 
                   <?php $counter++; } ?>
               
               </table>

             
             </div>
             
             </div>
             
             
           
           </div>
         
         </div>
              
       </div>
     
     </div>
	 
	 <?php include("includes/admin-footer.php"); ?>
	 
   </div>
  
  </div>
   
   <script>
    $(function(){
        alert('working');
    });
   </script>
     
</body>
</html>