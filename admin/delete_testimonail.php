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

			   

			   $delteProductFromTable = $db->query("DELETE FROM tbl_testimonial WHERE testimonialid=$NewsID");

		   }

	    

				   $msg = "Deleted Sucessfully";

				   echo "<script language='javascript'>window.location='diploma.php?msg=$msg'</script>";



?>