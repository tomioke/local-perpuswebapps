<?php

namespace PHPMaker2021\perpus;

// Table
$anggota = Container("anggota");
?>
<?php if ($anggota->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_anggotamaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($anggota->id_anggota->Visible) { // id_anggota ?>
        <tr id="r_id_anggota">
            <td class="<?= $anggota->TableLeftColumnClass ?>"><?= $anggota->id_anggota->caption() ?></td>
            <td <?= $anggota->id_anggota->cellAttributes() ?>>
<span id="el_anggota_id_anggota">
<span<?= $anggota->id_anggota->viewAttributes() ?>>
<?= $anggota->id_anggota->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($anggota->nama_anggota->Visible) { // nama_anggota ?>
        <tr id="r_nama_anggota">
            <td class="<?= $anggota->TableLeftColumnClass ?>"><?= $anggota->nama_anggota->caption() ?></td>
            <td <?= $anggota->nama_anggota->cellAttributes() ?>>
<span id="el_anggota_nama_anggota">
<span<?= $anggota->nama_anggota->viewAttributes() ?>>
<?= $anggota->nama_anggota->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($anggota->alamat->Visible) { // alamat ?>
        <tr id="r_alamat">
            <td class="<?= $anggota->TableLeftColumnClass ?>"><?= $anggota->alamat->caption() ?></td>
            <td <?= $anggota->alamat->cellAttributes() ?>>
<span id="el_anggota_alamat">
<span<?= $anggota->alamat->viewAttributes() ?>>
<?= $anggota->alamat->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($anggota->tgl_lahir->Visible) { // tgl_lahir ?>
        <tr id="r_tgl_lahir">
            <td class="<?= $anggota->TableLeftColumnClass ?>"><?= $anggota->tgl_lahir->caption() ?></td>
            <td <?= $anggota->tgl_lahir->cellAttributes() ?>>
<span id="el_anggota_tgl_lahir">
<span<?= $anggota->tgl_lahir->viewAttributes() ?>>
<?= $anggota->tgl_lahir->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($anggota->tmp_lahir->Visible) { // tmp_lahir ?>
        <tr id="r_tmp_lahir">
            <td class="<?= $anggota->TableLeftColumnClass ?>"><?= $anggota->tmp_lahir->caption() ?></td>
            <td <?= $anggota->tmp_lahir->cellAttributes() ?>>
<span id="el_anggota_tmp_lahir">
<span<?= $anggota->tmp_lahir->viewAttributes() ?>>
<?= $anggota->tmp_lahir->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($anggota->_username->Visible) { // username ?>
        <tr id="r__username">
            <td class="<?= $anggota->TableLeftColumnClass ?>"><?= $anggota->_username->caption() ?></td>
            <td <?= $anggota->_username->cellAttributes() ?>>
<span id="el_anggota__username">
<span<?= $anggota->_username->viewAttributes() ?>>
<?= $anggota->_username->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($anggota->_password->Visible) { // password ?>
        <tr id="r__password">
            <td class="<?= $anggota->TableLeftColumnClass ?>"><?= $anggota->_password->caption() ?></td>
            <td <?= $anggota->_password->cellAttributes() ?>>
<span id="el_anggota__password">
<span<?= $anggota->_password->viewAttributes() ?>>
<?= $anggota->_password->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($anggota->id_level->Visible) { // id_level ?>
        <tr id="r_id_level">
            <td class="<?= $anggota->TableLeftColumnClass ?>"><?= $anggota->id_level->caption() ?></td>
            <td <?= $anggota->id_level->cellAttributes() ?>>
<span id="el_anggota_id_level">
<span<?= $anggota->id_level->viewAttributes() ?>>
<?= $anggota->id_level->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($anggota->no_handphone->Visible) { // no_handphone ?>
        <tr id="r_no_handphone">
            <td class="<?= $anggota->TableLeftColumnClass ?>"><?= $anggota->no_handphone->caption() ?></td>
            <td <?= $anggota->no_handphone->cellAttributes() ?>>
<span id="el_anggota_no_handphone">
<span<?= $anggota->no_handphone->viewAttributes() ?>>
<?= $anggota->no_handphone->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($anggota->_email->Visible) { // email ?>
        <tr id="r__email">
            <td class="<?= $anggota->TableLeftColumnClass ?>"><?= $anggota->_email->caption() ?></td>
            <td <?= $anggota->_email->cellAttributes() ?>>
<span id="el_anggota__email">
<span<?= $anggota->_email->viewAttributes() ?>>
<?= $anggota->_email->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
