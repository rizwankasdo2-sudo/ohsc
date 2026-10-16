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
		  
		  
		  $db->query("update ourcourses set 
	ourcoursedetail='$Description' where ourcourseid=$id");
		  
		  $msg = "Updated Successfully";
		  echo "<script language='javascript'>window.location='ourcourse.php?msg=$msg'</script>";

?>