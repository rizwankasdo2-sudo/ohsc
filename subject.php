<?php
ob_start();
session_start();
include('classes/front.php');
include('classes/file.php');
include('classes/news.php');
// Include EndUser class
include('classes/enduser.php');
// Include General class
include('classes/general.php');
require_once('inc/constants.php');
if(!$_SESSION['verified']){
    $front->redirect($base.'login.php');
}
$user_id = $_SESSION['user_id'];
$course_id = trim(mysqli_real_escape_string($db, filter_var($_GET['id'], FILTER_SANITIZE_STRING)));

// Create object of EndUser class
$end_user = new EndUser($db, $user_id);
// Create object of General class
$general = new General($db);

// Get number of related helping materials
$help_count = $general->getHelpingMaterialCount($course_id);
// Get number of related recommended readings
$readings_count = $general->getRecommendedReadingCount($course_id);
// Get number of uploaded assignments
$upassign_count = $general->getUploadedAssignmentsCount($user_id, $course_id);

//validate user course
$result_validate = $front->validateUserCourse($course_id, $user_id);
if($result_validate['count(id)'] != 1)
{
    $front->redirect($base.'courses.php');
}
$result_menu = $front->get_menu();

$s_front = clone $front;

$s_front->table = "tbl_category";
$result_category = $s_front->get_category();
$s_front->table = "tbl_aboutus";
$aboutus = $s_front->get_all();

$userFound = $front->findByThis('customer_id', $id, 'personal_details');
$page = $front->findByThis('id', 3, 'pages');

$cid = trim(mysqli_real_escape_string($db, filter_var($_GET['id'], FILTER_SANITIZE_STRING)));

$sql3 = $db->query("SELECT * FROM  courses where id = '$cid'");
$r = $sql3->fetch_array();

