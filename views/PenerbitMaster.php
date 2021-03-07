<?php

namespace PHPMaker2021\perpus;

// Table
$penerbit = Container("penerbit");
?>
<?php if ($penerbit->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_penerbitmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($penerbit->nama_penerbit->Visible) { // nama_penerbit ?>
        <tr id="r_nama_penerbit">
            <td class="<?= $penerbit->TableLeftColumnClass ?>"><?= $penerbit->nama_penerbit->caption() ?></td>
            <td <?= $penerbit->nama_penerbit->cellAttributes() ?>>
<span id="el_penerbit_nama_penerbit">
<span<?= $penerbit->nama_penerbit->viewAttributes() ?>>
<?= $penerbit->nama_penerbit->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($penerbit->alamat_penerbit->Visible) { // alamat_penerbit ?>
        <tr id="r_alamat_penerbit">
            <td class="<?= $penerbit->TableLeftColumnClass ?>"><?= $penerbit->alamat_penerbit->caption() ?></td>
            <td <?= $penerbit->alamat_penerbit->cellAttributes() ?>>
<span id="el_penerbit_alamat_penerbit">
<span<?= $penerbit->alamat_penerbit->viewAttributes() ?>>
<?= $penerbit->alamat_penerbit->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
