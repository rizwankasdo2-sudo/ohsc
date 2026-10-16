<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

}

	  

	  include("classes/connection.php");

	  if(isset($_POST['catname'])&& isset($_POST['price']) && isset($_POST['catid'])&& isset($_POST['status']))

{

		  $name =   $_POST['catname'];
		  
		   $price =   $_POST['price'];


		  $catid  =  $_POST['catid'];


		  $status  =  $_POST['status'];
		  
$qry = $db->query("insert into tbl_menuitems(ICatID,IItemName,IItemPrice,IStatus) values($catid,'$name',$price,$status)");
		 

		   $msg = "Items Inserted Successfully";

		echo "<script language='javascript'>window.location='items.php?msg=$msg&id=$catid'</script>";

		   }

		 

	



else{

	$msg="Not allowed empty fields";

	

	echo "<script language='javascript'>window.location='items.php?msg=$msg'</script>";

	}



?>