?>
 <?php
 //Upload New Assignment
 if (isset($_POST['upAssignSubmit'])) {
    $errors = 0;
	$user_id = $_SESSION['user_id'];
	$upAssignNo = trim(mysqli_real_escape_string($db, filter_var($_POST['upAssignNo'], FILTER_SANITIZE_STRING)));
	if (empty($upAssignNo)) {
	    $errors = 1;
        $_SESSION['errorMessg'] = 'Please fill all fields and then submit';
	}
	if (!is_numeric($upAssignNo)) {
	    echo 'ERROR: Assignment no should be number'; exit();
	}
	// Begin check if assignment number is less than 1
	if ($upAssignNo <= 0 || filter_var($upAssignNo, FILTER_VALIDATE_INT) === false) {
	    $errors = 1;
	    $_SESSION['errorMessg'] = 'ERROR: Assignment no should be an integer and must be greater than 0';
	}
	// End check if assignment number is less than 1
	// Begin check if assignment number has more than 2 digits
	if (strlen(trim($upAssignNo)) > 2) {
	    $errors = 1;
	    $_SESSION['errorMessg'] = 'ERROR: Assignment no should not contain more than 2 digits';
	}
	// End check if assignment number has more than 2 digits
	$upAssignTopic = trim(mysqli_real_escape_string($db, filter_var($_POST['upAssignTopic'], FILTER_SANITIZE_STRING)));
	if (empty($upAssignTopic)) {
	    $errors = 1;
		$_SESSION['errorMessg'] = 'Please fill all fields and then submit';
	}
	// Begin check if topic contains any number
	/*if (preg_match('#[0-9]#', $upAssignTopic)) {
	    $errors = 1;
	    $_SESSION['errorMessg'] = 'Assignment topic should not contain any numbers';
	}*/
	// End check if topic contains any number
	$fileName = str_replace('#', '-', trim($_FILES['upAssignFile']['name']));
	// Begin check if assignment with same number already submitted
	if ($end_user->checkExistingAssignment($course_id, intval($upAssignNo))) {
	    $errors = 1;
	    $_SESSION['errorMessg'] = 'Assignment already submitted with same number';
	}
	// End check if assignment with same number already submitted
	if (empty($fileName)) {
	    $errors = 1;
		$_SESSION['errorMessg'] = 'Please fill all fields and then submit';
	} elseif ($errors == 0) {
	    $fileName = time().$fileName;
    	$tmpName =  $_FILES['upAssignFile']['tmp_name'];
    	$dir = 'materials/assignment/upload/';
    	$data = array('tmp_name'=>$tmpName,'name'=>$fileName, 'folder'=>$dir);
	    if (!File::upload($data)) {
	        $errors = 1;
	        $_SESSION['errorMessg'] = 'Please select only from .doc, .docx, .xls, .xlsx, .txt, .pdf';
	    }
        if (!file_exists($dir . $fileName)) {
            $errors = 1;
	        $_SESSION['errorMessg'] = 'Assignment not uploaded. Please try again.';
        }
	}
	 
    if ($errors == 0) {
        $sql = "INSERT INTO upload_assignments (user_id, course_id, assign_no, assign_topic, file, status, upload_date) values($user_id, $course_id, '$upAssignNo', '$upAssignTopic', '$fileName', '5', NOW())";
		if (!$db->query($sql)) {
    	    $_SESSION['errorMessg'] = 'Website is not responding properly please try again later';
    		header("location:subject.php?id=$course_id&upload-assignment=1");
    		exit;
        }
        // Begin auto-authorizing next assignment
        $auto_done = 'no';
        $attempt_count = 0;
        while ($auto_done == 'no' && $attempt_count < 50) {
            $attempt_count++;
            $temp_result = $db->query("SELECT id FROM assignments WHERE course_id = '$course_id' AND assign_no = '$upAssignNo' AND status = '1' ");
            if ($temp_row = $temp_result->fetch_array()) {
                $auto_done = 'yes';
                $general->authorizeNextAssignment($user_id, $course_id, $upAssignNo, $temp_row['id']);
            } else {
                $upAssignNo = $upAssignNo + 1;
            }
        }
        // End auto-authorizing next assignment
        // Begin auto-authorizing next study material
        $auto_done = 'no';
        $attempt_count = 0;
        while ($auto_done == 'no' && $attempt_count < 50) {
            $attempt_count++;
            $temp_result = $db->query("SELECT id FROM study_materials WHERE course_id = '$course_id' AND topic_no = '$upAssignNo' AND status = '1' ");
            if ($temp_row = $temp_result->fetch_array()) {
                $auto_done = 'yes';
                $general->authorizeNextStudyMaterial($user_id, $course_id, $upAssignNo, $temp_row['id']);
            } else {
                $upAssignNo = $upAssignNo + 1;
            }
        }
        // End auto-authorizing next study material
		$_SESSION['successMessg'] = 'Assignment uploaded successfully';
		header("location:subject.php?id=$course_id&upload-assignment=1");
		exit;
    } else {
        header("location:subject.php?id=$course_id&upload-assignment=1");
		exit;
    }
}
/*if (isset($_POST['e_upAssignSubmit'])) {
    $date=date('Y-m-d h:i:s', time());
 	$sql="";
 	$e_id=$_POST['e_id'];
	$errors = 0;
	$user_id = $_SESSION['user_id'];
	$upAssignNo = trim($_POST['e_upAssignNo']);
	if(!is_numeric($upAssignNo)){
	    echo 'ERROR: Assignment no should be number'; exit();
	}
	if(!is_numeric($upAssignNo)){
		echo 'ERROR: Assignment no should be number'; exit();
	}
	$sql = $db->query("select * from upload_assignments where id = '$e_id'");
	$upStatus_id = $sql->fetch_array();
	$up_status_id=$upStatus_id['status'];
	$sql = $db->query("select * from assignment_status where id = '$up_status_id'");
	$upStatus = $sql->fetch_array();
	$up_status=$upStatus['title'];
	
	if(empty($upAssignNo)) {
	    $errors = 1;
		$_SESSION['errorMessg'] = 'Please fill all fields and then submit';
	}
	// Begin check if assignment number is less than 1
	if ($upAssignNo <= 0 || filter_var($upAssignNo, FILTER_VALIDATE_INT) === false) {
	    $errors = 1;
	    $_SESSION['errorMessg'] = 'ERROR: Assignment no should be an integer and must be greater than 0';
	}
	// End check if assignment number is less than 1
	// Begin check if assignment number has more than 2 digits
	if (strlen(trim($upAssignNo)) > 2) {
	    $errors = 1;
	    $_SESSION['errorMessg'] = 'ERROR: Assignment no should not contain more than 2 digits';
	}
	// End check if assignment number has more than 2 digits
	$upAssignTopic = trim($_POST['e_upAssignTopic']);
	if (empty($upAssignTopic)) {
	    
		$errors = 1;
		$_SESSION['errorMessg'] = 'Please fill all fields and then submit';
	}
	// Begin check if topic contains any number
	if (preg_match('#[0-9]#', $upAssignTopic)) {
	    $errors = 1;
	    $_SESSION['errorMessg'] = 'Assignment topic should not contain any numbers';
	}
	// End check if topic contains any number
	// Begin check if an assignment with same assignment number already exists
	$temp_result = $db->query("SELECT id FROM upload_assignments WHERE assign_no = '$upAssignNo' AND user_id = '" . $_SESSION['user_id'] . "' AND course_id = '" . $_GET['id'] . "' AND status <> '7' AND id <> '$e_id' ");
    $duplicate_row = $temp_result->fetch_array();
	if ($duplicate_row) {
		$errors = 1;
	    $_SESSION['errorMessg'] = 'Assignment with same number already exists.';
	}
	// End check if an assignment with same assignment number already exists
	$fileName = str_replace('#', '-', trim($_FILES['e_upAssignFile']['name']));
	if (empty($fileName)) {
	    if ($up_status == "Submitted") {
	        $sql = "UPDATE upload_assignments set assign_no='$upAssignNo', assign_topic='$upAssignTopic', upload_date='$date' where id='$e_id'";
		} else {
		    
		    exit(':ERROR');
		}
	} else {
        $fileName = time().$fileName;
    	$tmpName =  $_FILES['e_upAssignFile']['tmp_name'];
    	$dir = 'materials/assignment/upload/';
    	$data = array('tmp_name'=>$tmpName,'name'=>$fileName, 'folder'=>$dir);
    	if(!File::upload($data)) {
    	    $errors = 1;
    		$_SESSION['errorMessg'] = 'Please select only from .doc, .docx, .xls, .xlsx, .txt, .pdf';
    	} else {
    	    if ($up_status=="Submitted") {
                $sql = "UPDATE upload_assignments set assign_no='$upAssignNo', assign_topic='$upAssignTopic', file='$fileName', upload_date='$date' where id='$e_id'";
	 	    } else {
	 	        exit(':ERROR');
	 	    }
	 	}
    
	}
	
	if ($errors == 0) {
	    if (!$db->query($sql)) {
	        $_SESSION['errorMessg'] = 'Website is not responding properly please try again later';
		    header("location:subject.php?id=$course_id&upload-assignment=1");
		    exit;
        }
		$_SESSION['successMessg'] = 'Assignment Updated successfully';
		header("location:subject.php?id=$course_id&upload-assignment=1");
		exit;
    } else {
        header("location:subject.php?id=$course_id&upload-assignment=1");
		exit;
	}
 }*/
 
