<div id="rightContent">
   <?php
          include("classes/connection.php");
		  
		  $isp = $_GET['q'];
		  $abc = $db->query("SELECT * FROM tbl_customer where ICustomerID=$isp");
		  $row = $abc->fetch_array();
		  
		  $cid = $row['ICustomerID'];
      ?>
    <!-- end of AboutMe-->

      
      <div id="PersonalInformation">

<div class="RContHeader">
<div><img src="../Resources/admin_images/topLeftcorner.jpg" height="51px"  style="float:left;" /></div>
<div><img src="../Resources/admin_images/topRightcorner.jpg" height="51px" style="float:right;" /> </div>
<div class="RCheading" >Personal Information </div>
</div><!-- end of PInfoHeader-->

<div class="clear"></div>
<div class="grediantBg">
<div class="RContBg">
 <table>
 <tr  style="font:12px Arial, Helvetica, sans-serif; font-weight:bold; color:#083b5b; ">
 <td width="200px" height="30px;" style="padding-left:15px;">Name</td>
 <td width="405px" style="padding-left:15px;"><?php echo $row['sFirstName'].' '.$row['sLastName']; ?></td>
 </tr>
 
  
 <tr style="font:12px Arial, Helvetica, sans-serif; font-weight:bold; color:#083b5b;" bgcolor="#dbf0fb">
 <td width="200px" height="30px;" style="padding-left:15px;">State</td>
 <td width="405px" style="padding-left:15px;"><?php echo $row['sState']?></td>
 </tr>
 
 <tr style="font:12px Arial, Helvetica, sans-serif; font-weight:bold; color:#083b5b;" bgcolor="#dbf0fb">
 <td width="200px" height="30px;" style="padding-left:15px;">Adress</td>
 <td width="405px" style="padding-left:15px;"><?php echo $row['sStreetAddress']?></td>
 </tr>
 
 <tr style="font:12px Arial, Helvetica, sans-serif; font-weight:bold; color:#083b5b;" >
 <td width="200px" height="30px;" style="padding-left:15px;">Phone</td>
 <td width="405px" style="padding-left:15px;"><?php echo $row['sPhoneNo']?></td>
 </tr>

 <tr style="font:12px Arial, Helvetica, sans-serif; font-weight:bold; color:#083b5b; " bgcolor="#dbf0fb">
  <td width="200px" height="30px;" style="padding-left:15px;">Email</td>
 <td width="405px" style="padding-left:15px;"><?php echo $row['sEmail']?></td>
 </tr>
  <tr style="font:12px Arial, Helvetica, sans-serif; font-weight:bold; color:#083b5b; ">
  <td width="200px" height="30px;" style="padding-left:15px;">Suburb</td>
 <td width="405px" style="padding-left:15px;"><?php echo $row['sSburb']?></td>
 </tr>

 <tr style="font:12px Arial, Helvetica, sans-serif; font-weight:bold; color:#083b5b;" bgcolor="#dbf0fb">
  <td width="200px" height="30px;" style="padding-left:15px;">Postal Code</td>
 <td width="405px" style="padding-left:15px;"><?php echo $row['sPostalCode']?></td>
 </tr>
 
 <tr style="font:12px Arial, Helvetica, sans-serif; font-weight:bold; color:#083b5b;" bgcolor="#dbf0fb">
 
 <td></td>
 </tr>

 </table>
</div>
<!-- end of PInfoTxt -->
</div><!-- end of background image div -->

<div class="RContFooter">
<div><img src="../Resources/admin_images/bottomLeftCorner.jpg"  style="float:left;" /></div>
<div><img src="../Resources/admin_images/bottomRightCorner.jpg" style="float:right;" /> </div>
</div><!-- end of PInfoFooter-->
</div><!-- end of PersonInformation-->
   
</div>