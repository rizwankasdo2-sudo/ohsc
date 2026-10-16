<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

}

	  

	  include("classes/connection.php");

	  if(isset($_POST['NewsTitle']) && isset($_POST['Newsdiscription']) && isset($_POST['description']) && isset($_POST['status']))

{

		  $NewsTitle =   $_POST['NewsTitle'];
		  
		  if (!isset ($_POST['slug']) || $_POST['slug'] == '')
		  {
			  $slug = str_replace(' ', '-', strtolower($NewsTitle));
		  }
		  else
		  {
			  $slug = str_replace(' ', '-', strtolower($_POST['slug']));
		  }

		  

		   $Newsdiscription = $_POST['Newsdiscription'];

		 

		    $NewsDetail = $_POST['description'];

		  $status  =  $_POST['status'];

		 $qry="insert into tbl_footer(footertitle,slug,footerdetail,footerorder,IStatus) values('$NewsTitle','$slug','$NewsDetail','$Newsdiscription','$status')";

		  $msg = "Slug already exists";
		  
		  $db->query($qry) or die("<script language='javascript'>window.location='footer.php?msg=$msg'</script>").mysqli_error($db);

		  $msg = "Inserted Successfully";

		echo "<script language='javascript'>window.location='footer.php?msg=$msg'</script>";

		   }

		 

	



else{

	$msg="you cant pass empty fields";

	

	echo "<script language='javascript'>window.location='footer.php?msg=$msg'</script>";

	}



?>