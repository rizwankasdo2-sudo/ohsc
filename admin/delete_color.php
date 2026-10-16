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
			   
			   $selct = $db->query("select * from tbl_color WHERE colorid=$NewsID ");
			   $result = $selct->fetch_array();
			   $image = $result['Image'];
			   unlink("../Resources/color/".$image);
			   
			   
			   $deletesubmenu=$db->query("DELETE FROM tbl_color WHERE colorid=$NewsID");


		   }

	  

		           



				   

				   $msg = "Deleted Sucessfully";

				   echo "<script language='javascript'>window.location='colors.php?id=$id&msg=$msg'</script>";



?>