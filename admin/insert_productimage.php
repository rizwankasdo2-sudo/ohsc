<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

}

	  

	  include("classes/connection.php");

	

	if(isset($_FILES['image']['name']) && isset($_POST['id']) && isset($_POST['detail'])  && isset($_POST['status']))

	{

	

		  

		   

		     $detail = $_POST['detail'];

			$id = $_POST['id'];

			 $image=$_FILES['image']['name'];

			

		   $status  =  $_POST['status'];

	

		  

		if(($_FILES['image']['type']=="image/jpeg") || ($_FILES['image']['type']=="image/pjpeg") || ($_FILES["image"]["type"] == "image/png")) 

		 {

			 if($_FILES['image']['error'] > 0){

				 

				 $no="Error".$_FILES['image']['error']. "<br />";

				 header("location:productimage.php?no=$no&id=$id");

				 

				 }

			 else{

				 

				 move_uploaded_file($_FILES['image']['tmp_name'], "../Resources/product/".$image);

				 	

					$qry="insert into tbl_productimage(ItemID,Image,Description,IStatus) values($id,'$image','$detail','$status')";

		  $db->query($qry) or die("insert query error").mysqli_error($db);

		  $msg = "Inserted Successfully";

		header("location:productimage.php?msg=$msg&id=$id");

				 }

			 

			 }

			 	

	}



		else{

		

			$msg="You have to fill all the fields";

		header("location:productimage.php?msg=$msg&id=$id");

			

			} 

		 

?>