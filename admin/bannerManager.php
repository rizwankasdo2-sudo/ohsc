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



<title>Banner Manger</title>



<link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />



<link rel="stylesheet" href="../Resources/admin_css/baner.css" />



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

         

         <div id="bannerheading">

         

           <h3>Banner Manager</h3>

         

         </div>

         

         <div id="addsave">

         

           <!--<ul>

           

             <li><a href="#">Save</a></li>

             <li><a href="#">Cancel</a></li>

           

           </ul>-->

         

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

             

             <div id="banertop">

             

               <h4>Add New Banner</h4>

             

             </div>

             

             <div id="banerdetail">

             

               <div id="uploadbanner">

               

                 <form action="upload_edit_delete_Banner.php?case=addbanner" method="post" name="banerupload" enctype="multipart/form-data" onsubmit="return valForm();">

                 

                   <div id="uploadfield">

                   

                     <p>Caption : </p>

                     

                     <input name="banername" type="text" class="banernamefield" id="banername" value="" size="45" />

                   

                   </div>

                   

                   <div id="uploadfield" style="width:267px;">

                   

                     <input type="file" class="banerimgfield" name="banerpath" id="banerpath" style="float:left; margin-right:0px; width:250px;" />

                     

                      <!--<strong style="float:left; font-family:Verdana, Geneva, sans-serif; font-size:12px; margin-top:15px;">(983 x 305)</strong>-->

                   

                   </div>

                   
			 <div class="status"  style="width:138px;">

                   

                     <p>Sr # :</p>

                     
				<input name="serialnumber" type="text" class="srialnumber" id="serialnumber" style="width:63px;"/>
                    
                   

                   </div>

                   <div class="status"  style="width:198px;">

                   

                     <p>Status :</p>

                     

                     <select name="status" id="status" class="stat" style="width:124px;">

                     

                       <option selected="selected" value="2">Select Status</option>

                       <option value="1">Enable</option>

                       <option value="0">Disable</option>

                     

                     </select>

                   

                   </div>

                   

                   <div id="uploadfield" style="width:120px; border:0;">

                   

                     <input type="submit" value="Add" class="browse" style="margin:7px 0px 0 20px;" />

                   

                   </div>

                 

                 </form>

               

               </div>

             

             </div>

           

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

             

               <h4>Banners</h4>

             

             </div>

             

             <div id="banerdetail22">

             

               <div id="listofbanners">

             

               <table width="100%" border="0" cellspacing="0" cellpadding="0">

               

               

                 <tr id="top_row">
					<th scope="col" class="lasttwo">Sr.#</th>
                   <th scope="col" class="bbtitle">Caption</th>

                   <th scope="col" class="thumbnailshow">Image</th>

                   <th scope="col" class="lasttwo">Status</th>

                   <th scope="col" class="lasttwo" style="border-right:none;">Action</th>

                 </tr>

                 

                   <?php

			 

			            $bannerslist = $db->query("SELECT * FROM tbl_banners order by ISerialnumber asc");
						$counter=1;
				        while($row= $bannerslist->fetch_array())

						{

							$id = $row['IBannerID'];

				   

				   ?>

                 

                 <tr id="rowdownrepeat">
					<td class="bbtitd"><?php echo $row['ISerialnumber']; ?></td>
                    
                   <td class="bbtitd"><?php echo $row['IBannerCaption']; ?></td>

                   <td class="bigimg"><img src="../Resources/banner/<?php echo $row['IBannerPath'];?>" alt="" width="100" height="100" /></td>

                   <td class="last2">

                   

                     <?php

                            if ($row['IBannerStatus'] == 1)

							{

								echo "Enable";

							}

							elseif (($row['IBannerStatus']) == 0)

							{

								echo "Disable";

							}

					 ?>

                   

                   </td>

                   

                   <td align="center" class="last2">

                   

                        <a href="banner_Edit.php?id=<?php echo $id; ?>" class="et"></a>

                        <a href="upload_edit_delete_Banner.php?id=<?php echo $id; ?>&case=<?php echo "deletebanner"; ?>" class="dl"></a>

                   

                   </td>

                   

                 </tr>

                 

                   <?php $counter++; } ?>

               

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