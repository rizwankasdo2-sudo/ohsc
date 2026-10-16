<div id="rightContent">

 <?php
        include("classes/connection.php");
		
		 $isp = $_GET['q'];
		$abc = $db->query("SELECT * FROM `tbl_customer` WHERE `ICustomerID`=$isp");
		$row = $abc->fetch_array();

 ?>

 <div id="PersonalInformation" style="margin-top:0px;">

   <div class="RContHeader">
    <div>
     <img src="../Resources/admin_images/topLeftcorner.jpg" height="51px"  style="float:left;" />
    </div>
    <div>
     <img src="../Resources/admin_images/topRightcorner.jpg" height="51px" style="float:right;" />
    </div>
    <div class="RCheading">
     Customer Information
    </div>
   </div>        <!-- end of PInfoHeader-->
   
   <div class="clear"></div>
   
   <div class="grediantBg">
   
    <div class="RContBg">
    
     <table style="width:640px; overflow:visible">
      
      <tr style="font:12px Arial, Helvetica, sans-serif; font-weight:bold; color:#083b5b;">
       <td width="200px" height="30px;" style="padding-left:15px;">First Name</td>
       <td width="405px" style="padding-left:15px;"><?php echo $row['sFirstName']; ?></td>
      </tr>
      
      <tr style="font:12px Arial, Helvetica, sans-serif; font-weight:bold; color:#083b5b;" bgcolor="#dbf0fb">
       <td width="200px" height="30px;" style="padding-left:15px;">Last Name</td>
       <td width="405px" style="padding-left:15px;"><?php echo $row['sLastName']; ?></td>
      </tr>
      
      <tr style="font:12px Arial, Helvetica, sans-serif; font-weight:bold; color:#083b5b;">
       <td width="200px" height="30px;" style="padding-left:15px;">Shipment Address</td>
       <td width="405px" style="padding-left:15px;"><?php echo $row['sStreetAddress']; ?></td>
      </tr>
      
      <tr style="font:12px Arial, Helvetica, sans-serif; font-weight:bold; color:#083b5b;" bgcolor="#dbf0fb">
       <td width="200px" height="30px;" style="padding-left:15px;">Suburb</td>
       <td width="405px" style="padding-left:15px;"><?php echo $row['sSburb']; ?></td>
      </tr>
      
      <tr style="font:12px Arial, Helvetica, sans-serif; font-weight:bold; color:#083b5b;">
       <td width="200px" height="30px;" style="padding-left:15px;">State</td>
       <td width="405px" style="padding-left:15px;"><?php echo $row['sState']; ?></td>
      </tr>
      
      <tr style="font:12px Arial, Helvetica, sans-serif; font-weight:bold; color:#083b5b;" bgcolor="#dbf0fb">
       <td width="200px" height="30px;" style="padding-left:15px;">Postal Code</td>
       <td width="405px" style="padding-left:15px;"><?php echo $row['sPostalCode']; ?></td>
      </tr>
      
      <tr style="font:12px Arial, Helvetica, sans-serif; font-weight:bold; color:#083b5b;">
       <td width="200px" height="30px;" style="padding-left:15px;">Phone No</td>
       <td width="405px" style="padding-left:15px;"><?php echo $row['sPhoneNo']?></td>
      </tr>
      
      <tr style="font:12px Arial, Helvetica, sans-serif; font-weight:bold; color:#083b5b;" bgcolor="#dbf0fb">
       <td width="200px" height="30px;" style="padding-left:15px;">Email</td>
       <td width="405px" style="padding-left:15px;"><?php echo $row['sEmail']; ?></td>
      </tr>
      
      
      <tr style="font:12px Arial, Helvetica, sans-serif; font-weight:bold; color:#083b5b;" bgcolor="#dbf0fb">
       <td></td>
      </tr>
    
     </table>
     
    </div>    <!-- end of PInfoTxt -->
   
   </div>        <!-- end of background image div -->
   
   <div class="RContFooter">
    <div>
     <img src="../Resources/admin_images/bottomLeftCorner.jpg"  style="float:left;" />
    </div>
    <div>
     <img src="../Resources/admin_images/bottomRightCorner.jpg" style="float:right;" />
    </div>
   </div>    <!-- end of PInfoFooter-->
 
 </div>    <!-- end of PersonInformation-->
   
</div>