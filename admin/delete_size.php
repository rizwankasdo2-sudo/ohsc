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
			   $id = $_GET['id'];
			   
			   
			   $deletesubmenu=$db->query("DELETE FROM tbl_size WHERE sizeid=$NewsID");

			   

			   

		   }

	  

		           



				   

				   $msg = "Deleted Sucessfully";

				   echo "<script language='javascript'>window.location='size.php?id=$id&msg=$msg'</script>";



?>