// Begin rating handling
$temp_result = $db->query("SELECT * FROM rating WHERE course_id = '" . trim(mysqli_real_escape_string($db, filter_var($_GET['id'], FILTER_SANITIZE_STRING))) . "' AND user_id = '" . $_SESSION['user_id'] . "' ");
$rating_row = $temp_result->fetch_array();
// Begin add rating
if (isset($_POST['star'])) {
	 // If current user has not yet rated this course
	 if (!$rating_row) {
		 $star = trim(mysqli_real_escape_string($db, filter_var($_POST['star'], FILTER_SANITIZE_STRING)));
		 $comments = trim(mysqli_real_escape_string($db, filter_var($_POST['comments'], FILTER_SANITIZE_STRING)));
		 // Check for empty values
		 if ($star != '' && $comments != '') {
			 // Make database entry
			 $db->query("INSERT INTO rating SET user_id = '" . $_SESSION['user_id'] . "', course_id = '" . trim(mysqli_real_escape_string($db, filter_var($_GET['id'], FILTER_SANITIZE_STRING))) . "', star = '$star', comments = '$comments' ");
			 // Set success message
			 $_SESSION['successMessg'] = 'Rating submitted successfully.';
			 // Get row containing currently submitted rating
			 $temp_result = $db->query("SELECT * FROM rating WHERE course_id = '" . trim(mysqli_real_escape_string($db, filter_var($_GET['id'], FILTER_SANITIZE_STRING))) . "' AND user_id = '" . $_SESSION['user_id'] . "' ");
         $rating_row = $temp_result->fetch_array();
		 } else { // If any value is empty
			 // Set error message
			 $_SESSION['errorMessg'] = 'Please fill in all the fields properly.';
		 }
	 } else { // If already rated
		 // Set error message
		 $_SESSION['errorMessg'] = 'You cannot rate this course at the moment.';
	 }
}
// End add rating
// End rating handling

