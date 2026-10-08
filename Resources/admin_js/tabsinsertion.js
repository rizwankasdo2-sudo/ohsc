<!-- for insertion of code -->
function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		if (window.XMLHttpRequest)
  {
  // code for IE7+, Firefox, Chrome, Opera, Safari
  return xmlhttp=new XMLHttpRequest();
  }
if (window.ActiveXObject)
  {
  // code for IE6, IE5
  return xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
return null;
    }
	
    function Ajaxwork() {
		
var username = document.getElementById('username').value;
var Password= document.getElementById('Password').value;
var Bname = document.getElementById('Bname').value;
var Cperson = document.getElementById('Cperson').value;
var StreetAddress= document.getElementById('StreetAddress').value;
var StreetNo= document.getElementById('StreetNo').value;
var State = document.getElementById('State').value;
var City = document.getElementById('City').value;
var Zipcode= document.getElementById('Zipcode').value;
var Country = document.getElementById('Country').value;
var WorkPhone = document.getElementById('WorkPhone').value;
var Phone= document.getElementById('Phone').value;
var Mobile = document.getElementById('Mobile').value;
var Fax = document.getElementById('Fax').value;
var PrefferedNo= document.getElementById('PrefferedNo').value;
var TravelingCost= document.getElementById('TravelingCost').value;
var Email = document.getElementById('Email').value;
//var Website = document.getElementById('WebSite').value;
var VendorType= document.getElementById('VendorType').value;
var companyname = document.getElementById('companyname').value;

		var strURL='insert_vender.php?username=' +username+'&Password='+Password+'&Bname='+Bname+'&Cperson='+Cperson+'&StreetAddress='+StreetAddress+'&City='+City+'&Zipcode='+Zipcode+'&Country='+Country+'&WorkPhone='+WorkPhone+'&Phone='+Phone+'&Mobile='+Mobile+'&Fax='+Fax+'&PrefferedNo='+PrefferedNo+'&Email='+Email+'&TravelingCost='+TravelingCost+'&VendorType='+VendorType+'&State='+State+'&StreetNo='+StreetNo+'&companyname='+companyname;
		
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {

try
{
document.getElementById('country2').innerHTML=req.responseText;
return countries.cycleit('next');
}
catch(ex)
{
alert(ex.message);
}

											
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}
		 }
		 
		 function Ajaxwork1() {
			var nameofdays=document.getElementById('nameofdays').value;
			var starttime=document.getElementById('starttime').value;
			var endtime=document.getElementById('endtime').value;
			var season=document.getElementById('season').value;
			var fk=document.getElementById('fk').value;
			//alert(fk);
		var strURL="insert_resturant_time.php?nameofdays="+nameofdays+"&starttime="+starttime+"&endtime="+endtime+"&season="+season+"&fk="+fk;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {
	

try
{
document.getElementById('country3').innerHTML=req.responseText;
return countries.cycleit('next');

}
catch(ex)
{
alert(ex.message);
}

											
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}
		 }
		 
		 function Ajaxwork2() {
			var category=document.getElementById('category').value;
			var categoryname=document.getElementById('categoryname').value;
			var description=document.getElementById('description').value;
			var VendorType=document.getElementById('VendorType').value;
			var resturentidper=document.getElementById('resturentidper').value;
			//alert(fk);
		var strURL="insert_resturant_subcategory.php?category="+category+"&categoryname="+categoryname+"&description="+description+"&VendorType="+VendorType+"&resturentidper="+resturentidper;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {
	

try
{
document.getElementById('country4').innerHTML=req.responseText;
return countries.cycleit('next');

}
catch(ex)
{
alert(ex.message);
}

											
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}
		 }
		 function Ajaxwork3() {
			var category=document.getElementById('categorynew').value;
			var categoryname=document.getElementById('categoryname').value;
			var description=document.getElementById('description').value;
			var VendorType=document.getElementById('VendorType').value;
			var resturentidper=document.getElementById('resturentidper').value;
			//alert(fk);
		var strURL="insert_resturant_subcategory_info.php?category="+category+"&categoryname="+categoryname+"&description="+description+"&VendorType="+VendorType+"&resturentidper="+resturentidper;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {
	

try
{
document.getElementById('country5').innerHTML=req.responseText;
return countries.cycleit('next');

}
catch(ex)
{
alert(ex.message);
}

											
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}
		 }
		 function Ajaxwork4() {
			var Itemname=document.getElementById('Itemname').value;
			var Itemcode=document.getElementById('Itemcode').value;
			var itemdescription=document.getElementById('itemdescription').value;
			var Itemstatus=document.getElementById('Itemstatus').value;
			var itemresturentidper=document.getElementById('itemresturentidper').value;
			var subcatid=document.getElementById('subcatid').value;
			//alert(fk);
		var strURL="insert_resturant_subcategory_item.php?Itemname="+Itemname+"&Itemcode="+Itemcode+"&itemdescription="+itemdescription+"&Itemstatus="+Itemstatus+"&itemresturentidper="+itemresturentidper+"&subcatid="+subcatid;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {
	

try
{
document.getElementById('country6').innerHTML=req.responseText;
return countries.cycleit('next');

}
catch(ex)
{
alert(ex.message);
}

											
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}
		 }
		 function Ajaxwork5() {
			var sizename=document.getElementById('sizename').value;
			var itemsizedescription=document.getElementById('itemsizedescription').value;
			var sizestatus=document.getElementById('sizestatus').value;
			var price=document.getElementById('price').value;
			
			var itemresturentidper=document.getElementById('itemresturentidper').value;
			var ssubcatid=document.getElementById('ssubcatid').value;
			var subcatid=document.getElementById('subcatidnew').value;
			//alert(fk);
		var strURL="insert_resturant_subcategory_item_size.php?sizename="+sizename+"&itemsizedescription="+itemsizedescription+"&sizestatus="+sizestatus+"&price="+price+"&itemresturentidper="+itemresturentidper+"&ssubcatid="+ssubcatid+"&subcatid="+subcatid;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {
	

try
{
document.getElementById('country7').innerHTML=req.responseText;
return countries.cycleit('next');

}
catch(ex)
{
alert(ex.message);
}

											
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}
		 }
		 
		 function Ajaxwork6() {
			var Ingrediantname=document.getElementById('Ingrediantname').value;
			var Ingrediantprice=document.getElementById('Ingrediantprice').value;
			var Ingrediantdescription=document.getElementById('Ingrediantdescription').value;
			var Ingrediantstatus=document.getElementById('Ingrediantstatus').value;
			var itemresturentidper=document.getElementById('itemresturentidper').value;
			var Ingrediantsubcatid=document.getElementById('Ingrediantsubcatid').value;
			//alert(fk);
		var strURL="insert_resturant_subcategory_item_ingrediants.php?Ingrediantname="+Ingrediantname+"&Ingrediantprice="+Ingrediantprice+"&Ingrediantdescription="+Ingrediantdescription+"&Ingrediantstatus="+Ingrediantstatus+"&itemresturentidper="+itemresturentidper+"&Ingrediantsubcatid="+Ingrediantsubcatid;
		var req = getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {
	

try
{
/*document.getElementById('country3').innerHTML=req.responseText;
return countries.cycleit('next');*/
window.location='addvender.php';

}
catch(ex)
{
alert(ex.message);
}

											
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}
		 }
		 
