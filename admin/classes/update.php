<?php
class update
{
	function update_vendar_information($id,$username,$Bname,$Cperson,$StreetAddress,$State,$City,$Zipcode,$Country,$WorkPhone,$Phone,$Mobile,$Fax,$PrefferedNo,$Email,$WebSite,$Status,$ImagePath,$VendorType,$date)
	{
 $update="update tbl_resturantinfo set sUserName='$username',sBusinessName='$Bname',sContactPerson='$Cperson',sStreetAddress='$StreetAddress',sState='$State',sCity='$City',sZipcode='$Zipcode',
		sCountry='$Country',sWorkPhone='$WorkPhone',sPhone='$Phone',sMobile='$Mobile',sFax='$Fax',sPrefferedNo='$PrefferedNo',
		sEmail='$Email',sWebSite='$WebSite',bStatus='1',sImagePath='$ImagePath',lVendorType=$VendorType,dRegistrationDate='$date' where lVendorID='$id'";
		
		/*values('$username','$Password','$Bname','$Cperson','$StreetAddress','$State','$City','$Zipcode','$Country','$WorkPhone','$Phone','$Mobile','$Fax','$PrefferedNo','$Email','$WebSite','1'
		,'$ImagePath',$VendorType,'$date')";*/
		$result=$db->query($update);
		return $result;
		
	}
}


?>