// Begin MCQs based assignment checking
if (isset($_POST['mcqAssignSubmit'])) {
    $assign_id = trim(mysqli_real_escape_string($db, filter_var($_POST['assign_id'], FILTER_SANITIZE_STRING)));
    $exam_questions = trim(mysqli_real_escape_string($db, filter_var($_POST['exam_questions'], FILTER_SANITIZE_STRING)));
    $assign_no = trim(mysqli_real_escape_string($db, filter_var($_POST['assign_no'], FILTER_SANITIZE_STRING)));
    $topic = trim(mysqli_real_escape_string($db, filter_var($_POST['topic'], FILTER_SANITIZE_STRING)));
    $correct_answers = 0;
    // Check if assignment already cleared
    $existing_check_result = $db->query("SELECT id FROM upload_assignments WHERE user_id = '$user_id' AND course_id = '$course_id' AND assign_id = '$assign_id' ");
    $existing_check_row = $existing_check_result->fetch_array();
    if (!$existing_check_row) {
        for ($i = 1; $i <= $exam_questions; $i++) {
            $question_subscript = 'question' . $i;
            $answer_subscript = 'answer' . $i;
            $check_result = $db->query("SELECT id FROM assignment_question_options WHERE question_id = '" . trim(mysqli_real_escape_string($db, filter_var($_POST[$question_subscript], FILTER_SANITIZE_STRING))) . "' AND id = '" . trim(mysqli_real_escape_string($db, filter_var($_POST[$answer_subscript], FILTER_SANITIZE_STRING))) . "' AND correct = '1' ");
            if ($check_row = $check_result->fetch_array()) {
                $correct_answers++;
            }
        }
        //$percent = $exam_questions * (40 / 100);
        $percent = round(($correct_answers * 100) / $exam_questions);
        //if ($correct_answers >= round($percent)) {
        if ($percent >= 40) {
            if ($percent <= 59) {
                $overall_result = 'Pass';
            } else if ($percent <= 69) {
                $overall_result = 'Good';
            } else if ($percent <= 89) {
                $overall_result = 'Very Good';
            } else {
                $overall_result = 'Excellent';
            }
            $db->query("update course_assign_user_auth set status = '4' where user_id = '$user_id' and course_id = '$course_id' and assign_id = '$assign_id' ");
            //$sql = "INSERT INTO upload_assignments (user_id, course_id, assign_no, assign_topic, file, status, upload_date) values($user_id, $course_id, '$upAssignNo', '$upAssignTopic', '$fileName', '5', NOW())";
            //$db->query("INSERT INTO upload_assignments SET status = '$up_assign_status_id' ");
            $db->query("INSERT INTO upload_assignments (user_id, course_id, assign_no, assign_topic, status, upload_date, result, result_date, overall_result, feedback_date, note, assign_id) values($user_id, $course_id, '$assign_no', '$topic', '4', NOW(), '1', NOW(), '$overall_result', NOW(), '$overall_result', '$assign_id')");
            // Begin auto-authorizing next assignment
            $auto_done = 'no';
            $attempt_count = 0;
            while ($auto_done == 'no' && $attempt_count < 50) {
                $attempt_count++;
                $temp_result = $db->query("SELECT id FROM assignments WHERE course_id = '$course_id' AND assign_no = '$assign_no' AND status = '1' ");
                if ($temp_row = $temp_result->fetch_array()) {
                    $auto_done = 'yes';
                    $general->authorizeNextAssignment($user_id, $course_id, $assign_no, $temp_row['id']);
                } else {
                    $assign_no = $assign_no + 1;
                }
            }
            // End auto-authorizing next assignment
            // Begin auto-authorizing next study material
            $auto_done = 'no';
            $attempt_count = 0;
            while ($auto_done == 'no' && $attempt_count < 50) {
                $attempt_count++;
                $temp_result = $db->query("SELECT id FROM study_materials WHERE course_id = '$course_id' AND topic_no = '$assign_no' AND status = '1' ");
                if ($temp_row = $temp_result->fetch_array()) {
                    $auto_done = 'yes';
                    $general->authorizeNextStudyMaterial($user_id, $course_id, $assign_no, $temp_row['id']);
                } else {
                    $assign_no = $assign_no + 1;
                }
            }
            // End auto-authorizing next study material
            // Begin check if all assignments have been completed
            if ($general->checkAllAssignComp($user_id, $course_id)) {
                // Check if auto-complete allowed by super admin and course is a free course
                if (($general->validateFreeCourse($course_id) || $general->validateFreeCourseLocal($course_id)) && $general->validateAutoComplete()) {
                    // Set course status to completed
                    $db->query("UPDATE course_user SET comp_date = '" . date('Y-m-d') . "', course_status='6' WHERE user_id = '$user_id' AND course_id = '$course_id' ");
                    /* Issue Certificate */
                    $course_type_result = $db->query("SELECT type, paid_course FROM courses WHERE id = '$course_id'");
                    if ($course_type_result && $course_type_result->num_rows > 0) {
                        $course_data = $course_type_result->fetch_array();
                        if ($course_data['type'] == 3 && $course_data['paid_course'] == 1) {
                            $issued_date = date('Y-m-d');
                            $general->issueCertificate($user_id, $course_id);
                        }
                    }
                    /* Send notification */
                	$mail_array = $end_user->sendNotification(0, 'Your Online Course Information Updated', "Your online course information has been updated. Following is the updated information.<br /><br />Course Name: " . $news->getParticularField('title', 'courses', 'id', $course_id) . "<br />Status: Completed");
                	$client_postmark->sendEmail($mail_array['0'], $mail_array['1'], $mail_array['2'], $mail_array['3']);
                    // Begin certificate addition
                    /*if ($general->validateFreeCourse($course_id)) {
                	    //$general->AddCourseCertificatePDF($user_id, $course_id);
                    }*/
                    // End certificate addition
                }
                // Begin sending course completion email for free courses
                if (!$general->checkPaidCourse($course_id)) {
                    $template_result = $db->query("SELECT subject, body FROM email_templates WHERE id = '24' ");
                    $template_row = $template_result->fetch_array();
                    $client_postmark->sendEmail('no-reply@oxfordhomestudy.com', $end_user->getUserEmail(), addslashes($template_row['subject']), addslashes($template_row['body']));
                }
                // End sending course completion email for free courses
                // Begin sending admin selected email on course completion
                $template_row = '';
                $new_quantity_sent = 1;
                $email_settings_row = $db->query("SELECT * FROM send_emails_settings WHERE id = '1' ")->fetch_array();
                $current_template_id = $email_settings_row['current_template_id'];
                if ($email_settings_row['current_template_id'] != '0' && ($email_settings_row['quantity_sent'] < $email_settings_row['change_template_after'])) {
                    $current_template_id = $email_settings_row['current_template_id'];
                    $template_row = $db->query("SELECT * FROM email_templates WHERE id = '$current_template_id' AND course_completion = '1' ")->fetch_array();
                    $new_quantity_sent = $email_settings_row['quantity_sent'] + 1;
                }
                if (!$template_row) {
                    $template_row = $db->query("SELECT * FROM email_templates WHERE id > '$current_template_id' AND course_completion = '1' ORDER BY id ASC ")->fetch_array();
                    $current_template_id = $template_row['id'];
                    $new_quantity_sent = 1;
                }
                if (!$template_row) {
                    $template_row = $db->query("SELECT * FROM email_templates WHERE course_completion = '1' ORDER BY id ASC ")->fetch_array();
                    $current_template_id = $template_row['id'];
                    $new_quantity_sent = 1;
                }
                if ($template_row) {
                    $client_postmark->sendEmail('no-reply@oxfordhomestudy.com', $end_user->getUserEmail(), $template_row['subject'], $template_row['body']);
                    $db->query("UPDATE send_emails_settings SET current_template_id = '$current_template_id', quantity_sent = '$new_quantity_sent' WHERE id = '1'");
                }
                // End sending admin selected email on course completion
            }
            // End check if all assignments have been completed
            $_SESSION['successMessg'] = 'Congratulations, you have passed the assignment.';
        } else {
            $_SESSION['errorMessg'] = 'You are failed. Please make another attempt.';
        }
    }
}
// End MCQs based assignment checking
?>

