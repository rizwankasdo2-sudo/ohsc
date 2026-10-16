<?php

session_start();
if($_SESSION['adminid']=="") {
    header("Location: index.php");
}
include("classes/connection.php");
if(isset($_POST['taskCourseId']) && !empty($_POST['taskCourseId']))
{
$id = $_POST['taskCourseId'];
$task = '';
$count = 1;
$sql = "select * from course_task where info_id = ".$id." order by completionDate desc";
$query=$db->query($sql);
$num= $query->num_rows;
if($num > 0)
{

while($results = $query->fetch_assoc())
{

	$fromDate = strtotime($results['completionDate']);
	$date = date('d-m-Y',$fromDate);
	$fromTime = strtotime($results['completionTime']);
	$time = date('h:i a',$fromTime);
	$status = '';
	if($results['status'] == 0)
	{
		$status .= '<a href="info.php?completeTask='.$results['id'].'">Un Complete</a>';
	}
	else
	{
		$status .= 'Completed';
	}
	
	$task.='	
			<tr>
			<td>'.$count.'</td>
			<td>'.$results['taskFor'].'</td>
			<td>'.$date.'</td>
			<td>'.$time.'</td>
			<td>'.$status.'</td>
			<td><a href="view_task.php?id='.$results['id'].'" target="_blank"> View</a></td>
			</tr>
			
	     
		
 
           	';
			$count++;
			
}
}

else
{
	$task .= "<p>No record found yet</p>";
}
echo $task;
}


//echo "Pakistan Is Best";

?>