<?php

session_start();

ob_start();
include("classes/connection.php");

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

}

if (isset($_GET['act'])) {
    if ($_GET['act'] == 'del') {
        $benefit_del_result = $db->query("SELECT img FROM benefits WHERE id = '" . $_GET['id'] . "' ");
        $benefit_del_row = $benefit_del_result->fetch_array();
        unlink('../images/nd/benefits/' . $benefit_del_row['img']);
        $db->query("DELETE FROM benefits WHERE id = '" . $_GET['id'] . "' ");
        $msg = 'Benefit Deleted Successfully.';
    }
}

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<title>Course Benefits</title>



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

         

           <h3>Course Benefits</h3>

         

         </div>

         

         <div id="adddel" style="width:600px;">

         

           <ul>
             <li style="float:right"><a href="add_benefit.php">Add</a></li>
           </ul>

         

         </div>

       

       </div>

       

       <div id="right_corner">

       </div>

       

       <div id="main_content">

       

         <div id="productslistsearch">           

           

           <div class="clear">&nbsp;</div>

           

           

           <?php

			 

			          if (isset($_GET['msg']) || !empty($msg))

					  {

						  echo '<p style="color:black; font-size:28px; text-align:center; font-weight:bold">';

						  echo (!empty($msg)) ? $msg : $_GET['msg'];

						  echo '</p>';

					  }

					  elseif (isset($_GET['no']))

					  {

						  echo $no;

					  }

					  elseif (isset($_GET['invalid']))

					  {

						  echo '<p style="color:black; font-size:28px; text-align:center; font-weight:bold">';

						  echo $_GET['invalid'];

						  echo '</p>';

					  }

					  

		   ?>

           

           <div id="producthead">

           

             <div id="prtop">

             

               <h4>Course Benefits</h4>

             

             </div>

             

             <div id="prdetail">

             

               <table width="100%" border="0" cellspacing="0" cellpadding="0">
                   <tr id="first_row">
                       <th scope="col" class="common">Sr #</th>
                       <th scope="col" class="rare">Heading</th>
                       <th scope="col" class="rare">Short Description</th>
                       <th scope="col" class="common">Order</th>
                       <th scope="col" class="common" style="border-right:none;">Action</th>
                   </tr>
                   <?php
				   $counter = 1;
                   $benefit_result = $db->query("SELECT * FROM benefits ORDER BY sort_order DESC");
                   while($benefit_row = $benefit_result->fetch_array()) {
                       $id = $benefit_row['id'];
				   ?>
				   <tr id="rowrepeat">
				       <td class="small"><?php echo $counter; ?></td>
				       <td class="big"><?php echo $benefit_row['heading']; ?></td>
				       <td class="big"><?php echo $benefit_row['description']; ?></td>
				       <td class="big"><?php echo $benefit_row['sort_order']; ?></td>
                       <td class="small">
                           <a href="edit_benefit.php?id=<?php echo $id; ?>" class="edit"></a> 
                           <a href="course_benefits.php?id=<?php echo $id; ?>&act=del" class="del" onclick="return confirm('Are you sure you want to delete this benefit?');"></a>
                       </td>
                   </tr>
                   <?php
				       $counter++;
                   }
                   ?>



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