<?php include("admin/classes/connection.php");
$date = $_POST['vl'];
$qry = $db->query("SELECT * FROM `tbl_diploma` WHERE `cid`=$date");
$num = $qry->num_rows;
if($num > 0)
{ ?>
    <select name='diploma' id='diploma' class="form-control gentxt" required>
        <option selected disabled value="">Select Course</option>
    <?php while($result = $qry->fetch_array()) {
        if ($result['course_state'] == 1) { ?>
        <option value='free'><?php echo $result['name'] ?></option>
        <?php } else { ?>
        <option value='<?php echo $result['diplomaid']?>'><?php echo $result['name'] ?></option>
        <?php } ?>

    <?php
    } ?>
    </select>
<?php }
else {
        $vri=1;
        echo json_encode($vri);
	}
?>