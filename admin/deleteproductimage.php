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

			   $testi = $_GET['uid'];

			   $qry=$db->query("select * from tbl_productimage where imageid=$testi");

			   $row= $qry->fetch_array();

			   $image=$row['Image'];
			   $id = $row['ItemID'];

			   unlink("../Resources/product/".$image);

			   

			   $delteProductFromTable = $db->query("DELETE FROM tbl_productimage WHERE imageid=$testi");

		   }

	  

		           



				   

				   $msg = "Deleted Sucessfully";

				   echo "<script language='javascript'>window.location='productimage.php?id=$id&msg=$msg'</script>";



?>