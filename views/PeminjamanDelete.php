<?php

namespace PHPMaker2021\perpus;

// Page object
$PeminjamanDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fpeminjamandelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fpeminjamandelete = currentForm = new ew.Form("fpeminjamandelete", "delete");
    loadjs.done("fpeminjamandelete");
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
<form name="fpeminjamandelete" id="fpeminjamandelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="peminjaman">
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
<?php if ($Page->id_peminjaman->Visible) { // id_peminjaman ?>
        <th class="<?= $Page->id_peminjaman->headerCellClass() ?>"><span id="elh_peminjaman_id_peminjaman" class="peminjaman_id_peminjaman"><?= $Page->id_peminjaman->caption() ?></span></th>
<?php } ?>
<?php if ($Page->berita_peminjaman->Visible) { // berita_peminjaman ?>
        <th class="<?= $Page->berita_peminjaman->headerCellClass() ?>"><span id="elh_peminjaman_berita_peminjaman" class="peminjaman_berita_peminjaman"><?= $Page->berita_peminjaman->caption() ?></span></th>
<?php } ?>
<?php if ($Page->id_buku->Visible) { // id_buku ?>
        <th class="<?= $Page->id_buku->headerCellClass() ?>"><span id="elh_peminjaman_id_buku" class="peminjaman_id_buku"><?= $Page->id_buku->caption() ?></span></th>
<?php } ?>
<?php if ($Page->id_anggota->Visible) { // id_anggota ?>
        <th class="<?= $Page->id_anggota->headerCellClass() ?>"><span id="elh_peminjaman_id_anggota" class="peminjaman_id_anggota"><?= $Page->id_anggota->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tgl_peminjaman->Visible) { // tgl_peminjaman ?>
        <th class="<?= $Page->tgl_peminjaman->headerCellClass() ?>"><span id="elh_peminjaman_tgl_peminjaman" class="peminjaman_tgl_peminjaman"><?= $Page->tgl_peminjaman->caption() ?></span></th>
<?php } ?>
<?php if ($Page->rencana_tgl_kembali->Visible) { // rencana_tgl_kembali ?>
        <th class="<?= $Page->rencana_tgl_kembali->headerCellClass() ?>"><span id="elh_peminjaman_rencana_tgl_kembali" class="peminjaman_rencana_tgl_kembali"><?= $Page->rencana_tgl_kembali->caption() ?></span></th>
<?php } ?>
<?php if ($Page->kondisi_buku_peminjaman->Visible) { // kondisi_buku_peminjaman ?>
        <th class="<?= $Page->kondisi_buku_peminjaman->headerCellClass() ?>"><span id="elh_peminjaman_kondisi_buku_peminjaman" class="peminjaman_kondisi_buku_peminjaman"><?= $Page->kondisi_buku_peminjaman->caption() ?></span></th>
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
<?php if ($Page->id_peminjaman->Visible) { // id_peminjaman ?>
        <td <?= $Page->id_peminjaman->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_id_peminjaman" class="peminjaman_id_peminjaman">
<span<?= $Page->id_peminjaman->viewAttributes() ?>>
<?= $Page->id_peminjaman->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->berita_peminjaman->Visible) { // berita_peminjaman ?>
        <td <?= $Page->berita_peminjaman->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_berita_peminjaman" class="peminjaman_berita_peminjaman">
<span<?= $Page->berita_peminjaman->viewAttributes() ?>>
<?= $Page->berita_peminjaman->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->id_buku->Visible) { // id_buku ?>
        <td <?= $Page->id_buku->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_id_buku" class="peminjaman_id_buku">
<span<?= $Page->id_buku->viewAttributes() ?>>
<?= $Page->id_buku->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->id_anggota->Visible) { // id_anggota ?>
        <td <?= $Page->id_anggota->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_id_anggota" class="peminjaman_id_anggota">
<span<?= $Page->id_anggota->viewAttributes() ?>>
<?= $Page->id_anggota->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tgl_peminjaman->Visible) { // tgl_peminjaman ?>
        <td <?= $Page->tgl_peminjaman->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_tgl_peminjaman" class="peminjaman_tgl_peminjaman">
<span<?= $Page->tgl_peminjaman->viewAttributes() ?>>
<?= $Page->tgl_peminjaman->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->rencana_tgl_kembali->Visible) { // rencana_tgl_kembali ?>
        <td <?= $Page->rencana_tgl_kembali->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_rencana_tgl_kembali" class="peminjaman_rencana_tgl_kembali">
<span<?= $Page->rencana_tgl_kembali->viewAttributes() ?>>
<?= $Page->rencana_tgl_kembali->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->kondisi_buku_peminjaman->Visible) { // kondisi_buku_peminjaman ?>
        <td <?= $Page->kondisi_buku_peminjaman->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_kondisi_buku_peminjaman" class="peminjaman_kondisi_buku_peminjaman">
<span<?= $Page->kondisi_buku_peminjaman->viewAttributes() ?>>
<?= $Page->kondisi_buku_peminjaman->getViewValue() ?></span>
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
