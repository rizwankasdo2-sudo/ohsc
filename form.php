<?php
session_start();
require_once(dirname(__FILE__) . '/postmark/basic.php');
include 'Image-to-WebP-Converter-master/ToWebp.php';

//include_once('PHPMailer_v5.1/class.phpmailer.php');

/*include "includes/database.php";
include "includes/functions.php";*/
?>
<?php include("admin/classes/connection.php");
include_once("includes/functions.php");
?>
<!DOCTYPE html>
  <?php
       
       $res = "SELECT * FROM form_manage where id = 2";

       $result = $db->query($res);

       while ($row = $result->fetch_array()) {
         
          $form_heading = $row['form_heading'];
          $form_content = $row['form_content'];
          $form_heading2 = $row['form_heading2'];
          $form_content2 = $row['form_content2'];
          $banner_img = $row['banner_img'];
          $guidelines_two = $row['guidelines_two'];
          $guidelines_one = $row['guidelines_one'];
          $footer_img = $row['footer_img'];

       }

        ?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="<?php echo $row['m_title'];?>" content="<?php echo $row['m_title'];?>">
<meta name="description" content="<?php echo $row['m_description'];?>" />
<meta name="keywords" content="<?php echo $row['m_keywords'];?>" />
<meta name="robots" content="noindex, nofollow" />
<?php echoCanonicalTag('/form'); ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 
<title><?php //echo $row['m_title'];?> Review Form</title>

 <?php include("includes/stylesheets.php");?>
    <link rel="stylesheet" href="css/menu_jqry.css" type="text/css" />
 
<link rel="stylesheet" type="text/css" href="js/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="js/css/style.css">



 <link href="air-datepicker-master/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
        <script src="air-datepicker-master/dist/js/datepicker.min.js"></script>

        <!-- Include English language -->
        <script src="air-datepicker-master/dist/js/i18n/datepicker.en.js"></script>

        <script type="text/javascript">
          // Initialization
$('#my-element').datepicker([options])
// Access instance of plugin
$('#my-element').data('datepicker')
        </script>
<?php
 // print_r($_POST);
