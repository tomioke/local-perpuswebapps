<?php

namespace PHPMaker2021\perpus;

// Page object
$PeminjamanList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
if (!ew.vars.tables.peminjaman) ew.vars.tables.peminjaman = <?= JsonEncode(GetClientVar("tables", "peminjaman")) ?>;
var currentForm, currentPageID;
var fpeminjamanlist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fpeminjamanlist = currentForm = new ew.Form("fpeminjamanlist", "list");
    fpeminjamanlist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';

    // Add fields
    var fields = ew.vars.tables.peminjaman.fields;
    fpeminjamanlist.addFields([
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
        var f = fpeminjamanlist,
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
    fpeminjamanlist.validate = function () {
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
    fpeminjamanlist.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpeminjamanlist.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fpeminjamanlist.lists.id_buku = <?= $Page->id_buku->toClientList($Page) ?>;
    fpeminjamanlist.lists.id_anggota = <?= $Page->id_anggota->toClientList($Page) ?>;
    loadjs.done("fpeminjamanlist");
});
var fpeminjamanlistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fpeminjamanlistsrch = currentSearchForm = new ew.Form("fpeminjamanlistsrch");

    // Dynamic selection lists

    // Filters
    fpeminjamanlistsrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fpeminjamanlistsrch");
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
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction) { ?>
<form name="fpeminjamanlistsrch" id="fpeminjamanlistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl() ?>">
<div id="fpeminjamanlistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="peminjaman">
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
<form name="fpeminjamanlist" id="fpeminjamanlist" class="ew-horizontal ew-form ew-list-form ew-multi-column-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="peminjaman">
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
        $Page->RowAttrs->merge(["data-rowindex" => 0, "id" => "r0_peminjaman", "data-rowtype" => ROWTYPE_ADD]);
        $Page->RowType = ROWTYPE_ADD;

        // Render row
        $Page->renderRow();

        // Render list options
        $Page->renderListOptions();
        $Page->StartRowCount = 0;
?>
<div class="<?= $Page->getMultiColumnClass() ?>" <?= $Page->rowAttributes() ?>>
    <?php if ($Page->id_peminjaman->Visible) { // id_peminjaman ?>
        <div class="form-group row peminjaman_id_peminjaman">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_peminjaman->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_peminjaman->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_id_peminjaman"></span>
<input type="hidden" data-table="peminjaman" data-field="x_id_peminjaman" data-hidden="1" name="o<?= $Page->RowIndex ?>_id_peminjaman" id="o<?= $Page->RowIndex ?>_id_peminjaman" value="<?= HtmlEncode($Page->id_peminjaman->OldValue) ?>">
</div></div>
        </div>
    <?php } ?>
    <?php if ($Page->berita_peminjaman->Visible) { // berita_peminjaman ?>
        <div class="form-group row peminjaman_berita_peminjaman">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->berita_peminjaman->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->berita_peminjaman->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_berita_peminjaman">
<input type="<?= $Page->berita_peminjaman->getInputTextType() ?>" data-table="peminjaman" data-field="x_berita_peminjaman" name="x<?= $Page->RowIndex ?>_berita_peminjaman" id="x<?= $Page->RowIndex ?>_berita_peminjaman" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->berita_peminjaman->getPlaceHolder()) ?>" value="<?= $Page->berita_peminjaman->EditValue ?>"<?= $Page->berita_peminjaman->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->berita_peminjaman->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="peminjaman" data-field="x_berita_peminjaman" data-hidden="1" name="o<?= $Page->RowIndex ?>_berita_peminjaman" id="o<?= $Page->RowIndex ?>_berita_peminjaman" value="<?= HtmlEncode($Page->berita_peminjaman->OldValue) ?>">
</div></div>
        </div>
    <?php } ?>
    <?php if ($Page->id_buku->Visible) { // id_buku ?>
        <div class="form-group row peminjaman_id_buku">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_buku->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_buku->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_id_buku">
    <select
        id="x<?= $Page->RowIndex ?>_id_buku"
        name="x<?= $Page->RowIndex ?>_id_buku"
        class="form-control ew-select<?= $Page->id_buku->isInvalidClass() ?>"
        data-select2-id="peminjaman_x<?= $Page->RowIndex ?>_id_buku"
        data-table="peminjaman"
        data-field="x_id_buku"
        data-value-separator="<?= $Page->id_buku->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->id_buku->getPlaceHolder()) ?>"
        <?= $Page->id_buku->editAttributes() ?>>
        <?= $Page->id_buku->selectOptionListHtml("x{$Page->RowIndex}_id_buku") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->id_buku->getErrorMessage() ?></div>
<?= $Page->id_buku->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_id_buku") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='peminjaman_x<?= $Page->RowIndex ?>_id_buku']"),
        options = { name: "x<?= $Page->RowIndex ?>_id_buku", selectId: "peminjaman_x<?= $Page->RowIndex ?>_id_buku", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.peminjaman.fields.id_buku.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="peminjaman" data-field="x_id_buku" data-hidden="1" name="o<?= $Page->RowIndex ?>_id_buku" id="o<?= $Page->RowIndex ?>_id_buku" value="<?= HtmlEncode($Page->id_buku->OldValue) ?>">
</div></div>
        </div>
    <?php } ?>
    <?php if ($Page->id_anggota->Visible) { // id_anggota ?>
        <div class="form-group row peminjaman_id_anggota">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_anggota->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_anggota->cellAttributes() ?>>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn() && !$Page->userIDAllow($Page->CurrentAction)) { // Non system admin ?>
<span id="el<?= $Page->RowCount ?>_peminjaman_id_anggota">
<span<?= $Page->id_anggota->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id_anggota->getDisplayValue($Page->id_anggota->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="peminjaman" data-field="x_id_anggota" data-hidden="1" name="x<?= $Page->RowIndex ?>_id_anggota" id="x<?= $Page->RowIndex ?>_id_anggota" value="<?= HtmlEncode($Page->id_anggota->CurrentValue) ?>">
<?php } else { ?>
<span id="el<?= $Page->RowCount ?>_peminjaman_id_anggota">
    <select
        id="x<?= $Page->RowIndex ?>_id_anggota"
        name="x<?= $Page->RowIndex ?>_id_anggota"
        class="form-control ew-select<?= $Page->id_anggota->isInvalidClass() ?>"
        data-select2-id="peminjaman_x<?= $Page->RowIndex ?>_id_anggota"
        data-table="peminjaman"
        data-field="x_id_anggota"
        data-value-separator="<?= $Page->id_anggota->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->id_anggota->getPlaceHolder()) ?>"
        <?= $Page->id_anggota->editAttributes() ?>>
        <?= $Page->id_anggota->selectOptionListHtml("x{$Page->RowIndex}_id_anggota") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->id_anggota->getErrorMessage() ?></div>
<?= $Page->id_anggota->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_id_anggota") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='peminjaman_x<?= $Page->RowIndex ?>_id_anggota']"),
        options = { name: "x<?= $Page->RowIndex ?>_id_anggota", selectId: "peminjaman_x<?= $Page->RowIndex ?>_id_anggota", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.peminjaman.fields.id_anggota.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="peminjaman" data-field="x_id_anggota" data-hidden="1" name="o<?= $Page->RowIndex ?>_id_anggota" id="o<?= $Page->RowIndex ?>_id_anggota" value="<?= HtmlEncode($Page->id_anggota->OldValue) ?>">
</div></div>
        </div>
    <?php } ?>
    <?php if ($Page->tgl_peminjaman->Visible) { // tgl_peminjaman ?>
        <div class="form-group row peminjaman_tgl_peminjaman">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->tgl_peminjaman->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tgl_peminjaman->cellAttributes() ?>>
<input type="hidden" data-table="peminjaman" data-field="x_tgl_peminjaman" data-hidden="1" name="o<?= $Page->RowIndex ?>_tgl_peminjaman" id="o<?= $Page->RowIndex ?>_tgl_peminjaman" value="<?= HtmlEncode($Page->tgl_peminjaman->OldValue) ?>">
</div></div>
        </div>
    <?php } ?>
    <?php if ($Page->rencana_tgl_kembali->Visible) { // rencana_tgl_kembali ?>
        <div class="form-group row peminjaman_rencana_tgl_kembali">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->rencana_tgl_kembali->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->rencana_tgl_kembali->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_rencana_tgl_kembali">
<input type="<?= $Page->rencana_tgl_kembali->getInputTextType() ?>" data-table="peminjaman" data-field="x_rencana_tgl_kembali" name="x<?= $Page->RowIndex ?>_rencana_tgl_kembali" id="x<?= $Page->RowIndex ?>_rencana_tgl_kembali" placeholder="<?= HtmlEncode($Page->rencana_tgl_kembali->getPlaceHolder()) ?>" value="<?= $Page->rencana_tgl_kembali->EditValue ?>"<?= $Page->rencana_tgl_kembali->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->rencana_tgl_kembali->getErrorMessage() ?></div>
<?php if (!$Page->rencana_tgl_kembali->ReadOnly && !$Page->rencana_tgl_kembali->Disabled && !isset($Page->rencana_tgl_kembali->EditAttrs["readonly"]) && !isset($Page->rencana_tgl_kembali->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpeminjamanlist", "datetimepicker"], function() {
    ew.createDateTimePicker("fpeminjamanlist", "x<?= $Page->RowIndex ?>_rencana_tgl_kembali", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="peminjaman" data-field="x_rencana_tgl_kembali" data-hidden="1" name="o<?= $Page->RowIndex ?>_rencana_tgl_kembali" id="o<?= $Page->RowIndex ?>_rencana_tgl_kembali" value="<?= HtmlEncode($Page->rencana_tgl_kembali->OldValue) ?>">
</div></div>
        </div>
    <?php } ?>
    <?php if ($Page->kondisi_buku_peminjaman->Visible) { // kondisi_buku_peminjaman ?>
        <div class="form-group row peminjaman_kondisi_buku_peminjaman">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->kondisi_buku_peminjaman->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->kondisi_buku_peminjaman->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_kondisi_buku_peminjaman">
<input type="<?= $Page->kondisi_buku_peminjaman->getInputTextType() ?>" data-table="peminjaman" data-field="x_kondisi_buku_peminjaman" name="x<?= $Page->RowIndex ?>_kondisi_buku_peminjaman" id="x<?= $Page->RowIndex ?>_kondisi_buku_peminjaman" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->kondisi_buku_peminjaman->getPlaceHolder()) ?>" value="<?= $Page->kondisi_buku_peminjaman->EditValue ?>"<?= $Page->kondisi_buku_peminjaman->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->kondisi_buku_peminjaman->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="peminjaman" data-field="x_kondisi_buku_peminjaman" data-hidden="1" name="o<?= $Page->RowIndex ?>_kondisi_buku_peminjaman" id="o<?= $Page->RowIndex ?>_kondisi_buku_peminjaman" value="<?= HtmlEncode($Page->kondisi_buku_peminjaman->OldValue) ?>">
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
loadjs.ready(["fpeminjamanlist","load"], "load"], function() {
    fpeminjamanlist.updateLists(<?= $Page->RowIndex ?>);
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_peminjaman", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->id_peminjaman->Visible) { // id_peminjaman ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <tr>
            <td class="ew-table-header <?= $Page->TableLeftColumnClass ?>"><span class="peminjaman_id_peminjaman"><?= $Page->renderSort($Page->id_peminjaman) ?></span></td>
            <td <?= $Page->id_peminjaman->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_id_peminjaman">
<span<?= $Page->id_peminjaman->viewAttributes() ?>>
<?= $Page->id_peminjaman->getViewValue() ?></span>
</span>
</td>
        </tr>
        <?php } else { // Add/edit record ?>
        <div class="form-group row peminjaman_id_peminjaman">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_peminjaman->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_peminjaman->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_id_peminjaman">
<span<?= $Page->id_peminjaman->viewAttributes() ?>>
<?= $Page->id_peminjaman->getViewValue() ?></span>
</span>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->berita_peminjaman->Visible) { // berita_peminjaman ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <tr>
            <td class="ew-table-header <?= $Page->TableLeftColumnClass ?>"><span class="peminjaman_berita_peminjaman"><?= $Page->renderSort($Page->berita_peminjaman) ?></span></td>
            <td <?= $Page->berita_peminjaman->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_berita_peminjaman">
<span<?= $Page->berita_peminjaman->viewAttributes() ?>>
<?= $Page->berita_peminjaman->getViewValue() ?></span>
</span>
</td>
        </tr>
        <?php } else { // Add/edit record ?>
        <div class="form-group row peminjaman_berita_peminjaman">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->berita_peminjaman->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->berita_peminjaman->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_berita_peminjaman">
<span<?= $Page->berita_peminjaman->viewAttributes() ?>>
<?= $Page->berita_peminjaman->getViewValue() ?></span>
</span>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->id_buku->Visible) { // id_buku ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <tr>
            <td class="ew-table-header <?= $Page->TableLeftColumnClass ?>"><span class="peminjaman_id_buku"><?= $Page->renderSort($Page->id_buku) ?></span></td>
            <td <?= $Page->id_buku->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_id_buku">
<span<?= $Page->id_buku->viewAttributes() ?>>
<?= $Page->id_buku->getViewValue() ?></span>
</span>
</td>
        </tr>
        <?php } else { // Add/edit record ?>
        <div class="form-group row peminjaman_id_buku">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_buku->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_buku->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_id_buku">
<span<?= $Page->id_buku->viewAttributes() ?>>
<?= $Page->id_buku->getViewValue() ?></span>
</span>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->id_anggota->Visible) { // id_anggota ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <tr>
            <td class="ew-table-header <?= $Page->TableLeftColumnClass ?>"><span class="peminjaman_id_anggota"><?= $Page->renderSort($Page->id_anggota) ?></span></td>
            <td <?= $Page->id_anggota->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_id_anggota">
<span<?= $Page->id_anggota->viewAttributes() ?>>
<?= $Page->id_anggota->getViewValue() ?></span>
</span>
</td>
        </tr>
        <?php } else { // Add/edit record ?>
        <div class="form-group row peminjaman_id_anggota">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_anggota->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_anggota->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_id_anggota">
<span<?= $Page->id_anggota->viewAttributes() ?>>
<?= $Page->id_anggota->getViewValue() ?></span>
</span>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->tgl_peminjaman->Visible) { // tgl_peminjaman ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <tr>
            <td class="ew-table-header <?= $Page->TableLeftColumnClass ?>"><span class="peminjaman_tgl_peminjaman"><?= $Page->renderSort($Page->tgl_peminjaman) ?></span></td>
            <td <?= $Page->tgl_peminjaman->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_tgl_peminjaman">
<span<?= $Page->tgl_peminjaman->viewAttributes() ?>>
<?= $Page->tgl_peminjaman->getViewValue() ?></span>
</span>
</td>
        </tr>
        <?php } else { // Add/edit record ?>
        <div class="form-group row peminjaman_tgl_peminjaman">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->tgl_peminjaman->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tgl_peminjaman->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_tgl_peminjaman">
<span<?= $Page->tgl_peminjaman->viewAttributes() ?>>
<?= $Page->tgl_peminjaman->getViewValue() ?></span>
</span>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->rencana_tgl_kembali->Visible) { // rencana_tgl_kembali ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <tr>
            <td class="ew-table-header <?= $Page->TableLeftColumnClass ?>"><span class="peminjaman_rencana_tgl_kembali"><?= $Page->renderSort($Page->rencana_tgl_kembali) ?></span></td>
            <td <?= $Page->rencana_tgl_kembali->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_rencana_tgl_kembali">
<span<?= $Page->rencana_tgl_kembali->viewAttributes() ?>>
<?= $Page->rencana_tgl_kembali->getViewValue() ?></span>
</span>
</td>
        </tr>
        <?php } else { // Add/edit record ?>
        <div class="form-group row peminjaman_rencana_tgl_kembali">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->rencana_tgl_kembali->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->rencana_tgl_kembali->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_rencana_tgl_kembali">
<span<?= $Page->rencana_tgl_kembali->viewAttributes() ?>>
<?= $Page->rencana_tgl_kembali->getViewValue() ?></span>
</span>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->kondisi_buku_peminjaman->Visible) { // kondisi_buku_peminjaman ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <tr>
            <td class="ew-table-header <?= $Page->TableLeftColumnClass ?>"><span class="peminjaman_kondisi_buku_peminjaman"><?= $Page->renderSort($Page->kondisi_buku_peminjaman) ?></span></td>
            <td <?= $Page->kondisi_buku_peminjaman->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_kondisi_buku_peminjaman">
<span<?= $Page->kondisi_buku_peminjaman->viewAttributes() ?>>
<?= $Page->kondisi_buku_peminjaman->getViewValue() ?></span>
</span>
</td>
        </tr>
        <?php } else { // Add/edit record ?>
        <div class="form-group row peminjaman_kondisi_buku_peminjaman">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->kondisi_buku_peminjaman->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->kondisi_buku_peminjaman->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_peminjaman_kondisi_buku_peminjaman">
<span<?= $Page->kondisi_buku_peminjaman->viewAttributes() ?>>
<?= $Page->kondisi_buku_peminjaman->getViewValue() ?></span>
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
    ew.addEventHandlers("peminjaman");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
