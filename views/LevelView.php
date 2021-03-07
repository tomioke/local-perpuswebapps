<?php

namespace PHPMaker2021\perpus;

// Page object
$LevelView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var flevelview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    flevelview = currentForm = new ew.Form("flevelview", "view");
    loadjs.done("flevelview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="flevelview" id="flevelview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="level">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->id_level->Visible) { // id_level ?>
    <tr id="r_id_level">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_level_id_level"><?= $Page->id_level->caption() ?></span></td>
        <td data-name="id_level" <?= $Page->id_level->cellAttributes() ?>>
<span id="el_level_id_level">
<span<?= $Page->id_level->viewAttributes() ?>>
<?= $Page->id_level->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->level_name->Visible) { // level_name ?>
    <tr id="r_level_name">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_level_level_name"><?= $Page->level_name->caption() ?></span></td>
        <td data-name="level_name" <?= $Page->level_name->cellAttributes() ?>>
<span id="el_level_level_name">
<span<?= $Page->level_name->viewAttributes() ?>>
<?= $Page->level_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
