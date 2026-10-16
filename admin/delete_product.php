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
				
			   $qry=$db->query("select * from tbl_product where ItemID=$testi");

			   $row= $qry->fetch_array();

			   $image=$row['Image'];
			   $catid = $row['ICatID'];
			   unlink("../Resources/product/".$image);

			

			   

			   $delteProductFromTable = $db->query("DELETE FROM tbl_product WHERE ItemID=$testi");

		   }

	  

		           



				   

				   $msg = "Deleted Sucessfully";

				   echo "<script language='javascript'>window.location='product.php?idees=$catid&msg=$msg'</script>";



?>