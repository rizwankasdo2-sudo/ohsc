<?php
session_start();
ob_start();
if ($_SESSION['adminid']== "") {
	header("location:index.php");
	exit();
}
include("classes/connection.php");
if (isset($_POST['email_subject'])) {
    $email_subject = trim($_POST['email_subject']);
    $email_body = trim($_POST['email_body']);
    $autosend_five = $_POST['autosend_five'];
    if ($email_subject == '' || $email_body == '') {
        $msg = 'Both fields are mandatory.';
    } else {
        $db->query("INSERT INTO email_templates SET email_subject = '$email_subject', email_body = '$email_body', autosend_five = '$autosend_five' ");
        header('location:manage-emails.php?msg=1');
        exit;
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Add Email</title>
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
		   if (!empty($msg)) {
		       echo '<br /><p style="color:black; font-size:28px; text-align:center; font-weight:bold">';
			   echo $msg;
			   echo '</p>';
		   }
		   ?>
            <div id="outer_contanier">
                <div id="board">
                    <div id="left_corner">
                    </div>
                    <form method="post">
                        <div id="midle_repeat">
                            <div id="tit">
                                <h3>Add Email</h3>
                            </div>
                            <div id="buttons">
                                <ul style="float:right;">
                                    <li><input type="submit" value="Save" class="add_cancel_button" /></li>
                                    <li><a href="manage-emails.php">Cancel</a></li>
                                </ul>
                            </div>
                        </div>
                        <div id="right_corner">
                        </div>
                        <div id="main_content">
                            <div id="addproducts">
                                <div id="uper">
                                    <div id="upertop">
                                        <h4>Add Email</h4>
                                    </div>
                                    <div id="uperdetail">
                                        <div id="general">
                                            <div class="fieldrepeat">
                                                <p>Email Subject: </p>
                                                <textarea name="email_subject" id="email_subject" cols="60" rows="10" required><?php echo $_POST['email_subject']; ?></textarea>
                                            </div>
                                            <div class="clear">
                                            </div>
                                            <div class="fieldrepeat">
                                                <p>Email Body: </p>
                                                <div style="width:80%; float:left">
                                                    <textarea id="email_body" name="email_body" class="ckeditor" cols="30" rows="15"><?php echo $_POST['email_body']; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="clear">
                                            <div class="fieldrepeat">
                                                <p>Send Automatically:<br />(On Five Star Rating) </p>
                                                <div style="width:80%; float:left">
                                                    <select name="autosend_five" style="padding:5px;">
                                                        <option value="0" <?php echo ($_POST['autosend_five'] == '0') ? 'selected' : ''; ?>>No</option>
                                                        <option value="1" <?php echo ($_POST['autosend_five'] == '1') ? 'selected' : ''; ?>>Yes</option>
                                                    </select>
                                                </div>
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
        <script>
            CKEDITOR.replace( 'email_body',
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