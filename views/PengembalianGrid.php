<?php

namespace PHPMaker2021\perpus;

// Set up and run Grid object
$Grid = Container("PengembalianGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
if (!ew.vars.tables.pengembalian) ew.vars.tables.pengembalian = <?= JsonEncode(GetClientVar("tables", "pengembalian")) ?>;
var currentForm, currentPageID;
var fpengembaliangrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    fpengembaliangrid = new ew.Form("fpengembaliangrid", "grid");
    fpengembaliangrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var fields = ew.vars.tables.pengembalian.fields;
    fpengembaliangrid.addFields([
        ["id_kembali", [fields.id_kembali.required ? ew.Validators.required(fields.id_kembali.caption) : null], fields.id_kembali.isInvalid],
        ["id_peminjaman", [fields.id_peminjaman.required ? ew.Validators.required(fields.id_peminjaman.caption) : null], fields.id_peminjaman.isInvalid],
        ["tgl_kembali", [fields.tgl_kembali.required ? ew.Validators.required(fields.tgl_kembali.caption) : null, ew.Validators.datetime(0)], fields.tgl_kembali.isInvalid],
        ["kondisi_buku_kembali", [fields.kondisi_buku_kembali.required ? ew.Validators.required(fields.kondisi_buku_kembali.caption) : null], fields.kondisi_buku_kembali.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fpengembaliangrid,
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
    fpengembaliangrid.validate = function () {
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
    fpengembaliangrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "id_peminjaman", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "tgl_kembali", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "kondisi_buku_kembali", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fpengembaliangrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpengembaliangrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fpengembaliangrid.lists.id_peminjaman = <?= $Grid->id_peminjaman->toClientList($Grid) ?>;
    loadjs.done("fpengembaliangrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> pengembalian">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="fpengembaliangrid" class="ew-form ew-list-form form-inline">
<div id="gmp_pengembalian" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_pengembaliangrid" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Grid->id_kembali->Visible) { // id_kembali ?>
        <th data-name="id_kembali" class="<?= $Grid->id_kembali->headerCellClass() ?>"><div id="elh_pengembalian_id_kembali" class="pengembalian_id_kembali"><?= $Grid->renderSort($Grid->id_kembali) ?></div></th>
<?php } ?>
<?php if ($Grid->id_peminjaman->Visible) { // id_peminjaman ?>
        <th data-name="id_peminjaman" class="<?= $Grid->id_peminjaman->headerCellClass() ?>"><div id="elh_pengembalian_id_peminjaman" class="pengembalian_id_peminjaman"><?= $Grid->renderSort($Grid->id_peminjaman) ?></div></th>
<?php } ?>
<?php if ($Grid->tgl_kembali->Visible) { // tgl_kembali ?>
        <th data-name="tgl_kembali" class="<?= $Grid->tgl_kembali->headerCellClass() ?>"><div id="elh_pengembalian_tgl_kembali" class="pengembalian_tgl_kembali"><?= $Grid->renderSort($Grid->tgl_kembali) ?></div></th>
<?php } ?>
<?php if ($Grid->kondisi_buku_kembali->Visible) { // kondisi_buku_kembali ?>
        <th data-name="kondisi_buku_kembali" class="<?= $Grid->kondisi_buku_kembali->headerCellClass() ?>"><div id="elh_pengembalian_kondisi_buku_kembali" class="pengembalian_kondisi_buku_kembali"><?= $Grid->renderSort($Grid->kondisi_buku_kembali) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_pengembalian", "data-rowtype" => $Grid->RowType]);

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
    <?php if ($Grid->id_kembali->Visible) { // id_kembali ?>
        <td data-name="id_kembali" <?= $Grid->id_kembali->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_pengembalian_id_kembali"></span>
<input type="hidden" data-table="pengembalian" data-field="x_id_kembali" data-hidden="1" name="o<?= $Grid->RowIndex ?>_id_kembali" id="o<?= $Grid->RowIndex ?>_id_kembali" value="<?= HtmlEncode($Grid->id_kembali->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_pengembalian_id_kembali">
<span<?= $Grid->id_kembali->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id_kembali->getDisplayValue($Grid->id_kembali->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="pengembalian" data-field="x_id_kembali" data-hidden="1" name="x<?= $Grid->RowIndex ?>_id_kembali" id="x<?= $Grid->RowIndex ?>_id_kembali" value="<?= HtmlEncode($Grid->id_kembali->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_pengembalian_id_kembali">
<span<?= $Grid->id_kembali->viewAttributes() ?>>
<?= $Grid->id_kembali->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pengembalian" data-field="x_id_kembali" data-hidden="1" name="fpengembaliangrid$x<?= $Grid->RowIndex ?>_id_kembali" id="fpengembaliangrid$x<?= $Grid->RowIndex ?>_id_kembali" value="<?= HtmlEncode($Grid->id_kembali->FormValue) ?>">
<input type="hidden" data-table="pengembalian" data-field="x_id_kembali" data-hidden="1" name="fpengembaliangrid$o<?= $Grid->RowIndex ?>_id_kembali" id="fpengembaliangrid$o<?= $Grid->RowIndex ?>_id_kembali" value="<?= HtmlEncode($Grid->id_kembali->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->id_peminjaman->Visible) { // id_peminjaman ?>
        <td data-name="id_peminjaman" <?= $Grid->id_peminjaman->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->id_peminjaman->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_pengembalian_id_peminjaman">
<span<?= $Grid->id_peminjaman->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id_peminjaman->getDisplayValue($Grid->id_peminjaman->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_id_peminjaman" name="x<?= $Grid->RowIndex ?>_id_peminjaman" value="<?= HtmlEncode($Grid->id_peminjaman->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_pengembalian_id_peminjaman">
    <select
        id="x<?= $Grid->RowIndex ?>_id_peminjaman"
        name="x<?= $Grid->RowIndex ?>_id_peminjaman"
        class="form-control ew-select<?= $Grid->id_peminjaman->isInvalidClass() ?>"
        data-select2-id="pengembalian_x<?= $Grid->RowIndex ?>_id_peminjaman"
        data-table="pengembalian"
        data-field="x_id_peminjaman"
        data-value-separator="<?= $Grid->id_peminjaman->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->id_peminjaman->getPlaceHolder()) ?>"
        <?= $Grid->id_peminjaman->editAttributes() ?>>
        <?= $Grid->id_peminjaman->selectOptionListHtml("x{$Grid->RowIndex}_id_peminjaman") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->id_peminjaman->getErrorMessage() ?></div>
<?= $Grid->id_peminjaman->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_id_peminjaman") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pengembalian_x<?= $Grid->RowIndex ?>_id_peminjaman']"),
        options = { name: "x<?= $Grid->RowIndex ?>_id_peminjaman", selectId: "pengembalian_x<?= $Grid->RowIndex ?>_id_peminjaman", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pengembalian.fields.id_peminjaman.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="pengembalian" data-field="x_id_peminjaman" data-hidden="1" name="o<?= $Grid->RowIndex ?>_id_peminjaman" id="o<?= $Grid->RowIndex ?>_id_peminjaman" value="<?= HtmlEncode($Grid->id_peminjaman->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->id_peminjaman->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_pengembalian_id_peminjaman">
<span<?= $Grid->id_peminjaman->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id_peminjaman->getDisplayValue($Grid->id_peminjaman->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_id_peminjaman" name="x<?= $Grid->RowIndex ?>_id_peminjaman" value="<?= HtmlEncode($Grid->id_peminjaman->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_pengembalian_id_peminjaman">
    <select
        id="x<?= $Grid->RowIndex ?>_id_peminjaman"
        name="x<?= $Grid->RowIndex ?>_id_peminjaman"
        class="form-control ew-select<?= $Grid->id_peminjaman->isInvalidClass() ?>"
        data-select2-id="pengembalian_x<?= $Grid->RowIndex ?>_id_peminjaman"
        data-table="pengembalian"
        data-field="x_id_peminjaman"
        data-value-separator="<?= $Grid->id_peminjaman->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->id_peminjaman->getPlaceHolder()) ?>"
        <?= $Grid->id_peminjaman->editAttributes() ?>>
        <?= $Grid->id_peminjaman->selectOptionListHtml("x{$Grid->RowIndex}_id_peminjaman") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->id_peminjaman->getErrorMessage() ?></div>
<?= $Grid->id_peminjaman->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_id_peminjaman") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pengembalian_x<?= $Grid->RowIndex ?>_id_peminjaman']"),
        options = { name: "x<?= $Grid->RowIndex ?>_id_peminjaman", selectId: "pengembalian_x<?= $Grid->RowIndex ?>_id_peminjaman", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pengembalian.fields.id_peminjaman.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_pengembalian_id_peminjaman">
<span<?= $Grid->id_peminjaman->viewAttributes() ?>>
<?= $Grid->id_peminjaman->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pengembalian" data-field="x_id_peminjaman" data-hidden="1" name="fpengembaliangrid$x<?= $Grid->RowIndex ?>_id_peminjaman" id="fpengembaliangrid$x<?= $Grid->RowIndex ?>_id_peminjaman" value="<?= HtmlEncode($Grid->id_peminjaman->FormValue) ?>">
<input type="hidden" data-table="pengembalian" data-field="x_id_peminjaman" data-hidden="1" name="fpengembaliangrid$o<?= $Grid->RowIndex ?>_id_peminjaman" id="fpengembaliangrid$o<?= $Grid->RowIndex ?>_id_peminjaman" value="<?= HtmlEncode($Grid->id_peminjaman->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->tgl_kembali->Visible) { // tgl_kembali ?>
        <td data-name="tgl_kembali" <?= $Grid->tgl_kembali->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_pengembalian_tgl_kembali">
<input type="<?= $Grid->tgl_kembali->getInputTextType() ?>" data-table="pengembalian" data-field="x_tgl_kembali" name="x<?= $Grid->RowIndex ?>_tgl_kembali" id="x<?= $Grid->RowIndex ?>_tgl_kembali" placeholder="<?= HtmlEncode($Grid->tgl_kembali->getPlaceHolder()) ?>" value="<?= $Grid->tgl_kembali->EditValue ?>"<?= $Grid->tgl_kembali->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->tgl_kembali->getErrorMessage() ?></div>
<?php if (!$Grid->tgl_kembali->ReadOnly && !$Grid->tgl_kembali->Disabled && !isset($Grid->tgl_kembali->EditAttrs["readonly"]) && !isset($Grid->tgl_kembali->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpengembaliangrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fpengembaliangrid", "x<?= $Grid->RowIndex ?>_tgl_kembali", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="pengembalian" data-field="x_tgl_kembali" data-hidden="1" name="o<?= $Grid->RowIndex ?>_tgl_kembali" id="o<?= $Grid->RowIndex ?>_tgl_kembali" value="<?= HtmlEncode($Grid->tgl_kembali->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_pengembalian_tgl_kembali">
<input type="<?= $Grid->tgl_kembali->getInputTextType() ?>" data-table="pengembalian" data-field="x_tgl_kembali" name="x<?= $Grid->RowIndex ?>_tgl_kembali" id="x<?= $Grid->RowIndex ?>_tgl_kembali" placeholder="<?= HtmlEncode($Grid->tgl_kembali->getPlaceHolder()) ?>" value="<?= $Grid->tgl_kembali->EditValue ?>"<?= $Grid->tgl_kembali->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->tgl_kembali->getErrorMessage() ?></div>
<?php if (!$Grid->tgl_kembali->ReadOnly && !$Grid->tgl_kembali->Disabled && !isset($Grid->tgl_kembali->EditAttrs["readonly"]) && !isset($Grid->tgl_kembali->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpengembaliangrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fpengembaliangrid", "x<?= $Grid->RowIndex ?>_tgl_kembali", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_pengembalian_tgl_kembali">
<span<?= $Grid->tgl_kembali->viewAttributes() ?>>
<?= $Grid->tgl_kembali->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pengembalian" data-field="x_tgl_kembali" data-hidden="1" name="fpengembaliangrid$x<?= $Grid->RowIndex ?>_tgl_kembali" id="fpengembaliangrid$x<?= $Grid->RowIndex ?>_tgl_kembali" value="<?= HtmlEncode($Grid->tgl_kembali->FormValue) ?>">
<input type="hidden" data-table="pengembalian" data-field="x_tgl_kembali" data-hidden="1" name="fpengembaliangrid$o<?= $Grid->RowIndex ?>_tgl_kembali" id="fpengembaliangrid$o<?= $Grid->RowIndex ?>_tgl_kembali" value="<?= HtmlEncode($Grid->tgl_kembali->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->kondisi_buku_kembali->Visible) { // kondisi_buku_kembali ?>
        <td data-name="kondisi_buku_kembali" <?= $Grid->kondisi_buku_kembali->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_pengembalian_kondisi_buku_kembali">
<input type="<?= $Grid->kondisi_buku_kembali->getInputTextType() ?>" data-table="pengembalian" data-field="x_kondisi_buku_kembali" name="x<?= $Grid->RowIndex ?>_kondisi_buku_kembali" id="x<?= $Grid->RowIndex ?>_kondisi_buku_kembali" size="30" maxlength="200" placeholder="<?= HtmlEncode($Grid->kondisi_buku_kembali->getPlaceHolder()) ?>" value="<?= $Grid->kondisi_buku_kembali->EditValue ?>"<?= $Grid->kondisi_buku_kembali->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->kondisi_buku_kembali->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="pengembalian" data-field="x_kondisi_buku_kembali" data-hidden="1" name="o<?= $Grid->RowIndex ?>_kondisi_buku_kembali" id="o<?= $Grid->RowIndex ?>_kondisi_buku_kembali" value="<?= HtmlEncode($Grid->kondisi_buku_kembali->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_pengembalian_kondisi_buku_kembali">
<input type="<?= $Grid->kondisi_buku_kembali->getInputTextType() ?>" data-table="pengembalian" data-field="x_kondisi_buku_kembali" name="x<?= $Grid->RowIndex ?>_kondisi_buku_kembali" id="x<?= $Grid->RowIndex ?>_kondisi_buku_kembali" size="30" maxlength="200" placeholder="<?= HtmlEncode($Grid->kondisi_buku_kembali->getPlaceHolder()) ?>" value="<?= $Grid->kondisi_buku_kembali->EditValue ?>"<?= $Grid->kondisi_buku_kembali->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->kondisi_buku_kembali->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_pengembalian_kondisi_buku_kembali">
<span<?= $Grid->kondisi_buku_kembali->viewAttributes() ?>>
<?= $Grid->kondisi_buku_kembali->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="pengembalian" data-field="x_kondisi_buku_kembali" data-hidden="1" name="fpengembaliangrid$x<?= $Grid->RowIndex ?>_kondisi_buku_kembali" id="fpengembaliangrid$x<?= $Grid->RowIndex ?>_kondisi_buku_kembali" value="<?= HtmlEncode($Grid->kondisi_buku_kembali->FormValue) ?>">
<input type="hidden" data-table="pengembalian" data-field="x_kondisi_buku_kembali" data-hidden="1" name="fpengembaliangrid$o<?= $Grid->RowIndex ?>_kondisi_buku_kembali" id="fpengembaliangrid$o<?= $Grid->RowIndex ?>_kondisi_buku_kembali" value="<?= HtmlEncode($Grid->kondisi_buku_kembali->OldValue) ?>">
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
loadjs.ready(["fpengembaliangrid","load"], function () {
    fpengembaliangrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_pengembalian", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->id_kembali->Visible) { // id_kembali ?>
        <td data-name="id_kembali">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_pengembalian_id_kembali"></span>
<?php } else { ?>
<span id="el$rowindex$_pengembalian_id_kembali">
<span<?= $Grid->id_kembali->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id_kembali->getDisplayValue($Grid->id_kembali->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="pengembalian" data-field="x_id_kembali" data-hidden="1" name="x<?= $Grid->RowIndex ?>_id_kembali" id="x<?= $Grid->RowIndex ?>_id_kembali" value="<?= HtmlEncode($Grid->id_kembali->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pengembalian" data-field="x_id_kembali" data-hidden="1" name="o<?= $Grid->RowIndex ?>_id_kembali" id="o<?= $Grid->RowIndex ?>_id_kembali" value="<?= HtmlEncode($Grid->id_kembali->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->id_peminjaman->Visible) { // id_peminjaman ?>
        <td data-name="id_peminjaman">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->id_peminjaman->getSessionValue() != "") { ?>
<span id="el$rowindex$_pengembalian_id_peminjaman">
<span<?= $Grid->id_peminjaman->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id_peminjaman->getDisplayValue($Grid->id_peminjaman->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_id_peminjaman" name="x<?= $Grid->RowIndex ?>_id_peminjaman" value="<?= HtmlEncode($Grid->id_peminjaman->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_pengembalian_id_peminjaman">
    <select
        id="x<?= $Grid->RowIndex ?>_id_peminjaman"
        name="x<?= $Grid->RowIndex ?>_id_peminjaman"
        class="form-control ew-select<?= $Grid->id_peminjaman->isInvalidClass() ?>"
        data-select2-id="pengembalian_x<?= $Grid->RowIndex ?>_id_peminjaman"
        data-table="pengembalian"
        data-field="x_id_peminjaman"
        data-value-separator="<?= $Grid->id_peminjaman->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->id_peminjaman->getPlaceHolder()) ?>"
        <?= $Grid->id_peminjaman->editAttributes() ?>>
        <?= $Grid->id_peminjaman->selectOptionListHtml("x{$Grid->RowIndex}_id_peminjaman") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->id_peminjaman->getErrorMessage() ?></div>
<?= $Grid->id_peminjaman->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_id_peminjaman") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pengembalian_x<?= $Grid->RowIndex ?>_id_peminjaman']"),
        options = { name: "x<?= $Grid->RowIndex ?>_id_peminjaman", selectId: "pengembalian_x<?= $Grid->RowIndex ?>_id_peminjaman", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pengembalian.fields.id_peminjaman.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_pengembalian_id_peminjaman">
<span<?= $Grid->id_peminjaman->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id_peminjaman->getDisplayValue($Grid->id_peminjaman->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="pengembalian" data-field="x_id_peminjaman" data-hidden="1" name="x<?= $Grid->RowIndex ?>_id_peminjaman" id="x<?= $Grid->RowIndex ?>_id_peminjaman" value="<?= HtmlEncode($Grid->id_peminjaman->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pengembalian" data-field="x_id_peminjaman" data-hidden="1" name="o<?= $Grid->RowIndex ?>_id_peminjaman" id="o<?= $Grid->RowIndex ?>_id_peminjaman" value="<?= HtmlEncode($Grid->id_peminjaman->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->tgl_kembali->Visible) { // tgl_kembali ?>
        <td data-name="tgl_kembali">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_pengembalian_tgl_kembali">
<input type="<?= $Grid->tgl_kembali->getInputTextType() ?>" data-table="pengembalian" data-field="x_tgl_kembali" name="x<?= $Grid->RowIndex ?>_tgl_kembali" id="x<?= $Grid->RowIndex ?>_tgl_kembali" placeholder="<?= HtmlEncode($Grid->tgl_kembali->getPlaceHolder()) ?>" value="<?= $Grid->tgl_kembali->EditValue ?>"<?= $Grid->tgl_kembali->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->tgl_kembali->getErrorMessage() ?></div>
<?php if (!$Grid->tgl_kembali->ReadOnly && !$Grid->tgl_kembali->Disabled && !isset($Grid->tgl_kembali->EditAttrs["readonly"]) && !isset($Grid->tgl_kembali->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpengembaliangrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fpengembaliangrid", "x<?= $Grid->RowIndex ?>_tgl_kembali", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_pengembalian_tgl_kembali">
<span<?= $Grid->tgl_kembali->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->tgl_kembali->getDisplayValue($Grid->tgl_kembali->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="pengembalian" data-field="x_tgl_kembali" data-hidden="1" name="x<?= $Grid->RowIndex ?>_tgl_kembali" id="x<?= $Grid->RowIndex ?>_tgl_kembali" value="<?= HtmlEncode($Grid->tgl_kembali->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pengembalian" data-field="x_tgl_kembali" data-hidden="1" name="o<?= $Grid->RowIndex ?>_tgl_kembali" id="o<?= $Grid->RowIndex ?>_tgl_kembali" value="<?= HtmlEncode($Grid->tgl_kembali->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->kondisi_buku_kembali->Visible) { // kondisi_buku_kembali ?>
        <td data-name="kondisi_buku_kembali">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_pengembalian_kondisi_buku_kembali">
<input type="<?= $Grid->kondisi_buku_kembali->getInputTextType() ?>" data-table="pengembalian" data-field="x_kondisi_buku_kembali" name="x<?= $Grid->RowIndex ?>_kondisi_buku_kembali" id="x<?= $Grid->RowIndex ?>_kondisi_buku_kembali" size="30" maxlength="200" placeholder="<?= HtmlEncode($Grid->kondisi_buku_kembali->getPlaceHolder()) ?>" value="<?= $Grid->kondisi_buku_kembali->EditValue ?>"<?= $Grid->kondisi_buku_kembali->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->kondisi_buku_kembali->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_pengembalian_kondisi_buku_kembali">
<span<?= $Grid->kondisi_buku_kembali->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->kondisi_buku_kembali->getDisplayValue($Grid->kondisi_buku_kembali->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="pengembalian" data-field="x_kondisi_buku_kembali" data-hidden="1" name="x<?= $Grid->RowIndex ?>_kondisi_buku_kembali" id="x<?= $Grid->RowIndex ?>_kondisi_buku_kembali" value="<?= HtmlEncode($Grid->kondisi_buku_kembali->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pengembalian" data-field="x_kondisi_buku_kembali" data-hidden="1" name="o<?= $Grid->RowIndex ?>_kondisi_buku_kembali" id="o<?= $Grid->RowIndex ?>_kondisi_buku_kembali" value="<?= HtmlEncode($Grid->kondisi_buku_kembali->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fpengembaliangrid","load"], function() {
    fpengembaliangrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="fpengembaliangrid">
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
    ew.addEventHandlers("pengembalian");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
