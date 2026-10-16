<div>
    <div id="addproducts">
        <div id="uper">
            <div id="upertop" class="orange_bar">
                <h4>Overview</h4>
            </div>
            <div id="uperdetail">
                <div id="general">
                    <div class="fieldrepeat">
                        <p>Payment : </p>
                        <input type="text" id="catname" name="catname" value="<?php echo $_POST['catname'];?>" class="productfieldlarge" style="width:300px;" />
                    </div>
                    <div class="clear">
                    </div>
                    <div class="fieldrepeat">
                        <p>Diposit : </p>
                        <input type="text" id="order" name="order" value="<?php echo $_POST['order'];?>" class="productfieldlarge" style="width:300px;" />
                    </div>
                    <div class="clear">
                    </div>
                    <div class="fieldrepeat">
                        <p>Number of Months : </p>
                        <input type="text" id="month" name="month" value="<?php echo $_POST['month']; ?>" class="productfieldlarge" style="width:300px;" />
                    </div>
                    <div class="clear">
                    </div>
                    <div class="fieldrepeat">
                        <p>Status : </p>
                        <select name="status2" id="status2" class="status" style="width:150px;">
					                  <option value='0'>Disable</option>
                            <option value='1' <?php echo ($_POST['status2'] == 1) ? 'selected' : ''; ?>>Enable</option>
                        </select>
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
CKEDITOR.replace( 'description',
    {
        filebrowserBrowseUrl : 'cms/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Images',
        filebrowserFlashBrowseUrl : 'cms/ckfinder/ckfinder.html?Type=Flash',
        filebrowserUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        filebrowserFlashUploadUrl : 'cms/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
    });
</script> 
