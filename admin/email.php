<?php
session_start();
ob_start();

if($_SESSION['adminid']== "") {

	header("location:index.php");
	exit();

}
 
 if(isset($_GET['did']) && isset($_GET['cid'])) {

    $did = $_GET['did'];
    $cid = $_GET['cid'];

} ?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Udate Full Payment</title>

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

?>

   <div id="outer_contanier">

     <div id="board">

       <div id="left_corner">

       </div>

       <?php

if(isset($_POST['update'])){

	$subject = $_POST['subject'];
    $body = trim($_POST['body']);

    $sql = "SELECT COUNT(*) FROM emails WHERE diploma_id='$did'";
    $result = $db->query($sql);

    $row = $result->fetch_array();
    if($row[0]){
        $sql = "UPDATE emails SET subject='$subject', body = '$body' WHERE diploma_id = '$did'";
        if(!$db->query($sql)){
            die(mysqli_error($db));
        }
    } else {
        $sql = "INSERT INTO emails (diploma_id, subject, body) VALUES ('$did', '$subject', '$body')";
        if(!$db->query($sql)){
            die(mysqli_error($db));
        }
    }

	$msg="Updated Sucssefully";
	echo "<script language='javascript'>window.location='diploma.php?cid=$cid&msg=$msg'</script>";
	exit();
	} ?>



       

       <form action="email.php?did=<?php echo $did ?>&cid=<?php echo $cid; ?>" method="post" enctype="multipart/form-data" name="addproduct" runat="server">

       

       <div id="midle_repeat">

         

         <div id="tit">

         

           <h3>Full Payment</h3>

         

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

            <?php
			$qry=$db->query("select * from  emails where diploma_id='$did'");
			$emailRecord = $qry->fetch_array(); ?>
                     

                     
                       <div class="fieldrepeat">


                         <p>Subject : </p>

                        <input type="text" id="subject" name="subject" value="<?php echo $emailRecord['subject'];?>" class="productfieldlarge" style="width:300px;" />

                       

         </div>

                       

                                 <div class="clear">

                                 </div>

 
                       <div class="fieldrepeat">

                       

                         <p>Message : </p>

                        <div style="width: 78%;padding-top:10px;margin-left: 183px;">
                            <textarea id="body"  name="body" cols="3" rows="4" class="ckeditor"><?php echo $emailRecord['body'];?></textarea>
                        </div>

                       

         </div>
                       

                         <div class="clear">

                         </div>

                       

                       

                                 <div class="clear">

                                 </div>

                                

                                 

                    <div id="buttons" style=" width:713px; margin:0 auto; margin-top:10px;"> 

                    <input type="submit" name="update" id="update" value="update" />

                      <input type="button" value="Cancel" onClick="window.location.href='diploma.php?cid=<?php echo $cid;?>'" />

            

                    </div>

                </div>



             </div>

             

             <div class="clear">&nbsp;

              

             </div>

             

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
  <script>
	CKEDITOR.replace( 'body',
    {
        filebrowserBrowseUrl : 'cms/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Images',
        filebrowserFlashBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Flash',
        filebrowserUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        filebrowserFlashUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
    });
	</script>
</body>

</html>









