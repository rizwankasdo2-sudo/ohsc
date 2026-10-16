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

<title>Orders Detail</title>

<link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />

<link rel="stylesheet" href="../Resources/admin_css/add-product.css" />

<link rel="stylesheet" href="../Resources/admin_css/orders.css" />

<link rel="stylesheet" href="../Resources/admin_css/tabs.css" />

<link rel="stylesheet" href="../Resources/admin_css/lightbox2.css" />

<link rel="stylesheet" href="../Resources/admin_css/invoice_lightbox.css" />


<script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>

<script type="text/javascript" src="../Resources/admin_js/jquery-1.3.2.min.js"></script>

<script type="text/javascript" src="../Resources/admin_js/jquery-ui-1.js"></script>

<script>
		!window.jQuery && document.write('<script src="fancybox/jquery-1.4.3.min.js"><\/script>');
</script>
    
	<script type="text/javascript" src="fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="fancybox/jquery.fancybox-1.3.4.css" media="screen" />
    
    
    <script language="javascript">
	$(document).ready(function() {
		$("a#example2").fancybox({
				'overlayShow'	: false,
				'transitionIn'	: 'elastic',
				'transitionOut'	: 'elastic'
			});
			
			});
</script>

<script type="text/javascript" src="ajax/invoices.js"></script>

<script type="text/javascript">
   //Start for Admin type window

function togle(div_id) {
	var el = document.getElementById(div_id);
	if ( el.style.display == 'none' ) {	el.style.display = 'block';}
	else {el.style.display = 'none';}
}
function blanket2_size(popUpDivVar) {
	if (typeof window.innerWidth != 'undefined') {
		viewportheight = window.innerHeight;
	} else {
		viewportheight = document.documentElement.clientHeight;
	}
	if ((viewportheight > document.body.parentNode.scrollHeight) && (viewportheight > document.body.parentNode.clientHeight)) {
		blanket_height = viewportheight;
	} else {
		if (document.body.parentNode.clientHeight > document.body.parentNode.scrollHeight) {
			blanket_height = document.body.parentNode.clientHeight;
		} else {
			blanket_height = document.body.parentNode.scrollHeight;
		}
	}
	var blanket = document.getElementById('blanket2');
	blanket.style.height = 1000 + 'px';
	var popUpDiv = document.getElementById(popUpDivVar);
	popUpDiv_height=blanket_height/2-150;
	popUpDiv.style.top = 270 + 'px';
}
function window_pos2(popUpDivVar) {
	if (typeof window.innerWidth != 'undefined') {
		viewportwidth = window.innerHeight;
	} else {
		viewportwidth = document.documentElement.clientHeight;
	}
	if ((viewportwidth > document.body.parentNode.scrollWidth) && (viewportwidth > document.body.parentNode.clientWidth)) {
		window_width = viewportwidth;
	} else {
		if (document.body.parentNode.clientWidth > document.body.parentNode.scrollWidth) {
			window_width = document.body.parentNode.clientWidth;
		} else {
			window_width = document.body.parentNode.scrollWidth;
		}
	}
	var popUpDiv = document.getElementById(popUpDivVar);
	window_width=window_width/2-100;
	popUpDiv.style.left = 425 + 'px';
}
function pop2(windowname) {
	blanket2_size(windowname);
	window_pos2(windowname);
	togle('blanket2');
	togle(windowname);		
}
//end of admin type popup window
</script>


<script type="text/javascript">
   //Start for Admin type window

