<?php
session_start();
ob_start();
if($_SESSION['adminid']== "")
{
	header("location:index.php");
	exit();
}
	  
	  include("classes/connection.php");
	  
	  //  Add, Update, Delete Data in tbl_product

		  $id   =   $_POST['id'];
		
		  $short_description   =   $_POST['short_description'];
          $Description   =   $_POST['description'];
          $bottom_text_intro   =   $_POST['bottom_text_intro'];
		  $bottom_text   =   $_POST['bottom_text'];
		  $show_in_footer_sections = isset($_POST['show_in_footer_sections']) ? $_POST['show_in_footer_sections'] : [];
    	  $show_in_footer_sections = implode(',', $show_in_footer_sections);

		  
		  $db->query("update tbl_ohltv set short_description = '$short_description', detail='$Description', bottom_text_intro = '$bottom_text_intro', bottom_text = '$bottom_text', show_in_footer_sections = '$show_in_footer_sections' where ohlid=$id");
		  
		  $msg = "Updated Successfully";
		  header('location:pages.php?msg=' . $msg);
		  exit;
