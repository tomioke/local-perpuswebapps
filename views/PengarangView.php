<?php

namespace PHPMaker2021\perpus;

// Page object
$PengarangView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fpengarangview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fpengarangview = currentForm = new ew.Form("fpengarangview", "view");
    loadjs.done("fpengarangview");
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
<form name="fpengarangview" id="fpengarangview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pengarang">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->id_pengarang->Visible) { // id_pengarang ?>
    <tr id="r_id_pengarang">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pengarang_id_pengarang"><?= $Page->id_pengarang->caption() ?></span></td>
        <td data-name="id_pengarang" <?= $Page->id_pengarang->cellAttributes() ?>>
<span id="el_pengarang_id_pengarang">
<span<?= $Page->id_pengarang->viewAttributes() ?>>
<?= $Page->id_pengarang->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nama_pengarang->Visible) { // nama_pengarang ?>
    <tr id="r_nama_pengarang">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pengarang_nama_pengarang"><?= $Page->nama_pengarang->caption() ?></span></td>
        <td data-name="nama_pengarang" <?= $Page->nama_pengarang->cellAttributes() ?>>
<span id="el_pengarang_nama_pengarang">
<span<?= $Page->nama_pengarang->viewAttributes() ?>>
<?= $Page->nama_pengarang->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php
    if (in_array("buku", explode(",", $Page->getCurrentDetailTable())) && $buku->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("buku", "TblCaption") ?>&nbsp;<?= str_replace("%c", $Page->buku_Count, $Language->phrase("DetailCount")) ?></h4>
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
