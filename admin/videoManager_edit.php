<?php
session_start();
ob_start();
if($_SESSION['adminid']== "")
{
	header("location:index.php");
	exit();
} 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Video Manager</title>

<link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />

<link rel="stylesheet" href="../Resources/admin_css/baner.css" />

<link href="../Resources/admin_css/add-product.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>

<script type="text/javascript" src="../Resources/admin_js/myform.js"></script>

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
       
       <div id="midle_repeat">
         
         <div id="bannerheading">
         
           <h3>Video Manager</h3>
         
         </div>
         
         <div id="addsave">
         
           <!--<ul>
           
             <li><a href="#">Save</a></li>
             <li><a href="#">Cancel</a></li>
           
           </ul>-->
         
         </div>
       
       </div>
       
       <div id="right_corner">
       </div>
       
       <div id="main_content">
       
         <div id="addbaners">           
           
           <div id="banerhead">
           
             <div id="banertop">
             
               <h4>Update Video Manager</h4>
             
             </div>
             
             <div id="banerdetail22">
             
             <div id="listofbanners">
			 
			 <?php
             
			          $id = $_GET['id'];
					  $select = $db->query("select * from tbl_videos where vVideoID=$id");
					  $rowofbanner = $select->fetch_array();
			 ?>
             
             <form action="videoManager_upload_edit_delete.php?case=updatebanner" method="post" name="bannerupdate" enctype="multipart/form-data">
             
             <div style="float:left; width:100%">
             
               <div class="fieldsname" style="float:left; width:20%; border:0; font-family:'Myriad Pro',Arial">Video Name</div>
               <div class="inputfield" style="float:left; width:80%;">
               
                 <input type="text" name="banername" id="banername" style="width:350px; height:25px;" value="<?php echo $rowofbanner['vVideoName']; ?>"   />
               
               </div>
               
             </div>
             
             <div style="float:left; width:100%; margin-top:25px;">
             
               <div class="fieldsname" style="float:left; width:20%; border:0; font-family:'Myriad Pro',Arial">Video Image :</div>
               <div style="float:left;width:80%;">
                 
				   <input type="file" name="banerpath" id="banerpath" style="vertical-align:top; margin-right:30px;" />  
                 
               </div>
             
             </div>
             
             <div style="float:left; width:100%; margin-top:25px;">
             
               <div class="fieldsname" style="float:left; width:20%; border:0; font-family:'Myriad Pro',Arial">Video Link</div>
               <div class="inputfield" style="float:left; width:80%;">
               
                 <input type="text" name="videolink" id="banername" style="width:350px; height:25px;"  value="<?php echo $rowofbanner['vVideoLink']; ?>" />
               
               </div>
               
             </div>
             
             
             
             
             
             
             
             
             
             
             <div style="float:left; width:100%; margin-top:25px;">
             
               <div class="fieldsname" style="float:left; width:20%; border:0; font-family:'Myriad Pro',Arial">Status :</div>
               <div class="inputfield" style="float:left; width:80%;">
               
                 <select name="status" id="status" class="stat" style="width:355px; height:25px;" >
                    
                    <?php if($rowofbanner['vStatus']==1 ){
						
						?>
                    
                    
                      <option value="1" selected="selected">Enable</option>
                      
                        <option value="0">Disable</option>
                        
                        <?php } else if($rowofbanner['vStatus']==0 ) {?>
                  
                      <option value="1" >Enable</option>
                      
                        <option value="0" selected="selected">Disable</option>
                        
                        <?php } ?>
                     </select>
               
               </div>
               
             </div>
             
             
             
             <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />
             
             <div style="float:left; width:100%; margin-top:15px;">
             
               <div class="fieldsname" style="float:left; width:20%;">&nbsp;</div>
               <div class="inputfield" style="float:left; width:80%;">
               
                 <input type="submit" value="Update" />
                 <input type="button" value="Cancel" onClick="window.location.href='videoManager.php'" />
                 
               </div>
             
             </div>
             
             </form>
             
             <div class="clear"></div>
             
             </div>
             
             </div>
           
           </div>
         
         </div>
              
       </div>
     
     </div>

     <?php include("includes/admin-footer.php"); ?>
	 
   </div>
  
  </div>
  <script>
	CKEDITOR.replace( 'description',
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