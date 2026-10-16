<?php
session_start();
ob_start();
if($_SESSION['adminid']== "")
{
	header("location:index.php");
	exit();
}

       include("classes/connection.php");
	   
	   if (isset ($_POST['ostatus']) && isset($_POST['ouniqueid']))
	   {
		   $changeorderstatus = $_POST['ostatus'];
		   $uniqeorderid = $_POST['ouniqueid'];
		   
		 $changeorderstatusquery = "update tbl_tmporderinfo set oStaus=$changeorderstatus where ITemporderinfoID=$uniqeorderid";
		   $updatetableoforder = $db->query($changeorderstatusquery);
		   
	   }

?>

<script language='javascript'>window.location='orderslist.php'</script>