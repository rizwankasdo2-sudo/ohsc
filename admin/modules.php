<?php
session_start();
ob_start();
include("classes/connection.php");
if($_SESSION['adminid']== "")
{
	header("location:index.php");
	exit();
}
$diplomaid = $_GET['did'];
$diploma_row = $db->query("SELECT name FROM tbl_diploma WHERE diplomaid = '$diplomaid'")->fetch_array();
if (!$diploma_row) {
    header("location:cours.php");
    exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Modules in <?php echo $diploma_row['name']; ?></title>
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
           <h3>Course Modules</h3>
         </div>
         <div id="adddel">
           <ul>
             <li style="float:right"><a href="add_module.php?did=<?php echo $diplomaid; ?>">Add</a></li>
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
               <h4>Modules in <?php echo $diploma_row['name']; ?></h4>
             </div>
             <div id="prdetail">
               <table width="100%" border="0" cellspacing="0" cellpadding="0">
                 <tr id="first_row">
                   <th scope="col" class="common">Title</th>
                    <th scope="col" class="common">Excerpt</th>
                   <th scope="col" class="common">Required</th>
                   <th scope="col" class="common" style="border-right:none;">Action</th>
                 </tr>
                <?php
                $module_result = $db->query("SELECT * FROM tbl_modules WHERE diplomaid = $diplomaid ORDER BY id DESC");
                while ($module_row = $module_result->fetch_array()) {
				 ?>
                <tr id="rowrepeat">
                    <td class="big"><?php echo $module_row['title']; ?></td>
                    <td class="big"><?php echo $module_row['excerpt']; ?></td>
                   <td class="small">
                        <?php
                        if ($module_row['required'] == 1) {
                            echo 'Required';
                        } else {
                            echo 'Optional';
                        }
					    ?>
                   </td>
                   <td class="small">
                    <a href="update_module.php?did=<?php echo $diplomaid; ?>&id=<?php echo $module_row['id']; ?>" class="edit"></a>
                     <a href="delete_module.php?did=<?php echo $diplomaid; ?>&id=<?php echo $module_row['id']; ?>" class="del" onclick="return confirm('Are you sure you want to delete this module?');"></a>
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
