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

	 

	 if (isset ($_POST['banername']) && isset ($_FILES['banerpath']['name']) && isset ($_FILES['lbanerpath']['name']) && isset ($_POST['status']))

	 {

		 $title   =   $_POST['banername'];

		 $path    =  $_FILES['banerpath']['name'];

		 $larg_path    =  $_FILES['lbanerpath']['name'];
		 
		 
		 
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

		 case "addbanner";

		 if(($_FILES['banerpath']['type']=="image/jpeg") || ($_FILES['banerpath']['type']=="image/pjpeg") || ($_FILES['banerpath']['type']=="image/png")  && ($_FILES['lbanerpath']['type']=="image/jpeg") || ($_FILES['lbanerpath']['type']=="image/pjpeg") || ($_FILES['lbanerpath']['type']=="image/png")){

			 if(($_FILES['banerpath']['error']>0) || ($_FILES['lbanerpath']['error']>0))

			 {

				$no="Error :".$_FILES['banerpath']['error']."<br/>"; 

				 echo "<script language='javascript'>window.location='portpolio.php?no=$no'</script>";

				 }

			 else{

				 move_uploaded_file($_FILES['banerpath']['tmp_name'],"../Resources/gallery/".$path);

				 move_uploaded_file($_FILES['lbanerpath']['tmp_name'],"../Resources/gallery/".$larg_path);

				$gallery="insert into tbl_gallery(IImageName,ISmallImage,ILargeImage,IStatus)values('$title','$path','$larg_path',$statuss)";

				$reslt=$db->query($gallery);

				$msg = "Image Inserted Successfully";

					echo "<script language='javascript'>window.location='gallery.php?msg=$msg'</script>";

				 }

			 

			 }

			 else

			 	{

					$invalid = 'Invalid Image OR  Leaved Empty';

					echo "<script language='javascript'>window.location='gallery.php?invalid=$invalid'</script>";

					}

		 

		 case "updatebanner":

		$title   =   $_POST['banername'];

		 $path    =  $_FILES['banerpath']['name'];

		 $larg_path    =  $_FILES['lbanerpath']['name'];
		 
		 

		 $statuss =   $_POST['status'];

		 

		   $updateimage = "SELECT * FROM tbl_gallery WHERE IGalleryID=$act";

		   $imagefield = $db->query($updateimage);

		   $row = $imagefield->fetch_array();

		   $image = $row['ISmallImage'];

		   $Limage = $row['ILargeImage'];

		   $msg = "Gallery Images Updated Successfully";

		 

		  

		   if ($path == "" && $larg_path == "")

		   {

			    $updatebanners = $db->query("UPDATE tbl_gallery SET IImageName='$title',ISmallImage='$image',ILargeImage='$Limage',IStatus=$statuss WHERE IGalleryID=$act");

					   

				$updatebannerTOtable = $db->query($updatebanners);

				echo "<script language='javascript'>window.location='gallery.php?msg=$msg'</script>";



		   }

		    

			//my coad

			elseif ($path !== "" && $larg_path !== "")

		   {

			   unlink("../Resources/gallery/".$image);

			   unlink("../Resources/gallery/".$Limage);

			    move_uploaded_file($_FILES['banerpath']['tmp_name'],"../Resources/gallery/".$path);

				 move_uploaded_file($_FILES['lbanerpath']['tmp_name'],"../Resources/gallery/".$larg_path);

			    $updatebanners = $db->query("UPDATE tbl_gallery SET IImageName='$title',ISmallImage='$path',ILargeImage='$larg_path',IStatus=$statuss WHERE IGalleryID=$act");

					   

				$updatebannerTOtable = $db->query($updatebanners);

				echo "<script language='javascript'>window.location='gallery.php?msg=$msg'</script>";



		   }

		  

		  //end

		  

		   else if ($path == "")

		   {

			    if (($_FILES["lbanerpath"]["type"] == "image/jpeg") || ($_FILES["lbanerpath"]["type"] == "image/pjpeg"))

			   {

				   if ($_FILES["lbanerpath"]["error"] > 0)

				   {

					   $no = "Error: " . $_FILES["file"]["error"] . "<br />";

					   echo "<script language='javascript'>window.location='gallery.php?no=$no'</script>";

				   }

				   else

				   {

					   if ($path != "")

					   {

						   unlink("../Resources/gallery/".$image);

						   move_uploaded_file($_FILES["banerpath"]["tmp_name"], "../Resources/gallery/" . $path);

					   }

					   else if ($larg_path != "")

					   {

						   unlink("../Resources/gallery/".$Limage);

						   move_uploaded_file($_FILES["lbanerpath"]["tmp_name"], "../Resources/gallery/" .$larg_path);

					   }

					   

					   

					   



					   $updatebanners = $db->query("UPDATE tbl_gallery SET IImageName='$title',                  

					                                 ISmallImage='$image',ILargeImage='$larg_path',IStatus=$statuss WHERE IGalleryID=$act");

					   

					   $updatebannerTOtable = $db->query($updatebanners);

					   echo "<script language='javascript'>window.location='gallery.php?msg=$msg'</script>";

				   }

			   }

			   else

			   {

				   $invalid = "Invalid file";

				   echo "<script language='javascript'>window.location='gallery.php?invalid=$invalid'</script>";

			   }



		   }

		   else if ($larg_path == "")

		   {

			    if (($_FILES["banerpath"]["type"] == "image/jpeg") || ($_FILES["banerpath"]["type"] == "image/pjpeg"))

			   {

				   if ($_FILES["banerpath"]["error"] > 0)

				   {

					   $no = "Error: " . $_FILES["file"]["error"] . "<br />";

					   echo "<script language='javascript'>window.location='gallery.php?no=$no'</script>";

				   }

				   else

				   {

					   if ($path != "")

					   {

						   unlink("../Resources/gallery/".$image);

						   move_uploaded_file($_FILES["banerpath"]["tmp_name"], "../Resources/gallery/" . $path);

					   }

					   else if ($larg_path != "")

					   {

						   unlink("../Resources/gallery/".$Limage);

						   move_uploaded_file($_FILES["lbanerpath"]["tmp_name"], "../Resources/gallery/" .$larg_path);

					   }

					   

					   

					   



					   $updatebanners = $db->query("UPDATE tbl_gallery SET IImageName='$title',                  

					                                 ISmallImage='$path',ILargeImage='$Limage',IStatus=$statuss WHERE IGalleryID=$act");

					   

					   $updatebannerTOtable = $db->query($updatebanners);

					   echo "<script language='javascript'>window.location='gallery.php?msg=$msg'</script>";

				   }

			   }

			   else

			   {

				   $invalid = "Invalid file";

				   echo "<script language='javascript'>window.location='gallery.php?invalid=$invalid'</script>";

			   }



		   }

		  

		   else

		   {

			   if (($_FILES["banerpath"]["type"] == "image/jpeg") || ($_FILES["banerpath"]["type"] == "image/pjpeg") || ($_FILES['lbanerpath']['type']=="image/jpeg") || ($_FILES['lbanerpath']['type']=="image/pjpeg"))

			   {

				   if ($_FILES["banerpath"]["error"] > 0)

				   {

					   $no = "Error: " . $_FILES["file"]["error"] . "<br />";

					   echo "<script language='javascript'>window.location='gallery.php?no=$no'</script>";

				   }

				   else

				   {

					   if ($path != "")

					   {

						   unlink("../Resources/gallery/".$image);

						   move_uploaded_file($_FILES["banerpath"]["tmp_name"], "../Resources/gallery/" . $path);

					   }

					   else if ($larg_path != "")

					   {

						   unlink("../Resources/gallery/".$Limage);

						   move_uploaded_file($_FILES["lbanerpath"]["tmp_name"], "../Resources/gallery/" .$larg_path);

					   }

					   

					   

					   



					   $updatebanners = $db->query("UPDATE tbl_gallery IImageName='$title',                  

					                                 ISmallImage='$path',ILargeImage='$larg_path',IStatus=$statuss WHERE IGalleryID=$act");

					   

					   $updatebannerTOtable = $db->query($updatebanners);

					   echo "<script language='javascript'>window.location='gallery.php?msg=$msg'</script>";

				   }

			   }

			   

			   else

			   {

				   $invalid = "Invalid file";

				   echo "<script language='javascript'>window.location='gallery.php?invalid=$invalid'</script>";

			   }

		   }

		   

		   break;

		 

		 case "deletebanner":

		 

		   $updateimage = "SELECT * FROM tbl_gallery WHERE IGalleryID=$delbaner";

		   $imagefield = $db->query($updateimage);

		   $row = $imagefield->fetch_array();

		   $image = $row['ISmallImage'];

		   $limage_delete=$row['ILargeImage'];

		   unlink("../Resources/gallery/".$image);

		    unlink("../Resources/gallery/".$limage_delete);

		   

		   $deltebannerfromtable = $db->query("DELETE FROM tbl_gallery WHERE IGalleryID=$delbaner");

		   $msg = "Gallery data Deleted Sucessfully";

		   echo "<script language='javascript'>window.location='gallery.php?msg=$msg'</script>";

		   

		   break;

		   

	 }

				 

?>		 

				 