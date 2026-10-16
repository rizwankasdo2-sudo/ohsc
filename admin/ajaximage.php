<?php

session_start();
ob_start();
if($_SESSION['adminid']== "")
{
	header("location:index.php");
	exit();
}
 $newsid=$_GET['id'];

$path = "uploads/";
$base_url = "https://www.oxfordhomestudy.com/";


	$valid_formats = array("webp");
	if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
		{
			$name = $_FILES['photoimg']['name'];
			$size = $_FILES['photoimg']['size'];
			$current_image = (!empty($_POST['current_image'])) ? $_POST['current_image'] : '';
			
			if(strlen($name))
				{
					list($txt, $ext) = explode(".", $name);
					if(in_array($ext,$valid_formats))
					{
					if($size<(1024*1024))
						{
							$actual_image_name = str_replace(" ", "_", $txt).".".$ext;
							$tmp = $_FILES['photoimg']['tmp_name'];
							if ($current_image == $actual_image_name || !file_exists($path.$actual_image_name)) {
								if(move_uploaded_file($tmp, $path.$actual_image_name))
									{
										
										echo "<img src='uploads/".$actual_image_name."'  class='preview' style='max-width:100%; display: block; margin-bottom: 4px;'>";
										echo "URL: ".$base_url."admin/uploads/".$actual_image_name;
									}
								else
									echo "failed";
							}
							else 
								echo 'File already exists.';
						}
						else
						echo "Image file size max 1 MB";					
						}
						else
						echo "Invalid file format..";	
				}
				
			else
				echo "Please select image..!";
				
			exit;
		}
?>