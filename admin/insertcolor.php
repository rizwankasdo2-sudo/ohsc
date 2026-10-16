<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

}

	  

	  include("classes/connection.php");

	  if(isset($_POST['catname'])&& isset($_POST['order'])&& isset($_POST['status']))

{
		$id = $_POST['id'];
		  $name =   $_POST['catname'];
		  $order = $_POST['order'];


		  $status  =  $_POST['status'];
$qry = $db->query("insert into tbl_color(ItemID,colorname,colorprice,IStatus) values($id,'$name',$order,$status)");
		 

		   $msg = "Inserted Successfully";

		echo "<script language='javascript'>window.location='color.php?id=$id&msg=$msg'</script>";

		   }

		 

	



else{

	$msg="Not allowed empty fields";

	

	echo "<script language='javascript'>window.location='color.php?id=$id&msg=$msg'</script>";

	}



?>