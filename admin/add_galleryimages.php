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



<title>Add Picture Gallery</title>



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

       

      

  <form action="upload_edit_delete_gallery.php?case=addbanner" method="post" name="banerupload" enctype="multipart/form-data">

       

       <div id="midle_repeat">

         

         <div id="tit">

         

           <h3>Add Pictures</h3>

         

         </div>

         

         <div id="buttons">

         

           <ul style="float:right;">

           

             <li><input type="submit" value="Save" class="add_cancel_button" /></li>

             <li><a href="gallery.php">Cancel</a></li>

           

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

                       

                       <div class="fieldrepeat">

                       

                         <p>Description  : </p>

                         <input type="text" id="banername" name="banername" class="productfieldlarge" style="width:300px;" />

                       

                     </div>

                       

                                 <div class="clear">

                                 </div>

                       

                     <div class="fieldrepeat">

                       

                       <p>Small Image</p>

                        <input name="banerpath" type="file" class="productfield" id="banerpath" style="float:left; margin-right:0px; width:300px;" size="41" /><p>(187 X 94)</p>

                       </div>

                       

                       <div class="clear">

                                 </div>

                       

                     <div class="fieldrepeat" style="margin-top:10px;">

                       

                       <p>Larg Image</p>

                        <input name="lbanerpath" type="file" class="productfield" id="lbanerpath" style="float:left; margin-right:0px; width:300px;" size="41" />

                     </div>

                       

                                 
                                 
                                 <div class="clear">

                                 </div>
                    
               



                       <div class="fieldrepeat">

                       

                         <p>Status : </p>

                         

                         <select name="status" id="status" class="productfieldlarge" style="width:140px;">

                           <option value="1">Enable</option>

                           <option value="0">Disable</option>

                         </select>

                       

                       </div>

                       

                                 <div class="clear">

                                 </div>

                   

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

    

</body>

</html>