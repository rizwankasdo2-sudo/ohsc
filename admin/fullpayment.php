<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

}
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



<title>Full Payment</title>



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

         

           <h3>Full Payment</h3>

         

         </div>

         

         <div id="adddel">

         

           <ul>

           

             <li style="float:right"><a href="add_payment.php?did=<?php echo $did; ?>&cid=<?php echo $cid; ?>">Add</a></li>

           

           </ul>

         

         </div>

       

       </div>

       

       <div id="right_corner">

       </div>

       

       <div id="main_content">

       

         <div id="productslistsearch">           

           

           <div class="clear">&nbsp;</div>

           

           

           <?php

			 

			          if (isset($_GET['msg']))

					  {

						  echo '<p style="color:black; font-size:28px; text-align:center; font-weight:bold">';

						  echo $_GET['msg'];

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

             

               <h4>Full Payment</h4>

             

             </div>

             

             <div id="prdetail">

             

               <table width="100%" border="0" cellspacing="0" cellpadding="0">

               

                 <tr id="first_row">

                   <th scope="col" class="common">Sr #</th>
                   
                    <th scope="col" class="common">Total Fee</th>
                     <th scope="col" class="common">Discount</th>
                     <th scope="col" class="common">Back</th>
                     <th scope="col" class="common">Status</th>

                   <th scope="col" class="common" style="border-right:none;">Action</th>

                 </tr>

                 

                 <?php
				 $counter=1;
$catqry = $db->query("select * from tbl_payment where diplomaid=$did order by paymentid asc");
				     

					  while($row = $catqry->fetch_array())

					  {

						  $id = $row['paymentid'];

				 ?>

                 

                 <tr id="rowrepeat">

                   <td class="big"><?php echo $counter; ?></td>
                   
                     <td class="big"><?php echo $row['payment']; ?></td>
                     
                      <td class="big"><?php echo $row['discount']; ?></td>
                      <td class="big"><a href="diploma.php?cid=<?php echo $cid; ?>">Back</a></td>

                  
                    
           

                   <td class="small">

                   

                     <?php

                            if ($row['IStatus'] == 1)

							{

								echo "Enable";

							}

							elseif (($row['IStatus']) == 0)

							{

								echo "Disable";

							}

					 ?>

                   

                   </td>

                   <td class="small">

                   

                    <a href="edit_payment.php?id=<?php echo $id; ?>&cid=<?php echo $cid; ?>&did=<?php echo $did; ?>" class="edit"></a>

                     <a href="delete_payment.php?uid=<?php echo $id; ?>&cid=<?php echo $cid; ?>&did=<?php echo $did; ?>" class="del"></a>
					<?php header("location: edit_payment.php?id=$id&cid=$cid&did=$did"); ?>
                     

                   </td>

                 </tr>

                 

                  <?php

					$counter++;  }

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