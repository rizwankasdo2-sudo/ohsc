<?php
session_start();
ob_start();
if($_SESSION['adminid']== "")
{
	header("location:index.php");
	exit();
}

     include("classes/connection.php");
	 
	 $vari = $_GET['case'];
	 
	 if (isset ($_FILES['banerpath']['name']) && isset ($_POST['status']))
	 {
		 $path    =  $_FILES['banerpath']['name'];
		 $statuss =   $_POST['status'];
	 }
	 
	 if (isset($_POST['id']))
	 {
		 $act = $_POST['id'];
	 }
	 
	 if (isset($_GET['id']))
	 {
		 $delbaner = $_GET['id'];
	 }
	 
	 switch ($vari)
	 {
		 case "addbanner":
		 
		    if (($_FILES["banerpath"]["type"] == "application/pdf") )
			{
				if ($_FILES["banerpath"]["error"] > 0)
				{
					$no = "Error: " . $_FILES["file"]["error"] . "<br />";
					echo "<script language='javascript'>window.location='prospectus.php?no=$no'</script>";
				}
				else
				{
					move_uploaded_file($_FILES["banerpath"]["tmp_name"], "../Resources/logo_images/" . $path);
					
					$insertBanner = "insert into tbl_logo(cBannerPath,cStatus)values('$path',$statuss)";
					
					$insertbannerTOtable = $db->query($insertBanner);
					$msg = "Inserted Successfully";
					echo "<script language='javascript'>window.location='prospectus.php?msg=$msg'</script>";
				}
			}
			
			else
			{
				$invalid = "Invalid file";
				echo "<script language='javascript'>window.location='prospectus.php?invalid=$invalid'</script>";
			}
			
			break;
		 
		 case "updatebanner":
		 
		   $updateimage = "SELECT * FROM tbl_logo WHERE clogoID=$act";
		   $imagefield = $db->query($updateimage);
		   $row = $imagefield->fetch_array();
		   $image=$row['cBannerPath'];
		   /*$nameofimage =$row['cBannerName'] ;*/
		   $msg = "Updated Successfully";
		   
		   if ($path == "")
		   {
			    $updatebanners = $db->query("UPDATE tbl_logo SET                   
					                                 cBannerPath='$image',cStatus=$statuss WHERE clogoID=$act");
					   
				$updatebannerTOtable = $db->query($updatebanners);
				echo "<script language='javascript'>window.location='prospectus.php?msg=$msg'</script>";

		   }
		   
		   else
		   {
			   if (($_FILES["banerpath"]["type"] == "application/pdf"))
			   {
				   if ($_FILES["banerpath"]["error"] > 0)
				   {
					   $no = "Error: " . $_FILES["file"]["error"] . "<br />";
					   echo "<script language='javascript'>window.location='prospectus.php?no=$no'</script>";
				   }
				   else
				   {
					   unlink("../Resources/logo_images/".$image);
					   move_uploaded_file($_FILES["banerpath"]["tmp_name"], "../Resources/logo_images/" . $path);
					   $updatebanners = $db->query("UPDATE tbl_logo SET cBannerPath='$path',cStatus=$statuss WHERE clogoID=$act");
					   
					   $updatebannerTOtable = $db->query($updatebanners);
					   echo "<script language='javascript'>window.location='prospectus.php?msg=$msg'</script>";
				   }
			   }
			   
			   else
			   {
				   $invalid = "Invalid file";
				   echo "<script language='javascript'>window.location='prospectus.php?invalid=$invalid'</script>";
			   }
		   }
		   
		   break;
		 
		 case "deletebanner":
		 
		   $updateimage = "SELECT * FROM tbl_logo WHERE clogoID=$delbaner";
		   $imagefield = $db->query($updateimage);
		   $row = $imagefield->fetch_array();
		   $image=$row['cBannerPath'];
		   unlink("../Resources/logo_images/".$image);
		   
		   $deltebannerfromtable = $db->query("DELETE FROM tbl_logo WHERE clogoID=$delbaner");
		   $msg = "Deleted Sucessfully";
		   echo "<script language='javascript'>window.location='prospectus.php?msg=$msg'</script>";
		   
		   break;
		   
	 }
			 
?>		 
				 