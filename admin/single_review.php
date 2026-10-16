<?php
include("classes/connection.php");
/*
if($_SESSION['adminid']== "") {
  header("location:index.php");
  exit();
}*/
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <style type="text/css">
    table {
        border-collapse: collapse;
    }
    table,
    th,
    td {
        border: 1px solid #D2D0D1;
    }
    </style>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>View Student Review</title>
    <link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />
    <link rel="stylesheet" href="../Resources/admin_css/add-product.css" />
    <script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>
    <script type="text/javascript" src="cms/ckeditor/ckeditor.js"></script>
    <script src="cms/ckeditor/sample.js" type="text/javascript"></script>
    <!--[if lte IE 6]>
<script type="text/javascript" src="../Resources/admin_js/supersleight-min.js"></script>
<![endif]-->
</head>
<body>
    <div id="wrap_dashboard">
        <?php
   include("includes/admin-header.php");
   include("includes/admin-menu.php");
?>
        <div id="outer_contanier">
            <div id="board">
                <div id="left_corner">
                </div>
                <?php
     $id = $_GET['id'];
  
   if(isset($_POST['update'])){
  
    $share = ($_POST['share']) ? implode(',', $_POST['share']) : 0;
    $about_course = $_POST['about_course'];
    $like_at_least = $_POST['like_at_least'];
    $ordernumber = $_POST['ordernumber'];
   
      $update = "UPDATE review_form SET share = '$share', about_course = '$about_course', like_at_least = '$like_at_least', ordernumber = '$ordernumber' WHERE id = '$id'";
       
       $updated = $db->query($update);
       if($updated){
       $msg = "Selective section(s) have been shared on the respective course page";
     }
    }
?>
                <?php
if(isset($_GET['id']) && is_numeric($_GET['id'])){
   $id = $_GET['id'];
   $query = "SELECT D.name AS diplomaName, D.diplomaid, R.* FROM `review_form` AS R INNER JOIN `tbl_diploma` AS D ON R.course_title = D.diplomaid WHERE id='$id'";
   $result = $db->query($query);
   if($result->num_rows == 0){
       header('location: pages.php');
       exit();
   }
   $page = $result->fetch_array();
  $course_title  =   $page['diplomaName'];
  $Stardate =  $page['start_date'];
  $about_course = $page['about_course'];
  $photo = $page['photo'];
  $video = $page['video'];
  $like_at_least = $page['like_at_least'];
  $rating = $page['rating'];
  $recomend = $page['recomend'];
  $name = $page['name'];
  $surname = $page['surname'];
  $email = $page['email'];
  $telephon = $page['telephon'];
  $share = $page['share'];
  $submited_date = $page['submited_date'];
  $ordernumber = $page['ordernumber'];
  $id = $page['id'];
}
?>
                <div id="midle_repeat">
                    <div id="tit">
                        <h3>Student Review</h3>
                    </div>
                </div>
                <div id="right_corner">
                </div>
                <div id="main_content">
                    <?php if (isset($msg)) {
      echo '<p style="font-size:25px; text-align:center; background-color:#DFF2BF;">';
      echo $msg;
      echo '</p>';
    }
    elseif (isset($_GET['no'])) {
      echo $no;
    }
    elseif (isset($_GET['invalid'])) {
      echo '<p style="color:black; font-size:28px; text-align:center; font-weight:bold">';
      echo $_GET['invalid'];
      echo '</p>';
    }
