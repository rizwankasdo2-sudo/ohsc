<?php //oxford
header('X-Robots-Tag: noindex, nofollow', true);
ob_start();
include("admin/classes/connection.php");
// Start captcha validation
$grr = trim(mysqli_real_escape_string($db, filter_var($_POST['g-recaptcha-response'], FILTER_SANITIZE_STRING)));
if ($grr == '') { 
    echo 'Captcha validation failed.';
    exit;
}
// End captcha validation
$dip = trim(mysqli_real_escape_string($db, filter_var($_POST['dip'], FILTER_SANITIZE_STRING)));
if ($dip == 0 || (!isset($_POST['diplomad']))) {
	$msg="Course and Diploma is not selected";
	header("location:".$base_url."/applyonline?msg=$msg");	
	exit();
}
else {
$diplomad = trim(mysqli_real_escape_string($db, filter_var($_POST['diplomad'], FILTER_SANITIZE_STRING)));
$d1 = $diplomad;
$dip = trim(mysqli_real_escape_string($db, filter_var($_POST['dip'], FILTER_SANITIZE_STRING)));
$d2 = $dip;

$menuqry = $db->query("SELECT cname FROM `tbl_courses` WHERE cid='$dip'");
$dipp= $menuqry->fetch_array();
$dip=$dipp['cname'];

$qry = $db->query("SELECT * FROM `tbl_diploma` WHERE `diplomaid`=$diplomad");
$result = $qry->fetch_array();
$diplomad=$result['name'];

$payment_method = trim(mysqli_real_escape_string($db, filter_var($_POST['payment_method'], FILTER_SANITIZE_STRING)));
if (empty($payment_method)) {
    $payment_method = 'Free Course';
    if ($result['course_state'] != 1) {
      $msg="Invalid selection please try again with valid course selection.";
      header("location:".$base_url."/applyonline?msg=$msg");  
      exit();
    }
}

$fname = trim(mysqli_real_escape_string($db, filter_var($_POST['fname'], FILTER_SANITIZE_STRING)));
$lname = trim(mysqli_real_escape_string($db, filter_var($_POST['lname'], FILTER_SANITIZE_STRING)));
$mobile = trim(mysqli_real_escape_string($db, filter_var($_POST['mobile'], FILTER_SANITIZE_STRING)));
$email = trim(mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)));
$form_type = "Apply Online";
$name_title = trim(mysqli_real_escape_string($db, filter_var($_POST['name_title'], FILTER_SANITIZE_STRING)));
$country = trim(mysqli_real_escape_string($db, filter_var($_POST['country'], FILTER_SANITIZE_STRING)));

$result11 = $db->query("insert into tbl_info(`cid`,`diplomaid`, `name_title` ,`firstname`,`lastname`,`mobile`,`email`, `form_type`, `apply_date`, `country`, `payment_method`) values ('$d2', '$d1', '$name_title', '$fname','$lname','$mobile','$email', '$form_type', CURDATE(), '$country', '$payment_method')");

if(!$result11){
    die(mysqli_error($db));
}

$table = '<table width="500" border="1">
  <tr>
    <td>Name: </td>
    <td>'.$name_title.' '.$fname.'&nbsp;&nbsp;'.$lname.'</td>
  </tr>
  <tr>
    <td>Mobile: </td>
    <td>'.$mobile.'</td>
  </tr>
  <tr>
    <td>Email: </td>
    <td>'.$email.'</td>
  </tr>
  <tr>
    <td>Country: </td>
    <td>'.$country.'</td>
  </tr>
  <tr>
    <td>Category: </td>
    <td>'.$dip.'</td>
  </tr>
<tr>
    <td>Course: </td>
    <td>'.$diplomad.'</td>
</tr>

<tr>
    <td>Payment Method: </td>
    <td>'.$payment_method.'</td>
</tr>

</table>';
$from = $email;
//$to = 'admissions@oxfordhomestudy.com';
$to = 'info@oxfordhomestudy.com';

$subject = 'Applying form';
$headers  = "From: $to\r\n";
$headers .= "Content-type: text/html\r\n";


//echo $table;
    // now lets send the email.

//mail($to, $subject, $table, $headers);
//smtp_mailer($to, $subject, $table);
$client_postmark->sendEmail('no-reply@oxfordhomestudy.com', $to, $subject, $table);
	
	
$masg="Thank you we will contact you soon";

 $header  = "From: $to\r\n";

    $header .= "Content-type: text/html\r\n";
	
	//mail($from,'Thank you for your email',$masg,$header);
  //smtp_mailer($from,'Thank you for your email',$masg);
  $client_postmark->sendEmail('no-reply@oxfordhomestudy.com', $from,'Thank you for your email',$masg);
	// Begin Zoho lead creation
    echo "<form action='https://crm.zoho.com/crm/WebToLeadForm' name=WebToLeads4453600000000276007 method='POST'  accept-charset='UTF-8'>
      <input type='hidden' name='xnQsjsdp' value='842ffaacf5dd98aab685b371bc6d46ae130ac6b6def68ac096f57366bd632068'></input>
      <input type='hidden' name='zc_gad' id='zc_gad' value=''></input>
      <input type='hidden' name='xmIwtLD' value='c7f29aa966771fb36cef697b5ba290aed055b2b8e7606a6471193aa3450910dc'></input>
      <input type='hidden' name='actionType' value='TGVhZHM='></input>
      <input type='hidden' name='returnURL' value='https://tools.kasdo.org/thank-you.php' ></input>
      <input type='hidden' name='First Name' value='$fname' />
      <input type='hidden' name='Last Name' value='$lname' />
      <input type='hidden' name='Phone' value='$mobile' />
      <input type='hidden' name='Email' value='$email' />
      <input type='hidden' name='Country' value='$country' />
      <input type='hidden' name='LEADCF1' value='$diplomad' />
      <input type='hidden' name='Lead Source' value='https://tools.kasdo.org/' />
      <input type='hidden' name='Lead Status' value='Query&#x20;Submitted' />
      <input type='hidden' name='LEADCF2' value='Enrollment&#x20;Application' />
      <input type='hidden' name='LEADCF3' value='$payment_method' />
      <input type='hidden' name='LEADCF4' value='' />
    </form>
    <script>document.WebToLeads4453600000000276007.submit();</script>";
    // End Zoho lead creation
	
	//header("location:".$base_url."/thank-you.php?msg");
}
?>
