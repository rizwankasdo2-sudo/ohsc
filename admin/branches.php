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



<title>Branches</title>



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

         

           <h3 style="width:300px;">Branches</h3>

         

         </div>

         

         <div id="adddel">

         

           <ul>

           

             <li style="float:right"><a href="add_branches.php">Add</a></li>

           

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

             

               <h4>Branches</h4>

             

             </div>

             

             <div id="prdetail">

             <?php if(isset($_GET['invalid'])) echo "invalid enter data first";?>

             

               <table width="100%" border="0" cellspacing="0" cellpadding="0">

               

                 <tr id="first_row">

                   <th scope="col" class="common">Sr #</th>


                   <th scope="col" class="common">Map</th>
 					<th scope="col" class="rare">Image</th>
				
                 <th scope="col" class="rare">Order number</th>
                 
                 
                
                   <th scope="col" class="common">Status</th>

                   <th scope="col" class="common" style="border-right:none;">Action</th>

                 </tr>

                 

                 <?php
					$counter = 1;
			          $catlist = $db->query("SELECT * FROM tbl_branches order by IOrder asc");

				     

					  while($row = $catlist->fetch_array())

					  {

						  $id = $row['IBranchid'];

				 ?>

                 

                 <tr id="rowrepeat">

                   <td class="small"><?php echo $counter; ?></td>

       

                   <td class="big"><?php echo $row['IBranchAddress']; ?></td>
                   
                   <td class="big"><img src="../Resources/branch/<?php echo $row['IImage']; ?>" width="200" height="200" /></td>

                  <td class="small"><?php echo $row['IOrder']; ?></td>

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

                   

                    <a href="edit_branch.php?id=<?php echo $id; ?>" class="edit"></a>

                     <a href="deletebranch.php?uid=<?php echo $id; ?>" class="del"></a>

                     

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