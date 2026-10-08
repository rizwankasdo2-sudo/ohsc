
function gradient(id, level)
{
	var box = document.getElementById(id);
	box.style.opacity = level;
	box.style.MozOpacity = level;
	box.style.KhtmlOpacity = level;
	box.style.filter = "alpha(opacity=" + level * 100 + ")";
	box.style.display="block";
	return;
}


function fadein(id) 
{
	var level = 0;
	while(level <= 1)
	{
		setTimeout( "gradient('" + id + "'," + level + ")", (level* 1000) + 10);
		level += 0.01;
	}
}


// Open the lightbox


function openbox(formtitle, fadin)
{
	
  var box = document.getElementById('box'); 
  document.getElementById('shadowing').style.display='block';

  var btitle = document.getElementById('boxtitle');
  btitle.innerHTML = formtitle;
  
  if(fadin)
  {
	 gradient("box", 0);
	 fadein("box");
  }
  else
  { 	
    box.style.display='block';
  }  	
}


// Close the lightbox

function closebox()
{
   document.getElementById('box').style.display='none';
   document.getElementById('shadowing').style.display='none';
}



function openboxcompnay(formtitle, fadin)
{
	
  var box = document.getElementById('boxcompnay'); 
  document.getElementById('shadowingcompany').style.display='block';

  var btitle = document.getElementById('boxtitlecompany');
  btitle.innerHTML = formtitle;
  
  if(fadin)
  {
	 gradient("boxcompnay", 0);
	 fadein("boxcompnay");
  }
  else
  { 	
    box.style.display='block';
  }  	
}


// Close the lightbox

function closeboxcompany()
{
   document.getElementById('boxcompnay').style.display='none';
   document.getElementById('shadowingcompany').style.display='none';
}



function openboxnew(formtitle, fadin)
{
  var box = document.getElementById('box1'); 
  document.getElementById('shadowing1').style.display='block';

  var btitle = document.getElementById('boxtitle1');
  btitle.innerHTML = formtitle;
  
  if(fadin)
  {
	 gradient("box1", 0);
	 fadein("box1");
  }
  else
  { 	
    box.style.display='block';
  }  	
}
function closeboxnew()
{
   document.getElementById('box1').style.display='none';
   document.getElementById('shadowing1').style.display='none';
}

function openboxnewnew(formtitle, fadin)
{
	
  var box = document.getElementById('box2'); 
  document.getElementById('producthead').style.display='block';

  var btitle = document.getElementById('boxtitle2');
  btitle.innerHTML = formtitle;
  
  if(fadin)
  {
	 gradient("box2", 0);
	 fadein("box2");
  }
  else
  { 	
    box.style.display='block';
  }  	
}
function closeboxnewnew()
{
   document.getElementById('box2').style.display='none';
   document.getElementById('producthead').style.display='none';
}


function openboxupdate(formtitle, fadin, str)
{
	
  var box = document.getElementById('box5'); 
  document.getElementById('shadowing5').style.display='block';

  var btitle = document.getElementById('boxtitle5');
  btitle.innerHTML = formtitle;
  
  //var venderid=document.getElementById('venderid').value;
 
  if(fadin)
  {
	 gradient("box5", 0);
	 fadein("box5");
  }
  else
  { 	
    box.style.display='block';
  } 
  //for value geting
 /* if (str=="")
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

document.getElementById("txtHint").innerHTML=xmlhttp.responseText;

} catch(ex){
alert(ex.message);
    
    }
  }
  }
 xmlhttp.open("GET","addvender.php?q="+str,true);
xmlhttp.send();
*/
}

function closeboxupdate()
{
   document.getElementById('box5').style.display='none';
   document.getElementById('shadowing5').style.display='none';
   location.href='addvender.php';
}

function openboxcategory(formtitle, fadin)
{
	
  var box = document.getElementById('boxcategory'); 
  document.getElementById('shadowingcategory').style.display='block';

  var btitle = document.getElementById('boxtitlecategory');
  btitle.innerHTML = formtitle;
  
  if(fadin)
  {
	 gradient("boxcategory", 0);
	 fadein("boxcategory");
  }
  else
  { 	
    box.style.display='block';
  }  	
}
function closeboxcategory()
{
   document.getElementById('boxcategory').style.display='none';
   document.getElementById('shadowingcategory').style.display='none';
}

