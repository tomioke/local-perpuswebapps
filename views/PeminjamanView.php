<?php

namespace PHPMaker2021\perpus;

// Page object
$PeminjamanView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fpeminjamanview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fpeminjamanview = currentForm = new ew.Form("fpeminjamanview", "view");
    loadjs.done("fpeminjamanview");
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
<form name="fpeminjamanview" id="fpeminjamanview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="peminjaman">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->id_peminjaman->Visible) { // id_peminjaman ?>
    <tr id="r_id_peminjaman">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_peminjaman_id_peminjaman"><?= $Page->id_peminjaman->caption() ?></span></td>
        <td data-name="id_peminjaman" <?= $Page->id_peminjaman->cellAttributes() ?>>
<span id="el_peminjaman_id_peminjaman">
<span<?= $Page->id_peminjaman->viewAttributes() ?>>
<?= $Page->id_peminjaman->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->berita_peminjaman->Visible) { // berita_peminjaman ?>
    <tr id="r_berita_peminjaman">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_peminjaman_berita_peminjaman"><?= $Page->berita_peminjaman->caption() ?></span></td>
        <td data-name="berita_peminjaman" <?= $Page->berita_peminjaman->cellAttributes() ?>>
<span id="el_peminjaman_berita_peminjaman">
<span<?= $Page->berita_peminjaman->viewAttributes() ?>>
<?= $Page->berita_peminjaman->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->id_buku->Visible) { // id_buku ?>
    <tr id="r_id_buku">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_peminjaman_id_buku"><?= $Page->id_buku->caption() ?></span></td>
        <td data-name="id_buku" <?= $Page->id_buku->cellAttributes() ?>>
<span id="el_peminjaman_id_buku">
<span<?= $Page->id_buku->viewAttributes() ?>>
<?= $Page->id_buku->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->id_anggota->Visible) { // id_anggota ?>
    <tr id="r_id_anggota">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_peminjaman_id_anggota"><?= $Page->id_anggota->caption() ?></span></td>
        <td data-name="id_anggota" <?= $Page->id_anggota->cellAttributes() ?>>
<span id="el_peminjaman_id_anggota">
<span<?= $Page->id_anggota->viewAttributes() ?>>
<?= $Page->id_anggota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tgl_peminjaman->Visible) { // tgl_peminjaman ?>
    <tr id="r_tgl_peminjaman">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_peminjaman_tgl_peminjaman"><?= $Page->tgl_peminjaman->caption() ?></span></td>
        <td data-name="tgl_peminjaman" <?= $Page->tgl_peminjaman->cellAttributes() ?>>
<span id="el_peminjaman_tgl_peminjaman">
<span<?= $Page->tgl_peminjaman->viewAttributes() ?>>
<?= $Page->tgl_peminjaman->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->rencana_tgl_kembali->Visible) { // rencana_tgl_kembali ?>
    <tr id="r_rencana_tgl_kembali">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_peminjaman_rencana_tgl_kembali"><?= $Page->rencana_tgl_kembali->caption() ?></span></td>
        <td data-name="rencana_tgl_kembali" <?= $Page->rencana_tgl_kembali->cellAttributes() ?>>
<span id="el_peminjaman_rencana_tgl_kembali">
<span<?= $Page->rencana_tgl_kembali->viewAttributes() ?>>
<?= $Page->rencana_tgl_kembali->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->kondisi_buku_peminjaman->Visible) { // kondisi_buku_peminjaman ?>
    <tr id="r_kondisi_buku_peminjaman">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_peminjaman_kondisi_buku_peminjaman"><?= $Page->kondisi_buku_peminjaman->caption() ?></span></td>
        <td data-name="kondisi_buku_peminjaman" <?= $Page->kondisi_buku_peminjaman->cellAttributes() ?>>
<span id="el_peminjaman_kondisi_buku_peminjaman">
<span<?= $Page->kondisi_buku_peminjaman->viewAttributes() ?>>
<?= $Page->kondisi_buku_peminjaman->getViewValue() ?></span>
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
