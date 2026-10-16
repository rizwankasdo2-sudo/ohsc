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

    <title>Blog</title>

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



           <h3>Blog</h3>



         </div>



         <div id="adddel" style="width:800px;">



           <ul>
              <li style="float:right"><a href="blog_categories.php" style="width:180px;background-size: 100% 100%;">Blog Categories</a></li> 
              <li style="float:right"><a href="add_blog.php">Add</a></li>
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



    <div id="prtop"><h4>Blog</h4></div>



<div id="prdetail">



<table width="100%" border="0" cellspacing="0" cellpadding="0">



<tr id="first_row">

    <th scope="col" class="common">Sr #</th>

    <th scope="col" class="rare">Post Title</th>

    <th scope="col" class="common">Category</th>

    <th scope="col" class="common">Date</th>

    <th scope="col" class="common">Status</th>

    <th scope="col" class="common" style="border-right:none;">Action</th>

</tr>



<?php

$counter=1;

$catqry = $db->query("select * from blog order by id asc");



while($row = $catqry->fetch_array()) {



$category_id = $row['category'];



    $cat_query = "SELECT cname FROM tbl_courses WHERE cid='$category_id'";

    $cat_result = $db->query($cat_query);

    if(!$cat_result){

        die(mysqli_error($db));

    }



    $cat_found = $cat_result->fetch_array();



?>

<tr id="rowrepeat">



    <td class="small"><?php echo $counter; ?></td>

    <td class="big"><?php echo $row['title']; ?></td>

    <td class="small">

<?php echo $cat_found['cname']; ?>

    </td>

    <td class="small"><?php echo $row['publish_date']; ?></td>



<td class="small">



    <?php if ($row['status'] == 1) {



        echo "Enable";



    } elseif (($row['status']) == 0) {



        echo "Disable";



    } ?>



</td>



<td class="small">



<a href="edit_blog.php?id=<?php echo $row['id']; ?>" class="edit"></a>

<!--<a href="delete_blog.php?id=<?php //echo $row['id']; ?>" class="del" onclick="return confirm('Are you sure you want to delete this item?');"></a>-->



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