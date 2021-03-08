<?php

namespace PHPMaker2021\perpusupdate;

// Page object
$PengarangEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fpengarangedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fpengarangedit = currentForm = new ew.Form("fpengarangedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "pengarang")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.pengarang)
        ew.vars.tables.pengarang = currentTable;
    fpengarangedit.addFields([
        ["id_pengarang", [fields.id_pengarang.visible && fields.id_pengarang.required ? ew.Validators.required(fields.id_pengarang.caption) : null], fields.id_pengarang.isInvalid],
        ["nama_pengarang", [fields.nama_pengarang.visible && fields.nama_pengarang.required ? ew.Validators.required(fields.nama_pengarang.caption) : null], fields.nama_pengarang.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fpengarangedit,
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
    fpengarangedit.validate = function () {
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
    fpengarangedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpengarangedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fpengarangedit");
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
<form name="fpengarangedit" id="fpengarangedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pengarang">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id_pengarang->Visible) { // id_pengarang ?>
    <div id="r_id_pengarang" class="form-group row">
        <label id="elh_pengarang_id_pengarang" for="x_id_pengarang" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_pengarang->caption() ?><?= $Page->id_pengarang->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_pengarang->cellAttributes() ?>>
<span id="el_pengarang_id_pengarang">
<span<?= $Page->id_pengarang->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id_pengarang->getDisplayValue($Page->id_pengarang->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="pengarang" data-field="x_id_pengarang" data-hidden="1" name="x_id_pengarang" id="x_id_pengarang" value="<?= HtmlEncode($Page->id_pengarang->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nama_pengarang->Visible) { // nama_pengarang ?>
    <div id="r_nama_pengarang" class="form-group row">
        <label id="elh_pengarang_nama_pengarang" for="x_nama_pengarang" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nama_pengarang->caption() ?><?= $Page->nama_pengarang->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nama_pengarang->cellAttributes() ?>>
<span id="el_pengarang_nama_pengarang">
<input type="<?= $Page->nama_pengarang->getInputTextType() ?>" data-table="pengarang" data-field="x_nama_pengarang" name="x_nama_pengarang" id="x_nama_pengarang" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->nama_pengarang->getPlaceHolder()) ?>" value="<?= $Page->nama_pengarang->EditValue ?>"<?= $Page->nama_pengarang->editAttributes() ?> aria-describedby="x_nama_pengarang_help">
<?= $Page->nama_pengarang->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nama_pengarang->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php
    if (in_array("buku", explode(",", $Page->getCurrentDetailTable())) && $buku->DetailEdit) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("buku", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "BukuGrid.php" ?>
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<div class="form-group row"><!-- buttons .form-group -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("SaveBtn") ?></button>
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
    ew.addEventHandlers("pengarang");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
