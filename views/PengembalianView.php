<?php

namespace PHPMaker2021\perpus;

// Page object
$PengembalianView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fpengembalianview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fpengembalianview = currentForm = new ew.Form("fpengembalianview", "view");
    loadjs.done("fpengembalianview");
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
<form name="fpengembalianview" id="fpengembalianview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pengembalian">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->id_kembali->Visible) { // id_kembali ?>
    <tr id="r_id_kembali">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pengembalian_id_kembali"><?= $Page->id_kembali->caption() ?></span></td>
        <td data-name="id_kembali" <?= $Page->id_kembali->cellAttributes() ?>>
<span id="el_pengembalian_id_kembali">
<span<?= $Page->id_kembali->viewAttributes() ?>>
<?= $Page->id_kembali->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->id_peminjaman->Visible) { // id_peminjaman ?>
    <tr id="r_id_peminjaman">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pengembalian_id_peminjaman"><?= $Page->id_peminjaman->caption() ?></span></td>
        <td data-name="id_peminjaman" <?= $Page->id_peminjaman->cellAttributes() ?>>
<span id="el_pengembalian_id_peminjaman">
<span<?= $Page->id_peminjaman->viewAttributes() ?>>
<?= $Page->id_peminjaman->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tgl_kembali->Visible) { // tgl_kembali ?>
    <tr id="r_tgl_kembali">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pengembalian_tgl_kembali"><?= $Page->tgl_kembali->caption() ?></span></td>
        <td data-name="tgl_kembali" <?= $Page->tgl_kembali->cellAttributes() ?>>
<span id="el_pengembalian_tgl_kembali">
<span<?= $Page->tgl_kembali->viewAttributes() ?>>
<?= $Page->tgl_kembali->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->kondisi_buku_kembali->Visible) { // kondisi_buku_kembali ?>
    <tr id="r_kondisi_buku_kembali">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pengembalian_kondisi_buku_kembali"><?= $Page->kondisi_buku_kembali->caption() ?></span></td>
        <td data-name="kondisi_buku_kembali" <?= $Page->kondisi_buku_kembali->cellAttributes() ?>>
<span id="el_pengembalian_kondisi_buku_kembali">
<span<?= $Page->kondisi_buku_kembali->viewAttributes() ?>>
<?= $Page->kondisi_buku_kembali->getViewValue() ?></span>
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