?>
                    <div id="addproducts">
                        <div id="uper">
                            <div id="uperdetail" style="border: none">
                                <div id="general">
                                    <div class="row">
                                        <form action="" method="post">
                                            <?php
               $sharee = array_map('trim', explode(",", $share));
              ?>
                                            <input type="hidden" name='id' value="<?php echo $id;?>">
                                            <table class="col-md-10 table table-hover" width="100%" height="20%">
                                                <tr style="height: 45px;">
                                                    <th width="20%">Course Title</th>
                                                    <td><?php echo $course_title;?></td>
                                                    <td></td>
                                                </tr>
                                                <tr style="height: 45px;">
                                                    <th width="20%">Course Start Date</th>
                                                    <td><?php echo date('d/m/Y', strtotime($Stardate));?></td>
                                                    <td></td>
                                                </tr>
                                                <tr style="height: 45px;">
                                                    <th width="20%">Image</th>
                                                    <td>
                                                        <?php if(!empty($photo)){?>
                                                        <img src="../images/form_photo/<?php echo $photo;?>" />
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if(!empty($photo)){?>
                                                        Share <input type="checkbox" value="1" name="share[]"
                                                            <?=(in_array('1', $sharee)?'checked="checked"':NULL)?> />
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <tr style="height: 45px;">
                                                    <th width="20%">Video</th>
                                                    <td>
                                                        <?php  
                           $paths = '../images/form_videos/';
                           $filenames =  $video;
                         //  echo $filenames;
                          // echo $filenames;
                     if(!empty($video) && file_exists($paths.$filenames)){ ?>
                                                        <video width="150" height="160" controls>
                                                            <source type="video/mp4"
                                                                src="../images/form_videos/<?php echo $video;?>" />
                                                            <source type="video/3gpp"
                                                                src="../images/form_videos/<?php echo $video;?>" />
                                                            <source type="video/avi"
                                                                src="../images/form_videos/<?php echo $video; ?>" />
                                                            <source type="video/mov"
                                                                src="../images/form_videos/<?php echo $video; ?>" />
                                                            <source type="video/flv"
                                                                src="../images/form_videos/<?php echo $video; ?>" />
                                                        </video>
                                                        <?php }?>
                                                    </td>
                                                    <td>
                                                        <?php  if(!empty($video) && file_exists($paths.$filenames)){ ?>
                                                        Share <input type="checkbox" value="2" name="share[]"
                                                            <?=(in_array('2', $sharee)?'checked="checked"':NULL)?> />
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <tr style="height: 45px;">
                                                    <th width="20%">Rating</th>
                                                    <td>5</td>
                                                    <td></td>
                                                </tr>
                                                <tr style="height: 45px;">
                                                    <th width="20%">When did you like most about the course?</th>
                                                    <td><textarea cols="110" rows="10"
                                                            name="about_course"><?php echo $about_course; ?></textarea>
                                                    </td>
                                                    <td>
                                                        <?php if(!empty($about_course)){?>
                                                        Share <input type="checkbox" value="3" name="share[]"
                                                            <?=(in_array('3', $sharee)?'checked="checked"':NULL)?> />
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <tr style="height: 45px;">
                                                    <th width="20%">What did you like least?</th>
                                                    <td><textarea cols="110" rows="10"
                                                            name="like_at_least"><?php echo $like_at_least;?></textarea>
                                                    </td>
                                                    <td>
                                                        <?php if(!empty($like_at_least)){?>
                                                        Share <input type="checkbox" value="4" name="share[]"
                                                            <?=(in_array('4', $sharee)?'checked="checked"':NULL)?> />
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <tr style="height: 45px;">
                                                    <th width="20%">Recomended</th>
                                                    <td><?php if($recomend==1){echo 'YES';}else{echo 'NO';}?></td>
                                                    <td></td>
                                                </tr>
                                                <tr style="height: 45px;">
                                                    <th width="20%">Name</th>
                                                    <td><?php echo $name;?></td>
                                                    <td></td>
                                                </tr>
                                                <tr style="height: 45px;">
                                                    <th width="20%">Email</th>
                                                    <td><?php echo $email;?></td>
                                                    <td></td>
                                                </tr>
                                                <tr style="height: 45px;">
                                                    <th width="20%">Phone</th>
                                                    <td><?php echo $telephon;?></td>
                                                    <td></td>
                                                </tr>
                                                <tr style="height: 45px;">
                                                    <th width="20%">Order Number</th>
                                                    <td><input type="number" name="ordernumber" value="<?php echo $ordernumber; ?>" /></td>
                                                    <td></td>
                                                </tr>
                                                <tr style="height: 45px;">
                                                    <th width="20%">Action</th>
                                                    <td><input type="submit" name="update" value="Share"
                                                            style="width:70px;height:30px;">
                                                        <button style="width:70px;height:30px;"><a
                                                                href="stdreviews.php">Back</a></button>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </form>
                                    </div>
                                    <div class="clear">
                                    </div>
                                    <!--  <div id="buttons" style=" width:713px; margin:0 auto; margin-top:15px;"> 
                   
                   <input type="submit" name="submit" id="submit" value="Submit" />
                   
                   </div> -->
                                </div>
                            </div>
                            <div class="clear">&nbsp;
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clear">&nbsp;</div>
                <!-- </form> -->
            </div>
            <div class="clear">&nbsp;</div>
            <?php include("includes/admin-footer.php"); ?>
        </div>
    </div>
    <script>
    CKEDITOR.replace('NewsDetail', {
        filebrowserBrowseUrl: 'cms/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl: 'cms/ckfinder/ckfinder.html?Type=Images',
        filebrowserFlashBrowseUrl: 'cms/ckfinder/ckfinder.html?Type=Flash',
        filebrowserUploadUrl: 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl: 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        filebrowserFlashUploadUrl: 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
    });
    </script>
    <script>
    CKEDITOR.replace('description', {
        filebrowserBrowseUrl: 'cms/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl: 'cms/ckfinder/ckfinder.html?Type=Images',
        filebrowserFlashBrowseUrl: 'cms/ckfinder/ckfinder.html?Type=Flash',
        filebrowserUploadUrl: 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl: 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        filebrowserFlashUploadUrl: 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
    });
    </script>
    <script>
    CKEDITOR.replace('excerpt', {
        filebrowserBrowseUrl: 'cms/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl: 'cms/ckfinder/ckfinder.html?Type=Images',
        filebrowserFlashBrowseUrl: 'cms/ckfinder/ckfinder.html?Type=Flash',
        filebrowserUploadUrl: 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl: 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        filebrowserFlashUploadUrl: 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
    });
    </script>
</body>
</html>