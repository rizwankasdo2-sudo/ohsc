<?php
session_start();
ob_start();
if ($_SESSION['adminid'] == '') {
	header("location:index.php");
	exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">



<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<title>Manage Emails</title>



<link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />



<link rel="stylesheet" href="../Resources/admin_css/products.css" />



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

       

       <div id="midle_repeat">

         

         <div id="productsheading">

         

           <h3>MANAGE EMAILS</h3>

         

         </div>

         

         <div id="adddel">

         

           <ul>

           

             <li style="float:right"><a href="add_email.php">Add</a></li>

           

           </ul>

         

         </div>

       

       </div>

       

       <div id="right_corner">

       </div>

       

       <div id="main_content">

       

         <div id="productslistsearch">           

           

           <div class="clear">&nbsp;</div>

           

           

           <?php
		   if (!empty($_GET['msg'])) {
               switch($_GET['msg']) {
                   case 1:
		               echo '<p style="color:black; font-size:28px; text-align:center; font-weight:bold">New Email Added Successfully.</p>';
			           break;
			        case 2:
		               echo '<p style="color:black; font-size:28px; text-align:center; font-weight:bold">Email Updated Successfully.</p>';
			           break;
		       }
		   }
		   ?>

           

           <div id="producthead">

           

             <div id="prtop">

             

               <h4>MANAGE EMAILS</h4>



             </div>

             

             <div id="prdetail">

             

               <table width="100%" border="0" cellspacing="0" cellpadding="0">

               

                 <tr id="first_row">
                   <th scope="col" class="common">Email Subject</th>
                    <th scope="col" class="common">Email Body</th>
                   <th scope="col" class="common" style="border-right:none;">Action</th>
                 </tr>



                 <?php
			     $email_result = $db->query("SELECT * FROM email_templates ORDER BY id ASC");
				 while ($email_row = $email_result->fetch_array()) {
				 ?>
                 <tr id="rowrepeat">
                     <td class="big"><?php echo $email_row['email_subject']; ?></td>
                     <td class="big"><?php echo $email_row['email_body']; ?></td>
                     <td class="small">
                         <a href="update_email.php?id=<?php echo $email_row['id']; ?>" class="edit"></a>
                     </td>
                 </tr>
                  <?php } ?>
               </table>



             

             </div>

           

           </div>

         

         </div>

         

         <div class="clear">&nbsp;</div>

              

       </div>

     

     </div>

     

     <?php include("includes/admin-footer.php"); ?>

   

   </div>

 

 </div>



</body>

</html>