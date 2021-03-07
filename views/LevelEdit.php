<?php

namespace PHPMaker2021\perpus;

// Page object
$LevelEdit = &$Page;
?>
<script>
if (!ew.vars.tables.level) ew.vars.tables.level = <?= JsonEncode(GetClientVar("tables", "level")) ?>;
var currentForm, currentPageID;
var fleveledit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fleveledit = currentForm = new ew.Form("fleveledit", "edit");

    // Add fields
    var fields = ew.vars.tables.level.fields;
    fleveledit.addFields([
        ["id_level", [fields.id_level.required ? ew.Validators.required(fields.id_level.caption) : null, ew.Validators.userLevelId, ew.Validators.integer], fields.id_level.isInvalid],
        ["level_name", [fields.level_name.required ? ew.Validators.required(fields.level_name.caption) : null, ew.Validators.userLevelName('id_level')], fields.level_name.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fleveledit,
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
    fleveledit.validate = function () {
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
    fleveledit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fleveledit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fleveledit");
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
<form name="fleveledit" id="fleveledit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="level">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id_level->Visible) { // id_level ?>
    <div id="r_id_level" class="form-group row">
        <label id="elh_level_id_level" for="x_id_level" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_level->caption() ?><?= $Page->id_level->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_level->cellAttributes() ?>>
<input type="<?= $Page->id_level->getInputTextType() ?>" data-table="level" data-field="x_id_level" name="x_id_level" id="x_id_level" size="30" placeholder="<?= HtmlEncode($Page->id_level->getPlaceHolder()) ?>" value="<?= $Page->id_level->EditValue ?>"<?= $Page->id_level->editAttributes() ?> aria-describedby="x_id_level_help">
<?= $Page->id_level->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->id_level->getErrorMessage() ?></div>
<input type="hidden" data-table="level" data-field="x_id_level" data-hidden="1" name="o_id_level" id="o_id_level" value="<?= HtmlEncode($Page->id_level->OldValue ?? $Page->id_level->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->level_name->Visible) { // level_name ?>
    <div id="r_level_name" class="form-group row">
        <label id="elh_level_level_name" for="x_level_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->level_name->caption() ?><?= $Page->level_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->level_name->cellAttributes() ?>>
<span id="el_level_level_name">
<input type="<?= $Page->level_name->getInputTextType() ?>" data-table="level" data-field="x_level_name" name="x_level_name" id="x_level_name" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->level_name->getPlaceHolder()) ?>" value="<?= $Page->level_name->EditValue ?>"<?= $Page->level_name->editAttributes() ?> aria-describedby="x_level_name_help">
<?= $Page->level_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->level_name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
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
    ew.addEventHandlers("level");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
