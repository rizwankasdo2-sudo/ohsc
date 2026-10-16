<?php
include("classes/connection.php");

$course = $_POST['course'];
$count = 0;

$sql_course = $db->query("SELECT * FROM tbl_diploma WHERE name LIKE '%$course%' LIMIT 10");

while($row_courses = $sql_course->fetch_array()){

    $id = $row_courses['diplomaid'];

$sql = "SELECT * FROM tbl_info WHERE diplomaid='$id'";
$result = $db->query($sql);
if(!$result){
    die(mysqli_error($db));
}

while($row = $result->fetch_array()) {

    $count++;
    $id = $row['infoid'];
    $cid = $row['cid'];
    $diplomaid = $row['diplomaid'];

    $type = $row['form_type'];
    $date = date('d-m-Y', strtotime($row['apply_date']));

    $programqry = $db->query("SELECT cname FROM `tbl_courses` WHERE `cid`=$cid" );
    $programresult = $programqry->fetch_array();
    $program = $programresult['cname'];

    $diploma = $db->query("SELECT name FROM `tbl_diploma` WHERE `diplomaid`=$diplomaid");
    $diploma = $diploma->fetch_array();
    $diplomass = $diploma['name']; ?>



    <tr>
        <td><?php echo $count; ?></td>
        <td><?php echo $type; ?></td>
        <td><?php if($date != '01-01-1970'){ echo $date; }; ?></td>
        <td><?php echo $diplomass; ?></td>
        <td><?php echo $row['firstname'].'&nbsp;'.$row['lastname']; ?></td>
        <td><?php echo $row['email']; ?></td>
        <td><?php echo $row['payment_method']; ?></td>
        <td>
            <input type="hidden" value="<?php echo $row['infoid']; ?>" class="info_id_found"/>
            <select name="status" class="info_status">
                <option value="0" <?php if($row['status'] == 0){ echo 'selected'; }?>>New</option>
                <option value="1" <?php if($row['status'] == 1){ echo 'selected'; }?>>Processed</option>
                <option value="2" <?php if($row['status'] == 2){ echo 'selected'; }?>>Info Sent</option>
                <option value="3" <?php if($row['status'] == 3){ echo 'selected'; }?>>Invoiced</option>
                <option value="4" <?php if($row['status'] == 4){ echo 'selected'; }?>>Payment Made</option>
            </select></td>
        <td><a href="delete_info.php?id=<?php echo $id; ?>" class="dl">DELETE</a></td>
    </tr>

<?php  }
}?>


