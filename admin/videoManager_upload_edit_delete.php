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
	 
	 if (isset ($_POST['banername']) && isset ($_FILES['banerpath']['name']) && isset ($_POST['videolink']) && isset ($_POST['status']))
	 {
		 $title   =   $_POST['banername'];
		 $path    =  $_FILES['banerpath']['name'];
		 $videolink = $_POST['videolink'];
		 $statuss =   $_POST['status'];
		 $dateinsert = date('Y-m-d');
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
		 
		    if (($_FILES["banerpath"]["type"] == "image/jpeg") || ($_FILES["banerpath"]["type"] == "image/pjpeg"))
			{
				if ($_FILES["banerpath"]["error"] > 0)
				{
					$no = "Error: " . $_FILES["file"]["error"] . "<br />";
					echo "<script language='javascript'>window.location='videoManager.php?no=$no'</script>";
				}
				else
				{
					move_uploaded_file($_FILES["banerpath"]["tmp_name"], "../Resources/video_images/" . $path);
					
					$insertBanner = "insert into tbl_videos(vVideoName,vVideoImage,vVideoLink,vVideoViews,vVideoDate,vStatus) values ('$title','$path','$videolink',0,'$dateinsert',$statuss)";
					
					$insertbannerTOtable = $db->query($insertBanner);
					$msg = " Inserted Successfully";
					echo "<script language='javascript'>window.location='videoManager.php?msg=$msg'</script>";
				}
			}
			
			else
			{
				$invalid = "Invalid file";
				echo "<script language='javascript'>window.location='videoManager.php?invalid=$invalid'</script>";
			}
			
			break;
		 
		 case "updatebanner":
		 
		  $videolink = $_POST['videolink'];
		 $statuss =   $_POST['status'];
		 $title = $_POST['banername'];
		 $path    =  $_FILES['banerpath']['name'];
		 
		   $updateimage = "SELECT * FROM  tbl_videos WHERE vVideoID=$act";
		   $imagefield = $db->query($updateimage);
		   $row = $imagefield->fetch_array();
		   $image=$row['vVideoImage'];
		  
		   $msg = "Video Manager Data Updated Successfully";
		   
		   if ($path == "")
		   {
			    $updatebanners = $db->query("UPDATE  tbl_videos SET vVideoName='$title',vVideoImage='$image',vVideoLink='$videolink',vVideoViews=0,vStatus=$statuss WHERE vVideoID=$act");
					   
				$updatebannerTOtable = $db->query($updatebanners);
				echo "<script language='javascript'>window.location='videoManager.php?msg=$msg'</script>";

		   }
		   
		   else
		   {
			   if (($_FILES["banerpath"]["type"] == "image/jpeg") || ($_FILES["banerpath"]["type"] == "image/pjpeg"))
			   {
				   if ($_FILES["banerpath"]["error"] > 0)
				   {
					   $no = "Error: " . $_FILES["file"]["error"] . "<br />";
					   echo "<script language='javascript'>window.location='videoManager.php?no=$no'</script>";
				   }
				   else
				   {
					   unlink("../Resources/video_images/".$image);
					   move_uploaded_file($_FILES["banerpath"]["tmp_name"], "../Resources/video_images/" . $path);
					   $updatebanners = $db->query("UPDATE  tbl_videos SET vVideoName='$title',vVideoImage='$path',vVideoLink='$videolink',vVideoViews=0,vStatus=$statuss WHERE vVideoID=$act");
					   
					   $updatebannerTOtable = $db->query($updatebanners);
					   echo "<script language='javascript'>window.location='videoManager.php?msg=$msg'</script>";
				   }
			   }
			   
			   else
			   {
				   $invalid = "Invalid file";
				   echo "<script language='javascript'>window.location='videoManager.php?invalid=$invalid'</script>";
			   }
		   }
		   
		   break;
		 
		 case "deletebanner":
		 
		   $updateimage = "SELECT * FROM  tbl_videos WHERE vVideoID=$delbaner";
		   $imagefield = $db->query($updateimage);
		   $row = $imagefield->fetch_array();
		   $image=$row['vVideoImage'];
		   
		  
		 unlink("../Resources/video_images/".$image);
		   
		   $deltebannerfromtable = $db->query("DELETE FROM  tbl_videos WHERE vVideoID=$delbaner");
		   $msg = "Video Deleted Sucessfully";
		   echo "<script language='javascript'>window.location='videoManager.php?msg=$msg'</script>";
		   
		   break;
		   
	 }
				 
?>		 
				 