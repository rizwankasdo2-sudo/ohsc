<?php
session_start();
ob_start();
if ($_SESSION['adminid']== "") {
	header("location:index.php");
	exit();
}
include("classes/connection.php");
include("includes/related-diploma-helper.php");
if (isset($_POST['NewsDetail']) && isset($_POST['status'])) {
	$category_id =   $_POST['category_id'];
	$subcat_ids = ($_POST['subcat_ids']) ? implode(',', $_POST['subcat_ids']) : '';
	$cat_ids = ($_POST['cat_ids']) ? implode(',', $_POST['cat_ids']) : '';
	$course_id =   $_POST['diploma_id'];
    $NewsDetail =   $_POST['NewsDetail'];
	$description =$_POST['description'];
	$status  =  $_POST['status'];
	$ordernumber  =  $_POST['ordernumber'];
	$show_in_free_courses  =  ($_POST['show_in_free_courses'] == 1) ? 1 : 0;
	$show_on_home_page  =  ($_POST['show_on_home_page'] == 1) ? 1 : 0;
	$show_in_classifications = ($_POST['show_in_classifications']) ? $_POST['show_in_classifications'] : '';
	$show_in_faq_topics = ($_POST['show_in_faq_topics']) ? implode(', ', $_POST['show_in_faq_topics']) : '';
	$related_diploma_ids = formatRelatedDiplomaIds(isset($_POST['related_diploma_ids']) ? $_POST['related_diploma_ids'] : []);
	$qry="insert into tbl_faq(category_id, subcat_ids, cat_ids, course_id,title,detail,IStatus,show_in_free_courses,show_on_home_page,show_in_classifications,show_in_faq_topics,related_diploma_ids,ordernumber) values('$category_id', '$subcat_ids', '$cat_ids', '$course_id','$NewsDetail','$description','$status','$show_in_free_courses', '$show_on_home_page', '$show_in_classifications', '$show_in_faq_topics', '$related_diploma_ids', $ordernumber)";
	$db->query($qry) or die("insert query error").mysqli_error($db);
	$msg = "Inserted Successfully";
	echo "<script language='javascript'>window.location='faq.php?msg=$msg'</script>";
} else {
	$msg="you cant pass empty fields";
	echo "<script language='javascript'>window.location='album.php?msg=$msg'</script>";
}