//exit;

 if(isset($_POST['save']) && !empty($_POST['save'])) {

    $course_title = $_POST['course_title'];
    $start_date = date('Y-m-d',strtotime($_POST['start_date']));
    
    $about_course = $_POST['about_course'];
    $rating = $_POST['rating'];

    $like_at_least = $_POST['like_at_least'];
    $recomend = $_POST['recomend'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $country = $_POST['country'];
    $telephon = $_POST['telephon'];


   $errors = array();
   if($_FILES['photo']['name']!="") {

        $filename = basename($_FILES['photo']['name']);
        $exploded_name = explode(".", $filename);
        $extension = strtolower(end($exploded_name));
        $valid_extensions = array("jpg","jpeg","gif","png","bmp","webp");

        $uploaddir = "images/form_photo/";
        $rand_no = rand(177, 1700);
        $photo = $exploded_name[0].$rand_no.time().".".$extension;

        $targetpath =  $uploaddir.$photo;
        list($width, $height) = getimagesize($_FILES['photo']['tmp_name']);
        
    /*     if($width > 82 && $height > 82)
       {
        $errors['size'] = "Image is large than required size";
       }
       else
       {*/

        if(filesize($_FILES['photo']['tmp_name']) < 2097152)
        {
            if (in_array($extension, $valid_extensions)) {
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetpath)) {
                    if ($extension != 'webp') {
                        // Begin image conversion to webp and original image deletion
                        $outPutQuality = 70;
                        $webp = new ToWebp();
                        $newImgInfo = $webp->convert($targetpath,$outPutQuality,true);
                        $photo = $newImgInfo->file;
                        // End image conversion to webp and original image deletion
                    }
                }
                else
                {
                    $msg_error = "There was a problem in uploading file. Please try again or select another file.";
                    
                }
            }
            else
            {
                $errors['formate'] = "You can only upload screenshots of the format jpg, jpeg, bmp, gif or png";

            }

        }
        else
        {
            $msg = "The file must be less than 2 MB in size. You can upload the file through FTP and then select it in <b>Select File</b> section.";
        }

    }

    if($_FILES['video']['name']!="") {

        $filename = basename($_FILES['video']['name']);
        $exploded_name = explode(".", $filename);
        $extension = strtolower(end($exploded_name));
        $valid_extensions = array("mp4","avi","mov","flv","3gp");

        $uploaddir = "images/form_videos/";
        $rand_no = rand(177, 1700);
        $fname = $exploded_name[0].$rand_no.time().".".$extension;

        $targetpath =  $uploaddir.$fname;

        if(filesize($_FILES['video']['tmp_name']) < 10485760)
        {
            if(in_array($extension, $valid_extensions))
            {
                if(move_uploaded_file($_FILES['video']['tmp_name'], $targetpath))
                {

                }
                else
                {
                    $msg = "There was a problem in uploading file. Please try again or select another file.";
                }
            }
            else
            {
                $errors['video'] = "You can only upload video of the format mp4, avi, mov or flv";

            }

        }
        else
        {
            $errors['video_size'] = "The file must be less than 10 MB in size. You can upload the file through FTP and then select it in <b>Select File</b> section.";
        }

    }
    
    $course_sql = "SELECT * FROM tbl_diploma WHERE diplomaid='$course_title'";
    //$course_row = $course_sql->fetch();

    $cou = $db->query($course_sql);

    while ($r = $cou->fetch_array()) {

       $course_titless = $r['name'];
      
    }
    
   // Email to Admin
    $body = $message_to_admin;
  
    $name_email = "OHSC";
   
    $admin_email = "info@oxfordhomestudy.com";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

    $headers .= "From: OHSC<$admin_email>" . "\r\n";

    $message= "Thank you for submitting the review.";

    $message_to_admin ="";

    $message_to_admin .= '<table width="100%" border="0" align="center">
  <tr>
    <td><strong>Name:</strong></td>
    <td>'.$surname.' '.$name.' </td>
  </tr>
 
  <tr>
    <td><strong>Course:</strong></td>
    <td>'.$course_titless.'</td>
  </tr>
   <tr>
    <td><strong>Email:</strong></td>
    <td>'.$email.'</td>
  </tr>
   

</table>';

if(count($errors) < 1) {
    $sql = "INSERT INTO review_form (course_title, start_date, about_course, rating, like_at_least, recomend, name, surname, email, telephon,photo, video, submited_date, country) VALUES ('$course_title','$start_date','$about_course','$rating','$like_at_least','$recomend','$name','$surname','$email','$telephon','$photo','$fname', now(), '$country')";
    $ins = $db->query($sql);
    // Begin sending email on five star rating
    if ($rating == 5) {
        $new_quantity_sent = 1;
        $email_settings_row = $db->query("SELECT * FROM send_emails_settings WHERE id = '1' ")->fetch_array();
        $current_template_id = $email_settings_row['current_template_id'];
        if ($email_settings_row['current_template_id'] != '0' && ($email_settings_row['quantity_sent'] < $email_settings_row['change_template_after'])) {
            $current_template_id = $email_settings_row['current_template_id'];
            $template_row = $db->query("SELECT * FROM email_templates WHERE id = '$current_template_id' AND autosend_five = '1' ")->fetch_array();
            $new_quantity_sent = $email_settings_row['quantity_sent'] + 1;
        }
        if (!$template_row) {
            $template_row = $db->query("SELECT * FROM email_templates WHERE id > '$current_template_id' AND autosend_five = '1' ORDER BY id ASC ")->fetch_array();
            $current_template_id = $template_row['id'];
        }
        if (!$template_row) {
            $template_row = $db->query("SELECT * FROM email_templates WHERE autosend_five = '1' ORDER BY id ASC ")->fetch_array();
            $current_template_id = $template_row['id'];
        }
        if ($template_row) {
            $client_postmark->sendEmail('no-reply@oxfordhomestudy.com', $email, $template_row['email_subject'], $template_row['email_body']);
            //smtp_mailer($email, $template_row['email_subject'], $template_row['email_body']);
            $db->query("UPDATE send_emails_settings SET current_template_id = '$current_template_id', quantity_sent = '$new_quantity_sent' WHERE id = '1'");
        }
    }
    // End sending email on five star rating
}
    //header("Location:form.php?msg=The course '$course_titless' has been added successfully");
    if(!$error['video_size'] || !$error['video']){
    $msg = "Thanks for submitting your review.";
    }

       /*Email to student AND Admin*/
    //mail($email,"Confirmation Massage",$message,$headers);
    //smtp_mailer($email,"Confirmation Massage",$message);
    $client_postmark->sendEmail('no-reply@oxfordhomestudy.com', $email, "Confirmation Massage",$message);
    //mail($admin_email,"Review Form Notification",$message_to_admin,$headers);
    //smtp_mailer($admin_email,"Review Form Notification",$message_to_admin);
    $client_postmark->sendEmail('no-reply@oxfordhomestudy.com', $admin_email,"Review Form Notification",$message_to_admin);

    /* smtpmailer($to,$from, $name_email ,$subject, $body);


       smtpmailer_std($to_std,$from_ad, $name_email ,$subject_std, $body_std);
    */
 
    /////////////////////

}

