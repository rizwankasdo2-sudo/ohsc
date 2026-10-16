<?php
include("classes/connection.php");
$counting = 0;
$searching = array();
if(isset($_POST['status']) && $_POST['status'] != 5){
    $status = (int)$_POST['status'];
    $searching[] = " status=$status ";
} else {
    $counting ++;
}
if(isset($_POST['course_type']) && $_POST['course_type'] != 5){
    $course_type = $_POST['course_type'];
    $searching[] = " form_type='$course_type' ";
} else {
    $counting ++;
}

if(isset($_POST['to_date']) && !empty($_POST['to_date']) && isset($_POST['from_date']) && !empty($_POST['from_date'])){
   $to_date = date("Y-m-d", strtotime($_POST['to_date']));
   $from_date = date("Y-m-d", strtotime($_POST['from_date']));
    $searching[] = " (apply_date BETWEEN '$from_date' AND '$to_date') ";
} else {
    $counting ++;
}
if($counting == 3){
    $sql = "SELECT * FROM tbl_info ORDER BY apply_date";
} else {
    $sql = "SELECT * FROM tbl_info WHERE ".join(' AND ', $searching)." ORDER BY apply_date DESC";
}

$result = $db->query($sql);
if(!$result){
    die(mysqli_error($db));
}
$count = 0;
while($row = $result->fetch_array()) {
$count++;
    $id = $row['infoid'];
    $cid = $row['cid'];
    $diplomaid = $row['diplomaid'];

    $type = $row['form_type'];
    $date = date('d-m-Y', strtotime($row['apply_date']));

    $programqry = $db->query("SELECT cname FROM `tbl_courses` WHERE `cid`=$cid");
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

<?php  } ?>


