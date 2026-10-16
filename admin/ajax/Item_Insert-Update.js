
function createObject() {
var request_type;
var browser = navigator.appName;
if(browser == "Microsoft Internet Explorer")
{
request_type = new ActiveXObject("Microsoft.XMLHTTP");
}else
{
request_type = new XMLHttpRequest();
}
return request_type;
}

var http = createObject();


/* -------------------------- */
/* INSERT */
/* -------------------------- */
/* Required: var nocache is a random number to add to request. This value solve an Internet Explorer cache issue */
var nocache = 0;

function InsertItem() {
// Optional: Show a waiting message in the layer with ID login_response

document.getElementById('insert_response').innerHTML = "Just a second..."

// Required: verify that all fileds is not empty. Use encodeURI() to solve some issues about character encoding.
//var id= document.getElementById('id').value;

var proID = document.getElementById('pid').value;
var name = document.getElementById('iname').value;
var istatus = document.getElementById('istatus').value;
var change = "additem";

//var message = document.getElementById('message').value;
//alert(email);
// Set te random number to add to URL request

// Pass the login variables like URL variable
http.open('get', 'AddEditDel_Item.php?pid='+proID+'&name='+name+'&status='+istatus+'&switch='+change);
http.onreadystatechange = insertReply;
http.send(null);
}
function insertReply() {
if(http.readyState == 4){ 
var response = http.responseText;
// else if login is ok show a message: "Site added+ site URL".

document.getElementById('iname').value == "";
document.getElementById('filename').value == "";
document.getElementById('istatus').value == "";
	  
document.getElementById('insert_response').innerHTML = 'Item Added:'+response;
}
}