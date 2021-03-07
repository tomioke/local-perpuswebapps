<?php

namespace PHPMaker2021\perpus;

// Page object
$LevelDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fleveldelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fleveldelete = currentForm = new ew.Form("fleveldelete", "delete");
    loadjs.done("fleveldelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fleveldelete" id="fleveldelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="level">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid">
<div class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table class="table ew-table">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->id_level->Visible) { // id_level ?>
        <th class="<?= $Page->id_level->headerCellClass() ?>"><span id="elh_level_id_level" class="level_id_level"><?= $Page->id_level->caption() ?></span></th>
<?php } ?>
<?php if ($Page->level_name->Visible) { // level_name ?>
        <th class="<?= $Page->level_name->headerCellClass() ?>"><span id="elh_level_level_name" class="level_level_name"><?= $Page->level_name->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while (!$Page->Recordset->EOF) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->Recordset);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->id_level->Visible) { // id_level ?>
        <td <?= $Page->id_level->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_level_id_level" class="level_id_level">
<span<?= $Page->id_level->viewAttributes() ?>>
<?= $Page->id_level->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->level_name->Visible) { // level_name ?>
        <td <?= $Page->level_name->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_level_level_name" class="level_level_name">
<span<?= $Page->level_name->viewAttributes() ?>>
<?= $Page->level_name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
    $Page->Recordset->moveNext();
}
$Page->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
