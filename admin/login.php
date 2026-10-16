<?php
session_start();
include("classes/connection.php");
$username = trim(mysqli_real_escape_string($db, filter_var($_POST['username'], FILTER_SANITIZE_STRING)));
$password = md5(trim(mysqli_real_escape_string($db, filter_var($_POST['password'], FILTER_SANITIZE_STRING))));
//check the user name and password entered our not
if ($username =='' || $password =='') {
    echo "username and password not match";
    header('location:log_in.php?msg=msg');
    echo"<script language='javascript'>window.location='log_in.php?msg=msg'</script>";
    exit();
} else {
    $selectusername="select * from tbl_admin where sUserName='$username'";
    $queryselectusername=$db->query($selectusername);
    $usernamerow= $queryselectusername->num_rows;
    if($usernamerow =='0')
    {
        header('location:log_in.php?no=no');
        echo"<script language='javascript'>window.location='log_in.php?no=no'</script>";
        exit();
    }
    else
    {
        $selectusernamepass="select * from tbl_admin where sUserName='$username' and bLoginPassword='$password'";
        $queryselectusernamepass=$db->query($selectusernamepass);
        $usernamerowpass= $queryselectusernamepass->num_rows;
        if($usernamerowpass > 0)
        {
            $rowofusernamepass = $queryselectusernamepass->fetch_array();
            $adminid = $rowofusernamepass['IAdminID'];
            $_SESSION['adminid'] = $adminid;
            $_SESSION['adminType'] = $rowofusernamepass['adminType'];
            header('location:dashboard.php');
            echo"<script language='javascript'>window.location='dashboard.php'</script>";
            exit();
        }
        else
        {
            header('location:dashboard.php?msg=msg');
            echo"<script language='javascript'>window.location='log_in.php?msg=msg'</script>";
            exit();
        }
    }
}
?>