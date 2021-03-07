<?php

namespace PHPMaker2021\perpus;

// Page object
$Permission2Add = &$Page;
?>
<script>
if (!ew.vars.tables.permission2) ew.vars.tables.permission2 = <?= JsonEncode(GetClientVar("tables", "permission2")) ?>;
var currentForm, currentPageID;
var fpermission2add;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "add";
    fpermission2add = currentForm = new ew.Form("fpermission2add", "add");

    // Add fields
    var fields = ew.vars.tables.permission2.fields;
    fpermission2add.addFields([
        ["table_name", [fields.table_name.required ? ew.Validators.required(fields.table_name.caption) : null], fields.table_name.isInvalid],
        ["id_level", [fields.id_level.required ? ew.Validators.required(fields.id_level.caption) : null, ew.Validators.integer], fields.id_level.isInvalid],
        ["_permission", [fields._permission.required ? ew.Validators.required(fields._permission.caption) : null], fields._permission.isInvalid]
    ]);

    // Set invalid fields
    $(function() {
        var f = fpermission2add,
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
    fpermission2add.validate = function () {
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
    fpermission2add.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpermission2add.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    loadjs.done("fpermission2add");
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
<form name="fpermission2add" id="fpermission2add" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="permission2">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->table_name->Visible) { // table_name ?>
    <div id="r_table_name" class="form-group row">
        <label id="elh_permission2_table_name" for="x_table_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->table_name->caption() ?><?= $Page->table_name->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->table_name->cellAttributes() ?>>
<span id="el_permission2_table_name">
    <select
        id="x_table_name"
        name="x_table_name"
        class="form-control ew-select<?= $Page->table_name->isInvalidClass() ?>"
        data-select2-id="permission2_x_table_name"
        data-table="permission2"
        data-field="x_table_name"
        data-value-separator="<?= $Page->table_name->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->table_name->getPlaceHolder()) ?>"
        <?= $Page->table_name->editAttributes() ?>>
        <?= $Page->table_name->selectOptionListHtml("x_table_name") ?>
    </select>
    <?= $Page->table_name->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->table_name->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='permission2_x_table_name']"),
        options = { name: "x_table_name", selectId: "permission2_x_table_name", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.permission2.fields.table_name.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
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
    <select
        id="x__permission"
        name="x__permission"
        class="form-control ew-select<?= $Page->_permission->isInvalidClass() ?>"
        data-select2-id="permission2_x__permission"
        data-table="permission2"
        data-field="x__permission"
        data-value-separator="<?= $Page->_permission->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->_permission->getPlaceHolder()) ?>"
        <?= $Page->_permission->editAttributes() ?>>
        <?= $Page->_permission->selectOptionListHtml("x__permission") ?>
    </select>
    <?= $Page->_permission->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->_permission->getErrorMessage() ?></div>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='permission2_x__permission']"),
        options = { name: "x__permission", selectId: "permission2_x__permission", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.permission2.fields._permission.selectOptions);
    ew.createSelect(options);
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
    ew.addEventHandlers("permission2");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
