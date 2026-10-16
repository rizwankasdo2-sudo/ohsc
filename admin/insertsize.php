<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

}

	  

	  include("classes/connection.php");

	  if(isset($_POST['sizename'])&& isset($_POST['status']))

{
		$id = $_POST['id'];
		  $name =   $_POST['sizename'];


		  $status  =  $_POST['status'];
$qry = $db->query("insert into tbl_size(ItemID,sizename,IStatus) values($id,'$name',$status)");
		 

		   $msg = "Inserted Successfully";

		echo "<script language='javascript'>window.location='size.php?id=$id&msg=$msg'</script>";

		   }

		 

	



else{

	$msg="Not allowed empty fields";

	

	echo "<script language='javascript'>window.location='size.php?id=$id&msg=$msg'</script>";

	}



?>