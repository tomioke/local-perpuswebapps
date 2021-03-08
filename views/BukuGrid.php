<?php

namespace PHPMaker2021\perpusupdate;

// Set up and run Grid object
$Grid = Container("BukuGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fbukugrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    fbukugrid = new ew.Form("fbukugrid", "grid");
    fbukugrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "buku")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.buku)
        ew.vars.tables.buku = currentTable;
    fbukugrid.addFields([
        ["cover", [fields.cover.visible && fields.cover.required ? ew.Validators.fileRequired(fields.cover.caption) : null], fields.cover.isInvalid],
        ["nama_buku", [fields.nama_buku.visible && fields.nama_buku.required ? ew.Validators.required(fields.nama_buku.caption) : null], fields.nama_buku.isInvalid],
        ["pengarang", [fields.pengarang.visible && fields.pengarang.required ? ew.Validators.required(fields.pengarang.caption) : null], fields.pengarang.isInvalid],
        ["penerbit", [fields.penerbit.visible && fields.penerbit.required ? ew.Validators.required(fields.penerbit.caption) : null], fields.penerbit.isInvalid],
        ["kode_isbn", [fields.kode_isbn.visible && fields.kode_isbn.required ? ew.Validators.required(fields.kode_isbn.caption) : null], fields.kode_isbn.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fbukugrid,
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
    fbukugrid.validate = function () {
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
    fbukugrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "cover", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "nama_buku", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "pengarang", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "penerbit", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "kode_isbn", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fbukugrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fbukugrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fbukugrid.lists.pengarang = <?= $Grid->pengarang->toClientList($Grid) ?>;
    fbukugrid.lists.penerbit = <?= $Grid->penerbit->toClientList($Grid) ?>;
    loadjs.done("fbukugrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> buku">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="fbukugrid" class="ew-form ew-list-form form-inline">
<div id="gmp_buku" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_bukugrid" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Grid->cover->Visible) { // cover ?>
        <th data-name="cover" class="<?= $Grid->cover->headerCellClass() ?>"><div id="elh_buku_cover" class="buku_cover"><?= $Grid->renderSort($Grid->cover) ?></div></th>
<?php } ?>
<?php if ($Grid->nama_buku->Visible) { // nama_buku ?>
        <th data-name="nama_buku" class="<?= $Grid->nama_buku->headerCellClass() ?>"><div id="elh_buku_nama_buku" class="buku_nama_buku"><?= $Grid->renderSort($Grid->nama_buku) ?></div></th>
<?php } ?>
<?php if ($Grid->pengarang->Visible) { // pengarang ?>
        <th data-name="pengarang" class="<?= $Grid->pengarang->headerCellClass() ?>"><div id="elh_buku_pengarang" class="buku_pengarang"><?= $Grid->renderSort($Grid->pengarang) ?></div></th>
<?php } ?>
<?php if ($Grid->penerbit->Visible) { // penerbit ?>
        <th data-name="penerbit" class="<?= $Grid->penerbit->headerCellClass() ?>"><div id="elh_buku_penerbit" class="buku_penerbit"><?= $Grid->renderSort($Grid->penerbit) ?></div></th>
<?php } ?>
<?php if ($Grid->kode_isbn->Visible) { // kode_isbn ?>
        <th data-name="kode_isbn" class="<?= $Grid->kode_isbn->headerCellClass() ?>"><div id="elh_buku_kode_isbn" class="buku_kode_isbn"><?= $Grid->renderSort($Grid->kode_isbn) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_buku", "data-rowtype" => $Grid->RowType]);

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
    <?php if ($Grid->cover->Visible) { // cover ?>
        <td data-name="cover" <?= $Grid->cover->cellAttributes() ?>>
<?php if ($Grid->RowAction == "insert") { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_buku_cover">
<div id="fd_x<?= $Grid->RowIndex ?>_cover">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->cover->title() ?>" data-table="buku" data-field="x_cover" name="x<?= $Grid->RowIndex ?>_cover" id="x<?= $Grid->RowIndex ?>_cover" lang="<?= CurrentLanguageID() ?>"<?= $Grid->cover->editAttributes() ?><?= ($Grid->cover->ReadOnly || $Grid->cover->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_cover"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->cover->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_cover" id= "fn_x<?= $Grid->RowIndex ?>_cover" value="<?= $Grid->cover->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_cover" id= "fa_x<?= $Grid->RowIndex ?>_cover" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_cover" id= "fs_x<?= $Grid->RowIndex ?>_cover" value="200">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_cover" id= "fx_x<?= $Grid->RowIndex ?>_cover" value="<?= $Grid->cover->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_cover" id= "fm_x<?= $Grid->RowIndex ?>_cover" value="<?= $Grid->cover->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_cover" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="buku" data-field="x_cover" data-hidden="1" name="o<?= $Grid->RowIndex ?>_cover" id="o<?= $Grid->RowIndex ?>_cover" value="<?= HtmlEncode($Grid->cover->OldValue) ?>">
<?php } elseif ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_buku_cover">
<span>
<?= GetFileViewTag($Grid->cover, $Grid->cover->getViewValue(), false) ?>
</span>
</span>
<?php } else  { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_buku_cover" class="form-group buku_cover">
<div id="fd_x<?= $Grid->RowIndex ?>_cover">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->cover->title() ?>" data-table="buku" data-field="x_cover" name="x<?= $Grid->RowIndex ?>_cover" id="x<?= $Grid->RowIndex ?>_cover" lang="<?= CurrentLanguageID() ?>"<?= $Grid->cover->editAttributes() ?><?= ($Grid->cover->ReadOnly || $Grid->cover->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_cover"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->cover->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_cover" id= "fn_x<?= $Grid->RowIndex ?>_cover" value="<?= $Grid->cover->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_cover" id= "fa_x<?= $Grid->RowIndex ?>_cover" value="<?= (Post("fa_x<?= $Grid->RowIndex ?>_cover") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_cover" id= "fs_x<?= $Grid->RowIndex ?>_cover" value="200">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_cover" id= "fx_x<?= $Grid->RowIndex ?>_cover" value="<?= $Grid->cover->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_cover" id= "fm_x<?= $Grid->RowIndex ?>_cover" value="<?= $Grid->cover->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_cover" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->nama_buku->Visible) { // nama_buku ?>
        <td data-name="nama_buku" <?= $Grid->nama_buku->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_buku_nama_buku">
<input type="<?= $Grid->nama_buku->getInputTextType() ?>" data-table="buku" data-field="x_nama_buku" name="x<?= $Grid->RowIndex ?>_nama_buku" id="x<?= $Grid->RowIndex ?>_nama_buku" size="30" maxlength="200" placeholder="<?= HtmlEncode($Grid->nama_buku->getPlaceHolder()) ?>" value="<?= $Grid->nama_buku->EditValue ?>"<?= $Grid->nama_buku->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->nama_buku->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="buku" data-field="x_nama_buku" data-hidden="1" name="o<?= $Grid->RowIndex ?>_nama_buku" id="o<?= $Grid->RowIndex ?>_nama_buku" value="<?= HtmlEncode($Grid->nama_buku->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_buku_nama_buku">
<input type="<?= $Grid->nama_buku->getInputTextType() ?>" data-table="buku" data-field="x_nama_buku" name="x<?= $Grid->RowIndex ?>_nama_buku" id="x<?= $Grid->RowIndex ?>_nama_buku" size="30" maxlength="200" placeholder="<?= HtmlEncode($Grid->nama_buku->getPlaceHolder()) ?>" value="<?= $Grid->nama_buku->EditValue ?>"<?= $Grid->nama_buku->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->nama_buku->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_buku_nama_buku">
<span<?= $Grid->nama_buku->viewAttributes() ?>>
<?= $Grid->nama_buku->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="buku" data-field="x_nama_buku" data-hidden="1" name="fbukugrid$x<?= $Grid->RowIndex ?>_nama_buku" id="fbukugrid$x<?= $Grid->RowIndex ?>_nama_buku" value="<?= HtmlEncode($Grid->nama_buku->FormValue) ?>">
<input type="hidden" data-table="buku" data-field="x_nama_buku" data-hidden="1" name="fbukugrid$o<?= $Grid->RowIndex ?>_nama_buku" id="fbukugrid$o<?= $Grid->RowIndex ?>_nama_buku" value="<?= HtmlEncode($Grid->nama_buku->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->pengarang->Visible) { // pengarang ?>
        <td data-name="pengarang" <?= $Grid->pengarang->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->pengarang->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_buku_pengarang">
<span<?= $Grid->pengarang->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->pengarang->getDisplayValue($Grid->pengarang->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_pengarang" name="x<?= $Grid->RowIndex ?>_pengarang" value="<?= HtmlEncode($Grid->pengarang->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_buku_pengarang">
    <select
        id="x<?= $Grid->RowIndex ?>_pengarang"
        name="x<?= $Grid->RowIndex ?>_pengarang"
        class="form-control ew-select<?= $Grid->pengarang->isInvalidClass() ?>"
        data-select2-id="buku_x<?= $Grid->RowIndex ?>_pengarang"
        data-table="buku"
        data-field="x_pengarang"
        data-value-separator="<?= $Grid->pengarang->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->pengarang->getPlaceHolder()) ?>"
        <?= $Grid->pengarang->editAttributes() ?>>
        <?= $Grid->pengarang->selectOptionListHtml("x{$Grid->RowIndex}_pengarang") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->pengarang->getErrorMessage() ?></div>
<?= $Grid->pengarang->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_pengarang") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='buku_x<?= $Grid->RowIndex ?>_pengarang']"),
        options = { name: "x<?= $Grid->RowIndex ?>_pengarang", selectId: "buku_x<?= $Grid->RowIndex ?>_pengarang", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.buku.fields.pengarang.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="buku" data-field="x_pengarang" data-hidden="1" name="o<?= $Grid->RowIndex ?>_pengarang" id="o<?= $Grid->RowIndex ?>_pengarang" value="<?= HtmlEncode($Grid->pengarang->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->pengarang->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_buku_pengarang">
<span<?= $Grid->pengarang->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->pengarang->getDisplayValue($Grid->pengarang->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_pengarang" name="x<?= $Grid->RowIndex ?>_pengarang" value="<?= HtmlEncode($Grid->pengarang->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_buku_pengarang">
    <select
        id="x<?= $Grid->RowIndex ?>_pengarang"
        name="x<?= $Grid->RowIndex ?>_pengarang"
        class="form-control ew-select<?= $Grid->pengarang->isInvalidClass() ?>"
        data-select2-id="buku_x<?= $Grid->RowIndex ?>_pengarang"
        data-table="buku"
        data-field="x_pengarang"
        data-value-separator="<?= $Grid->pengarang->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->pengarang->getPlaceHolder()) ?>"
        <?= $Grid->pengarang->editAttributes() ?>>
        <?= $Grid->pengarang->selectOptionListHtml("x{$Grid->RowIndex}_pengarang") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->pengarang->getErrorMessage() ?></div>
<?= $Grid->pengarang->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_pengarang") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='buku_x<?= $Grid->RowIndex ?>_pengarang']"),
        options = { name: "x<?= $Grid->RowIndex ?>_pengarang", selectId: "buku_x<?= $Grid->RowIndex ?>_pengarang", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.buku.fields.pengarang.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_buku_pengarang">
<span<?= $Grid->pengarang->viewAttributes() ?>>
<?= $Grid->pengarang->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="buku" data-field="x_pengarang" data-hidden="1" name="fbukugrid$x<?= $Grid->RowIndex ?>_pengarang" id="fbukugrid$x<?= $Grid->RowIndex ?>_pengarang" value="<?= HtmlEncode($Grid->pengarang->FormValue) ?>">
<input type="hidden" data-table="buku" data-field="x_pengarang" data-hidden="1" name="fbukugrid$o<?= $Grid->RowIndex ?>_pengarang" id="fbukugrid$o<?= $Grid->RowIndex ?>_pengarang" value="<?= HtmlEncode($Grid->pengarang->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->penerbit->Visible) { // penerbit ?>
        <td data-name="penerbit" <?= $Grid->penerbit->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->penerbit->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_buku_penerbit">
<span<?= $Grid->penerbit->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->penerbit->getDisplayValue($Grid->penerbit->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_penerbit" name="x<?= $Grid->RowIndex ?>_penerbit" value="<?= HtmlEncode($Grid->penerbit->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_buku_penerbit">
    <select
        id="x<?= $Grid->RowIndex ?>_penerbit"
        name="x<?= $Grid->RowIndex ?>_penerbit"
        class="form-control ew-select<?= $Grid->penerbit->isInvalidClass() ?>"
        data-select2-id="buku_x<?= $Grid->RowIndex ?>_penerbit"
        data-table="buku"
        data-field="x_penerbit"
        data-value-separator="<?= $Grid->penerbit->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->penerbit->getPlaceHolder()) ?>"
        <?= $Grid->penerbit->editAttributes() ?>>
        <?= $Grid->penerbit->selectOptionListHtml("x{$Grid->RowIndex}_penerbit") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->penerbit->getErrorMessage() ?></div>
<?= $Grid->penerbit->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_penerbit") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='buku_x<?= $Grid->RowIndex ?>_penerbit']"),
        options = { name: "x<?= $Grid->RowIndex ?>_penerbit", selectId: "buku_x<?= $Grid->RowIndex ?>_penerbit", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.buku.fields.penerbit.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="buku" data-field="x_penerbit" data-hidden="1" name="o<?= $Grid->RowIndex ?>_penerbit" id="o<?= $Grid->RowIndex ?>_penerbit" value="<?= HtmlEncode($Grid->penerbit->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->penerbit->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_buku_penerbit">
<span<?= $Grid->penerbit->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->penerbit->getDisplayValue($Grid->penerbit->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_penerbit" name="x<?= $Grid->RowIndex ?>_penerbit" value="<?= HtmlEncode($Grid->penerbit->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_buku_penerbit">
    <select
        id="x<?= $Grid->RowIndex ?>_penerbit"
        name="x<?= $Grid->RowIndex ?>_penerbit"
        class="form-control ew-select<?= $Grid->penerbit->isInvalidClass() ?>"
        data-select2-id="buku_x<?= $Grid->RowIndex ?>_penerbit"
        data-table="buku"
        data-field="x_penerbit"
        data-value-separator="<?= $Grid->penerbit->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->penerbit->getPlaceHolder()) ?>"
        <?= $Grid->penerbit->editAttributes() ?>>
        <?= $Grid->penerbit->selectOptionListHtml("x{$Grid->RowIndex}_penerbit") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->penerbit->getErrorMessage() ?></div>
<?= $Grid->penerbit->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_penerbit") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='buku_x<?= $Grid->RowIndex ?>_penerbit']"),
        options = { name: "x<?= $Grid->RowIndex ?>_penerbit", selectId: "buku_x<?= $Grid->RowIndex ?>_penerbit", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.buku.fields.penerbit.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_buku_penerbit">
<span<?= $Grid->penerbit->viewAttributes() ?>>
<?= $Grid->penerbit->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="buku" data-field="x_penerbit" data-hidden="1" name="fbukugrid$x<?= $Grid->RowIndex ?>_penerbit" id="fbukugrid$x<?= $Grid->RowIndex ?>_penerbit" value="<?= HtmlEncode($Grid->penerbit->FormValue) ?>">
<input type="hidden" data-table="buku" data-field="x_penerbit" data-hidden="1" name="fbukugrid$o<?= $Grid->RowIndex ?>_penerbit" id="fbukugrid$o<?= $Grid->RowIndex ?>_penerbit" value="<?= HtmlEncode($Grid->penerbit->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->kode_isbn->Visible) { // kode_isbn ?>
        <td data-name="kode_isbn" <?= $Grid->kode_isbn->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_buku_kode_isbn">
<input type="<?= $Grid->kode_isbn->getInputTextType() ?>" data-table="buku" data-field="x_kode_isbn" name="x<?= $Grid->RowIndex ?>_kode_isbn" id="x<?= $Grid->RowIndex ?>_kode_isbn" size="30" maxlength="200" placeholder="<?= HtmlEncode($Grid->kode_isbn->getPlaceHolder()) ?>" value="<?= $Grid->kode_isbn->EditValue ?>"<?= $Grid->kode_isbn->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->kode_isbn->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="buku" data-field="x_kode_isbn" data-hidden="1" name="o<?= $Grid->RowIndex ?>_kode_isbn" id="o<?= $Grid->RowIndex ?>_kode_isbn" value="<?= HtmlEncode($Grid->kode_isbn->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_buku_kode_isbn">
<input type="<?= $Grid->kode_isbn->getInputTextType() ?>" data-table="buku" data-field="x_kode_isbn" name="x<?= $Grid->RowIndex ?>_kode_isbn" id="x<?= $Grid->RowIndex ?>_kode_isbn" size="30" maxlength="200" placeholder="<?= HtmlEncode($Grid->kode_isbn->getPlaceHolder()) ?>" value="<?= $Grid->kode_isbn->EditValue ?>"<?= $Grid->kode_isbn->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->kode_isbn->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_buku_kode_isbn">
<span<?= $Grid->kode_isbn->viewAttributes() ?>>
<?= $Grid->kode_isbn->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="buku" data-field="x_kode_isbn" data-hidden="1" name="fbukugrid$x<?= $Grid->RowIndex ?>_kode_isbn" id="fbukugrid$x<?= $Grid->RowIndex ?>_kode_isbn" value="<?= HtmlEncode($Grid->kode_isbn->FormValue) ?>">
<input type="hidden" data-table="buku" data-field="x_kode_isbn" data-hidden="1" name="fbukugrid$o<?= $Grid->RowIndex ?>_kode_isbn" id="fbukugrid$o<?= $Grid->RowIndex ?>_kode_isbn" value="<?= HtmlEncode($Grid->kode_isbn->OldValue) ?>">
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
loadjs.ready(["fbukugrid","load"], function () {
    fbukugrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_buku", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->cover->Visible) { // cover ?>
        <td data-name="cover">
<span id="el$rowindex$_buku_cover">
<div id="fd_x<?= $Grid->RowIndex ?>_cover">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Grid->cover->title() ?>" data-table="buku" data-field="x_cover" name="x<?= $Grid->RowIndex ?>_cover" id="x<?= $Grid->RowIndex ?>_cover" lang="<?= CurrentLanguageID() ?>"<?= $Grid->cover->editAttributes() ?><?= ($Grid->cover->ReadOnly || $Grid->cover->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Grid->RowIndex ?>_cover"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Grid->cover->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Grid->RowIndex ?>_cover" id= "fn_x<?= $Grid->RowIndex ?>_cover" value="<?= $Grid->cover->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Grid->RowIndex ?>_cover" id= "fa_x<?= $Grid->RowIndex ?>_cover" value="0">
<input type="hidden" name="fs_x<?= $Grid->RowIndex ?>_cover" id= "fs_x<?= $Grid->RowIndex ?>_cover" value="200">
<input type="hidden" name="fx_x<?= $Grid->RowIndex ?>_cover" id= "fx_x<?= $Grid->RowIndex ?>_cover" value="<?= $Grid->cover->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Grid->RowIndex ?>_cover" id= "fm_x<?= $Grid->RowIndex ?>_cover" value="<?= $Grid->cover->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Grid->RowIndex ?>_cover" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="buku" data-field="x_cover" data-hidden="1" name="o<?= $Grid->RowIndex ?>_cover" id="o<?= $Grid->RowIndex ?>_cover" value="<?= HtmlEncode($Grid->cover->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->nama_buku->Visible) { // nama_buku ?>
        <td data-name="nama_buku">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_buku_nama_buku">
<input type="<?= $Grid->nama_buku->getInputTextType() ?>" data-table="buku" data-field="x_nama_buku" name="x<?= $Grid->RowIndex ?>_nama_buku" id="x<?= $Grid->RowIndex ?>_nama_buku" size="30" maxlength="200" placeholder="<?= HtmlEncode($Grid->nama_buku->getPlaceHolder()) ?>" value="<?= $Grid->nama_buku->EditValue ?>"<?= $Grid->nama_buku->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->nama_buku->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_buku_nama_buku">
<span<?= $Grid->nama_buku->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->nama_buku->getDisplayValue($Grid->nama_buku->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="buku" data-field="x_nama_buku" data-hidden="1" name="x<?= $Grid->RowIndex ?>_nama_buku" id="x<?= $Grid->RowIndex ?>_nama_buku" value="<?= HtmlEncode($Grid->nama_buku->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="buku" data-field="x_nama_buku" data-hidden="1" name="o<?= $Grid->RowIndex ?>_nama_buku" id="o<?= $Grid->RowIndex ?>_nama_buku" value="<?= HtmlEncode($Grid->nama_buku->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->pengarang->Visible) { // pengarang ?>
        <td data-name="pengarang">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->pengarang->getSessionValue() != "") { ?>
<span id="el$rowindex$_buku_pengarang">
<span<?= $Grid->pengarang->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->pengarang->getDisplayValue($Grid->pengarang->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_pengarang" name="x<?= $Grid->RowIndex ?>_pengarang" value="<?= HtmlEncode($Grid->pengarang->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_buku_pengarang">
    <select
        id="x<?= $Grid->RowIndex ?>_pengarang"
        name="x<?= $Grid->RowIndex ?>_pengarang"
        class="form-control ew-select<?= $Grid->pengarang->isInvalidClass() ?>"
        data-select2-id="buku_x<?= $Grid->RowIndex ?>_pengarang"
        data-table="buku"
        data-field="x_pengarang"
        data-value-separator="<?= $Grid->pengarang->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->pengarang->getPlaceHolder()) ?>"
        <?= $Grid->pengarang->editAttributes() ?>>
        <?= $Grid->pengarang->selectOptionListHtml("x{$Grid->RowIndex}_pengarang") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->pengarang->getErrorMessage() ?></div>
<?= $Grid->pengarang->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_pengarang") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='buku_x<?= $Grid->RowIndex ?>_pengarang']"),
        options = { name: "x<?= $Grid->RowIndex ?>_pengarang", selectId: "buku_x<?= $Grid->RowIndex ?>_pengarang", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.buku.fields.pengarang.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_buku_pengarang">
<span<?= $Grid->pengarang->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->pengarang->getDisplayValue($Grid->pengarang->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="buku" data-field="x_pengarang" data-hidden="1" name="x<?= $Grid->RowIndex ?>_pengarang" id="x<?= $Grid->RowIndex ?>_pengarang" value="<?= HtmlEncode($Grid->pengarang->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="buku" data-field="x_pengarang" data-hidden="1" name="o<?= $Grid->RowIndex ?>_pengarang" id="o<?= $Grid->RowIndex ?>_pengarang" value="<?= HtmlEncode($Grid->pengarang->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->penerbit->Visible) { // penerbit ?>
        <td data-name="penerbit">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->penerbit->getSessionValue() != "") { ?>
<span id="el$rowindex$_buku_penerbit">
<span<?= $Grid->penerbit->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->penerbit->getDisplayValue($Grid->penerbit->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_penerbit" name="x<?= $Grid->RowIndex ?>_penerbit" value="<?= HtmlEncode($Grid->penerbit->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_buku_penerbit">
    <select
        id="x<?= $Grid->RowIndex ?>_penerbit"
        name="x<?= $Grid->RowIndex ?>_penerbit"
        class="form-control ew-select<?= $Grid->penerbit->isInvalidClass() ?>"
        data-select2-id="buku_x<?= $Grid->RowIndex ?>_penerbit"
        data-table="buku"
        data-field="x_penerbit"
        data-value-separator="<?= $Grid->penerbit->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->penerbit->getPlaceHolder()) ?>"
        <?= $Grid->penerbit->editAttributes() ?>>
        <?= $Grid->penerbit->selectOptionListHtml("x{$Grid->RowIndex}_penerbit") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->penerbit->getErrorMessage() ?></div>
<?= $Grid->penerbit->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_penerbit") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='buku_x<?= $Grid->RowIndex ?>_penerbit']"),
        options = { name: "x<?= $Grid->RowIndex ?>_penerbit", selectId: "buku_x<?= $Grid->RowIndex ?>_penerbit", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.buku.fields.penerbit.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_buku_penerbit">
<span<?= $Grid->penerbit->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->penerbit->getDisplayValue($Grid->penerbit->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="buku" data-field="x_penerbit" data-hidden="1" name="x<?= $Grid->RowIndex ?>_penerbit" id="x<?= $Grid->RowIndex ?>_penerbit" value="<?= HtmlEncode($Grid->penerbit->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="buku" data-field="x_penerbit" data-hidden="1" name="o<?= $Grid->RowIndex ?>_penerbit" id="o<?= $Grid->RowIndex ?>_penerbit" value="<?= HtmlEncode($Grid->penerbit->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->kode_isbn->Visible) { // kode_isbn ?>
        <td data-name="kode_isbn">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_buku_kode_isbn">
<input type="<?= $Grid->kode_isbn->getInputTextType() ?>" data-table="buku" data-field="x_kode_isbn" name="x<?= $Grid->RowIndex ?>_kode_isbn" id="x<?= $Grid->RowIndex ?>_kode_isbn" size="30" maxlength="200" placeholder="<?= HtmlEncode($Grid->kode_isbn->getPlaceHolder()) ?>" value="<?= $Grid->kode_isbn->EditValue ?>"<?= $Grid->kode_isbn->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->kode_isbn->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_buku_kode_isbn">
<span<?= $Grid->kode_isbn->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->kode_isbn->getDisplayValue($Grid->kode_isbn->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="buku" data-field="x_kode_isbn" data-hidden="1" name="x<?= $Grid->RowIndex ?>_kode_isbn" id="x<?= $Grid->RowIndex ?>_kode_isbn" value="<?= HtmlEncode($Grid->kode_isbn->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="buku" data-field="x_kode_isbn" data-hidden="1" name="o<?= $Grid->RowIndex ?>_kode_isbn" id="o<?= $Grid->RowIndex ?>_kode_isbn" value="<?= HtmlEncode($Grid->kode_isbn->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fbukugrid","load"], function() {
    fbukugrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="fbukugrid">
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
    ew.addEventHandlers("buku");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
