<?php

namespace PHPMaker2021\perpus;

// Page object
$Permission2Edit = &$Page;
?>
<script>
if (!ew.vars.tables.permission2) ew.vars.tables.permission2 = <?= JsonEncode(GetClientVar("tables", "permission2")) ?>;
var currentForm, currentPageID;
var fpermission2edit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fpermission2edit = currentForm = new ew.Form("fpermission2edit", "edit");

    // Add fields
    var fields = ew.vars.tables.permission2.fields;
    fpermission2edit.addFields([
        ["table_name", [fields.table_name.required ? ew.Validators.required(fields.table_name.caption) : null], fields.table_name.isInvalid],
        ["id_level", [fields.id_level.required ? ew.Validators.required(fields.id_level.caption) : null, ew.Validators.integer], fields.id_level.isInvalid],
        ["_permission", [fields._permission.required ? ew.Validators.required(fields._permission.caption) : null, ew.Validators.integer], fields._permission.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fpermission2edit,
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
    fpermission2edit.validate = function () {
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
    fpermission2edit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpermission2edit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fpermission2edit");
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
<form name="fpermission2edit" id="fpermission2edit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="permission2">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->table_name->Visible) { // table_name ?>
    <div id="r_table_name" class="form-group row">
        <label id="elh_permission2_table_name" for="x_table_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->table_name->caption() ?><?= $Page->table_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->table_name->cellAttributes() ?>>
<input type="<?= $Page->table_name->getInputTextType() ?>" data-table="permission2" data-field="x_table_name" name="x_table_name" id="x_table_name" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->table_name->getPlaceHolder()) ?>" value="<?= $Page->table_name->EditValue ?>"<?= $Page->table_name->editAttributes() ?> aria-describedby="x_table_name_help">
<?= $Page->table_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->table_name->getErrorMessage() ?></div>
<input type="hidden" data-table="permission2" data-field="x_table_name" data-hidden="1" name="o_table_name" id="o_table_name" value="<?= HtmlEncode($Page->table_name->OldValue ?? $Page->table_name->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->id_level->Visible) { // id_level ?>
    <div id="r_id_level" class="form-group row">
        <label id="elh_permission2_id_level" for="x_id_level" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_level->caption() ?><?= $Page->id_level->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_level->cellAttributes() ?>>
<span id="el_permission2_id_level">
<input type="<?= $Page->id_level->getInputTextType() ?>" data-table="permission2" data-field="x_id_level" name="x_id_level" id="x_id_level" size="30" placeholder="<?= HtmlEncode($Page->id_level->getPlaceHolder()) ?>" value="<?= $Page->id_level->EditValue ?>"<?= $Page->id_level->editAttributes() ?> aria-describedby="x_id_level_help">
<?= $Page->id_level->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->id_level->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_permission->Visible) { // permission ?>
    <div id="r__permission" class="form-group row">
        <label id="elh_permission2__permission" for="x__permission" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_permission->caption() ?><?= $Page->_permission->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_permission->cellAttributes() ?>>
<span id="el_permission2__permission">
<input type="<?= $Page->_permission->getInputTextType() ?>" data-table="permission2" data-field="x__permission" name="x__permission" id="x__permission" size="30" placeholder="<?= HtmlEncode($Page->_permission->getPlaceHolder()) ?>" value="<?= $Page->_permission->EditValue ?>"<?= $Page->_permission->editAttributes() ?> aria-describedby="x__permission_help">
<?= $Page->_permission->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_permission->getErrorMessage() ?></div>
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
    ew.addEventHandlers("permission2");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
