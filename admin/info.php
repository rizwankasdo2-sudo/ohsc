<?php
session_start();
ob_start();
include("classes/connection.php");
include("includes/admin-header.php");
include("includes/admin-menu.php");

if($_SESSION['adminid']== "") {

	header("location:index.php");
	exit();

} ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>


<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Course Information</title>

<link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />

<link rel="stylesheet" href="../Resources/admin_css/products.css" />

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css" />

<script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>



<!--[if lte IE 6]>

	<script type="text/javascript" src="../Resources/admin_js/supersleight-min.js"></script>

<![endif]-->

<style>
    table
    {
        border-collapse: collapse;

    }
    table thead tr
    {
        background: #f5f5f5;
    }
    table td, table th
    {
        border: 1px solid #ddd;
        padding: 10px 10px 10px 10px;
        text-align: left;
        font-family: "Helvetica", "Arial", sans-serif;
        font-size: 13px;
    }
    table tr:nth-child(even)
    {
        background: #f5f5f5;
    }

</style>



</head >



<body>



 <div id="wrap_dashboard">

   

   <?php

  //update action and status as bulk/group
if(isset($_POST['submit_action']))
{
	$actionTop = $_POST['actiontop'];
	$statusTop = $_POST['status_foundtop'];
	$checkedList = $_POST['checked'];
	$update_date = date('Y-m-d h:i:s');
	foreach($checkedList as $key=>$check)
	{
		//mantain log of action and status
		 $actionUpate = false;
		 $statusUpdate = false;
		 $sql = "select action, status from tbl_info where id = $check";
		 $query=$db->query($sql);
		 $result = $query->fetch_assoc();
		 if($result['action'] != $actionTop)
		 {
			 $actionUpate = true;
		 }
		 if($result['status'] != $statusTop)
		 {
			 $statusUpdate = true;
		 }
		 if($actionUpate == true)
		 {
			
			 $insert="insert into course_action_log (courseInfoId, courseActionId, dateTime) values($check, $actionTop, '$update_date')";
			 $query=$db->query($insert);

		 }
		 if($statusUpdate == true)
		 {
			
			  $insert="insert into course_status_log (courseInfoId, courseStatusId, dateTime) values($check, $statusTop, '$update_date')";
			  $query=$db->query($insert);
		 }
		  
		 $sql = "update tbl_info set action='$actionTop', status='$statusTop', update_date='$update_date' where infoid=$check";
		 //print_r($sql);
		 //die();
		  $query=$db->query($sql);
		 
		 
	}
	header('location: info.php?msg=Action performed successfully!');
	exit;
	
}
if(isset($_POST['send_email']))
{
	if(isset($_POST['tmp']) && !empty($_POST['tmp']))
	{
	    $subject_id = trim($_POST['subject']);
		$sql = "select subject from course_email_template where id = $subject_id";
		$query=$db->query($sql);
		$subject_result = $query->fetch_array();
		$subject = $subject_result['subject'];
	}
	else{
		$subject = trim($_POST['subject']);
	}
	$body = trim($_POST['body']);
	$courseId = trim($_POST['courseId']);
	$datTime= date('Y-m-d H:i:s');
	if(empty($subject) || empty($body) || empty($courseId))
	{
		header('location: info.php?msg=<p style="color:red;"><strong>Error: </strong> Make sure to fill all fields before sending email</p>');
	exit;
	}
	$sql = "select email from tbl_info where infoid = $courseId";
	$query=$db->query($sql);
	
	$result =  $query->fetch_array();
	//send email
	$to = $result['email'];
    $subject = $subject;
    $message = $body;
    $from = "From: BOLC <info@bolc.co.uk>";
    //if (mail($to,$subject,$message,$from)) {
    //if (smtp_mailer($to,$subject,$message)) {
      if ($client_postmark->sendEmail('no-reply@oxfordhomestudy.com', $to,$subject,$message)) {
	//insert into database
	$sql= "insert into course_email_history (infoid,subject,body,sendedDateTime) values('$courseId','$subject','$body','$datTime')";

	 $query=$db->query($sql);
	 header('location: info.php?msg=Email sended successfully!');
	 exit;
	}
	else{
	    $sql ="insert into course_email_history (infoid,subject,body,sendedDateTime) values ('$courseId','$subject','$body','$datTime')";
		
		 $query=$db->query($sql);
	    header('location: info.php?msg=<p style="color:red;"><strong>Error: </strong> Website is not responding properly please try again later!</p>');
		exit;
	}
}
if(isset($_GET['deleteEmail']))
{
    $del = $_GET['deleteEmail'];
    $sql_delete = "DELETE FROM course_email_history WHERE id='$del'";
    $query=$db->query($sql_delete);
	header('location: info.php?msg=Email Deleted Successfully</p>');
	exit;
}
if(isset($_POST['save_note']))
{
	$added_by = trim($_POST['added_by']);
	$note = trim($_POST['note']);
	$courseId = trim($_POST['courseId']);
	$datTime= date('Y-m-d H:i:s');
	if(empty($added_by) || empty($note) || empty($courseId))
	{
		header('location: info.php?msg=<p style="color:red;"><strong>Error: </strong> Make sure to fill all fields before clicking save button</p>');
		exit;
	}
	 $sql= "insert into course_notes(infoid,addedBy,note,addedOn) values ('$courseId','$added_by','$note','$datTime')";
	 $query=$db->query($sql);
	  if($query)
	  {
	    header('location: info.php?msg=Note Added Successfully</p>');
	    //echo "Note Added Successfully";
		//exit;
	  }
	  else
	  {
		   header('location: info.php?msg=<p style="color:red;"><strong>Error: </strong> Website is not responding properly please try again later!</p>');
		   exit;
	  }
}
if(isset($_GET['deleteNote']))
{
    $del = $_GET['deleteNote'];
    $sql_delete = "DELETE FROM course_notes WHERE id='$del'";
    $query=$db->query($sql_delete);
	header('location: info.php?msg=Note Deleted Successfully</p>');
	exit;
}
//save tasks
if(isset($_POST['save_task']))
{
	$taskFor = trim($_POST['taskFor']);
	$taskDescription = trim($_POST['taskDescription']);
	$taskCourseId = trim($_POST['taskCourseId']);
	$completionDate= $_POST['completionDate'];
	$completionTime= $_POST['completionTime'];
	if(empty($taskFor) || empty($taskDescription) || empty($taskCourseId) || empty($completionDate) || empty($completionTime))
	{
		header('location: info.php?msg=<p style="color:red;"><strong>Error: </strong> Make sure to fill all fields before clicking save button</p>');
		exit;
	}
	 //check task status
	 $sql = "select count(id) from course_task where info_id = $taskCourseId and status = 0";
	 $query = $db->query($sql);
	 $result = $query->fetch_assoc();
	 if($result['count(id)'] > 0)
	 {
		 header('location: info.php?msg=<p style="color:red;"><strong>Error: </strong> Task not added because only one task will remain open at a time</p>');
		 exit;
	 }
	 else{
		 
	 $sql= "insert into course_task(info_id,taskFor,completionDate,completionTime,taskDescription) value('$taskCourseId','$taskFor','$completionDate','$completionTime','$taskDescription')";
	// print_r($sql);
	 //die();
	  if($db=$db->query($sql))
	  {
	    header('location: info.php?msg=Task Added Successfully</p>');
		exit;
	  }
	  else
	  {
		   header('location: info.php?msg=<p style="color:red;"><strong>Error: </strong> Website is not responding properly please try again later!</p>');
		   exit;
	  }
	 }
}
if(isset($_GET['completeTask']))
{
    $cmp = $_GET['completeTask'];
    $sql_delete = "update course_task set status=1 WHERE id='$cmp'";
    $delettask=$db->query($sql_delete);
	header('location: info.php?msg=Status Changed Successfully</p>');
	exit;
}



