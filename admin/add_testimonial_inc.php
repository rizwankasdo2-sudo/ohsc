<div>
    <div id="addproducts">
         <div id="uper">
            <div id="upertop">
                <h4>Testimonial</h4>
            </div>
            <div id="uperdetail">
                <div id="general">
                    <div class="clear">
                    </div>
                    <div class="fieldrepeat">
                        <p>Name</p>
                        <label for="textfield"></label>
                        <input type="text" name="name" id="name"  class="productfield" size="300" value="<?php echo $_POST['name']; ?>" />
                    </div>
                    <div class="fieldrepeat" style="margin-top:10px;">
                        <p>description</p>
                        <label for="textarea"></label>
                        <textarea name="description" id="description" cols="60" rows="10"><?php echo $_POST['description']; ?></textarea>
                    </div>
                    <div class="clear">
                    </div>
                    <div class="fieldrepeat" style="margin-top:10px;">
                        <p>Detial</p>
                        <label for="textarea"></label>
                        <textarea name="detail" id="detail" cols="60" rows="10"><?php echo $_POST['detail']; ?></textarea>
                    </div>
                    <div class="fieldrepeat">
                        <p>Status : </p>
                        <select name="IStatus" id="IStatus" class="productfieldlarge" style="width:140px;">
                            <option value="1" <?php echo ($_POST['IStatus'] == 1) ? 'selected' : ''; ?>>Enable</option>
                            <option value="0" <?php echo ($_POST['IStatus'] != 1) ? 'selected' : ''; ?>>Disable</option>
                        </select>
                    </div>
                    <div class="clear">
                    </div>
                    <div class="fieldrepeat">               
                        <p>Rating : </p>
                        <label ><input type="radio" name="rating" value="1"  required <?php echo ($_POST['rating'] == 1) ? 'checked' : ''; ?> /><img src="images/one-star.jpg"></label>
                        <label ><input type="radio"  name="rating" value="2" <?php echo ($_POST['rating'] == 2) ? 'checked' : ''; ?> /><img src="images/two-star.jpg"></label>
                        <label ><input type="radio"  name="rating" value="3" <?php echo ($_POST['rating'] == 3) ? 'checked' : ''; ?> /><img src="images/three-star.jpg"></label>
                        <label ><input type="radio"  name="rating" value="4" <?php echo ($_POST['rating'] == 4) ? 'checked' : ''; ?> /><img src="images/four-star.jpg"></label>
                        <label ><input type="radio"  name="rating" value="5" <?php echo ($_POST['rating'] == 5) ? 'checked' : ''; ?> /><img src="images/five-star.jpg"></label>
                    </div>
                </div>
            </div>
            <div class="clear">&nbsp;</div>
        </div>
    </div>
</div>
