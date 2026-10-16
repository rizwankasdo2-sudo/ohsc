<div>
    <div id="addproducts">
        <div id="uper">
            <div id="upertop">
                <h4>Add Module</h4>
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
                        <p>Excerpt : </p>
                        <textarea id="excerpt" name="excerpt" cols="60" rows="10"><?php echo $_POST['excerpt']; ?></textarea>
                    </div>
                    <div class="clear">
                    </div>
                    <div class="fieldrepeat">
                        <p>Detail : </p>
                        <textarea id="description" name="description" cols="60" rows="10"><?php echo $_POST['description']; ?></textarea>
                    </div>
                    <div class="clear">
                    </div>
                    <div class="fieldrepeat">
                        <p>Required </p>
                        <div style="width:80%; float:left">
                            <select name="required" id="required">
                                <option value="0" <?php echo (isset($_POST['required']) && $_POST['required'] == '0') ? 'selected' : ''; ?>>Optional</option>
                                <option value="1" <?php echo (!empty($_POST['required']) && $_POST['required'] == '1') ? 'selected' : ''; ?>>Required</option>
                            </select>
                        </div>
                    </div>
                    <div class="clear">
                    </div>
                    <div class="fieldrepeat">
                        <p>Hours : </p>
                        <input name="hours" id="hours" type="number" value="<?php echo $_POST['hours']; ?>" />
                    </div>
                    <div class="clear">
                    </div>
                </div>
            </div>
            <div class="clear">&nbsp;</div>
        </div>
    </div>
</div>