//Select data query
$query_builder = '';

	if(isset($_SESSION['courseActionFilter']) && !empty($_SESSION['courseActionFilter']))
	{
		$courseAction = $_SESSION['courseActionFilter'];
		$query_builder .= " AND action='$courseAction'";
	}
	
	if(isset($_SESSION['courseStatusFilter']) && !empty($_SESSION['courseStatusFilter']))
	{
		$courseStatus = $_SESSION['courseStatusFilter'];
		$query_builder .= " AND status='$courseStatus'";
	}
	
	if(isset($_SESSION['courseFromDateFilter']) && !empty($_SESSION['courseFromDateFilter']) && isset($_SESSION['courseToDateFilter']) && !empty($_SESSION['courseToDateFilter']))
	{
		$dateArray = explode('/',$_SESSION['courseFromDateFilter']);
		$courseFromDate = $dateArray[2].'-'.$dateArray[1].'-'.$dateArray[0];
		$dateArray1 = explode('/',$_SESSION['courseToDateFilter']);
		$courseToDate = $dateArray1[2].'-'.$dateArray1[1].'-'.$dateArray1[0];
		$query_builder .= " AND date(apply_date) BETWEEN '$courseFromDate' AND '$courseToDate'";
	}
	
		
	if(isset($_SESSION['buttonFilter']) && !empty($_SESSION['buttonFilter']))
	{
		$btnFilter = $_SESSION['buttonFilter'];
		if($btnFilter == 1)
		{
			$query_builder .= " AND form_type = 'Apply online' AND payment_method != 'Free Course'";
			//$query_builder .= " AND form_type  = 'Apply Online'";

		}
		else if($btnFilter == 2)
		{
			$query_builder .= " AND payment_method = 'Free Course'";
		}
		else if($btnFilter == 3)
		{
			$query_builder .= " AND form_type = 'Get Info'";
		}
	}
	else{
		$_SESSION['buttonFilter'] = 0;
	}
	
	
	if(isset($query_builder) && !empty($query_builder))
	{
		$where_clause = 'WHERE';
		$query_builder = substr($query_builder,4);
		$query_builder = $where_clause.$query_builder;
	}
