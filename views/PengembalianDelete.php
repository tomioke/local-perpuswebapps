<?php

namespace PHPMaker2021\perpus;

// Page object
$PengembalianDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fpengembaliandelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fpengembaliandelete = currentForm = new ew.Form("fpengembaliandelete", "delete");
    loadjs.done("fpengembaliandelete");
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
<form name="fpengembaliandelete" id="fpengembaliandelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pengembalian">
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
<?php if ($Page->id_kembali->Visible) { // id_kembali ?>
        <th class="<?= $Page->id_kembali->headerCellClass() ?>"><span id="elh_pengembalian_id_kembali" class="pengembalian_id_kembali"><?= $Page->id_kembali->caption() ?></span></th>
<?php } ?>
<?php if ($Page->id_peminjaman->Visible) { // id_peminjaman ?>
        <th class="<?= $Page->id_peminjaman->headerCellClass() ?>"><span id="elh_pengembalian_id_peminjaman" class="pengembalian_id_peminjaman"><?= $Page->id_peminjaman->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tgl_kembali->Visible) { // tgl_kembali ?>
        <th class="<?= $Page->tgl_kembali->headerCellClass() ?>"><span id="elh_pengembalian_tgl_kembali" class="pengembalian_tgl_kembali"><?= $Page->tgl_kembali->caption() ?></span></th>
<?php } ?>
<?php if ($Page->kondisi_buku_kembali->Visible) { // kondisi_buku_kembali ?>
        <th class="<?= $Page->kondisi_buku_kembali->headerCellClass() ?>"><span id="elh_pengembalian_kondisi_buku_kembali" class="pengembalian_kondisi_buku_kembali"><?= $Page->kondisi_buku_kembali->caption() ?></span></th>
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
<?php if ($Page->id_kembali->Visible) { // id_kembali ?>
        <td <?= $Page->id_kembali->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pengembalian_id_kembali" class="pengembalian_id_kembali">
<span<?= $Page->id_kembali->viewAttributes() ?>>
<?= $Page->id_kembali->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->id_peminjaman->Visible) { // id_peminjaman ?>
        <td <?= $Page->id_peminjaman->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pengembalian_id_peminjaman" class="pengembalian_id_peminjaman">
<span<?= $Page->id_peminjaman->viewAttributes() ?>>
<?= $Page->id_peminjaman->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tgl_kembali->Visible) { // tgl_kembali ?>
        <td <?= $Page->tgl_kembali->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pengembalian_tgl_kembali" class="pengembalian_tgl_kembali">
<span<?= $Page->tgl_kembali->viewAttributes() ?>>
<?= $Page->tgl_kembali->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->kondisi_buku_kembali->Visible) { // kondisi_buku_kembali ?>
        <td <?= $Page->kondisi_buku_kembali->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pengembalian_kondisi_buku_kembali" class="pengembalian_kondisi_buku_kembali">
<span<?= $Page->kondisi_buku_kembali->viewAttributes() ?>>
<?= $Page->kondisi_buku_kembali->getViewValue() ?></span>
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
