<div id="popUpDiv" class="round" style="display:none;">
   
     <div style="border-bottom:1px solid #e9e5e5; color:#323232; font-family:Arial, Helvetica, sans-serif; font-size:24px;
                 margin-left:20px; padding-top:14px; padding-bottom:5px; width:860px">
           Insert New Item
     </div>
     
     <div style="float:right; margin-top:-45px;">
     
       <a href="#" onclick="popup('popUpDiv')" style="text-decoration:none;">
         <img src="../Resources/admin_images/close_button.jpg" width="31" height="31" style="border:none" />
       </a>
       
     </div>
     
     <div id="insert_response"></div>
     
     <form action="#" method="get" enctype="multipart/form-data" name="item" id="item">
     
       <input type="hidden" value="<?php echo $id; ?>" name="pid" id="pid" />
       
       <div class="fields" style="margin-top:40px;">
        
        <div class="label">
          Item Name :
        </div>
        
        <div class="inputfield">
          <input name="iname" type="text" id="iname" class="txtbox" value="" />
        </div>
      
       </div>
       
       <div class="fields">
       
        <div class="label">
          Thumbnail Image : 
        </div>
        
        <div class="inputfield">
          <input type="file" name="filename" id="filename" class="txtbox" onchange="ajaxUpload(this.form,'ajaxupload.php?filename=filename&amp;maxSize=9999999999&amp;maxW=1000&amp;fullPath=http://www.abc.com/images/&amp;relPath=../Resources/item_thumbnail_images/&amp;colorR=255&amp;colorG=255&amp;colorB=255&amp;maxH=300','upload_area','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'../Resources/admin_images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'../Resources/admin_images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;" />
        </div>
        
         <div id="upload_area" style="width:15%; float:left;">
         </div>
         
       </div>
       
       <div class="fields" style="clear:both">
       
         <div class="label">
           Large Image : 
         </div>
        
         <div class="inputfield">
           <input type="file" name="limage" id="limage" class="txtbox" onchange="ajaxUpload2(this.form,'ajaxupload_large.php?filename=limage&amp;maxSize=9999999999&amp;maxW=1000&amp;fullPath=http://www.abc.com/images/&amp;relPath=../Resources/item_large_images/&amp;colorR=255&amp;colorG=255&amp;colorB=255&amp;maxH=300','upload_area2','File Uploading Please Wait...&lt;br /&gt;&lt;img src=\'../Resources/admin_images/loader_light_blue.gif\' width=\'128\' height=\'15\' border=\'0\' /&gt;','&lt;img src=\'../Resources/admin_images/error.gif\' width=\'16\' height=\'16\' border=\'0\' /&gt; Error in Upload, check settings and path info in source code.'); return false;" />
         </div>
        
         <div id="upload_area2" style="width:15%; float:left;">
         </div>
        
       </div>
       
       <div class="fields">
       
        <div class="label">
          Status : 
        </div>
        
        <div class="inputfield">
          
          <select name="istatus" id="istatus" class="txtbox" style="width:140px;">
		    <option value="1">Enable</option>
			<option value="0">Disable</option>
          </select>
          
        </div>
       
       </div>
       
       <div class="clear">&nbsp;</div>
       
       <div style="border-bottom:1px solid #e9e5e5; color:#323232; font-family:Arial, Helvetica, sans-serif; font-size:24px;
                 margin-left:20px; padding-top:14px; padding-bottom:5px; width:860px; height:27px;">
           <div style="float:left; margin-right:20px;">Add Item Sizes</div>
           <a href="#github" id="example2" class="special" onclick="return itemsize(<?php echo $itemId;?>)">
             <img src="../Resources/admin_images/plus_sing.jpg" />
           </a>           
       </div>
       
       <div class="clear">&nbsp;</div>
       
	   <div id="lightBOXtable">
	   
	           <?php
                      $itemsizestable = $db->query("SELECT * FROM tbl_productitemsize");
					  $var = $itemsizestable->num_rows;
					  if ($var == 0)
					  {
						  echo '<h4 style="font-family:Arial; font-weight:normal">There are no sizes currently available.</h4>';
					  }
					  else
					  {
	           ?>
               
               <table width="100%" border="0" cellspacing="0" cellpadding="0">
               
                 <tr class="heading">
                   <th scope="col" class="headingborder" width="20%">Item Size Name</th>
                   <th scope="col" class="headingborder" width="10%">Weight</th>
                   <th scope="col" class="headingborder" width="10%">Width</th>
                   <th scope="col" class="headingborder" width="10%">Height</th>
                   <th scope="col" class="headingborder" width="12%">Quantity</th>
                   <th scope="col" class="headingborder" width="13%">Price</th>
                   <th scope="col" class="headingborder" width="13%">Status</th>
                   <th scope="col" class="headingborder_last" width="12%">Actions</th>
                 </tr>
                 
                 <tr class="rowdescription">
                   <td class="bord">Small</td>
                   <td class="bord">10 kg</td>
                   <td class="bord">24</td>
                   <td class="bord">30</td>
                   <td class="bord">1000</td>
                   <td class="bord">$ 200</td>
                   <td class="bord">Enable</td>
                   <td class="imagess"><a href="#" class="tick"></a><a href="#" class="cross"></a></td>
                 </tr>
                 
                 <tr class="rowdescriptioncolor">
                   <td class="bord">Small</td>
                   <td class="bord">10 kg</td>
                   <td class="bord">24</td>
                   <td class="bord">30</td>
                   <td class="bord">1000</td>
                   <td class="bord">$ 200</td>
                   <td class="bord">Enable</td>
                   <td class="imagess"><a href="#" class="tick"></a><a href="#" class="cross"></a></td>
                 </tr>
                 
                 <tr class="rowdescription">
                   <td class="bord">Small</td>
                   <td class="bord">10 kg</td>
                   <td class="bord">24</td>
                   <td class="bord">30</td>
                   <td class="bord">1000</td>
                   <td class="bord">$ 200</td>
                   <td class="bord">Enable</td>
                   <td class="imagess"><a href="#" class="tick"></a><a href="#" class="cross"></a></td>
                 </tr>
              
               </table>
			   
			   <?php
                      }
			   ?>
       </div>
       
       <input type="button" onclick="popup('popUpDiv')" value="Cancel" style="margin-right:10px; background:url(../Resources/admin_images/buttonGreyLarge.jpg) no-repeat; width:148px; height:40px; border:0px; outline:0px; font-family:Arial; font-size:18px; text-shadow: 1px 1px 1px #333; color:#FFF; margin-left:30px; margin-top:20px"/>
       
       <input type="button" onclick="javascript:InsertItem()" value="Insert" style="margin-right:10px; background:url(../Resources/admin_images/buttonGreyLarge.jpg) no-repeat; width:148px; height:40px; border:0px; outline:0px; font-family:Arial; font-size:18px; text-shadow: 1px 1px 1px #333; color:#FFF; margin-top:20px" />
     
     </form>
     
     <div class="clear">&nbsp;</div>
   
   </div>