<!doctype html>
<html>
<head>
    <!-- Begin Google Adsense code -->
	<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8850775732328921"
     crossorigin="anonymous"></script>
	<!-- End Google Adsense code -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $r['title']; ?></title>
<link rel="shortcut icon" type="image/jpg" href="img/favicon.jpg">
<meta name="keywords" content="<?php echo $page['meta_keywords']; ?>">
<meta name="description" content="<?php echo $page['meta_description']; ?>">
<link rel="stylesheet" type="text/css" href="css/assessment.css?v=<?php echo VER; ?>" />

    <?php include('header.php');?>
    <style>
    .modal-dialog{
        overflow-y: initial !important
    }
    .modal-body{
        max-height: 80vh;
        overflow-y: auto;
    }
    </style>
    <div class="br_container"></div>
    <?php include('bars.php'); ?>
    <div class="container">
        <div class="row">
             <div class="col-md-3 col-sm-12 col-xs-12 quick">
                <?php include('user_sidebar.php'); ?>
            </div>
             <div class="col-md-9 col-sm-12 col-xs-12 mt">
             	<?php include('user/error-messages/error.php'); ?>
                <div>
                 <!-- Course id used for redirection of page -->
                <input type="hidden" name="course-id" id="course-id" value="<?php echo $course_id; ?>" style="display:none">
                </div>
                <ul class="breadcrumb my_style_breadcrumb">
                    <li><a href="<?php echo $base; ?>welcome.php">Welcome</a><span>/</span></li>
                    <li><a href="<?php echo $base; ?>courses.php">Courses</a><span>/</span></li>
                    <li class="active"><?php echo $r['title']; ?></li>
                </ul>
                <div class="text-left" style="float:left;">Tutor Name: <?php echo $r['tutor']; ?></div>
                <div class="text-right uploadtxt" style="float:right;"><a href="#" class="uploadtxt" data-toggle="modal" data-target="#reviewCourseModal">Rate This Course</a> | <a href="#" class="uploadtxt" data-toggle="modal" data-target="#uploadAssignModal">+ Upload Assignment</a><br />&nbsp; </div>
                <div style="clear:both;max-height:1px !important;">&nbsp;</div>
                <ul class="nav nav-tabs">
                    <li class="<?php if(isset($_GET['study-material'])){echo 'active';} ?>" style=" border: 5px; border-color: red;"><a href="#study-material" data-toggle="tab" style="margin-right: 5px;  border-left: 1px solid #ddd; border-right: 1px solid #ddd; padding: 10px; border-color: #1687CE;" >Study Material</a></li>
                    <li class="<?php if(isset($_GET['assignments'])){echo 'active';} ?>"><a href="#assignments" data-toggle="tab" style="  border-left: 1px solid #ddd; border-right: 1px solid #ddd; padding: 10px; border-color: #1687CE;">Assignments</a></li>
                    <?php if ($help_count > 0) { ?>
                    <li class="<?php if(isset($_GET['helping-material'])){echo 'active';} ?>"><a href="#helping-material" data-toggle="tab" style="  border-left: 1px solid #ddd; border-right: 1px solid #ddd; padding: 10px; border-color: #1687CE;">Helping Material</a></li>
                    <?php
                    }
                    if ($readings_count > 0) {
                    ?>
                    <li class="<?php if(isset($_GET['recommended-reading'])){echo 'active';} ?>"><a href="#recommended-reading" data-toggle="tab" style="  border-left: 1px solid #ddd; border-right: 1px solid #ddd; padding: 10px; border-color: #1687CE;">Recommended Reading</a></li>
                    <?php
                    }
                    if ($upassign_count > 0) {
                    ?>
                    <li class="<?php if(isset($_GET['upload-assignment'])){echo 'active';} ?>"><a href="#upload-assignment" data-toggle="tab" style="  border-left: 1px solid #ddd; border-right: 1px solid #ddd; padding: 10px; border-color: #1687CE;">Assignment Feedback</a></li>
                    <?php } ?>
                </ul>
                <div id="myTabContent" class="tab-content">
                	<?php
					include('user/tabs/study-material.php');
					include('user/tabs/assignments.php');
					if ($help_count > 0) {
					    include('user/tabs/helping-material.php');
					}
					if ($readings_count > 0) {
					    include('user/tabs/recommended-reading.php');
					}
					if ($upassign_count > 0) {
					    include('user/tabs/upload-assignment.php');
					}    
					?>
                </div>
            </div>
        </div>
    </div>
