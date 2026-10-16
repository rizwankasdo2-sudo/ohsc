<?php
session_start();
ob_start();

if($_SESSION['adminid']== "")
{
	header("location:index.php");
	exit();
}
include("classes/connection.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Students Reviews</title>
    <link rel="stylesheet" href="../Resources/admin_css/admin-style.css?v=1" />
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

           <h3>Students Reviews</h3>

         </div>

         <div id="adddel">


         </div>

    </div>

       <div id="right_corner">
       </div>

       <div id="main_content">

         <div id="productslistsearch">           

           <div class="clear">&nbsp;</div>

    <?php

    if (!empty($_POST['email_template'])) {
        echo '<p style="color:black; font-size:28px; text-align:center; font-weight:bold">All emails will be sent shortly.</p>';
    }
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
    <div style="text-align:center;padding-bottom:20px;">
        <div class="orange_sec" style="margin-bottom:20px;">Filter Reviews</div>
        <form method="post">
            <select name="sOnly" style="padding:5px;">
                <option value="2" <?php echo ($_POST['sOnly'] == 2) ? 'selected' : ''; ?>>All Reviews</option>
                <option value="3" <?php echo ($_POST['sOnly'] == 3) ? 'selected' : ''; ?>>Reviews With Image</option>
                <option value="4" <?php echo ($_POST['sOnly'] == 4) ? 'selected' : ''; ?>>Reviews With Video</option>
                <option value="5" <?php echo ($_POST['sOnly'] == 5) ? 'selected' : ''; ?>>Reviews With Video And Image</option>
            </select>
            <select name="sOnlyRating" style="padding:5px;margin-left:10px;">
                <option value="">-- Select Rating --</option>
                <option value="5" <?php echo ($_POST['sOnlyRating'] == 5) ? 'selected' : ''; ?>>Five Star</option>
                <option value="4" <?php echo ($_POST['sOnlyRating'] == 4) ? 'selected' : ''; ?>>Four Star</option>
                <option value="3" <?php echo ($_POST['sOnlyRating'] == 3) ? 'selected' : ''; ?>>Three Star</option>
                <option value="2" <?php echo ($_POST['sOnlyRating'] == 2) ? 'selected' : ''; ?>>Two Star</option>
                <option value="1" <?php echo ($_POST['sOnlyRating'] == 1) ? 'selected' : ''; ?>>One Star</option>
            </select>
            <span style="padding-left:10px;">From:</span>
            <input type="date" class="form_field" name="sOnlyFrom" placeholder="From" style="margin-left:10px;" value="<?php echo $_POST['sOnlyFrom']; ?>">
            <span style="padding-left:10px;">To:</span>
            <input type="date" class="form_field" name="sOnlyTo" placeholder="From" style="margin-left:10px;" value="<?php echo $_POST['sOnlyTo']; ?>"><br />
            <select name="country" class="form-control" style="padding:5px;">
                <option>-- Select Country --</option>
                <?php
                $country_result = $db->query("SELECT name FROM country ORDER BY name ASC");
                while ($country_row = $country_result->fetch_array()) {
                ?>
                <option value="<?php echo $country_row['name']; ?>" <?php echo ($country_row['name'] == $_POST['country']) ? 'selected' : ''; ?>><?php echo $country_row['name']; ?></option>
                <?php } ?>
            </select><br />
            <input type="submit" value="Filter Results" style="padding:5px;margin-top:15px;">
        </form>
        <div class="orange_sec" style="margin-top:20px;margin-bottom:20px;">Send Email</div>
        <div>
            <form method="post">
                <input type="hidden" name="sOnly" value="<?php echo $_POST['sOnly']; ?>" />
                <input type="hidden" name="sOnlyRating" value="<?php echo $_POST['sOnlyRating']; ?>" />
                <input type="hidden" name="sOnlyFrom" value="<?php echo $_POST['sOnlyFrom']; ?>" />
                <input type="hidden" name="sOnlyTo" value="<?php echo $_POST['sOnlyTo']; ?>" />
                <input type="hidden" name="country" value="<?php echo $_POST['country']; ?>" />
                <select name="email_template" style="padding:5px;">
                    <option>-- Select Email --</option>
                    <?php
                    $email_result = $db->query("SELECT * FROM email_templates");
                    while ($email_row = $email_result->fetch_array()) {
                    ?>
                    <option value="<?php echo $email_row['id']; ?>"><?php echo $email_row['email_subject']; ?></option>
                    <?php } ?>
                </select> &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="submit" value="Send Email" style="padding:5px;">
            </form>
        </div>
    </div>
<div id="producthead">

    <div id="prtop"><h4>Students Reviews</h4></div>

<div id="prdetail">

<table width="100%" border="0" cellspacing="0" cellpadding="0">

<tr id="first_row">
    <th scope="col" class="common">Sr #</th>
    <th scope="col" class="rare">Course Title</th>
    <th scope="col" class="rare">Name</th>
    <th scope="col" class="rare">Email</th>
    <th scope="col" class="rare">Submitted Date</th>
    
    <th scope="col" class="common" style="border-right:none;">Action</th>
</tr>

<?php
$counter=1;
$query_extend = '';
if (isset($_POST['sOnly'])) {
    if ($_POST['sOnly'] == 3) {
        $query_extend .= " AND R.photo <> '' ";
    } else if ($_POST['sOnly'] == 4) {
        $query_extend .= " AND R.video <> '' ";
    } else if ($_POST['sOnly'] == 5) {
        $query_extend .= " AND R.photo <> '' AND R.video <> '' ";
    }
    if (!empty($_POST['sOnlyRating'])) {
        $query_extend .= " AND R.rating = '" . $_POST['sOnlyRating'] . "' ";
    }
    if (!empty($_POST['sOnlyFrom'])) {
        $query_extend .= " AND R.submited_date >= '" . $_POST['sOnlyFrom'] . "' ";
    }
    if (!empty($_POST['sOnlyTo'])) {
        $query_extend .= " AND R.submited_date <= '" . $_POST['sOnlyTo'] . "' ";
    }
    if (!empty($_POST['country'])) {
        $query_extend .= " AND R.country = '" . $_POST['country'] . "' ";
    }
}
$pages_query = "SELECT D.name AS diplomaName, D.diplomaid, R.name, R.email,R.id, R.submited_date FROM `review_form` AS R INNER JOIN `tbl_diploma` AS D ON R.course_title = D.diplomaid WHERE R.id <> '' $query_extend";
$rrr = $db->query($pages_query);


while ($row = $rrr->fetch_array()) {
    if (!empty($_POST['email_template'])) {
        $db->query("INSERT INTO send_emails SET review_form_id = '" . $row['id'] . "', email_template_id = '" . $_POST['email_template'] . "' ");
    }
?>
<tr id="rowrepeat">

    <td class="small"><?php echo $counter; ?></td>
    <td class="big"><?php echo $row['diplomaName'];?></td>
    <td class="big"><?php echo $row['name'];?></td>
    <td class="big"><?php echo $row['email'];?></td>
    <td class="big"><?php echo date('d/m/Y', strtotime($row['submited_date']));?></td>
    



<td class="small">

<a href="single_review.php?id=<?php echo $row['id'];?>" class="edit1">  View </a> |  
<a onclick="return confirm('Are you sure you want to delete this Review?');" href="delete_review.php?id=<?php echo $row['id'];?>" class="del1"> Delete</a>


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