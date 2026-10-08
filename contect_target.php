<?php
header('X-Robots-Tag: noindex, nofollow', true);
ob_start();
include("admin/classes/connection.php");
// Start captcha validation
$grecaptcharesponse = trim(mysqli_real_escape_string($db, filter_var($_POST['g-recaptcha-response'], FILTER_SANITIZE_STRING)));
if (trim($grecaptcharesponse) == '') { 
    echo 'Captcha validation failed.';
    exit;
}
// End captcha validation
$name = trim(mysqli_real_escape_string($db, filter_var($_POST['name'], FILTER_SANITIZE_STRING))); 
$title = trim(mysqli_real_escape_string($db, filter_var($_POST['title'], FILTER_SANITIZE_STRING)));
$country = trim(mysqli_real_escape_string($db, filter_var($_POST['country'], FILTER_SANITIZE_STRING)));
$province = trim(mysqli_real_escape_string($db, filter_var($_POST['province'], FILTER_SANITIZE_STRING)));
$phone = trim(mysqli_real_escape_string($db, filter_var($_POST['phone'], FILTER_SANITIZE_STRING)));
$call = trim(mysqli_real_escape_string($db, filter_var($_POST['call'], FILTER_SANITIZE_STRING)));
$email = trim(mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)));
$massage = trim(mysqli_real_escape_string($db, filter_var($_POST['message'], FILTER_SANITIZE_STRING)));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Invalid email address provided.';
    exit;
}
$to = 'info@oxfordhomestudy.com';
$from = $email;
    
$subject = 'Oxford Learning Home';
  $massages = 'Name : '.$name.'<br>Title : '.$title.'<br>Country : '.$country.'<br>City : '.$province.'<br>Phone : '.$phone.'<br>Email : '.$email.'<br>Best time to call: '.$call.'<br>Massage : '.$massage;
    //begin of HTML message
	$message = $massage;
   //end of message
    $headers  = "From: $from\r\n";
    $headers .= "Content-type: text/html\r\n";
    // now lets send the email.
    //mail($to, $subject, $massages, $headers);
    //smtp_mailer($to, $subject, $massages);
    $client_postmark->sendEmail('no-reply@oxfordhomestudy.com', $to, $subject, $massages);
	
	
$masg="Thank you for your feedback";
 $header  = "From: $to\r\n";
    $header .= "Content-type: text/html\r\n";
	
	//mail($from,'Thank you for your email',$masg,$header);
    //smtp_mailer($from,'Thank you for your email',$masg);
    $client_postmark->sendEmail('no-reply@oxfordhomestudy.com', $from, 'Thank you for your email', $masg);
	
// Begin Zoho lead creation
$name_array = explode(' ', trim($name));
$first_name = $name_array['0'];
$last_name = '';
$space = '';
for ($i = 1; $i < count($name_array); $i++) {
    $last_name .= $space . $name_array[$i];
    $space = ' ';
}
echo "<form action='https://crm.zoho.com/crm/WebToLeadForm' name=WebToLeads4453600000000276007 method='POST'  accept-charset='UTF-8'>
  <input type='hidden' name='xnQsjsdp' value='842ffaacf5dd98aab685b371bc6d46ae130ac6b6def68ac096f57366bd632068'></input>
  <input type='hidden' name='zc_gad' id='zc_gad' value=''></input>
  <input type='hidden' name='xmIwtLD' value='c7f29aa966771fb36cef697b5ba290aed055b2b8e7606a6471193aa3450910dc'></input>
  <input type='hidden' name='actionType' value='TGVhZHM='></input>
  <input type='hidden' name='returnURL' value='https://www.oxfordhomestudy.com/thanks' ></input>
  <input type='hidden' name='First Name' value='$first_name' />
  <input type='hidden' name='Last Name' value='$last_name' />
  <input type='hidden' name='Phone' value='$phone' />
  <input type='hidden' name='Email' value='$email' />
  <input type='hidden' name='Country' value='$country' />
  <input type='hidden' name='LEADCF1' value='' />
  <input type='hidden' name='Lead Source' value='https&#x3a;&#x2f;&#x2f;www.oxfordhomestudy.com&#x2f;' />
  <input type='hidden' name='Lead Status' value='Query&#x20;Submitted' />
  <input type='hidden' name='LEADCF2' value='User&#x20;Contact' />
  <input type='hidden' name='LEADCF3' value='' />
  <input type='hidden' name='LEADCF4' value='$massage' />
</form>
<script>document.WebToLeads4453600000000276007.submit();</script>";
// End Zoho lead creation
//header("location:".$base_url."/thanks?msg");
?>