?>

<?php 
//include('header.php'); ?>
 <?php include("includes/header.php");
?>
 <div class="container" style="margin-bottom:5px;">


        <div class="row clearfix" >

          <div >
<img style="margin-bottom:-25px !important;" src="<?php echo $base_url; ?>/images/form_banner/<?php echo $banner_img;?>" alt="<?php //echo $banner['alt']; ?>" class="img-responsive banner-width" />
          
<div class="container">
        <div class="row spc_background" style="margin-right: -11px !important;">


            <div class="col-md-3" style="margin-top: 26px !important;">
                <p class="spc_para"><i><?php //echo $contact['slider_text']; ?>Home Study Experts</i></p>
            </div>

            <div class="col-md-1"></div>

            <div class="col-md-8" style="margin-bottom: -7px !important;display: block !important; padding-top: 29px !important;">
                <div class="pull-right">
                    <?php // $aw_bodies = FindAll('bodies', 'sort_order', 'DESC');

                    ?>
                    <img class="img-responsive img-float slid" src="images/logos_banner.png" />

                </div>
            </div>

        </div>
       </div>
          </div>
         
   
        </div>
        <br>
     
        <div class="row">
      <div> <h1 style="color:#09387c; font-size:27px; font-weight:600;"> <?php echo $form_heading;?> </h1></div>
      <!-- <div style="color:#757272;">Your Feedback could win you a prize!<div><br> -->
         <div style="color:#757272;">
          <?php echo $form_content;?>
         
          <div>
      <br><br>
      <div style="display: block;
                  height: 1px;
                  border: 0;
                  border-top: 1px solid #ccc;
                  margin: 1em 0;
                  padding: 0;"></div>    
    </div>
    </div>

<div class="row">
<div class="col-md-12">

    <?php  if(isset($msg) && !empty($msg)){?>
        <div class="alert alert-success">
        <?php echo $msg; ?>
      </div>
      <?php }else{
       if(isset($errors) && !empty($errors)){
       ?>
       
      <div class="alert alert-danger">
      <?php  foreach ($errors as $key => $value) { ?>
        <?php  echo $value; ?>
        <?php } ?>
          
        </div>
        <?php }} ?>
 

<form method="post" action="" enctype="multipart/form-data">
  <div class="arrow">
           <div class="inner-arrow"> <div><strong><?php echo $form_heading2;?></strong></div></div>
         </div>

        
    <p class="description">
     <?php echo $form_content2;?>
    
    </p>
    
<br>
 <div class="row">
  <div class="col-md-4 form-group course">
    <label for="titleInputcourse">Course title</label>
   
  </div>
  <div class="col-md-4 dropdown">
     <select  class="form-control"  name="course_title" required >
    <option selected disabled value="">Select Course</option>
    <?php 
    $menuqry = "SELECT * FROM tbl_diploma";

   $dip = $db->query($menuqry);
  
    while($diploma = $dip->fetch_array()){
      
    ?>
        <option value="<?php echo $diploma['diplomaid'];?>"><?php echo $diploma['name']; ?></option>
    <?php  } ?>  

   </select>
    
  </div>
  </div>
