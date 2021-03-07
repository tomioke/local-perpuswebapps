<?php

namespace PHPMaker2021\perpus;

// Page object
$BukuView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fbukuview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fbukuview = currentForm = new ew.Form("fbukuview", "view");
    loadjs.done("fbukuview");
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
<form name="fbukuview" id="fbukuview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="buku">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->cover->Visible) { // cover ?>
    <tr id="r_cover">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_buku_cover"><?= $Page->cover->caption() ?></span></td>
        <td data-name="cover" <?= $Page->cover->cellAttributes() ?>>
<span id="el_buku_cover">
<span>
<?= GetFileViewTag($Page->cover, $Page->cover->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->id_buku->Visible) { // id_buku ?>
    <tr id="r_id_buku">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_buku_id_buku"><?= $Page->id_buku->caption() ?></span></td>
        <td data-name="id_buku" <?= $Page->id_buku->cellAttributes() ?>>
<span id="el_buku_id_buku">
<span<?= $Page->id_buku->viewAttributes() ?>>
<?= $Page->id_buku->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nama_buku->Visible) { // nama_buku ?>
    <tr id="r_nama_buku">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_buku_nama_buku"><?= $Page->nama_buku->caption() ?></span></td>
        <td data-name="nama_buku" <?= $Page->nama_buku->cellAttributes() ?>>
<span id="el_buku_nama_buku">
<span<?= $Page->nama_buku->viewAttributes() ?>>
<?= $Page->nama_buku->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pengarang->Visible) { // pengarang ?>
    <tr id="r_pengarang">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_buku_pengarang"><?= $Page->pengarang->caption() ?></span></td>
        <td data-name="pengarang" <?= $Page->pengarang->cellAttributes() ?>>
<span id="el_buku_pengarang">
<span<?= $Page->pengarang->viewAttributes() ?>>
<?= $Page->pengarang->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->penerbit->Visible) { // penerbit ?>
    <tr id="r_penerbit">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_buku_penerbit"><?= $Page->penerbit->caption() ?></span></td>
        <td data-name="penerbit" <?= $Page->penerbit->cellAttributes() ?>>
<span id="el_buku_penerbit">
<span<?= $Page->penerbit->viewAttributes() ?>>
<?= $Page->penerbit->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->kode_isbn->Visible) { // kode_isbn ?>
    <tr id="r_kode_isbn">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_buku_kode_isbn"><?= $Page->kode_isbn->caption() ?></span></td>
        <td data-name="kode_isbn" <?= $Page->kode_isbn->cellAttributes() ?>>
<span id="el_buku_kode_isbn">
<span<?= $Page->kode_isbn->viewAttributes() ?>>
<?= $Page->kode_isbn->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->rangkuman->Visible) { // rangkuman ?>
    <tr id="r_rangkuman">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_buku_rangkuman"><?= $Page->rangkuman->caption() ?></span></td>
        <td data-name="rangkuman" <?= $Page->rangkuman->cellAttributes() ?>>
<span id="el_buku_rangkuman">
<span<?= $Page->rangkuman->viewAttributes() ?>>
<?= $Page->rangkuman->getViewValue() ?></span>
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
