<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

}

//print_r($_POST['submit']);
	  

	  include("classes/connection.php");

	  if($_POST['submit']){

	  

		  $name =   mysqli_real_escape_string($db, $_POST['name']);

		  $description = mysqli_real_escape_string($db, $_POST['description']);

		    $detail = mysqli_real_escape_string($db, $_POST['detail']);

		  $IStatus  =  $_POST['IStatus'];
		 $date = date('Y-m-d H:i:s');

		 $qry="insert into tbl_testimonial(name, detail,description, IStatus,time)
                                  values ('$name', '$detail','$description','$IStatus','$date')";

		  $db->query($qry) or die(mysqli_error($db));

		  echo $msg = "Inserted Successfully";

		echo "<script language='javascript'>window.location='testimonial.php?msg=$msg'</script>";



}

?>