// create  pagination
require "includes/pagination.php";
$page = 1;
$size = 50;
if(isset($_SESSION['noOfRecords']) && !empty($_SESSION['noOfRecords']))
{
	if($_SESSION['noOfRecords'] == 'All')
	{
		$size = 'All';
	}else{
		$size = $_SESSION['noOfRecords'];
	}
}
if (isset($_GET['page'])){
    $page = (int) $_GET['page'];
}
$sql = $db->query("SELECT COUNT(infoid) as totalRecords FROM tbl_info $query_builder");
$result = $sql->fetch_array();
$total_records = $result['totalRecords'];
if($size == 'All')
{
	$size = $total_records;
}
$pagination = new Pagination();
$pagination->setLink("?page=%s");
$pagination->setPage($page);
$pagination->setSize($size);

$pagination->setTotalRecords($total_records);
$navigation = $pagination->create_links();
$result_sql = "SELECT * FROM tbl_info $query_builder ORDER BY apply_date DESC ".$pagination->getLimitSql();
//print_r($result_sql);
//die();
$query=$db->query($result_sql);


?>

   <div id="outer_contanier">

     <?php
	if($_GET['msg']!="") { ?>
    <div class="check_main">
    <div class="check">
        <div class="good"><h4 style="color:green;"><?php echo $_GET['msg'];?></h4></div>
    </div>
    
	<?php } ?>
     <div id="board">

     

       <div id="left_corner">

       </div>

       

       <div id="midle_repeat">

         
		  
         <div id="productsheading" style="width:300px; margin-top:0px;">

         
	
           <h3>Course Information </h3>
           
           <br />

         

         </div>

         

         

       

       </div>

       

       <div id="right_corner">

       </div>

       

       <div id="main_content">

     

         <div id="productslistsearch">

           <div class="clear">&nbsp;</div>

           <div class="in" style="margin-top:0px">
        <div style="margin:10px 0px 25px 0px;">
        <a href="manage-action.php" class="btn btn-success" style="color:#fff">Manage Action</a>
        <a href="manage-status.php" class="btn btn-success" style="color:#fff">Manage Status</a>
        <a href="course_email_template.php"class="btn btn-success" style="color:#fff">Manage Email Templates</a>
        <a href="action-log.php"class="btn btn-success" style="color:#fff">Action Log</a>
        <a href="status-log.php"class="btn btn-success" style="color:#fff">Status Log</a>
        </div>
        <form action="" method="post" id="courseForm">
        <div class="filter_dates" style="float: left; margin-right: 3px;">
                <input type="text" id="fromDateFilter" name="date" class="form_field" placeholder="dd/mm/yyyy" onchange="addFilters();" value="<?php if(isset($_SESSION['courseFromDateFilter']) && !empty($_SESSION['courseFromDateFilter'])){echo $_SESSION['courseFromDateFilter'];} ?>">
                <input type="text" id="toDateFilter"name="date" placeholder="dd/mm/yyyy" onchange="addFilters();" value="<?php if(isset($_SESSION['courseToDateFilter']) && !empty($_SESSION['courseToDateFilter'])){echo $_SESSION['courseToDateFilter'];} ?>" class="form_field">
        </div>
        <div class="filter_dates">
        <select name="actionFilter" id="actionFilter" onchange="addFilters();" class="form_field">
         <option value="">Select Action</option>
         <?php
                        $actionResults = $db->query('select * from course_action');
						while($action = $actionResults->fetch_array())
						{
						?>

                        <option value="<?php echo $action['id']; ?>" <?php if(isset($_SESSION['courseActionFilter']) && !empty($_SESSION['courseActionFilter']) && $action['id'] == $_SESSION['courseActionFilter']){echo 'selected';} ?> ><?php echo $action['title']; ?></option>
                        <?php
						}
						?>
        </select>
        <select id="statusFilter" name="statusFilter" onchange="addFilters();" class="form_field">
        <option value="">Select Status</option>
         <?php          $statusResults = $db->query('select * from course_status');
						while($status = $statusResults->fetch_array())
						{
						?>
                        <option value="<?php echo $status['id']; ?>" <?php if(isset($_SESSION['courseStatusFilter']) && !empty($_SESSION['courseStatusFilter']) && $status['id'] == $_SESSION['courseStatusFilter']){echo 'selected';} ?>><?php echo $status['title']; ?></option>
                        <?php
						}
						?>
        </select>
        
        
        </div>
   
        <div class="tabs_button" style="clear:both; margin-bottom:10px;">
        <input type="button" name="allFilter" value="All" id="0" class="btn <?php if(isset($_SESSION['buttonFilter']) && $_SESSION['buttonFilter'] == 0){echo 'btn-primary'; } else{echo 'btn-default';}?> " onclick="addButtonFilters(this.value);"/>
        <input type="button" name="appFilter" value="App" id="1" class="btn <?php if(isset($_SESSION['buttonFilter']) && $_SESSION['buttonFilter'] == 1){echo 'btn-primary'; } else{echo 'btn-default';}?> " onclick="addButtonFilters(this.value);" />
         <input type="button" name="freeFilter" value="Free" id="2" class="btn <?php if(isset($_SESSION['buttonFilter']) && $_SESSION['buttonFilter'] == 2){echo 'btn-primary'; } else{echo 'btn-default';}?> " onclick="addButtonFilters(this.value);" />
        <input type="button" name="infoFilter" value="Info" id="3" class="btn <?php if(isset($_SESSION['buttonFilter']) && $_SESSION['buttonFilter'] == 3){echo 'btn-primary'; } else{echo 'btn-default';}?>" onclick="addButtonFilters(this.value);"/>
        </div>
        <div class="row">
        <div class="col-md-6">
        <div class="form-group">
            <label>No of records</label>
        	<select name="noRecords" id="noOfRecords" class="form-control" style="max-width:80px; display:inline;" onchange="setNoOfRecords(this.value);"> 
           <option value="50" <?php if(isset($_SESSION['noOfRecords'])&&$_SESSION['noOfRecords'] == 50){echo 'selected';} ?>>50</option>
            <option value="100" <?php if(isset($_SESSION['noOfRecords'])&&$_SESSION['noOfRecords'] == 100){echo 'selected';} ?>>100</option>
            <option value="1000" <?php if(isset($_SESSION['noOfRecords'])&&$_SESSION['noOfRecords'] == 1000){echo 'selected';} ?>>1000</option>
            <option value="All" <?php if(isset($_SESSION['noOfRecords'])&&$_SESSION['noOfRecords'] == 'All'){echo 'selected';} ?>>All</option>
            </select>
        </div>
        </div>
        <div class="col-md-6">
        <div class="filter_dates" style="text-align:right;">
        
            <select name="actiontop" class="form_field">
            <option values="">Select Action</option>
                    <?php
					        $actionResults = $db->query('select * from course_action');
    						while($action = $actionResults->fetch_array())
    						{
    						?>
                    <option value="<?php echo $action['id']; ?>"><?php echo $action['title']; ?></option>
                    <?php
    						}
    						?>
                </select>
                
            <select class="form_field " name="status_foundtop">
            <option values="">Select Status</option>
                    <?php
					        $statusResults = $db->query('select * from course_status');
    						while($status = $statusResults->fetch_array())
    						{
    						?>
                    <option value="<?php echo $status['id']; ?>"><?php echo $status['title']; ?></option>
                    <?php
    						}
    						?>
                    </select>
            <input name="submit_action" type="submit" id="submit" tabindex="5" class="com_btn" value="Apply" onclick="check(this)">
    </div>
        </div>
        </div>
        <table class="table table-striped table-bordered table-hover" id="example">
            <thead>
                <tr>
                    <th>Check</th>
                    <th>Type</th>
                    <th>Date</th>
                    <th>Course</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th title="Country">Country</th>
                    <th title="Payment">Pay</th>
                    <th title="Action">Act</th>
                    <th>Tasks</th>
                    <th>Updated</th>
                    <th>Status</th>
                    <th style="font-size:14px;"><span class="glyphicon glyphicon-envelope"></span></th>
                    <th style="font-size:14px"><span class="glyphicon glyphicon-list-alt"></span></th>
                </tr>
            </thead>
            <tbody>
            <?php
            while($row = $query->fetch_array()){
                $diplomaid = $row['diplomaid'];
                $course_query = $db->query("SELECT name FROM tbl_diploma WHERE diplomaid='$diplomaid'");
                $course_row = $course_query->fetch_array();
                $courseFound = $course_row['name'];
                ?>
                <tr> 
                    <td style="vertical-align:middle;"><input type="checkbox" name="checked[]" value="<?php echo $row['infoid']; ?>" /></td>
                    <td title="<?php echo $row['form_type']; ?>" style="vertical-align:middle; background-color:<?php if($row['form_type'] == 'Apply Online'){echo '#ef7701';}else{ echo '#337ab7';}?>"><?php if($row['form_type'] == 'Apply Online'){echo 'App';}else{echo 'Info';} ?></td>
                    <td style="vertical-align:middle;"><?php echo date('d/m/Y', strtotime($row['apply_date'])); ?></td>
                    <td style="vertical-align:middle;"><?php echo $courseFound; ?></td>
                    <td style="vertical-align:middle;"><?php echo $row['firstname']; ?>  <?php echo $row['lastname']; ?></td>
                    <td style="vertical-align:middle;">
                    <a href="" class="btn btn-default glyphicon glyphicon-envelope" title="<?php echo $row['email']; ?>" style="text-decoration:none;" data-toggle="modal" data-target="#emailModal" onclick="getEmail(<?php echo $row['infoid']; ?>);"></a>
                    
                    <span style="display:none"><?php echo $row['email']; ?></span>
                    </td>
	                <td style="vertical-align:middle;">
                    <a href="" class="btn btn-default glyphicon glyphicon-phone" title="<?php echo $row['mobile']; ?>" style="text-decoration:none;" data-toggle="modal" data-target="#phoneModal" onclick="getPhoneData(<?php echo $row['infoid']; ?>);"></a>


					<span style="display:none"><?php echo $row['mobile']; ?></span>
                    </td>
                    <td style="vertical-align:middle;"><span title="<?php echo $row['country']; ?>"><?php //echo strtoupper(substr($row['country'],0,2)); ?>
                    <?php echo $row['country']; ?>
                   
                    </td>
                    <td title="<?php echo $row['payment_method']; ?>" style="vertical-align:middle;"><?php if($row['payment_method'] == 'Installments'){echo 'Inst';}else if($row['payment_method'] == 'Full Fee'){echo 'Full';}else if($row['payment_method'] == 'Free Course'){echo 'Free';} ?></td>
                    
                    <td style="vertical-align:middle;">
                    <div class="update_button">
                    <select name="action[<?php echo $row['infoid']; ?>]" class="edit_button_top" onchange="updateAction(this.value,<?php echo $row['infoid']; ?>);">
                        <option values="">Select</option>
                    
                        <?php
						$actionResults = $db->query('select * from course_action');
						while($action = $actionResults->fetch_array())
						{
						?>

                        <option value="<?php echo $action['id'];?>" <?php if($row['action']== $action['id']){ echo 'selected'; } ?>><?php echo $action['title']; ?></option>
                        <?php

						}
						?>
                    </select>
                   <?php /*?> <button type="submit" name="updateAction[<?php echo $row['id']; ?>]" class="edit_button btn btn-primary btn-xs" value="">
                    <span class="glyphicon glyphicon-edit edit_button_glyph"></span>
                    </button><?php */?>
                    
                    </div>
                    </td>
                    <td style="text-align:center; vertical-align: middle;">
                    <?php
					$sql = $db->query("select completionDate,completionTime from course_task where info_id = ".$row['infoid']." and status = 0");
                    $tasks = $sql->fetch_array();
					
					$taskDate = strtotime($tasks['completionDate']);
					$taskTime = strtotime($tasks['completionTime']);
					$taskDatef = date('m/d/Y',$taskDate);
					$taskTimef = date('h:i a',$taskTime);
                    ?>
                    <a href="" class="" style="text-decoration:none;" data-toggle="modal" data-target="#taskModal" onclick="getTaskData(<?php echo $row['infoid']; ?>);"><?php if(empty($tasks)){echo 'Add Task';}else{echo $taskDatef.'<br>'; echo  $taskTimef;}?></a></td>
                     <td style="text-align:center; vertical-align: middle;">
					 <?php
					 $dbDate = strtotime($row['update_date']);
					 $orgDate = date('m/d/Y h:i a',$dbDate);
					 echo $orgDate; ?>
                     </td>
                    <td style="vertical-align:middle;">
                   
                    <div class="update_button">
                    <select name="status[<?php echo $row['infoid']; ?>]" class="edit_button_top" onchange="updateStatus(this.value, <?php echo $row['infoid']; ?>);">
                     <option values="null">Select</option>
                        <?php
						$statusResults = $db->query('select * from course_status');
    					while($status = $statusResults->fetch_array())
						{
						?>
                        <option value="<?php echo $status['id']; ?>" <?php if($row['status']== $status['id']){ echo 'selected'; } ?>><?php echo $status['title']; ?></option>
                        <?php
						}
						?>
                    </select>
                   <?php /*?> <button type="submit" name="updateStatus[<?php echo $row['id']; ?>]" class="edit_button btn btn-primary btn-xs" value="">
                    <span class="glyphicon glyphicon-edit edit_button_glyph"></span>
                    </button><?php */?>
                    </div>
                        <input type="hidden" class="idfound" value="<?php echo $row['infoid']; ?>"/>
                    </td>
                    <td style="text-align:center; vertical-align:middle"><a href="" class="glyphicon glyphicon-envelope btn btn-default btn-xs" style="text-decoration:none;" data-toggle="modal" data-target="#myModal" onclick="getEmailData(<?php echo $row['infoid']; ?>);"></a></td>
                    
                    <td style="text-align:center; vertical-align:middle"><a href="" class="glyphicon glyphicon-list-alt btn btn-default btn-xs" style="text-decoration:none;" data-toggle="modal" data-target="#noteModal" onclick="getNoteData(<?php echo $row['infoid']; ?>);"></a></td>
                    <?php /*?><td style="vertical-align:middle;"><a href="info.php?del=<?php echo $row['id']; ?>" class="btn btn-xs btn-danger" style="color:#fff;">Delete</a></td><?php */?>
                </tr>
                <?php
            } ?>
            </tbody>
        </table>
        </form>
        
         <div class="row">
                                    <div class="col-md-3">
                                        <div class="entries_info">
                                        <p>Showing <?php echo $pagination->getEnteriesStart(); ?> to <?php echo $pagination->getEnteriesEnd(); ?> of <?php echo $pagination->total_records; ?> entries</p>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div style="text-align:right; margin-top:23px;">
                                        <?php echo $navigation; ?>	
                                        </div>
                                    </div>
                                </div>
         
         
    </div>

         

         </div>

         

         <div class="clear">&nbsp;</div>

              

       </div>

     

     </div>
      <!-- Modal -->
    <!-- Email Modal -->
    <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Email</h4>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#newEmail" data-toggle="tab">New Email</a></li>
            <li><a href="#template" data-toggle="tab">Template</a></li>
            <li><a href="#viewEmails" data-toggle="tab">Sent</a></li>
        </ul>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="newEmail">
                <form action="" method="post">
                    <div class="form-group">
                    <label>Subject:</label>
                    <input type="text" name="subject" class="form-control" placeholder="Enter Subject Of Email" style="border-radius:0px;max-width:45%;" required="required" />
                    </div>
                    <div class="form-group">
                    <label>Body:</label>
                    <textarea name="body" class="form-control" placeholder="Enter Body Of Email" style="border-radius:0px;max-width:45%;" rows="5" required="required"></textarea>
                    <input type="hidden" name="courseId" id="courseId" value="" />
                    </div>
                    <div class="form-group">
                    <input name="send_email" type="submit" id="submit" tabindex="5" class="com_btn" value="Send">
                    </div>
                </form>
            </div>
            <div class="tab-pane fade" id="template">
           <?php
		   $sql = 'select id,subject from course_email_template';
		   $query=$db->query($sql);
		  
		   ?>
           <form action="" method="post">
                    <div class="form-group">
                    <label>Subject:</label>
                    <select name="subject" class="form-control" style="border-radius:0px;max-width:45%;" id="tmp_subject" onchange="changeBody(this.value);">
                   
                    	<option value="">Select Subject</option>
                         <?php while($templates = $query->fetch_assoc()) { ?>
					
                        <option value="<?php echo $templates['id']; ?>"><?php echo $templates['subject']; ?></option>
                         <?php 

                			}
                			?>
                      
                    </select>
                  
                    </div>
                    <div class="form-group">
                    <label>Body:</label>
                    <textarea name="body" class="form-control" placeholder="Enter Body Of Email" style="border-radius:0px;max-width:45%;" rows="5" id="tmp_body" required="required"></textarea>
                    <input type="hidden" name="courseId" id="courseId1" value="" />
                    <input type="hidden" name="tmp" value="12w2" />
                    </div>
                    <div class="form-group">
                    <input name="send_email" type="submit" id="submit" tabindex="5" class="com_btn" value="Send">
                    </div>
                </form>
               
            </div>
            
            <div class="tab-pane fade" id="viewEmails">
                <table class="table table-striped table-hover " style="max-width:45%;">
                    <thead>
                        <tr>
                        <th title="Serial Number">Sr.No</th>
                        <th title="Eiamil Subject">Subject</th>
                        <th title="Sended Date">Date</th>
                        <th title="Sended Time">Time</th>
                        <th title="Actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="accordion">
                   
                    </tbody>
                </table>  	
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
    <!-- Note Modal -->
    <div id="noteModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Note</h4>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs">
        <li class="active"><a href="#allNotes" data-toggle="tab">All Notes</a></li>
            <li ><a href="#newNote" data-toggle="tab">Add Note</a></li>
        </ul>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="allNotes">
                <table class="table table-striped table-hover " style="max-width:45%;">
                    <thead>
                        <tr>
                        <th title="Serial Number">Sr.No</th>
                        <th title="Note Added By">Added By</th>
                        <th title="Added Time">Time</th>
                        <th title="Added date">Date</th>
                        <th title="Actions">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="notesTable">
                   
                    </tbody>
                </table>  	
            </div>
            <div class="tab-pane fade " id="newNote">
                <form action="" method="post">
                    <div class="form-group">
                    <label>Added By:</label>
                    <input type="text" name="added_by" class="form-control" placeholder="Enter Your Name" style="border-radius:0px;max-width:45%;" required="required" />
                    </div>
                    <div class="form-group">
                    <label>Notes:</label>
                    <textarea name="note" class="form-control" placeholder="Enter Note Here" style="border-radius:0px;max-width:45%;" rows="5" required="required"></textarea>
                    <input type="hidden" name="courseId" id="noteCourseId" value="" />
                    </div>
                    <div class="form-group">
                    <input name="save_note" type="submit" id="submit" tabindex="5" class="com_btn" value="Save">
                    </div>
                </form>
            </div>
            
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
   <!-- tasks modal -->
   <div id="taskModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Tasks</h4>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#allTasks" data-toggle="tab">All Tasks</a></li>
            <li class=""><a href="#newTask" data-toggle="tab">Add Task</a></li>
        </ul>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="allTasks">
            <table class="table table-striped table-hover " style="max-width:45%;">
                    <thead>
                        <tr>
                        <th title="Serial Number">Sr.No</th>
                        <th title="Task For">Task For</th>
                        <th title="Completion Date">Date</th>
                        <th title="Completion Time">Time</th>
                        <th title="Task Status">Status</th>
                        <th title="View Detail">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tasksTable">
                   
                    </tbody>
                </table>  
               
            </div>
            
            <div class="tab-pane fade" id="newTask">
                <form action="" method="post">
                    <div class="form-group">
                    <label>Task For:</label>
                    <input type="text" name="taskFor" class="form-control" placeholder="Enter Name" style="border-radius:0px;max-width:45%;" required="required" />
                    </div>
                    <div class="form-group">
                    <label>Completion Date:</label>
                    <input type="date" name="completionDate" class="form-control" style="border-radius:0px;max-width:45%;"  required="required">
                    </div>
                    <div class="form-group">
                    <label>Completion Time:</label>
                    <input type="time" name="completionTime" class="form-control" style="border-radius:0px;max-width:45%;"  required="required">
                    </div>
                    <div class="form-group">
                    <label>Task Description:</label>
                    <textarea name="taskDescription" class="form-control" placeholder="Enter task detail here" style="border-radius:0px;max-width:45%;" rows="5" required="required"></textarea>
                    <input type="hidden" name="taskCourseId" id="taskCourseId" value="" />
                    </div>
                    <div class="form-group">
                    <input name="save_task" type="submit" id="submit" tabindex="5" class="com_btn" value="Save">
                    </div>
                </form>
            </div>
            
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
   <!-- Email modal -->
   <div id="emailModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Email</h4>
      </div>
      <div class="modal-body">
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="allTasks">
            <p id="courseEmail"></p>
            </div>            
        </div>
      </div>
      <div class="modal-footer">
         <button name="copyData" value="Copy" class="btn btn-primary" onclick="copyToClipboard('#courseEmail')">Copy Email</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
    <!-- Phone modal -->
    <div id="phoneModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Phone Number</h4>
      </div>
      <div class="modal-body">
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="allTasks">
            <p id="coursePhone"></p>  
               
            </div>            
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
     

     <?php include("includes/admin-footer.php"); ?>

   

   </div>

 

 
 
