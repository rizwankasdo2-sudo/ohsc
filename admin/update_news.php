<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

} 

 $newsid=$_GET['id'];

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">



<head>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<title>Events</title>



<link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />



<link rel="stylesheet" href="../Resources/admin_css/add-product.css" />





<script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>

<script type="text/javascript" src="cms/ckeditor/ckeditor.js"></script>
<script src="cms/ckeditor/sample.js" type="text/javascript"></script>


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

	

	$NewsDetail=$_POST['NewsDetail'];
	
	$description=$_POST['description'];
	

	

	$status  =  $_POST['status'];

	$NewsUpdate=$db->query("UPDATE tbl_news SET INewsDescription='$NewsDetail',INewsDetail='$description',IStatus='$status' where INewsID=$newsid") or die("update query error").mysqli_error($db);

	$msg="Events Updated Sucssefully";

	echo "<script language='javascript'>window.location='news.php?msg=$msg'</script>";

	

	}

?>



       

       <form action="" method="post" enctype="multipart/form-data" name="addproduct" runat="server">

       

       <div id="midle_repeat">

         

         <div id="tit">

         

           <h3>Events</h3>

         

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
			
			$qry=$db->query("select * from tbl_news where INewsID=$newsid");
			
			$newsreslt= $qry->fetch_array();
			
			?>
                     

                     
                       <div class="fieldrepeat">

                       

                         <p>Events Description : </p>

                        

                 <textarea name="NewsDetail" id="NewsDetail" cols="60" rows="10"><?php echo $newsreslt['INewsDescription']; ?></textarea>


                       

         </div>

                       

                                 <div class="clear">

                                 </div>

  

                       <div class="fieldrepeat">
                       
                         <p>Events Detail : </p>
                         <div style="width:80%; float:left">
                         <textarea id="description" name="description" class="ckeditor" cols="30" rows="15">
                         <?php echo $newsreslt['INewsDetail']; ?>
                         </textarea>
                         </div>
                       
                       </div>
                       
     
                       
                                 <div class="clear">
                                 </div>

                       


                                 <div class="clear">

                                 </div>

                       

                  

                       

                       <div class="fieldrepeat">

                       

                         <p>Status : </p>

                         

                         <select name="status" id="status" class="status" style="width:150px;">

                   

					   <?php

					   

					           if ($newsreslt['IStatus'] == 1)

							   {
								   echo "<option value=".$newsreslt['IStatus']." 'selected'>Enable</option>";

								   echo "<option value='0'>Disable</option>";

								   

							   }

							   else

							   {
								   echo "<option value=".$newsreslt['IStatus']." 'selected'>Disable</option>";

								   echo "<option value='1'>Enable</option>";

								   

							   }

					   

					   ?>

                     

                 </select>

               

                       

                       </div>

                       

                       

                                 <div class="clear">

                                 </div>

                                

                                 

                    <div id="buttons" style=" width:713px; margin:0 auto; margin-top:10px;"> 

                    <input type="submit" name="update" id="update" value="update" />

                      <input type="button" value="Cancel" onClick="window.location.href='news.php'" />

            

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
  <script>
	CKEDITOR.replace( 'description',
    {
        filebrowserBrowseUrl : 'cms/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Images',
        filebrowserFlashBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Flash',
        filebrowserUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        filebrowserFlashUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
    });
	</script> 
</body>

</html>