function togle(div_id) {
	var el = document.getElementById(div_id);
	if ( el.style.display == 'none' ) {	el.style.display = 'block';}
	else {el.style.display = 'none';}
}
function blanket_size123(popUpDivVar) {
	if (typeof window.innerWidth != 'undefined') {
		viewportheight = window.innerHeight;
	} else {
		viewportheight = document.documentElement.clientHeight;
	}
	if ((viewportheight > document.body.parentNode.scrollHeight) && (viewportheight > document.body.parentNode.clientHeight)) {
		blanket_height = viewportheight;
	} else {
		if (document.body.parentNode.clientHeight > document.body.parentNode.scrollHeight) {
			blanket_height = document.body.parentNode.clientHeight;
		} else {
			blanket_height = document.body.parentNode.scrollHeight;
		}
	}
	var blanket = document.getElementById('blanket123');
	blanket.style.height = 1000 + 'px';
	var popUpDiv = document.getElementById(popUpDivVar);
	popUpDiv_height=blanket_height/2-150;
	popUpDiv.style.top = 270 + 'px';
}
function window_pos123(popUpDivVar) {
	if (typeof window.innerWidth != 'undefined') {
		viewportwidth = window.innerHeight;
	} else {
		viewportwidth = document.documentElement.clientHeight;
	}
	if ((viewportwidth > document.body.parentNode.scrollWidth) && (viewportwidth > document.body.parentNode.clientWidth)) {
		window_width = viewportwidth;
	} else {
		if (document.body.parentNode.clientWidth > document.body.parentNode.scrollWidth) {
			window_width = document.body.parentNode.clientWidth;
		} else {
			window_width = document.body.parentNode.scrollWidth;
		}
	}
	var popUpDiv = document.getElementById(popUpDivVar);
	window_width=window_width/2-100;
	popUpDiv.style.left = 425 + 'px';
}
function pop123(windowname) {
	blanket_size123(windowname);
	window_pos123(windowname);
	togle('blanket123');
	togle(windowname);		
}
//end of admin type popup window
</script>


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
         
         <div id="tit">
         
           <h3>Orders</h3>
         
         </div>
       
       </div>
       
       <div id="right_corner">
       </div>
       
       <div id="main_content">
       
         <div id="addproducts">
         
           <div id="uper">
           
             <div id="upertop">
             
               <h4>Orders Detail</h4>
             
             </div>
             
             <div id="uperdetail">
             
               
               <script type="text/javascript">
			   
			     $(function() {
					 $("#tabs").tabs({ fx: { height: 'toggle', opacity: 'toggle' } });
					 /*$("#proMember").hide();*/
					 });
               
               </script>
               
               <div id="tabs">
               
                 <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" id="tabsTop" style="border-bottom:solid 1px #CECECE">
                 
                   <li style="list-style-image:none; margin-right:12px; margin-left:5px;" class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"> <a href="#tabs-1" style="text-decoration:none; color:#333">Pending</a></li>
                   
                   <li style="list-style-image:none; margin-right:12px;" class="ui-state-default ui-corner-top"><a href="#tabs-2" style="text-decoration:none; color:#333">Delivered</a></li>
                   
                   <li style="list-style-image:none; margin-right:12px;" class="ui-state-default ui-corner-top"><a href="#tabs-3" style="text-decoration:none; color:#333">Canceled</a></li>
                 
                 </ul>
                 
                 <div id="tabs-1" class="tabsContent ui-tabs-panel ui-widget-content ui-corner-bottom">
                 
                   <div class="clear">&nbsp;</div>
                   
                   <?php
				          $orderquery = $db->query("SELECT * FROM tbl_tmporderinfo WHERE oStaus=0");
						  $ordernorows = $orderquery->num_rows;
						  
						  if ($ordernorows > 0)
						  {
				   ?>
                   
                   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                   
                     <tr class="heading">
                      <th scope="col" class="headingborder" width="5%">Sr #</th>
                      <th scope="col" class="headingborder" width="9%">First Name</th>
                      <th scope="col" class="headingborder" width="11%">Last Name</th>
                      <th scope="col" class="headingborder" width="11%">Phone</th>
                      <th scope="col" class="headingborder" width="15%">Email</th>
                      <th scope="col" class="headingborder" width="12%">Postal Code</th>
                      <th scope="col" class="headingborder" width="8%">Status</th>
                      <th scope="col" class="headingborder" width="12%">Customer Detail</th>
                      <th scope="col" class="headingborder" width="10%">Order Detail</th>
                      <th scope="col" class="headingborder_last" width="7%">Actions</th>
                     </tr>
                     
                     <?php
					         $ordercounter = 1;
							 
					         while ($orderrequests = $orderquery->fetch_array())
							 {
								 $cod = $orderrequests['ICustoamrID'];
								 $infoid = $orderrequests['ITemporderinfoID'];
								 $orderbycustomer = $db->query("select * from tbl_customer where ICustomerID=$cod");
								 $orderbyccustname = $orderbycustomer->fetch_array();
								 
								 $odr = $orderrequests['ICustoamrID'];
								
								
								 
								 $orderdate = new DateTime($orderrequests['sOrderDate']);
								 $correctDate = $orderdate->format('F j, Y');
								 
								 
								 if ($ordercounter % 2 == 0)
								 {
									 echo '<tr class="rowdescriptioncolor">';
								 }
								 else
								 {
									 echo '<tr class="rowdescription">';
								 }
									  echo '<td class="bord">'.$ordercounter.'</td>';
									  echo '<td class="bord">'.$orderbyccustname['sFirstName'].'</td>';
									  echo '<td class="bord">'.$orderbyccustname['sLastName'].'</td>';
									  echo '<td class="bord">'.$orderbyccustname['sPhoneNo'].'</td>';
									  echo '<td class="bord">'.$orderbyccustname['sEmail'].'</td>';
									  echo '<td class="bord">'.$orderbyccustname['sPostalCode'].'</td>';
									  
									   if ($orderrequests['oStaus'] == 0)
									   {
										   echo '<td class="bord">';
					 ?>
                                           <a href="#" onclick="pop2('pDiv')">Pending</a>
                                           
                                           <div id="blanket2" style="display:none;">
                                           </div>
                                           
                                           <div id="pDiv" class="round" style="display:none; overflow:hidden; width:400px;">
                                            
                                             <div style="border-bottom:1px solid #e9e5e5; color:#323232; font-family:Arial, Helvetica, sans-serif; font-size:24px; padding-top:14px; padding-bottom:5px; width:350px">Change Order Status</div>
                                             
                                             <div style="float:right; margin-top:-45px;">
                                               <a href="#" onclick="pop2('pDiv')" style="text-decoration:none;">
                                               <img src="../Resources/admin_images/close_button.jpg" width="31" height="31" />
                                               </a>
                                             </div>
                                             
                                             <form action="updateorderstatus.php" method="post" name="cahngeorderstatus">
                                             
                                              <input type="hidden" value="<?php echo $infoid; ?>" name="ouniqueid" id="ouniqueid" />
                                              
                                              <div class="fields" style="margin-top:25px; margin-left:25px;">
                                              
                                                <div class="label">
                                                 Select Status :
                                                </div>
                                                <div class="inputfield">
                                                 <select name="ostatus" id="ostatus" style="width:180px; border:solid 1px #666">
                                                   <option value="0" selected="selected">Pending</option>
                                                   <option value="1">Delivered</option>
                                                   <option value="2">Canceled</option>
                                                 </select>
                                                </div>
                                               
                                              </div>
                                              
                                              <input type="button" onclick="pop2('pDiv')" value="Cancel" style="margin-right:10px; background:url(../Resources/admin_images/buttonGreyLarge.jpg) no-repeat; width:148px; height:40px; border:0px; outline:0px; font-family:Arial; font-size:18px; text-shadow: 1px 1px 1px #333; color:#FFF; margin-left:5px; margin-top:20px" />
                                              
                                              <input type="submit" value="Update" style="margin-right:10px; background:url(../Resources/admin_images/buttonGreyLarge.jpg) no-repeat; width:148px; height:40px; border:0px; outline:0px; font-family:Arial; font-size:18px; text-shadow: 1px 1px 1px #333; color:#FFF; margin-top:20px" />
                                             
                                             </form>
                                             
                                             <div class="clear">&nbsp;</div>
                                           
                                           </div>
                     <?php
                                           echo '</td>';
									   }
									  
									  echo '<td class="bord"><a href="#cinvic" id="example2" onclick="csdtl('.$cod.')">View</a></td>';
									  
                                      echo '<td class="bord"><a href="#cinvic" id="example2" onclick="orddtl('.$odr.')">View</a></td>';
									  echo '<td class="imagess"><a href="deleteorder.php?doid='.$infoid.'" class="cross" title="Cancel Order"></a></td>';
									 echo '</tr>';
								 
								 $ordercounter++;
							 }
					 ?>
                    
                   </table>
                   
                   <?php
						  }
						  else
						  {
							  echo "<h3>Currently there are no Pending Orders.</h3>";
						  }
				   ?>

                 </div>	
                 
                 
                 <div id="tabs-2" class="tabsContent ui-tabs-panel ui-widget-content ui-corner-bottom">
                 
                   <div class="clear">&nbsp;</div>
                   
                  
                   <?php
				          $orderquery = $db->query("SELECT * FROM tbl_tmporderinfo WHERE oStaus=1");
						  $ordernorows = $orderquery->num_rows;
						  
						  if ($ordernorows > 0)
						  {
				   ?>
                   
                   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                   
                     <tr class="heading">
                      <th scope="col" class="headingborder" width="5%">Sr #</th>
                      <th scope="col" class="headingborder" width="9%">First Name</th>
                      <th scope="col" class="headingborder" width="11%">Last Name</th>
                      <th scope="col" class="headingborder" width="11%">Phone</th>
                      <th scope="col" class="headingborder" width="15%">Email</th>
                      <th scope="col" class="headingborder" width="12%">Postal Code</th>
                      <th scope="col" class="headingborder" width="8%">Status</th>
                      <th scope="col" class="headingborder" width="12%">Customer Detail</th>
                      <th scope="col" class="headingborder" width="10%">Order Detail</th>
                      <th scope="col" class="headingborder_last" width="7%">Actions</th>
                     </tr>
                     
                     <?php
					         $ordercounter = 1;
							 
					         while ($orderrequests = $orderquery->fetch_array())
							 {
								 $cod = $orderrequests['ICustoamrID'];
								 $infoid = $orderrequests['ITemporderinfoID'];
								 $orderbycustomer = $db->query("select * from tbl_customer where ICustomerID=$cod");
								 $orderbyccustname = $orderbycustomer->fetch_array();
								 
								 $odr = $orderrequests['ICustoamrID'];
								
								
								 
								 $orderdate = new DateTime($orderrequests['sOrderDate']);
								 $correctDate = $orderdate->format('F j, Y');
								 
								 
								 if ($ordercounter % 2 == 0)
								 {
									 echo '<tr class="rowdescriptioncolor">';
								 }
								 else
								 {
									 echo '<tr class="rowdescription">';
								 }
									  echo '<td class="bord">'.$ordercounter.'</td>';
									  echo '<td class="bord">'.$orderbyccustname['sFirstName'].'</td>';
									  echo '<td class="bord">'.$orderbyccustname['sLastName'].'</td>';
									  echo '<td class="bord">'.$orderbyccustname['sPhoneNo'].'</td>';
									  echo '<td class="bord">'.$orderbyccustname['sEmail'].'</td>';
									  echo '<td class="bord">'.$orderbyccustname['sPostalCode'].'</td>';
									  
									   if ($orderrequests['oStaus'] == 1)
									   {
										   echo '<td class="bord">';
					 ?>
                                           <a href="#" onclick="pop2('pDiv')">Pending</a>
                                           
                                           <div id="blanket2" style="display:none;">
                                           </div>
                                           
                                           <div id="pDiv" class="round" style="display:none; overflow:hidden; width:400px;">
                                            
                                             <div style="border-bottom:1px solid #e9e5e5; color:#323232; font-family:Arial, Helvetica, sans-serif; font-size:24px; padding-top:14px; padding-bottom:5px; width:350px">Change Order Status</div>
                                             
                                             <div style="float:right; margin-top:-45px;">
                                               <a href="#" onclick="pop2('pDiv')" style="text-decoration:none;">
                                               <img src="../Resources/admin_images/close_button.jpg" width="31" height="31" />
                                               </a>
                                             </div>
                                             
                                             <form action="updateorderstatus.php" method="post" name="cahngeorderstatus">
                                             
                                              <input type="hidden" value="<?php echo $infoid; ?>" name="ouniqueid" id="ouniqueid" />
                                              
                                              <div class="fields" style="margin-top:25px; margin-left:25px;">
                                              
                                                <div class="label">
                                                 Select Status :
                                                </div>
                                                <div class="inputfield">
                                                 <select name="ostatus" id="ostatus" style="width:180px; border:solid 1px #666">
                                                   <option value="0" selected="selected">Pending</option>
                                                   <option value="1">Delivered</option>
                                                   <option value="2">Canceled</option>
                                                 </select>
                                                </div>
                                               
                                              </div>
                                              
                                              <input type="button" onclick="pop2('pDiv')" value="Cancel" style="margin-right:10px; background:url(../Resources/admin_images/buttonGreyLarge.jpg) no-repeat; width:148px; height:40px; border:0px; outline:0px; font-family:Arial; font-size:18px; text-shadow: 1px 1px 1px #333; color:#FFF; margin-left:5px; margin-top:20px" />
                                              
                                              <input type="submit" value="Update" style="margin-right:10px; background:url(../Resources/admin_images/buttonGreyLarge.jpg) no-repeat; width:148px; height:40px; border:0px; outline:0px; font-family:Arial; font-size:18px; text-shadow: 1px 1px 1px #333; color:#FFF; margin-top:20px" />
                                             
                                             </form>
                                             
                                             <div class="clear">&nbsp;</div>
                                           
                                           </div>
                     <?php
                                           echo '</td>';
									   }
									  
									  echo '<td class="bord"><a href="#cinvic" id="example2" onclick="csdtl('.$cod.')">View</a></td>';
									  
                                      echo '<td class="bord"><a href="#cinvic" id="example2" onclick="orddtl('.$odr.')">View</a></td>';
									  echo '<td class="imagess"><a href="deleteorder.php?doid='.$infoid.'" class="cross" title="Cancel Order"></a></td>';
									 echo '</tr>';
								 
								 $ordercounter++;
							 }
					 ?>
                    
                   </table>
                   
                   <?php
						  }
						  else
						  {
							  echo "<h3>Currently there are no Pending Orders.</h3>";
						  }
				   ?>

                 </div>
                 
                 
                 <div id="tabs-3" class="tabsContent ui-tabs-panel ui-widget-content ui-corner-bottom">
                 
                   <div class="clear">&nbsp;</div>
                  
                  <?php
				          $orderquery = $db->query("SELECT * FROM tbl_tmporderinfo WHERE oStaus=2");
						  $ordernorows = $orderquery->num_rows;
						  
						  if ($ordernorows > 0)
						  {
				   ?>
                   
                   <table width="100%" border="0" cellspacing="0" cellpadding="0">
                   
                     <tr class="heading">
                      <th scope="col" class="headingborder" width="5%">Sr #</th>
                      <th scope="col" class="headingborder" width="9%">First Name</th>
                      <th scope="col" class="headingborder" width="11%">Last Name</th>
                      <th scope="col" class="headingborder" width="11%">Phone</th>
                      <th scope="col" class="headingborder" width="15%">Email</th>
                      <th scope="col" class="headingborder" width="12%">Postal Code</th>
                      <th scope="col" class="headingborder" width="8%">Status</th>
                      <th scope="col" class="headingborder" width="12%">Customer Detail</th>
                      <th scope="col" class="headingborder" width="10%">Order Detail</th>
                      <th scope="col" class="headingborder_last" width="7%">Actions</th>
                     </tr>
                     
                     <?php
					         $ordercounter = 1;
							 
					         while ($orderrequests = $orderquery->fetch_array())
							 {
								 $cod = $orderrequests['ICustoamrID'];
								 $infoid = $orderrequests['ITemporderinfoID'];
								 $orderbycustomer = $db->query("select * from tbl_customer where ICustomerID=$cod");
								 $orderbyccustname = $orderbycustomer->fetch_array();
								 
								 $odr = $orderrequests['ICustoamrID'];
								
								
								 
								 $orderdate = new DateTime($orderrequests['sOrderDate']);
								 $correctDate = $orderdate->format('F j, Y');
								 
								 
								 if ($ordercounter % 2 == 0)
								 {
									 echo '<tr class="rowdescriptioncolor">';
								 }
								 else
								 {
									 echo '<tr class="rowdescription">';
								 }
									  echo '<td class="bord">'.$ordercounter.'</td>';
									  echo '<td class="bord">'.$orderbyccustname['sFirstName'].'</td>';
									  echo '<td class="bord">'.$orderbyccustname['sLastName'].'</td>';
									  echo '<td class="bord">'.$orderbyccustname['sPhoneNo'].'</td>';
									  echo '<td class="bord">'.$orderbyccustname['sEmail'].'</td>';
									  echo '<td class="bord">'.$orderbyccustname['sPostalCode'].'</td>';
									  
									   if ($orderrequests['oStaus'] == 2)
									   {
										   echo '<td class="bord">';
					 ?>
                                           <a href="#" onclick="pop2('pDiv')">Pending</a>
                                           
                                           <div id="blanket2" style="display:none;">
                                           </div>
                                           
                                           <div id="pDiv" class="round" style="display:none; overflow:hidden; width:400px;">
                                            
                                             <div style="border-bottom:1px solid #e9e5e5; color:#323232; font-family:Arial, Helvetica, sans-serif; font-size:24px; padding-top:14px; padding-bottom:5px; width:350px">Change Order Status</div>
                                             
                                             <div style="float:right; margin-top:-45px;">
                                               <a href="#" onclick="pop2('pDiv')" style="text-decoration:none;">
                                               <img src="../Resources/admin_images/close_button.jpg" width="31" height="31" />
                                               </a>
                                             </div>
                                             
                                             <form action="updateorderstatus.php" method="post" name="cahngeorderstatus">
                                             
                                              <input type="hidden" value="<?php echo $infoid; ?>" name="ouniqueid" id="ouniqueid" />
                                              
                                              <div class="fields" style="margin-top:25px; margin-left:25px;">
                                              
                                                <div class="label">
                                                 Select Status :
                                                </div>
                                                <div class="inputfield">
                                                 <select name="ostatus" id="ostatus" style="width:180px; border:solid 1px #666">
                                                   <option value="0" selected="selected">Pending</option>
                                                   <option value="1">Delivered</option>
                                                   <option value="2">Canceled</option>
                                                 </select>
                                                </div>
                                               
                                              </div>
                                              
                                              <input type="button" onclick="pop2('pDiv')" value="Cancel" style="margin-right:10px; background:url(../Resources/admin_images/buttonGreyLarge.jpg) no-repeat; width:148px; height:40px; border:0px; outline:0px; font-family:Arial; font-size:18px; text-shadow: 1px 1px 1px #333; color:#FFF; margin-left:5px; margin-top:20px" />
                                              
                                              <input type="submit" value="Update" style="margin-right:10px; background:url(../Resources/admin_images/buttonGreyLarge.jpg) no-repeat; width:148px; height:40px; border:0px; outline:0px; font-family:Arial; font-size:18px; text-shadow: 1px 1px 1px #333; color:#FFF; margin-top:20px" />
                                             
                                             </form>
                                             
                                             <div class="clear">&nbsp;</div>
                                           
                                           </div>
                     <?php
                                           echo '</td>';
									   }
									  
									  echo '<td class="bord"><a href="#cinvic" id="example2" onclick="csdtl('.$cod.')">View</a></td>';
									  
                                      echo '<td class="bord"><a href="#cinvic" id="example2" onclick="orddtl('.$odr.')">View</a></td>';
									  echo '<td class="imagess"><a href="deleteorder.php?doid='.$infoid.'" class="cross" title="Cancel Order"></a></td>';
									 echo '</tr>';
								 
								 $ordercounter++;
							 }
					 ?>
                    
                   </table>
                   
                   <?php
						  }
						  else
						  {
							  echo "<h3>Currently there are no Pending Orders.</h3>";
						  }
				   ?>
                 </div>               
               
               </div>
             
             </div>
             
                                
                   <div style="display:none">
                    <div id="cinvic" style="min-width:700px; height:auto">
                     wait
                    </div>
                   </div>
           
           </div>
         
         </div>
         
         <div class="clear">&nbsp;</div>
         
       </div>

     </div>
     
     <div class="clear">&nbsp;</div>
     
     <?php include("includes/admin-footer.php"); ?>
     
   </div>
   
  </div>

</body>
</html>
