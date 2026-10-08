<?php include("admin/classes/connection.php");
$date = $_POST['vl'];
$qry = $db->query("SELECT * FROM `tbl_diploma` WHERE `cid`=$date and `course_state` = 0");
$num = $qry->num_rows;
if($num > 0)
{
?><select name='diplomad' id='diplomad' class="form-control gentxt" required>
    <option selected disabled value="" class="gentxt">Select Course</option>
<?php 
while($result = $qry->fetch_array())
{
?>
<option value='<?php echo $result['diplomaid']?>' class="gentxt"><?php echo $result['name']?></option>
<?php }?>
</select>

<?php 
}
else
{
	$vri=1;
	echo json_encode($vri);
	
	}
?>