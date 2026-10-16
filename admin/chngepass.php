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

<title>Change Your Password</title>

<link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />

<link rel="stylesheet" href="../Resources/admin_css/baner.css" />

<script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>

<!--[if lte IE 6]>
	<script type="text/javascript" src="../Resources/admin_js/supersleight-min.js"></script>
<![endif]-->


<script type="text/javascript">

  function abc()
  {
	  var pass1 = document.changepass.pass.value;
	  var pass2 = document.changepass.repass.value;
	  
	  if (pass1 == "")
	  {
		  alert("Please Enter Your New Password.");
		  return false;
	  }
	  else if (pass2 == "")
	  {
		  alert("Please Enter Your New Password Again.");
		  return false;
	  }
	  else if (pass1 != pass2)
	  {
		  alert("Your Passwords do not match");
		  return false;
	  }
  }

</script>

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
         
           <h3>Change Password</h3>
         
         </div>
       
       </div>
       
       <div id="right_corner">
       </div>
       
       <div id="main_content">
       
         <div id="addbaners">
           
           <?php
			 
			          if (isset($_GET['msg']))
					  {
						  echo '<p style="color:black; font-size:28px; text-align:center; font-weight:bold">';
						  echo $_GET['msg'];
						  echo '</p>';
					  }
					  
		   ?>
               
                 <form action="passreset.php" method="post" name="changepass" id="changepass" onsubmit="return abc();">
                 
                   <div style="margin-left:200px; margin-top:20px;">
                   
                     <p style="font-family:Tahoma, Geneva, sans-serif; font-size:14px; font-weight:bold; float:left; width:200px;">
                       Enter Password :
                     </p>
                     <input type="password" name="pass" id="pass" size="30" style="float:left;" />
                   
                   </div>
                   
                   <div class="clear">&nbsp;</div>
                   
                   <div style="margin-left:200px;">
                   
                     <p style="font-family:Tahoma, Geneva, sans-serif; font-size:14px; font-weight:bold; float:left; width:200px;">
                       Re-Enter Password :
                     </p>
                     <input type="password" name="repass" id="repass" size="30" style="float:left;" />
                   
                   </div>
                   
                   <div class="clear">&nbsp;</div>
                   
                   <div style="margin-left:200px; width:405px;">
                   
                     <input type="submit" value="Submit" style="float:right; cursor:pointer;" />
                   
                   </div>
                 
                 </form>
               
               </div>
               
         <div class="clear">&nbsp;</div>
              
       </div>
     
     </div>
	 
	 <?php include("includes/admin-footer.php"); ?>
	 
   </div>
  
  </div>
     
</body>
</html>