<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

}

	  

	  include("classes/connection.php");

	

	if(isset($_POST['pname'])  && isset($_POST['category'])  && isset($_POST['price']) && isset($_FILES['image']['name']) && isset($_POST['detail'])  && isset($_POST['status']) && isset($_POST['best']))

	{

	

		  

		   
		$name = $_POST['pname'];
		
		$category = $_POST['category'];
		
		$price = $_POST['price'];
		
		$detail = $_POST['detail'];
		
		$image=$_FILES['image']['name'];
		
		 $status  =  $_POST['status'];
		 $bestseller = $_POST['best'];  

	

		  

		if(($_FILES['image']['type']=="image/jpeg") || ($_FILES['image']['type']=="image/pjpeg") || ($_FILES["image"]["type"] == "image/png")) 

		 {

			 if($_FILES['image']['error'] > 0){

				 

				 $no="Error".$_FILES['image']['error']. "<br />";

				 header("location:product.php?no=$no");

				 

				 }

			 else{

				 

				 move_uploaded_file($_FILES['image']['tmp_name'], "../Resources/product/".$image);

				 	

					$qry=$db->query("INSERT INTO `tbl_product`(`ICatID`, `IItemName`, `Image`, `price`, `Description`, `IStatus`,bestseller) VALUES($category,'$name','$image',$price,'$detail','$status',$bestseller)");

		 $ids = $db->insert_id;

		  $msg = "Inserted Successfully";

		header("location:product.php?msg=$msg&idees=$category");

				 }

			 

			 }

			 	

	}



		else{

		

			$msg="You have to fill all the fields";

		header("location:product.php?msg=$msg&idees=$category");

			

			} 

		 

?>