<script src="ajax/jquery-1.12.3.min.js"></script>
<script  async="async" type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"></script>

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript" >
 $(document).ready(function() { 
		
            $('#photoimg').live('change', function()			{ 
			           $("#preview").html('');
			    $("#preview").html('<img src="loader.gif" alt="Uploading...."/>');
			$("#imageform").ajaxForm({
						target: '#preview'
		}).submit();
		
			});
        }); 
</script>
<!-- Include Date Range Picker -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>

<script>
var jq = $.noConflict();
    jq(document).ready(function(){
        var date_input=jq('input[name="date"]'); //our date input has the name "date"
        var container=jq('.bootstrap-iso form').length>0 ? jq('.bootstrap-iso form').parent() : "body";
        date_input.datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
        })
    });
</script>
<script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.2.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.print.min.js"></script>

<script>

  $(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        "ordering": false,
        "bPaginate": false,
		"info":     false,
		 buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [ 1, 2, 3, 4, 5, 6, 7, 10]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6, 7, 10]
                }
            },
            {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 1, 2, 3, 4, 5, 6, 7, 10]
                }
            }
            
        ]
    } );
	 table.buttons( 0, null ).container().prependTo(
        table.table().container()
    );
} );
</script>


<script>
//Email Script
function getEmailData(val)
{
	document.getElementById('courseId').value = val;
	document.getElementById('courseId1').value = val;
	//alert(val);
	$.ajax({
	type:'POST',
	url: 'ajax_course_email.php',
	dataType:"text",
	data:'h_course_id='+val,
	success: function(result){
		document.getElementById('accordion').innerHTML = result;
		//document.getElementById('accordion').innerHTML = "Pakistan";

	}
	});
}
//Change Email template Body According to subject
function changeBody(val)
{
	
	var course_id = document.getElementById('courseId').value;
	$.ajax({
		type:'POST',
		url:"ajax_course_email.php",
		dataType:"text",
		data:{temp_id: val , course_id: course_id},
		success: function(results){
			document.getElementById('tmp_body').value = results;
		}
		});
		//alert('checingh');
}

