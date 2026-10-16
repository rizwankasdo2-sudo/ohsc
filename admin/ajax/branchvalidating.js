function branch(ser)
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
 
 	if (document.getElementById('shopname').value == "")
	{
		alert ("Please Enter the required fields.");
		window.location="add_branch.php";
	}
  
  else
	{
		var branchname = document.getElementById('shopname').value;
		var branchDscrptn = CKEDITOR.instances.branchdesc.getData();
		var branchStatus = document.getElementById('branchstatus').value;
		
		xmlhttp.open('get', 'insert_branch.php?bname='+branchname+'&bdesc='+branchDscrptn+'&bstatus='+branchStatus);
		xmlhttp.send(null);
	}
	return true;
}

//end of checklist

//end of checklist



function updatebranch(ser)
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
 
 	if (document.getElementById('shopname').value == "")
	{
		alert ("Please Enter the required fields.");
		window.location="add_branch.php";
	}
  
  else
	{
		var branchname = document.getElementById('shopname').value;
		var branchDscrptn = CKEDITOR.instances.branchdesc.getData();
		var branchStatus = document.getElementById('branchstatus').value;
		var bbid = document.getElementById('id').value;
		
 xmlhttp.open('get', 'Edit_Branches.php?ubname='+branchname+'&ubdesc='+branchDscrptn+'&ubstatus='+branchStatus+'&ubid='+bbid);
		xmlhttp.send(null);
	}
	return true;
}

//end of checklist