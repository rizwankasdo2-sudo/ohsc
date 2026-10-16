<?php
session_start();
ob_start();
if($_SESSION['adminid']== "")
{
    header("location:index.php");
    exit();
}
include("classes/connection.php");
if(isset($_POST['coursename'])&& isset($_POST['order'])  && isset($_POST['cid']))
{
    $name =   $_POST['coursename'];
    if (!isset ($_POST['slug']) || $_POST['slug'] == '')
    {
        $slug = str_replace(' ', '-', strtolower($name));
    }
    else
    {
        $slug = str_replace(' ', '-', strtolower($_POST['slug']));
    }
    $order = $_POST['order'];
    $portal_course_id = empty($_POST['portal_course_id']) ? 0 : $_POST['portal_course_id'];
    $thumbnail = time().$_FILES['thumbnail']['name'];
    $tmp_name = $_FILES['thumbnail']['tmp_name'];
    $dir_name = '../images/subject';
    $destination = $dir_name.'/'.$thumbnail;
    move_uploaded_file($tmp_name, $destination);
    $status  =  ($_SESSION['adminType']== 2) ? $_POST['status'] : 1;
    $famous_course  =  $_POST['famous_course'];
    $top_diploma  =  $_POST['top_diploma'];
    $top_certification  =  $_POST['top_certification'];
    $new_course  =  $_POST['new_course'];
    $cid = $_POST['cid'];
    $type = $_POST['type'];
    $admin_total_enrolled = empty($_POST['admin_total_enrolled']) ? 0 : $_POST['admin_total_enrolled'];
    $admin_rating = empty($_POST['admin_rating']) ? 0 : $_POST['admin_rating'];
    $total_ratings = empty($_POST['total_ratings']) ? 0 : $_POST['total_ratings'];
    $total_ratings_five = empty($_POST['total_ratings_five']) ? 0 : $_POST['total_ratings_five'];
    $total_ratings_four = empty($_POST['total_ratings_four']) ? 0 : $_POST['total_ratings_four'];
    $total_ratings_three = empty($_POST['total_ratings_three']) ? 0 : $_POST['total_ratings_three'];
    $total_ratings_two = empty($_POST['total_ratings_two']) ? 0 : $_POST['total_ratings_two'];
    $total_ratings_one = empty($_POST['total_ratings_one']) ? 0 : $_POST['total_ratings_one'];
    $added_on_portal = $_POST['added_on_portal'];
    $classification = $_POST['classification'];
    $square_icon = '';
    // Begin square thumbnail handling
    if (isset($_FILES['square_icon']['name']) && !empty($_FILES['square_icon']['name'])) {
        $square_icon = time() . rand(999, 99999) . rand(999, 99999) . $_FILES['square_icon']['name'];
        move_uploaded_file($_FILES['square_icon']['tmp_name'], '../images/subject/' . $square_icon);
    }
    $certificate_ids = ($_POST['certificate_ids']) ? implode(', ', $_POST['certificate_ids']) : '';
    // End square thumbnail handling
    $msg = "Slug already exists";
    
    $qry = $db->query("insert into tbl_diploma(cid,name,slug,ordernumber,thumbnail,IStatus,famous_course,top_diploma,top_certification,new_course,type,square_icon, admin_total_enrolled, admin_rating, total_ratings, total_ratings_five, total_ratings_four, total_ratings_three, total_ratings_two, total_ratings_one, added_on_portal, classification, portal_course_id, certificate_ids) values($cid,'$name','$slug',$order,'$thumbnail',$status,'$famous_course', $top_diploma,$top_certification,$new_course,'$type', '$square_icon', '$admin_total_enrolled', '$admin_rating', '$total_ratings', '$total_ratings_five', '$total_ratings_four', '$total_ratings_three', '$total_ratings_two', '$total_ratings_one', '$added_on_portal', '$classification', '$portal_course_id', '$certificate_ids')") or die(mysqli_error($db));
    $msg = "Inserted Successfully";
    header("location: diploma.php?cid=$cid&msg=$msg");
    echo "<script language='javascript'>window.location='diploma.php?cid=$cid&msg=$msg'</script>";
}
else{
    $msg="Not allowed empty fields";
    header("location: cours.php?msg=$msg");
    echo "<script language='javascript'>window.location='cours.php?msg=$msg'</script>";
}
?>