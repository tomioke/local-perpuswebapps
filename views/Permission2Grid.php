<?php

namespace PHPMaker2021\perpus;

// Set up and run Grid object
$Grid = Container("Permission2Grid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
if (!ew.vars.tables.permission2) ew.vars.tables.permission2 = <?= JsonEncode(GetClientVar("tables", "permission2")) ?>;
var currentForm, currentPageID;
var fpermission2grid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    fpermission2grid = new ew.Form("fpermission2grid", "grid");
    fpermission2grid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var fields = ew.vars.tables.permission2.fields;
    fpermission2grid.addFields([
        ["table_name", [fields.table_name.required ? ew.Validators.required(fields.table_name.caption) : null], fields.table_name.isInvalid],
        ["id_level", [fields.id_level.required ? ew.Validators.required(fields.id_level.caption) : null, ew.Validators.integer], fields.id_level.isInvalid],
        ["_permission", [fields._permission.required ? ew.Validators.required(fields._permission.caption) : null], fields._permission.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fpermission2grid,
            fobj = f.getForm(),
            $fobj = $(fobj),
            $k = $fobj.find("#" + f.formKeyCountName), // Get key_count
            rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1,
            startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
        for (var i = startcnt; i <= rowcnt; i++) {
            var rowIndex = ($k[0]) ? String(i) : "";
            f.setInvalid(rowIndex);
        }
    });

    // Validate form
    fpermission2grid.validate = function () {
        if (!this.validateRequired)
            return true; // Ignore validation
        var fobj = this.getForm(),
            $fobj = $(fobj);
        if ($fobj.find("#confirm").val() == "confirm")
            return true;
        var addcnt = 0,
            $k = $fobj.find("#" + this.formKeyCountName), // Get key_count
            rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1,
            startcnt = (rowcnt == 0) ? 0 : 1, // Check rowcnt == 0 => Inline-Add
            gridinsert = ["insert", "gridinsert"].includes($fobj.find("#action").val()) && $k[0];
        for (var i = startcnt; i <= rowcnt; i++) {
            var rowIndex = ($k[0]) ? String(i) : "";
            $fobj.data("rowindex", rowIndex);
            var checkrow = (gridinsert) ? !this.emptyRow(rowIndex) : true;
            if (checkrow) {
                addcnt++;

            // Validate fields
            if (!this.validateFields(rowIndex))
                return false;

            // Call Form_CustomValidate event
            if (!this.customValidate(fobj)) {
                this.focus();
                return false;
            }
            } // End Grid Add checking
        }
        return true;
    }

    // Check empty row
    fpermission2grid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "table_name", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "id_level", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "_permission", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fpermission2grid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpermission2grid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fpermission2grid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> permission2">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="fpermission2grid" class="ew-form ew-list-form form-inline">
<div id="gmp_permission2" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_permission2grid" class="table ew-table"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Grid->RowType = ROWTYPE_HEADER;

// Render list options
$Grid->renderListOptions();

// Render list options (header, left)
$Grid->ListOptions->render("header", "left");
?>
<?php if ($Grid->table_name->Visible) { // table_name ?>
        <th data-name="table_name" class="<?= $Grid->table_name->headerCellClass() ?>"><div id="elh_permission2_table_name" class="permission2_table_name"><?= $Grid->renderSort($Grid->table_name) ?></div></th>
<?php } ?>
<?php if ($Grid->id_level->Visible) { // id_level ?>
        <th data-name="id_level" class="<?= $Grid->id_level->headerCellClass() ?>"><div id="elh_permission2_id_level" class="permission2_id_level"><?= $Grid->renderSort($Grid->id_level) ?></div></th>
<?php } ?>
<?php if ($Grid->_permission->Visible) { // permission ?>
        <th data-name="_permission" class="<?= $Grid->_permission->headerCellClass() ?>"><div id="elh_permission2__permission" class="permission2__permission"><?= $Grid->renderSort($Grid->_permission) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Grid->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody>
<?php
$Grid->StartRecord = 1;
$Grid->StopRecord = $Grid->TotalRecords; // Show all records

// Restore number of post back records
if ($CurrentForm && ($Grid->isConfirm() || $Grid->EventCancelled)) {
    $CurrentForm->Index = -1;
    if ($CurrentForm->hasValue($Grid->FormKeyCountName) && ($Grid->isGridAdd() || $Grid->isGridEdit() || $Grid->isConfirm())) {
        $Grid->KeyCount = $CurrentForm->getValue($Grid->FormKeyCountName);
        $Grid->StopRecord = $Grid->StartRecord + $Grid->KeyCount - 1;
    }
}
$Grid->RecordCount = $Grid->StartRecord - 1;
if ($Grid->Recordset && !$Grid->Recordset->EOF) {
    // Nothing to do
} elseif (!$Grid->AllowAddDeleteRow && $Grid->StopRecord == 0) {
    $Grid->StopRecord = $Grid->GridAddRowCount;
}

// Initialize aggregate
$Grid->RowType = ROWTYPE_AGGREGATEINIT;
$Grid->resetAttributes();
$Grid->renderRow();
if ($Grid->isGridAdd())
    $Grid->RowIndex = 0;
if ($Grid->isGridEdit())
    $Grid->RowIndex = 0;
while ($Grid->RecordCount < $Grid->StopRecord) {
    $Grid->RecordCount++;
    if ($Grid->RecordCount >= $Grid->StartRecord) {
        $Grid->RowCount++;
        if ($Grid->isGridAdd() || $Grid->isGridEdit() || $Grid->isConfirm()) {
            $Grid->RowIndex++;
            $CurrentForm->Index = $Grid->RowIndex;
            if ($CurrentForm->hasValue($Grid->FormActionName) && ($Grid->isConfirm() || $Grid->EventCancelled)) {
                $Grid->RowAction = strval($CurrentForm->getValue($Grid->FormActionName));
            } elseif ($Grid->isGridAdd()) {
                $Grid->RowAction = "insert";
            } else {
                $Grid->RowAction = "";
            }
        }

        // Set up key count
        $Grid->KeyCount = $Grid->RowIndex;

        // Init row class and style
        $Grid->resetAttributes();
        $Grid->CssClass = "";
        if ($Grid->isGridAdd()) {
            if ($Grid->CurrentMode == "copy") {
                $Grid->loadRowValues($Grid->Recordset); // Load row values
                $Grid->OldKey = $Grid->getKey(true); // Get from CurrentValue
            } else {
                $Grid->loadRowValues(); // Load default values
                $Grid->OldKey = "";
            }
        } else {
            $Grid->loadRowValues($Grid->Recordset); // Load row values
            $Grid->OldKey = $Grid->getKey(true); // Get from CurrentValue
        }
        $Grid->setKey($Grid->OldKey);
        $Grid->RowType = ROWTYPE_VIEW; // Render view
        if ($Grid->isGridAdd()) { // Grid add
            $Grid->RowType = ROWTYPE_ADD; // Render add
        }
        if ($Grid->isGridAdd() && $Grid->EventCancelled && !$CurrentForm->hasValue("k_blankrow")) { // Insert failed
            $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
        }
        if ($Grid->isGridEdit()) { // Grid edit
            if ($Grid->EventCancelled) {
                $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
            }
            if ($Grid->RowAction == "insert") {
                $Grid->RowType = ROWTYPE_ADD; // Render add
            } else {
                $Grid->RowType = ROWTYPE_EDIT; // Render edit
            }
        }
        if ($Grid->isGridEdit() && ($Grid->RowType == ROWTYPE_EDIT || $Grid->RowType == ROWTYPE_ADD) && $Grid->EventCancelled) { // Update failed
            $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
        }
        if ($Grid->RowType == ROWTYPE_EDIT) { // Edit row
            $Grid->EditRowCount++;
        }
        if ($Grid->isConfirm()) { // Confirm row
            $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
        }

        // Set up row id / data-rowindex
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_permission2", "data-rowtype" => $Grid->RowType]);

        // Render row
        $Grid->renderRow();

        // Render list options
        $Grid->renderListOptions();

        // Skip delete row / empty row for confirm page
        if ($Grid->RowAction != "delete" && $Grid->RowAction != "insertdelete" && !($Grid->RowAction == "insert" && $Grid->isConfirm() && $Grid->emptyRow())) {
?>
    <tr <?= $Grid->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Grid->ListOptions->render("body", "left", $Grid->RowCount);
?>
    <?php if ($Grid->table_name->Visible) { // table_name ?>
        <td data-name="table_name" <?= $Grid->table_name->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_permission2_table_name" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_table_name"
        name="x<?= $Grid->RowIndex ?>_table_name"
        class="form-control ew-select<?= $Grid->table_name->isInvalidClass() ?>"
        data-select2-id="permission2_x<?= $Grid->RowIndex ?>_table_name"
        data-table="permission2"
        data-field="x_table_name"
        data-value-separator="<?= $Grid->table_name->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->table_name->getPlaceHolder()) ?>"
        <?= $Grid->table_name->editAttributes() ?>>
        <?= $Grid->table_name->selectOptionListHtml("x{$Grid->RowIndex}_table_name") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->table_name->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='permission2_x<?= $Grid->RowIndex ?>_table_name']"),
        options = { name: "x<?= $Grid->RowIndex ?>_table_name", selectId: "permission2_x<?= $Grid->RowIndex ?>_table_name", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.permission2.fields.table_name.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="permission2" data-field="x_table_name" data-hidden="1" name="o<?= $Grid->RowIndex ?>_table_name" id="o<?= $Grid->RowIndex ?>_table_name" value="<?= HtmlEncode($Grid->table_name->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_permission2_table_name" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>_table_name"
        name="x<?= $Grid->RowIndex ?>_table_name"
        class="form-control ew-select<?= $Grid->table_name->isInvalidClass() ?>"
        data-select2-id="permission2_x<?= $Grid->RowIndex ?>_table_name"
        data-table="permission2"
        data-field="x_table_name"
        data-value-separator="<?= $Grid->table_name->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->table_name->getPlaceHolder()) ?>"
        <?= $Grid->table_name->editAttributes() ?>>
        <?= $Grid->table_name->selectOptionListHtml("x{$Grid->RowIndex}_table_name") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->table_name->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='permission2_x<?= $Grid->RowIndex ?>_table_name']"),
        options = { name: "x<?= $Grid->RowIndex ?>_table_name", selectId: "permission2_x<?= $Grid->RowIndex ?>_table_name", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.permission2.fields.table_name.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_permission2_table_name">
<span<?= $Grid->table_name->viewAttributes() ?>>
<?= $Grid->table_name->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="permission2" data-field="x_table_name" data-hidden="1" name="fpermission2grid$x<?= $Grid->RowIndex ?>_table_name" id="fpermission2grid$x<?= $Grid->RowIndex ?>_table_name" value="<?= HtmlEncode($Grid->table_name->FormValue) ?>">
<input type="hidden" data-table="permission2" data-field="x_table_name" data-hidden="1" name="fpermission2grid$o<?= $Grid->RowIndex ?>_table_name" id="fpermission2grid$o<?= $Grid->RowIndex ?>_table_name" value="<?= HtmlEncode($Grid->table_name->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->id_level->Visible) { // id_level ?>
        <td data-name="id_level" <?= $Grid->id_level->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->id_level->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_permission2_id_level" class="form-group">
<span<?= $Grid->id_level->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id_level->getDisplayValue($Grid->id_level->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_id_level" name="x<?= $Grid->RowIndex ?>_id_level" value="<?= HtmlEncode($Grid->id_level->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_permission2_id_level" class="form-group">
<input type="<?= $Grid->id_level->getInputTextType() ?>" data-table="permission2" data-field="x_id_level" name="x<?= $Grid->RowIndex ?>_id_level" id="x<?= $Grid->RowIndex ?>_id_level" size="30" placeholder="<?= HtmlEncode($Grid->id_level->getPlaceHolder()) ?>" value="<?= $Grid->id_level->EditValue ?>"<?= $Grid->id_level->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->id_level->getErrorMessage() ?></div>
</span>
<?php } ?>
<input type="hidden" data-table="permission2" data-field="x_id_level" data-hidden="1" name="o<?= $Grid->RowIndex ?>_id_level" id="o<?= $Grid->RowIndex ?>_id_level" value="<?= HtmlEncode($Grid->id_level->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->id_level->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_permission2_id_level" class="form-group">
<span<?= $Grid->id_level->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id_level->getDisplayValue($Grid->id_level->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_id_level" name="x<?= $Grid->RowIndex ?>_id_level" value="<?= HtmlEncode($Grid->id_level->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_permission2_id_level" class="form-group">
<input type="<?= $Grid->id_level->getInputTextType() ?>" data-table="permission2" data-field="x_id_level" name="x<?= $Grid->RowIndex ?>_id_level" id="x<?= $Grid->RowIndex ?>_id_level" size="30" placeholder="<?= HtmlEncode($Grid->id_level->getPlaceHolder()) ?>" value="<?= $Grid->id_level->EditValue ?>"<?= $Grid->id_level->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->id_level->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_permission2_id_level">
<span<?= $Grid->id_level->viewAttributes() ?>>
<?= $Grid->id_level->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="permission2" data-field="x_id_level" data-hidden="1" name="fpermission2grid$x<?= $Grid->RowIndex ?>_id_level" id="fpermission2grid$x<?= $Grid->RowIndex ?>_id_level" value="<?= HtmlEncode($Grid->id_level->FormValue) ?>">
<input type="hidden" data-table="permission2" data-field="x_id_level" data-hidden="1" name="fpermission2grid$o<?= $Grid->RowIndex ?>_id_level" id="fpermission2grid$o<?= $Grid->RowIndex ?>_id_level" value="<?= HtmlEncode($Grid->id_level->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->_permission->Visible) { // permission ?>
        <td data-name="_permission" <?= $Grid->_permission->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_permission2__permission" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>__permission"
        name="x<?= $Grid->RowIndex ?>__permission"
        class="form-control ew-select<?= $Grid->_permission->isInvalidClass() ?>"
        data-select2-id="permission2_x<?= $Grid->RowIndex ?>__permission"
        data-table="permission2"
        data-field="x__permission"
        data-value-separator="<?= $Grid->_permission->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->_permission->getPlaceHolder()) ?>"
        <?= $Grid->_permission->editAttributes() ?>>
        <?= $Grid->_permission->selectOptionListHtml("x{$Grid->RowIndex}__permission") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->_permission->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='permission2_x<?= $Grid->RowIndex ?>__permission']"),
        options = { name: "x<?= $Grid->RowIndex ?>__permission", selectId: "permission2_x<?= $Grid->RowIndex ?>__permission", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.permission2.fields._permission.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="permission2" data-field="x__permission" data-hidden="1" name="o<?= $Grid->RowIndex ?>__permission" id="o<?= $Grid->RowIndex ?>__permission" value="<?= HtmlEncode($Grid->_permission->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_permission2__permission" class="form-group">
    <select
        id="x<?= $Grid->RowIndex ?>__permission"
        name="x<?= $Grid->RowIndex ?>__permission"
        class="form-control ew-select<?= $Grid->_permission->isInvalidClass() ?>"
        data-select2-id="permission2_x<?= $Grid->RowIndex ?>__permission"
        data-table="permission2"
        data-field="x__permission"
        data-value-separator="<?= $Grid->_permission->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->_permission->getPlaceHolder()) ?>"
        <?= $Grid->_permission->editAttributes() ?>>
        <?= $Grid->_permission->selectOptionListHtml("x{$Grid->RowIndex}__permission") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->_permission->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='permission2_x<?= $Grid->RowIndex ?>__permission']"),
        options = { name: "x<?= $Grid->RowIndex ?>__permission", selectId: "permission2_x<?= $Grid->RowIndex ?>__permission", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.permission2.fields._permission.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_permission2__permission">
<span<?= $Grid->_permission->viewAttributes() ?>>
<?= $Grid->_permission->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="permission2" data-field="x__permission" data-hidden="1" name="fpermission2grid$x<?= $Grid->RowIndex ?>__permission" id="fpermission2grid$x<?= $Grid->RowIndex ?>__permission" value="<?= HtmlEncode($Grid->_permission->FormValue) ?>">
<input type="hidden" data-table="permission2" data-field="x__permission" data-hidden="1" name="fpermission2grid$o<?= $Grid->RowIndex ?>__permission" id="fpermission2grid$o<?= $Grid->RowIndex ?>__permission" value="<?= HtmlEncode($Grid->_permission->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowCount);
?>
    </tr>
<?php if ($Grid->RowType == ROWTYPE_ADD || $Grid->RowType == ROWTYPE_EDIT) { ?>
<script>
loadjs.ready(["fpermission2grid","load"], function () {
    fpermission2grid.updateLists(<?= $Grid->RowIndex ?>);
});
</script>
<?php } ?>
<?php
    }
    } // End delete row checking
    if (!$Grid->isGridAdd() || $Grid->CurrentMode == "copy")
        if (!$Grid->Recordset->EOF) {
            $Grid->Recordset->moveNext();
        }
}
?>
<?php
    if ($Grid->CurrentMode == "add" || $Grid->CurrentMode == "copy" || $Grid->CurrentMode == "edit") {
        $Grid->RowIndex = '$rowindex$';
        $Grid->loadRowValues();

        // Set row properties
        $Grid->resetAttributes();
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_permission2", "data-rowtype" => ROWTYPE_ADD]);
        $Grid->RowAttrs->appendClass("ew-template");
        $Grid->RowType = ROWTYPE_ADD;

        // Render row
        $Grid->renderRow();

        // Render list options
        $Grid->renderListOptions();
        $Grid->StartRowCount = 0;
?>
    <tr <?= $Grid->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Grid->ListOptions->render("body", "left", $Grid->RowIndex);
?>
    <?php if ($Grid->table_name->Visible) { // table_name ?>
        <td data-name="table_name">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_permission2_table_name" class="form-group permission2_table_name">
    <select
        id="x<?= $Grid->RowIndex ?>_table_name"
        name="x<?= $Grid->RowIndex ?>_table_name"
        class="form-control ew-select<?= $Grid->table_name->isInvalidClass() ?>"
        data-select2-id="permission2_x<?= $Grid->RowIndex ?>_table_name"
        data-table="permission2"
        data-field="x_table_name"
        data-value-separator="<?= $Grid->table_name->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->table_name->getPlaceHolder()) ?>"
        <?= $Grid->table_name->editAttributes() ?>>
        <?= $Grid->table_name->selectOptionListHtml("x{$Grid->RowIndex}_table_name") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->table_name->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='permission2_x<?= $Grid->RowIndex ?>_table_name']"),
        options = { name: "x<?= $Grid->RowIndex ?>_table_name", selectId: "permission2_x<?= $Grid->RowIndex ?>_table_name", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.permission2.fields.table_name.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_permission2_table_name" class="form-group permission2_table_name">
<span<?= $Grid->table_name->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->table_name->getDisplayValue($Grid->table_name->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="permission2" data-field="x_table_name" data-hidden="1" name="x<?= $Grid->RowIndex ?>_table_name" id="x<?= $Grid->RowIndex ?>_table_name" value="<?= HtmlEncode($Grid->table_name->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="permission2" data-field="x_table_name" data-hidden="1" name="o<?= $Grid->RowIndex ?>_table_name" id="o<?= $Grid->RowIndex ?>_table_name" value="<?= HtmlEncode($Grid->table_name->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->id_level->Visible) { // id_level ?>
        <td data-name="id_level">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->id_level->getSessionValue() != "") { ?>
<span id="el$rowindex$_permission2_id_level" class="form-group permission2_id_level">
<span<?= $Grid->id_level->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id_level->getDisplayValue($Grid->id_level->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_id_level" name="x<?= $Grid->RowIndex ?>_id_level" value="<?= HtmlEncode($Grid->id_level->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_permission2_id_level" class="form-group permission2_id_level">
<input type="<?= $Grid->id_level->getInputTextType() ?>" data-table="permission2" data-field="x_id_level" name="x<?= $Grid->RowIndex ?>_id_level" id="x<?= $Grid->RowIndex ?>_id_level" size="30" placeholder="<?= HtmlEncode($Grid->id_level->getPlaceHolder()) ?>" value="<?= $Grid->id_level->EditValue ?>"<?= $Grid->id_level->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->id_level->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_permission2_id_level" class="form-group permission2_id_level">
<span<?= $Grid->id_level->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id_level->getDisplayValue($Grid->id_level->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="permission2" data-field="x_id_level" data-hidden="1" name="x<?= $Grid->RowIndex ?>_id_level" id="x<?= $Grid->RowIndex ?>_id_level" value="<?= HtmlEncode($Grid->id_level->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="permission2" data-field="x_id_level" data-hidden="1" name="o<?= $Grid->RowIndex ?>_id_level" id="o<?= $Grid->RowIndex ?>_id_level" value="<?= HtmlEncode($Grid->id_level->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->_permission->Visible) { // permission ?>
        <td data-name="_permission">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_permission2__permission" class="form-group permission2__permission">
    <select
        id="x<?= $Grid->RowIndex ?>__permission"
        name="x<?= $Grid->RowIndex ?>__permission"
        class="form-control ew-select<?= $Grid->_permission->isInvalidClass() ?>"
        data-select2-id="permission2_x<?= $Grid->RowIndex ?>__permission"
        data-table="permission2"
        data-field="x__permission"
        data-value-separator="<?= $Grid->_permission->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->_permission->getPlaceHolder()) ?>"
        <?= $Grid->_permission->editAttributes() ?>>
        <?= $Grid->_permission->selectOptionListHtml("x{$Grid->RowIndex}__permission") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->_permission->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='permission2_x<?= $Grid->RowIndex ?>__permission']"),
        options = { name: "x<?= $Grid->RowIndex ?>__permission", selectId: "permission2_x<?= $Grid->RowIndex ?>__permission", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.permission2.fields._permission.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_permission2__permission" class="form-group permission2__permission">
<span<?= $Grid->_permission->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->_permission->getDisplayValue($Grid->_permission->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="permission2" data-field="x__permission" data-hidden="1" name="x<?= $Grid->RowIndex ?>__permission" id="x<?= $Grid->RowIndex ?>__permission" value="<?= HtmlEncode($Grid->_permission->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="permission2" data-field="x__permission" data-hidden="1" name="o<?= $Grid->RowIndex ?>__permission" id="o<?= $Grid->RowIndex ?>__permission" value="<?= HtmlEncode($Grid->_permission->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fpermission2grid","load"], function() {
    fpermission2grid.updateLists(<?= $Grid->RowIndex ?>);
});
</script>
    </tr>
<?php
    }
?>
</tbody>
</table><!-- /.ew-table -->
</div><!-- /.ew-grid-middle-panel -->
<?php if ($Grid->CurrentMode == "add" || $Grid->CurrentMode == "copy") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
<?php if ($Grid->CurrentMode == "edit") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
<?php if ($Grid->CurrentMode == "") { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fpermission2grid">
</div><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Grid->Recordset) {
    $Grid->Recordset->close();
}
?>
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php $Grid->OtherOptions->render("body", "bottom") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } ?>
<?php if ($Grid->TotalRecords == 0 && !$Grid->CurrentAction) { // Show other options ?>
<div class="ew-list-other-options">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if (!$Grid->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("permission2");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
