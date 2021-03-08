<?php

namespace PHPMaker2021\perpusupdate;

// Page object
$PenerbitView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fpenerbitview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fpenerbitview = currentForm = new ew.Form("fpenerbitview", "view");
    loadjs.done("fpenerbitview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<script>
if (!ew.vars.tables.penerbit) ew.vars.tables.penerbit = <?= JsonEncode(GetClientVar("tables", "penerbit")) ?>;
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
<form name="fpenerbitview" id="fpenerbitview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="penerbit">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->id_penerbit->Visible) { // id_penerbit ?>
    <tr id="r_id_penerbit">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_penerbit_id_penerbit"><?= $Page->id_penerbit->caption() ?></span></td>
        <td data-name="id_penerbit" <?= $Page->id_penerbit->cellAttributes() ?>>
<span id="el_penerbit_id_penerbit">
<span<?= $Page->id_penerbit->viewAttributes() ?>>
<?= $Page->id_penerbit->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nama_penerbit->Visible) { // nama_penerbit ?>
    <tr id="r_nama_penerbit">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_penerbit_nama_penerbit"><?= $Page->nama_penerbit->caption() ?></span></td>
        <td data-name="nama_penerbit" <?= $Page->nama_penerbit->cellAttributes() ?>>
<span id="el_penerbit_nama_penerbit">
<span<?= $Page->nama_penerbit->viewAttributes() ?>>
<?= $Page->nama_penerbit->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->alamat_penerbit->Visible) { // alamat_penerbit ?>
    <tr id="r_alamat_penerbit">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_penerbit_alamat_penerbit"><?= $Page->alamat_penerbit->caption() ?></span></td>
        <td data-name="alamat_penerbit" <?= $Page->alamat_penerbit->cellAttributes() ?>>
<span id="el_penerbit_alamat_penerbit">
<span<?= $Page->alamat_penerbit->viewAttributes() ?>>
<?= $Page->alamat_penerbit->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php
    if (in_array("buku", explode(",", $Page->getCurrentDetailTable())) && $buku->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("buku", "TblCaption") ?>&nbsp;<?= str_replace("%c", Container("buku")->Count, $Language->phrase("DetailCount")) ?></h4>
<?php } ?>
<?php include_once "BukuGrid.php" ?>
<?php } ?>
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
