<?php
session_start();
ob_start();
if($_SESSION['adminid']=="") {
    header("location:index.php");
    exit;
}
  include("classes/connection.php");
if(isset($_POST['note_id']) && !empty($_POST['note_id']))
{
$id = $_POST['note_id'];
$note = '';
$count =1;	
$sql = "select * from course_notes where infoid = ".$id." order by addedOn desc";
$query=$db->query($sql);
$num= $query->num_rows;
if($num > 0){


//foreach ($results as $result ) 
	# code...
while($results = $query->fetch_assoc())
{
	$fromDate = strtotime($results['addedOn']);
	$date = date('d-m-Y',$fromDate);
	$time = date('h:i a',$fromDate);
	
	$note.='	
			<tr>
			<td>'.$count.'</td>
			<td>'.$results['addedBy'].'</td>
			<td>'.$time.'</td>
			<td>'.$date.'</td>
			<td><a href="info.php?deleteNote='.$results['id'].'">Delete </a>|<a href="view_note.php?id='.$results['id'].'" target="_blank"> View</a></td>
			</tr>'; 
			$count++;
			
}
}
else
{
	$note .= "<p>No record found yet</p>";
}
echo $note;

}
