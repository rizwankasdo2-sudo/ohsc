<?php
session_start();
if($_SESSION['user_id']=="") {
    header("Location: login.php");
}
$pagename = "reviews.php";
$pagetitle = "Course Review";
include "../includes/database.php";
include "header.php";

if(isset($_GET['del']) && is_numeric($_GET['del'])){
    $del = $_GET['del'];
    $sql_delete = $db->query("DELETE FROM info WHERE id='$del'");
}
$id = $_GET['id'];
require "../includes/pagination.php";

$page = 1;
$size = 10;

if (isset($_GET['page'])){
    $page = (int) $_GET['page'];
}
 // print_r($_POST['share']);
  $id = $_GET['id'];
  
   if(isset($_POST['update'])){

  
    $share = implode(',', $_POST['share']);
    $about_course = $_POST['about_course'];
    $like_at_least = $_POST['like_at_least'];

   
      $update = "UPDATE review_form SET share = :share, about_course = :about_course, like_at_least = :like_at_least WHERE id = :id";
       $stmt = $db->prepare($update);
       $stmt->bindValue(':share', $share);
       $stmt->bindValue(':about_course', $about_course);
       $stmt->bindValue(':like_at_least', $like_at_least);
       $stmt->bindValue(':id', $id);
       $stmt->execute();
       $errors = $stmt->errorInfo();
       $msg = "Selective section(s) have been shared on the respective course page";
    }

    $sql = "SELECT review_form.*,courses_new.coursetitle,courses_new.id AS course_id FROM review_form
    INNER JOIN courses_new
    ON (review_form.course_title = courses_new.id)
     WHERE review_form.id=:id";
    $result = $db->prepare($sql);
    $values = [':id'=>$id];
    $result->execute($values);
    $row = $result->fetch();

if(isset($msg) && !empty($msg)) { ?>
    <div class="check_main">
    <div class="check">
        <div class="good"><img src="images/check.gif" alt="check" class="icon" /><?php echo $msg; ?></div>
    </div>
<?php } ?>

    <div class="col-md-12 in">
        <div class="filter_dates">
            <!-- <form action="" method="post">
                <input type="date" name="start_date" class="box">
                <input type="date" name="end_date" class="box">
                <input name="submit_date" type="submit" id="submit" tabindex="5" class="com_btn" value="Submit">
            </form> -->
        </div>
        <div class="container">
        <div class="row">

        
   <form action="" method="post">
    <input type="hidden" name='id' value="<?php echo $id;?>">
        <table class="col-md-10 table">
           
            <tbody>
            <?php
            $counting = 1;
            $rew = $result->fetch();

            //print_r($rew);
              $sharee = array_map('trim', explode(",", $row['share']));
           ?>
          
                <tr>
               
                   <th>Course Title</th>
                    <td width="77%"><?php echo $row['coursetitle']; ?></td>
                </tr>
                <tr>
                    <th>Course Start Date</th>
                    <td><?php echo date('d/m/Y', strtotime($row['start_date'])); ?></td>
                 </tr>
                 <tr>
                    <th>Image</th>
                     <?php  
                           $path = '../images/form_photo/';
                           $filename =  $row['photo'];
 // echo $filename;
                       ?>  
                     
                    <td>
                 <?php   if(!empty($row['photo']) && file_exists($path.$filename)){ ?>
                      <img src="../images/form_photo/<?php echo $row['photo'];?>" style="width: 150px;">
                     Share <input type="checkbox" value="1" name="share[]" <?=(in_array('1', $sharee)?'checked="checked"':NULL)?> />
                        <?php } ?>
                    </td>
                 </tr>
                 
                 <tr>   
                    <th>Video</th>
                     
                    <td>
                       <?php  
                           $paths = '../images/form_videos/';
                           $filenames =  $row['video'];

                         //  echo $filenames;
                          // echo $filenames;
                     if(!empty($row['video']) && file_exists($paths.$filenames)){ ?>

                      <video width="150" height="160" controls>
                     <source type="video/mp4" src="../images/form_videos/<?php echo $row['video']?>"  />
                     <source type="video/3gpp"  src="../images/form_videos/<?php echo $row['video']?>" />
                     <source type="video/avi"  src="../images/form_videos/<?php echo $row['video']?>" />
                     <source type="video/mov"  src="../images/form_videos/<?php echo $row['video']?>" />
                     <source type="video/flv"  src="../images/form_videos/<?php echo $row['video']?>" />
                     
                     </video> 
                 Share <input type="checkbox" value="2" name="share[]" <?=(in_array('2', $sharee)?'checked="checked"':NULL)?>/>
                 <?php } ?>
                  
                 </td>

<iframe width="480" height="320" src="http://player.ooyala.com/iframe.html?
ec=embedcode
&pbid=player_branding_id
&docUrl=http%3A%2F%2Fexample.com
&platform=platform
&options[optionname]=value&options[optionname]=value..."
frameborder="0" allowfullscreen>
</iframe>  
                 
                   </tr>

                   <tr>
                    <th>Rating</th>
                    <td><?php echo $row['rating']; ?></td>
                </tr>
                <tr>
                    <th>When did you like most about the course?</th>
                    <td>
                  
  <div class="form-group">

  <textarea name="about_course" class="textarea form-control" rows="10" cols="120" id="comment"><?php echo $row['about_course']; ?></textarea>
</div>

                   <br><br> Share <input type="checkbox" value="3" name="share[]" <?=(in_array('3', $sharee)?'checked="checked"':NULL)?> />
                    </td>
                 </tr>

                 <tr>
                    <th>What did you like least?</th>
                    <td>

   <div class="form-group">

  <textarea name="like_at_least" class="form-control" rows="10" cols="120" id="comment"><?php echo $row['like_at_least']; ?></textarea>
</div>
                   
                   <br> <br> Share <input type="checkbox" value="4" name="share[]" <?=(in_array('4', $sharee)?'checked="checked"':NULL)?> />
                    </td>
                </tr>

                <tr>
                    <th>Recomended</th>
                    <td><?php if($row['recomend']==1){echo 'YES';}else{echo 'NO';} ?></td>
                  </tr>
                <tr>
                    <th>Name</th>
                    <td><?php echo $row['surname'];?>  <?php echo $row['name'] ?></td>
                </tr>
                  <tr>
                    <th>Email</th>
                     <td><?php echo $row['email']; ?></td>
                    </tr> 
                    <tr>
                       <th>Phone</th>
                      <td><?php echo $row['telephon']; ?></td>
                  </tr>
                  <tr>
                    <th>Action</th>
                    <td>
                        <input type="submit" name="update" value="Share">
                        <button ><a href="reviews.php">Back</a></button>
                        
                        <input type="hidden" class="idfound" value="<?php echo $row['id']; ?>"/>
                    </td>
                    <!-- <td><a href="info.php?del=<?php echo $row['id']; ?>">Delete</a></td> -->
                </tr>

                <?php
              
                $counting++;
            ?>
            </tbody>
        </table>
    </form>
    </div>
    </div>
  </div>
  </div>
 

    </div>

<script type="text/javascript" src="js/jquery-1.12.3.min.js" />

<?php
include "footer.php";
?>