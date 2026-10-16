<?php
session_start();

ob_start();

if($_SESSION['adminid']== "")

{

  header("location:index.php");

  exit();

}
$pagename = "form_manage.php";
$pagetitle = "Form Manage";
include "../includes/database.php";
include "../includes/functions.php";


if(isset($_POST['form_submit'])) {
    
    $form_heading = $_POST['form_heading'];
    $form_content = $_POST['form_content'];
    $form_heading2 = $_POST['form_heading2'];
    $form_content2 = $_POST['form_content2'];
    $guidelines_one = $_POST['guidelines_one'];
    $guidelines_two = $_POST['guidelines_two'];
   
    $m_title = $_POST['m_title'];
    $m_keywords = $_POST['m_keywords'];
    $m_description = $_POST['m_description'];

    if(isset($_FILES['banner_img']['name']) && !empty($_FILES['banner_img']['name'])){
        //$r_num = rand(1, 1000);

        $banner_img = $_FILES['banner_img']['name'];
        $dest = '../images/form_banner/'.$banner_img;
      
       list($width, $height) = getimagesize($_FILES['banner_img']['tmp_name']);
      
       if ($width >= 1167 && $height >= 260)
        {
          
        move_uploaded_file($_FILES['banner_img']['tmp_name'], $dest);
      }else{
         $msg = "Image is small from required Diminssion";
        
    }
}else{
  $banner_img = $_POST['banner_img1'];
}
     if(isset($_FILES['footer_img']['name']) && !empty($_FILES['footer_img']['name'])){
        //$r_num = rand(1, 1000);
        $footer_img = $_FILES['footer_img']['name'];
        list($widthFooter, $heightFooter) = getimagesize($_FILES['footer_img']['tmp_name']);

        if($widthFooter >= 1167 && $heightFooter >= 260)
        {
          $dest = '../images/form_banner/'.$footer_img;
           move_uploaded_file($_FILES['footer_img']['tmp_name'], $dest);
          
        }else{
            $msg = "Image is small from required Diminssion";
        }

       
    }else{
      $footer_img = $_POST['footer_img1'];
    } 

    if($banner_img!=""  && $width >= 1167 && $height >= 260 || $widthFooter >= 1167 && $heightFooter >= 260){


    $sql = "UPDATE form_manage SET
                form_heading=:form_heading,
                form_content=:form_content,
                form_heading2=:form_heading2,
                form_content2=:form_content2,
                guidelines_one=:guidelines_one,
                guidelines_two=:guidelines_two,
                
                m_title=:m_title,
                m_keywords=:m_keywords,
                m_description=:m_description,
                banner_img=:banner_img,
                footer_img=:footer_img
                ";

                $result_course = $db->prepare($sql);

                $values = [':form_heading'=>$form_heading, ':form_content'=>$form_content, ':form_heading2'=>$form_heading2, ':form_content2'=>$form_content2,':guidelines_two'=>$guidelines_two,':guidelines_one'=>$guidelines_one, ':m_title'=>$m_title, ':m_keywords'=>$m_keywords,
                ':m_description'=>$m_description,':banner_img'=>$banner_img, ':footer_img'=>$footer_img];


          $result_course->execute($values);

            $errors = $result_course->errorInfo();

        if($errors[2]){
            die($errors[2]);
        }
    
header("Location: form_manage.php?msg=Form updated successfully");

exit();
}
else
{
$sql = "UPDATE form_manage SET
                form_heading=:form_heading,
                form_content=:form_content,
                form_heading2=:form_heading2,
                form_content2=:form_content2,
                guidelines_one=:guidelines_one,
                guidelines_two=:guidelines_two,
                
                m_title=:m_title,
                m_keywords=:m_keywords,
                m_description=:m_description
                
                ";

                $result_course = $db->prepare($sql);

                $values = [':form_heading'=>$form_heading, ':form_content'=>$form_content, ':form_heading2'=>$form_heading2, ':form_content2'=>$form_content2,':guidelines_two'=>$guidelines_two,':guidelines_one'=>$guidelines_one, ':m_title'=>$m_title, ':m_keywords'=>$m_keywords,
                ':m_description'=>$m_description];


          $result_course->execute($values);

            $errors = $result_course->errorInfo();

        if($errors[2]){
            die($errors[2]);
        }
    
        header("Location: form_manage.php?msg=Form updated successfully");
     
              exit();


}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">



<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<title>Upcoming Events</title>



<link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />



<link rel="stylesheet" href="../Resources/admin_css/products.css" />



<script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>



<!--[if lte IE 6]>

  <script type="text/javascript" src="../Resources/admin_js/supersleight-min.js"></script>

<![endif]-->





</head>

  <?php

   

           include("classes/connection.php");

       

       include("includes/admin-header.php");

       

       include("includes/admin-menu.php");

   

   ?>
<?php

if($msg!="")
	{ ?>
	
	<div class="check_main">
	<div class="check">	
	<div class="bad"><img src="images/x.gif" alt="check" class="icon" /><?php echo $msg;?></div>
	</div>				
	</div>
	
	<?php } 
?>

<?php
if($_GET['msg']!="") { ?>
    <div class="check_main">
    <div class="check">
        <div class="good"><img src="images/check.gif" alt="check" class="icon" /><?php echo $_GET['msg'];?></div>
    </div>
<?php } ?>
<?php

$res = $db->query("SELECT * FROM form_manage where id = 2");

$row = $res->fetch();

$banner_img = $row['banner_img'];
$form_content = htmlentities($row['form_content']);
$form_content2 = htmlentities($row['form_content2']);
$guidelines_one = htmlentities($row['guidelines_one']);
$guidelines_two = htmlentities($row['guidelines_two']);
$footer_img = $row['footer_img'];
//print_r($row);

/* include($editor_include_path); */?>
      <script type="text/javascript" src="../includes/ckeditor/ckeditor.js"></script>
<script src="../includes/ckeditor/sample.js" type="text/javascript"></script>
<link href="../includes/ckeditor/sample.css" rel="stylesheet" type="text/css" />

 <?php
    if(isset($success_mg) && !empty($success_mg)){ ?>
        <div class="alert alert-success"><?php echo $success_mg; ?></div>
    <?php } ?>
<div class="in forms">
					<form id="courseform" name="courseform" method="post" action="" onsubmit="return validate_form ( )" enctype="multipart/form-data">

        <?php
    if(isset($msg) && !empty($msg)){ ?>
        <div class="alert alert-success"><?php echo $msg; ?></div>
    <?php } ?>
	
      				<p><strong>Form Heading</strong><br />
					<input type="text" name="form_heading" value="<?php echo $row['form_heading'];?>" class="box" />
					</p>
      				
      				
					
					<p><strong>Form Content</strong><br />
					<textarea  id="form_ckeditor" name="form_content" class="ckeditor" cols="30" rows="15">
                    <?php echo $form_content;?>
                    </textarea>
                    </p>

                        <p><strong>Form Heading 2</strong><br />
                            <input type="text" name="form_heading2" value="<?php echo $row['form_heading2'];?>" class="box" />
                        </p>

                   <p><strong>Form Content 2</strong><br />
                    <textarea  id="excerpt" name="form_content2" class="excerpt" cols="30" rows="15">
                    <?php echo $form_content2;?>
                    </textarea>
                    </p>

                      <p><strong>Guide Lines For what did you Like most about the course</strong><br />
                    <textarea  id="guide_ckeditor" name="guidelines_two" class="ckeditor" cols="30" rows="15">
                    <?php echo $guidelines_two;?>
                    </textarea>
                    </p>
                      <p><strong>Guide Lines For What did you like at least</strong><br />
                    <textarea  id="lines_ckeditor" name="guidelines_one" class="ckeditor" cols="30" rows="15">
                    <?php echo $guidelines_one;?>
                    </textarea>
                    </p>

                   <!--  <p><strong>Banner Image</strong><br />
            <img border='0' src='../images/broucher/<?php echo $row['uploadfile']; ?>' width='79' height='79' vspace='3'>
            <br><input type="file" name="uploadfile" class="box"/>
            <input type="hidden" name="uploadfile1" value="<?php echo $row['uploadfile']; ?>" />
                

            <br>
            <font color="red">Leave blank to retain old picture</font>
        </p> -->
          <!--  <p><strong>Banner Image</strong><br />
                          
             <img border='0' src='../course/<?php echo $banner_img; ?>' width='79' height='79' vspace='3'>
            <br><input type="file" name="banner_img" class="box"/><br>
            <font color="red">Leave blank to retain old picture</font>

                    
                    </p>    -->
                    <div class="form-group ">
                               <label class="control-label col-md-3"><strong>Banner Image</strong></label>
                              <span style="color:red"> (1167 x 260) </span><span><?php echo $msg; ?></span>
                                  <div class="col-md-9">
                                  <div class="fileinput fileinput-new" data-provides="fileinput">
                                
                              <div>
                                <!-- ///////// -->
                                <div class="broucher_img">
                        <?php if(!empty($banner_img)){ ?>
                        <img src="../images/form_banner/<?php echo $banner_img; ?>" style="width: 230px; height: 100px;"/>
                        <a href="remove_form_banner.php?id=2" class="btn default fileinput-exists" data-dismiss="fileinput">
                             Remove
                          </a> 
                        <?php } ?>  
                          </div>
                                <!-- ///////// -->
                          <span class="btn default btn-file"><?php echo $banner_img; ?>
                          <br><input type="file" name="banner_img" class="box"/><br>

                             <input type="hidden" name="banner_img1" value="<?php echo $banner_img; ?>">
                          </span>
                           
                        </div>
                      </div>
                      
                    </div>
                  </div>      
                     <div class="form-group ">
                               <label class="control-label col-md-3"><strong>Footer Image</strong></label>
                               <span style="color:red"> (1167 x 300)</span>
                                  <div class="col-md-9">
                                  <div class="fileinput fileinput-new" data-provides="fileinput">
                                
                              <div>
                                <!-- ///////// -->
                                <div class="broucher_img">
                        <?php if(!empty($footer_img)){ ?>
                        <img src="../images/form_banner/<?php echo $footer_img; ?>" style="width: 230px; height: 100px;"/>
                        <a href="remove_form_footer_image.php?id=2" class="btn default fileinput-exists" data-dismiss="fileinput">
                             Remove
                          </a> 
                        <?php } ?>  
                          </div>
                                <!-- ///////// -->
                          <span class="btn default btn-file"><?php echo $footer_img; ?>
                          <br><input type="file" name="footer_img" class="box"/><br>

                             <input type="hidden" name="footer_img1" value="<?php echo $footer_img; ?>"> 
                          </span>
                           
                        </div>
                      </div>
                      
                    </div>
                  </div> 
                    <!-- <p><strong>Page Title</strong><br />
                    <input type="text" name="m_title" value="<?php echo $row['m_title'];?>" class="box">
                    
                                
                            </p> -->
        <!--  <p><strong>Meta keyword</strong><br />
        
           <textarea  name="m_keywords" class="box" rows="8"><?php echo $row['m_keywords'];?></textarea>
                </p> -->
       <!--   <p><strong>Meta Description</strong><br />
       
          <textarea  name="m_description" class="box" rows="8"><?php echo $row['m_description'];;?></textarea>
               </p> -->
                    
                 
                  
					<p>
					<input type='hidden' name='form_submit' value='1'>					
					<input name="submit" type="submit" id="submit" class="com_btn" value="Add" /></p>
					</form>
			
		</div>
		
		
       
 <?php include("includes/admin-footer.php"); ?>
<script>
  CKEDITOR.replace( 'form_ckeditor',
    {
        filebrowserBrowseUrl : 'cms/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Images',
        filebrowserFlashBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Flash',
        filebrowserUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        filebrowserFlashUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
    });
  CKEDITOR.replace( 'excerpt',
    {
        filebrowserBrowseUrl : 'cms/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Images',
        filebrowserFlashBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Flash',
        filebrowserUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        filebrowserFlashUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
    });
  CKEDITOR.replace( 'guide_ckeditor',
    {
        filebrowserBrowseUrl : 'cms/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Images',
        filebrowserFlashBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Flash',
        filebrowserUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        filebrowserFlashUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
    });

  CKEDITOR.replace( 'lines_ckeditor',
    {
        filebrowserBrowseUrl : 'cms/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Images',
        filebrowserFlashBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Flash',
        filebrowserUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        filebrowserFlashUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
    });
  </script> 
   