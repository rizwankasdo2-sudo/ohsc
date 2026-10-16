<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

} 

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">



<head>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<title>Update SEO</title>




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
	   $id=$_GET['uid'];
	   
	   $catlist = $db->query("SELECT * FROM  tbl_seo where seoid=$id ");
	   
	   while($row= $catlist->fetch_array()){
	   
	   
	   ?>

       <form action="updateseo.php" method="post" enctype="multipart/form-data" name="addproduct" runat="server">

       

       <div id="midle_repeat">

         

         <div id="tit">

         

           <h3>Add SEO</h3>

         

         </div>

         

         <div id="buttons">

         

           <ul style="float:right;">

           

             <li><input type="submit" value="Save" class="add_cancel_button" /></li>

             <li><a href="seo.php">Cancel</a></li>

           

           </ul>

         

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

                   

                       <input type="hidden" value="<?php echo $id; ?>" name="hid" />

                       <div class="fieldrepeat">

                       

                         <p>Page Title : </p>
                            <textarea class="seo_text" name="ptitle" id="ptitle" rows="3" style="width: 645px; padding:10px;"><?php echo $row['pagetitle']; ?></textarea>
                         

                       

                       </div>

                       

                                 <div class="clear">

                                 </div>
                                
                     <div class="fieldrepeat">

                       

                       <p>Meta Keywords : </p>

                       <textarea class="seo_text" name="metatitle" id="metatitle" rows="3" style="width: 645px; padding:10px;"><?php echo $row['metatitle']; ?></textarea>

                       </div>

                       

                                 <div class="clear">

                                 </div>
                                 
                          <div class="fieldrepeat">

                      

                         <p>Meta Description : </p>
<textarea class="seo_text" name="desc" id="desc" rows="3" style="width: 645px; padding:10px;"><?php echo $row['metadiscription']; ?></textarea>

                       

                       </div>
                       

                                 <div class="clear">

                                 </div>



<div class="fieldrepeat">



<p>Robots  : </p>

<label><input type="radio" name="Robot" value="Index,Nofollow" />Index</label>
<label><input type="radio" name="Robot" value="Noindex,Follow" />Follow</label>
<label><input type="radio" name="Robot" value="Noindex,Nofollow" />Disallow</label>
<label><input type="radio" checked="" name="Robot" value="Index,Follow" />Allow</label>



</div>



<div class="clear">

</div>


                                 
                                 
                         <?php } ?>

                     

                    

                     

                       

                   

                   </div>



             </div>

             

             <div class="clear">&nbsp;</div>

             

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