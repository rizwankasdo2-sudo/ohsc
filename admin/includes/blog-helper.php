<?php

function getBlogUploadDir()
{
    return dirname(__DIR__) . '/../images/blog/';
}

function getBlogImageFields()
{
    return [
        'image' => [
            'width' => 686,
            'height' => 401,
            'label' => 'Main Image (686 x 401 webp)',
            'column' => 'image',
        ],
        'image_272_170' => [
            'width' => 272,
            'height' => 170,
            'label' => 'Image (272 x 170 webp)',
            'column' => 'image_272_170',
        ],
        'image_240_181' => [
            'width' => 240,
            'height' => 181,
            'label' => 'Image (240 x 181 webp)',
            'column' => 'image_240_181',
        ],
        'image_80_64' => [
            'width' => 80,
            'height' => 64,
            'label' => 'Image (80 x 64 webp)',
            'column' => 'image_80_64',
        ],
    ];
}

function getBlogSanitizedFilename($file)
{
    if (empty($file['name'])) {
        return '';
    }

    return preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($file['name']));
}

function validateBlogWebpUpload($file, $width, $height, $label, $required = true)
{
    if (empty($file['name'])) {
        if ($required) {
            return $label . ' is required.';
        }

        return '';
    }

    $filename = getBlogSanitizedFilename($file);
    if (strtolower(pathinfo($filename, PATHINFO_EXTENSION)) !== 'webp') {
        return $label . ' must be in webp format.';
    }

    $fileMime = @mime_content_type($file['tmp_name']);
    $imageInfo = @getimagesize($file['tmp_name']);

    if ($fileMime !== 'image/webp') {
        return $label . ' must be in webp format.';
    }

    if (!$imageInfo || (int) $imageInfo[0] !== (int) $width || (int) $imageInfo[1] !== (int) $height) {
        return $label . ' dimensions must be ' . $width . ' x ' . $height . '.';
    }

    return '';
}

function blogImageFilenameInUse($db, $filename, $excludeId = 0)
{
    if ($filename === '') {
        return false;
    }

    $filename = mysqli_real_escape_string($db, $filename);
    $excludeId = (int) $excludeId;
    $conditions = [];

    foreach (getBlogImageFields() as $config) {
        $conditions[] = $config['column'] . " = '$filename'";
    }

    $where = '(' . implode(' OR ', $conditions) . ')';
    if ($excludeId > 0) {
        $where .= " AND id <> $excludeId";
    }

    return (bool) $db->query("SELECT id FROM blog WHERE $where LIMIT 1")->fetch_array();
}

function validateBlogImageFilenameRules($excludeId = 0, $existing = [], $requireAll = true)
{
    global $db;

    $errors = [];
    $uploadDir = getBlogUploadDir();
    $seenNames = [];

    foreach (getBlogImageFields() as $field => $config) {
        if (empty($_FILES[$field]['name'])) {
            continue;
        }

        $filename = getBlogSanitizedFilename($_FILES[$field]);
        if ($filename === '') {
            continue;
        }

        if (isset($seenNames[$filename])) {
            $errors[] = $config['label'] . ': The same file name has already been selected for ' . $seenNames[$filename] . '. Each image must have a unique name.';
            continue;
        }

        $seenNames[$filename] = $config['label'];

        $allowReplaceExisting = !empty($existing[$config['column']]) && $existing[$config['column']] === $filename;
        $samePostConflict = false;

        if (!$allowReplaceExisting) {
            foreach ($existing as $existingColumn => $existingFilename) {
                if ($existingColumn !== $config['column'] && !empty($existingFilename) && $existingFilename === $filename) {
                    $errors[] = $config['label'] . ': The file name "' . $filename . '" is already used by another image on this post. Each image must have a unique name.';
                    $samePostConflict = true;
                    break;
                }
            }
        }

        if ($samePostConflict) {
            continue;
        }

        if (file_exists($uploadDir . $filename) && !$allowReplaceExisting) {
            $errors[] = $config['label'] . ': An image with the name "' . $filename . '" already exists. Each image must have a unique name.';
        }

        if (!$allowReplaceExisting && blogImageFilenameInUse($db, $filename, $excludeId)) {
            $errors[] = $config['label'] . ': The file name "' . $filename . '" is already used by another blog post. Each image must have a unique name.';
        }
    }

    if ($requireAll) {
        $uploadCount = 0;
        foreach (getBlogImageFields() as $field => $config) {
            if (!empty($_FILES[$field]['name'])) {
                $uploadCount++;
            }
        }

        if ($uploadCount < count(getBlogImageFields())) {
            $errors[] = 'All 4 blog images are required.';
        }
    } else {
        foreach (getBlogImageFields() as $field => $config) {
            $column = $config['column'];
            $hasExisting = !empty($existing[$column]);
            $hasUpload = !empty($_FILES[$field]['name']);

            if (!$hasExisting && !$hasUpload) {
                $errors[] = $config['label'] . ' is required.';
            }
        }
    }

    return $errors;
}

