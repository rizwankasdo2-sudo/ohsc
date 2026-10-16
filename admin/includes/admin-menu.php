<?php
//we need to set a var to find what page we are on
$pageOn = basename($_SERVER['PHP_SELF']);
//this is used for the title bar
$mItem = $pageOn;
?>

<div id="menu">
   
     <ul id="cssmenu1">
     
       
          <li><a href="dashboard.php" <?php if ( ($pageOn == 'dashboard.php') || ($pageOn == 'HomePage.php') || ($pageOn == 'Edit_HomePage_template.php') || ($pageOn == 'about_page.php') || ($pageOn == 'Edit_about_template.php') || ($pageOn == 'collerbration_page.php') || ($pageOn == 'Edit_collebration_template.php') || ($pageOn == 'privacy_page.php') || ($pageOn == 'Edit_privacy_template.php') || ($pageOn == 'contect_page.php') || ($pageOn == 'Edit_contect_template.php') ){?> class="selected"<?php }?>>CMS</a></li>
          
          
          <li><a href="#" style="width:200px" <?php if ( ($pageOn == 'logoManager.php') || ($pageOn == 'logo_Edit.php') || ($pageOn == 'add_video_in_cat.php') || ($pageOn == 'videoManager.php') || ($pageOn == 'videoManager_Edit.php') || (strpos($_SERVER['SCRIPT_NAME'], '/tools/admin/') !== false) ){?> class="selected"<?php }?>>Website Management</a>
           <ul style="height:auto;">
           
             <li><a href="banner.php" style="width:190px">Banner Manager</a></li>
             <li><a href="cours.php" style="width:190px">Courses Manager</a></li>
             <li><a href="manage-emails.php" style="width:190px">Emails Manager</a></li>
             <li><a href="certificates.php" style="width:190px">Certificate Manager</a></li>
             <li><a href="careers.php" style="width:190px">Career Manager</a></li>
             <li><a href="key_aspects.php" style="width:190px">Key Aspect Manager</a></li>
              <?php /*?><li><a href="news.php" style="width:190px">News Manager</a></li><?php */?>
              <li><a href="testimonial.php" style="width:190px">Testimonail Manager</a></li>
               <li><a href="faq.php" style="width:190px">FAQ Manager</a></li>
               <li><a href="tools-manager" style="width:190px">Tools Manager</a></li>
               <li><a href="typing-passages" style="width:190px">Typing Texts</a></li>
               <li><a href="eq-questions" style="width:190px">EQ Questions</a></li>
                <li><a href="partner.php" style="width:190px">Partner Manager</a></li>
                <li><a href="blog.php" style="width:190px">Bolg</a></li>
               <!-- <li><a href="update_policy.php" style="width:190px">Policy Manager</a></li>            
                <li><a href="terms_conditon.php " style="width:190px">Terms Conditions</a></li>                     
                <li><a href="update_tc.php" style="width:190px">TC Manager</a></li>-->                
                
             <li><a href="info.php" style="width:190px">Course Information</a></li>
             <li><a href="voucher_info.php" style="width:190px">Voucher Redemption</a></li>
              <li><a href="prospectus.php" style="width:190px">Prospectus</a></li>
              <li><a href="edit_social.php" style="width:190px">Social Links</a></li>
               <li><a href="courses_details.php" style="width:190px">Courses Feature</a></li>
              <li><a href="video.php" style="width:190px">Video</a></li>
              <li><a href="stdreviews.php" style="width:190px">Students Reviews</a></li>
              <li><a href="edit_form_manage.php" style="width:190px">Reviews Form Manage</a></li>
             <li><a href="terms_conditon.php" style="width:190px">Terms & Conditions</a></li>
               <?php /*?> <li><a href="Tprogram.php" style="width:190px">Shop by price</a></li>
               <li><a href="zipcode.php" style="width:190px">Delivery Charges</a></li><?php */?>
             <li><a href="chngepass.php" style="width:190px">Change Password</a></li>
               
              
               
              
            </ul>
          </li>
          
          
        
 
     </ul>
   
</div>