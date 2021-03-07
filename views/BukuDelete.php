<?php

namespace PHPMaker2021\perpus;

// Page object
$BukuDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fbukudelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fbukudelete = currentForm = new ew.Form("fbukudelete", "delete");
    loadjs.done("fbukudelete");
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
<form name="fbukudelete" id="fbukudelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="buku">
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
<?php if ($Page->cover->Visible) { // cover ?>
        <th class="<?= $Page->cover->headerCellClass() ?>"><span id="elh_buku_cover" class="buku_cover"><?= $Page->cover->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nama_buku->Visible) { // nama_buku ?>
        <th class="<?= $Page->nama_buku->headerCellClass() ?>"><span id="elh_buku_nama_buku" class="buku_nama_buku"><?= $Page->nama_buku->caption() ?></span></th>
<?php } ?>
<?php if ($Page->pengarang->Visible) { // pengarang ?>
        <th class="<?= $Page->pengarang->headerCellClass() ?>"><span id="elh_buku_pengarang" class="buku_pengarang"><?= $Page->pengarang->caption() ?></span></th>
<?php } ?>
<?php if ($Page->penerbit->Visible) { // penerbit ?>
        <th class="<?= $Page->penerbit->headerCellClass() ?>"><span id="elh_buku_penerbit" class="buku_penerbit"><?= $Page->penerbit->caption() ?></span></th>
<?php } ?>
<?php if ($Page->kode_isbn->Visible) { // kode_isbn ?>
        <th class="<?= $Page->kode_isbn->headerCellClass() ?>"><span id="elh_buku_kode_isbn" class="buku_kode_isbn"><?= $Page->kode_isbn->caption() ?></span></th>
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
<?php if ($Page->cover->Visible) { // cover ?>
        <td <?= $Page->cover->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_buku_cover" class="buku_cover">
<span>
<?= GetFileViewTag($Page->cover, $Page->cover->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->nama_buku->Visible) { // nama_buku ?>
        <td <?= $Page->nama_buku->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_buku_nama_buku" class="buku_nama_buku">
<span<?= $Page->nama_buku->viewAttributes() ?>>
<?= $Page->nama_buku->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->pengarang->Visible) { // pengarang ?>
        <td <?= $Page->pengarang->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_buku_pengarang" class="buku_pengarang">
<span<?= $Page->pengarang->viewAttributes() ?>>
<?= $Page->pengarang->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->penerbit->Visible) { // penerbit ?>
        <td <?= $Page->penerbit->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_buku_penerbit" class="buku_penerbit">
<span<?= $Page->penerbit->viewAttributes() ?>>
<?= $Page->penerbit->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->kode_isbn->Visible) { // kode_isbn ?>
        <td <?= $Page->kode_isbn->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_buku_kode_isbn" class="buku_kode_isbn">
<span<?= $Page->kode_isbn->viewAttributes() ?>>
<?= $Page->kode_isbn->getViewValue() ?></span>
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
