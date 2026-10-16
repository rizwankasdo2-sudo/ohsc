<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

} 

 $pid=$_GET['id'];
 
 if(isset($_GET['did']) && isset($_GET['cid']))
{
$did = $_GET['did'];
$cid = $_GET['cid'];	
	
}

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">



<head>



<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<title>Udate Installment</title>



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

	

	$catname=$_POST['catname'];
	
	$order = $_POST['order'];
	
	$status  =  $_POST['status'];
	$month = $_POST['month'];
	$cid = $_POST['cid'];
	$did = $_POST['did'];
	$pid = $_POST['pid'];
	
	$qry = $db->query("update tbl_installment set fullpayment=$catname,advance=$order,months=$month,IStatus=$status where installmentid=$pid");

	$msg="Updated Sucssefully";

	echo "<script language='javascript'>window.location='diploma.php?cid=$cid&msg=$msg'</script>";

	

	}

?>



       

       <form action="" method="post" enctype="multipart/form-data" name="addproduct" runat="server">

       

       <div id="midle_repeat">

         

         <div id="tit">

         

           <h3>Installment</h3>

         

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
			
			$qry=$db->query("select * from  tbl_installment where installmentid=$pid");
			
			$newsreslt= $qry->fetch_array();
			
			?>
                     

                     
                       <div class="fieldrepeat">

                       <input type="hidden" name="cid" id="cid" value="<?php echo $cid; ?>" />
                       <input type="hidden" name="did" id="did" value="<?php echo $did; ?>" />
                       <input type="hidden" name="pid" id="pid" value="<?php echo $pid; ?>" />

                         <p>Payment : </p>

                        <input type="text" id="catname" name="catname" value="<?php echo $newsreslt['fullpayment'];?>" class="productfieldlarge" style="width:300px;" />

                       

         </div>

                       

                                 <div class="clear">

                                 </div>

 
                       <div class="fieldrepeat">

                       

                         <p>Diposit : </p>

                        <input type="text" id="order" name="order" value="<?php echo $newsreslt['advance'];?>" class="productfieldlarge" style="width:300px;" />
         </div>
                             <div class="clear">

                                 </div>
                                 
                                  <div class="fieldrepeat">

                       

                         <p>Number of Months : </p>

                        <input type="text" id="month" name="month" value="<?php echo $newsreslt['months'];?>" class="productfieldlarge" style="width:300px;" />

                       

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

                      <input type="button" value="Cancel" onClick="window.location.href='diploma.php?cid=<?php echo $cid;?>'" />

            

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









