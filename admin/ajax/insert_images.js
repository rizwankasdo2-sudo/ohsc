
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

function InsertImages() {
// Optional: Show a waiting message in the layer with ID login_response
document.getElementById('insert_response').innerHTML = "Just a second..."
// Required: verify that all fileds is not empty. Use encodeURI() to solve some issues about character encoding.
//var id= document.getElementById('id').value;

var iid = document.getElementById('bid').value;
var change = "addimage";



http.open('get', 'insertimages.php?bid='+iid+'&actions='+change);
http.onreadystatechange = insertReply;
http.send(null);
}

document.getElementById("bimage").value = "";
document.getElementById("limage").value = "";


function insertReply() {
if(http.readyState == 4){ 
var response = http.responseText;
// else if login is ok show a message: "Site added+ site URL".
document.getElementById('insert_response').innerHTML = http.responseText;
}
}

function deleteimage(image) {
// Optional: Show a waiting message in the layer with ID login_response

document.getElementById('insert_response').innerHTML = "Just a second..."

var imageid = image;
var change = "deleteimage";

//var message = document.getElementById('message').value;
//alert(email);
// Set te random number to add to URL request

// Pass the login variables like URL variable
http.open('get', 'insertimages.php?sdid='+imageid+'&actions='+change);
http.onreadystatechange = insertReply;
http.send(null);

}