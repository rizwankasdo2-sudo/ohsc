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
			   
			   $catid = $_GET['catid'];

			   

			   $delteProductFromTable = $db->query("DELETE FROM tbl_menuitems WHERE ItemID=$NewsID");

		   }

	  

		           



				   

				   $msg = "Category Deleted Sucessfully";

				   echo "<script language='javascript'>window.location='items.php?msg=$msg&id=$catid'</script>";



?>