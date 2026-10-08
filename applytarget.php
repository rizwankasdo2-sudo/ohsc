<?php //  for oxford  from  enroll page
header('X-Robots-Tag: noindex, nofollow', true);
ob_start();
include("admin/classes/connection.php");


$mr = $_POST['mr'];
$name = $_POST['namess'];
$dob = $_POST['dob'];
$mobile = $_POST['mobile'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$address = $_POST['address'];
$city = $_POST['city'];
$country =$_POST['country'];
 $dip = $_POST['dip'];
$diplomad = $_POST['diplomad'];
$exp = $_POST['exp'];
$mathod = $_POST['mathod'];

if($mathod==1)
{
$mathodss = 'Full payment';	
	
}
else
{
$mathodss = 'Payment in installments';	
}

$programqry = $db->query("SELECT * FROM `tbl_courses` WHERE `cid`=$dip");
$programresult = $programqry->fetch_array();
$program = $programresult['cname'];

$diploma = $db->query("SELECT * FROM `tbl_diploma` WHERE `diplomaid`=$diplomad");
$diploma = $diploma->fetch_array();
$diplomass = $diploma['name'];

$table = '<table width="500" border="1">
  <tr>
    <td>Name: </td>
    <td>'.$mr.'&nbsp;&nbsp;'.$name.'</td>
  </tr>
  <tr>
    <td>DOB: </td>
    <td>'.$dob.'</td>
  </tr>
  <tr>
    <td>Mobile: </td>
    <td>'.$mobile.'</td>
  </tr>
  <tr>
    <td>Phone: </td>
    <td>'.$phone.'</td>
  </tr>
  <tr>
    <td>Email: </td>
    <td>'.$email.'</td>
  </tr>
  <tr>
    <td>Address: </td>
    <td>'.$address.'</td>
  </tr>
  <tr>
    <td>City: </td>
    <td>'.$city.'</td>
  </tr>
  <tr>
    <td>Category: </td>
    <td>'.$program.'</td>
  </tr>
   <tr>
    <td>Diploma: </td>
    <td>'.$diplomass.'</td>
  </tr>
   <tr>
    <td>Education and Experience: </td>
    <td>'.$exp.'</td>
  </tr>
  
   <tr>
    <td>Payment Mathod: </td>
    <td>'.$mathodss.'</td>
  </tr>

</table>';
 
$from = $email;
//$to='rizwan.mallhi@live.com';
$to = 'info@oxfordhomestudy.com';
$subject = 'Applying form';
 $headers  = "From: $from\r\n";

    $headers .= "Content-type: text/html\r\n";



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
    
    // Begin Zoho lead creation
    $fname = trim(mysqli_real_escape_string($db, filter_var($_POST['fname'], FILTER_SANITIZE_STRING)));
    $lname = trim(mysqli_real_escape_string($db, filter_var($_POST['lname'], FILTER_SANITIZE_STRING)));
    echo "<form action='https://crm.zoho.com/crm/WebToLeadForm' name=WebToLeads4453600000000276007 method='POST'  accept-charset='UTF-8'>
      <input type='hidden' name='xnQsjsdp' value='842ffaacf5dd98aab685b371bc6d46ae130ac6b6def68ac096f57366bd632068'></input>
      <input type='hidden' name='zc_gad' id='zc_gad' value=''></input>
      <input type='hidden' name='xmIwtLD' value='c7f29aa966771fb36cef697b5ba290aed055b2b8e7606a6471193aa3450910dc'></input>
      <input type='hidden' name='actionType' value='TGVhZHM='></input>
      <input type='hidden' name='returnURL' value='https://www.oxfordhomestudy.com/enrolthanks.php' ></input>
      <input type='hidden' name='First Name' value='$fname' />
      <input type='hidden' name='Last Name' value='$lname' />
      <input type='hidden' name='Phone' value='$mobile' />
      <input type='hidden' name='Email' value='$email' />
      <input type='hidden' name='Country' value='$country' />
      <input type='hidden' name='LEADCF1' value='$diplomass' />
      <input type='hidden' name='Lead Source' value='https&#x3a;&#x2f;&#x2f;www.stafftrainingsolutions.co.uk&#x2f;' />
      <input type='hidden' name='Lead Status' value='Query&#x20;Submitted' />
      </form>
      <script>document.WebToLeads4453600000000276007.submit();</script>";
      // End Zoho lead creation
	
	
	//header("location:".$base_url."/enrolthanks.php");

?>