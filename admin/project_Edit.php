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

<script type="text/javascript" src="../Resources/admin_js/myform.js"></script>

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
           
             <div id="banertop">
             
               <h4>Update Projects</h4>
             
             </div>
             
             <div id="banerdetail22">
             
             <div id="listofbanners">
			 
			 <?php
             
			      $id = $_GET['id'];
					  $select = $db->query("select * from tbl_projects where Iprojectid=$id");
					  $rowofbanner = $select->fetch_array();
			 ?>
             
             <form action="update_project.php" method="post" name="bannerupdate" enctype="multipart/form-data">
             
             <div style="float:left; width:100%;">
             
               <div class="fieldsname" style="float:left; width:20%; border:0; font-family:'Myriad Pro',Arial; margin-top:15px;">Title :</div>
               <div class="inputfield" style="float:left; width:80%; margin-top:10px;">
               
                 <input type="text" name="downloadtitle" id="downloadtitle" value="<?php echo $rowofbanner['IProjectTitle']; ?>"  size="40" style="height:30px;"/>
               
               </div>
               
               
               
               <div style="float:left; width:100%; margin-top:15px;">
               
               <div class="fieldsname" style="float:left; width:20%; border:0; font-family:'Myriad Pro',Arial">Description :</div>
                <div class="inputfield" style="float:left; width:80%;">
               
                 <input type="text" name="downloaddesc" id="downloaddesc" value="<?php echo $rowofbanner['IProjectDiscription']; ?>" size="40" style="height:30px;" />
               
               </div>
               </div>
               
             </div>
             
             <div style="float:left; width:100%; margin-top:25px;">
             
               <div class="fieldsname" style="float:left; width:20%; border:0; font-family:'Myriad Pro',Arial">Upload :</div>
               <div style="float:left;width:80%;">
                 
				   <input type="file" name="downloadpath" id="downloadpath" style="vertical-align:top; margin-right:30px;"  size="30" />  
                 
               </div>
               
             
             </div>
             
             
             
             <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />
             
             <div style="float:left; width:100%; margin-top:15px;">
             
               <div class="fieldsname" style="float:left; width:20%;">&nbsp;</div>
               <div class="inputfield" style="float:left; width:80%;">
               
                 <input type="submit" value="Update" />
                 <input type="button" value="Cancel" onClick="window.location.href='project_page.php'" />
                 
               </div>
             
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