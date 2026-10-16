<?php

session_start();
ob_start();
if($_SESSION['adminid']== "")
{
	header("location:index.php");
	exit();
}

include("classes/connection.php");


		   if (isset($_POST['pass']) && isset($_POST['repass']))
		   {
			   $pass1 = $_POST['pass'];
			   $pass2 = $_POST['repass'];
			   
			   if ($pass1 == $pass2)
			   {
				   $newpass = md5($pass1);
				   
				   $updtpass = $db->query("update tbl_admin set bLoginPassword='$newpass' where IAdminID=".$_SESSION['adminid']." and sUserName='admin'");
				   $msg = "Your Password has been changed successfully";
				   echo "<script language='javascript'>window.location='chngepass.php?msg=$msg'</script>";
			   }
			   else
			   {
				   $msg = "Your Passwords do not match";
				   echo "<script language='javascript'>window.location='chngepass.php?msg=$msg'</script>";
			   }
		   }
				   
?>