<?php

namespace PHPMaker2021\perpusupdate;

// Page object
$AnggotaDelete = &$Page;
?>
<script>
var currentForm, currentPageID;
var fanggotadelete;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "delete";
    fanggotadelete = currentForm = new ew.Form("fanggotadelete", "delete");
    loadjs.done("fanggotadelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<script>
if (!ew.vars.tables.anggota) ew.vars.tables.anggota = <?= JsonEncode(GetClientVar("tables", "anggota")) ?>;
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fanggotadelete" id="fanggotadelete" class="form-inline ew-form ew-delete-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="anggota">
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
<?php if ($Page->id_anggota->Visible) { // id_anggota ?>
        <th class="<?= $Page->id_anggota->headerCellClass() ?>"><span id="elh_anggota_id_anggota" class="anggota_id_anggota"><?= $Page->id_anggota->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nama_anggota->Visible) { // nama_anggota ?>
        <th class="<?= $Page->nama_anggota->headerCellClass() ?>"><span id="elh_anggota_nama_anggota" class="anggota_nama_anggota"><?= $Page->nama_anggota->caption() ?></span></th>
<?php } ?>
<?php if ($Page->alamat->Visible) { // alamat ?>
        <th class="<?= $Page->alamat->headerCellClass() ?>"><span id="elh_anggota_alamat" class="anggota_alamat"><?= $Page->alamat->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tgl_lahir->Visible) { // tgl_lahir ?>
        <th class="<?= $Page->tgl_lahir->headerCellClass() ?>"><span id="elh_anggota_tgl_lahir" class="anggota_tgl_lahir"><?= $Page->tgl_lahir->caption() ?></span></th>
<?php } ?>
<?php if ($Page->tmp_lahir->Visible) { // tmp_lahir ?>
        <th class="<?= $Page->tmp_lahir->headerCellClass() ?>"><span id="elh_anggota_tmp_lahir" class="anggota_tmp_lahir"><?= $Page->tmp_lahir->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
        <th class="<?= $Page->_username->headerCellClass() ?>"><span id="elh_anggota__username" class="anggota__username"><?= $Page->_username->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_password->Visible) { // password ?>
        <th class="<?= $Page->_password->headerCellClass() ?>"><span id="elh_anggota__password" class="anggota__password"><?= $Page->_password->caption() ?></span></th>
<?php } ?>
<?php if ($Page->id_level->Visible) { // id_level ?>
        <th class="<?= $Page->id_level->headerCellClass() ?>"><span id="elh_anggota_id_level" class="anggota_id_level"><?= $Page->id_level->caption() ?></span></th>
<?php } ?>
<?php if ($Page->no_handphone->Visible) { // no_handphone ?>
        <th class="<?= $Page->no_handphone->headerCellClass() ?>"><span id="elh_anggota_no_handphone" class="anggota_no_handphone"><?= $Page->no_handphone->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_email->Visible) { // email ?>
        <th class="<?= $Page->_email->headerCellClass() ?>"><span id="elh_anggota__email" class="anggota__email"><?= $Page->_email->caption() ?></span></th>
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
<?php if ($Page->id_anggota->Visible) { // id_anggota ?>
        <td <?= $Page->id_anggota->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_id_anggota" class="anggota_id_anggota">
<span<?= $Page->id_anggota->viewAttributes() ?>>
<?= $Page->id_anggota->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->nama_anggota->Visible) { // nama_anggota ?>
        <td <?= $Page->nama_anggota->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_nama_anggota" class="anggota_nama_anggota">
<span<?= $Page->nama_anggota->viewAttributes() ?>>
<?= $Page->nama_anggota->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->alamat->Visible) { // alamat ?>
        <td <?= $Page->alamat->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_alamat" class="anggota_alamat">
<span<?= $Page->alamat->viewAttributes() ?>>
<?= $Page->alamat->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tgl_lahir->Visible) { // tgl_lahir ?>
        <td <?= $Page->tgl_lahir->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_tgl_lahir" class="anggota_tgl_lahir">
<span<?= $Page->tgl_lahir->viewAttributes() ?>>
<?= $Page->tgl_lahir->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->tmp_lahir->Visible) { // tmp_lahir ?>
        <td <?= $Page->tmp_lahir->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_tmp_lahir" class="anggota_tmp_lahir">
<span<?= $Page->tmp_lahir->viewAttributes() ?>>
<?= $Page->tmp_lahir->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
        <td <?= $Page->_username->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota__username" class="anggota__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_password->Visible) { // password ?>
        <td <?= $Page->_password->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota__password" class="anggota__password">
<span<?= $Page->_password->viewAttributes() ?>>
<?= $Page->_password->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->id_level->Visible) { // id_level ?>
        <td <?= $Page->id_level->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_id_level" class="anggota_id_level">
<span<?= $Page->id_level->viewAttributes() ?>>
<?= $Page->id_level->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->no_handphone->Visible) { // no_handphone ?>
        <td <?= $Page->no_handphone->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_no_handphone" class="anggota_no_handphone">
<span<?= $Page->no_handphone->viewAttributes() ?>>
<?= $Page->no_handphone->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->_email->Visible) { // email ?>
        <td <?= $Page->_email->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota__email" class="anggota__email">
<span<?= $Page->_email->viewAttributes() ?>>
<?= $Page->_email->getViewValue() ?></span>
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
