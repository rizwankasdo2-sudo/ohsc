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

<title>Customer List</title>

<link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />

<link rel="stylesheet" href="../Resources/admin_css/baner.css" />

<link rel="stylesheet" href="../Resources/admin_css/lightbox2.css" />

<script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>

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


<!--[if lte IE 6]>
	<script type="text/javascript" src="../Resources/admin_js/supersleight-min.js"></script>
<![endif]-->


<script type="text/javascript" language="javascript">
	
function ser(id)
{
	
if (id=="")
  {
  //document.getElementById("github").innerHTML="";
  
  return;
  } 
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
	try{

document.getElementById("github").innerHTML=xmlhttp.responseText;

} catch(ex){
alert(ex.message);
    
    }
  }
  }
 xmlhttp.open("GET","customer_detail.php?q="+id,true);
xmlhttp.send();
}
	
</script>
    
    

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
         
           <h3>Customer List</h3>
         
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
           
           <?php
			 
			          if (isset($_GET['msg']))
					  {
						  echo '<p style="color:black; font-size:28px; text-align:center; font-weight:bold">';
						  echo $_GET['msg'];
						  echo '</p>';
					  }
					  
		   ?>
           
           <div id="banerhead">
           
             <div id="banertop">
             
               <h4>Customers</h4>
             
             </div>
             
             <div id="banerdetail22">
             
               <div id="listofbanners">
             
               <table width="100%" border="0" cellspacing="0" cellpadding="0">
               
               
                 <tr id="top_row">
                   <th scope="col" class="bbtitle">Name</th>
                   <th scope="col" class="bbtitle">Address</th>
                   <th scope="col" class="lasttwo">Zip Code</th>
                   <th scope="col" class="lasttwo">Action</th>
                   <th scope="col" class="lasttwo" style="border-right:none;"></th>
                 </tr>
                 
                   <?php
			 
			            $bannerslist = $db->query("SELECT * FROM `tbl_customer` order by `ICustomerID` desc");
				        while($row= $bannerslist->fetch_array())
						{
							$id = $row['ICustomerID'];
				   
				   ?>
                 
                 <tr id="rowdownrepeat" style="height:40px;">
                   <td class="bbtitd"><?php echo $row['sFirstName'].$row['sLastName'];  ?></td>
                   <td class="bbtitd"><?php echo $row['sStreetAddress']; ?></td>
                   
                   <td class="last2">
                   
                    <?php echo $row['sPostalCode']; ?>
                   
                   </td>
                   
                   <td align="center" class="last2">

                    <a href="delete_customer.php?id=<?php echo $id; ?>" class="dl"></a>
                   
                   </td>
                   
                   <td align="center" class="last2">
                   
                        <a href="#github" id="example2" onclick="ser(<?php echo $id; ?>)">View Detail</a>
                   
                   </td>
                   
                 </tr>
                 
                   <?php } ?>
               
               </table>
               
               <div style="display:none">
 <div id="github" style="min-width:700px; height:auto">
   wait
 </div>
 </div>

             
             </div>
             
             </div>
             
             
           
           </div>
         
         </div>
              
       </div>
     
     </div>
	 
	 <?php include("includes/admin-footer.php"); ?>
	 
   </div>
  
  </div>
     
</body>
</html>