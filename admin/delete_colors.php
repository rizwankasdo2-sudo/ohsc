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

			   $qry=$db->query("select * from tbl_portfolio where IPortfolioID=$testi");

			   $row= $qry->fetch_array();

			   $image=$row['IPortfolioImage'];

			
			   

			   $delteProductFromTable = $db->query("DELETE FROM tbl_portfolio WHERE IPortfolioID=$testi");

		   }

	  

		           



				   

				   $msg = "Portfolio record Deleted Sucessfully";

				   echo "<script language='javascript'>window.location='portfolio.php?msg=$msg'</script>";



?>