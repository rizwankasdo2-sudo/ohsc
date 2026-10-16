<div id="rightContent">

 <?php
        include("classes/connection.php");
		
		 $isp = $_GET['q'];
		$abcd = $db->query("SELECT * FROM `tbl_customer` WHERE `ICustomerID`=$isp");
		
		$customarresult = $abcd->fetch_array();
		
		 $customarid = $customarresult['ICustomerID'];
		 $postcode = $customarresult['sPostalCode'];
		
		$abc = $db->query("SELECT * FROM tbl_tmporderinfo where ICustoamrID=$customarid");
		$infocount = $abc->num_rows;
		$sql = $abc->fetch_array();
		
		$time = $sql['IOrderTime'];
		$tempid = $sql['ITemporderinfoID'];
		/*$ordercustid = $sql['ICustomerID'];*/

		$date = new DateTime($sql['sOrderDate']);
		$HeaderDate = $date->format('F j, Y');
		
		// or, procedurally:
		
		$date = date_create($sql['sOrderDate']);
		$HeaderDate = date_format(date_create($sql['sOrderDate']), 'F j, Y');
 ?>

 <div id="invoiceheader">
 
   <div id="invoiceleft">
     <img src="../images/logo.jpg" width="300" alt="" />
     <!--<input type="button" value="Print Now" onClick="window.print()"/>-->
   </div>
   <div id="invoiceright">
     <h3>Order Invoice</h3>
     <div class="clear">&nbsp;</div>
     <p><strong>Order ID : </strong> <?php echo $tempid; ?></p>
     <p><strong>Order Date : </strong> <?php echo $HeaderDate; ?></p>
   </div>
   <div class="clear">&nbsp;</div>
 </div>               <!-- end of invoiceheader-->
 
 <div id="invoiccompany">
 
   <div id="companyleft">
   
     <h4>From</h4>
     
     <table style="width:inherit; height:210px;">
     
      <tr>
       <td class="heading"><strong>Company Name : </strong></td>
       <td class="description">We buy any phone</td>
      </tr>
      
      <tr>
       <td class="heading"><strong>Address : </strong></td>
       <td class="description">Austrilia</td>
      </tr>
      
      <tr>
       <td class="heading"><strong>Zip Code : </strong></td>
       <td class="description">46000</td>
      </tr>
      
      <tr>
       <td class="heading"><strong>Phone No : </strong></td>
       <td class="description">96638354239</td>
      </tr>
      
      <tr>
       <td class="heading"><strong>Fax : </strong></td>
       <td class="description">00966-(3) 8336077</td>
      </tr>
      
      <tr>
       <td class="heading"><strong>E-mail : </strong></td>
       <td class="description">abaya@abayatal.com</td>
      </tr>
     
     </table>
   
   </div>
   
   <div id="companyright">
   
     <h4>To</h4>
     <table style="width:inherit; height:210px;">

      <tr>
       <td class="heading"><strong> Name: </strong></td>
       <td class="description"><?php echo $customarresult['sFirstName'].'&nbsp'.$customarresult['sLastName']; ?></td>
      </tr>
      
      <tr>
       <td class="heading"><strong>Street Address : </strong></td>
       <td class="description"><?php echo $customarresult['sStreetAddress'];?></td>
      </tr>
      
      <tr>
       <td class="heading"><strong>Suburb : </strong></td>
       <td class="description"><?php echo $customarresult['sSburb']; ?></td>
      </tr>
      
     
      <tr>
       <td class="heading"><strong>Post code :</strong></td>
       <td class="description"><?php echo $customarresult['sPostalCode']; ?></td>
      </tr>
       <tr>
       <td class="heading"><strong>Phone No : </strong></td>
       <td class="description"><?php echo $customarresult['sPhoneNo']; ?></td>
      </tr>
      
      
      <tr>
       <td class="heading"><strong>E-mail : </strong></td>
       <td class="description"><?php echo $customarresult['sEmail']; ?></td>
      </tr>
     
     </table>
   
   </div>
 
 </div>
 
 <div class="clear">&nbsp;</div>
 
 <div id="invoictable">
 
 <?php
       if ($infocount > 0)
	   {  ?>
       <table style="width:inherit; border:none; border-collapse:collapse;" border="0" cellpadding="0" cellspacing="0">
		   
		    <tr style="background-color:#444444; height:30px;">
	
			 <td width="103" class="orderheading" style=" border-bottom:none">Category Name</td>
			 <td width="87" class="orderheading" style=" border-bottom:none">Item Name</td>
              <td width="138" class="orderheading" style=" border-bottom:none">Quantity</td>
			 <td width="75" class="orderheading" style=" border-bottom:none">Unit Price</td>
			
			 <td width="138" class="orderheading" style=" border-bottom:none">Total</td>
			</tr>
<?php
 $counter=1;
 $total = 0;
 $qry = $db->query("select * from tbl_temporderdetail where ITemporderinfoID=$tempid");
 
 while($row = $qry->fetch_array())
 
 	{
		$itemid = $row['ItemID'];
		$catid = $row['ICatID'];
		$price = $row['IPrice'];
		$numberofitems = $row['ITemno'];
		$catqry = $db->query("select * from tbl_menu where ICatID=$catid");
		$catresult = $catqry->fetch_array();
		$itemsqry = $db->query("select * from tbl_product where ItemID=$itemid");
 		$itemsresult = $itemsqry->fetch_array();
 ?>
  
				<tr <?php if($counter%2==0){?>style="background-color:#ebebeb; height:30px;"<?php }else{?> style="background:#F7F7F7; height:30px;"<?php }?>>
				
				<td class="orderdetaili" style=" border-top:none;"><?php echo $catresult['ICatName']; ?></td>
				<td class="orderdetaili" style=" border-top:none"><?php echo $itemsresult['IItemName']; ?></td>
				<td class="orderdetaili" style=" border-top:none"><?php echo $numberofitems; ?></td>
				<td class="orderdetaili" style=" border-top:none">$&nbsp;<?php echo $itemsresult['price']; ?></td>
				<td class="orderdetaili" style="border-left:none; border-top:none">$&nbsp;<?php echo $price; ?></td>
				</tr>
<?php 

$total=$total+$price;
	$counter++;
}
$zipqury = $db->query("SELECT * FROM  `tbl_zip` WHERE  '$postcode' BETWEEN  `Startzip` AND  `Endzip`");
$zipresult = $zipqury->fetch_array();
$zipprice = $zipresult['price'];
?>

<tr>
	
			 <td class="orderdetaili" style="border:none">&nbsp;</td>
			 <td class="orderdetaili" style="border:none">&nbsp;</td>
			 <td class="orderdetaili" style="border-left:none; border-top:none; border-bottom:none">&nbsp;</td>
			 <td class="orderdetaili" style=" border-top:none; border-left:none;  color:#fff; background:#444444;"><strong>Delivery Charges</strong></td>
			 <td class="orderdetaili" style=" border-top:none; border-left:none;  color:#fff; font-weight:bold; background:#444444;">$&nbsp;<?php echo number_format((float)$zipprice,2); ?></td>
			</tr>
            
            <tr>


			
			<tr style="">
	
			 <td class="orderdetaili" style="border:none">&nbsp;</td>
			 <td class="orderdetaili" style="border:none">&nbsp;</td>
			 <td class="orderdetaili" style="border-left:none; border-top:none; border-bottom:none">&nbsp;</td>
			 <td class="orderdetaili" style=" border-top:none; border-left:none;  color:#fff; background:#444444;"><strong>Grand Total</strong></td>
			 <td class="orderdetaili" style=" border-top:none; border-left:none;  color:#fff; font-weight:bold; background:#444444;">$&nbsp;<?php echo number_format((float)$total,2); ?></td>
			</tr>
            </table>
	   <?php }
 ?>
 <a href="#" onclick="window.print();return false;">Print</a>
 </div>
 
 <div class="clear">&nbsp;</div>
 
 <div id="bottom">
 
   <center>
    <p><strong>We buy any phone </strong> © 2011, All Rights Reserved</p>
   </center>
 
 </div>
   
</div>