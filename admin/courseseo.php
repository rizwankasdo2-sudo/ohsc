<?php

session_start();

ob_start();

if($_SESSION['adminid']== "")

{

	header("location:index.php");

	exit();

}
if(isset($_GET['did']) && $_GET['cid'])
{
$did = $_GET['did'];
$cid = $_GET['cid'];	
	
}

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">



<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<title>SEO Page</title>



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

         

           <h3>SEO

         

         </h3></div><div id="adddel">

         

           <ul>

           

             <li style="float:right"><a href="add_courseseo.php?did=<?php echo $did; ?>&cid=<?php echo $cid; ?>">Add</a></li>

           

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

             

               <h4>SEO</h4>

             

             </div>

             

             <div id="prdetail">

             

               <table width="100%" border="0" cellspacing="0" cellpadding="0">

               

                 <tr id="first_row">

                   <th scope="col" class="common">Page Title</th>

                   <th scope="col" class="rare">Meta Title</th>

                   <th scope="col" class="rare">Meta Description</th>
                    <th scope="col" class="rare">Back</th>

       
                   <th scope="col" class="common" style="border-right:none;">Action</th>

                 </tr>

                 

                 <?php

			          $catlist = $db->query("SELECT * FROM `tbl_caourseseo` WHERE `diplomaid`=$did order by courseseo desc");

				     

					  while($row = $catlist->fetch_array())

					  {

						  $id = $row['courseseo'];

				 ?>

                 

                 <tr id="rowrepeat">

                   <td class="big"><?php echo $row['pagetitle']; ?></td>

                   <td class="big"><?php echo $row['metatitle']; ?></td>

                   <td class="big"><?php echo $row['metadescription']; ?></td>
                   <td class="big"><a href="diploma.php?cid=<?php echo $cid; ?>">Back</a></td>

                   

                   <td class="small">

                   

                    <a href="update_couseseo.php?id=<?php echo $id; ?>&did=<?php echo $did; ?>&cid=<?php echo $cid; ?>" class="edit"></a>

                     <a href="delete_courseseo.php?uid=<?php echo $id; ?>&did=<?php echo $did; ?>&cid=<?php echo $cid; ?>" class="del"></a>
					<?php header("location: update_couseseo.php?id=$id&cid=$cid&did=$did"); ?>
                     

                   </td>

                 </tr>

                 

                  <?php

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