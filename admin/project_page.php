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

<title>Projects</title>

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

   include("classes/connection.php");
   
   include("includes/admin-header.php");
   
   include("includes/admin-menu.php");

 ?>
   
   
   
   <div id="outer_contanier">
     
     <div id="board">
     
       <div id="left_corner">
       </div>
       
       <div id="midle_repeat">
         
         <div id="bannerheading">
         
           <h3>Projects</h3>
         
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
             
               <h4>Add New</h4>
             
             </div>
             
             <div id="banerdetail">
             
               <div id="uploadbanner">
               
                 <form action="upload_delete_project.php?case=addbanner" method="post" name="banerupload" enctype="multipart/form-data" onsubmit="return valForm();">
                 
                    <div id="download_div2">
                    <p>Title :</p>
                     <input type="text" name="prjecttitle"  style="float:left" size="10" class="download4"/>
                     <p>Description : </p>
                     
                     <input type="text" name="prjectdesc" id="prjectdesc" class="download3" />
                   
                   </div>
                   
                   <div id="uploadfield" style="width:346px;">
                   <p>Upload : </p>
                     <input type="file" class="banerimgfield" name="prjectpath" id="prjectpath" style="float:left; margin-right:0px; width:228px;" />
                    
                    
                   </div>
                   
                 
                   
                   <div id="uploadfield" style="width:120px; border:0;">
                   
                     <input type="submit" value="Add" class="browse" style="margin:7px 0px 0 20px;" />
                   
                   </div>
                 
                 </form>
               
               </div>
             
             </div>
           
           </div>
           
           
           <div class="clear">&nbsp;</div>
           
           <?php
			 
			          if (isset($_GET['msg']))
					  {
						  echo '<p style="color:black; font-size:28px; text-align:center; font-weight:bold">';
						  echo $_GET['msg'];
						  echo '</p>';
					  }
					  
		   ?>
           
           <div id="banerhead">
           
             <div id="banertop">
             
               <h4>Projects</h4>
             
             </div>
             
             <div id="banerdetail22">
             
               <div id="listofbanners">
             
               <table width="100%" border="0" cellspacing="0" cellpadding="0">
               
               
                 <tr id="top_row">
                   <th scope="col" class="bbtitle">Title</th>
                    <th scope="col" class="bbtitle">Description</th>
                   <th scope="col" class="thumbnailshow">File</th>
                   <th scope="col" class="lasttwo" style="border-right:none;">Action</th>
                 </tr>
                 
                   <?php
			 
			            $bannerslist = $db->query("SELECT * FROM tbl_projects order by Iprojectid desc");
				        while($row= $bannerslist->fetch_array())
						{
							$id = $row['Iprojectid'];
				   
				   ?>
                 
                 <tr id="rowdownrepeat">
                   <td class="bbtitd"><?php echo $row['IProjectTitle']; ?></td>
                    <td class="bigimg"><?php echo $row['IProjectDiscription']; ?></td>
                   <td class="bbtitd"><?php echo $row['IProjectPdf']; ?></td>
                  
                   <td align="center" class="last2">
                   
                       <a href="project_Edit.php?id=<?php echo $id; ?>" class="et"></a>
                        <a href="upload_delete_project.php?id=<?php echo $id; ?>&case=<?php echo "deletebanner"; ?>" class="dl"></a>
                   
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