<?php

namespace PHPMaker2021\perpusupdate;

// Table
$pengarang = Container("pengarang");
?>
<?php if ($pengarang->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_pengarangmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($pengarang->nama_pengarang->Visible) { // nama_pengarang ?>
        <tr id="r_nama_pengarang">
            <td class="<?= $pengarang->TableLeftColumnClass ?>"><?= $pengarang->nama_pengarang->caption() ?></td>
            <td <?= $pengarang->nama_pengarang->cellAttributes() ?>>
<span id="el_pengarang_nama_pengarang">
<span<?= $pengarang->nama_pengarang->viewAttributes() ?>>
<?= $pengarang->nama_pengarang->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
