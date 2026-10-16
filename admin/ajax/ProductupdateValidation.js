function update(ser)
{
	//var itemcd = document.getElementById('itemcd').value;
	//var casenew = document.getElementById('case').value;

	
	if (ser=="")
  {
  document.getElementById("txtHint").innerHTML="";
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
document.getElementById("txtHint").innerHTML=="";
document.getElementById("txtHint").innerHTML=xmlhttp.responseText;

} catch(ex){
alert(ex.message);
    
    }
  }
  }
 
 	if (document.getElementById('pname').value == "")
	{
		alert ("Please Enter the required fields.");
		window.location="add_product.php";
	}
  
  else
	{
		var pcat=document.getElementById('pcat').value;
		var productName = document.getElementById('pname').value;
		var productDscrptn = CKEDITOR.instances.pdesc.getData();
		var productMetaDesc = document.getElementById('ptag').value;
		var productMetaKey = document.getElementById('pkeywords').value;
		var productStatus = document.getElementById('pstatus').value;
		var id = document.getElementById('id').value;
		var change="updatepro";
		
 xmlhttp.open('get', 'Edit_Product.php?pname='+productName+'&pdesc='+productDscrptn+'&pmetadsc='+productMetaDesc+
		                                     '&pmetakey='+productMetaKey+'&pstatus='+productStatus+'&pcat='+pcat+'&switch='+change+'&uid='+id);
		xmlhttp.send(null);
	}
	return true;
}

//end of checklist









function updatespecial(ser)
{
	//var itemcd = document.getElementById('itemcd').value;
	//var casenew = document.getElementById('case').value;

	
	if (ser=="")
  {
  document.getElementById("txtHint").innerHTML="";
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
document.getElementById("txtHint").innerHTML=="";
document.getElementById("txtHint").innerHTML=xmlhttp.responseText;

} catch(ex){
alert(ex.message);
    
    }
  }
  }
 
 	if (document.getElementById('pname').value == "")
	{
		alert ("Please Enter the required fields.");
		window.location="add_special_offer.php";
	}
  
  else
	{
		var productName = document.getElementById('pname').value;
		var productDscrptn = CKEDITOR.instances.pdesc.getData();
		var productStatus = document.getElementById('pstatus').value;
		var id = document.getElementById('id').value;
		
		var change = "updatespecialoffer";
		
 xmlhttp.open('get', 'Edit_Special.php?pname='+productName+'&pdesc='+productDscrptn+'&pstatus='+productStatus+'&uid='+id+'&switch='+change);
		xmlhttp.send(null);
	}
	return true;
}

//end of checklist