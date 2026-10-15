<?php
header('X-Robots-Tag: noindex, nofollow', true);
session_start();
//ob_start();
include("admin/classes/connection.php");
// Start captcha validation
$grecaptcharesponse = trim(mysqli_real_escape_string($db, filter_var($_POST['g-recaptcha-response'], FILTER_SANITIZE_STRING)));
if (trim($grecaptcharesponse) == '') {
    echo 'Captcha validation failed.';
    $_SESSION['captchaErr'] = 'yes';
    $_SESSION['degree'] = trim(mysqli_real_escape_string($db, filter_var($_POST['degree'], FILTER_SANITIZE_STRING)));
    $_SESSION['diploma'] = trim(mysqli_real_escape_string($db, filter_var($_POST['diploma'], FILTER_SANITIZE_STRING)));
    $_SESSION['fname'] = trim(mysqli_real_escape_string($db, filter_var($_POST['fname'], FILTER_SANITIZE_STRING)));
    $_SESSION['lname'] = trim(mysqli_real_escape_string($db, filter_var($_POST['lname'], FILTER_SANITIZE_STRING)));
    $_SESSION['mobile'] = trim(mysqli_real_escape_string($db, filter_var($_POST['mobile'], FILTER_SANITIZE_STRING)));
    $_SESSION['email'] = trim(mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)));
    header('location:' . trim(mysqli_real_escape_string($db, filter_var($_POST['prevloc'], FILTER_SANITIZE_STRING))));
    exit;
}
/*include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';
$securimage = new Securimage();
if ($securimage->check($_POST['captcha_code']) == false) {
  // the code was incorrect
  // you should handle the error so that the form processor doesn't continue

  // or you can use the following code if there is no validation or you do not know how
  echo "The security code entered was incorrect.<br /><br />";
  echo "Please go <a href='javascript:history.go(-1)'>back</a> and try again.";
  exit;
}*/
// End captcha validation


$degree = trim(mysqli_real_escape_string($db, filter_var($_POST['degree'], FILTER_SANITIZE_STRING)));
$diploma = trim(mysqli_real_escape_string($db, filter_var($_POST['diploma'], FILTER_SANITIZE_STRING)));
// Check for free courses
if ($diploma == 'free') {
    header('location:https://www.elearningcollege.com/lms');
    exit;
}
$fname = trim(mysqli_real_escape_string($db, filter_var($_POST['fname'], FILTER_SANITIZE_STRING)));
$lname = trim(mysqli_real_escape_string($db, filter_var($_POST['lname'], FILTER_SANITIZE_STRING)));
$phone = trim(mysqli_real_escape_string($db, filter_var($_POST['mobile'], FILTER_SANITIZE_STRING)));
$email = trim(mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)));
$ftype = 'Get Info';


$sql = $db->query("SELECT * FROM emails WHERE diploma_id='$diploma'");
if(!$sql){
    die(mysqli_error($db));
}
$rowEmail = $sql->fetch_array();

$db->query("insert into tbl_info(`cid`,`diplomaid`,`firstname`,`lastname`,`mobile`,`email`, `form_type`, `apply_date`) values($degree,$diploma,'$fname','$lname','$phone','$email', '$ftype', CURDATE())");

$programqry = $db->query("SELECT * FROM `tbl_courses` WHERE `cid`=$degree");
$programresult = $programqry->fetch_array();
$program = $programresult['cname'];

$diploma = $db->query("SELECT * FROM `tbl_diploma` WHERE `diplomaid`= '$diploma' ");
$diploma = $diploma->fetch_array();
$diplomass = $diploma['name'];

//Mail send to admin
$massages = 'Category : '.$program.'<br>Course : '.$diplomass.'<br>First Name : '.$fname.'<br>Last Name : '.$lname.'<br>Phone : '.$phone.'<br>Email : '.$email;
$from = $email;
$to = 'admissions@oxfordhomestudy.com';
$subject = 'Course information';
$headers  = "From: $to\r\n";

$headers .= "Content-type: text/html\r\n";

// now lets send the email.

//mail($to, $subject, $massages, $headers);
//smtp_mailer($to, $subject, $massages);
$client_postmark->sendEmail('no-reply@oxfordhomestudy.com', $to, $subject, $massages);

//Mail send to customer

if(!empty($rowEmail['subject'])){
    $subject = $rowEmail['subject'];
} else {
    $subject = 'Thank you for your email';
}

if(!empty($rowEmail['subject'])){
   $masg = $rowEmail['body'];
} else {
   $masg="Thank you we will contact you soon";
}

$header  = "From: $to\r\n";

$header .= "Content-type: text/html\r\n";

//mail($from,$subject,$masg,$header);
//smtp_mailer($from,$subject,$masg);
$client_postmark->sendEmail('no-reply@oxfordhomestudy.com', $from,$subject,$masg);
// Begin Zoho lead creation
echo "<form action='https://crm.zoho.com/crm/WebToLeadForm' name=WebToLeads4453600000000276007 method='POST'  accept-charset='UTF-8'>
  <input type='hidden' name='xnQsjsdp' value='842ffaacf5dd98aab685b371bc6d46ae130ac6b6def68ac096f57366bd632068'></input>
  <input type='hidden' name='zc_gad' id='zc_gad' value=''></input>
  <input type='hidden' name='xmIwtLD' value='c7f29aa966771fb36cef697b5ba290aed055b2b8e7606a6471193aa3450910dc'></input>
  <input type='hidden' name='actionType' value='TGVhZHM='></input>
  <input type='hidden' name='returnURL' value='https://tools.kasdo.org/thanks' ></input>
  <input type='hidden' name='First Name' value='$fname' />
  <input type='hidden' name='Last Name' value='$lname' />
  <input type='hidden' name='Phone' value='$phone' />
  <input type='hidden' name='Email' value='$email' />
  <input type='hidden' name='Country' value='' />
  <input type='hidden' name='LEADCF1' value='$diplomass' />
  <input type='hidden' name='Lead Source' value='https://tools.kasdo.org/' />
  <input type='hidden' name='Lead Status' value='Replied&#x20;to&#x20;Query' />
  <input type='hidden' name='LEADCF2' value='Info&#x20;Request' />
  <input type='hidden' name='LEADCF3' value='' />
  <input type='hidden' name='LEADCF4' value='' />
</form>
<script>document.WebToLeads4453600000000276007.submit();</script>";
// End Zoho lead creation
//header("location:".$base_url."/thanks?msg"); ?>