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

			   $qry=$db->query("select * from tbl_borucher where IBroucherID=$testi");

			   $row= $qry->fetch_array();

			   $image=$row['IBroucher'];

			   unlink("../Resources/broucher/".$image);

			   

			   $delteProductFromTable = $db->query("DELETE FROM tbl_borucher WHERE IBroucherID=$testi");

		   }









				   $msg = "Brochure Deleted Sucessfully";

				   echo "<script language='javascript'>window.location='broucher.php?msg=$msg'</script>";



?>