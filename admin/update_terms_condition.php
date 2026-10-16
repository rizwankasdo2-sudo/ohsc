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

		  

		  

		  $db->query("update tbl_termscondtion set detail='$Description' where id=$id");

		  

		  $msg = "Terms and condition Page Updated Successfully";

		  echo "<script language='javascript'>window.location='terms_conditon.php?msg=$msg'</script>";



?>