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

			  

			   $qry = $db->query("select * from tbl_branches where IBranchid=$testi");
			   $result = $qry->fetch_array();
			   $image = $result['IImage'];
				unlink("../Resources/branch/".$image);
			   $delteProductFromTable = $db->query("DELETE FROM tbl_branches WHERE IBranchid=$testi");

		   }

	  

		           



				   

				   $msg = "Branch Deleted Sucessfully";

				   echo "<script language='javascript'>window.location='branches.php?msg=$msg'</script>";



?>