<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

}

	  

	  include("classes/connection.php");

	

if(isset($_POST['map']) && isset($_POST['detail']) && isset($_POST['order']) && isset($_FILES['image']['name']) && isset($_POST['status']))

	{

	

		  

		   

		     $detail = $_POST['detail'];

			

			 $map = $_POST['map'];
			 
			 $order = $_POST['order'];
			
		$image = $_FILES['image']['name'];
			

		   $status  =  $_POST['status'];

	
	
		    if (($_FILES["image"]["type"] == "image/jpeg")  || ($_FILES["image"]["type"] == "image/jpg") || ($_FILES["image"]["type"] == "image/png"))
			{
				if ($_FILES["image"]["error"] > 0)
				{
					$no = "Error: " . $_FILES["file"]["error"] . "<br />";
					$msg="Please select proper image format";
					echo "<script language='javascript'>window.location='branches.php?msg=$msg'</script>";
				}
				else
				{
					move_uploaded_file($_FILES['image']['tmp_name'],"../Resources/branch/".$image);
					$qry="insert into tbl_branches(IBranchMap,IBranchAddress,IOrder,IImage,IStatus) 		values('$map','$detail',$order,'$image','$status')";

		  			$db->query($qry) or die("insert query error").mysqli_error($db);

		  			$msg = "Branch Inserted Successfully";

					header("location:branches.php?msg=$msg");
				}
			}
			
			else
			{
				$msg = "Invalid file";
				echo "<script language='javascript'>window.location='branches.php?msg=$msg'</script>";
			}
	
	
	}

		else{

		
	
		
			$msg="You have to fill all the fields";

		header("location:branches.php?msg=$msg");

			

			} 

		 

?>