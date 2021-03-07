<?php

namespace PHPMaker2021\perpus;

// Page object
$Permission2View = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fpermission2view;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fpermission2view = currentForm = new ew.Form("fpermission2view", "view");
    loadjs.done("fpermission2view");
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
<form name="fpermission2view" id="fpermission2view" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="permission2">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->table_name->Visible) { // table_name ?>
    <tr id="r_table_name">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_permission2_table_name"><?= $Page->table_name->caption() ?></span></td>
        <td data-name="table_name" <?= $Page->table_name->cellAttributes() ?>>
<span id="el_permission2_table_name">
<span<?= $Page->table_name->viewAttributes() ?>>
<?= $Page->table_name->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->id_level->Visible) { // id_level ?>
    <tr id="r_id_level">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_permission2_id_level"><?= $Page->id_level->caption() ?></span></td>
        <td data-name="id_level" <?= $Page->id_level->cellAttributes() ?>>
<span id="el_permission2_id_level">
<span<?= $Page->id_level->viewAttributes() ?>>
<?= $Page->id_level->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_permission->Visible) { // permission ?>
    <tr id="r__permission">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_permission2__permission"><?= $Page->_permission->caption() ?></span></td>
        <td data-name="_permission" <?= $Page->_permission->cellAttributes() ?>>
<span id="el_permission2__permission">
<span<?= $Page->_permission->viewAttributes() ?>>
<?= $Page->_permission->getViewValue() ?></span>
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
