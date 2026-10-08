//This is validation file is use only for order form


function valForm()
{	
	    window.onload=clear;
  
  function clear ()
  {
	  document.banerupload.banername.value = "";
	  document.banerupload.banerpath.value = "";
	  document.banerupload.status.value = "";
  }
  
  //Call valid_required function to check if Customer Name field not empty
  
		if(document.banerupload.banername.value == "")
		{
			alert("Banner Name is Required.")
			return false;
		}
		
		if(document.banerupload.banerpath.value == "")
		{
			alert("Banner Image is Required.")
			return false;
		}
		
		if(document.banerupload.status.value == "2")
		{
			alert("Please Select the status of the Banner.")
			return false;
		}

		return true;
}


//Category form validation


function validateForm ()
{	
	    window.onload=clear;
  
  function clear ()
  {
	  document.addcategory.catname.value = "";
	  document.addcategory.catImage.value = "";
	  document.addcategory.catMetadesc.value = "";
	  document.addcategory.catMetakeywords.value = "";
	  document.addcategory.catdesc.value = "";
	  document.addcategory.parentcat.value = "";
	  document.addcategory.catstatus.value = "";
  }
  
  //Call valid_required function to check if Customer Name field not empty
  
		if(document.addcategory.catname.value == "")
		{
			alert("Category Name is Required.")
			return false;
		}
		
		if(document.addcategory.catImage.value == "")
		{
			alert("Category Image is Required.")
			return false;
		}
		
		if(document.addcategory.catstatus.value == "2")
		{
			alert("Please Select the status of the Category.")
			return false;
		}

		return true;
}

function itemSizeForm()
{	
	    window.onload=clear;
  
  function clear ()
  {
	  document.itemsize.isname.value = "";
	  document.itemsize.weight.value = "";
	  document.itemsize.width.value = "";
	  document.itemsize.height.value = "";
	  document.itemsize.quantity.value = "";
	  document.itemsize.price.value = "";
	  document.itemsize.sizestatus.value = "";
	  document.itemsize.optionss.value = "";
  }
  
  //Call valid_required function to check if Customer Name field not empty
  
		if(document.itemsize.quantity.value == "")
		{
			alert("Name is Required.")
			return false;
		}
		
		if(document.itemsize.quantity.value == "")
		{
			alert("Quantity is Required.")
			return false;
		}
		
		if(document.itemsize.price.value == "")
		{
			alert("Price is Required.")
			return false;
		}

		return true;
}









function checkForm()
{
	if(document.addaccessory.aname.value == "")
	{
		alert("Name is Required.")
		return false;
	}
	
	if(document.addaccessory.aimage.value == "")
	{
		alert("Image is Required.")
		return false;
	}
}
