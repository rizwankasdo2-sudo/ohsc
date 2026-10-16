<?php
session_start();
ob_start();
if($_SESSION['adminid']== "")
{
	header("location:index.php");
	exit();
}

      include("classes/connection.php");
	  
	  if (isset ($_GET['doid']))
	  {
		  $orderuniqeid = $_GET['doid'];

		  $deletequery = $db->query("DELETE FROM tbl_temporderdetail WHERE ITemporderinfoID=$orderuniqeid");
		  $deletecustomar = $db->query($deletequery);
		  
		  $deletequeryinfo = $db->query("DELETE FROM tbl_tmporderinfo WHERE ITemporderinfoID=$orderuniqeid");
		  
		  echo '<script type="text/javascript">window.location="orderslist.php"</script>';
	  }
	  else
	  {
		  echo '<script type="text/javascript">window.location="index.php"</script>';
	  }

?>