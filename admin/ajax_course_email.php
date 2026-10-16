<?php
session_start();
ob_start();
if($_SESSION['adminid']=="") {
    header("Location: index.php");
}
include("classes/connection.php");

if(isset($_POST['h_course_id']) && !empty($_POST['h_course_id']))
{
$id = $_POST['h_course_id'];
$history = '';
$count = 1;
$historywithBody = '';
$sql = "select * from course_email_history where infoid = '$id' order by sendedDateTime desc";
//$sql = "select * from course_email_history";

//print_r($sql);

$query=$db->query($sql);
$num= $query->num_rows;
if($num > 0)
{	
while($results = $query->fetch_assoc()){

	$fromDate = strtotime($results['sendedDateTime']);
	$date = date('d-m-Y',$fromDate);
	$time = date('h:i:s	A',$fromDate);
	
	$history.='	
	<tr>
	<a href="view_email.php?id='.$results['id'].'" target="_blank">
	<td>'.$count.'</td>
	<td>'.$results['subject'].'</td>
	<td>'.$date.'</td>
	<td>'.$time.'</td>
	<td><a href="info.php?deleteEmail='.$results['id'].'">Delete </a>|<a href="view_email.php?id='.$results['id'].'" target="_blank">View</a></td>
	</a>
	</tr>';
	$count++;
			
}
}
else
{
	$history .= "<p>No record found yet</p>";
}
echo $history;

}

if(isset($_POST['temp_id']) && !empty($_POST['temp_id']))
{
$body = '';
$id = $_POST['temp_id'];
$course_id = $_POST['course_id'];

$sql = "select * from info where id = $course_id";
//print_r($sql);
$query=$db->query($sql);

$course_result = $query->fetch_assoc();
$rec_fullName = $course_result['firstname'].' '.$course_result['lastname'];
$rec_email = $course_result['email'];
$rec_payment_methd = $course_result['p_method'];

$course = $course_result['course'];
$course_query = "SELECT coursetitle FROM courses_new WHERE id='$course'";
$query=$db->query($course_query);
$course_info = $query->fetch_assoc();
$rec_course_title = $course_info['coursetitle'];

$sql = 'select body from course_email_template where id='.$id;
//print_r($sql);
$query=$db->query($sql);
$num= $query->num_rows;
$tmp_result = $query->fetch_assoc();
$body = $tmp_result['body'];

	$body = str_replace('{RECEIVER_NAME}',$rec_fullName,$body);
	$body = str_replace('{RECEIVER_EMAIL}',$rec_email,$body);
	$body = str_replace('{COURSE_INFO}',$rec_course_title,$body);
	$body = str_replace('{PAYMENT_METHOD}',$rec_payment_methd,$body);
echo $body;
}

if(isset($_POST['historyEmailId']) && !empty($_POST['historyEmailId']))
{
$id = $_POST['historyEmailId'];
$body = '';
$sql = "select * from course_email_history where id = ".$id;
$query=$db->query($sql);
$results = $query->fetch_assoc();
if(!empty($results))
{
	$fromDate = strtotime($result['sendedDateTime']);
	$date = date('d-m-Y',$fromDate);
	$time = date('h:i:s	A',$fromDate);
	
	$body.= '<div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><label>Subject:</label>'.$results['subject'].'</h4>
      </div>
      <div class="modal-body">
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade">
             <p>'.$results['body'].'</p> 	
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>';
			
}
else
{
	$history .= "<p>No record found </p>";
}
echo $history;

}

?>