<br>


 <div class="row">
  <div class="col-md-4 form-group start">
    <label for="exampleInputEmail1">When did you start the course?</label>
   
  </div>
   <div class="col-md-6" > <input type='text' name="start_date" class='datepicker-here' data-language='en' /></div>
  
  
  </div>
  <div class="row">
  <div class="col-md-4 form-group mostcourse">
    <label for="exampleInputEmail1">What did you like most about the course?</label>
   
  </div>
  
  <div class="col-md-8 form-group">  
    <textarea class="form-control" name="about_course" id="about_course" rows="9" ></textarea>
    
    <!-- Button trigger modal -->
<a class="pull-right" type="button"  data-toggle="modal" data-target="#myModal">
  Click here to view guide lines
</a>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <a  class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></a>
        <h4 class="modal-title" id="myModalLabel">Instructions:</h4>
      </div>
      <div class="modal-body" style="margin-left: 21px !important;">

    <ul class="instruction">
      
       <?php echo $guidelines_two;?>
       </ul>

      </div>
      <div class="modal-footer">
        <a  class="btn btn-default" data-dismiss="modal">Close</a>
        
      </div>
    </div>
  </div>
</div>
    
  </div>
  
  </div>
  <div class="row">
  <div class="col-md-4 form-group">
    <label for="photo">Upload your photo</label>
   
  </div>
   <div class="col-md-4 form-group">
  
    <input type="text" name="" class="form-control" id="filename" aria-describedby="emailHelp">
    <p style="color: #c1c1c1; font-size: 12px;">Supported Formats: jpg, jpeg, png, gif, bmp</p>
  </div> 
  <div class="col-md-2 form-group">
    
   <label class="btn btn-default btn-file">
    Browse <input type="file" name="photo" id="file" style="display: none;" onchange="setfilename(this.value);" />

</label>
    
  </div>
  </div>
  <div class="row">
  <div class="col-md-4 form-group">
  
    <label for="exampleInputEmail1">Upload your Video</label>
   
  </div>
   <div class="col-md-4 form-group">
   
    <input type="text" name="" class="form-control" id="video" aria-describedby="emailHelp">
    <p style="color: #c1c1c1; font-size: 12px;">Supported Formats: mp4, avi, mov, flv, 3gp<br>Max Size 10 MB</p>
  </div>
  <div class="col-md-2 form-group">
   
   <label class="btn btn-default btn-file">
     Browse <input name="video" id="video" type="file" style="display: none;" onchange="setfilenamesss(this.value);"/>
</label>
    
  </div>
  </div>
  <div class="row">
  <div class="col-md-4 form-group">
    <label for="exampleInputEmail1">What did you like least?</label>
   
  </div>
  
  <div class="col-md-8 form-group">  
     <textarea class="form-control" name="like_at_least" id="like_at_least" rows="9" required></textarea>
     <a class="pull-right" type="button"  data-toggle="modal" data-target="#myModalaa">
  Click here to view guide lines
