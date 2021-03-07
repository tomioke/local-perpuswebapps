<?php

namespace PHPMaker2021\perpus;

// Page object
$PenerbitDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fpenerbitdelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fpenerbitdelete = currentForm = new ew.Form("fpenerbitdelete", "delete");
    loadjs.done("fpenerbitdelete");
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
<form name="fpenerbitdelete" id="fpenerbitdelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="penerbit">
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
<?php if ($Page->nama_penerbit->Visible) { // nama_penerbit ?>
        <th class="<?= $Page->nama_penerbit->headerCellClass() ?>"><span id="elh_penerbit_nama_penerbit" class="penerbit_nama_penerbit"><?= $Page->nama_penerbit->caption() ?></span></th>
<?php } ?>
<?php if ($Page->alamat_penerbit->Visible) { // alamat_penerbit ?>
        <th class="<?= $Page->alamat_penerbit->headerCellClass() ?>"><span id="elh_penerbit_alamat_penerbit" class="penerbit_alamat_penerbit"><?= $Page->alamat_penerbit->caption() ?></span></th>
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
<?php if ($Page->nama_penerbit->Visible) { // nama_penerbit ?>
        <td <?= $Page->nama_penerbit->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_penerbit_nama_penerbit" class="penerbit_nama_penerbit">
<span<?= $Page->nama_penerbit->viewAttributes() ?>>
<?= $Page->nama_penerbit->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->alamat_penerbit->Visible) { // alamat_penerbit ?>
        <td <?= $Page->alamat_penerbit->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_penerbit_alamat_penerbit" class="penerbit_alamat_penerbit">
<span<?= $Page->alamat_penerbit->viewAttributes() ?>>
<?= $Page->alamat_penerbit->getViewValue() ?></span>
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
