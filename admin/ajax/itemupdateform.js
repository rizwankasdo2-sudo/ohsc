function itemupdate(itemid)
{
	//var itemcd = document.getElementById('itemcd').value;
	//var casenew = document.getElementById('case').value;
	if (itemid=="")
  {
  document.getElementById("github").innerHTML="";
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
document.getElementById("github").innerHTML=="";
document.getElementById("github").innerHTML=xmlhttp.responseText;

} catch(ex){
alert(ex.message);
    
    }
  }
  }
 
  
 xmlhttp.open("GET","includes/EditItemForm.php?itemid="+itemid,true);
xmlhttp.send();
}

function itemsize(itemid)
{
	alert(itemid);
	
	//var itemcd = document.getElementById('itemcd').value;
	//var casenew = document.getElementById('case').value;
	
	if (itemid=="")
  {
  document.getElementById("git").innerHTML="";
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
document.getElementById("git").innerHTML=="";
document.getElementById("git").innerHTML=xmlhttp.responseText;

} catch(ex){
alert(ex.message);
    
    }
  }
  }
 
  
 xmlhttp.open("GET","includes/AddItemSizeForm.php?itemid="+itemid,true);
xmlhttp.send();
}

function UpdateItem(id) {
// Optional: Show a waiting message in the layer with ID login_response

document.getElementById('insert_response').innerHTML = "Just a second..."

// Required: verify that all fileds is not empty. Use encodeURI() to solve some issues about character encoding.
//var id= document.getElementById('id').value;

var name = document.getElementById('iuname').value;
var istatus = document.getElementById('iustatus').value;
var change = "updateitem";

//var message = document.getElementById('message').value;
//alert(email);
// Set te random number to add to URL request
// Pass the login variables like URL variable
xmlhttp.open('get', 'AddEditDel_Item.php?id='+id+'&name='+name+'&status='+istatus+'&switch='+change,true);
xmlhttp.send();
}
function insertReply() {
if(http.readyState == 4){ 
var response = http.responseText;
// else if login is ok show a message: "Site added+ site URL".

document.getElementById('iuname').value == "";
document.getElementById('filename').value == "";
document.getElementById('iustatus').value == "";
	  
document.getElementById('insert_response').innerHTML = 'Item Added:'+response;
}
}






