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
		 
		   $db->query("update tbl_home set IHometext='$Description' where IHomeid=$id");
		  
		  $msg = "Updated Successfully";
		  echo "<script language='javascript'>window.location='HomePage.php?msg=$msg'</script>";

?>