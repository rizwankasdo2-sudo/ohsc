<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

}



include("classes/connection.php");





		   if (isset($_GET['id']))

		   {

			   $id = $_GET['id'];

			   

			   $delteProductFromTable = $db->query("DELETE FROM review_form WHERE id=$id");

		   }

	    

				   $msg = "Deleted Sucessfully";

				   echo "<script language='javascript'>window.location='stdreviews.php?msg=$msg'</script>";



?>