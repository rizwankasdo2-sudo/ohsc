<?php //oxford
ob_start();
include("admin/classes/connection.php");
// Start captcha validation
$grecaptcharesponse = trim(mysqli_real_escape_string($db, filter_var($_POST['g-recaptcha-response'], FILTER_SANITIZE_STRING)));
if (trim($grecaptcharesponse) == '') { 
    echo 'Captcha validation failed.';
    exit;
}
// End captcha validation
$dip = trim(mysqli_real_escape_string($db, filter_var($_POST['dip'], FILTER_SANITIZE_STRING)));
if($dip==0 || (!isset($_POST['diplomad'])))
{
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

$fname = trim(mysqli_real_escape_string($db, filter_var($_POST['fname'], FILTER_SANITIZE_STRING)));
$lname = trim(mysqli_real_escape_string($db, filter_var($_POST['lname'], FILTER_SANITIZE_STRING)));
$mobile = trim(mysqli_real_escape_string($db, filter_var($_POST['mobile'], FILTER_SANITIZE_STRING)));
$email = trim(mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_SANITIZE_STRING)));
$voucher = trim(mysqli_real_escape_string($db, filter_var($_POST['voucher_code'], FILTER_SANITIZE_STRING)));
$name_title = trim(mysqli_real_escape_string($db, filter_var($_POST['name_title'], FILTER_SANITIZE_STRING)));
$country = trim(mysqli_real_escape_string($db, filter_var($_POST['country'], FILTER_SANITIZE_STRING)));
if (isset($_POST['payment_method']) && !empty($_POST['payment_method'])) {
    $payment_method = trim(mysqli_real_escape_string($db, filter_var($_POST['payment_method'], FILTER_SANITIZE_STRING)));
} else {

    $payment_method = 'Free Course';
}

$result11 = $db->query("INSERT INTO vouchers(`cid`,`diplomaid` ,`firstname`,`lastname`,`mobile`,`email`, `apply_date`, `country`, `voucher`) VALUES ('$d2', '$d1', '$fname','$lname','$mobile','$email', CURDATE(), '$country', '$voucher')");

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
    <td>Voucher Code </td>
    <td>'.$payment_method.'</td>
</tr>

</table>';
$from = $email;
$to = 'info@oxfordhomestudy.com';
//$to='rizwan.mallhi@live.com';

$subject = 'Applying form';
$headers  = "From: $to\r\n";
$headers .= "Content-type: text/html\r\n";


//echo $table;
    // now lets send the email.

//mail($to, $subject, $table, $headers,'-finfo@oxfordhomestudy.com');
//smtp_mailer($to, $subject, $table);
$client_postmark->sendEmail('no-reply@oxfordhomestudy.com', $to, $subject, $table);
	
	
$masg="Thank you we will contact you soon";

 $header  = "From: $to\r\n";

    $header .= "Content-type: text/html\r\n";
	
	//mail($from,'Thank you for your email',$masg,$header,'-finfo@oxfordhomestudy.com');
  //smtp_mailer($from,'Thank you for your email',$masg);
  $client_postmark->sendEmail('no-reply@oxfordhomestudy.com', $from,'Thank you for your email',$masg);
	
	header("location:".$base_url."/thank-you.php?msg");
}
?>