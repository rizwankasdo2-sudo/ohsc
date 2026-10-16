<div id="pDiv" class="round" style="display:none; overflow:hidden">
   
     <div style="border-bottom:1px solid #e9e5e5; color:#323232; font-family:Arial, Helvetica, sans-serif; font-size:24px;
                 margin-left:20px; padding-top:14px; padding-bottom:5px; width:350px">
           Insert Item Sizes
     </div>
     
     <div style="float:right; margin-top:-45px;">
     
       <a href="#" onclick="pop2('pDiv')" style="text-decoration:none;">
         <img src="../Resources/admin_images/close_button.jpg" width="31" height="31" style="border:none" />
       </a>
       
     </div>
     
     <div id="insert_response"></div>
     
      
     
     <form action="#" method="get" name="itemsize" id="itemsize">
     
       <?php /*?><input type="text" name="iid" id="iid" value="<?php echo $itemId ?>" /><?php */?>
       
       <div class="fields" style="margin-top:25px;">
       
         <div class="label">
           Item Size Name :
         </div>
         
         <div class="inputfield">
           <input name="isname" type="text" id="isname" class="txtbox" />
         </div>
       
       </div>
       
       <div class="fields">
       
         <div class="label">
           Item Weight : 
         </div>
         
         <div class="inputfield">
           <input name="weight" type="text" id="weight" class="txtbox" />
         </div>
        
       </div>
       
       <div class="fields">
       
         <div class="label">
           Item Width : 
         </div>
         
         <div class="inputfield">
           <input name="width" type="text" id="width" class="txtbox" />
         </div>
        
       </div>
       
       <div class="fields">
       
         <div class="label">
           Item Height : 
         </div>
         
         <div class="inputfield">
           <input name="height" type="text" id="height" class="txtbox" />
         </div>
        
       </div>
       
       <div class="fields">
       
         <div class="label">
           Item Quantity : 
         </div>
         
         <div class="inputfield">
           <input name="quantity" type="text" id="quantity" class="txtbox" />
         </div>
        
       </div>
       
       <div class="fields">
       
         <div class="label">
           Item Price : 
         </div>
         
         <div class="inputfield">
           <input name="price" type="text" id="price" class="txtbox" />
         </div>
        
       </div>
       
       <div class="fields">
       
         <div class="label">
           Status : 
         </div>
         
         <div class="inputfield">
          <select name="sizestatus" id="sizestatus" class="txtbox" style="width:140px;">
          
            <option value="1">Enable</option>
		    <option value="0">Disable</option>
              
          </select>
         </div>
       
       </div>
       
       <div class="fields">
       
         <div class="label">
           Colors Available : 
         </div>
         
         <div class="inputfield">
            
            <input type="checkbox" name="optionss" id="optionss" value="1" /> Black <br />
            <input type="checkbox" name="optionss" id="optionss" value="2" /> White <br />
            <input type="checkbox" name="optionss" id="optionss" value="3" /> Grey <br />
            <input type="checkbox" name="optionss" id="optionss" value="4" /> Off White <br />
            <input type="checkbox" name="optionss" id="optionss" value="5" /> Other <br />
            
         </div>
       
       </div>
       
       <input type="button" onclick="pop2('pDiv')" value="Cancel" style="margin-right:10px; background:url(../Resources/admin_images/buttonGreyLarge.jpg) no-repeat; width:148px; height:40px; border:0px; outline:0px; font-family:Arial; font-size:18px; text-shadow: 1px 1px 1px #333; color:#FFF; margin-left:30px; margin-top:20px" />
       
       <input type="button" onclick="pop2('pDiv'), InsertItemSizes()" value="Insert" style="margin-right:10px; background:url(../Resources/admin_images/buttonGreyLarge.jpg) no-repeat; width:148px; height:40px; border:0px; outline:0px; font-family:Arial; font-size:18px; text-shadow: 1px 1px 1px #333; color:#FFF; margin-top:20px" />
     
     </form>
     
     <div class="clear">&nbsp;</div>
   
   </div>