function validateBlogImageUploads($excludeId = 0, $existing = [], $requireAll = true)
{
    $errors = [];

    foreach (getBlogImageFields() as $field => $config) {
        $column = $config['column'];
        $required = $requireAll || empty($existing[$column]);

        $errors[] = validateBlogWebpUpload(
            $_FILES[$field],
            $config['width'],
            $config['height'],
            $config['label'],
            $required
        );
    }

    $errors = array_merge(array_filter($errors), validateBlogImageFilenameRules($excludeId, $existing, $requireAll));

    return $errors;
}

function uploadBlogImageFile($file, $uploadDir, $allowExistingFilename = '')
{
    $filename = getBlogSanitizedFilename($file);
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

function deleteBlogImageIfUnused($db, $uploadDir, $filename, $excludeId = 0)
{
    if (empty($filename)) {
        return;
    }

    if (blogImageFilenameInUse($db, $filename, $excludeId)) {
        return;
    }

    $path = $uploadDir . $filename;
    if (file_exists($path)) {
        @unlink($path);
    }
}

function collectBlogExtraFormData($db)
{
    $minutesRead = isset($_POST['minutes_read']) ? (int) $_POST['minutes_read'] : 0;
    if ($minutesRead < 1 || $minutesRead > 100) {
        $minutesRead = 1;
    }

    return [
        'short_content' => mysqli_real_escape_string($db, isset($_POST['short_content']) ? $_POST['short_content'] : ''),
        'minutes_read' => $minutesRead,
        'popular' => (!empty($_POST['popular']) && $_POST['popular'] == '1') ? 1 : 0,
        'featured' => (!empty($_POST['featured']) && $_POST['featured'] == '1') ? 1 : 0,
    ];
}

function validateBlogPopularFeaturedLimits($db, $popular, $featured, $excludeId = 0)
{
    $errors = [];
    $excludeId = (int) $excludeId;
    $excludeSql = $excludeId > 0 ? " AND id <> $excludeId" : '';

    if ((int) $featured === 1) {
        $result = $db->query("SELECT COUNT(*) AS total FROM blog WHERE featured = 1 $excludeSql");
        $row = $result ? $result->fetch_array() : null;
        $featuredCount = $row ? (int) $row['total'] : 0;

        if ($featuredCount >= 4) {
            $errors[] = 'A maximum of 4 blog posts can be marked as Featured. Set another post to No before marking this one as Featured.';
        }
    }

    return $errors;
}

function saveBlogImageUploads($uploadDir, $existing = [], $db = null, $blogId = 0)
{
    $saved = [];

    foreach (getBlogImageFields() as $field => $config) {
        $column = $config['column'];
        $saved[$column] = isset($existing[$column]) ? $existing[$column] : '';
    }

    foreach (getBlogImageFields() as $field => $config) {
        $column = $config['column'];

        if (empty($_FILES[$field]['name'])) {
            continue;
        }

        $allowExisting = !empty($existing[$column]) ? $existing[$column] : '';
        $newFilename = uploadBlogImageFile($_FILES[$field], $uploadDir, $allowExisting);

        if ($newFilename) {
            if ($db && $blogId && !empty($existing[$column]) && $existing[$column] !== $newFilename) {
                deleteBlogImageIfUnused($db, $uploadDir, $existing[$column], $blogId);
            }

            $saved[$column] = $newFilename;
        }
    }

    return $saved;
}

function blogImageUploadsComplete($uploadedImages)
{
    foreach (getBlogImageFields() as $config) {
        if (empty($uploadedImages[$config['column']])) {
            return false;
        }
    }

    return true;
}

function renderBlogExtraFields($values = [], $isEdit = false)
{
    $defaults = [
        'short_content' => '',
        'minutes_read' => 1,
        'popular' => 0,
        'featured' => 0,
        'image' => '',
        'image_272_170' => '',
        'image_240_181' => '',
        'image_80_64' => '',
    ];

    $values = array_merge($defaults, $values);
    ?>
    <div class="fieldrepeat">
        <p>Short Content : </p>
        <div style="width:80%; float:left">
            <textarea id="short_content" name="short_content" cols="30" rows="10"><?php echo $values['short_content']; ?></textarea>
        </div>
    </div>
    <div class="clear"></div>

    <div class="fieldrepeat">
        <p>Minutes Read : </p>
        <select name="minutes_read" id="minutes_read" class="category" style="width:200px;" required>
            <?php for ($i = 1; $i <= 100; $i++) { ?>
                <option value="<?php echo $i; ?>" <?php echo ((int) $values['minutes_read'] === $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="clear"></div>

    <div class="fieldrepeat">
        <p>Popular : </p>
        <label><input type="radio" name="popular" value="1" <?php echo ((int) $values['popular'] === 1) ? 'checked' : ''; ?> /> Yes</label>
        &nbsp;&nbsp;&nbsp;
        <label><input type="radio" name="popular" value="0" <?php echo ((int) $values['popular'] !== 1) ? 'checked' : ''; ?> /> No</label>
    </div>
    <div class="clear"></div>

    <div class="fieldrepeat">
        <p>Featured : </p>
        <label><input type="radio" name="featured" value="1" <?php echo ((int) $values['featured'] === 1) ? 'checked' : ''; ?> /> Yes</label>
        &nbsp;&nbsp;&nbsp;
        <label><input type="radio" name="featured" value="0" <?php echo ((int) $values['featured'] !== 1) ? 'checked' : ''; ?> /> No</label>
        <p style="font-weight:normal; font-size:12px; margin:8px 0 0;">Maximum 4 posts can be marked Featured.</p>
    </div>
    <div class="clear"></div>

    <?php foreach (getBlogImageFields() as $field => $config) {
        $column = $config['column'];
        $currentImage = isset($values[$column]) ? $values[$column] : '';
        $imageRequired = !$isEdit || empty($currentImage);
    ?>
    <div class="fieldrepeat">
        <p><?php echo htmlspecialchars($config['label']); ?> : </p>
        <?php if (!empty($currentImage)) { ?>
            <img src="../images/blog/<?php echo htmlspecialchars($currentImage); ?>" alt="<?php echo htmlspecialchars($config['label']); ?>" width="<?php echo (int) $config['width']; ?>" height="<?php echo (int) $config['height']; ?>" style="display:block; margin-bottom:10px;" />
            <?php if ($isEdit) { ?>
                <p style="font-weight:normal; font-size:12px; margin:0 0 8px;">Leave empty to keep the current image.</p>
            <?php } ?>
        <?php } ?>
        <input type="file" id="<?php echo htmlspecialchars($field); ?>" name="<?php echo htmlspecialchars($field); ?>" accept=".webp,image/webp" class="productfieldlarge" style="width:300px;"<?php echo $imageRequired ? ' required' : ''; ?> />
    </div>
    <div class="clear"></div>
    <?php } ?>
    <?php
}

function normalizeBlogSlug($slugInput)
{
    return str_replace(' ', '-', strtolower(trim($slugInput)));
}

function validateNewBlogSlug($db, $slug)
{
    $slug = trim($slug);

    if ($slug === '') {
        return [
            'valid' => false,
            'message' => 'Slug cannot be empty.',
        ];
    }

    $escapedSlug = mysqli_real_escape_string($db, $slug);
    $result = $db->query("SELECT id FROM blog WHERE slug = '$escapedSlug' LIMIT 1");

    if ($result && $result->num_rows > 0) {
        return [
            'valid' => false,
            'message' => 'This slug already exists in blog posts.',
        ];
    }

    return ['valid' => true];
}

function renderBlogFormErrors($errors)
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

function initBlogShortContentEditor()
{
    ?>
    <script>
    CKEDITOR.replace('short_content', {
        versionCheck: false,
        extraPlugins: 'uploadimage,image2',
        removePlugins: 'image',
        height: 250,
        filebrowserUploadUrl: 'upload.php',
        filebrowserUploadMethod: 'form'
    });
    </script>
    <?php
}
