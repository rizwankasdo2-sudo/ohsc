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

	 

	 if (isset ($_POST['prjecttitle']) && isset ($_POST['prjectdesc']) && isset ($_FILES['prjectpath']['name']))

	 {

		 $prjecttitle   =   $_POST['prjecttitle'];

		 $prjectdesc = $_POST['prjectdesc'];

		 $prjectpath    =  $_FILES['prjectpath']['name'];

		 

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

		 

		      if (($_FILES["prjectpath"]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") || ($_FILES["prjectpath"]["type"] == "application/pdf")|| ($_FILES["prjectpath"]["type"] == "application/msword") || ($_FILES["prjectpath"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") || ($_FILES["prjectpath"]["type"] == "application/vnd.openxmlformats-officedocument.presentationml.presentation"))
			{

				if ($_FILES["prjectpath"]["error"] > 0)

				{

					$no = "Error: " . $_FILES["file"]["error"] . "<br />";

					echo "<script language='javascript'>window.location='project_page.php?no=$no'</script>";

				}

				else

				{

					move_uploaded_file($_FILES["prjectpath"]["tmp_name"], "../Resources/projects/" . $prjectpath);

					

					$insertproject = "insert into tbl_projects(IProjectTitle,IProjectDiscription ,IProjectPdf)values('$prjecttitle','$prjectdesc','$prjectpath')";

					

		$insertbannerTOtable = $db->query($insertproject);

		$msg = "Project Inserted Successfully";

					echo "<script language='javascript'>window.location='project_page.php?msg=$msg'</script>";

				}

			}

			

			else

			{

				$invalid = "Invalid file";

				echo $_FILES["prjectpath"]["type"];

				echo "<script language='javascript'>window.location='project_page.php?invalid=$invalid'</script>";

			}

			

			break;

		 

		 /*case "updatebanner":

		 

		   $updateimage = "SELECT * FROM tbl_download WHERE IDownloadID=$act";

		   $imagefield = $db->query($updateimage) or die("select query error").mysqli_error($db);

		   $row = $imagefield->fetch_array();

		   $book = $row['IDownloadBook'];

		   $msg = " Books Updated Successfully";

		   

		   if ($downloadpath == "")

		   {

			   $updatebanners = $db->query("UPDATE tbl_download SET IDownoladTitle='$downloadtitle', 

			   

  IDownloadDiscription='$downloaddesc',IDownloadBook='$book',IDate='$date',IStatus =$statuss WHERE IDownloadID=$act");

					   

				$updatebannerTOtable = $db->query($updatebanners);

				echo "<script language='javascript'>window.location='book.php?msg=$msg'</script>";



		   }

		   

		   else

		   {

			   if (($_FILES["downloadpath"]["type"] == "application/msword"))

			   {

				   if ($_FILES["downloadpath"]["error"] > 0)

				   {

					   $no = "Error: " . $_FILES["file"]["error"] . "<br />";

					   echo "<script language='javascript'>window.location='picture_gallery.php?no=$no'</script>";

				   }

				   else

				   {

					   unlink("../Resources/download_books/".$book);

					   move_uploaded_file($_FILES["downloadpath"]["tmp_name"], "../Resources/download_books/".$downloadpath);

					   $updatebanners = $db->query("UPDATE tbl_download SET IDownoladTitle='$downloadtitle', 

			   

  IDownloadDiscription='$downloaddesc',IDownloadBook='$downloadpath',IDate='$date',IStatus =$statuss WHERE IDownloadID=$act");

					   

					   $updatebannerTOtable = $db->query($updatebanners);

					   echo "<script language='javascript'>window.location='book.php?msg=$msg'</script>";

				   }

			   }

			   

			   else

			   {

				   $invalid = "Invalid file";

				   $msg = "invalid format";

		   echo "<script language='javascript'>window.location='book.php?msg=$msg'</script>";

		   

		   break;

			   }

		   }

		   

		   break;

		 */

		 case "deletebanner":

		 

		   $updateimage = "SELECT * FROM tbl_projects WHERE Iprojectid=$delbaner";

		   $imagefield = $db->query($updateimage);

		   $row = $imagefield->fetch_array();

		   $paper = $row['IProjectPdf'];

		   unlink("../Resources/projects/".$paper);

		   

		   $deltebannerfromtable = $db->query("DELETE FROM tbl_projects WHERE Iprojectid=$delbaner")  or die("delete query error").mysqli_error($db);

		   $msg = "Project Deleted Sucessfully";

		 

		   echo "<script language='javascript'>window.location='project_page.php?msg=$msg'</script>";

	 }

				 

?>		 

				 