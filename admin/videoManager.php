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


<link rel="stylesheet" href="../Resources/admin_css/products.css" />

<title>Video Manager</title>

<link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />

<link rel="stylesheet" href="../Resources/admin_css/baner.css" />

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
       
       <div id="midle_repeat">
         
         <div id="productsheading">
         
           <h3>Video Manager</h3>
         
         </div>
         
         <div id="adddel">
         
           <ul>
           
             <li style="float:right"><a href="add_video_in_cat.php">Add</a></li>
           
           </ul>
         
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
             
             
             
             
           
           
           
           
           <?php
			 
			          if (isset($_GET['msg']))
					  {
						  echo '<p style="color:black; font-size:28px; text-align:center; font-weight:bold">';
						  echo $_GET['msg'];
						  echo '</p>';
					  }
					  
		   ?>
           <div class="clear">&nbsp;</div>
           <div id="banerhead">
           
             <div id="banertop">
             
               <h4>Overview</h4>
             
             </div>
             
             <div id="banerdetail22">
             
               <div id="listofbanners">
             
               <table width="100%" border="0" cellspacing="0" cellpadding="0">
               
               
                 <tr id="top_row">
                 	<th scope="col" class="bbtitle">Video Name</th>
                   <th scope="col" class="bbtitle"   style="padding-left:0px;">Video Image</th>
                   <th scope="col" class="thumbnailshow">Video Link</th>
                   <th scope="col" class="bbtitle"  style="padding-left:0px;">Uploaded Date</th>
                  
                   <th scope="col" class="lasttwo"  style="padding-left:0px;">Status</th>
                   <th scope="col" class="lasttwo" style="border-right:none; padding-left:0px;">Action</th>
                 </tr>
                 
                   <?php
			 
			            $bannerslist = $db->query("SELECT * FROM tbl_videos order by vVideoID desc");
				        while($row= $bannerslist->fetch_array())
						{
							$id = $row['vVideoID'];
				   
				   ?>
                 
                 <tr id="rowdownrepeat">
                 	<td class="bbtitd" style="padding-left:0px;"><?php echo $row['vVideoName']; ?></td>
                    <td class="bbtitd"   style="padding-left:0px;" ><img src="../Resources/video_images/<?php echo $row['vVideoImage'];?>" alt="" width="140" height="80" /></td>
                   <td class="bbtitd" width="250" headers="150" style="padding-left:0px;"><iframe width="250" height="150" src="<?php echo $row['vVideoLink']; ?>" frameborder="0" allowfullscreen></iframe></td>
                   
                   <td class="bbtitd" style="padding-left:0px;"><?php echo $row['vVideoDate']; ?></td>
                   
                  
                   
                   <td class="last2" style="padding-left:0px;">
                   
                     <?php
                            if ($row['6'] == 1)
							{
								echo "Enable";
							}
							elseif (($row['6']) == 0)
							{
								echo "Disable";
							}
					 ?>
                   
                   </td>
                   
                   <td align="center" class="last2"  style="padding-left:0px;">
                   
                        <a href="videoManager_Edit.php?id=<?php echo $id; ?>" class="et"></a>
                        <a href="videoManager_upload_edit_delete.php?id=<?php echo $id; ?>&case=<?php echo "deletebanner"; ?>" class="dl"></a>
                   
                   </td>
                   
                 </tr>
                 
                   <?php } ?>
               
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
    
</body>
</html>