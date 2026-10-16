<?php

function getBlogCategoryUploadDir()
{
    $dir = dirname(__DIR__) . '/../images/blog_categories/';

    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    return $dir;
}

function getBlogCategoryUploadUrl($filename)
{
    if (empty($filename)) {
        return '';
    }

    return '../images/blog_categories/' . $filename;
}

function sanitizeBlogCategoryText($db, $value)
{
    return mysqli_real_escape_string($db, trim((string) $value));
}

function getBlogCategorySanitizedFilename($file)
{
    if (empty($file['name'])) {
        return '';
    }

    return preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($file['name']));
}

function getBlogCategoryIconFields()
{
    return [
        'icon' => 'Category icon',
        'benefit_icon_1' => 'Benefit icon 1',
        'benefit_icon_2' => 'Benefit icon 2',
        'benefit_icon_3' => 'Benefit icon 3',
        'benefit_icon_4' => 'Benefit icon 4',
    ];
}

function validateBlogCategoryFilenameRules($isEdit = false, $existing = [])
{
    $errors = [];
    $uploadDir = getBlogCategoryUploadDir();
    $seenNames = [];

    foreach (getBlogCategoryIconFields() as $field => $label) {
        if (empty($_FILES[$field]['name'])) {
            continue;
        }

        $filename = getBlogCategorySanitizedFilename($_FILES[$field]);

        if ($filename === '') {
            continue;
        }

        if (isset($seenNames[$filename])) {
            $errors[] = $label . ': The same file name has already been selected for ' . $seenNames[$filename] . '.';
            continue;
        }

        $seenNames[$filename] = $label;

        if (file_exists($uploadDir . $filename)) {
            $allowReplace = $isEdit && !empty($existing[$field]) && $existing[$field] === $filename;
            if (!$allowReplace) {
                $errors[] = $label . ': An image with the name "' . $filename . '" already exists.';
            }
        }
    }

    if (!$isEdit) {
        $uploadCount = 0;
        foreach (array_keys(getBlogCategoryIconFields()) as $field) {
            if (!empty($_FILES[$field]['name'])) {
                $uploadCount++;
            }
        }

        if ($uploadCount < 5) {
            $errors[] = 'All 5 icon images are required when adding a blog category.';
        }
    }

    return $errors;
}

function validateBlogCategoryWebpUpload($file, $requiredWidth, $requiredHeight, $label, $required = true)
{
    if (empty($file['name'])) {
        if ($required) {
            return $label . ' is required.';
        }

        return '';
    }

    $fileMime = mime_content_type($file['tmp_name']);
    $imageInfo = @getimagesize($file['tmp_name']);

    if ($fileMime !== 'image/webp') {
        return $label . ' must be in webp format.';
    }

    if (!$imageInfo || (int) $imageInfo[0] !== (int) $requiredWidth || (int) $imageInfo[1] !== (int) $requiredHeight) {
        return $label . ' dimensions must be ' . $requiredWidth . ' x ' . $requiredHeight . '.';
    }

    return '';
}

function uploadBlogCategoryWebpFile($file, $uploadDir, $allowExistingFilename = '')
{
    $filename = getBlogCategorySanitizedFilename($file);

    if ($filename === '') {
        return false;
    }

    if (file_exists($uploadDir . $filename) && $filename !== $allowExistingFilename) {
        return false;
    }

    if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
        return $filename;
    }

    return false;
}

