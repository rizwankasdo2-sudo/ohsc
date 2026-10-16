<?php
session_start();
ob_start();
if($_SESSION['adminid']== "") {
	header("location:index.php");
	exit();
}?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Course Information</title>
<link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />
<link rel="stylesheet" href="../Resources/admin_css/products.css" />
<script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>

<!--[if lte IE 6]>

	<script type="text/javascript" src="../Resources/admin_js/supersleight-min.js"></script>

<![endif]-->

<style>
    table{
        border-collapse: collapse;
    }
    table thead tr{
        background: #f5f5f5;
    }
    table td, table th{
        border: 1px solid #ddd;
        padding: 10px 10px 10px 10px;
        text-align: left;
        font-family: "Helvetica", "Arial", sans-serif;
        font-size: 13px;
    }
    table tr:nth-child(even){
        background: #f5f5f5;
    }
</style>



</head>



<body>



 <div id="wrap_dashboard">

   

   <?php

           include("classes/connection.php");

		   include("includes/admin-header.php");

		   include("includes/admin-menu.php");


   if(isset($_GET['del_id']) && !empty($_GET['del_id'])){
       $del_id = $_GET['del_id'];
       $del_sql = "DELETE FROM vouchers WHERE infoid=$del_id";
       $db->query($del_sql);
   } ?>

   <div id="outer_contanier">

   <div id="board">

       <div id="left_corner">

       </div>

       <div id="midle_repeat">

         <div id="productsheading" style="width:300px;">

           <h3>Voucher code Information</h3>

         </div>

         

         <!--<div id="adddel">

         

           <ul>

           

             <li style="float:right"><a href="add_task.php">Add</a></li>

           

           </ul>

         

         </div>-->

       

       </div>

       

       <div id="right_corner">

       </div>

       

       <div id="main_content">

       

         <div id="productslistsearch">

           <div class="clear">&nbsp;</div>

           <div id="producthead">

             <div id="prdetail">

  <form method="post" action="voucher_info.php" enctype="multipart/form-data" class="filter_form">
      <div style="width: 1000px; margin: 0px auto;">

          <label>From:</label>
          <input type="date" class="form_field" id="" name="from_date" placeholder="From" style="width: 180px">

          <label>To:</label>
          <input type="date" class="form_field" id="" name="to_date" placeholder="From" style="width: 180px">

          <input type="submit" name="submit" value="Submit" class="browse" style="padding: 7px 10px; margin-left: 14px;" />

      </div>
      <hr />
  </form>

<table style="width: 100%;">

<thead>
    <tr>
        <th style="width: 20px">No</th>
        <th style="width: 75px">Date</th>
        <th>Course</th>
        <th>Name</th>
        <th>Email</th>
        <th>Country</th>
        <th>Voucher Code</th>
        <th>Action</th>
    </tr>
</thead>

<tbody class="d_found_course">
    <?php

    if(isset($_GET['page'])){

        $recordShow = 100;
        $page = $_GET['page'];
        $thisPage = ($page*100) - $recordShow;
        $infoQuery = "SELECT * FROM vouchers order by infoid desc LIMIT 100 OFFSET $thisPage";

    } elseif(isset($_POST['from_date']) && !empty($_POST['from_date']) && !empty($_POST['to_date'])){

        $from = $_POST['from_date'];
        $to = $_POST['to_date'];
        $infoQuery = "SELECT * FROM vouchers WHERE (apply_date BETWEEN '$from' AND '$to') order by infoid desc";

    } else {

        $thisPage = 0;
        $page = 1;
        $infoQuery = "SELECT * FROM vouchers order by infoid desc LIMIT 100 OFFSET $thisPage";

    }

    $catlist = $db->query($infoQuery);

    if(!$catlist){
        die(mysqli_error($db));
    }

$count = $thisPage;

    while($row = $catlist->fetch_array()) {

$count++;
        $id = $row['infoid'];
        $cid = $row['cid'];
        $diplomaid = $row['diplomaid'];

        $date = date('d-m-Y', strtotime($row['apply_date']));

        $programqry = $db->query("SELECT * FROM `tbl_courses` WHERE `cid`=$cid");
        $programresult = $programqry->fetch_array();
        $program = $programresult['cname'];

        $diploma = $db->query("SELECT * FROM `tbl_diploma` WHERE `diplomaid`=$diplomaid");
        $diploma = $diploma->fetch_array();
        $diplomass = $diploma['name']; ?>



    <tr>
        <td><?php echo $count; ?></td>
        <td><?php if($date != '01-01-1970'){ echo $date; }; ?></td>
        <td><?php echo $diplomass; ?></td>
        <td><?php echo $row['firstname'].'&nbsp;'.$row['lastname']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td><?php echo $row['country']; ?></td>
        <td><?php echo $row['voucher']; ?></td>
        <td><a href="voucher_info.php?del_id=<?php echo $id; ?>" class="dl" onclick="return confirm('Do you want to delete this item.')">DELETE</a></td>
    </tr>

    <?php  } ?>

</tbody>
</table>
</div>

<?php
    $pageQuery = "SELECT count(*) FROM vouchers";
    $pageResult = $db->query($pageQuery);
    $pageRow = $pageResult->fetch_array();
    $pageCount = $pageRow[0]/100;

    for($i=1; $i<=$pageCount; $i++) {
        if ($page == $i) { ?>
            <a href="<?php echo $base_url;?>/admin/info.php?page=<?php echo $i; ?>" class="page_link current"><?php echo $i; ?></a>
        <?php } else { ?>
            <a href="<?php echo $base_url;?>/admin/info.php?page=<?php echo $i; ?>" class="page_link"><?php echo $i; ?></a>
        <?php
        }
    } ?>

</div>

         

         </div>

         

         <div class="clear">&nbsp;</div>

              

       </div>

     

     </div>

     

     <?php include("includes/admin-footer.php"); ?>

   

   </div>

 

 </div>

<script src="ajax/jquery-1.12.3.min.js"></script>
<script src="ajax/custom.js"></script>
</body>

</html>