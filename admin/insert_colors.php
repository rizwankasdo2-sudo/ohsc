<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

}

	  

	  include("classes/connection.php");

	

	if(isset($_POST['catname']) && isset($_FILES['image']['name']) && isset($_POST['order'])  && isset($_POST['status']))

	{

		  $name =   $_POST['catname'];

		  
		$id = $_POST['id'];
		   

		    $detail = $_POST['order'];

			

		 $image=$_FILES['image']['name'];

			

		  $status  =  $_POST['status'];
		  if(isset($_POST['size']))
		  {
		  $size = $_POST['size'];
		  }
		  else
		  {
			  $size = 0;
			  
			 }
	}

		  

		if(($_FILES['image']['type']=="image/jpeg") || ($_FILES['image']['type']=="image/pjpeg") || ($_FILES["image"]["type"] == "image/png")) 

		 {

			 if($_FILES['image']['error'] > 0){

				 

				 $no="Error".$_FILES['image']['error']. "<br />";

				 header("location:colors.php?id=$id&no=$no");

				 

				 }

			 else{

				 

				 move_uploaded_file($_FILES['image']['tmp_name'], "../Resources/color/".$image);

				 	

					$db->query ("insert into tbl_color(ItemID,colorname,colorprice,Image,IStatus,sizeid) values($id,'$name',$detail,'$image',$status,'$size')");

		

		  $msg = "Inserted Successfully";

		header("location:colors.php?id=$id&msg=$msg");

				 }

			 

			 }

			 	

	



		else{

		

			$msg="you first enter data first";

		header("location:colors.php?id=$id&msg=$msg");

			

			} 

		 

?>