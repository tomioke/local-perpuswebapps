<?php

namespace PHPMaker2021\perpus;

// Page object
$PenerbitEdit = &$Page;
?>
<script>
if (!ew.vars.tables.penerbit) ew.vars.tables.penerbit = <?= JsonEncode(GetClientVar("tables", "penerbit")) ?>;
var currentForm, currentPageID;
var fpenerbitedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fpenerbitedit = currentForm = new ew.Form("fpenerbitedit", "edit");

    // Add fields
    var fields = ew.vars.tables.penerbit.fields;
    fpenerbitedit.addFields([
        ["id_penerbit", [fields.id_penerbit.required ? ew.Validators.required(fields.id_penerbit.caption) : null], fields.id_penerbit.isInvalid],
        ["nama_penerbit", [fields.nama_penerbit.required ? ew.Validators.required(fields.nama_penerbit.caption) : null], fields.nama_penerbit.isInvalid],
        ["alamat_penerbit", [fields.alamat_penerbit.required ? ew.Validators.required(fields.alamat_penerbit.caption) : null], fields.alamat_penerbit.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fpenerbitedit,
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
    fpenerbitedit.validate = function () {
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
    fpenerbitedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpenerbitedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fpenerbitedit");
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
<form name="fpenerbitedit" id="fpenerbitedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="penerbit">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id_penerbit->Visible) { // id_penerbit ?>
    <div id="r_id_penerbit" class="form-group row">
        <label id="elh_penerbit_id_penerbit" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_penerbit->caption() ?><?= $Page->id_penerbit->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_penerbit->cellAttributes() ?>>
<span id="el_penerbit_id_penerbit">
<span<?= $Page->id_penerbit->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id_penerbit->getDisplayValue($Page->id_penerbit->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="penerbit" data-field="x_id_penerbit" data-hidden="1" name="x_id_penerbit" id="x_id_penerbit" value="<?= HtmlEncode($Page->id_penerbit->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nama_penerbit->Visible) { // nama_penerbit ?>
    <div id="r_nama_penerbit" class="form-group row">
        <label id="elh_penerbit_nama_penerbit" for="x_nama_penerbit" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nama_penerbit->caption() ?><?= $Page->nama_penerbit->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nama_penerbit->cellAttributes() ?>>
<span id="el_penerbit_nama_penerbit">
<input type="<?= $Page->nama_penerbit->getInputTextType() ?>" data-table="penerbit" data-field="x_nama_penerbit" name="x_nama_penerbit" id="x_nama_penerbit" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->nama_penerbit->getPlaceHolder()) ?>" value="<?= $Page->nama_penerbit->EditValue ?>"<?= $Page->nama_penerbit->editAttributes() ?> aria-describedby="x_nama_penerbit_help">
<?= $Page->nama_penerbit->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nama_penerbit->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->alamat_penerbit->Visible) { // alamat_penerbit ?>
    <div id="r_alamat_penerbit" class="form-group row">
        <label id="elh_penerbit_alamat_penerbit" for="x_alamat_penerbit" class="<?= $Page->LeftColumnClass ?>"><?= $Page->alamat_penerbit->caption() ?><?= $Page->alamat_penerbit->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->alamat_penerbit->cellAttributes() ?>>
<span id="el_penerbit_alamat_penerbit">
<input type="<?= $Page->alamat_penerbit->getInputTextType() ?>" data-table="penerbit" data-field="x_alamat_penerbit" name="x_alamat_penerbit" id="x_alamat_penerbit" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->alamat_penerbit->getPlaceHolder()) ?>" value="<?= $Page->alamat_penerbit->EditValue ?>"<?= $Page->alamat_penerbit->editAttributes() ?> aria-describedby="x_alamat_penerbit_help">
<?= $Page->alamat_penerbit->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->alamat_penerbit->getErrorMessage() ?></div>
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
    ew.addEventHandlers("penerbit");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
