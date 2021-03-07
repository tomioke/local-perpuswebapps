<?php

namespace PHPMaker2021\perpus;

// Set up and run Grid object
$Grid = Container("PeminjamanGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
if (!ew.vars.tables.peminjaman) ew.vars.tables.peminjaman = <?= JsonEncode(GetClientVar("tables", "peminjaman")) ?>;
var currentForm, currentPageID;
var fpeminjamangrid;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    fpeminjamangrid = new ew.Form("fpeminjamangrid", "grid");
    fpeminjamangrid.formKeyCountName = '<?= $Grid->FormKeyCountName ?>';

    // Add fields
    var fields = ew.vars.tables.peminjaman.fields;
    fpeminjamangrid.addFields([
        ["id_peminjaman", [fields.id_peminjaman.required ? ew.Validators.required(fields.id_peminjaman.caption) : null], fields.id_peminjaman.isInvalid],
        ["berita_peminjaman", [fields.berita_peminjaman.required ? ew.Validators.required(fields.berita_peminjaman.caption) : null], fields.berita_peminjaman.isInvalid],
        ["id_buku", [fields.id_buku.required ? ew.Validators.required(fields.id_buku.caption) : null], fields.id_buku.isInvalid],
        ["id_anggota", [fields.id_anggota.required ? ew.Validators.required(fields.id_anggota.caption) : null], fields.id_anggota.isInvalid],
        ["tgl_peminjaman", [fields.tgl_peminjaman.required ? ew.Validators.required(fields.tgl_peminjaman.caption) : null], fields.tgl_peminjaman.isInvalid],
        ["rencana_tgl_kembali", [fields.rencana_tgl_kembali.required ? ew.Validators.required(fields.rencana_tgl_kembali.caption) : null, ew.Validators.datetime(0)], fields.rencana_tgl_kembali.isInvalid],
        ["kondisi_buku_peminjaman", [fields.kondisi_buku_peminjaman.required ? ew.Validators.required(fields.kondisi_buku_peminjaman.caption) : null], fields.kondisi_buku_peminjaman.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fpeminjamangrid,
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
    fpeminjamangrid.validate = function () {
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
    fpeminjamangrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm();
        if (ew.valueChanged(fobj, rowIndex, "berita_peminjaman", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "id_buku", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "id_anggota", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "rencana_tgl_kembali", false))
            return false;
        if (ew.valueChanged(fobj, rowIndex, "kondisi_buku_peminjaman", false))
            return false;
        return true;
    }

    // Form_CustomValidate
    fpeminjamangrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpeminjamangrid.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fpeminjamangrid.lists.id_buku = <?= $Grid->id_buku->toClientList($Grid) ?>;
    fpeminjamangrid.lists.id_anggota = <?= $Grid->id_anggota->toClientList($Grid) ?>;
    loadjs.done("fpeminjamangrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> peminjaman">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="fpeminjamangrid" class="ew-form ew-list-form form-inline">
<div id="gmp_peminjaman" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_peminjamangrid" class="table ew-table"><!-- .ew-table -->
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
<?php if ($Grid->id_peminjaman->Visible) { // id_peminjaman ?>
        <th data-name="id_peminjaman" class="<?= $Grid->id_peminjaman->headerCellClass() ?>"><div id="elh_peminjaman_id_peminjaman" class="peminjaman_id_peminjaman"><?= $Grid->renderSort($Grid->id_peminjaman) ?></div></th>
<?php } ?>
<?php if ($Grid->berita_peminjaman->Visible) { // berita_peminjaman ?>
        <th data-name="berita_peminjaman" class="<?= $Grid->berita_peminjaman->headerCellClass() ?>"><div id="elh_peminjaman_berita_peminjaman" class="peminjaman_berita_peminjaman"><?= $Grid->renderSort($Grid->berita_peminjaman) ?></div></th>
<?php } ?>
<?php if ($Grid->id_buku->Visible) { // id_buku ?>
        <th data-name="id_buku" class="<?= $Grid->id_buku->headerCellClass() ?>"><div id="elh_peminjaman_id_buku" class="peminjaman_id_buku"><?= $Grid->renderSort($Grid->id_buku) ?></div></th>
<?php } ?>
<?php if ($Grid->id_anggota->Visible) { // id_anggota ?>
        <th data-name="id_anggota" class="<?= $Grid->id_anggota->headerCellClass() ?>"><div id="elh_peminjaman_id_anggota" class="peminjaman_id_anggota"><?= $Grid->renderSort($Grid->id_anggota) ?></div></th>
<?php } ?>
<?php if ($Grid->tgl_peminjaman->Visible) { // tgl_peminjaman ?>
        <th data-name="tgl_peminjaman" class="<?= $Grid->tgl_peminjaman->headerCellClass() ?>"><div id="elh_peminjaman_tgl_peminjaman" class="peminjaman_tgl_peminjaman"><?= $Grid->renderSort($Grid->tgl_peminjaman) ?></div></th>
<?php } ?>
<?php if ($Grid->rencana_tgl_kembali->Visible) { // rencana_tgl_kembali ?>
        <th data-name="rencana_tgl_kembali" class="<?= $Grid->rencana_tgl_kembali->headerCellClass() ?>"><div id="elh_peminjaman_rencana_tgl_kembali" class="peminjaman_rencana_tgl_kembali"><?= $Grid->renderSort($Grid->rencana_tgl_kembali) ?></div></th>
<?php } ?>
<?php if ($Grid->kondisi_buku_peminjaman->Visible) { // kondisi_buku_peminjaman ?>
        <th data-name="kondisi_buku_peminjaman" class="<?= $Grid->kondisi_buku_peminjaman->headerCellClass() ?>"><div id="elh_peminjaman_kondisi_buku_peminjaman" class="peminjaman_kondisi_buku_peminjaman"><?= $Grid->renderSort($Grid->kondisi_buku_peminjaman) ?></div></th>
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowCount, "id" => "r" . $Grid->RowCount . "_peminjaman", "data-rowtype" => $Grid->RowType]);

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
    <?php if ($Grid->id_peminjaman->Visible) { // id_peminjaman ?>
        <td data-name="id_peminjaman" <?= $Grid->id_peminjaman->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_id_peminjaman"></span>
<input type="hidden" data-table="peminjaman" data-field="x_id_peminjaman" data-hidden="1" name="o<?= $Grid->RowIndex ?>_id_peminjaman" id="o<?= $Grid->RowIndex ?>_id_peminjaman" value="<?= HtmlEncode($Grid->id_peminjaman->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_id_peminjaman">
<span<?= $Grid->id_peminjaman->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id_peminjaman->getDisplayValue($Grid->id_peminjaman->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="peminjaman" data-field="x_id_peminjaman" data-hidden="1" name="x<?= $Grid->RowIndex ?>_id_peminjaman" id="x<?= $Grid->RowIndex ?>_id_peminjaman" value="<?= HtmlEncode($Grid->id_peminjaman->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_id_peminjaman">
<span<?= $Grid->id_peminjaman->viewAttributes() ?>>
<?= $Grid->id_peminjaman->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="peminjaman" data-field="x_id_peminjaman" data-hidden="1" name="fpeminjamangrid$x<?= $Grid->RowIndex ?>_id_peminjaman" id="fpeminjamangrid$x<?= $Grid->RowIndex ?>_id_peminjaman" value="<?= HtmlEncode($Grid->id_peminjaman->FormValue) ?>">
<input type="hidden" data-table="peminjaman" data-field="x_id_peminjaman" data-hidden="1" name="fpeminjamangrid$o<?= $Grid->RowIndex ?>_id_peminjaman" id="fpeminjamangrid$o<?= $Grid->RowIndex ?>_id_peminjaman" value="<?= HtmlEncode($Grid->id_peminjaman->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->berita_peminjaman->Visible) { // berita_peminjaman ?>
        <td data-name="berita_peminjaman" <?= $Grid->berita_peminjaman->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_berita_peminjaman">
<input type="<?= $Grid->berita_peminjaman->getInputTextType() ?>" data-table="peminjaman" data-field="x_berita_peminjaman" name="x<?= $Grid->RowIndex ?>_berita_peminjaman" id="x<?= $Grid->RowIndex ?>_berita_peminjaman" size="30" maxlength="200" placeholder="<?= HtmlEncode($Grid->berita_peminjaman->getPlaceHolder()) ?>" value="<?= $Grid->berita_peminjaman->EditValue ?>"<?= $Grid->berita_peminjaman->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->berita_peminjaman->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="peminjaman" data-field="x_berita_peminjaman" data-hidden="1" name="o<?= $Grid->RowIndex ?>_berita_peminjaman" id="o<?= $Grid->RowIndex ?>_berita_peminjaman" value="<?= HtmlEncode($Grid->berita_peminjaman->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_berita_peminjaman">
<input type="<?= $Grid->berita_peminjaman->getInputTextType() ?>" data-table="peminjaman" data-field="x_berita_peminjaman" name="x<?= $Grid->RowIndex ?>_berita_peminjaman" id="x<?= $Grid->RowIndex ?>_berita_peminjaman" size="30" maxlength="200" placeholder="<?= HtmlEncode($Grid->berita_peminjaman->getPlaceHolder()) ?>" value="<?= $Grid->berita_peminjaman->EditValue ?>"<?= $Grid->berita_peminjaman->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->berita_peminjaman->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_berita_peminjaman">
<span<?= $Grid->berita_peminjaman->viewAttributes() ?>>
<?= $Grid->berita_peminjaman->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="peminjaman" data-field="x_berita_peminjaman" data-hidden="1" name="fpeminjamangrid$x<?= $Grid->RowIndex ?>_berita_peminjaman" id="fpeminjamangrid$x<?= $Grid->RowIndex ?>_berita_peminjaman" value="<?= HtmlEncode($Grid->berita_peminjaman->FormValue) ?>">
<input type="hidden" data-table="peminjaman" data-field="x_berita_peminjaman" data-hidden="1" name="fpeminjamangrid$o<?= $Grid->RowIndex ?>_berita_peminjaman" id="fpeminjamangrid$o<?= $Grid->RowIndex ?>_berita_peminjaman" value="<?= HtmlEncode($Grid->berita_peminjaman->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->id_buku->Visible) { // id_buku ?>
        <td data-name="id_buku" <?= $Grid->id_buku->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_id_buku">
    <select
        id="x<?= $Grid->RowIndex ?>_id_buku"
        name="x<?= $Grid->RowIndex ?>_id_buku"
        class="form-control ew-select<?= $Grid->id_buku->isInvalidClass() ?>"
        data-select2-id="peminjaman_x<?= $Grid->RowIndex ?>_id_buku"
        data-table="peminjaman"
        data-field="x_id_buku"
        data-value-separator="<?= $Grid->id_buku->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->id_buku->getPlaceHolder()) ?>"
        <?= $Grid->id_buku->editAttributes() ?>>
        <?= $Grid->id_buku->selectOptionListHtml("x{$Grid->RowIndex}_id_buku") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->id_buku->getErrorMessage() ?></div>
<?= $Grid->id_buku->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_id_buku") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='peminjaman_x<?= $Grid->RowIndex ?>_id_buku']"),
        options = { name: "x<?= $Grid->RowIndex ?>_id_buku", selectId: "peminjaman_x<?= $Grid->RowIndex ?>_id_buku", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.peminjaman.fields.id_buku.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="peminjaman" data-field="x_id_buku" data-hidden="1" name="o<?= $Grid->RowIndex ?>_id_buku" id="o<?= $Grid->RowIndex ?>_id_buku" value="<?= HtmlEncode($Grid->id_buku->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_id_buku">
    <select
        id="x<?= $Grid->RowIndex ?>_id_buku"
        name="x<?= $Grid->RowIndex ?>_id_buku"
        class="form-control ew-select<?= $Grid->id_buku->isInvalidClass() ?>"
        data-select2-id="peminjaman_x<?= $Grid->RowIndex ?>_id_buku"
        data-table="peminjaman"
        data-field="x_id_buku"
        data-value-separator="<?= $Grid->id_buku->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->id_buku->getPlaceHolder()) ?>"
        <?= $Grid->id_buku->editAttributes() ?>>
        <?= $Grid->id_buku->selectOptionListHtml("x{$Grid->RowIndex}_id_buku") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->id_buku->getErrorMessage() ?></div>
<?= $Grid->id_buku->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_id_buku") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='peminjaman_x<?= $Grid->RowIndex ?>_id_buku']"),
        options = { name: "x<?= $Grid->RowIndex ?>_id_buku", selectId: "peminjaman_x<?= $Grid->RowIndex ?>_id_buku", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.peminjaman.fields.id_buku.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_id_buku">
<span<?= $Grid->id_buku->viewAttributes() ?>>
<?= $Grid->id_buku->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="peminjaman" data-field="x_id_buku" data-hidden="1" name="fpeminjamangrid$x<?= $Grid->RowIndex ?>_id_buku" id="fpeminjamangrid$x<?= $Grid->RowIndex ?>_id_buku" value="<?= HtmlEncode($Grid->id_buku->FormValue) ?>">
<input type="hidden" data-table="peminjaman" data-field="x_id_buku" data-hidden="1" name="fpeminjamangrid$o<?= $Grid->RowIndex ?>_id_buku" id="fpeminjamangrid$o<?= $Grid->RowIndex ?>_id_buku" value="<?= HtmlEncode($Grid->id_buku->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->id_anggota->Visible) { // id_anggota ?>
        <td data-name="id_anggota" <?= $Grid->id_anggota->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->id_anggota->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_id_anggota">
<span<?= $Grid->id_anggota->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id_anggota->getDisplayValue($Grid->id_anggota->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_id_anggota" name="x<?= $Grid->RowIndex ?>_id_anggota" value="<?= HtmlEncode($Grid->id_anggota->CurrentValue) ?>" data-hidden="1">
<?php } elseif (!$Security->isAdmin() && $Security->isLoggedIn() && !$Grid->userIDAllow("grid")) { // Non system admin ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_id_anggota">
<span<?= $Grid->id_anggota->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id_anggota->getDisplayValue($Grid->id_anggota->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="peminjaman" data-field="x_id_anggota" data-hidden="1" name="x<?= $Grid->RowIndex ?>_id_anggota" id="x<?= $Grid->RowIndex ?>_id_anggota" value="<?= HtmlEncode($Grid->id_anggota->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_id_anggota">
    <select
        id="x<?= $Grid->RowIndex ?>_id_anggota"
        name="x<?= $Grid->RowIndex ?>_id_anggota"
        class="form-control ew-select<?= $Grid->id_anggota->isInvalidClass() ?>"
        data-select2-id="peminjaman_x<?= $Grid->RowIndex ?>_id_anggota"
        data-table="peminjaman"
        data-field="x_id_anggota"
        data-value-separator="<?= $Grid->id_anggota->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->id_anggota->getPlaceHolder()) ?>"
        <?= $Grid->id_anggota->editAttributes() ?>>
        <?= $Grid->id_anggota->selectOptionListHtml("x{$Grid->RowIndex}_id_anggota") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->id_anggota->getErrorMessage() ?></div>
<?= $Grid->id_anggota->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_id_anggota") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='peminjaman_x<?= $Grid->RowIndex ?>_id_anggota']"),
        options = { name: "x<?= $Grid->RowIndex ?>_id_anggota", selectId: "peminjaman_x<?= $Grid->RowIndex ?>_id_anggota", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.peminjaman.fields.id_anggota.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="peminjaman" data-field="x_id_anggota" data-hidden="1" name="o<?= $Grid->RowIndex ?>_id_anggota" id="o<?= $Grid->RowIndex ?>_id_anggota" value="<?= HtmlEncode($Grid->id_anggota->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->id_anggota->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_id_anggota">
<span<?= $Grid->id_anggota->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id_anggota->getDisplayValue($Grid->id_anggota->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_id_anggota" name="x<?= $Grid->RowIndex ?>_id_anggota" value="<?= HtmlEncode($Grid->id_anggota->CurrentValue) ?>" data-hidden="1">
<?php } elseif (!$Security->isAdmin() && $Security->isLoggedIn() && !$Grid->userIDAllow("grid")) { // Non system admin ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_id_anggota">
<span<?= $Grid->id_anggota->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id_anggota->getDisplayValue($Grid->id_anggota->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="peminjaman" data-field="x_id_anggota" data-hidden="1" name="x<?= $Grid->RowIndex ?>_id_anggota" id="x<?= $Grid->RowIndex ?>_id_anggota" value="<?= HtmlEncode($Grid->id_anggota->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_id_anggota">
    <select
        id="x<?= $Grid->RowIndex ?>_id_anggota"
        name="x<?= $Grid->RowIndex ?>_id_anggota"
        class="form-control ew-select<?= $Grid->id_anggota->isInvalidClass() ?>"
        data-select2-id="peminjaman_x<?= $Grid->RowIndex ?>_id_anggota"
        data-table="peminjaman"
        data-field="x_id_anggota"
        data-value-separator="<?= $Grid->id_anggota->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->id_anggota->getPlaceHolder()) ?>"
        <?= $Grid->id_anggota->editAttributes() ?>>
        <?= $Grid->id_anggota->selectOptionListHtml("x{$Grid->RowIndex}_id_anggota") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->id_anggota->getErrorMessage() ?></div>
<?= $Grid->id_anggota->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_id_anggota") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='peminjaman_x<?= $Grid->RowIndex ?>_id_anggota']"),
        options = { name: "x<?= $Grid->RowIndex ?>_id_anggota", selectId: "peminjaman_x<?= $Grid->RowIndex ?>_id_anggota", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.peminjaman.fields.id_anggota.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_id_anggota">
<span<?= $Grid->id_anggota->viewAttributes() ?>>
<?= $Grid->id_anggota->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="peminjaman" data-field="x_id_anggota" data-hidden="1" name="fpeminjamangrid$x<?= $Grid->RowIndex ?>_id_anggota" id="fpeminjamangrid$x<?= $Grid->RowIndex ?>_id_anggota" value="<?= HtmlEncode($Grid->id_anggota->FormValue) ?>">
<input type="hidden" data-table="peminjaman" data-field="x_id_anggota" data-hidden="1" name="fpeminjamangrid$o<?= $Grid->RowIndex ?>_id_anggota" id="fpeminjamangrid$o<?= $Grid->RowIndex ?>_id_anggota" value="<?= HtmlEncode($Grid->id_anggota->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->tgl_peminjaman->Visible) { // tgl_peminjaman ?>
        <td data-name="tgl_peminjaman" <?= $Grid->tgl_peminjaman->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="peminjaman" data-field="x_tgl_peminjaman" data-hidden="1" name="o<?= $Grid->RowIndex ?>_tgl_peminjaman" id="o<?= $Grid->RowIndex ?>_tgl_peminjaman" value="<?= HtmlEncode($Grid->tgl_peminjaman->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_tgl_peminjaman">
<span<?= $Grid->tgl_peminjaman->viewAttributes() ?>>
<?= $Grid->tgl_peminjaman->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="peminjaman" data-field="x_tgl_peminjaman" data-hidden="1" name="fpeminjamangrid$x<?= $Grid->RowIndex ?>_tgl_peminjaman" id="fpeminjamangrid$x<?= $Grid->RowIndex ?>_tgl_peminjaman" value="<?= HtmlEncode($Grid->tgl_peminjaman->FormValue) ?>">
<input type="hidden" data-table="peminjaman" data-field="x_tgl_peminjaman" data-hidden="1" name="fpeminjamangrid$o<?= $Grid->RowIndex ?>_tgl_peminjaman" id="fpeminjamangrid$o<?= $Grid->RowIndex ?>_tgl_peminjaman" value="<?= HtmlEncode($Grid->tgl_peminjaman->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->rencana_tgl_kembali->Visible) { // rencana_tgl_kembali ?>
        <td data-name="rencana_tgl_kembali" <?= $Grid->rencana_tgl_kembali->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_rencana_tgl_kembali">
<input type="<?= $Grid->rencana_tgl_kembali->getInputTextType() ?>" data-table="peminjaman" data-field="x_rencana_tgl_kembali" name="x<?= $Grid->RowIndex ?>_rencana_tgl_kembali" id="x<?= $Grid->RowIndex ?>_rencana_tgl_kembali" placeholder="<?= HtmlEncode($Grid->rencana_tgl_kembali->getPlaceHolder()) ?>" value="<?= $Grid->rencana_tgl_kembali->EditValue ?>"<?= $Grid->rencana_tgl_kembali->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->rencana_tgl_kembali->getErrorMessage() ?></div>
<?php if (!$Grid->rencana_tgl_kembali->ReadOnly && !$Grid->rencana_tgl_kembali->Disabled && !isset($Grid->rencana_tgl_kembali->EditAttrs["readonly"]) && !isset($Grid->rencana_tgl_kembali->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpeminjamangrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fpeminjamangrid", "x<?= $Grid->RowIndex ?>_rencana_tgl_kembali", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="peminjaman" data-field="x_rencana_tgl_kembali" data-hidden="1" name="o<?= $Grid->RowIndex ?>_rencana_tgl_kembali" id="o<?= $Grid->RowIndex ?>_rencana_tgl_kembali" value="<?= HtmlEncode($Grid->rencana_tgl_kembali->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_rencana_tgl_kembali">
<input type="<?= $Grid->rencana_tgl_kembali->getInputTextType() ?>" data-table="peminjaman" data-field="x_rencana_tgl_kembali" name="x<?= $Grid->RowIndex ?>_rencana_tgl_kembali" id="x<?= $Grid->RowIndex ?>_rencana_tgl_kembali" placeholder="<?= HtmlEncode($Grid->rencana_tgl_kembali->getPlaceHolder()) ?>" value="<?= $Grid->rencana_tgl_kembali->EditValue ?>"<?= $Grid->rencana_tgl_kembali->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->rencana_tgl_kembali->getErrorMessage() ?></div>
<?php if (!$Grid->rencana_tgl_kembali->ReadOnly && !$Grid->rencana_tgl_kembali->Disabled && !isset($Grid->rencana_tgl_kembali->EditAttrs["readonly"]) && !isset($Grid->rencana_tgl_kembali->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpeminjamangrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fpeminjamangrid", "x<?= $Grid->RowIndex ?>_rencana_tgl_kembali", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_rencana_tgl_kembali">
<span<?= $Grid->rencana_tgl_kembali->viewAttributes() ?>>
<?= $Grid->rencana_tgl_kembali->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="peminjaman" data-field="x_rencana_tgl_kembali" data-hidden="1" name="fpeminjamangrid$x<?= $Grid->RowIndex ?>_rencana_tgl_kembali" id="fpeminjamangrid$x<?= $Grid->RowIndex ?>_rencana_tgl_kembali" value="<?= HtmlEncode($Grid->rencana_tgl_kembali->FormValue) ?>">
<input type="hidden" data-table="peminjaman" data-field="x_rencana_tgl_kembali" data-hidden="1" name="fpeminjamangrid$o<?= $Grid->RowIndex ?>_rencana_tgl_kembali" id="fpeminjamangrid$o<?= $Grid->RowIndex ?>_rencana_tgl_kembali" value="<?= HtmlEncode($Grid->rencana_tgl_kembali->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->kondisi_buku_peminjaman->Visible) { // kondisi_buku_peminjaman ?>
        <td data-name="kondisi_buku_peminjaman" <?= $Grid->kondisi_buku_peminjaman->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_kondisi_buku_peminjaman">
<input type="<?= $Grid->kondisi_buku_peminjaman->getInputTextType() ?>" data-table="peminjaman" data-field="x_kondisi_buku_peminjaman" name="x<?= $Grid->RowIndex ?>_kondisi_buku_peminjaman" id="x<?= $Grid->RowIndex ?>_kondisi_buku_peminjaman" size="30" maxlength="200" placeholder="<?= HtmlEncode($Grid->kondisi_buku_peminjaman->getPlaceHolder()) ?>" value="<?= $Grid->kondisi_buku_peminjaman->EditValue ?>"<?= $Grid->kondisi_buku_peminjaman->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->kondisi_buku_peminjaman->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="peminjaman" data-field="x_kondisi_buku_peminjaman" data-hidden="1" name="o<?= $Grid->RowIndex ?>_kondisi_buku_peminjaman" id="o<?= $Grid->RowIndex ?>_kondisi_buku_peminjaman" value="<?= HtmlEncode($Grid->kondisi_buku_peminjaman->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_kondisi_buku_peminjaman">
<input type="<?= $Grid->kondisi_buku_peminjaman->getInputTextType() ?>" data-table="peminjaman" data-field="x_kondisi_buku_peminjaman" name="x<?= $Grid->RowIndex ?>_kondisi_buku_peminjaman" id="x<?= $Grid->RowIndex ?>_kondisi_buku_peminjaman" size="30" maxlength="200" placeholder="<?= HtmlEncode($Grid->kondisi_buku_peminjaman->getPlaceHolder()) ?>" value="<?= $Grid->kondisi_buku_peminjaman->EditValue ?>"<?= $Grid->kondisi_buku_peminjaman->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->kondisi_buku_peminjaman->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_peminjaman_kondisi_buku_peminjaman">
<span<?= $Grid->kondisi_buku_peminjaman->viewAttributes() ?>>
<?= $Grid->kondisi_buku_peminjaman->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="peminjaman" data-field="x_kondisi_buku_peminjaman" data-hidden="1" name="fpeminjamangrid$x<?= $Grid->RowIndex ?>_kondisi_buku_peminjaman" id="fpeminjamangrid$x<?= $Grid->RowIndex ?>_kondisi_buku_peminjaman" value="<?= HtmlEncode($Grid->kondisi_buku_peminjaman->FormValue) ?>">
<input type="hidden" data-table="peminjaman" data-field="x_kondisi_buku_peminjaman" data-hidden="1" name="fpeminjamangrid$o<?= $Grid->RowIndex ?>_kondisi_buku_peminjaman" id="fpeminjamangrid$o<?= $Grid->RowIndex ?>_kondisi_buku_peminjaman" value="<?= HtmlEncode($Grid->kondisi_buku_peminjaman->OldValue) ?>">
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
loadjs.ready(["fpeminjamangrid","load"], function () {
    fpeminjamangrid.updateLists(<?= $Grid->RowIndex ?>);
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
        $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_peminjaman", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->id_peminjaman->Visible) { // id_peminjaman ?>
        <td data-name="id_peminjaman">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_peminjaman_id_peminjaman"></span>
<?php } else { ?>
<span id="el$rowindex$_peminjaman_id_peminjaman">
<span<?= $Grid->id_peminjaman->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id_peminjaman->getDisplayValue($Grid->id_peminjaman->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="peminjaman" data-field="x_id_peminjaman" data-hidden="1" name="x<?= $Grid->RowIndex ?>_id_peminjaman" id="x<?= $Grid->RowIndex ?>_id_peminjaman" value="<?= HtmlEncode($Grid->id_peminjaman->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="peminjaman" data-field="x_id_peminjaman" data-hidden="1" name="o<?= $Grid->RowIndex ?>_id_peminjaman" id="o<?= $Grid->RowIndex ?>_id_peminjaman" value="<?= HtmlEncode($Grid->id_peminjaman->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->berita_peminjaman->Visible) { // berita_peminjaman ?>
        <td data-name="berita_peminjaman">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_peminjaman_berita_peminjaman">
<input type="<?= $Grid->berita_peminjaman->getInputTextType() ?>" data-table="peminjaman" data-field="x_berita_peminjaman" name="x<?= $Grid->RowIndex ?>_berita_peminjaman" id="x<?= $Grid->RowIndex ?>_berita_peminjaman" size="30" maxlength="200" placeholder="<?= HtmlEncode($Grid->berita_peminjaman->getPlaceHolder()) ?>" value="<?= $Grid->berita_peminjaman->EditValue ?>"<?= $Grid->berita_peminjaman->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->berita_peminjaman->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_peminjaman_berita_peminjaman">
<span<?= $Grid->berita_peminjaman->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->berita_peminjaman->getDisplayValue($Grid->berita_peminjaman->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="peminjaman" data-field="x_berita_peminjaman" data-hidden="1" name="x<?= $Grid->RowIndex ?>_berita_peminjaman" id="x<?= $Grid->RowIndex ?>_berita_peminjaman" value="<?= HtmlEncode($Grid->berita_peminjaman->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="peminjaman" data-field="x_berita_peminjaman" data-hidden="1" name="o<?= $Grid->RowIndex ?>_berita_peminjaman" id="o<?= $Grid->RowIndex ?>_berita_peminjaman" value="<?= HtmlEncode($Grid->berita_peminjaman->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->id_buku->Visible) { // id_buku ?>
        <td data-name="id_buku">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_peminjaman_id_buku">
    <select
        id="x<?= $Grid->RowIndex ?>_id_buku"
        name="x<?= $Grid->RowIndex ?>_id_buku"
        class="form-control ew-select<?= $Grid->id_buku->isInvalidClass() ?>"
        data-select2-id="peminjaman_x<?= $Grid->RowIndex ?>_id_buku"
        data-table="peminjaman"
        data-field="x_id_buku"
        data-value-separator="<?= $Grid->id_buku->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->id_buku->getPlaceHolder()) ?>"
        <?= $Grid->id_buku->editAttributes() ?>>
        <?= $Grid->id_buku->selectOptionListHtml("x{$Grid->RowIndex}_id_buku") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->id_buku->getErrorMessage() ?></div>
<?= $Grid->id_buku->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_id_buku") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='peminjaman_x<?= $Grid->RowIndex ?>_id_buku']"),
        options = { name: "x<?= $Grid->RowIndex ?>_id_buku", selectId: "peminjaman_x<?= $Grid->RowIndex ?>_id_buku", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.peminjaman.fields.id_buku.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_peminjaman_id_buku">
<span<?= $Grid->id_buku->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id_buku->getDisplayValue($Grid->id_buku->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="peminjaman" data-field="x_id_buku" data-hidden="1" name="x<?= $Grid->RowIndex ?>_id_buku" id="x<?= $Grid->RowIndex ?>_id_buku" value="<?= HtmlEncode($Grid->id_buku->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="peminjaman" data-field="x_id_buku" data-hidden="1" name="o<?= $Grid->RowIndex ?>_id_buku" id="o<?= $Grid->RowIndex ?>_id_buku" value="<?= HtmlEncode($Grid->id_buku->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->id_anggota->Visible) { // id_anggota ?>
        <td data-name="id_anggota">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->id_anggota->getSessionValue() != "") { ?>
<span id="el$rowindex$_peminjaman_id_anggota">
<span<?= $Grid->id_anggota->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id_anggota->getDisplayValue($Grid->id_anggota->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_id_anggota" name="x<?= $Grid->RowIndex ?>_id_anggota" value="<?= HtmlEncode($Grid->id_anggota->CurrentValue) ?>" data-hidden="1">
<?php } elseif (!$Security->isAdmin() && $Security->isLoggedIn() && !$Grid->userIDAllow("grid")) { // Non system admin ?>
<span id="el$rowindex$_peminjaman_id_anggota">
<span<?= $Grid->id_anggota->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id_anggota->getDisplayValue($Grid->id_anggota->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="peminjaman" data-field="x_id_anggota" data-hidden="1" name="x<?= $Grid->RowIndex ?>_id_anggota" id="x<?= $Grid->RowIndex ?>_id_anggota" value="<?= HtmlEncode($Grid->id_anggota->CurrentValue) ?>">
<?php } else { ?>
<span id="el$rowindex$_peminjaman_id_anggota">
    <select
        id="x<?= $Grid->RowIndex ?>_id_anggota"
        name="x<?= $Grid->RowIndex ?>_id_anggota"
        class="form-control ew-select<?= $Grid->id_anggota->isInvalidClass() ?>"
        data-select2-id="peminjaman_x<?= $Grid->RowIndex ?>_id_anggota"
        data-table="peminjaman"
        data-field="x_id_anggota"
        data-value-separator="<?= $Grid->id_anggota->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->id_anggota->getPlaceHolder()) ?>"
        <?= $Grid->id_anggota->editAttributes() ?>>
        <?= $Grid->id_anggota->selectOptionListHtml("x{$Grid->RowIndex}_id_anggota") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->id_anggota->getErrorMessage() ?></div>
<?= $Grid->id_anggota->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_id_anggota") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='peminjaman_x<?= $Grid->RowIndex ?>_id_anggota']"),
        options = { name: "x<?= $Grid->RowIndex ?>_id_anggota", selectId: "peminjaman_x<?= $Grid->RowIndex ?>_id_anggota", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.peminjaman.fields.id_anggota.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_peminjaman_id_anggota">
<span<?= $Grid->id_anggota->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->id_anggota->getDisplayValue($Grid->id_anggota->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="peminjaman" data-field="x_id_anggota" data-hidden="1" name="x<?= $Grid->RowIndex ?>_id_anggota" id="x<?= $Grid->RowIndex ?>_id_anggota" value="<?= HtmlEncode($Grid->id_anggota->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="peminjaman" data-field="x_id_anggota" data-hidden="1" name="o<?= $Grid->RowIndex ?>_id_anggota" id="o<?= $Grid->RowIndex ?>_id_anggota" value="<?= HtmlEncode($Grid->id_anggota->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->tgl_peminjaman->Visible) { // tgl_peminjaman ?>
        <td data-name="tgl_peminjaman">
<?php if (!$Grid->isConfirm()) { ?>
<?php } else { ?>
<span id="el$rowindex$_peminjaman_tgl_peminjaman">
<span<?= $Grid->tgl_peminjaman->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->tgl_peminjaman->getDisplayValue($Grid->tgl_peminjaman->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="peminjaman" data-field="x_tgl_peminjaman" data-hidden="1" name="x<?= $Grid->RowIndex ?>_tgl_peminjaman" id="x<?= $Grid->RowIndex ?>_tgl_peminjaman" value="<?= HtmlEncode($Grid->tgl_peminjaman->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="peminjaman" data-field="x_tgl_peminjaman" data-hidden="1" name="o<?= $Grid->RowIndex ?>_tgl_peminjaman" id="o<?= $Grid->RowIndex ?>_tgl_peminjaman" value="<?= HtmlEncode($Grid->tgl_peminjaman->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->rencana_tgl_kembali->Visible) { // rencana_tgl_kembali ?>
        <td data-name="rencana_tgl_kembali">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_peminjaman_rencana_tgl_kembali">
<input type="<?= $Grid->rencana_tgl_kembali->getInputTextType() ?>" data-table="peminjaman" data-field="x_rencana_tgl_kembali" name="x<?= $Grid->RowIndex ?>_rencana_tgl_kembali" id="x<?= $Grid->RowIndex ?>_rencana_tgl_kembali" placeholder="<?= HtmlEncode($Grid->rencana_tgl_kembali->getPlaceHolder()) ?>" value="<?= $Grid->rencana_tgl_kembali->EditValue ?>"<?= $Grid->rencana_tgl_kembali->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->rencana_tgl_kembali->getErrorMessage() ?></div>
<?php if (!$Grid->rencana_tgl_kembali->ReadOnly && !$Grid->rencana_tgl_kembali->Disabled && !isset($Grid->rencana_tgl_kembali->EditAttrs["readonly"]) && !isset($Grid->rencana_tgl_kembali->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpeminjamangrid", "datetimepicker"], function() {
    ew.createDateTimePicker("fpeminjamangrid", "x<?= $Grid->RowIndex ?>_rencana_tgl_kembali", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_peminjaman_rencana_tgl_kembali">
<span<?= $Grid->rencana_tgl_kembali->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->rencana_tgl_kembali->getDisplayValue($Grid->rencana_tgl_kembali->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="peminjaman" data-field="x_rencana_tgl_kembali" data-hidden="1" name="x<?= $Grid->RowIndex ?>_rencana_tgl_kembali" id="x<?= $Grid->RowIndex ?>_rencana_tgl_kembali" value="<?= HtmlEncode($Grid->rencana_tgl_kembali->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="peminjaman" data-field="x_rencana_tgl_kembali" data-hidden="1" name="o<?= $Grid->RowIndex ?>_rencana_tgl_kembali" id="o<?= $Grid->RowIndex ?>_rencana_tgl_kembali" value="<?= HtmlEncode($Grid->rencana_tgl_kembali->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->kondisi_buku_peminjaman->Visible) { // kondisi_buku_peminjaman ?>
        <td data-name="kondisi_buku_peminjaman">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_peminjaman_kondisi_buku_peminjaman">
<input type="<?= $Grid->kondisi_buku_peminjaman->getInputTextType() ?>" data-table="peminjaman" data-field="x_kondisi_buku_peminjaman" name="x<?= $Grid->RowIndex ?>_kondisi_buku_peminjaman" id="x<?= $Grid->RowIndex ?>_kondisi_buku_peminjaman" size="30" maxlength="200" placeholder="<?= HtmlEncode($Grid->kondisi_buku_peminjaman->getPlaceHolder()) ?>" value="<?= $Grid->kondisi_buku_peminjaman->EditValue ?>"<?= $Grid->kondisi_buku_peminjaman->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->kondisi_buku_peminjaman->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_peminjaman_kondisi_buku_peminjaman">
<span<?= $Grid->kondisi_buku_peminjaman->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->kondisi_buku_peminjaman->getDisplayValue($Grid->kondisi_buku_peminjaman->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="peminjaman" data-field="x_kondisi_buku_peminjaman" data-hidden="1" name="x<?= $Grid->RowIndex ?>_kondisi_buku_peminjaman" id="x<?= $Grid->RowIndex ?>_kondisi_buku_peminjaman" value="<?= HtmlEncode($Grid->kondisi_buku_peminjaman->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="peminjaman" data-field="x_kondisi_buku_peminjaman" data-hidden="1" name="o<?= $Grid->RowIndex ?>_kondisi_buku_peminjaman" id="o<?= $Grid->RowIndex ?>_kondisi_buku_peminjaman" value="<?= HtmlEncode($Grid->kondisi_buku_peminjaman->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fpeminjamangrid","load"], function() {
    fpeminjamangrid.updateLists(<?= $Grid->RowIndex ?>);
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
<input type="hidden" name="detailpage" value="fpeminjamangrid">
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
    ew.addEventHandlers("peminjaman");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
