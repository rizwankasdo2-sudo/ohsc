<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

} 
if(isset($_GET['catid'])){
	
$catid = $_GET['catid'];	
	
}

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">



<head>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<title>Add Items</title>


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

   $qry = $db->query("select * from tbl_menu where ICatID=$catid");
   $result = $qry->fetch_array();

   ?>

   

   <div id="outer_contanier">

     

     <div id="board">

     

       <div id="left_corner">

       </div>

       

       <form action="insertitems.php" method="post" enctype="multipart/form-data" name="addproduct" runat="server">

       

       <div id="midle_repeat">

         

         <div id="tit">

         

           <h3>Add Items</h3>

         

         </div>

         

         <div id="buttons">

         

           <ul style="float:right;">

           

             <li><input type="submit" value="Save" class="add_cancel_button" /></li>

             <li><a href="items.php?id=<?php echo $catid; ?>">Cancel</a></li>

           

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

                       

                         <p>Item Name : </p>

                  <input type="text" id="catname" name="catname" class="productfieldlarge" style="width:300px;" />


                       

         </div>

                       

                                 <div class="clear">

                                 </div>
                                 
                                      <div class="fieldrepeat">

                       

                         <p>Item Price : </p>

                  <input type="text" id="price" name="price" class="productfieldlarge" style="width:300px;" />


                       

         </div>

                       

                                 <div class="clear">

                                 </div>
                                 
                       
                   <div class="fieldrepeat">

                       

                         <p>Category : </p>

                         

                         <select name="catid" id="catid" class="productfieldlarge" style="width:140px;">
                         <?php 
			
			$qry234=$db->query("select * from  tbl_menu");
			
			while($newsreslt= $qry234->fetch_array()){
			
			?>

                           <option value="<?php echo $newsreslt['ICatID']; ?>" <?php if($newsreslt['ICatID']==$catid){ echo 'selected';}?>><?php echo $newsreslt['ICatName'];  ?></option>
<?php } ?>
                          

                         </select>

                       

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