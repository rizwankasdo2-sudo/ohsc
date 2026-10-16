<?php
session_start();
ob_start();
if($_SESSION['adminid']== "")
{
	header("location:index.php");
	exit();
}
	  
	  include("classes/connection.php");
	  if(isset($_POST['NewsTitle']) && isset($_POST['Newsdiscription']) && isset($_POST['NewsDetail']) && isset($_POST['status']))
{
		  $NewsTitle =   $_POST['NewsTitle'];
		  
		   $Newsdiscription = $_POST['Newsdiscription'];
		 
		    $NewsDetail = $_POST['NewsDetail'];
		  $status  =  $_POST['status'];
		 $qry="insert into tbl_news(INewsTitle,INewsDiscrition,INewsDetail,IStatus) values('$NewsTitle','$Newsdiscription','$NewsDetail','$status')";
		  $db->query($qry) or die("insert query error").mysqli_error($db);
		  echo $msg = "News Inserted Successfully";
		echo "<script language='javascript'>window.location='news.php?msg=$msg'</script>";
		   }
		 
	

else{
	$msg="you cant pass empty fields";
	
	echo "<script language='javascript'>window.location='news.php?msg=$msg'</script>";
	}

?>