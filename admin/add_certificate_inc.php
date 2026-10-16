<div>
    <div id="addproducts">
        <div id="uper">
            <div id="upertop">
                <h4>Add Certificates</h4>
            </div>
            <div id="uperdetail">
                <div id="general">
                    <div class="fieldrepeat">
                        <p>Title : </p>
                        <input name="title" id="title" type="text" value="<?php echo $_POST['title']; ?>" />
                    </div>
                    <div class="clear">
                    </div>
                    <div class="fieldrepeat">
                        <p>Tagline : </p>
                        <textarea name="tag_line" id="tag_line" cols="60" rows="5" maxlength="500"><?php echo $_POST['tag_line']; ?></textarea>
                    </div>
                    <div class="clear">
                    </div>
                    <div class="fieldrepeat">
                        <p>Earn By : </p>
                        <textarea name="earn_by" id="earn_by" cols="60" rows="5" maxlength="500"><?php echo $_POST['earn_by']; ?></textarea>
                    </div>
                    <div class="clear">
                    </div>
                    <div class="fieldrepeat">
                        <p>Image :<br />340 x 429 Format:webp </p>
                        <input name="pic" id="pic" type="file" />
                    </div>
                    <div class="clear">
                    </div>
                </div>
            </div>
            <div class="clear">&nbsp;</div>
        </div>
    </div>
</div>