</a>
     <div class="modal fade" id="myModalaa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Instructions:</h4>
      </div>
      <div class="modal-body" style="margin-left: 21px !important;">
       
    <ul class="instruction">
       <?php echo $guidelines_one;?>
  </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div>
  
  </div>
  
  </div>
  
  <div class="row">
  <div class="col-md-4 form-group">
    <label for="rating">Overall rating</label>
   
  </div>
  <div class="col-md-4 form-group">
   <div class="rate" name="rating" required>
    <input type="radio" id="star5" name="rating" value="5" />
    <label for="star5" title="text">5 stars</label>
    <input type="radio" id="star4" name="rating" value="4" />
    <label for="star4" title="text">4 stars</label>
    <input type="radio" id="star3" name="rating" value="3" />
    <label for="star3" title="text">3 stars</label>
    <input type="radio" id="star2" name="rating" value="2" />
    <label for="star2" title="text">2 stars</label>
    <input type="radio" id="star1" name="rating" value="1" />
    <label for="star1" title="text">1 star</label>
  </div>
  
    
  </div>
  </div>
 
 <div class="row">
  <div class="col-md-4 form-group">
    <label for="exampleInputEmail1">Would you recommend this course?</label>
   
  </div>
  
  <div class="col-md-8 form-group" required>  
    <input type="radio" class="form-check-input" name="recomend" id="optionsRadios1" value="1" checked><span>yes</span>
     <input type="radio" class="form-check-input" name="recomend" id="optionsRadios1" value="0" ><span>no</span>
   
    
  </div>
  
  </div>
   <p>We need the following information in order to process your opinion about this course. These details will not be used for any other purpose.</p>
   
   <div class="row">
  <div class="col-md-4 form-group">
    <label for="exampleInputEmail1">Name</label>
   
  </div>
  <div class="col-md-2 form-group">
    <label for="name"></label>
    <input type="text" name="name" class="form-control" id="name" aria-describedby="emailHelp" required>
    
  </div>
  </div>
  <div class="row">
  <div class="col-md-4 form-group">
    <label for="surname">Surname</label>
   
  </div>
  <div class="col-md-2 form-group">
    <label for="surname"></label>
    <input type="text" name="surname" class="form-control" id="surname" aria-describedby="emailHelp" required>
    
  </div>
  </div>
  <div class="row">
  <div class="col-md-4 form-group">
    <label for="email">Email Address</label>
   
  </div>
  <div class="col-md-2 form-group">
    <label for="email"></label>
    <input type="text" name="email" class="form-control" id="email" aria-describedby="emailHelp" required>
    
  </div>
  </div>
  <div class="row">
      <div class="col-md-4 form-group">
        <label for="country">Country</label>
      </div>
      <div class="col-md-2 form-group">
          <label for="country"></label>
          <select name="country" class="form-control">
              <?php
              $country_result = $db->query("SELECT name FROM country");
              while ($country_row = $country_result->fetch_array()) {
              ?>
              <option value="<?php echo $country_row['name']; ?>"><?php echo $country_row['name']; ?></option>
              <?php } ?>
          </select>
      </div>
  </div>
  <div class="row">
  <div class="col-md-4 form-group">
    <label for="telephon">Telephone</label>
   
  </div>
  <div class="col-md-2 form-group">
    <label for="telephon"></label>
    <input type="text" name="telephon" class="form-control" id="telephon" aria-describedby="emailHelp" >
    
  </div>
  </div>
  
  <div class="row">
  <div class="col-md-4 form-group">
   
   
  </div>
  <div class="col-md-8 form-group">
    <div class="form-check">
    <label class="form-check-label">
      <input type="checkbox" class="form-check-input" required>
      I have read and accept the <span><a href="<?php echo $base_url;?>/privacy-policy" target="_blank">privacy policy</a></span> the <span><a href="<?php echo $base_url;?>/terms-and-Conditions" target="_blank">terms and conditions for publishing opinions</a></span> and <span><a href="<?php echo $base_url;?>/competition-rules" target="_blank">Competition rules</a></span>
    </label>
   
  </div>
    
  </div>
  
  </div>
  <div class="row">
   <div class="col-md-4 form-group">
   
   
  </div>
  <div class="col-md-8 form-group">
   <input type="submit" value="Send Review" name="save" class="btn btn-primary">
  </div>
  </div>
  
</form>

<div class="row">
   <div class="col-md-12">
   <!-- <img class="img-responsive" src="css/images/img.jpg" />
    -->
   <img  src="<?php echo $base_url; ?>/images/form_banner/<?php echo $footer_img;?>"  class="img-responsive banner-width" />
          
   </div>
   </div>
</div>
</div>

</div>
<br>
</div>



<?php include("includes/footer.php"); ?>
 <script>
   function setfilename(val)
  {
    var fileName = val.substr(val.lastIndexOf("\\")+1, val.length);
   document.getElementById("filename").value = fileName;
  };

  function setfilenamesss(val)
  {
    var video = val.substr(val.lastIndexOf("\\")+1, val.length);
   document.getElementById("video").value = video;
  }


</script>