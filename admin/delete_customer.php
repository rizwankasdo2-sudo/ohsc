<?php
session_start();
ob_start();
if($_SESSION['adminid']== "")
{
	header("location:index.php");
	exit();
}


     include("classes/connection.php");
	 
	 if (isset ($_GET['id']))
	 {
		 $title234 = $_GET['id'];
	 }
	 $infoqry = $db->query("select * from tbl_tmporderinfo where ICustoamrID=$title234");
	 $inforesult = $infoqry->fetch_array();
	 $orderuniqeid = $inforesult['ITemporderinfoID'];
	  $deletequery = $db->query("DELETE FROM tbl_temporderdetail WHERE ITemporderinfoID=$orderuniqeid");
	  $deletecustomar = $db->query($deletequery);
		  
	  $deletequeryinfo = $db->query("DELETE FROM tbl_tmporderinfo WHERE ITemporderinfoID=$orderuniqeid");
		  
	 $deltecustomerfromtable = $db->query("DELETE FROM tbl_customer WHERE ICustomerID=$title234");
	 $msg = "Customer Details Deleted Sucessfully";
	 echo "<script language='javascript'>window.location='customer.php?msg=$msg'</script>";
				 
?>		 
				 