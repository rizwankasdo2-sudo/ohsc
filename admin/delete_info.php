<?php include("classes/connection.php");

if(isset($_GET['id']))
{
$id = $_GET['id'];	

$qry = $db->query("delete from tbl_info where infoid=$id");
$msg = 'Deleted successfully';
	header("location:info.php?msg=$msg");
}


?>