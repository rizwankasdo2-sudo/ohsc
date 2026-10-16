<div>
    <div id="addproducts">
        <div id="uper">
            <div id="upertop" class="orange_bar">
                <h4>Overview</h4>
            </div>
            <div id="uperdetail">
                <div id="general">
                    <div class="fieldrepeat">
                        <p>Page Title : </p>
                        <textarea id="NewsTitle" name="NewsTitle" cols="60" rows="10" ><?php echo $_POST['NewsTitle']; ?></textarea>
                    </div>
                    <div class="clear">
                    </div>
                    <div class="fieldrepeat">
                        <p> Meta Keywords</p>
                        <label for="textfield"></label>
                        <textarea name="Newsdiscription" id="Newsdiscription"  cols="60" rows="10" ><?php echo $_POST['Newsdiscription']; ?></textarea>
                    </div>
                    <div class="fieldrepeat" style="margin-top:10px;">
                        <p>Meta Description</p>
                        <label for="textarea"></label>
                        <textarea name="NewsDetail" id="NewsDetail" cols="60" rows="10"><?php echo $_POST['NewsDetail']; ?></textarea>
                    </div>
                    <div class="clear">
                    </div>
                    <div class="fieldrepeat" style="margin-top:10px;">
                        <p>Robots  : </p>
                        <label><input type="radio" name="Robot" value="Index,Nofollow" <?php if($_POST['Robot']=="Index,Nofollow"){ echo "checked"; } ?>/>Index</label>
                        <label><input type="radio" name="Robot" value="Noindex,Follow" <?php if($_POST['Robot']=="Noindex,Follow"){ echo "checked"; } ?> />Follow</label>
                        <label><input type="radio" name="Robot" value="Noindex,Nofollow" <?php if($_POST['Robot']=="Noindex,Nofollow"){ echo "checked"; } ?> />Disallow</label>
                        <label><input type="radio" name="Robot" value="Index,Follow" <?php if($_POST['Robot']=="Index,Follow"){ echo "checked"; } ?> />Allow</label> 
                    </div>
                    <div class="clear">
                    </div>
                    <div class="fieldrepeat">
                        <p>Status : </p>
                        <select name="status3" id="status3" class="status" style="width:150px;">
                            <option value='0'>Disable</option>
                            <option value='1' <?php echo ($_POST['status3'] == 1) ? 'selected' : ''; ?>>Enable</option>
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