<?php include('footer.php');?>
<!-- Begin Add Review Modal -->
<div id="reviewCourseModal" class="modal fade" role="dialog">

    <div class="modal-dialog">

        <!-- Modal content-->

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title">Rate This Course</h4>

            </div>

            <div class="modal-body">
				<form name="reviewCourse" method="post">

                    <div class="form-group">
						<?php if ($rating_row) { ?>You have given following rating to this course. <br /><br /><?php } ?>
                        <label><?php if (!$rating_row) { ?>Select <?php } ?>Your Rating</label><br />
						<?php if ($rating_row) { ?>
						<input type="radio" name="star" value="1" <?php echo ($rating_row['star'] == 1) ? 'checked' : ''; ?> disabled="disabled">&nbsp;&nbsp;&nbsp;<span class="rating-static rating-10"></span><br />
						<input type="radio" name="star" value="2" <?php echo ($rating_row['star'] == 2) ? 'checked' : ''; ?> disabled="disabled">&nbsp;&nbsp;&nbsp;<span class="rating-static rating-20"></span><br />
						<input type="radio" name="star" value="3" <?php echo ($rating_row['star'] == 3) ? 'checked' : ''; ?> disabled="disabled">&nbsp;&nbsp;&nbsp;<span class="rating-static rating-30"></span><br />
						<input type="radio" name="star" value="4" <?php echo ($rating_row['star'] == 4) ? 'checked' : ''; ?> disabled="disabled">&nbsp;&nbsp;&nbsp;<span class="rating-static rating-40"></span><br />
						<input type="radio" name="star" value="5" <?php echo ($rating_row['star'] == 5) ? 'checked' : ''; ?> disabled="disabled">&nbsp;&nbsp;&nbsp;<span class="rating-static rating-50"></span>
						<?php } else { ?>
						<input type="radio" name="star" value="1">&nbsp;&nbsp;&nbsp;<span class="rating-static rating-10"></span><br />
						<input type="radio" name="star" value="2">&nbsp;&nbsp;&nbsp;<span class="rating-static rating-20"></span><br />
						<input type="radio" name="star" value="3">&nbsp;&nbsp;&nbsp;<span class="rating-static rating-30"></span><br />
						<input type="radio" name="star" value="4">&nbsp;&nbsp;&nbsp;<span class="rating-static rating-40"></span><br />
						<input type="radio" name="star" value="5" checked>&nbsp;&nbsp;&nbsp;<span class="rating-static rating-50"></span>
						<?php } ?>
                    </div>
					
					<div class="form-group">

                        <label>Comments</label>

                        <textarea name="comments" class="form-control" rows="5" required="required" <?php if ($rating_row) { echo 'disabled="disabled"'; } ?>><?php if ($rating_row) { echo $rating_row['comments']; } ?></textarea>
                    </div>

                    <div class="form-group">

                        <input type="submit" name="reviewSubmit" class="btn btn-primary" value="Submit"  <?php if ($rating_row) { echo 'disabled="disabled"'; } ?> />

                    </div>

                </form>

            </div>

            

        </div>

    

    </div>

