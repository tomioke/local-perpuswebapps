<?php

namespace PHPMaker2021\perpusupdate;

// Page object
$PengembalianAdd = &$Page;
?>
<script>
var currentForm, currentPageID;
var fpengembalianadd;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fpengembalianadd = currentForm = new ew.Form("fpengembalianadd", "add");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "pengembalian")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.pengembalian)
        ew.vars.tables.pengembalian = currentTable;
    fpengembalianadd.addFields([
        ["id_peminjaman", [fields.id_peminjaman.visible && fields.id_peminjaman.required ? ew.Validators.required(fields.id_peminjaman.caption) : null], fields.id_peminjaman.isInvalid],
        ["tgl_kembali", [fields.tgl_kembali.visible && fields.tgl_kembali.required ? ew.Validators.required(fields.tgl_kembali.caption) : null, ew.Validators.datetime(0)], fields.tgl_kembali.isInvalid],
        ["kondisi_buku_kembali", [fields.kondisi_buku_kembali.visible && fields.kondisi_buku_kembali.required ? ew.Validators.required(fields.kondisi_buku_kembali.caption) : null], fields.kondisi_buku_kembali.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fpengembalianadd,
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
    fpengembalianadd.validate = function () {
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
    fpengembalianadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpengembalianadd.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fpengembalianadd.lists.id_peminjaman = <?= $Page->id_peminjaman->toClientList($Page) ?>;
    loadjs.done("fpengembalianadd");
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
<form name="fpengembalianadd" id="fpengembalianadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pengembalian">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->id_peminjaman->Visible) { // id_peminjaman ?>
    <div id="r_id_peminjaman" class="form-group row">
        <label id="elh_pengembalian_id_peminjaman" for="x_id_peminjaman" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_peminjaman->caption() ?><?= $Page->id_peminjaman->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_peminjaman->cellAttributes() ?>>
<span id="el_pengembalian_id_peminjaman">
    <select
        id="x_id_peminjaman"
        name="x_id_peminjaman"
        class="form-control ew-select<?= $Page->id_peminjaman->isInvalidClass() ?>"
        data-select2-id="pengembalian_x_id_peminjaman"
        data-table="pengembalian"
        data-field="x_id_peminjaman"
        data-value-separator="<?= $Page->id_peminjaman->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->id_peminjaman->getPlaceHolder()) ?>"
        <?= $Page->id_peminjaman->editAttributes() ?>>
        <?= $Page->id_peminjaman->selectOptionListHtml("x_id_peminjaman") ?>
    </select>
    <?= $Page->id_peminjaman->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->id_peminjaman->getErrorMessage() ?></div>
<?= $Page->id_peminjaman->Lookup->getParamTag($Page, "p_x_id_peminjaman") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='pengembalian_x_id_peminjaman']"),
        options = { name: "x_id_peminjaman", selectId: "pengembalian_x_id_peminjaman", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.pengembalian.fields.id_peminjaman.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tgl_kembali->Visible) { // tgl_kembali ?>
    <div id="r_tgl_kembali" class="form-group row">
        <label id="elh_pengembalian_tgl_kembali" for="x_tgl_kembali" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tgl_kembali->caption() ?><?= $Page->tgl_kembali->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tgl_kembali->cellAttributes() ?>>
<span id="el_pengembalian_tgl_kembali">
<input type="<?= $Page->tgl_kembali->getInputTextType() ?>" data-table="pengembalian" data-field="x_tgl_kembali" name="x_tgl_kembali" id="x_tgl_kembali" placeholder="<?= HtmlEncode($Page->tgl_kembali->getPlaceHolder()) ?>" value="<?= $Page->tgl_kembali->EditValue ?>"<?= $Page->tgl_kembali->editAttributes() ?> aria-describedby="x_tgl_kembali_help">
<?= $Page->tgl_kembali->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tgl_kembali->getErrorMessage() ?></div>
<?php if (!$Page->tgl_kembali->ReadOnly && !$Page->tgl_kembali->Disabled && !isset($Page->tgl_kembali->EditAttrs["readonly"]) && !isset($Page->tgl_kembali->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpengembalianadd", "datetimepicker"], function() {
    ew.createDateTimePicker("fpengembalianadd", "x_tgl_kembali", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->kondisi_buku_kembali->Visible) { // kondisi_buku_kembali ?>
    <div id="r_kondisi_buku_kembali" class="form-group row">
        <label id="elh_pengembalian_kondisi_buku_kembali" for="x_kondisi_buku_kembali" class="<?= $Page->LeftColumnClass ?>"><?= $Page->kondisi_buku_kembali->caption() ?><?= $Page->kondisi_buku_kembali->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->kondisi_buku_kembali->cellAttributes() ?>>
<span id="el_pengembalian_kondisi_buku_kembali">
<input type="<?= $Page->kondisi_buku_kembali->getInputTextType() ?>" data-table="pengembalian" data-field="x_kondisi_buku_kembali" name="x_kondisi_buku_kembali" id="x_kondisi_buku_kembali" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->kondisi_buku_kembali->getPlaceHolder()) ?>" value="<?= $Page->kondisi_buku_kembali->EditValue ?>"<?= $Page->kondisi_buku_kembali->editAttributes() ?> aria-describedby="x_kondisi_buku_kembali_help">
<?= $Page->kondisi_buku_kembali->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->kondisi_buku_kembali->getErrorMessage() ?></div>
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
    ew.addEventHandlers("pengembalian");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