//Notes script start
function getNoteData(val)
{
	document.getElementById('noteCourseId').value = val;
	$.ajax({
	type:'POST',
	url: 'ajax_course_notes.php',
	dataType:"text",
	data:'note_id='+val,
	success: function(result){
		document.getElementById('notesTable').innerHTML = result;
	}
	});
}
//Tasks script start 
function getTaskData(val)
{
	document.getElementById('taskCourseId').value = val;
	$.ajax({
	type:'POST',
	url: 'ajax_course_task.php',
	dataType:"text",
	data:'taskCourseId='+val,
	success: function(result){
		document.getElementById('tasksTable').innerHTML = result;
	}
	});
}
//get email data
function getEmail(val)
{
	$.ajax({
	type:'POST',
	url: 'ajax_update.php',
	dataType:"text",
	data:'emailCourseId='+val,
	success: function(result){
		document.getElementById('courseEmail').innerHTML = result;
	}
	});
}
//get email data
function getPhoneData(val)
{
	$.ajax({
	type:'POST',
	url: 'ajax_update.php',
	dataType:"text",
	data:'phoneCourseId='+val,
	success: function(result){
		document.getElementById('coursePhone').innerHTML = result;
		//document.getElementById('coursePhone').innerHTML = "Phone Number";
	}
	});
}
// update action
function updateAction(actionId, infoId)
{
	//alert(actionId);
	//alert(infoId);
	
	
	
	$.ajax({

		type:'POST',
		url:"ajax_update.php",
		dataType:"text",
		data:"actionId="+actionId+"&infoId="+infoId
		});
       
}
// update status
function updateStatus(statusId, infoId)
{
	//alert(statusId);
	//alert(infoId);
	
	$.ajax({
		type:'POST',
		url:"ajax_update.php",
		dataType:"text",
		data:"statusId="+statusId+'&infoId='+infoId      //unpridictable
		});
}
//check button
function fillCheckList()
{
	var val = 10;
	alert(val);
}
//copy text
function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    $temp.remove();
}

//Add Filters
function addFilters()
{
    var fromDate = document.getElementById('fromDateFilter').value;	
	var toDate = document.getElementById('toDateFilter').value;	
	var action = document.getElementById('actionFilter').value;
	var status = document.getElementById('statusFilter').value;
	$.ajax({
		type:'POST',
		url:"ajax_course_filters.php",
		dataType:"text",
		data:'addFilters=1'+'&fromDate='+fromDate+'&toDate='+toDate+'&action='+action+'&status='+status,
		success: function(){
			window.location.href = "info.php";
		}
		});
}

function addButtonFilters(val)
{
    var id = val;	
	$.ajax({
		type:'POST',
		url:"ajax_course_filters.php",
		dataType:"text",
		data:'addButtonFilters=1'+'&buttonId='+id,
		success: function(){
			window.location.href = "info.php";
		}
		});
}

function setNoOfRecords(val)
{
    var id = val;
	$.ajax({
		type:'POST',
		url:"ajax_course_filters.php",
		dataType:"text",
		data:'addNoOfRecords=1'+'&value='+id,
		success: function(){
			window.location.href = "info.php";
		}
		});
}
</script>



<script src="ajax/custom.js"></script>

</body>

</html>