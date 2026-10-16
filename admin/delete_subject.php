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

			   $cid = $_GET['cid'];	
				$did = $_GET['did'];

			   $delteProductFromTable = $db->query("DELETE FROM tbl_subject WHERE subjectid=$NewsID");

		   }

	  

		           



				   

				   $msg = "News Deleted Sucessfully";

				   echo "<script language='javascript'>window.location='subject.php?cid=$cid&did=$did&msg=$msg'</script>";



?>