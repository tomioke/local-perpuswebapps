<?php

namespace PHPMaker2021\perpus;

// Table
$level = Container("level");
?>
<?php if ($level->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_levelmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($level->id_level->Visible) { // id_level ?>
        <tr id="r_id_level">
            <td class="<?= $level->TableLeftColumnClass ?>"><?= $level->id_level->caption() ?></td>
            <td <?= $level->id_level->cellAttributes() ?>>
<span id="el_level_id_level">
<span<?= $level->id_level->viewAttributes() ?>>
<?= $level->id_level->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($level->level_name->Visible) { // level_name ?>
        <tr id="r_level_name">
            <td class="<?= $level->TableLeftColumnClass ?>"><?= $level->level_name->caption() ?></td>
            <td <?= $level->level_name->cellAttributes() ?>>
<span id="el_level_level_name">
<span<?= $level->level_name->viewAttributes() ?>>
<?= $level->level_name->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
