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

		  

		  

		  $db->query("update tbl_whole set detail='$Description' where id=$id");

		  

		  $msg = "Wholesale Page Updated Successfully";

		  echo "<script language='javascript'>window.location='wholsale.php?msg=$msg'</script>";



?>