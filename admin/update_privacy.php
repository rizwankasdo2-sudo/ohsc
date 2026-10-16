<?php
session_start();
ob_start();
if($_SESSION['adminid']== "")
{
	header("location:index.php");
	exit();
}
	  
	  include("classes/connection.php");
	  
	  //  Add, Update, Delete Data in tbl_product

		  $id   =   $_POST['id'];
		 
		  $Description   =   $_POST['description'];
		  
		  
		  $db->query("update tbl_privacypolicy set 
	
IPrivacyText='$Description' where IPirvacyID=$id");
		  
		  $msg = "Privacy Policy Updated Successfully";
		  echo "<script language='javascript'>window.location='privacy_page.php?msg=$msg'</script>";

?>