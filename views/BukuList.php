<?php

namespace PHPMaker2021\perpusupdate;

// Page object
$BukuList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fbukulist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fbukulist = currentForm = new ew.Form("fbukulist", "list");
    fbukulist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "buku")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.buku)
        ew.vars.tables.buku = currentTable;
    fbukulist.addFields([
        ["cover", [fields.cover.visible && fields.cover.required ? ew.Validators.fileRequired(fields.cover.caption) : null], fields.cover.isInvalid],
        ["nama_buku", [fields.nama_buku.visible && fields.nama_buku.required ? ew.Validators.required(fields.nama_buku.caption) : null], fields.nama_buku.isInvalid],
        ["pengarang", [fields.pengarang.visible && fields.pengarang.required ? ew.Validators.required(fields.pengarang.caption) : null], fields.pengarang.isInvalid],
        ["penerbit", [fields.penerbit.visible && fields.penerbit.required ? ew.Validators.required(fields.penerbit.caption) : null], fields.penerbit.isInvalid],
        ["kode_isbn", [fields.kode_isbn.visible && fields.kode_isbn.required ? ew.Validators.required(fields.kode_isbn.caption) : null], fields.kode_isbn.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fbukulist,
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
    fbukulist.validate = function () {
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

            // Validate fields
            if (!this.validateFields(rowIndex))
                return false;

            // Call Form_CustomValidate event
            if (!this.customValidate(fobj)) {
                this.focus();
                return false;
            }
        }
        return true;
    }

    // Form_CustomValidate
    fbukulist.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fbukulist.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fbukulist.lists.pengarang = <?= $Page->pengarang->toClientList($Page) ?>;
    fbukulist.lists.penerbit = <?= $Page->penerbit->toClientList($Page) ?>;
    loadjs.done("fbukulist");
});
var fbukulistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fbukulistsrch = currentSearchForm = new ew.Form("fbukulistsrch");

    // Dynamic selection lists

    // Filters
    fbukulistsrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fbukulistsrch");
});
</script>
<style type="text/css">
.ew-table-preview-row { /* main table preview row color */
    background-color: #FFFFFF; /* preview row color */
}
.ew-table-preview-row .ew-grid {
    display: table;
}
</style>
<div id="ew-preview" class="d-none"><!-- preview -->
    <div class="ew-nav-tabs"><!-- .ew-nav-tabs -->
        <ul class="nav nav-tabs"></ul>
        <div class="tab-content"><!-- .tab-content -->
            <div class="tab-pane fade active show"></div>
        </div><!-- /.tab-content -->
    </div><!-- /.ew-nav-tabs -->
</div><!-- /preview -->
<script>
loadjs.ready("head", function() {
    ew.PREVIEW_PLACEMENT = ew.CSS_FLIP ? "left" : "right";
    ew.PREVIEW_SINGLE_ROW = false;
    ew.PREVIEW_OVERLAY = false;
    loadjs(ew.PATH_BASE + "js/ewpreview.js", "preview");
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
<?php if ($Page->TotalRecords > 0 && $Page->ExportOptions->visible()) { ?>
<?php $Page->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->ImportOptions->visible()) { ?>
<?php $Page->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->SearchOptions->visible()) { ?>
<?php $Page->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($Page->FilterOptions->visible()) { ?>
<?php $Page->FilterOptions->render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php } ?>
<?php if (!$Page->isExport() || Config("EXPORT_MASTER_RECORD") && $Page->isExport("print")) { ?>
<?php
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "penerbit") {
    if ($Page->MasterRecordExists) {
        include_once "views/PenerbitMaster.php";
    }
}
?>
<?php
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "pengarang") {
    if ($Page->MasterRecordExists) {
        include_once "views/PengarangMaster.php";
    }
}
?>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction) { ?>
<form name="fbukulistsrch" id="fbukulistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl() ?>">
<div id="fbukulistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="buku">
    <div class="ew-extended-search">
<div id="xsr_<?= $Page->SearchRowCount + 1 ?>" class="ew-row d-sm-flex">
    <div class="ew-quick-search input-group">
        <input type="text" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>">
        <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
        <div class="input-group-append">
            <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
            <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false"><span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span></button>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this);"><?= $Language->phrase("QuickSearchAuto") ?></a>
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "=") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this, '=');"><?= $Language->phrase("QuickSearchExact") ?></a>
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "AND") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this, 'AND');"><?= $Language->phrase("QuickSearchAll") ?></a>
                <a class="dropdown-item<?php if ($Page->BasicSearch->getType() == "OR") { ?> active<?php } ?>" href="#" onclick="return ew.setSearchType(this, 'OR');"><?= $Language->phrase("QuickSearchAny") ?></a>
            </div>
        </div>
    </div>