</div>
<!-- End Add Review Modal -->
<!-- Upload New Assignment Modal Start-->
<div id="uploadAssignModal" class="modal fade" role="dialog">

    <div class="modal-dialog assignmodal">

        <!-- Modal content-->

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title">Upload New Assignment</h4>

            </div>

            <div class="modal-body">
				<?php
				// Declare array to hold pending assignments
				$pending_assignments = array();
				// Get total assignments authorized
				$total_auth_assignments_result = $db->query("SELECT assign_no, topic, assignment_type FROM assignments WHERE status = '1' AND id IN (SELECT assign_id FROM course_assign_user_auth WHERE user_id = '" . $_SESSION['user_id'] . "' AND auth_status = '2' AND assign_id IN (SELECT id FROM assignments WHERE course_id = '" . trim(mysqli_real_escape_string($db, filter_var($_GET['id'], FILTER_SANITIZE_STRING))) . "'))");
				while ($total_auth_assignments_row  = $total_auth_assignments_result->fetch_array()) {
					$temp_result = $db->query("SELECT id FROM upload_assignments WHERE assign_no = '" . $total_auth_assignments_row['assign_no'] . "' AND user_id = '" . $_SESSION['user_id'] . "' AND course_id = '" . trim(mysqli_real_escape_string($db, filter_var($_GET['id'], FILTER_SANITIZE_STRING))) . "' AND status <> '7' ");
                    $submitted_row = $temp_result->fetch_array();
					if (!$submitted_row) {
						$pending_assignments[] = $total_auth_assignments_row;
					}
				}
				// Check if non-submitted pending assignments are there
				if (count($pending_assignments) == 0) {
					echo 'You don\'t have any pending assignments at the moment';
					exit;
				}
				?>

                <form name="uploadAssignment" method="post" action="" enctype="multipart/form-data">

                    <div class="form-group">

                        <label>Assignment No</label>

                        <select name="upAssignNo" id="upAssignNo" required="required" onchange="if ($('option:selected', this).attr('mcqs') == 'yes') { document.getElementById('mcqdiv').style.display='';document.getElementById('uplddiv').style.display='none';$('#mcqdiv').load('mcqs.php?assign_no=' + this.value + '&course_id=' + document.getElementById('course-id').value); } else { document.getElementById('mcqdiv').style.display='none';document.getElementById('uplddiv').style.display='';$('#upAssignTopic').val($('option:selected', this).attr('topic'));}" class="form-control">
							<option selected value="" topic="">Select Assignment Number</option>
							<?php foreach ($pending_assignments as $i) { ?>
							<option value="<?php echo $i['assign_no']; ?>" topic="<?php echo $i['topic']; ?>" <?php echo ($i['assignment_type'] == 2) ? 'mcqs="yes"' : ''; ?>><?php echo $i['assign_no']; ?></option>
							<?php } ?>
						</select>

                    </div>

                    <div id="uplddiv">
                        <div class="form-group">
    
                            <label>Topic</label>
    
                            <input type="text" name="upAssignTopic" id="upAssignTopic" class="form-control" placeholder="Enter Topic of Assignment" required="required" readonly="readonly" />
    
                        </div>
    
                        <div class="form-group">
    
                            <label>File</label>
    
                            <input type="file" name="upAssignFile" class="form-control" required="required" />
    
                        </div>
    
                        <div class="form-group">
    
                            <input type="submit" name="upAssignSubmit" class="btn btn-primary" value="Save"/>
    
                        </div>
                    </div>

                </form>
                <!-- Begin MCQs section -->
                <div id="mcqdiv"></div>
                <!-- End MCQs section -->

            </div>

            

        </div>

    

    </div>

</div>
<!-- Upload New Assignment Modal Ends -->
