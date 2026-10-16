<?php
session_start();

ob_start();

if($_SESSION['adminid']== "") {

	header("location:index.php");
	exit();

}

//add filters
if(isset($_POST['addFilters']))
{
	$courseActionFilter = trim($_POST['action']);
	$courseStatusFilter = trim($_POST['status']);
	$courseFromDateFilter = trim($_POST['fromDate']);
	$courseToDateFilter = trim($_POST['toDate']);

	if(!empty($courseActionFilter))
	{
		$_SESSION['courseActionFilter'] = strip_tags($courseActionFilter);
	}else{
		unset($_SESSION['courseActionFilter']);
	}
	
	if(!empty($courseStatusFilter))
	{
		$_SESSION['courseStatusFilter'] = strip_tags($courseStatusFilter);
	}else{
		unset($_SESSION['courseStatusFilter']);
	}
	
	
	if(!empty($courseFromDateFilter))
	{
		
		$_SESSION['courseFromDateFilter'] = strip_tags($courseFromDateFilter);
	}else{
		unset($_SESSION['courseFromDateFilter']);
	}
	
	if(!empty($courseToDateFilter))
	{
		
		$_SESSION['courseToDateFilter'] = strip_tags($courseToDateFilter);
	}else{
		unset($_SESSION['courseToDateFilter']);
	}
		
}

//Add Button  Filter
if(isset($_POST['addButtonFilters']))
{
	$buttonId = trim($_POST['buttonId']);

	if($buttonId == 'App')
	{
		$_SESSION['buttonFilter'] = 1;
	}else if($buttonId == 'Free')
	{
		$_SESSION['buttonFilter'] = 2;
	}else if($buttonId == 'Info')
	{
		$_SESSION['buttonFilter'] = 3;
	}else{
		$_SESSION['buttonFilter'] = 0;
	}
}

//Add No of records for page
if(isset($_POST['addNoOfRecords']))
{
	$_SESSION['noOfRecords'] = strip_tags($_POST['value']);
}
?>