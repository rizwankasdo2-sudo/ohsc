<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

}

 if(isset($_GET['did']) && isset($_GET['cid']))
{
$did = $_GET['did'];
$cid = $_GET['cid'];	
	
}

include("classes/connection.php");





		   if (isset($_GET['uid']))

		   {

			   $NewsID = $_GET['uid'];
			   
			   

			   

			   $delteProductFromTable = $db->query("DELETE FROM tbl_payment WHERE paymentid=$NewsID");

		   }

	  

		           



				   

				   $msg = "Deleted Sucessfully";

				   echo "<script language='javascript'>window.location='fullpayment.php?cid=$cid&did=$did&msg=$msg'</script>";



?>