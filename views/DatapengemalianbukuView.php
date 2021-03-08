<?php

namespace PHPMaker2021\perpusupdate;

// Page object
$DatapengemalianbukuView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fdatapengemalianbukuview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fdatapengemalianbukuview = currentForm = new ew.Form("fdatapengemalianbukuview", "view");
    loadjs.done("fdatapengemalianbukuview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.datapengemalianbuku) ew.vars.tables.datapengemalianbuku = <?= JsonEncode(GetClientVar("tables", "datapengemalianbuku")) ?>;
</script>
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
<form name="fdatapengemalianbukuview" id="fdatapengemalianbukuview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="datapengemalianbuku">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->id_kembali->Visible) { // id_kembali ?>
    <tr id="r_id_kembali">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_datapengemalianbuku_id_kembali"><?= $Page->id_kembali->caption() ?></span></td>
        <td data-name="id_kembali" <?= $Page->id_kembali->cellAttributes() ?>>
<span id="el_datapengemalianbuku_id_kembali">
<span<?= $Page->id_kembali->viewAttributes() ?>>
<?= $Page->id_kembali->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->berita_peminjaman->Visible) { // berita_peminjaman ?>
    <tr id="r_berita_peminjaman">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_datapengemalianbuku_berita_peminjaman"><?= $Page->berita_peminjaman->caption() ?></span></td>
        <td data-name="berita_peminjaman" <?= $Page->berita_peminjaman->cellAttributes() ?>>
<span id="el_datapengemalianbuku_berita_peminjaman">
<span<?= $Page->berita_peminjaman->viewAttributes() ?>>
<?= $Page->berita_peminjaman->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->id_peminjaman->Visible) { // id_peminjaman ?>
    <tr id="r_id_peminjaman">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_datapengemalianbuku_id_peminjaman"><?= $Page->id_peminjaman->caption() ?></span></td>
        <td data-name="id_peminjaman" <?= $Page->id_peminjaman->cellAttributes() ?>>
<span id="el_datapengemalianbuku_id_peminjaman">
<span<?= $Page->id_peminjaman->viewAttributes() ?>>
<?= $Page->id_peminjaman->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->id_buku->Visible) { // id_buku ?>
    <tr id="r_id_buku">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_datapengemalianbuku_id_buku"><?= $Page->id_buku->caption() ?></span></td>
        <td data-name="id_buku" <?= $Page->id_buku->cellAttributes() ?>>
<span id="el_datapengemalianbuku_id_buku">
<span<?= $Page->id_buku->viewAttributes() ?>>
<?= $Page->id_buku->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->id_anggota->Visible) { // id_anggota ?>
    <tr id="r_id_anggota">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_datapengemalianbuku_id_anggota"><?= $Page->id_anggota->caption() ?></span></td>
        <td data-name="id_anggota" <?= $Page->id_anggota->cellAttributes() ?>>
<span id="el_datapengemalianbuku_id_anggota">
<span<?= $Page->id_anggota->viewAttributes() ?>>
<?= $Page->id_anggota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tgl_peminjaman->Visible) { // tgl_peminjaman ?>
    <tr id="r_tgl_peminjaman">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_datapengemalianbuku_tgl_peminjaman"><?= $Page->tgl_peminjaman->caption() ?></span></td>
        <td data-name="tgl_peminjaman" <?= $Page->tgl_peminjaman->cellAttributes() ?>>
<span id="el_datapengemalianbuku_tgl_peminjaman">
<span<?= $Page->tgl_peminjaman->viewAttributes() ?>>
<?= $Page->tgl_peminjaman->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->rencana_tgl_kembali->Visible) { // rencana_tgl_kembali ?>
    <tr id="r_rencana_tgl_kembali">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_datapengemalianbuku_rencana_tgl_kembali"><?= $Page->rencana_tgl_kembali->caption() ?></span></td>
        <td data-name="rencana_tgl_kembali" <?= $Page->rencana_tgl_kembali->cellAttributes() ?>>
<span id="el_datapengemalianbuku_rencana_tgl_kembali">
<span<?= $Page->rencana_tgl_kembali->viewAttributes() ?>>
<?= $Page->rencana_tgl_kembali->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->kondisi_buku_peminjaman->Visible) { // kondisi_buku_peminjaman ?>
    <tr id="r_kondisi_buku_peminjaman">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_datapengemalianbuku_kondisi_buku_peminjaman"><?= $Page->kondisi_buku_peminjaman->caption() ?></span></td>
        <td data-name="kondisi_buku_peminjaman" <?= $Page->kondisi_buku_peminjaman->cellAttributes() ?>>
<span id="el_datapengemalianbuku_kondisi_buku_peminjaman">
<span<?= $Page->kondisi_buku_peminjaman->viewAttributes() ?>>
<?= $Page->kondisi_buku_peminjaman->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tgl_kembali->Visible) { // tgl_kembali ?>
    <tr id="r_tgl_kembali">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_datapengemalianbuku_tgl_kembali"><?= $Page->tgl_kembali->caption() ?></span></td>
        <td data-name="tgl_kembali" <?= $Page->tgl_kembali->cellAttributes() ?>>
<span id="el_datapengemalianbuku_tgl_kembali">
<span<?= $Page->tgl_kembali->viewAttributes() ?>>
<?= $Page->tgl_kembali->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->kondisi_buku_kembali->Visible) { // kondisi_buku_kembali ?>
    <tr id="r_kondisi_buku_kembali">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_datapengemalianbuku_kondisi_buku_kembali"><?= $Page->kondisi_buku_kembali->caption() ?></span></td>
        <td data-name="kondisi_buku_kembali" <?= $Page->kondisi_buku_kembali->cellAttributes() ?>>
<span id="el_datapengemalianbuku_kondisi_buku_kembali">
<span<?= $Page->kondisi_buku_kembali->viewAttributes() ?>>
<?= $Page->kondisi_buku_kembali->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Lama_Kembali->Visible) { // Lama_Kembali ?>
    <tr id="r_Lama_Kembali">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_datapengemalianbuku_Lama_Kembali"><?= $Page->Lama_Kembali->caption() ?></span></td>
        <td data-name="Lama_Kembali" <?= $Page->Lama_Kembali->cellAttributes() ?>>
<span id="el_datapengemalianbuku_Lama_Kembali">
<span<?= $Page->Lama_Kembali->viewAttributes() ?>>
<?= $Page->Lama_Kembali->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Lama_Pinjam->Visible) { // Lama_Pinjam ?>
    <tr id="r_Lama_Pinjam">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_datapengemalianbuku_Lama_Pinjam"><?= $Page->Lama_Pinjam->caption() ?></span></td>
        <td data-name="Lama_Pinjam" <?= $Page->Lama_Pinjam->cellAttributes() ?>>
<span id="el_datapengemalianbuku_Lama_Pinjam">
<span<?= $Page->Lama_Pinjam->viewAttributes() ?>>
<?= $Page->Lama_Pinjam->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->Terlambat->Visible) { // Terlambat ?>
    <tr id="r_Terlambat">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_datapengemalianbuku_Terlambat"><?= $Page->Terlambat->caption() ?></span></td>
        <td data-name="Terlambat" <?= $Page->Terlambat->cellAttributes() ?>>
<span id="el_datapengemalianbuku_Terlambat">
<span<?= $Page->Terlambat->viewAttributes() ?>>
<?= $Page->Terlambat->getViewValue() ?></span>
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
