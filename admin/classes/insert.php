<?php
class insert 
{
	var $id;
	var $username;
	var $password;
	var $admintypeid;
	function insert_admin_login($username,$password,$admintypeid)
	{
		$insert="insert into  tbl_adminlogin(sUserName,bLoginPassword,lAdminTypeID) values('$username','$password',$admintypeid)";
		$result=$db->query($insert);
		return $result;
	}
	
	function insert_admin_type($username,$password,$admintypeid)
	{
		$insert="insert into tbl_admintype(sAdminTypeName,sDescription,bStatus) values('$username','$password',$admintypeid)";
		$result=$db->query($insert);
		return $result;
	}
	
	
function insert_vendar_information($username,$Password,$Bname,$Cperson,$StreetAddress,$State,$City,$Zipcode,$Country,$WorkPhone,$Phone,$Mobile,$Fax,$PrefferedNo,
		$Email,$WebSite,$Status,$ImagePath,$VendorType,$date)
	{
 $insert="insert into tbl_resturantinfo(sUserName,bLoginPassword,sBusinessName,sContactPerson,sStreetAddress,sState,
sCity,sZipcode,
		sCountry,sWorkPhone,sPhone,sMobile,sFax,sPrefferedNo,
		sEmail,sWebSite,bStatus,sImagePath,lVendorType,dRegistrationDate) 
		
		values('$username','$Password','$Bname','$Cperson','$StreetAddress','$State','$City','$Zipcode','$Country','$WorkPhone','$Phone','$Mobile','$Fax','$PrefferedNo','$Email','$WebSite','1'
		,'$ImagePath',$VendorType,'$date')";
		$result=$db->query($insert);
		return $result;
		
	}
	
	function insert_logo($priority,$dateactive,$deactivedate,$status,$logoimage)
	{
		$insert="insert into tbl_logomanager(Lpriority,lActivedtm,ldeactivedtm,bstatus,limagepath) values('$priority','$dateactive','$deactivedate',$status,'$logoimage')";
		$result=$db->query($insert);
		return $result;
	}
	
	function insert_banner($priority,$dateactive,$deactivedate,$status,$logoimage)
	{
		 $insert="insert into tbl_bannerinfo(Priority,Activedtm,deactivedtm,bstatus,ImagePath) values('$priority','$dateactive','$deactivedate',$status,'$logoimage')";
		$result=$db->query($insert);
		return $result;
	}
	
}




?>