function deleteBlogCategoryImageIfUnused($db, $uploadDir, $filename, $excludeId = 0)
{
    if (empty($filename)) {
        return;
    }

    $filename = mysqli_real_escape_string($db, $filename);
    $excludeId = (int) $excludeId;
    $inUse = $db->query("SELECT id FROM blog_categories
        WHERE id <> $excludeId
        AND (
            icon = '$filename'
            OR benefit_icon_1 = '$filename'
            OR benefit_icon_2 = '$filename'
            OR benefit_icon_3 = '$filename'
            OR benefit_icon_4 = '$filename'
        )
        LIMIT 1")->fetch_array();

    if ($inUse) {
        return;
    }

    $path = $uploadDir . $filename;
    if (file_exists($path)) {
        @unlink($path);
    }
}

function getBlogCategoryAltFields()
{
    return [
        'icon_alt' => 'Icon Alt',
        'benefit_icon_1_alt' => 'Benefit 1 Icon Alt',
        'benefit_icon_2_alt' => 'Benefit 2 Icon Alt',
        'benefit_icon_3_alt' => 'Benefit 3 Icon Alt',
        'benefit_icon_4_alt' => 'Benefit 4 Icon Alt',
    ];
}

function validateBlogCategoryAltFields()
{
    $errors = [];

    foreach (getBlogCategoryAltFields() as $field => $label) {
        $value = isset($_POST[$field]) ? trim((string) $_POST[$field]) : '';
        if ($value === '') {
            $errors[] = $label . ' is required.';
        }
    }

    return $errors;
}

function collectBlogCategoryFormData($db)
{
    return [
        'tag_line' => sanitizeBlogCategoryText($db, $_POST['tag_line']),
        'benefit_1' => sanitizeBlogCategoryText($db, $_POST['benefit_1']),
        'benefit_2' => sanitizeBlogCategoryText($db, $_POST['benefit_2']),
        'benefit_3' => sanitizeBlogCategoryText($db, $_POST['benefit_3']),
        'benefit_4' => sanitizeBlogCategoryText($db, $_POST['benefit_4']),
        'description_excerpt' => sanitizeBlogCategoryText($db, $_POST['description_excerpt']),
        'description_detail' => sanitizeBlogCategoryText($db, $_POST['description_detail']),
        'short_intro' => sanitizeBlogCategoryText($db, $_POST['short_intro']),
        'icon_alt' => sanitizeBlogCategoryText($db, isset($_POST['icon_alt']) ? $_POST['icon_alt'] : ''),
        'benefit_icon_1_alt' => sanitizeBlogCategoryText($db, isset($_POST['benefit_icon_1_alt']) ? $_POST['benefit_icon_1_alt'] : ''),
        'benefit_icon_2_alt' => sanitizeBlogCategoryText($db, isset($_POST['benefit_icon_2_alt']) ? $_POST['benefit_icon_2_alt'] : ''),
        'benefit_icon_3_alt' => sanitizeBlogCategoryText($db, isset($_POST['benefit_icon_3_alt']) ? $_POST['benefit_icon_3_alt'] : ''),
        'benefit_icon_4_alt' => sanitizeBlogCategoryText($db, isset($_POST['benefit_icon_4_alt']) ? $_POST['benefit_icon_4_alt'] : ''),
        'pagetitle' => sanitizeBlogCategoryText($db, isset($_POST['pagetitle']) ? $_POST['pagetitle'] : ''),
        'metatitle' => sanitizeBlogCategoryText($db, isset($_POST['metatitle']) ? $_POST['metatitle'] : ''),
        'metadescription' => sanitizeBlogCategoryText($db, isset($_POST['metadescription']) ? $_POST['metadescription'] : ''),
        'robots' => sanitizeBlogCategoryText($db, isset($_POST['robots']) ? $_POST['robots'] : ''),
    ];
}

function validateBlogCategoryIconUploads($isEdit = false, $existing = [])
{
    $errors = [];

    $errors[] = validateBlogCategoryWebpUpload($_FILES['icon'], 65, 65, 'Category icon', !$isEdit);

    for ($i = 1; $i <= 4; $i++) {
        $errors[] = validateBlogCategoryWebpUpload(
            $_FILES['benefit_icon_' . $i],
            50,
            50,
            'Benefit icon ' . $i,
            !$isEdit
        );
    }

    $errors = array_merge(array_filter($errors), validateBlogCategoryFilenameRules($isEdit, $existing));

    return $errors;
}

function saveBlogCategoryIconUploads($uploadDir, $existing = [], $db = null, $categoryId = 0)
{
    $saved = [
        'icon' => isset($existing['icon']) ? $existing['icon'] : '',
        'benefit_icon_1' => isset($existing['benefit_icon_1']) ? $existing['benefit_icon_1'] : '',
        'benefit_icon_2' => isset($existing['benefit_icon_2']) ? $existing['benefit_icon_2'] : '',
        'benefit_icon_3' => isset($existing['benefit_icon_3']) ? $existing['benefit_icon_3'] : '',
        'benefit_icon_4' => isset($existing['benefit_icon_4']) ? $existing['benefit_icon_4'] : '',
    ];

    foreach (getBlogCategoryIconFields() as $field => $label) {
        if (empty($_FILES[$field]['name'])) {
            continue;
        }

        $allowExisting = (!empty($existing[$field])) ? $existing[$field] : '';
        $newFilename = uploadBlogCategoryWebpFile($_FILES[$field], $uploadDir, $allowExisting);

        if ($newFilename) {
            if (
                $db
                && $categoryId
                && !empty($existing[$field])
                && $existing[$field] !== $newFilename
            ) {
                deleteBlogCategoryImageIfUnused($db, $uploadDir, $existing[$field], $categoryId);
            }

            $saved[$field] = $newFilename;
        }
    }

    return $saved;
}

function renderBlogCategoryFields($values = [])
{
    $defaults = [
        'name' => '',
        'slug' => '',
        'order' => '',
        'tag_line' => '',
        'benefit_1' => '',
        'benefit_2' => '',
        'benefit_3' => '',
        'benefit_4' => '',
        'description_excerpt' => '',
        'description_detail' => '',
        'short_intro' => '',
        'icon' => '',
        'benefit_icon_1' => '',
        'benefit_icon_2' => '',
        'benefit_icon_3' => '',
        'benefit_icon_4' => '',
        'icon_alt' => '',
        'benefit_icon_1_alt' => '',
        'benefit_icon_2_alt' => '',
        'benefit_icon_3_alt' => '',
        'benefit_icon_4_alt' => '',
        'pagetitle' => '',
        'metatitle' => '',
        'metadescription' => '',
        'robots' => 'Index,Follow',
    ];

    $values = array_merge($defaults, $values);
    $isEdit = !empty($values['id']);
    ?>
    <div class="fieldrepeat">
        <p>Title </p>
        <input type="text" name="name" class="productfieldlarge" style="width:300px;" value="<?php echo htmlspecialchars($values['name']); ?>" required/>
    </div>
    <div class="clear"></div>

    <div class="fieldrepeat">
        <p>Slug : </p>
        <input type="text" id="slug" name="slug" class="productfieldlarge" style="width:300px;" value="<?php echo htmlspecialchars($values['slug']); ?>" required/>
    </div>
    <div class="clear"></div>

    <div class="fieldrepeat">
        <p>Order Number : </p>
        <input type="number" id="order" name="order" class="productfieldlarge" style="width:300px;" value="<?php echo htmlspecialchars($values['order']); ?>" required/>
    </div>
    <div class="clear"></div>

    <div class="fieldrepeat">
        <p>Tag Line : </p>
        <textarea name="tag_line" cols="60" rows="4" style="width:645px; padding:10px;"><?php echo htmlspecialchars($values['tag_line']); ?></textarea>
    </div>
    <div class="clear"></div>

    <div class="fieldrepeat">
        <p>Short Intro (for blog post listing page) : </p>
        <textarea name="short_intro" cols="60" rows="5" style="width:645px; padding:10px;"><?php echo htmlspecialchars($values['short_intro']); ?></textarea>
    </div>
    <div class="clear"></div>

    <div class="fieldrepeat">
        <p>Icon (65x65 webp)<?php echo $isEdit ? ' - leave blank to keep current icon' : ''; ?> : </p>
        <?php if ($isEdit && !empty($values['icon'])) { ?>
            <img src="<?php echo htmlspecialchars(getBlogCategoryUploadUrl($values['icon'])); ?>" alt="Current category icon" width="65" height="65" style="display:block; margin-bottom:10px;" />
        <?php } ?>
        <input type="file" name="icon" accept=".webp,image/webp" class="productfieldlarge" style="width:300px;" <?php echo $isEdit ? '' : 'required'; ?> />
    </div>
    <div class="clear"></div>
    <div class="fieldrepeat">
        <p>Icon Alt : </p>
        <input type="text" name="icon_alt" class="productfieldlarge" style="width:645px;" value="<?php echo htmlspecialchars($values['icon_alt']); ?>" required />
    </div>
    <div class="clear"></div>

    <?php for ($i = 1; $i <= 4; $i++) {
        $benefitField = 'benefit_' . $i;
        $iconField = 'benefit_icon_' . $i;
        $iconAltField = 'benefit_icon_' . $i . '_alt';
    ?>
    <div class="fieldrepeat">
        <p>Benefit <?php echo $i; ?> : </p>
        <input type="text" name="<?php echo $benefitField; ?>" class="productfieldlarge" style="width:645px;" value="<?php echo htmlspecialchars($values[$benefitField]); ?>" />
    </div>
    <div class="clear"></div>
    <div class="fieldrepeat">
        <p>Benefit <?php echo $i; ?> Icon (50x50 webp)<?php echo $isEdit ? ' - leave blank to keep current icon' : ''; ?> : </p>
        <?php if ($isEdit && !empty($values[$iconField])) { ?>
            <img src="<?php echo htmlspecialchars(getBlogCategoryUploadUrl($values[$iconField])); ?>" alt="Current benefit icon <?php echo $i; ?>" width="50" height="50" style="display:block; margin-bottom:10px;" />
        <?php } ?>
        <input type="file" name="<?php echo $iconField; ?>" accept=".webp,image/webp" class="productfieldlarge" style="width:300px;" <?php echo $isEdit ? '' : 'required'; ?> />
    </div>
    <div class="clear"></div>
    <div class="fieldrepeat">
        <p>Benefit <?php echo $i; ?> Icon Alt : </p>
        <input type="text" name="<?php echo $iconAltField; ?>" class="productfieldlarge" style="width:645px;" value="<?php echo htmlspecialchars($values[$iconAltField]); ?>" required />
    </div>
    <div class="clear"></div>
    <?php } ?>

    <div class="fieldrepeat">
        <p>Description Excerpt : </p>
        <div style="width:80%; float:left">
            <textarea id="description_excerpt" name="description_excerpt" cols="30" rows="10"><?php echo htmlspecialchars($values['description_excerpt']); ?></textarea>
        </div>
    </div>
    <div class="clear"></div>

    <div class="fieldrepeat">
        <p>Description Detail : </p>
        <div style="width:80%; float:left">
            <textarea id="description_detail" name="description_detail" cols="30" rows="15"><?php echo htmlspecialchars($values['description_detail']); ?></textarea>
        </div>
    </div>
    <div class="clear"></div>

    <div class="fieldrepeat">
        <p>Page Title : </p>
        <textarea name="pagetitle" id="pagetitle" cols="60" rows="10"><?php echo htmlspecialchars($values['pagetitle']); ?></textarea>
    </div>
    <div class="clear"></div>

    <div class="fieldrepeat">
        <p>Meta Keywords : </p>
        <textarea name="metatitle" id="metatitle" cols="60" rows="10"><?php echo htmlspecialchars($values['metatitle']); ?></textarea>
    </div>
    <div class="clear"></div>

    <div class="fieldrepeat" style="margin-top:10px;">
        <p>Meta Description : </p>
        <textarea name="metadescription" id="metadescription" cols="60" rows="10"><?php echo htmlspecialchars($values['metadescription']); ?></textarea>
    </div>
    <div class="clear"></div>

    <div class="fieldrepeat" style="margin-top:10px;">
        <p>Robots : </p>
        <label><input type="radio" name="robots" value="Index,Nofollow" <?php echo ($values['robots'] === 'Index,Nofollow') ? 'checked' : ''; ?> />Index</label>
        <label><input type="radio" name="robots" value="Noindex,Follow" <?php echo ($values['robots'] === 'Noindex,Follow') ? 'checked' : ''; ?> />Follow</label>
        <label><input type="radio" name="robots" value="Noindex,Nofollow" <?php echo ($values['robots'] === 'Noindex,Nofollow') ? 'checked' : ''; ?> />Disallow</label>
        <label><input type="radio" name="robots" value="Index,Follow" <?php echo ($values['robots'] === 'Index,Follow' || $values['robots'] === '') ? 'checked' : ''; ?> />Allow</label>
    </div>
    <div class="clear"></div>
    <?php
}

function renderBlogCategoryErrors($errors)
{
    if (empty($errors)) {
        return;
    }
    ?>
    <div style="max-width:713px; margin:0 auto 20px; padding:16px 20px; border:1px solid #d9534f; background:#fdf2f2; border-radius:6px; color:#7a1f1f;">
        <p style="margin:0 0 12px; font-size:16px; font-weight:bold;">Please fix the following errors:</p>
        <ul style="margin:0; padding:0 0 0 20px; font-size:15px; line-height:1.7;">
            <?php foreach ($errors as $error) { ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php } ?>
        </ul>
    </div>
    <?php
}

function initBlogCategoryEditors()
{
    ?>
    <script>
    CKEDITOR.replace('description_excerpt', {
        versionCheck: false,
        extraPlugins: 'uploadimage,image2',
        removePlugins: 'image',
        height: 250,
        filebrowserUploadUrl: 'upload.php',
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('description_detail', {
        versionCheck: false,
        extraPlugins: 'uploadimage,image2',
        removePlugins: 'image',
        height: 350,
        filebrowserUploadUrl: 'upload.php',
        filebrowserUploadMethod: 'form'
    });
    </script>
    <?php
}
