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
    <title>Pages</title>
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

           <h3>Pages</h3>

         </div>

         <div id="adddel">

           <ul>
               <li style="float:right"><a href="add_page.php">Add</a></li>
           </ul>

         </div>

    </div>

       <div id="right_corner">
       </div>

       <div id="main_content">

         <div id="productslistsearch">           

           <div class="clear">&nbsp;</div>

    <?php

    if (isset($_GET['msg'])) {

      echo '<p style="color:black; font-size:28px; text-align:center; font-weight:bold">';

      echo $_GET['msg'];

      echo '</p>';

    }

    elseif (isset($_GET['no'])) {

      echo $no;

    }

    elseif (isset($_GET['invalid'])) {

      echo '<p style="color:black; font-size:28px; text-align:center; font-weight:bold">';

      echo $_GET['invalid'];

      echo '</p>';

    }
?>
<div id="producthead">

    <div id="prtop"><h4>Pages</h4></div>

<div id="prdetail">

<table width="100%" border="0" cellspacing="0" cellpadding="0">

<tr id="first_row">
    <th scope="col" class="common">Sr #</th>
    <th scope="col" class="rare">Page Name</th>
    <th scope="col" class="common">SEO</th>
     
    <th scope="col" class="common" style="border-right:none;">Action</th>
</tr>

<tr id="rowrepeat">

    <td class="small">1</td>
    <td class="big">Home</td>

	<td class="small"><a href="update_seo.php?uid=1" >SEO</a></td>
	
	<td class="small"> <a href="edit_home.php?id=1" class="edit"></a> </td>
</tr>

<tr id="rowrepeat">

    <td class="small">2</td>
    <td class="big">How to pay</td>

	<td class="small"><a href="update_seo.php?uid=11" >SEO</a></td>
	
	<td class="small"> <a href="edit_pay.php?id=1" class="edit"></a> </td>
</tr>
<tr id="rowrepeat">

    <td class="small">3</td>
    <td class="big">Free Courses</td>

	<td class="small"><a href="update_seo.php?uid=3" >SEO</a></td>
	
	<td class="small"> <a href="edit_freecourses.php?id=2" class="edit"></a> </td>
</tr>

<tr id="rowrepeat">

    <td class="small">4</td>
    <td class="big">About Us</td>

	<td class="small"><a href="update_seo.php?uid=2" >SEO</a></td>
	
	<td class="small"> <a href="Edit_about_template.php" class="edit"></a> </td>
</tr>

<tr id="rowrepeat">

    <td class="small">5</td>
    
   
    <td class="big">How Distance Learning Works</td>

	<td class="small"><a href="update_seo.php?uid=7" >SEO</a></td>
	
	<td class="small"><a href="edit_dlearning.php?id=1" class="edit"></a> </td>
</tr>

<tr id="rowrepeat">

    <td class="small">6</td>
     <td class="big">Privacy Policy</td>

	<td class="small"><a href="update_seo.php?uid=13" >SEO</a></td>
	
	<td class="small"><a href="update_policy.php"  class="edit" ></a></td>
    
</tr>
<tr id="rowrepeat">

    <td class="small">7</td>
    <td class="big">Contact Us</td>

	<td class="small"><a href="update_seo.php?uid=6" >SEO</a></td>
	<td class="small"><a href="edit_contactus.php?id=1"  class="edit" ></a></td>
</tr>
<tr id="rowrepeat">

    <td class="small">8</td>
    <td class="big">Apply Online</td>

	<td class="small"><a href="update_seo.php?uid=4" >SEO</a></td>
	
	<td class="small">--</td>
</tr>

<tr id="rowrepeat">

    <td class="small">9</td>
   <td class="big">All Courses</td>

	<td class="small"><a href="update_seo.php?uid=5" >SEO</a></td>
	
	<td class="small">--</td>
</tr>
<tr id="rowrepeat">

    <td class="small">10</td>
    <td class="big">FAQ</td>

	<td class="small"><a href="update_seo.php?uid=9" >SEO</a></td>
	
	<td class="small">--</td>
   
</tr>
<tr id="rowrepeat">

    <td class="small">11</td>
      <td class="big">Blog</td>

	<td class="small"><a href="update_seo.php?uid=10" >SEO</a></td>
	
	<td class="small">--</td>
   
</tr>
<tr id="rowrepeat">

    <td class="small">12</td>
    <td class="big">Competition Rules</td>
    <td class="small"></td>
	
	
	 
    <td class="small"><a href="edit_page.php?id=29"  class="edit" ></a></td>
   
</tr> 
<?php
  $count =13;
  $query = "SELECT * FROM pages WHERE id !=31";
  $result = $db->query($query);
  if($result->num_rows > 0)
  {
  while($record = $result->fetch_array())
  {
?>
    <tr id="rowrepeat">
      <td class="small"><?php echo $count; ?></td>
      <td class="big"><?php echo $record['name']; ?></td>
      <td class="small"></td>
      <td class="small"><a href="edit_page.php?id=<?php echo $record['id']; ?>"  class="edit" ></a></td>
    </tr>
<?php
$count++;
  }
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