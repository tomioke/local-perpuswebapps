<?php

namespace PHPMaker2021\perpus;

// Page object
$AnggotaView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fanggotaview;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "view";
    fanggotaview = currentForm = new ew.Form("fanggotaview", "view");
    loadjs.done("fanggotaview");
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
<form name="fanggotaview" id="fanggotaview" class="form-inline ew-form ew-view-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="anggota">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->id_anggota->Visible) { // id_anggota ?>
    <tr id="r_id_anggota">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_anggota_id_anggota"><?= $Page->id_anggota->caption() ?></span></td>
        <td data-name="id_anggota" <?= $Page->id_anggota->cellAttributes() ?>>
<span id="el_anggota_id_anggota">
<span<?= $Page->id_anggota->viewAttributes() ?>>
<?= $Page->id_anggota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->nama_anggota->Visible) { // nama_anggota ?>
    <tr id="r_nama_anggota">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_anggota_nama_anggota"><?= $Page->nama_anggota->caption() ?></span></td>
        <td data-name="nama_anggota" <?= $Page->nama_anggota->cellAttributes() ?>>
<span id="el_anggota_nama_anggota">
<span<?= $Page->nama_anggota->viewAttributes() ?>>
<?= $Page->nama_anggota->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->alamat->Visible) { // alamat ?>
    <tr id="r_alamat">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_anggota_alamat"><?= $Page->alamat->caption() ?></span></td>
        <td data-name="alamat" <?= $Page->alamat->cellAttributes() ?>>
<span id="el_anggota_alamat">
<span<?= $Page->alamat->viewAttributes() ?>>
<?= $Page->alamat->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tgl_lahir->Visible) { // tgl_lahir ?>
    <tr id="r_tgl_lahir">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_anggota_tgl_lahir"><?= $Page->tgl_lahir->caption() ?></span></td>
        <td data-name="tgl_lahir" <?= $Page->tgl_lahir->cellAttributes() ?>>
<span id="el_anggota_tgl_lahir">
<span<?= $Page->tgl_lahir->viewAttributes() ?>>
<?= $Page->tgl_lahir->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->tmp_lahir->Visible) { // tmp_lahir ?>
    <tr id="r_tmp_lahir">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_anggota_tmp_lahir"><?= $Page->tmp_lahir->caption() ?></span></td>
        <td data-name="tmp_lahir" <?= $Page->tmp_lahir->cellAttributes() ?>>
<span id="el_anggota_tmp_lahir">
<span<?= $Page->tmp_lahir->viewAttributes() ?>>
<?= $Page->tmp_lahir->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <tr id="r__username">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_anggota__username"><?= $Page->_username->caption() ?></span></td>
        <td data-name="_username" <?= $Page->_username->cellAttributes() ?>>
<span id="el_anggota__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_password->Visible) { // password ?>
    <tr id="r__password">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_anggota__password"><?= $Page->_password->caption() ?></span></td>
        <td data-name="_password" <?= $Page->_password->cellAttributes() ?>>
<span id="el_anggota__password">
<span<?= $Page->_password->viewAttributes() ?>>
<?= $Page->_password->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->id_level->Visible) { // id_level ?>
    <tr id="r_id_level">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_anggota_id_level"><?= $Page->id_level->caption() ?></span></td>
        <td data-name="id_level" <?= $Page->id_level->cellAttributes() ?>>
<span id="el_anggota_id_level">
<span<?= $Page->id_level->viewAttributes() ?>>
<?= $Page->id_level->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->no_handphone->Visible) { // no_handphone ?>
    <tr id="r_no_handphone">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_anggota_no_handphone"><?= $Page->no_handphone->caption() ?></span></td>
        <td data-name="no_handphone" <?= $Page->no_handphone->cellAttributes() ?>>
<span id="el_anggota_no_handphone">
<span<?= $Page->no_handphone->viewAttributes() ?>>
<?= $Page->no_handphone->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_email->Visible) { // email ?>
    <tr id="r__email">
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_anggota__email"><?= $Page->_email->caption() ?></span></td>
        <td data-name="_email" <?= $Page->_email->cellAttributes() ?>>
<span id="el_anggota__email">
<span<?= $Page->_email->viewAttributes() ?>>
<?= $Page->_email->getViewValue() ?></span>
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
