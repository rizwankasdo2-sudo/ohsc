<?php
session_start();
ob_start();
if($_SESSION['adminid']== "")
{
    header("location:index.php");
    exit();
}
if(isset($_GET['cid']))
{
    $cid = $_GET['cid'];
}
?>
<div id="uper">
    <div id="upertop" class="orange_bar">
        <h4>Overview</h4>
    </div>
    <div id="uperdetail">
        <div id="general">
            <div class="fieldrepeat">
                <p>Name : </p>
                <input type="text" id="coursename" name="coursename"
                    class="productfieldlarge" style="width:300px;" />
            </div>
            <div class="clear">
            </div>
            <div class="fieldrepeat">
                <p>Slug : </p>
                <input type="text" id="slug" name="slug" class="productfieldlarge"
                    style="width:300px;" />
            </div>
            <div class="clear">
            </div>
            <div class="fieldrepeat">
                <p>Order Number : </p>
                <input type="number" id="order" name="order" class="productfieldlarge"
                    style="width:300px;" />
            </div>
            <div class="clear">
            </div>
            <div class="fieldrepeat">
                <p>Portal Course ID : </p>
                <input type="text" id="portal_course_id" name="portal_course_id" class="productfieldlarge"
                    style="width:300px;" />
            </div>
            <div class="clear">
            </div>
            <div class="fieldrepeat">
                <p>Thumbnail: </p>
                <input type="file" id="slug" name="thumbnail" />
            </div>
            <div class="clear">
            </div>
            <div class="fieldrepeat">
                <p>Alternative Text : </p>
                <div style="float:left">
                    <input type="text" name="alternate"
                        value="<?php if(!empty($newsreslt['alternate'])){ echo $newsreslt['alternate']; }?>" />
                </div>
            </div>
            <div class="clear">
            </div>
            <div class="fieldrepeat">
                <p>Course Thumbnail :<br />(70 x 70) </p>
                <input type="file" id="square_icon" name="square_icon" />
            </div>
            <div class="clear"> &nbsp;</div>
            <?php if ($_SESSION['adminType'] == 2) { ?>
            <div class="fieldrepeat">
                <p>Status : </p>
                <select name="status" id="status" class="productfieldlarge"
                    style="width:140px;">
                    <option value="1">Enable</option>
                    <option value="0">Disable</option>
                </select>
            </div>
            <?php } ?>
            <div class="fieldrepeat">
                <p>Popular Course : </p>
                <select name="famous_course" id="famous_course" class="productfieldlarge"
                    style="width:140px;">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="fieldrepeat">
                <p>Type : </p>
                <select name="type" id="type" class="productfieldlarge"
                    style="width:140px;">
                    <option value="1">Paid Course</option>
                    <option value="2">Free Course</option>
                    <option value="3">Free Course Category</option>
                </select>
            </div>
            <div class="fieldrepeat">
                <p>Total Students : </p>
                <input type="number" name="admin_total_enrolled" autocomplete="off" />
            </div>
            <div class="fieldrepeat">
                <p>Overall Rating : </p>
                <select name="admin_rating" id="admin_rating" class="productfieldlarge"
                    style="width:140px;">
                    <?php for ($i = 5; $i > 4; $i -= 0.10) { ?>
                    <option value="<?php echo number_format($i, 1, '.', ''); ?>">
                        <?php echo number_format($i, 1, '.', ''); ?></option>
                    <?php } ?>
                    <?php for ($i = 4; $i >= 0; $i--) { ?>
                    <option value="<?php echo number_format($i, 1, '.', ''); ?>">
                        <?php echo number_format($i, 1, '.', ''); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="fieldrepeat">
                <p>Total Ratings : </p>
                <input type="number" name="total_ratings" autocomplete="off" />
            </div>
            <div class="fieldrepeat">
                <p>Total 5 Star Ratings : </p>
                <input type="number" name="total_ratings_five" autocomplete="off" />
            </div>
            <div class="fieldrepeat">
                <p>Total 4 Star Ratings : </p>
                <input type="number" name="total_ratings_four" autocomplete="off" />
            </div>
            <div class="fieldrepeat">
                <p>Total 3 Star Ratings : </p>
                <input type="number" name="total_ratings_three" autocomplete="off" />
            </div>
            <div class="fieldrepeat">
                <p>Total 2 Star Ratings : </p>
                <input type="number" name="total_ratings_two" autocomplete="off" />
            </div>
            <div class="fieldrepeat">
                <p>Total 1 Star Ratings : </p>
                <input type="number" name="total_ratings_one" autocomplete="off" />
            </div>
            <div class="fieldrepeat">
                <p>Available on Portal : </p>
                <select name="added_on_portal" id="added_on_portal" style="width:140px;">
                    <option value="0">--Select Portal --</option>
                    <option value="1">E-Learning College</option>
                    <option value="2">OHSC</option>
                </select>
            </div>
            <div class="clear">
            </div>
            <div class="fieldrepeat" style="display:flex;">
                <p>Related Certificates : </p>
                <div style="font-family:Tahoma, arial;font-size:12px;">
                    <?php
                    $i = 0;
                    $certificate_result = $db->query("SELECT id, title FROM certificates ORDER BY title ASC");
                    while ($certificate_row = $certificate_result->fetch_array()) {
                        $i++;
                    ?>
                    <input type="checkbox" name="certificate_ids[]" value="<?php echo $certificate_row['id']; ?>" /> <?php echo $certificate_row['title']; ?>
                    <?php
                        if ($i < 2) {
                            echo '&nbsp&nbsp&nbsp&nbsp&nbsp';
                        } else {
                            echo '<br /><br />';
                            $i = 0;
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="clear">
            </div>
        </div>
    </div>
    <div class="clear">&nbsp;</div>
</div>
<div class="clear">&nbsp;</div>