<!-- end of insertion script-->


function ser()
{
var username = document.getElementById('usernamecom').value;
var Password= document.getElementById('Passwordcom').value;
var Bname = document.getElementById('Bnamecom').value;
var Cperson = document.getElementById('Cpersoncom').value;

var WorkPhone = document.getElementById('WorkPhonecom').value;

var Mobile = document.getElementById('Mobilecom').value;


var Email = document.getElementById('Emailcom').value;
var Website = document.getElementById('WebSitecom').value;
var VendorType= document.getElementById('VendorTypecom').value;

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
document.getElementById("txtHint").innerHTML="";
document.getElementById("txtHint").innerHTML=xmlhttp.responseText;

} catch(ex){
alert(ex.message);
    
    }
  }
  }
 xmlhttp.open("GET",'insert_resturent_company.php?username=' +username+'&Password='+Password+'&Bname='+Bname+'&Cperson='+Cperson+'&WorkPhone='+WorkPhone+'&Mobile='+Mobile+'&Email='+Email+'&Website='+Website+'&VendorType='+VendorType,true);
xmlhttp.send();
}



function sernew()
{
var categorynameper = document.getElementById('categorynameper').value;
var descriptionper= document.getElementById('descriptionper').value;
var statusper = document.getElementById('statusper').value;
var resturentid=document.getElementById('resturentid').value;

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
document.getElementById("txtHint1").innerHTML="";
document.getElementById("txtHint1").innerHTML=xmlhttp.responseText;

} catch(ex){
alert(ex.message);
    
    }
  }
  }
 xmlhttp.open("GET",'insert_resturent_category.php?categorynameper='+categorynameper+'&descriptionper='+descriptionper+'&statusper='+statusper+'&resturentid='+resturentid,true);
xmlhttp.send();
}

function imageupload()
{
	"ajaxUpload(this.form,'../ajaxupload.php?filename=ImagePath&amp;maxSize=9999999999&amp;maxW=200&amp;fullPath=..images/&amp;relPath=../images/&amp;colorR=255&amp;colorG=255&amp;colorB=255&amp;maxH=300','upload_area','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;"
}


