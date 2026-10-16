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




function SpecialItemSizes() {
// Optional: Show a waiting message in the layer with ID login_response
document.getElementById('insert_response').innerHTML = "Just a second..."
// Required: verify that all fileds is not empty. Use encodeURI() to solve some issues about character encoding.
//var id= document.getElementById('id').value;

var iid = document.getElementById('iid').value;

var name = document.getElementById('isname').value;
var weight = document.getElementById('weight').value;
var width = document.getElementById('width').value;
var height = document.getElementById('height').value;
var waist = document.getElementById('Waist').value;
var sleeves = document.getElementById('Sleeves').value;
var quantity = document.getElementById('quantity').value;
var price = document.getElementById('price').value;
var dprice = document.getElementById('dprice').value;
var status = document.getElementById('sizestatus').value;


var colorchkboxes1 = document.getElementById('options1').checked;
if(colorchkboxes1==true)
{
	colorchkboxes1='black';
}
else
{
	colorchkboxes1='';
}
var colorchkboxes2 = document.getElementById('options2').checked;

if(colorchkboxes2==true)
{
	colorchkboxes2='white';
}
else
{
	colorchkboxes2='';
}
var colorchkboxes3 = document.getElementById('options3').checked;
if(colorchkboxes3==true)
{
	colorchkboxes3='gray';
}
else
{
	colorchkboxes3='';
}

var colorchkboxes4 = document.getElementById('options4').checked;

if(colorchkboxes4==true)
{
	colorchkboxes4='off white';
}
else
{
	colorchkboxes4='';
}
var colorchkboxes5 = document.getElementById('options5').checked;
if(colorchkboxes5==true)
{
	colorchkboxes5='Red';
}
else
{
	colorchkboxes5='';
}


var action = "additemsizespecial";

//var message = document.getElementById('message').value;
//alert(email);
// Set te random number to add to URL request

// Pass the login variables like URL variable
http.open('get', 'special_itemsizeinsert.php?iid='+iid+'&name='+name+'&weight='+weight+'&width='+width+'&height='+height+'&waist='+waist+'&sleeves='+sleeves+'&quantity='+quantity+'&price='+price+'&dprice='+dprice+'&status='+status+'&colorchkboxes1='+colorchkboxes1+'&colorchkboxes2='+colorchkboxes2+'&colorchkboxes3='+colorchkboxes3+'&colorchkboxes4='+colorchkboxes4+'&colorchkboxes5='+colorchkboxes5+'&action='+action);
http.onreadystatechange = insertReply;
http.send(null);
}

function insertReply() {
if(http.readyState == 4){ 
var response = http.responseText;
// else if login is ok show a message: "Site added+ site URL".
document.getElementById('insert_response').innerHTML = http.responseText;
}
}







function delsize_special(s) {
// Optional: Show a waiting message in the layer with ID login_response

document.getElementById('insert_response').innerHTML = "Just a second..."

var did = s;
var action = "specialOfferdeletesize";

//var message = document.getElementById('message').value;
//alert(email);
// Set te random number to add to URL request

// Pass the login variables like URL variable
http.open('get', 'special_itemsizeinsert.php?sid='+did+'&action='+action);
http.onreadystatechange = insertReply;
http.send(null);

}