<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

}

	  

	  include("classes/connection.php");

	  if(isset($_POST['coursename'])&& isset($_POST['order'])  && isset($_POST['status']) && isset($_POST['content']))

{

		  $name =   $_POST['coursename'];
          $alternate = $_POST['alternate'];
		  
		  if (!isset ($_POST['slug']) || $_POST['slug'] == '')
		  {
			  $slug = str_replace(' ', '-', strtolower($name));
		  }
		  else
		  {
			  $slug = str_replace(' ', '-', strtolower($_POST['slug']));
		  }
		  
		  $order = $_POST['order'];
			
			
		  $msg = "Slug Already Exists";
          $excerpt = $_POST['excerpt'];
          $course_content = $_POST['content'];
          $thumbnail = $_FILES['thumbnail']['name'];
		  $status  =  $_POST['status'];
          
          
          $path_to_image_directory = '../images/';
         $path_to_thumbs_directory = '../images/thumbs/';
          
          function createThumbnail($filename) {
     $path_to_image_directory = '../images/';
         $path_to_thumbs_directory = '../images/thumbs/';
    if(preg_match('/[.](jpg)$/', $filename)) {
        $im = imagecreatefromjpeg($path_to_image_directory . $filename);
    } else if (preg_match('/[.](gif)$/', $filename)) {
        $im = imagecreatefromgif($path_to_image_directory . $filename);
    } else if (preg_match('/[.](png)$/', $filename)) {
        $im = imagecreatefrompng($path_to_image_directory . $filename);
    }
     
    $ox = imagesx($im);
    $oy = imagesy($im);
     $final_width_of_image=264;
    $nx = $final_width_of_image;
    $ny = 100;
     
    $nm = imagecreatetruecolor($nx, $ny);
     
    imagecopyresampled($nm, $im, 0,0,0,0,$nx,$ny,$ox,$oy);
     
    if(!file_exists($path_to_thumbs_directory)) {
      if(!mkdir($path_to_thumbs_directory)) {
           die("There was a problem. Please try again!");
      } 
       }
 
    imagejpeg($nm, $path_to_thumbs_directory . $filename);
}
          if(isset($_FILES['thumbnail'])) {
     
    if(preg_match('/[.](jpg)|(gif)|(png)$/', $_FILES['thumbnail']['name'])) {

        $filename = $_FILES['thumbnail']['name'];
        $source = $_FILES['thumbnail']['tmp_name'];   
        $target = $path_to_image_directory . $filename;
         
        move_uploaded_file($source, $target);
         
        createThumbnail($filename);     
    }
}




          
          
$qry = $db->query("insert into tbl_courses(cname,slug,ordernumber,excerpt,content,thumbnail,IStatus, alternate) values('$name','$slug',$order,'$excerpt','$course_content','$thumbnail',$status, '$alternate')")or die("<script language='javascript'>window.location='cours.php?msg=$msg'</script>").mysqli_error($db);
		 

$msg = "Inserted Successfully";
header("location: cours.php?msg=$msg");
echo "<script language='javascript'>window.location='cours.php?msg=$msg'</script>";

		   }

		 

	



else{

	$msg="Not allowed empty fields";
    header("location: cours.php?msg=$msg");
	echo "<script language='javascript'>window.location='cours.php?msg=$msg'</script>";

	}



?>