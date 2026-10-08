<?php
header('X-Robots-Tag: noindex, nofollow', true);
session_start();
date_default_timezone_set("Europe/London");
set_time_limit(0);
include(dirname(__FILE__) . '/lms/classes/front.php');
include(dirname(__FILE__) . '/lms/classes/news.php');
include(dirname(__FILE__) . '/lms/classes/common.php');
/* Include EndUser class */
include(dirname(__FILE__) . '/lms/classes/enduser.php');
/* Include General class */
include(dirname(__FILE__) . '/lms/classes/general.php');
// Create object of General class
$general = new General($db);
// Declare use as not already registered
$existing_user = 'no';
$not_enrolled = 'no';
function sendEmail($from, $to, $subject, $message) {
    $from = urlencode($from);
    $to = urlencode($to);
    $subject = urlencode($subject);
    $message = urlencode($message);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.oxfordhomestudy.com/lms/sendmail.php?from=$from&to=$to&subject=$subject&message=$message");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);
}
if (is_numeric($_POST['portal_course_id']) && $_POST['portal_course_id'] > 0 && !empty($_POST['stripeToken']) && $_POST['stripeTokenType'] == 'card' && filter_var($_POST['stripeEmail'], FILTER_VALIDATE_EMAIL)) {
    require(dirname(__FILE__) . '/stripe_config.php');
    \Stripe\Stripe::setVerifySslCerts(false);
    $stripeToken = $_POST['stripeToken'];
    try {
        $data = \Stripe\Charge::create(array(
            'amount' => $_POST['stripe_pay'],
            'currency' => 'gbp',
            'description' => 'About OHSC',
            'source' => $stripeToken
        ));
    } catch (\Stripe\Exception\CardException $e) {
        echo 'Payment not successful. Please try again. <button onclick="window.history.back();">Go Back</button>';
        exit;
    } catch (\Exception $e) {
        // Handle other types of exceptions
        echo 'Payment not successful. Please try again. <button onclick="window.history.back();">Go Back</button>';
        exit;
    }
    
    if ($data->amount_captured == $_POST['stripe_pay'] && $data->amount_refunded == 0 && $data->outcome->type == 'authorized' && $data->source->name == $_POST['stripeEmail']) {
        $email = $_POST['stripeEmail'];
        $stripe_pay_actual = $_POST['stripe_pay_actual'];
        // Begin student registration
        $length = 10;
        $verify_string = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ($*)"), 0, $length);
        $sql = $db->query("SELECT id FROM users WHERE email='$email'");
        if ($user_row = $sql->fetch_array()) {
            $customer = $user_row['id'];
            $existing_user = 'yes';
        } else {
            // Get highest membership number
            $db->query("LOCK TABLES users WRITE");
            $temp_result = $db->query("SELECT max(CAST(m_number as UNSIGNED)) as m_number FROM users ");
            $m_number_row = $temp_result->fetch_array();
            // Generate membership number for new user
            $m_number = $m_number_row['m_number'] + 1;
            // Encrypt membership number to create password
            $db_password = md5($m_number);
            // Get id of admin who is registering the user
            $register_by_admin = 0;
            $sql = "INSERT INTO users SET email = '$email', password = '$db_password', step = '1', verify = '1', verify_token = '$verify_string', status = '1', m_number = '$m_number', register_by_admin = '$register_by_admin' ";
            $result = $db->query($sql);
            $customer = $db->insert_id;
            $db->query("UNLOCK TABLES");
            $sql = "INSERT INTO personal_details (customer_title, customer_id, first_name, surname, dob, email, mobile, address_line_1, address_line_2, address_line_3, town, country, postcode, gender,admission_source_id)
        VALUES ('', '$customer', 'First Name', 'Last Name', '', '$email', '', '', '', '', '', '', '', '','1')";
            $db->query($sql);
            // Begin insertion into business_details table
            $db->query("INSERT INTO business_details SET customer_id = '$customer' ");
            // End insertion into business_details table
            $to = $email;
            $from = 'no-reply@oxfordhomestudy.com';
            $subject = 'Your OHSC Login Information';
            $message = "<strong>Dear Student</strong><br /><br />";
            $message .= "Following are your login details for OHSC.<br /><br />";
            $message .= "<strong>PORTAL ACCESS USER NAME: </strong>" . $email . "<br />
                    <strong>PORTAL ACCESS PASSWORD: </strong>" . $m_number . "<br /><br />";
            $message .= 'Please use the username and password supplied above.<br /><br />Go to https://www.oxfordhomestudy.com/lms/<br /><br />Log in using your username and password.<br /><br />If you have any difficulties logging in or with any other aspect of this procedure, please contact us at info@oxfordhomestudy.com';
            $message .= "<br /><br /><strong>Regards<br />Support Team</strong>";
            $headers = "Mime-version:1.0\r\n";
            $headers .= "content-type:text/html;charset=iso-8859-1\r\n";
            $headers .= "From:$from\r\n";
            //$client_postmark->sendEmail($from,$to,$subject,$message);
            sendEmail($from, $to, $subject, $message);
            // Get id of currently signed in admin
            $admin_id = 0;
            // Make entry in notifications table
            $db->query("INSERT INTO notifications (admin_id, user_id, status, subject, body, send_date) VALUES('$admin_id', '$customer', 0, '$subject', '$message', NOW())");
        }
        // End student registration
        // Begin course enrolment
        $course_id  = $_POST['portal_course_id'];
        // Begin start date handling
        $start_date  = date('Y-m-d', time());
        // End start date handling
        $course_status  = 5;
        $query = "SELECT * FROM course_user WHERE user_id = '$customer' AND course_id='$course_id' ";
        $sql5 = $db->query($query);
        $course_user_row = $sql5->fetch_array();
        //calculate Target date
        $sql = $db->query("select no_month from courses where id = $course_id");
        $result = $sql->fetch_array();
        $strTime = strtotime("+ ".$result['no_month']." months");
        $target_date = date('Y-m-d',$strTime);
        $sql = $generalObject->getStatusById('course_status', $course_status);
        $course_db_status = $sql->fetch_array();
        $pd_result = $db->query("SELECT * FROM personal_details WHERE customer_id = '$customer' ");
        $pd_row = $pd_result->fetch_array();
        if (!$course_user_row) {
            $sql = $generalObject->getStatusById('course_status', $course_status);
            $course_db_status = $sql->fetch_array();
            $course_as_query = "INSERT INTO course_user (user_id, course_id, enrol_request_date, start_date, target_date, enrol_status, course_status, reed_order_id) VALUES ('$customer', '$course_id', NOW(), '$start_date', '$target_date', '2', '$course_status', '')";
            // Create object of EndUser class
            $enduser = new EndUser($db, $customer);
            // Send notification
            $mail_array = $enduser->sendNotificationWithoutName(0, 'You Are Enrolled In An Online Course', 'You are enrolled in an online course, ' . $news->getParticularField('title', 'courses', 'id', $course_id) . '.');
            $headers = "Mime-version:1.0\r\n";
            $headers .= "content-type:text/html;charset=iso-8859-1\r\n";
            $headers .= "From:$from\r\n";
            sendEmail($mail_array['0'], $mail_array['1'], $mail_array['2'], $mail_array['3']);
            $course_user_sql = $db->query($course_as_query);
            //Auto Authorize study material
            $sql = $generalObject->getStatus('study_material_status');
            $result = $sql->fetch_array();
            $sm_status = $result['id'];
            $sql = $db->query("select id from study_materials where course_id = $course_id and auto_auth = 1 and status = 1");
            while ($sm_record = $sql->fetch_array()) {
                $sm_id = $sm_record['id'];
                $sql1 = $db->query("select count(id) from course_sm_user_auth where user_id = $customer and course_id = $course_id and sm_id = $sm_id");
                $sm_record_old = $sql1->fetch_array();
                if ($sm_record_old['count(id)'] == 0) {
                    $db->query("insert into course_sm_user_auth (user_id, course_id, sm_id, status, auth_status, request_date, auth_date, auto_auth) values('$customer', '$course_id', '$sm_id', '$sm_status', 2, NOW(), NOW(), 1)");
                }
            }
            //Auto Authorize Assignments
            $sql = $generalObject->getStatus('assignment_status');
            $result = $sql->fetch_array();
            $assign_status = $result['id'];
            $sql = $db->query("select id from assignments where course_id = $course_id and auto_auth = 1 AND status = '1' ");
            while ($assign_record = $sql->fetch_array()) {
                $assign_id = $assign_record['id'];
                $sql1 = $db->query("select count(id) from course_assign_user_auth where user_id = $customer and course_id = $course_id and assign_id = $assign_id");
                $assign_record_old = $sql1->fetch_array();
                if ($assign_record_old['count(id)'] == 0) {
                    $db->query("insert into course_assign_user_auth (user_id, course_id, assign_id, status, auth_status, request_date, auth_date, auto_auth) values('$customer', '$course_id', '$assign_id', '$assign_status', 2, NOW(), NOW(), 1)");
                }
            }
        } else {
            $db->query("INSERT INTO course_not_enrolled SET course_id = '$course_id', user_id = '$customer'");
            $not_enrolled = 'yes';
            $_SESSION['message'] = 'You are already enrolled in this course.';
            $_SESSION['err'] = 'yes';
        }
        // Begin payment insertion
        // End payment insertion
        $db->query("INSERT INTO payments SET user_id = $customer, course_id = $course_id, amount = '$stripe_pay_actual', total_amount = '$stripe_pay_actual', payment_date = NOW(), status = 0, description = 'Payment for course enrolment', payment_type = 2, payment_method = 3");
        if ($existing_user == 'yes' && $_SESSION['verified']) { // Case 1. If user already registered and logged in
            $front->redirect($base.'courses.php');
        } else if ($existing_user == 'yes') { // Case 2. User already registered but not logged in
            if ($not_enrolled == 'no') {
                $_SESSION['msg'] = 'yes';
                $_SESSION['message'] = 'You have enrolled in new course successfully.';
            }
            $front->redirect($base);
        } else { // Case 3. User just registered here
            // Begin user login and redirect
            $userFound = $db->query("SELECT * FROM users WHERE id = $customer")->fetch_array();
            $_SESSION['user_id'] = $userFound['id'];
            $_SESSION['user_login'] = true;
            $_SESSION['user_email'] = $userFound['email'];
            $ip = $_SERVER['REMOTE_ADDR'];
            $db->query("INSERT INTO log_history (user_id, login_date, ip) VALUES ('$customer', NOW(), '$ip')");
            $_SESSION['verified'] = true;
            $front->redirect($base.'courses.php');
            // End user login and redirect
        }
    } else {
        echo 'Invalid access.';
    }
} else {
    echo 'Invalid access request.';
}
