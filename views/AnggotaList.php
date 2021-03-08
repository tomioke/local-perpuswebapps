<?php

namespace PHPMaker2021\perpusupdate;

// Page object
$AnggotaList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fanggotalist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fanggotalist = currentForm = new ew.Form("fanggotalist", "list");
    fanggotalist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "anggota")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.anggota)
        ew.vars.tables.anggota = currentTable;
    fanggotalist.addFields([
        ["id_anggota", [fields.id_anggota.visible && fields.id_anggota.required ? ew.Validators.required(fields.id_anggota.caption) : null], fields.id_anggota.isInvalid],
        ["nama_anggota", [fields.nama_anggota.visible && fields.nama_anggota.required ? ew.Validators.required(fields.nama_anggota.caption) : null], fields.nama_anggota.isInvalid],
        ["alamat", [fields.alamat.visible && fields.alamat.required ? ew.Validators.required(fields.alamat.caption) : null], fields.alamat.isInvalid],
        ["tgl_lahir", [fields.tgl_lahir.visible && fields.tgl_lahir.required ? ew.Validators.required(fields.tgl_lahir.caption) : null, ew.Validators.datetime(0)], fields.tgl_lahir.isInvalid],
        ["tmp_lahir", [fields.tmp_lahir.visible && fields.tmp_lahir.required ? ew.Validators.required(fields.tmp_lahir.caption) : null], fields.tmp_lahir.isInvalid],
        ["_username", [fields._username.visible && fields._username.required ? ew.Validators.required(fields._username.caption) : null], fields._username.isInvalid],
        ["_password", [fields._password.visible && fields._password.required ? ew.Validators.required(fields._password.caption) : null], fields._password.isInvalid],
        ["id_level", [fields.id_level.visible && fields.id_level.required ? ew.Validators.required(fields.id_level.caption) : null], fields.id_level.isInvalid],
        ["no_handphone", [fields.no_handphone.visible && fields.no_handphone.required ? ew.Validators.required(fields.no_handphone.caption) : null], fields.no_handphone.isInvalid],
        ["_email", [fields._email.visible && fields._email.required ? ew.Validators.required(fields._email.caption) : null, ew.Validators.email], fields._email.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fanggotalist,
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
    fanggotalist.validate = function () {
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
    fanggotalist.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fanggotalist.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fanggotalist.lists.id_level = <?= $Page->id_level->toClientList($Page) ?>;
    loadjs.done("fanggotalist");
});
var fanggotalistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fanggotalistsrch = currentSearchForm = new ew.Form("fanggotalistsrch");

    // Dynamic selection lists

    // Filters
    fanggotalistsrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fanggotalistsrch");
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
<form name="fanggotalistsrch" id="fanggotalistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl() ?>">
<div id="fanggotalistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="anggota">
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
<form name="fanggotalist" id="fanggotalist" class="ew-horizontal ew-form ew-list-form ew-multi-column-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="anggota">
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
        $Page->RowAttrs->merge(["data-rowindex" => 0, "id" => "r0_anggota", "data-rowtype" => ROWTYPE_ADD]);
        $Page->RowType = ROWTYPE_ADD;

        // Render row
        $Page->renderRow();

        // Render list options
        $Page->renderListOptions();
        $Page->StartRowCount = 0;
?>
<div class="<?= $Page->getMultiColumnClass() ?>" <?= $Page->rowAttributes() ?>>
    <?php if ($Page->id_anggota->Visible) { // id_anggota ?>
        <div class="form-group row anggota_id_anggota">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_anggota->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_anggota->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_id_anggota"></span>
<input type="hidden" data-table="anggota" data-field="x_id_anggota" data-hidden="1" name="o<?= $Page->RowIndex ?>_id_anggota" id="o<?= $Page->RowIndex ?>_id_anggota" value="<?= HtmlEncode($Page->id_anggota->OldValue) ?>">
</div></div>
        </div>
    <?php } ?>
    <?php if ($Page->nama_anggota->Visible) { // nama_anggota ?>
        <div class="form-group row anggota_nama_anggota">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->nama_anggota->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nama_anggota->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_nama_anggota">
<input type="<?= $Page->nama_anggota->getInputTextType() ?>" data-table="anggota" data-field="x_nama_anggota" name="x<?= $Page->RowIndex ?>_nama_anggota" id="x<?= $Page->RowIndex ?>_nama_anggota" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->nama_anggota->getPlaceHolder()) ?>" value="<?= $Page->nama_anggota->EditValue ?>"<?= $Page->nama_anggota->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->nama_anggota->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="anggota" data-field="x_nama_anggota" data-hidden="1" name="o<?= $Page->RowIndex ?>_nama_anggota" id="o<?= $Page->RowIndex ?>_nama_anggota" value="<?= HtmlEncode($Page->nama_anggota->OldValue) ?>">
</div></div>
        </div>
    <?php } ?>
    <?php if ($Page->alamat->Visible) { // alamat ?>
        <div class="form-group row anggota_alamat">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->alamat->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->alamat->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_alamat">
<input type="<?= $Page->alamat->getInputTextType() ?>" data-table="anggota" data-field="x_alamat" name="x<?= $Page->RowIndex ?>_alamat" id="x<?= $Page->RowIndex ?>_alamat" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->alamat->getPlaceHolder()) ?>" value="<?= $Page->alamat->EditValue ?>"<?= $Page->alamat->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->alamat->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="anggota" data-field="x_alamat" data-hidden="1" name="o<?= $Page->RowIndex ?>_alamat" id="o<?= $Page->RowIndex ?>_alamat" value="<?= HtmlEncode($Page->alamat->OldValue) ?>">
</div></div>
        </div>
    <?php } ?>
    <?php if ($Page->tgl_lahir->Visible) { // tgl_lahir ?>
        <div class="form-group row anggota_tgl_lahir">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->tgl_lahir->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tgl_lahir->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_tgl_lahir">
<input type="<?= $Page->tgl_lahir->getInputTextType() ?>" data-table="anggota" data-field="x_tgl_lahir" name="x<?= $Page->RowIndex ?>_tgl_lahir" id="x<?= $Page->RowIndex ?>_tgl_lahir" placeholder="<?= HtmlEncode($Page->tgl_lahir->getPlaceHolder()) ?>" value="<?= $Page->tgl_lahir->EditValue ?>"<?= $Page->tgl_lahir->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->tgl_lahir->getErrorMessage() ?></div>
<?php if (!$Page->tgl_lahir->ReadOnly && !$Page->tgl_lahir->Disabled && !isset($Page->tgl_lahir->EditAttrs["readonly"]) && !isset($Page->tgl_lahir->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fanggotalist", "datetimepicker"], function() {
    ew.createDateTimePicker("fanggotalist", "x<?= $Page->RowIndex ?>_tgl_lahir", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="anggota" data-field="x_tgl_lahir" data-hidden="1" name="o<?= $Page->RowIndex ?>_tgl_lahir" id="o<?= $Page->RowIndex ?>_tgl_lahir" value="<?= HtmlEncode($Page->tgl_lahir->OldValue) ?>">
</div></div>
        </div>
    <?php } ?>
    <?php if ($Page->tmp_lahir->Visible) { // tmp_lahir ?>
        <div class="form-group row anggota_tmp_lahir">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->tmp_lahir->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tmp_lahir->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_tmp_lahir">
<input type="<?= $Page->tmp_lahir->getInputTextType() ?>" data-table="anggota" data-field="x_tmp_lahir" name="x<?= $Page->RowIndex ?>_tmp_lahir" id="x<?= $Page->RowIndex ?>_tmp_lahir" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->tmp_lahir->getPlaceHolder()) ?>" value="<?= $Page->tmp_lahir->EditValue ?>"<?= $Page->tmp_lahir->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->tmp_lahir->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="anggota" data-field="x_tmp_lahir" data-hidden="1" name="o<?= $Page->RowIndex ?>_tmp_lahir" id="o<?= $Page->RowIndex ?>_tmp_lahir" value="<?= HtmlEncode($Page->tmp_lahir->OldValue) ?>">
</div></div>
        </div>
    <?php } ?>
    <?php if ($Page->_username->Visible) { // username ?>
        <div class="form-group row anggota__username">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->_username->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_username->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota__username">
<input type="<?= $Page->_username->getInputTextType() ?>" data-table="anggota" data-field="x__username" name="x<?= $Page->RowIndex ?>__username" id="x<?= $Page->RowIndex ?>__username" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->_username->getPlaceHolder()) ?>" value="<?= $Page->_username->EditValue ?>"<?= $Page->_username->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->_username->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="anggota" data-field="x__username" data-hidden="1" name="o<?= $Page->RowIndex ?>__username" id="o<?= $Page->RowIndex ?>__username" value="<?= HtmlEncode($Page->_username->OldValue) ?>">
</div></div>
        </div>
    <?php } ?>
    <?php if ($Page->_password->Visible) { // password ?>
        <div class="form-group row anggota__password">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->_password->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_password->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota__password">
<div class="input-group" id="ig<?= $Page->RowIndex ?>__password">
    <input type="password" autocomplete="new-password" data-table="anggota" data-field="x__password" name="x<?= $Page->RowIndex ?>__password" id="x<?= $Page->RowIndex ?>__password" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->_password->getPlaceHolder()) ?>"<?= $Page->_password->editAttributes() ?>>
    <div class="input-group-append">
        <button type="button" class="btn btn-default ew-toggle-password" onclick="ew.togglePassword(event);"><i class="fas fa-eye"></i></button>
        <button type="button" class="btn btn-default ew-password-generator rounded-right" title="<?= HtmlTitle($Language->phrase("GeneratePassword")) ?>" data-password-field="x<?= $Page->RowIndex ?>__password" data-password-confirm="c<?= $Page->RowIndex ?>__password"><?= $Language->phrase("GeneratePassword") ?></button>
    </div>
</div>
<div class="invalid-feedback"><?= $Page->_password->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="anggota" data-field="x__password" data-hidden="1" name="o<?= $Page->RowIndex ?>__password" id="o<?= $Page->RowIndex ?>__password" value="<?= HtmlEncode($Page->_password->OldValue) ?>">
</div></div>
        </div>
    <?php } ?>
    <?php if ($Page->id_level->Visible) { // id_level ?>
        <div class="form-group row anggota_id_level">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_level->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_level->cellAttributes() ?>>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin ?>
<span id="el<?= $Page->RowCount ?>_anggota_id_level">
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id_level->getDisplayValue($Page->id_level->EditValue))) ?>">
</span>
<?php } else { ?>
<span id="el<?= $Page->RowCount ?>_anggota_id_level">
    <select
        id="x<?= $Page->RowIndex ?>_id_level"
        name="x<?= $Page->RowIndex ?>_id_level"
        class="form-control ew-select<?= $Page->id_level->isInvalidClass() ?>"
        data-select2-id="anggota_x<?= $Page->RowIndex ?>_id_level"
        data-table="anggota"
        data-field="x_id_level"
        data-value-separator="<?= $Page->id_level->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->id_level->getPlaceHolder()) ?>"
        <?= $Page->id_level->editAttributes() ?>>
        <?= $Page->id_level->selectOptionListHtml("x{$Page->RowIndex}_id_level") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->id_level->getErrorMessage() ?></div>
<?= $Page->id_level->Lookup->getParamTag($Page, "p_x" . $Page->RowIndex . "_id_level") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='anggota_x<?= $Page->RowIndex ?>_id_level']"),
        options = { name: "x<?= $Page->RowIndex ?>_id_level", selectId: "anggota_x<?= $Page->RowIndex ?>_id_level", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.anggota.fields.id_level.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<input type="hidden" data-table="anggota" data-field="x_id_level" data-hidden="1" name="o<?= $Page->RowIndex ?>_id_level" id="o<?= $Page->RowIndex ?>_id_level" value="<?= HtmlEncode($Page->id_level->OldValue) ?>">
</div></div>
        </div>
    <?php } ?>
    <?php if ($Page->no_handphone->Visible) { // no_handphone ?>
        <div class="form-group row anggota_no_handphone">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->no_handphone->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->no_handphone->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_no_handphone">
<input type="<?= $Page->no_handphone->getInputTextType() ?>" data-table="anggota" data-field="x_no_handphone" name="x<?= $Page->RowIndex ?>_no_handphone" id="x<?= $Page->RowIndex ?>_no_handphone" size="30" maxlength="24" placeholder="<?= HtmlEncode($Page->no_handphone->getPlaceHolder()) ?>" value="<?= $Page->no_handphone->EditValue ?>"<?= $Page->no_handphone->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->no_handphone->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="anggota" data-field="x_no_handphone" data-hidden="1" name="o<?= $Page->RowIndex ?>_no_handphone" id="o<?= $Page->RowIndex ?>_no_handphone" value="<?= HtmlEncode($Page->no_handphone->OldValue) ?>">
</div></div>
        </div>
    <?php } ?>
    <?php if ($Page->_email->Visible) { // email ?>
        <div class="form-group row anggota__email">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->_email->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_email->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota__email">
<input type="<?= $Page->_email->getInputTextType() ?>" data-table="anggota" data-field="x__email" name="x<?= $Page->RowIndex ?>__email" id="x<?= $Page->RowIndex ?>__email" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->_email->getPlaceHolder()) ?>" value="<?= $Page->_email->EditValue ?>"<?= $Page->_email->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->_email->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="anggota" data-field="x__email" data-hidden="1" name="o<?= $Page->RowIndex ?>__email" id="o<?= $Page->RowIndex ?>__email" value="<?= HtmlEncode($Page->_email->OldValue) ?>">
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
loadjs.ready(["fanggotalist","load"], "load"], function() {
    fanggotalist.updateLists(<?= $Page->RowIndex ?>);
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_anggota", "data-rowtype" => $Page->RowType]);

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
    <?php if ($Page->id_anggota->Visible) { // id_anggota ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <tr>
            <td class="ew-table-header <?= $Page->TableLeftColumnClass ?>"><span class="anggota_id_anggota"><?= $Page->renderSort($Page->id_anggota) ?></span></td>
            <td <?= $Page->id_anggota->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_id_anggota">
<span<?= $Page->id_anggota->viewAttributes() ?>>
<?= $Page->id_anggota->getViewValue() ?></span>
</span>
</td>
        </tr>
        <?php } else { // Add/edit record ?>
        <div class="form-group row anggota_id_anggota">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_anggota->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_anggota->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_id_anggota">
<span<?= $Page->id_anggota->viewAttributes() ?>>
<?= $Page->id_anggota->getViewValue() ?></span>
</span>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->nama_anggota->Visible) { // nama_anggota ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <tr>
            <td class="ew-table-header <?= $Page->TableLeftColumnClass ?>"><span class="anggota_nama_anggota"><?= $Page->renderSort($Page->nama_anggota) ?></span></td>
            <td <?= $Page->nama_anggota->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_nama_anggota">
<span<?= $Page->nama_anggota->viewAttributes() ?>>
<?= $Page->nama_anggota->getViewValue() ?></span>
</span>
</td>
        </tr>
        <?php } else { // Add/edit record ?>
        <div class="form-group row anggota_nama_anggota">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->nama_anggota->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nama_anggota->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_nama_anggota">
<span<?= $Page->nama_anggota->viewAttributes() ?>>
<?= $Page->nama_anggota->getViewValue() ?></span>
</span>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->alamat->Visible) { // alamat ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <tr>
            <td class="ew-table-header <?= $Page->TableLeftColumnClass ?>"><span class="anggota_alamat"><?= $Page->renderSort($Page->alamat) ?></span></td>
            <td <?= $Page->alamat->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_alamat">
<span<?= $Page->alamat->viewAttributes() ?>>
<?= $Page->alamat->getViewValue() ?></span>
</span>
</td>
        </tr>
        <?php } else { // Add/edit record ?>
        <div class="form-group row anggota_alamat">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->alamat->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->alamat->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_alamat">
<span<?= $Page->alamat->viewAttributes() ?>>
<?= $Page->alamat->getViewValue() ?></span>
</span>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->tgl_lahir->Visible) { // tgl_lahir ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <tr>
            <td class="ew-table-header <?= $Page->TableLeftColumnClass ?>"><span class="anggota_tgl_lahir"><?= $Page->renderSort($Page->tgl_lahir) ?></span></td>
            <td <?= $Page->tgl_lahir->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_tgl_lahir">
<span<?= $Page->tgl_lahir->viewAttributes() ?>>
<?= $Page->tgl_lahir->getViewValue() ?></span>
</span>
</td>
        </tr>
        <?php } else { // Add/edit record ?>
        <div class="form-group row anggota_tgl_lahir">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->tgl_lahir->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tgl_lahir->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_tgl_lahir">
<span<?= $Page->tgl_lahir->viewAttributes() ?>>
<?= $Page->tgl_lahir->getViewValue() ?></span>
</span>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->tmp_lahir->Visible) { // tmp_lahir ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <tr>
            <td class="ew-table-header <?= $Page->TableLeftColumnClass ?>"><span class="anggota_tmp_lahir"><?= $Page->renderSort($Page->tmp_lahir) ?></span></td>
            <td <?= $Page->tmp_lahir->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_tmp_lahir">
<span<?= $Page->tmp_lahir->viewAttributes() ?>>
<?= $Page->tmp_lahir->getViewValue() ?></span>
</span>
</td>
        </tr>
        <?php } else { // Add/edit record ?>
        <div class="form-group row anggota_tmp_lahir">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->tmp_lahir->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tmp_lahir->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_tmp_lahir">
<span<?= $Page->tmp_lahir->viewAttributes() ?>>
<?= $Page->tmp_lahir->getViewValue() ?></span>
</span>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->_username->Visible) { // username ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <tr>
            <td class="ew-table-header <?= $Page->TableLeftColumnClass ?>"><span class="anggota__username"><?= $Page->renderSort($Page->_username) ?></span></td>
            <td <?= $Page->_username->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</td>
        </tr>
        <?php } else { // Add/edit record ?>
        <div class="form-group row anggota__username">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->_username->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_username->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota__username">
<span<?= $Page->_username->viewAttributes() ?>>
<?= $Page->_username->getViewValue() ?></span>
</span>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->_password->Visible) { // password ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <tr>
            <td class="ew-table-header <?= $Page->TableLeftColumnClass ?>"><span class="anggota__password"><?= $Page->renderSort($Page->_password) ?></span></td>
            <td <?= $Page->_password->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota__password">
<span<?= $Page->_password->viewAttributes() ?>>
<?= $Page->_password->getViewValue() ?></span>
</span>
</td>
        </tr>
        <?php } else { // Add/edit record ?>
        <div class="form-group row anggota__password">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->_password->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_password->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota__password">
<span<?= $Page->_password->viewAttributes() ?>>
<?= $Page->_password->getViewValue() ?></span>
</span>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->id_level->Visible) { // id_level ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <tr>
            <td class="ew-table-header <?= $Page->TableLeftColumnClass ?>"><span class="anggota_id_level"><?= $Page->renderSort($Page->id_level) ?></span></td>
            <td <?= $Page->id_level->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_id_level">
<span<?= $Page->id_level->viewAttributes() ?>>
<?= $Page->id_level->getViewValue() ?></span>
</span>
</td>
        </tr>
        <?php } else { // Add/edit record ?>
        <div class="form-group row anggota_id_level">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_level->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_level->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_id_level">
<span<?= $Page->id_level->viewAttributes() ?>>
<?= $Page->id_level->getViewValue() ?></span>
</span>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->no_handphone->Visible) { // no_handphone ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <tr>
            <td class="ew-table-header <?= $Page->TableLeftColumnClass ?>"><span class="anggota_no_handphone"><?= $Page->renderSort($Page->no_handphone) ?></span></td>
            <td <?= $Page->no_handphone->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_no_handphone">
<span<?= $Page->no_handphone->viewAttributes() ?>>
<?= $Page->no_handphone->getViewValue() ?></span>
</span>
</td>
        </tr>
        <?php } else { // Add/edit record ?>
        <div class="form-group row anggota_no_handphone">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->no_handphone->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->no_handphone->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota_no_handphone">
<span<?= $Page->no_handphone->viewAttributes() ?>>
<?= $Page->no_handphone->getViewValue() ?></span>
</span>
</div></div>
        </div>
        <?php } ?>
    <?php } ?>
    <?php if ($Page->_email->Visible) { // email ?>
        <?php if ($Page->RowType == ROWTYPE_VIEW) { // View record ?>
        <tr>
            <td class="ew-table-header <?= $Page->TableLeftColumnClass ?>"><span class="anggota__email"><?= $Page->renderSort($Page->_email) ?></span></td>
            <td <?= $Page->_email->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota__email">
<span<?= $Page->_email->viewAttributes() ?>>
<?= $Page->_email->getViewValue() ?></span>
</span>
</td>
        </tr>
        <?php } else { // Add/edit record ?>
        <div class="form-group row anggota__email">
            <label class="<?= $Page->LeftColumnClass ?>"><?= $Page->_email->caption() ?></label>
            <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_email->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_anggota__email">
<span<?= $Page->_email->viewAttributes() ?>>
<?= $Page->_email->getViewValue() ?></span>
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
    ew.addEventHandlers("anggota");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
