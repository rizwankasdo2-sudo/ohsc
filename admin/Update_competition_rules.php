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



		  //$id   =   $_POST['id'];

		  

		  $Description   =   $_POST['description'];

		  

		  

		  $db->query("update pages set content='$Description' where id=29");

		  

		  $msg = "Competition Rules Page Updated Successfully";

		  echo "<script language='javascript'>window.location='pages.php?msg=$msg'</script>";



?>