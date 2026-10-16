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
		
		  $Description   =   $_POST['description'];
		  $show_in_footer_sections = isset($_POST['show_in_footer_sections']) ? $_POST['show_in_footer_sections'] : [];
    	  $show_in_footer_sections = implode(',', $show_in_footer_sections);
		  
		  
		  $db->query("update tbl_aboutus set 
	cAboutusText='$Description', show_in_footer_sections = '$show_in_footer_sections' where cAboutusID=$id");
		  
		  $msg = "About Us Data Updated Successfully";
		  echo "<script language='javascript'>window.location='about_page.php?msg=$msg'</script>";

?>