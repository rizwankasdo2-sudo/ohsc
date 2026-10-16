<?php
session_start();
ob_start();
if($_SESSION['adminid']=="") {
    header("Location: index.php");
	exit;
}
include("classes/connection.php");

if(isset($_POST['actionId']) && !empty($_POST['actionId']))
{
$courseId = $_POST['infoId'];
$actionId = $_POST['actionId'];
$update_date = date('Y-m-d h:i:s');
	
	//mantain log of action
	$sql = "select action from tbl_info where infoid = $courseId";

    $query=$db->query($sql);
    $result= $query->fetch_array();
	if($result['action'] != $actionId)
	{
		
		$insert="insert into course_action_log (courseInfoId, courseActionId, dateTime) values($courseId, $actionId, '$update_date')";
		$query=$db->query($insert);
	}
	
	$update="update tbl_info set action='$actionId', update_date='$update_date' where infoid = $courseId";
	$query=$db->query($update);
	//print_r($update);
	exit;
	

}


//Update Status 
if(isset($_POST['statusId']) && !empty($_POST['statusId']))
{
$courseId = $_POST['infoId'];
$statusId = $_POST['statusId'];
$update_date = date('Y-m-d h:i:s');
	
	//mantain status log
	$sql ="select status from tbl_info where infoid = $courseId";
    $query=$db->query($sql);
    $result= $query->fetch_array();
	if($result['status'] != $statusId)
	{
		$insert="insert into course_status_log (courseInfoId, courseStatusId, dateTime) values($courseId, $statusId, '$update_date')";
		$query=$db->query($insert);

	}
	$update="update tbl_info set status='$statusId', update_date='$update_date' where infoid = $courseId";
	$query=$db->query($update);
	exit;
	

}







//Getting Phone Numbers
if(isset($_POST['phoneCourseId'])){


	$phoneId = $_POST['phoneCourseId'];
	$sql = "select mobile from tbl_info where infoid = $phoneId";
	//print_r($sql);
	//die();
    $query=$db->query($sql);
    $result= $query->fetch_array();
	echo $result['mobile'];
}

//Getting Email addresess
if(isset($_POST['emailCourseId'])){


	$emailId = $_POST['emailCourseId'];
	$sql = "select email from tbl_info where infoid = $emailId";
	//print_r($sql);
	//die();
    $query=$db->query($sql);
    $result= $query->fetch_array();
	echo $result['email'];
}


?>