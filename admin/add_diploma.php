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
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Add Diploma</title>
    <link rel="stylesheet" href="../Resources/admin_css/admin-style.css" />
    <link rel="stylesheet" href="../Resources/admin_css/add-product.css" />
    <script type="text/javascript" src="../Resources/admin_js/csshorizontalmenu.js"></script>
    <script type="text/javascript" src="cms/ckeditor/ckeditor.js"></script>
    <script src="cms/ckeditor/sample.js" type="text/javascript"></script>
    <!--[if lte IE 6]>
    <script type="text/javascript" src="../Resources/admin_js/supersleight-min.js"></script>
    <![endif]-->
    <style type="text/css">
        #related-diploma-rows {
            float: left;
            width: 700px;
        }
        .related-diploma-row {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        .related-diploma-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .related-diploma-search {
            width: 300px;
        }
        .related-diploma-clear,
        .related-diploma-remove-row {
            border: 0;
            background: transparent;
            color: #c00;
            font-size: 20px;
            line-height: 1;
            cursor: pointer;
            padding: 0 6px;
        }
        .related-diploma-remove-row {
            margin-left: 4px;
        }
        .related-diploma-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            width: 300px;
            max-height: 220px;
            overflow-y: auto;
            margin: 0;
            padding: 0;
            list-style: none;
            border: 1px solid #ccc;
            background: #fff;
            display: none;
        }
        .related-diploma-suggestions li {
            padding: 6px 10px;
            cursor: pointer;
            font-family: Tahoma, Arial, sans-serif;
            font-size: 12px;
        }
        .related-diploma-suggestions li:hover {
            background: #f2f2f2;
        }
        #add-related-diploma-row {
            margin-top: 4px;
            padding: 4px 10px;
            cursor: pointer;
            font-family: Tahoma, Arial, sans-serif;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div id="wrap_dashboard">
        <?php
include("classes/connection.php");
include("includes/related-diploma-helper.php");

$form = array();
$form_errors = array();
if (!empty($_SESSION['add_diploma_form_errors'])) {
    $form_errors = $_SESSION['add_diploma_form_errors'];
    unset($_SESSION['add_diploma_form_errors']);
}
if (!empty($_SESSION['add_diploma_form'])) {
    $form = $_SESSION['add_diploma_form'];
    unset($_SESSION['add_diploma_form']);
    if (!empty($form['cid'])) {
        $cid = $form['cid'];
    }
}

function diplomaFormValue($key, $default = '')
{
    global $form;

    if (!isset($form[$key]) || is_array($form[$key])) {
        return htmlspecialchars($default, ENT_QUOTES, 'UTF-8');
    }

    return htmlspecialchars($form[$key], ENT_QUOTES, 'UTF-8');
}

function diplomaFormSelected($key, $value)
{
    global $form;

    return (isset($form[$key]) && (string) $form[$key] === (string) $value) ? 'selected' : '';
}

function diplomaFormChecked($key, $value)
{
    global $form;

    if (!isset($form[$key])) {
        return '';
    }

    $values = is_array($form[$key]) ? array_map('strval', $form[$key]) : array((string) $form[$key]);

    return in_array((string) $value, $values, true) ? 'checked' : '';
}

$parent_ids_selected = (isset($form['parent_ids']) && is_array($form['parent_ids'])) ? array_map('strval', $form['parent_ids']) : array();
$certificate_ids_selected = (isset($form['certificate_ids']) && is_array($form['certificate_ids'])) ? array_map('strval', $form['certificate_ids']) : array();
$show_subcats = !empty($parent_ids_selected);
$selected_cid = isset($form['cid']) ? $form['cid'] : (isset($cid) ? $cid : '');
$related_diplomas = array();
if (!empty($form['related_diploma_ids']) && is_array($form['related_diploma_ids'])) {
    $related_diploma_ids_restore = implode(',', array_filter(array_map('intval', $form['related_diploma_ids'])));
    $related_diplomas = buildRelatedDiplomasFromIds($db, $related_diploma_ids_restore);
}

