<?php
if (!isset($related_diplomas)) {
    $related_diplomas = [];
}
?>
<div class="fieldrepeat">
    <p>Related Courses : </p>
    <div id="related-diploma-rows">
        <?php if (empty($related_diplomas)) { ?>
        <div class="related-diploma-row" data-row-type="primary">
            <div class="related-diploma-input-wrap">
                <input type="text" class="related-diploma-search productfieldlarge" autocomplete="off" placeholder="Start typing diploma name..." />
                <input type="hidden" name="related_diploma_ids[]" class="related-diploma-id" value="" />
                <ul class="related-diploma-suggestions"></ul>
            </div>
        </div>
        <?php } else { ?>
        <?php foreach ($related_diplomas as $index => $related_diploma) { ?>
        <div class="related-diploma-row" data-row-type="<?php echo ($index === 0) ? 'primary' : 'additional'; ?>">
            <div class="related-diploma-input-wrap">
                <?php if ($index > 0) { ?>
                <button type="button" class="related-diploma-clear" title="Clear selection">&times;</button>
                <?php } ?>
                <input type="text" class="related-diploma-search productfieldlarge" autocomplete="off" placeholder="Start typing diploma name..." value="<?php echo htmlspecialchars($related_diploma['name'], ENT_QUOTES, 'UTF-8'); ?>" <?php echo ($index > 0) ? 'readonly="readonly"' : ''; ?> />
                <input type="hidden" name="related_diploma_ids[]" class="related-diploma-id" value="<?php echo (int) $related_diploma['diplomaid']; ?>" />
                <ul class="related-diploma-suggestions"></ul>
            </div>
            <?php if ($index > 0) { ?>
            <button type="button" class="related-diploma-remove-row" title="Remove course">&times;</button>
            <?php } ?>
        </div>
        <?php } ?>
        <?php } ?>
    </div>
    <button type="button" id="add-related-diploma-row">Associate Another Course</button>
</div>
<div class="clear">
</div>
