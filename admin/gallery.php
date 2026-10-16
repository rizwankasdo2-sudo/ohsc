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



<title>Picture Gallery</title>



<link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />



<link rel="stylesheet" href="../Resources/admin_css/baner.css" />



<script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>

<link rel="stylesheet" href="../Resources/admin_css/products.css" type="text/css" />



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

         

           <h3>Dishes Images</h3>

         

         </div>

         

         <div id="adddel">

         

           <ul>

           

             <li style="float:right"><a href="add_galleryimages.php">Add</a></li>

           

           </ul>

         

         </div>

       

       </div>

       

       <div id="right_corner">

       </div>

       

       <div id="main_content">

       

         <div id="addbaners">

         

           <div id="banerhead">

           

             <?php

			 

			          if (isset($_GET['no']))

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

             

           

           

           

           </div>

           

           

           <div class="clear">&nbsp;</div>

           

           <?php

			 

			          if (isset($_GET['msg']))

					  {

						  echo '<p style="color:black; font-size:28px; text-align:center; font-weight:bold">';

						  echo $_GET['msg'];

						  echo '</p>';

					  }

					  

		   ?>

           

           <div id="banerhead">

           

             <div id="banertop">

             

               <h4>Dishes Images</h4>

             

             </div>

             

             <div id="banerdetail22">

             

               <div id="listofbanners">

             

               <table width="100%" border="0" cellspacing="0" cellpadding="0">

               

               

                 <tr id="top_row">

                   <th scope="col" class="bbtitle">Description</th>
                   
                

                   <th scope="col" class="thumbnailshow">Small Image</th>

                    <th scope="col" class="thumbnailshow">Larg Image</th>

                   <th scope="col" class="lasttwo">Status</th>

                   <th scope="col" class="lasttwo" style="border-right:none;">Action</th>

                 </tr>

                 

                   <?php

			 

			            $bannerslist = $db->query("SELECT * FROM tbl_gallery order by IGalleryID desc");

				        while($row= $bannerslist->fetch_array())

						{

							$id = $row['IGalleryID'];
							
							

				   

				   ?>

                 

                 <tr id="rowdownrepeat">

                   <td class="bbtitd"><?php echo $row['IImageName']; ?></td>
                  
                   <td class="bigimg"><img src="../Resources/gallery/<?php echo $row['ISmallImage'];?>" alt="" width="110" height="80" /></td>

                   <td class="bigimg"><img src="../Resources/gallery/<?php echo $row['ILargeImage'];?>" alt="" width="110" height="80" /></td>

                    
                    
                 
                     
                    
                    
                
                     
                    
                  
                   
                    

                   <td class="last2">

                   

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

                   

                   <td align="center" class="last2">

                   

                        <a href="gallery_Edit.php?id=<?php echo $id; ?>" class="et"></a>

                        <a href="upload_edit_delete_gallery.php?id=<?php echo $id; ?>&case=<?php echo "deletebanner"; ?>" class="dl"></a>

                   

                   </td>

                   

                 </tr>

                 

                   <?php } ?>

               

               </table>



             

             </div>

             

             </div>

             

             

           

           </div>

         

         </div>

              

       </div>

     

     </div>

	 

	 <?php include("includes/admin-footer.php"); ?>

	 

   </div>

  

  </div>

     

</body>

</html>