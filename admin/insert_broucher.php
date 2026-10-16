<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

}

	  

	  include("classes/connection.php");

	

	if(isset($_POST['name']) && isset($_FILES['image']['name']) && isset($_POST['company'])  && isset($_POST['status']))

	{

		  $name =   $_POST['name'];

		  

		   

		    $company = $_POST['company'];

			

			 $image=$_FILES['image']['name'];

			

		  $status  =  $_POST['status'];

	}


		if(($_FILES['image']['type']=="application/zip") || ($_FILES['image']['type']=="application/x-zip-compressed") || ($_FILES["image"]["type"] == "multipart/x-zip") | ($_FILES["file"]["type"] == "application/x-compressed") || ($_FILES["file"]["type"] == "application/octet-stream")) 

		 {

			 if($_FILES['image']['error'] > 0){

				 

				 $no="Error".$_FILES['image']['error']. "<br />";

				 header("location:company.php?no=$no");

				 

				 }

			 else{

				 

				 move_uploaded_file($_FILES['image']['tmp_name'], "../Resources/broucher/".$image);

				 	

					$qry="insert into tbl_borucher(ICompanyID,IBroucherName,IBroucher,BStatus) values($company,'$name','$image','$status')";

		  $db->query($qry) or die("insert query error").mysqli_error($db);

		  $msg = "Brochure Inserted Successfully";

		header("location:broucher.php?msg=$msg");

				 }

			 

			 }

			 	

	



		else{

		

			$msg="you first enter data first";

		header("location:broucher.php?msg=$msg");

			

			} 

		 

?>