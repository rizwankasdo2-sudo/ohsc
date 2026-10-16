<?php
session_start();
ob_start();
include("classes/connection.php");
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
<title>Certificates</title>
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
           include("includes/admin-header.php");
		   include("includes/admin-menu.php");
   ?>
   <div id="outer_contanier">
     <div id="board">
       <div id="left_corner">
       </div>
       <div id="midle_repeat">
         <div id="productsheading">
           <h3>Certificates</h3>
         </div>
         <div id="adddel">
           <ul>
             <li style="float:right"><a href="add_certificate.php">Add</a></li>
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
               <h4>Certificate</h4>
             </div>
             <div id="prdetail">
               <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr id="first_row">
                   <th scope="col" class="common">Title</th>
                    <th scope="col" class="common">Tagline</th>
                   <th scope="col" class="common">Earn By</th>
                   <th scope="col" class="common" style="border-right:none;">Action</th>
                 </tr>
                <?php
                $certificate_result = $db->query("SELECT * FROM certificates ORDER BY title ASC");
                while ($certificate_row = $certificate_result->fetch_array()) {
				 ?>
                <tr id="rowrepeat">
                    <td class="big"><?php echo $certificate_row['title']; ?></td>
                    <td class="big"><?php echo $certificate_row['tag_line']; ?></td>
                    <td class="big"><?php echo $certificate_row['earn_by']; ?></td>
                   <td class="small">
                       <a href="edit_certificate.php?id=<?php echo $certificate_row['id']; ?>" class="edit"></a>
                       <a href="delete_certificate.php?id=<?php echo $certificate_row['id']; ?>" class="del" onclick="return confirm('Are you sure you want to delete this certificate?');"></a>
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
