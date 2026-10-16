<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

} 
$id = $_GET['id'];

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">



<head>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<title>SEO</title>



<link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />



<link rel="stylesheet" href="../Resources/admin_css/add-product.css" />





<script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>



<!--[if lte IE 6]>

	<script type="text/javascript" src="../Resources/admin_js/supersleight-min.js"></script>

<![endif]-->



</head>



<body>



  <div id="wrap_dashboard">

 

   <?php

   

           include("classes/connection.php");

		   

		   include("includes/admin-header.php");

		   

		   include("includes/admin-menu.php");

   

   ?>

   

   <div id="outer_contanier">

     

     <div id="board">

     

       <div id="left_corner">

       </div>

       <?php

if(isset($_POST['update'])){

    $id= $_POST['get_id'];
    $page_title = $_POST['page_title'];
    $meta_desc = $_POST['meta_desc'];
    $robots = $_POST['robots'];
    $meta_key = $_POST['meta_key'];

$check_query = "SELECT * FROM diploma_seo WHERE d_id=$id";
$check_result = $db->query($check_query);
if($check_result->num_rows == 1){
	$NewsUpdate=$db->query("UPDATE diploma_seo SET page_title='$page_title', meta_desc='$meta_desc',meta_key='$meta_key', robots='$robots' where d_id=$id") or die("update query error").mysqli_error($db);
	$msg="SEO Updated Sucssefully";
	echo "<script language='javascript'>window.location='cours.php?msg=$msg'</script>";

	} else {
        	$NewsUpdate = $db->query("INSERT INTO diploma_seo (page_title, meta_desc, meta_key, robots, d_id) VALUES('$page_title', '$meta_desc', '$meta_key','$robots', $id)") or die("update query error").mysqli_error($db);
        	$msg="SEO Added Sucssefully";  
        	echo "<script language='javascript'>window.location='cours.php?msg=$msg'</script>";
    }
    }

?>   

       <form action="diploma_seo.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data" name="addproduct" runat="server">
       

       <div id="midle_repeat">


         <div id="tit">
         

           <h3>Update Testimonail</h3>

         </div>


       </div>


       <div id="right_corner">

       </div>


       <div id="main_content">
       

         <div id="addproducts">

         

           <div id="uper">

           

             <div id="upertop">

             

               <h4>Overview</h4>

             

             </div>

             

             <div id="uperdetail">

                   <div id="general">

               <?php 
			   $seo_check_sql = "SELECT * FROM diploma_seo WHERE d_id = $id";
                $result = $db->query($seo_check_sql);
                $row = $result->fetch_array();

			   ?>   

                       <div class="fieldrepeat">

                       

                         <p>Page Title : </p>

                         <textarea id="NewsTitle" name="page_title" cols="60" rows="10"><?php if(!empty($row['page_title'])){ echo $row['page_title']; } ?></textarea>

                       </div>

                             <div class="clear">

                                 </div>

                       

                       <div class="fieldrepeat">

                       

                         <p> Meta Keywords</p>

                         <label for="textfield"></label>

                         <textarea name="meta_key" id="Newsdiscription" cols="60" rows="10"><?php if(!empty($row['meta_key'])){ echo $row['meta_key']; } ?></textarea>

                       </div>

<div class="fieldrepeat" style="margin-top:10px;">



<p>Meta Description</p>

<label for="textarea"></label>

<textarea name="meta_desc" id="NewsDetail" cols="60" rows="10"><?php if(!empty($row['meta_desc'])){ echo $row['meta_desc']; } ?></textarea>

<input type="hidden" name="get_id" value="<?php echo $id; ?>" />

</div>



         <div class="clear">

         </div>

<div class="fieldrepeat" style="margin-top:10px;">

<p>Robots  : </p>

<label><input type="radio" name="robots" value="Index,Nofollow" <?php if ($row['robots']=='Index,Nofollow'){ echo 'checked'; }?> />Index</label>
<label><input type="radio" name="robots" value="Noindex,Follow" <?php if ($row['robots']=='Noindex,Follow'){ echo 'checked'; }?> />Follow</label>
<label><input type="radio" name="robots" value="Noindex,Nofollow" <?php if ($row['robots']=='Noindex,Nofollow'){ echo 'checked'; }?> />Disallow</label>
<label><input type="radio" name="robots" value="Index,Follow" <?php if ($row['robots']=='Index,Follow'){ echo 'checked'; }?> />Allow</label> 


</div>



<div class="clear">

</div>

                       

                                 <div class="clear">

                                 </div>

                    <div id="buttons" style=" width:713px; margin:0 auto; margin-top:15px;"> 

                    <input type="submit" name="update" id="update" value="update" />

                      <input type="button" value="Cancel" onClick="window.location.href='diploma.php?cid=<?php // echo $cid; ?>'" />

                 

                    </div>

                </div>



             </div>

             

             <div class="clear">&nbsp;

              

             </div>

             

           </div>

           

           </div>

         

         </div>

         

         <div class="clear">&nbsp;</div>

         

         

         </form>

         

       </div>

     

     <div class="clear">&nbsp;</div>

     

     <?php include("includes/admin-footer.php"); ?>

     

    </div>

     

  </div>  

</body>

</html>









