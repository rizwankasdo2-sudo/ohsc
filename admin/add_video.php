<?php
session_start();

ob_start();

if($_SESSION['adminid']== "")

{

    header("location:index.php");

    exit();

}
/*if(isset($_GET['cid']))
{
    $cid = $_GET['cid'];

}*/
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">



<head>



    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



    <title>Add Video</title>


    <link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />

    <link rel="stylesheet" href="../Resources/admin_css/add-product.css" />

    <script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>

    <script type="text/javascript" src="cms/ckeditor/ckeditor.js"></script>
    <script src="cms/ckeditor/sample.js" type="text/javascript"></script>



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

if($_POST['save']){

    $video = $_POST['video'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    $insert = $db->query("INSERT into video (video,description,status)value('$video','$description','$status')");

    
}



?>



<div id="outer_contanier">



<div id="board">



<div id="left_corner">

</div>



<form action="" method="post" enctype="multipart/form-data" name="addproduct" runat="server">



<div id="midle_repeat">



    <div id="tit">



        <h3>Add Video</h3>



    </div>
    <input type="hidden" name="cid" value="<?php echo $cid; ?>" />


    <div id="buttons">



        <ul style="float:right;">



            <li><input type="submit" name="save" value="Save" class="add_cancel_button" /></li>

            <li><a href="video.php">Cancel</a></li>



        </ul>



    </div>



</div>



<div id="right_corner">

</div>



<div id="main_content">



    <div id="addproducts">



        <div id="uper">



            <div id="upertop">



                <h4>Overview</h4>



            </div>



            <div id="uperdetail">



                <div id="general">



                    <div class="fieldrepeat">



                        <p>video: </p>

                        <input type="text" id="video" name="video" class="productfieldlarge" style="width:300px;" />




                    </div>



                    <div class="clear">

                    </div>

  <div class="fieldrepeat">

    <p>Description : </p>
    <div style="width:80%; float:left">
        <textarea id="description" name="description" class="ckeditor" cols="30" rows="15">
           
        </textarea>
    </div>

</div>
                   

                    <div class="fieldrepeat">



                        <p>Status : </p>



                        <select name="status" id="status" class="productfieldlarge" style="width:140px;">

                            <option value="1">Enable</option>

                            <option value="0">Disable</option>

                        </select>



                    </div>



                    <div class="clear">

                    </div>



                </div>



            </div>



            <div class="clear">&nbsp;</div>



        </div>



    </div>



</div>



<div class="clear">&nbsp;</div>



</form>



</div>



<div class="clear">&nbsp;</div>



<?php include("includes/admin-footer.php"); ?>



</div>



</div>



</body>

</html>