</div>
    </div><!-- /.ew-extended-search -->
</div><!-- /.ew-search-panel -->
</form>
<?php } ?>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if ($Page->TotalRecords > 0 || $Page->CurrentAction) { ?>
<div class="ew-multi-column-grid">
<?php if (!$Page->isExport()) { ?>
<div>
<?php if (!$Page->isGridAdd()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?= CurrentPageUrl() ?>">
<?= $Page->Pager->render() ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
<form name="fbukulist" id="fbukulist" class="ew-horizontal ew-form ew-list-form ew-multi-column-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="buku">
<?php if ($Page->getCurrentMasterTable() == "penerbit" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="penerbit">
<input type="hidden" name="fk_id_penerbit" value="<?= HtmlEncode($Page->penerbit->getSessionValue()) ?>">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "pengarang" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="pengarang">
<input type="hidden" name="fk_id_pengarang" value="<?= HtmlEncode($Page->pengarang->getSessionValue()) ?>">
<?php } ?>
<div class="row ew-multi-column-row">
<?php if ($Page->TotalRecords > 0 || $Page->isAdd() || $Page->isCopy() || $Page->isGridEdit()) { ?>
<?php
    if ($Page->isAdd() || $Page->isCopy()) {
        $Page->RowIndex = 0;
        $Page->KeyCount = $Page->RowIndex;
        if ($Page->isAdd())
            $Page->loadRowValues();
        if ($Page->EventCancelled) // Insert failed
            $Page->restoreFormValues(); // Restore form values

        // Set row properties
        $Page->resetAttributes();
        $Page->RowAttrs->merge(["data-rowindex" => 0, "id" => "r0_buku", "data-rowtype" => ROWTYPE_ADD]);
        $Page->RowType = ROWTYPE_ADD;

        // Render row
        $Page->renderRow();

        // Render list options
        $Page->renderListOptions();
        $Page->StartRowCount = 0;
?>
<div class="<?= $Page->getMultiColumnClass() ?>" <?= $Page->rowAttributes() ?>>
    <?php if ($Page->cover->Visible) { // cover ?>
        <div class="form-group row buku_cover">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->cover->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cover->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_buku_cover">
<div id="fd_x<?= $Page->RowIndex ?>_cover">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->cover->title() ?>" data-table="buku" data-field="x_cover" name="x<?= $Page->RowIndex ?>_cover" id="x<?= $Page->RowIndex ?>_cover" lang="<?= CurrentLanguageID() ?>"<?= $Page->cover->editAttributes() ?><?= ($Page->cover->ReadOnly || $Page->cover->Disabled) ? " disabled" : "" ?>>
        <label class="custom-file-label ew-file-label" for="x<?= $Page->RowIndex ?>_cover"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->cover->getErrorMessage() ?></div>
<input type="hidden" name="fn_x<?= $Page->RowIndex ?>_cover" id= "fn_x<?= $Page->RowIndex ?>_cover" value="<?= $Page->cover->Upload->FileName ?>">
<input type="hidden" name="fa_x<?= $Page->RowIndex ?>_cover" id= "fa_x<?= $Page->RowIndex ?>_cover" value="0">
<input type="hidden" name="fs_x<?= $Page->RowIndex ?>_cover" id= "fs_x<?= $Page->RowIndex ?>_cover" value="200">
<input type="hidden" name="fx_x<?= $Page->RowIndex ?>_cover" id= "fx_x<?= $Page->RowIndex ?>_cover" value="<?= $Page->cover->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x<?= $Page->RowIndex ?>_cover" id= "fm_x<?= $Page->RowIndex ?>_cover" value="<?= $Page->cover->UploadMaxFileSize ?>">
</div>
<table id="ft_x<?= $Page->RowIndex ?>_cover" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
<input type="hidden" data-table="buku" data-field="x_cover" data-hidden="1" name="o<?= $Page->RowIndex ?>_cover" id="o<?= $Page->RowIndex ?>_cover" value="<?= HtmlEncode($Page->cover->OldValue) ?>">
</div></div>
        </div>
    <?php } ?>
    <?php if ($Page->nama_buku->Visible) { // nama_buku ?>
        <div class="form-group row buku_nama_buku">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->nama_buku->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nama_buku->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_buku_nama_buku">
<input type="<?= $Page->nama_buku->getInputTextType() ?>" data-table="buku" data-field="x_nama_buku" name="x<?= $Page->RowIndex ?>_nama_buku" id="x<?= $Page->RowIndex ?>_nama_buku" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->nama_buku->getPlaceHolder()) ?>" value="<?= $Page->nama_buku->EditValue ?>"<?= $Page->nama_buku->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nama_buku->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="buku" data-field="x_nama_buku" data-hidden="1" name="o<?= $Page->RowIndex ?>_nama_buku" id="o<?= $Page->RowIndex ?>_nama_buku" value="<?= HtmlEncode($Page->nama_buku->OldValue) ?>">
</div></div>
        </div>
    <?php } ?>
    <?php if ($Page->pengarang->Visible) { // pengarang ?>
        <div class="form-group row buku_pengarang">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->pengarang->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->pengarang->cellAttributes() ?>>
<?php if ($Page->pengarang->getSessionValue() != "") { ?>
<span id="el<?= $Page->RowCount ?>_buku_pengarang">
<span<?= $Page->pengarang->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->pengarang->getDisplayValue($Page->pengarang->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Page->RowIndex ?>_pengarang" name="x<?= $Page->RowIndex ?>_pengarang" value="<?= HtmlEncode($Page->pengarang->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Page->RowCount ?>_buku_pengarang">
    <select
        id="x<?= $Page->RowIndex ?>_pengarang"
        name="x<?= $Page->RowIndex ?>_pengarang"
        class="form-control ew-select<?= $Page->pengarang->isInvalidClass() ?>"
        data-select2-id="buku_x<?= $Page->RowIndex ?>_pengarang"
        data-table="buku"
        data-field="x_pengarang"
        data-value-separator="<?= $Page->pengarang->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->pengarang->getPlaceHolder()) ?>"
        <?= $Page->pengarang->editAttributes() ?>>
        <?= $Page->pengarang->selectOptionListHtml("x{$Page->RowIndex}_pengarang") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->pengarang->getErrorMessage() ?></div>
<?= $Page->pengarang->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_pengarang") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='buku_x<?= $Page->RowIndex ?>_pengarang']"),
        options = { name: "x<?= $Page->RowIndex ?>_pengarang", selectId: "buku_x<?= $Page->RowIndex ?>_pengarang", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.buku.fields.pengarang.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="buku" data-field="x_pengarang" data-hidden="1" name="o<?= $Page->RowIndex ?>_pengarang" id="o<?= $Page->RowIndex ?>_pengarang" value="<?= HtmlEncode($Page->pengarang->OldValue) ?>">
</div></div>
        </div>
    <?php } ?>
    <?php if ($Page->penerbit->Visible) { // penerbit ?>
        <div class="form-group row buku_penerbit">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->penerbit->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->penerbit->cellAttributes() ?>>
<?php if ($Page->penerbit->getSessionValue() != "") { ?>
<span id="el<?= $Page->RowCount ?>_buku_penerbit">
<span<?= $Page->penerbit->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->penerbit->getDisplayValue($Page->penerbit->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Page->RowIndex ?>_penerbit" name="x<?= $Page->RowIndex ?>_penerbit" value="<?= HtmlEncode($Page->penerbit->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Page->RowCount ?>_buku_penerbit">
    <select
        id="x<?= $Page->RowIndex ?>_penerbit"
        name="x<?= $Page->RowIndex ?>_penerbit"
        class="form-control ew-select<?= $Page->penerbit->isInvalidClass() ?>"
        data-select2-id="buku_x<?= $Page->RowIndex ?>_penerbit"
        data-table="buku"
        data-field="x_penerbit"
        data-value-separator="<?= $Page->penerbit->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->penerbit->getPlaceHolder()) ?>"
        <?= $Page->penerbit->editAttributes() ?>>
        <?= $Page->penerbit->selectOptionListHtml("x{$Page->RowIndex}_penerbit") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->penerbit->getErrorMessage() ?></div>
<?= $Page->penerbit->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_penerbit") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='buku_x<?= $Page->RowIndex ?>_penerbit']"),
        options = { name: "x<?= $Page->RowIndex ?>_penerbit", selectId: "buku_x<?= $Page->RowIndex ?>_penerbit", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.buku.fields.penerbit.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="buku" data-field="x_penerbit" data-hidden="1" name="o<?= $Page->RowIndex ?>_penerbit" id="o<?= $Page->RowIndex ?>_penerbit" value="<?= HtmlEncode($Page->penerbit->OldValue) ?>">
</div></div>
        </div>
    <?php } ?>
    <?php if ($Page->kode_isbn->Visible) { // kode_isbn ?>
        <div class="form-group row buku_kode_isbn">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->kode_isbn->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->kode_isbn->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_buku_kode_isbn">
<input type="<?= $Page->kode_isbn->getInputTextType() ?>" data-table="buku" data-field="x_kode_isbn" name="x<?= $Page->RowIndex ?>_kode_isbn" id="x<?= $Page->RowIndex ?>_kode_isbn" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->kode_isbn->getPlaceHolder()) ?>" value="<?= $Page->kode_isbn->EditValue ?>"<?= $Page->kode_isbn->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->kode_isbn->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="buku" data-field="x_kode_isbn" data-hidden="1" name="o<?= $Page->RowIndex ?>_kode_isbn" id="o<?= $Page->RowIndex ?>_kode_isbn" value="<?= HtmlEncode($Page->kode_isbn->OldValue) ?>">
</div></div>
        </div>
    <?php } ?>
<div class="ew-multi-column-list-option">
<?php
// Render list options (body, bottom)
$Page->ListOptions->render("body", "bottom", $Page->RowCount);
?>
</div>
<div class="clearfix"></div>
</div>
<script>
loadjs.ready(["fbukulist","load"], "load"], function() {
    fbukulist.updateLists(<?= $Page->RowIndex ?>);
});
</script>
<?php
    }
?>
<?php
if ($Page->ExportAll && $Page->isExport()) {
    $Page->StopRecord = $Page->TotalRecords;
} else {
    // Set the last record to display
    if ($Page->TotalRecords > $Page->StartRecord + $Page->DisplayRecords - 1) {
        $Page->StopRecord = $Page->StartRecord + $Page->DisplayRecords - 1;
    } else {
        $Page->StopRecord = $Page->TotalRecords;
    }
}

// Restore number of post back records
if ($CurrentForm && ($Page->isConfirm() || $Page->EventCancelled)) {
    $CurrentForm->Index = -1;
    if ($CurrentForm->hasValue($Page->FormKeyCountName) && ($Page->isGridAdd() || $Page->isGridEdit() || $Page->isConfirm())) {
        $Page->KeyCount = $CurrentForm->getValue($Page->FormKeyCountName);
        $Page->StopRecord = $Page->StartRecord + $Page->KeyCount - 1;
    }
}
$Page->RecordCount = $Page->StartRecord - 1;
if ($Page->Recordset && !$Page->Recordset->EOF) {
    // Nothing to do
} elseif (!$Page->AllowAddDeleteRow && $Page->StopRecord == 0) {
    $Page->StopRecord = $Page->GridAddRowCount;
}
while ($Page->RecordCount < $Page->StopRecord) {
    $Page->RecordCount++;
    if ($Page->RecordCount >= $Page->StartRecord) {
        $Page->RowCount++;

        // Set up key count
        $Page->KeyCount = $Page->RowIndex;

        // Init row class and style
        $Page->resetAttributes();
        $Page->CssClass = "";
        if ($Page->isGridAdd()) {
            $Page->loadRowValues(); // Load default values
            $Page->OldKey = "";
            $Page->setKey($Page->OldKey);
        } else {
            $Page->loadRowValues($Page->Recordset); // Load row values
            if ($Page->isGridEdit()) {
                $Page->OldKey = $Page->getKey(true); // Get from CurrentValue
                $Page->setKey($Page->OldKey);
            }
        }
        $Page->RowType = ROWTYPE_VIEW; // Render view

        // Set up row id / data-rowindex
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_buku", "data-rowtype" => $Page->RowType]);

        // Render row
        $Page->renderRow();

        // Render list options
        $Page->renderListOptions();
?>
<div class="<?= $Page->getMultiColumnClass() ?>" <?= $Page->rowAttributes() ?>>
    <div class="card ew-card">
    <div class="card-body">
    <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
    <table class="table table-striped table-sm ew-view-table">
    <?php } ?>
    <?php if ($Page->cover->Visible) { // cover ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <tr>
            <td class="ew-table-header <?= $Page->TableLeftColumnClass ?>"><span class="buku_cover"><?= $Page->renderSort($Page->cover) ?></span></td>
            <td <?= $Page->cover->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_buku_cover">
<span>
<?= GetFileViewTag($Page->cover, $Page->cover->getViewValue(), false) ?>
</span>
</span>
</td>
        </tr>
        <?php } else { // Add/edit record ?>
        <div class="form-group row buku_cover">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->cover->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cover->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_buku_cover">
<span>
<?= GetFileViewTag($Page->cover, $Page->cover->getViewValue(), false) ?>
</span>
</span>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->nama_buku->Visible) { // nama_buku ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <tr>
            <td class="ew-table-header <?= $Page->TableLeftColumnClass ?>"><span class="buku_nama_buku"><?= $Page->renderSort($Page->nama_buku) ?></span></td>
            <td <?= $Page->nama_buku->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_buku_nama_buku">
<span<?= $Page->nama_buku->viewAttributes() ?>>
<?= $Page->nama_buku->getViewValue() ?></span>
</span>
</td>
        </tr>
        <?php } else { // Add/edit record ?>
        <div class="form-group row buku_nama_buku">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->nama_buku->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nama_buku->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_buku_nama_buku">
<span<?= $Page->nama_buku->viewAttributes() ?>>
<?= $Page->nama_buku->getViewValue() ?></span>
</span>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->pengarang->Visible) { // pengarang ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <tr>
            <td class="ew-table-header <?= $Page->TableLeftColumnClass ?>"><span class="buku_pengarang"><?= $Page->renderSort($Page->pengarang) ?></span></td>
            <td <?= $Page->pengarang->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_buku_pengarang">
<span<?= $Page->pengarang->viewAttributes() ?>>
<?= $Page->pengarang->getViewValue() ?></span>
</span>
</td>
        </tr>
        <?php } else { // Add/edit record ?>
        <div class="form-group row buku_pengarang">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->pengarang->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->pengarang->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_buku_pengarang">
<span<?= $Page->pengarang->viewAttributes() ?>>
<?= $Page->pengarang->getViewValue() ?></span>
</span>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->penerbit->Visible) { // penerbit ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <tr>
            <td class="ew-table-header <?= $Page->TableLeftColumnClass ?>"><span class="buku_penerbit"><?= $Page->renderSort($Page->penerbit) ?></span></td>
            <td <?= $Page->penerbit->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_buku_penerbit">
<span<?= $Page->penerbit->viewAttributes() ?>>
<?= $Page->penerbit->getViewValue() ?></span>
</span>
</td>
        </tr>
        <?php } else { // Add/edit record ?>
        <div class="form-group row buku_penerbit">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->penerbit->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->penerbit->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_buku_penerbit">
<span<?= $Page->penerbit->viewAttributes() ?>>
<?= $Page->penerbit->getViewValue() ?></span>
</span>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->kode_isbn->Visible) { // kode_isbn ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <tr>
            <td class="ew-table-header <?= $Page->TableLeftColumnClass ?>"><span class="buku_kode_isbn"><?= $Page->renderSort($Page->kode_isbn) ?></span></td>
            <td <?= $Page->kode_isbn->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_buku_kode_isbn">
<span<?= $Page->kode_isbn->viewAttributes() ?>>
<?= $Page->kode_isbn->getViewValue() ?></span>
</span>
</td>
        </tr>
        <?php } else { // Add/edit record ?>
        <div class="form-group row buku_kode_isbn">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->kode_isbn->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->kode_isbn->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_buku_kode_isbn">
<span<?= $Page->kode_isbn->viewAttributes() ?>>
<?= $Page->kode_isbn->getViewValue() ?></span>
</span>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
    </table>
    <?php } ?>
    </div><!-- /.card-body -->
<?php if (!$Page->isExport()) { ?>
    <div class="card-footer">
        <div class="ew-multi-column-list-option">
<?php
// Render list options (body, bottom)
$Page->ListOptions->render("body", "bottom", $Page->RowCount);
?>
        </div><!-- /.ew-multi-column-list-option -->
        <div class="clearfix"></div>
    </div><!-- /.card-footer -->
<?php } ?>
    </div><!-- /.card -->
</div><!-- /.col-* -->
<?php
    }
    if (!$Page->isGridAdd()) {
        $Page->Recordset->moveNext();
    }
}
?>
<?php } ?>
</div><!-- /.ew-multi-column-row -->
<?php if ($Page->isAdd() || $Page->isCopy()) { ?>
<input type="hidden" name="<?= $Page->FormKeyCountName ?>" id="<?= $Page->FormKeyCountName ?>" value="<?= $Page->KeyCount ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php } ?>
<?php if (!$Page->CurrentAction) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</form><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Page->Recordset) {
    $Page->Recordset->close();
}
?>
<?php if (!$Page->isExport()) { ?>
<div>
<?php if (!$Page->isGridAdd()) { ?>
<form name="ew-pager-form" class="form-inline ew-form ew-pager-form" action="<?= CurrentPageUrl() ?>">
<?= $Page->Pager->render() ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body", "bottom") ?>
</div>
<div class="clearfix"></div>
</div>
<?php } ?>
</div><!-- /.ew-multi-column-grid -->
<?php } ?>
<?php if ($Page->TotalRecords == 0 && !$Page->CurrentAction) { // Show other options ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
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
