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
			   
			   $productqry = $db->query("SELECT * FROM `tbl_product` WHERE ICatID=$NewsID");
			   
			   while($result = $productqry->fetch_array())
			   {
				   $image = $result['Image'];
				   unlink("../Resources/product/".$image);
				   $deleteqry = $db->query("DELETE FROM `tbl_product` WHERE ICatID=$NewsID");
				   
				   
				   
				}
			   

			   

			   $delteProductFromTable = $db->query("DELETE FROM tbl_menu WHERE ICatID=$NewsID");

		   }

	  

		           



				   

				   $msg = "Category Deleted Sucessfully";

				   echo "<script language='javascript'>window.location='category.php?msg=$msg'</script>";



?>