<?php

namespace PHPMaker2021\perpusupdate;

// Page object
$BukuAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var fbukuadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fbukuadd = currentForm = new ew.Form("fbukuadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "buku")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.buku)
        ew.vars.tables.buku = currentTable;
    fbukuadd.addFields([
        ["cover", [fields.cover.visible && fields.cover.required ? ew.Validators.fileRequired(fields.cover.caption) : null], fields.cover.isInvalid],
        ["nama_buku", [fields.nama_buku.visible && fields.nama_buku.required ? ew.Validators.required(fields.nama_buku.caption) : null], fields.nama_buku.isInvalid],
        ["pengarang", [fields.pengarang.visible && fields.pengarang.required ? ew.Validators.required(fields.pengarang.caption) : null], fields.pengarang.isInvalid],
        ["penerbit", [fields.penerbit.visible && fields.penerbit.required ? ew.Validators.required(fields.penerbit.caption) : null], fields.penerbit.isInvalid],
        ["kode_isbn", [fields.kode_isbn.visible && fields.kode_isbn.required ? ew.Validators.required(fields.kode_isbn.caption) : null], fields.kode_isbn.isInvalid],
        ["rangkuman", [fields.rangkuman.visible && fields.rangkuman.required ? ew.Validators.required(fields.rangkuman.caption) : null], fields.rangkuman.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fbukuadd,
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
    fbukuadd.validate = function () {
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

        // Process detail forms
        var dfs = $fobj.find("input[name='detailpage']").get();
        for (var i = 0; i < dfs.length; i++) {
            var df = dfs[i],
                val = df.value,
                frm = ew.forms.get(val);
            if (val && frm && !frm.validate())
                return false;
        }
        return true;
    }

    // Form_CustomValidate
    fbukuadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fbukuadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fbukuadd.lists.pengarang = <?= $Page->pengarang->toClientList($Page) ?>;
    fbukuadd.lists.penerbit = <?= $Page->penerbit->toClientList($Page) ?>;
    loadjs.done("fbukuadd");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fbukuadd" id="fbukuadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="buku">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "penerbit") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="penerbit">
<input type="hidden" name="fk_id_penerbit" value="<?= HtmlEncode($Page->penerbit->getSessionValue()) ?>">
<?php } ?>
<?php if ($Page->getCurrentMasterTable() == "pengarang") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="pengarang">
<input type="hidden" name="fk_id_pengarang" value="<?= HtmlEncode($Page->pengarang->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->cover->Visible) { // cover ?>
    <div id="r_cover" class="form-group row">
        <label id="elh_buku_cover" class="<?= $Page->LeftColumnClass ?>"><?= $Page->cover->caption() ?><?= $Page->cover->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->cover->cellAttributes() ?>>
<span id="el_buku_cover">
<div id="fd_x_cover">
<div class="input-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" title="<?= $Page->cover->title() ?>" data-table="buku" data-field="x_cover" name="x_cover" id="x_cover" lang="<?= CurrentLanguageID() ?>"<?= $Page->cover->editAttributes() ?><?= ($Page->cover->ReadOnly || $Page->cover->Disabled) ? " disabled" : "" ?> aria-describedby="x_cover_help">
        <label class="custom-file-label ew-file-label" for="x_cover"><?= $Language->phrase("ChooseFile") ?></label>
    </div>
</div>
<?= $Page->cover->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->cover->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_cover" id= "fn_x_cover" value="<?= $Page->cover->Upload->FileName ?>">
<input type="hidden" name="fa_x_cover" id= "fa_x_cover" value="0">
<input type="hidden" name="fs_x_cover" id= "fs_x_cover" value="200">
<input type="hidden" name="fx_x_cover" id= "fx_x_cover" value="<?= $Page->cover->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_cover" id= "fm_x_cover" value="<?= $Page->cover->UploadMaxFileSize ?>">
</div>
<table id="ft_x_cover" class="table table-sm float-left ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nama_buku->Visible) { // nama_buku ?>
    <div id="r_nama_buku" class="form-group row">
        <label id="elh_buku_nama_buku" for="x_nama_buku" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nama_buku->caption() ?><?= $Page->nama_buku->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nama_buku->cellAttributes() ?>>
<span id="el_buku_nama_buku">
<input type="<?= $Page->nama_buku->getInputTextType() ?>" data-table="buku" data-field="x_nama_buku" name="x_nama_buku" id="x_nama_buku" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->nama_buku->getPlaceHolder()) ?>" value="<?= $Page->nama_buku->EditValue ?>"<?= $Page->nama_buku->editAttributes() ?> aria-describedby="x_nama_buku_help">
<?= $Page->nama_buku->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nama_buku->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pengarang->Visible) { // pengarang ?>
    <div id="r_pengarang" class="form-group row">
        <label id="elh_buku_pengarang" for="x_pengarang" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pengarang->caption() ?><?= $Page->pengarang->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->pengarang->cellAttributes() ?>>
<?php if ($Page->pengarang->getSessionValue() != "") { ?>
<span id="el_buku_pengarang">
<span<?= $Page->pengarang->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->pengarang->getDisplayValue($Page->pengarang->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x_pengarang" name="x_pengarang" value="<?= HtmlEncode($Page->pengarang->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_buku_pengarang">
    <select
        id="x_pengarang"
        name="x_pengarang"
        class="form-control ew-select<?= $Page->pengarang->isInvalidClass() ?>"
        data-select2-id="buku_x_pengarang"
        data-table="buku"
        data-field="x_pengarang"
        data-value-separator="<?= $Page->pengarang->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->pengarang->getPlaceHolder()) ?>"
        <?= $Page->pengarang->editAttributes() ?>>
        <?= $Page->pengarang->selectOptionListHtml("x_pengarang") ?>
    </select>
    <?= $Page->pengarang->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->pengarang->getErrorMessage() ?></div>
<?= $Page->pengarang->Lookup->getParamTag($Page, "p_x_pengarang") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='buku_x_pengarang']"),
        options = { name: "x_pengarang", selectId: "buku_x_pengarang", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.buku.fields.pengarang.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->penerbit->Visible) { // penerbit ?>
    <div id="r_penerbit" class="form-group row">
        <label id="elh_buku_penerbit" for="x_penerbit" class="<?= $Page->LeftColumnClass ?>"><?= $Page->penerbit->caption() ?><?= $Page->penerbit->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->penerbit->cellAttributes() ?>>
<?php if ($Page->penerbit->getSessionValue() != "") { ?>
<span id="el_buku_penerbit">
<span<?= $Page->penerbit->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->penerbit->getDisplayValue($Page->penerbit->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x_penerbit" name="x_penerbit" value="<?= HtmlEncode($Page->penerbit->CurrentValue) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_buku_penerbit">
    <select
        id="x_penerbit"
        name="x_penerbit"
        class="form-control ew-select<?= $Page->penerbit->isInvalidClass() ?>"
        data-select2-id="buku_x_penerbit"
        data-table="buku"
        data-field="x_penerbit"
        data-value-separator="<?= $Page->penerbit->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->penerbit->getPlaceHolder()) ?>"
        <?= $Page->penerbit->editAttributes() ?>>
        <?= $Page->penerbit->selectOptionListHtml("x_penerbit") ?>
    </select>
    <?= $Page->penerbit->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->penerbit->getErrorMessage() ?></div>
<?= $Page->penerbit->Lookup->getParamTag($Page, "p_x_penerbit") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='buku_x_penerbit']"),
        options = { name: "x_penerbit", selectId: "buku_x_penerbit", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.buku.fields.penerbit.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->kode_isbn->Visible) { // kode_isbn ?>
    <div id="r_kode_isbn" class="form-group row">
        <label id="elh_buku_kode_isbn" for="x_kode_isbn" class="<?= $Page->LeftColumnClass ?>"><?= $Page->kode_isbn->caption() ?><?= $Page->kode_isbn->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->kode_isbn->cellAttributes() ?>>
<span id="el_buku_kode_isbn">
<input type="<?= $Page->kode_isbn->getInputTextType() ?>" data-table="buku" data-field="x_kode_isbn" name="x_kode_isbn" id="x_kode_isbn" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->kode_isbn->getPlaceHolder()) ?>" value="<?= $Page->kode_isbn->EditValue ?>"<?= $Page->kode_isbn->editAttributes() ?> aria-describedby="x_kode_isbn_help">
<?= $Page->kode_isbn->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->kode_isbn->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->rangkuman->Visible) { // rangkuman ?>
    <div id="r_rangkuman" class="form-group row">
        <label id="elh_buku_rangkuman" class="<?= $Page->LeftColumnClass ?>"><?= $Page->rangkuman->caption() ?><?= $Page->rangkuman->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->rangkuman->cellAttributes() ?>>
<span id="el_buku_rangkuman">
<?php $Page->rangkuman->EditAttrs->appendClass("editor"); ?>
<textarea data-table="buku" data-field="x_rangkuman" name="x_rangkuman" id="x_rangkuman" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->rangkuman->getPlaceHolder()) ?>"<?= $Page->rangkuman->editAttributes() ?> aria-describedby="x_rangkuman_help"><?= $Page->rangkuman->EditValue ?></textarea>
<?= $Page->rangkuman->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->rangkuman->getErrorMessage() ?></div>
<script>
loadjs.ready(["fbukuadd", "editor"], function() {
	ew.createEditor("fbukuadd", "x_rangkuman", 35, 4, <?= $Page->rangkuman->ReadOnly || false ? "true" : "false" ?>);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("AddBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
    </div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
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
