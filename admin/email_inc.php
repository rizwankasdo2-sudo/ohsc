<div>
    <div id="addproducts">
        <div id="uper">
            <div id="upertop" class="orange_bar">
                <h4>Email</h4>
            </div>
            <div id="uperdetail">
                <div id="general">
                    <div class="fieldrepeat">
                        <p>Subject : </p>
                        <input type="text" id="subject" name="subject" value="<?php echo $_POST['subject'];?>" class="productfieldlarge" style="width:300px;" />
                    </div>
                    <div class="clear">
                    </div>
                    <div class="fieldrepeat">
                        <p>Message : </p>
                        <div style="width: 78%;padding-top:10px;margin-left: 183px;">
                            <textarea id="body"  name="body" cols="3" rows="4" class="ckeditor"><?php echo $_POST['body'];?></textarea>
                        </div>
                    </div>
                    <div class="clear">
                    </div>
                    <div class="clear">
                    </div>
                </div>
            </div>
            <div class="clear">&nbsp;
             </div>
        </div>
    </div>
 </div>
<div class="clear">&nbsp;</div>
  <script>
	CKEDITOR.replace( 'body',
    {
        filebrowserBrowseUrl : 'cms/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Images',
        filebrowserFlashBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Flash',
        filebrowserUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        filebrowserFlashUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
    });
	</script>