include("includes/admin-header.php");
include("includes/admin-menu.php");
?>
        <div id="outer_contanier">
            <div id="board">
                <div id="left_corner">
                </div>
                <?php
                if (!empty($form_errors)) {
                    echo '<div style="max-width:713px; margin:0 auto 20px; padding:16px 20px; border:1px solid #d9534f; background:#fdf2f2; border-radius:6px; color:#7a1f1f;">';
                    echo '<p style="margin:0 0 12px; font-size:16px; font-weight:bold;">Please fix the following errors:</p>';
                    echo '<ul style="margin:0; padding:0 0 0 20px; font-size:15px; line-height:1.7;">';
                    foreach ($form_errors as $error) {
                        echo '<li>' . htmlspecialchars($error) . '</li>';
                    }
                    echo '</ul></div>';
                }
                ?>
                <form action="insertdiploma.php" method="post" enctype="multipart/form-data" name="addproduct"
                    runat="server">
                    <div id="midle_repeat">
                        <div id="tit">
                            <h3>Add Diploma</h3>
                        </div>
                        <div id="buttons">
                            <ul style="float:right;">
                                <li><input type="submit" value="Save" class="add_cancel_button" /></li>
                                <li><a href="diploma.php?cid=<?php echo $cid; ?>">Cancel</a></li>
                            </ul>
                        </div>
                    </div>
                    <div id="right_corner">
                    </div>
                    <div id="main_content">
                        <div id="addproducts">
                            <div id="uper">
                                <div id="upertop">
                                    <h4>Overview</h4>
                                </div>
                                <div id="uperdetail">
                                    <div id="general">
                                        <div class="fieldrepeat">
                                            <p>Name : </p>
                                            <input type="text" id="coursename" name="coursename"
                                                class="productfieldlarge" style="width:300px;" value="<?php echo diplomaFormValue('coursename'); ?>" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Slug : </p>
                                            <input type="text" id="slug" name="slug" class="productfieldlarge"
                                                style="width:300px;" value="<?php echo diplomaFormValue('slug'); ?>" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Order Number : </p>
                                            <input type="number" id="order" name="order" class="productfieldlarge"
                                                style="width:300px;" value="<?php echo diplomaFormValue('order'); ?>" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Portal Course ID : </p>
                                            <input type="text" id="portal_course_id" name="portal_course_id" class="productfieldlarge"
                                                style="width:300px;" value="<?php echo diplomaFormValue('portal_course_id'); ?>" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Image: </p>
                                            <input type="file" id="slug" name="thumbnail" />
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Image Alternative Text : </p>
                                            <div style="float:left">
                                                <input type="text" name="alternate"
                                                    value="<?php echo diplomaFormValue('alternate'); ?>" />
                                            </div>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Image Title : </p>
                                            <textarea name="thumbnail_title" id="thumbnail_title" cols="60"
                                                rows="5"><?php echo diplomaFormValue('thumbnail_title'); ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Image Description : </p>
                                            <textarea name="thumbnail_description" id="thumbnail_description" cols="60"
                                                rows="5"><?php echo diplomaFormValue('thumbnail_description'); ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Course Thumbnail :<br />(272 x 153) </p>
                                            <input type="file" id="square_icon" name="square_icon" />
                                        </div>
                                        <div class="clear"> &nbsp;</div>
                                        <div class="fieldrepeat">
                                            <p>Thumbnail Alt Text : </p>
                                            <textarea name="square_icon_alt" id="square_icon_alt" cols="60"
                                                rows="5"><?php echo diplomaFormValue('square_icon_alt'); ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Thumbnail Title : </p>
                                            <textarea name="square_icon_title" id="square_icon_title" cols="60"
                                                rows="5"><?php echo diplomaFormValue('square_icon_title'); ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Thumbnail Description : </p>
                                            <textarea name="square_icon_description" id="square_icon_description" cols="60"
                                                rows="5"><?php echo diplomaFormValue('square_icon_description'); ?></textarea>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <?php if ($_SESSION['adminType'] == 2) { ?>
                                        <div class="fieldrepeat">
                                            <p>Status : </p>
                                            <select name="status" id="status" class="productfieldlarge"
                                                style="width:140px;">
                                                <option value="1" <?php echo diplomaFormSelected('status', '1'); ?>>Enable</option>
                                                <option value="0" <?php echo diplomaFormSelected('status', '0'); ?>>Disable</option>
                                            </select>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <?php } ?>
                                        <div class="fieldrepeat">
                                            <p>Main Category : </p>
                                            <select name="cid" id="cid" class="status" style="width:300px;" required>
                                                <option value="">-- Select Main Category --</option>
                                                <?php
                            $cours_cat_sql = "SELECT cid, cname FROM tbl_courses WHERE previous_free_category = 0";
                            $cours_cat_result = $db->query($cours_cat_sql);
                            while($cours_cat_row = $cours_cat_result->fetch_array()){ ?>
                                                <option value="<?php echo $cours_cat_row['cid']; ?>"
                                                    <?php echo ((string) $selected_cid === (string) $cours_cat_row['cid']) ? 'selected' : ''; ?>>
                                                    <?php echo $cours_cat_row['cname']; ?></option>
                                                <?php }
                            ?>
                                            </select>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Subcategories : </p>
                                            <input type="radio" name="show_subcats" onclick="document.getElementById('pcatdiv').style.display = 'flex';" <?php echo $show_subcats ? 'checked' : ''; ?> /> Show Subcategories &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="show_subcats" onclick="document.getElementById('pcatdiv').style.display = 'none';" <?php echo $show_subcats ? '' : 'checked'; ?> /> Hide Subcategories
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat" style="display:<?php echo $show_subcats ? 'flex' : 'none'; ?>;" id="pcatdiv">
                                            <p> &nbsp;</p>
                                            <div style="font-family:Tahoma, arial;font-size:12px;">
                                                <?php
                                                $i = 0;
                                                $parent_ids_result = $db->query("SELECT cid, cname FROM tbl_courses WHERE parent_ids <> '' ORDER BY cname ASC");
                                                while ($parent_ids_row = $parent_ids_result->fetch_array()) {
                                                    $i++;
                                                ?>
                                                <input type="checkbox" name="parent_ids[]" value="<?php echo $parent_ids_row['cid']; ?>" <?php echo in_array((string) $parent_ids_row['cid'], $parent_ids_selected, true) ? 'checked' : ''; ?> /> <?php echo $parent_ids_row['cname']; ?>
                                                <?php
                                                    if ($i < 3) {
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
                                        <div class="fieldrepeat">
                                            <p>Free Category : </p>
                                            <select name="cid_free" id="cid_free" class="status" style="width:300px;">
                                                <option value="">-- Select Free Category --</option>
                                                <?php
                            $cours_cat_sql = "SELECT cid, cname FROM tbl_courses WHERE previous_free_category = 1";
                            $cours_cat_result = $db->query($cours_cat_sql);
                            while($cours_cat_row = $cours_cat_result->fetch_array()){ ?>
                                                <option value="<?php echo $cours_cat_row['cid']; ?>" <?php echo diplomaFormSelected('cid_free', $cours_cat_row['cid']); ?>>
                                                    <?php echo $cours_cat_row['cname']; ?></option>
                                                <?php }
                            ?>
                                            </select>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Popular Course : </p>
                                            <select name="famous_course" id="famous_course" class="productfieldlarge"
                                                style="width:140px;">
                                                <option value="1" <?php echo diplomaFormSelected('famous_course', '1'); ?>>Yes</option>
                                                <option value="0" <?php echo diplomaFormSelected('famous_course', '0'); ?>>No</option>
                                            </select>
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Top Diploma : </p>
                                            <select name="top_diploma" id="top_diploma" class="productfieldlarge"
                                                style="width:140px;">
                                                <option value="1" <?php echo diplomaFormSelected('top_diploma', '1'); ?>>
                                                    Yes</option>
                                                <option value="0" <?php echo diplomaFormSelected('top_diploma', '0'); ?>>
                                                    No</option>
                                            </select>
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Top Certification : </p>
                                            <select name="top_certification" id="top_certification" class="productfieldlarge"
                                                style="width:140px;">
                                                <option value="1" <?php echo diplomaFormSelected('top_certification', '1'); ?>>
                                                    Yes</option>
                                                <option value="0" <?php echo diplomaFormSelected('top_certification', '0'); ?>>
                                                    No</option>
                                            </select>
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>New Course : </p>
                                            <select name="new_course" id="new_course" class="productfieldlarge"
                                                style="width:140px;">
                                                <option value="1" <?php echo diplomaFormSelected('new_course', '1'); ?>>
                                                    Yes</option>
                                                <option value="0" <?php echo diplomaFormSelected('new_course', '0'); ?>>
                                                    No</option>
                                            </select>
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Type : </p>
                                            <select name="type" id="type" class="productfieldlarge"
                                                style="width:140px;">
                                                <option value="1" <?php echo diplomaFormSelected('type', '1'); ?>>Paid Course</option>
                                                <option value="2" <?php echo diplomaFormSelected('type', '2'); ?>>Free Course</option>
                                                <option value="3" <?php echo diplomaFormSelected('type', '3'); ?>>Free Course Category</option>
                                                <option value="4" <?php echo diplomaFormSelected('type', '4'); ?>>Regulated</option>
                                                <option value="5" <?php echo diplomaFormSelected('type', '5'); ?>>Accredited</option>
                                                <option value="6" <?php echo diplomaFormSelected('type', '6'); ?>>Endorsed</option>
                                                <option value="7" <?php echo diplomaFormSelected('type', '7'); ?>>CPD</option>
                                            </select>
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Total Students : </p>
                                            <input type="number" name="admin_total_enrolled" autocomplete="off" value="<?php echo diplomaFormValue('admin_total_enrolled'); ?>" />
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Overall Rating : </p>
                                            <select name="admin_rating" id="admin_rating" class="productfieldlarge"
                                                style="width:140px;">
                                                <?php for ($i = 5; $i > 4; $i -= 0.10) { ?>
                                                <option value="<?php echo number_format($i, 1, '.', ''); ?>" <?php echo diplomaFormSelected('admin_rating', number_format($i, 1, '.', '')); ?>>
                                                    <?php echo number_format($i, 1, '.', ''); ?></option>
                                                <?php } ?>
                                                <?php for ($i = 4; $i >= 0; $i--) { ?>
                                                <option value="<?php echo number_format($i, 1, '.', ''); ?>" <?php echo diplomaFormSelected('admin_rating', number_format($i, 1, '.', '')); ?>>
                                                    <?php echo number_format($i, 1, '.', ''); ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Total Ratings : </p>
                                            <input type="number" name="total_ratings" autocomplete="off" value="<?php echo diplomaFormValue('total_ratings'); ?>" />
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Total 5 Star Ratings : </p>
                                            <input type="number" name="total_ratings_five" autocomplete="off" value="<?php echo diplomaFormValue('total_ratings_five'); ?>" />
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Total 4 Star Ratings : </p>
                                            <input type="number" name="total_ratings_four" autocomplete="off" value="<?php echo diplomaFormValue('total_ratings_four'); ?>" />
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Total 3 Star Ratings : </p>
                                            <input type="number" name="total_ratings_three" autocomplete="off" value="<?php echo diplomaFormValue('total_ratings_three'); ?>" />
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Total 2 Star Ratings : </p>
                                            <input type="number" name="total_ratings_two" autocomplete="off" value="<?php echo diplomaFormValue('total_ratings_two'); ?>" />
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Total 1 Star Ratings : </p>
                                            <input type="number" name="total_ratings_one" autocomplete="off" value="<?php echo diplomaFormValue('total_ratings_one'); ?>" />
                                        </div>
                                        <div class="fieldrepeat">
                                            <p>Available on Portal : </p>
                                            <select name="added_on_portal" id="added_on_portal" style="width:140px;">
                                                <option value="0" <?php echo diplomaFormSelected('added_on_portal', '0'); ?>>--Select Portal --</option>
                                                <option value="1" <?php echo diplomaFormSelected('added_on_portal', '1'); ?>>E-Learning College</option>
                                                <option value="2" <?php echo diplomaFormSelected('added_on_portal', '2'); ?>>OHSC</option>
                                            </select>
                                        </div>
                                        <div class="clear">
                                        </div>
                                        <?php include 'includes/related-diploma-field.php'; ?>
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
                                                <input type="checkbox" name="certificate_ids[]" value="<?php echo $certificate_row['id']; ?>" <?php echo in_array((string) $certificate_row['id'], $certificate_ids_selected, true) ? 'checked' : ''; ?> /> <?php echo $certificate_row['title']; ?>
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
                                        <div class="fieldrepeat">
                                            <p>Certification : </p>
                                            <select name="certification" id="certification" style="width:140px;">
                                                <option value="0" <?php echo diplomaFormSelected('certification', '0'); ?>>--Select Certification --</option>
                                                <option value="1" <?php echo diplomaFormSelected('certification', '1'); ?>>Certificate</option>
                                                <option value="2" <?php echo diplomaFormSelected('certification', '2'); ?>>Diploma</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="clear">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                    <div class="clear">&nbsp;</div>
                </form>
            </div>
            <div class="clear">&nbsp;</div>
            <?php include("includes/admin-footer.php"); ?>
        </div>
    </div>
    <script type="text/javascript">
        (function () {
            var rowsContainer = document.getElementById('related-diploma-rows');
            var addRowButton = document.getElementById('add-related-diploma-row');
            var debounceTimer = null;
            var selectedIds = {};

            function getSelectedIds(excludeRow) {
                var ids = {};
                var rows = rowsContainer.querySelectorAll('.related-diploma-row');

                for (var i = 0; i < rows.length; i++) {
                    if (excludeRow && rows[i] === excludeRow) {
                        continue;
                    }

                    var hiddenInput = rows[i].querySelector('.related-diploma-id');
                    if (hiddenInput && hiddenInput.value) {
                        ids[hiddenInput.value] = true;
                    }
                }

                return ids;
            }

            function hideSuggestions(list) {
                list.innerHTML = '';
                list.style.display = 'none';
            }

            function toggleClearButton(row) {
                var clearButton = row.querySelector('.related-diploma-clear');
                var hiddenInput = row.querySelector('.related-diploma-id');

                if (!clearButton) {
                    return;
                }

                clearButton.style.display = hiddenInput.value ? 'inline-block' : 'none';
            }

            function clearSelection(row) {
                var searchInput = row.querySelector('.related-diploma-search');
                var hiddenInput = row.querySelector('.related-diploma-id');
                var suggestions = row.querySelector('.related-diploma-suggestions');

                if (hiddenInput.value) {
                    delete selectedIds[hiddenInput.value];
                }

                searchInput.value = '';
                hiddenInput.value = '';
                searchInput.readOnly = false;
                hideSuggestions(suggestions);
                toggleClearButton(row);
            }

            function selectSuggestion(row, item) {
                var searchInput = row.querySelector('.related-diploma-search');
                var hiddenInput = row.querySelector('.related-diploma-id');
                var suggestions = row.querySelector('.related-diploma-suggestions');
                var currentId = hiddenInput.value;
                var isPrimaryRow = row.getAttribute('data-row-type') === 'primary';

                if (currentId && currentId !== String(item.id)) {
                    delete selectedIds[currentId];
                }

                searchInput.value = item.name;
                hiddenInput.value = item.id;
                searchInput.readOnly = !isPrimaryRow;
                selectedIds[item.id] = true;
                hideSuggestions(suggestions);
                toggleClearButton(row);
            }

            function renderSuggestions(row, items) {
                var suggestions = row.querySelector('.related-diploma-suggestions');
                var takenIds = getSelectedIds(row);

                suggestions.innerHTML = '';

                for (var i = 0; i < items.length; i++) {
                    if (takenIds[String(items[i].id)]) {
                        continue;
                    }

                    var listItem = document.createElement('li');
                    listItem.textContent = items[i].name;
                    listItem.setAttribute('data-id', items[i].id);
                    listItem.setAttribute('data-name', items[i].name);
                    listItem.addEventListener('mousedown', function (event) {
                        event.preventDefault();
                        selectSuggestion(row, {
                            id: this.getAttribute('data-id'),
                            name: this.getAttribute('data-name')
                        });
                    });
                    suggestions.appendChild(listItem);
                }

                suggestions.style.display = suggestions.children.length ? 'block' : 'none';
            }

            function fetchSuggestions(row, query) {
                if (!query) {
                    hideSuggestions(row.querySelector('.related-diploma-suggestions'));
                    return;
                }

                fetch('ajax/diploma_suggest.php?q=' + encodeURIComponent(query))
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (data) {
                        renderSuggestions(row, data || []);
                    })
                    .catch(function () {
                        hideSuggestions(row.querySelector('.related-diploma-suggestions'));
                    });
            }

            function bindRow(row) {
                var searchInput = row.querySelector('.related-diploma-search');
                var hiddenInput = row.querySelector('.related-diploma-id');
                var suggestions = row.querySelector('.related-diploma-suggestions');
                var clearButton = row.querySelector('.related-diploma-clear');
                var removeButton = row.querySelector('.related-diploma-remove-row');

                searchInput.addEventListener('input', function () {
                    if (hiddenInput.value) {
                        delete selectedIds[hiddenInput.value];
                        hiddenInput.value = '';
                        searchInput.readOnly = false;
                        toggleClearButton(row);
                    } else if (row.getAttribute('data-row-type') !== 'primary') {
                        searchInput.readOnly = false;
                    }

                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(function () {
                        fetchSuggestions(row, searchInput.value.trim());
                    }, 300);
                });

                searchInput.addEventListener('blur', function () {
                    setTimeout(function () {
                        hideSuggestions(suggestions);
                    }, 150);
                });

                if (clearButton) {
                    clearButton.addEventListener('click', function () {
                        clearSelection(row);
                    });
                }

                if (removeButton) {
                    removeButton.addEventListener('click', function () {
                        if (hiddenInput.value) {
                            delete selectedIds[hiddenInput.value];
                        }
                        row.parentNode.removeChild(row);
                    });
                }
            }

            function createAdditionalRow() {
                var row = document.createElement('div');
                row.className = 'related-diploma-row';
                row.setAttribute('data-row-type', 'additional');
                row.innerHTML = ''
                    + '<div class="related-diploma-input-wrap">'
                    + '<button type="button" class="related-diploma-clear" style="display:none;" title="Clear selection">&times;</button>'
                    + '<input type="text" class="related-diploma-search productfieldlarge" autocomplete="off" placeholder="Start typing diploma name..." />'
                    + '<input type="hidden" name="related_diploma_ids[]" class="related-diploma-id" value="" />'
                    + '<ul class="related-diploma-suggestions"></ul>'
                    + '</div>'
                    + '<button type="button" class="related-diploma-remove-row" title="Remove course">&times;</button>';

                rowsContainer.appendChild(row);
                bindRow(row);
            }

            bindRow(rowsContainer.querySelector('.related-diploma-row'));
            addRowButton.addEventListener('click', createAdditionalRow);
        })();
    </script>
</body>
</html>