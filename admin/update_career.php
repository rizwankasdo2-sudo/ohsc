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
		  
		  
		  $db->query("update tbl_carrers set IcarrerContent='$Description' where ICarrersID=$id");
		  
		  $msg = "Careers Page Updated Successfully";
		  echo "<script language='javascript'>window.location='carrers.php?msg=$msg'</script>";

?>