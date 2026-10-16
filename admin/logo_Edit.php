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

<title>Logo Manger</title>

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
         
           <h3>Logo Manager</h3>
         
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
             
               <h4>Update Logo</h4>
             
             </div>
             
             <div id="banerdetail22">
             
             <div id="listofbanners">
			 
			 <?php
             
			          $id = $_GET['id'];
					  $select = $db->query("select * from tbl_logo where clogoID=$id");
					  $rowofbanner = $select->fetch_array();
			 ?>
             
             <form action="upload_edit_delete_logo.php?case=updatebanner" method="post" name="bannerupdate" enctype="multipart/form-data">
             
             
             
             <div style="float:left; width:100%; margin-top:25px;">
             
               <div class="fieldsname" style="float:left; width:20%; border:0; font-family:'Myriad Pro',Arial">Banner Image :</div>
               <div style="float:left;width:80%;">
                 
				   <input type="file" name="banerpath" id="banerpath" style="vertical-align:top; margin-right:30px;" />  
                 
               </div>
             
             </div>
             
             <div style="float:left; width:100%; margin-top:25px;">
             
               <div class="fieldsname" style="float:left; width:20%; border:0; font-family:'Myriad Pro',Arial">Status :</div>
               <div class="inputfield" style="float:left; width:80%;">
               
                 <select name="status" id="status" class="status" style="width:150px;">
                   
					   <?php
					   
					           if ($rowofbanner['cStatus'] == 1)
							   {
								   echo "<option value='0'>Disable</option>";
								   echo "<option value=".$rowofbanner['cStatus']." 'selected'>Enable</option>";
							   }
							   else
							   {
								   echo "<option value='1'>Enable</option>";
								   echo "<option value=".$rowofbanner['cStatus']." 'selected'>Disable</option>";
							   }
					   
					   ?>
                     
                 </select>
               
               </div>
             
             </div>
             
             <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />
             
             <div style="float:left; width:100%; margin-top:15px;">
             
               <div class="fieldsname" style="float:left; width:20%;">&nbsp;</div>
               <div class="inputfield" style="float:left; width:80%;">
               
                 <input type="submit" value="Update" />
                 <input type="button" value="Cancel" onClick="window.location.href='logoManager.php'" />
                 
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