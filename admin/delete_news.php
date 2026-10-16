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

			   

			   $delteProductFromTable = $db->query("DELETE FROM tbl_news WHERE INewsID=$NewsID");

		   }

	  

		           



				   

				   $msg = "News Deleted Sucessfully";

				   echo "<script language='javascript'>window.location='news.php?msg=$msg'</script>";



?>