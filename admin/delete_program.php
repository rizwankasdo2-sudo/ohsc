<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

}



include("classes/connection.php");





		   if (isset($_GET['uid']))

		   {

			   $NewsID = $_GET['uid'];

			   

			   $delteProductFromTable = $db->query("DELETE FROM tbl_program WHERE IProgramID=$NewsID");

		   }

	  

		           



				   

				   $msg = "Deleted Sucessfully";

				   echo "<script language='javascript'>window.location='Tprogram.php?msg=$msg'</script>";



?>