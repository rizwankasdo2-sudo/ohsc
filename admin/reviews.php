<?php
session_start();
if($_SESSION['user_id']=="") {
    header("Location: login.php");
}
$pagename = "reviews.php";
$pagetitle = "Reviews";
include "../includes/database.php";
include "header.php";

if(isset($_GET['del']) && is_numeric($_GET['del'])){
    $del = $_GET['del'];
    $sql_delete = $db->query("DELETE FROM info WHERE id='$del'");
}

require "../includes/pagination.php";

$page = 1;
$size = 10;

if (isset($_GET['page'])){
    $page = (int) $_GET['page'];
}

    $sql = "SELECT
    `review_form`.`id`
    , `review_form`.`course_title`
    , `review_form`.`start_date`
     , `review_form`.`submited_date`
    , `review_form`.`about_course`
    , `review_form`.`photo`
    , `review_form`.`video`
    , `review_form`.`like_at_least`
    , `review_form`.`rating`
    , `review_form`.`recomend`
    , `review_form`.`name`
    , `review_form`.`surname`
    , `review_form`.`email`
     , `review_form`.`telephon`
    , `courses_new`.`coursetitle`
FROM
    `review_form`
    INNER JOIN `courses_new` 
        ON (`review_form`.`course_title` = `courses_new`.`id`);  ORDER BY id DESC LIMIT 60";


$result = $db->query($sql);
$total_records = $result->rowCount();
?>
 <button style="float: right !important;
    margin-right: 28px !important;" class="btn button-home"><a class="h-text" href="form_manage.php">Manage Reviews Form</a></button> 
    <!--  <a href="form_manage.php">Manage Reviews Form </a> -->
<?php
if($_GET['msg']!="") { ?>
    <div class="check_main">
    <div class="check">
        <div class="good"><img src="images/check.gif" alt="check" class="icon" /><?php echo $_GET['msg'];?></div>
    </div>
<?php } ?>
        
                
    <div class="in">
        <div class="filter_dates">
            <!-- <form action="" method="post">
                <input type="date" name="start_date" class="box">
                <input type="date" name="end_date" class="box">
                <input name="submit_date" type="submit" id="submit" tabindex="5" class="com_btn" value="Submit">
            </form> -->
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Serial #</th>
                    <th width="20%">Course Title</th>
                     <th>Name</th>
                     <th>Email</th>
                    <th>Submitted Date</th>
                    
                    
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
           $counter = 0;
            while($row=$result->fetch()){

               /* $course = $row['course'];
                $course_query = $db->query("SELECT coursetitle FROM courses_new WHERE id='$course'");
                $course_row = $course_query->fetch();
                $courseFound = $course_row['coursetitle'];*/
                ?>
                <tr>
                    <td><?php echo ++$counter; ?></td> 
                    <td><?php echo $row['coursetitle']; ?></td>
                     <td><?php echo $row['surname']; ?>  <?php echo $row['name']; ?></td>
                     <td><?php echo $row['email']; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['submited_date'])); ?></td>
                   
                   
                    <td>
                       <a href="single_reviews.php?id=<?php echo $row['id']; ?>">View</a>
                        <input type="hidden" class="idfound" value="<?php echo $row['id']; ?>"/>
                    | <a onclick="return confirm('Are you sure you want to delete this Review?');" href="delete_review.php?del=<?php echo $row['id']; ?>">Delete</a></td>
                </tr>
                <?php
                $counting++;
            } ?>
            </tbody>
        </table>
    </div>

<script type="text/javascript" src="js/jquery-1.12.3.min.js" />

<?php
include "footer.php";
?>