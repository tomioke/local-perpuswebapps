<?php

namespace PHPMaker2021\perpusupdate;

// Page object
$AnggotaEdit = &$Page;
?>
<script>
var currentForm, currentPageID;
var fanggotaedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fanggotaedit = currentForm = new ew.Form("fanggotaedit", "edit");

    // Add fields
    var currentTable = <?= JsonEncode(GetClientVar("tables", "anggota")) ?>,
        fields = currentTable.fields;
    if (!ew.vars.tables.anggota)
        ew.vars.tables.anggota = currentTable;
    fanggotaedit.addFields([
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
        var f = fanggotaedit,
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
    fanggotaedit.validate = function () {
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
    fanggotaedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fanggotaedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fanggotaedit.lists.id_level = <?= $Page->id_level->toClientList($Page) ?>;
    loadjs.done("fanggotaedit");
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
<form name="fanggotaedit" id="fanggotaedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="anggota">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id_anggota->Visible) { // id_anggota ?>
    <div id="r_id_anggota" class="form-group row">
        <label id="elh_anggota_id_anggota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_anggota->caption() ?><?= $Page->id_anggota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_anggota->cellAttributes() ?>>
<span id="el_anggota_id_anggota">
<span<?= $Page->id_anggota->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id_anggota->getDisplayValue($Page->id_anggota->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="anggota" data-field="x_id_anggota" data-hidden="1" name="x_id_anggota" id="x_id_anggota" value="<?= HtmlEncode($Page->id_anggota->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nama_anggota->Visible) { // nama_anggota ?>
    <div id="r_nama_anggota" class="form-group row">
        <label id="elh_anggota_nama_anggota" for="x_nama_anggota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nama_anggota->caption() ?><?= $Page->nama_anggota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->nama_anggota->cellAttributes() ?>>
<span id="el_anggota_nama_anggota">
<input type="<?= $Page->nama_anggota->getInputTextType() ?>" data-table="anggota" data-field="x_nama_anggota" name="x_nama_anggota" id="x_nama_anggota" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->nama_anggota->getPlaceHolder()) ?>" value="<?= $Page->nama_anggota->EditValue ?>"<?= $Page->nama_anggota->editAttributes() ?> aria-describedby="x_nama_anggota_help">
<?= $Page->nama_anggota->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nama_anggota->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->alamat->Visible) { // alamat ?>
    <div id="r_alamat" class="form-group row">
        <label id="elh_anggota_alamat" for="x_alamat" class="<?= $Page->LeftColumnClass ?>"><?= $Page->alamat->caption() ?><?= $Page->alamat->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->alamat->cellAttributes() ?>>
<span id="el_anggota_alamat">
<input type="<?= $Page->alamat->getInputTextType() ?>" data-table="anggota" data-field="x_alamat" name="x_alamat" id="x_alamat" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->alamat->getPlaceHolder()) ?>" value="<?= $Page->alamat->EditValue ?>"<?= $Page->alamat->editAttributes() ?> aria-describedby="x_alamat_help">
<?= $Page->alamat->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->alamat->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tgl_lahir->Visible) { // tgl_lahir ?>
    <div id="r_tgl_lahir" class="form-group row">
        <label id="elh_anggota_tgl_lahir" for="x_tgl_lahir" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tgl_lahir->caption() ?><?= $Page->tgl_lahir->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tgl_lahir->cellAttributes() ?>>
<span id="el_anggota_tgl_lahir">
<input type="<?= $Page->tgl_lahir->getInputTextType() ?>" data-table="anggota" data-field="x_tgl_lahir" name="x_tgl_lahir" id="x_tgl_lahir" placeholder="<?= HtmlEncode($Page->tgl_lahir->getPlaceHolder()) ?>" value="<?= $Page->tgl_lahir->EditValue ?>"<?= $Page->tgl_lahir->editAttributes() ?> aria-describedby="x_tgl_lahir_help">
<?= $Page->tgl_lahir->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tgl_lahir->getErrorMessage() ?></div>
<?php if (!$Page->tgl_lahir->ReadOnly && !$Page->tgl_lahir->Disabled && !isset($Page->tgl_lahir->EditAttrs["readonly"]) && !isset($Page->tgl_lahir->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fanggotaedit", "datetimepicker"], function() {
    ew.createDateTimePicker("fanggotaedit", "x_tgl_lahir", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->tmp_lahir->Visible) { // tmp_lahir ?>
    <div id="r_tmp_lahir" class="form-group row">
        <label id="elh_anggota_tmp_lahir" for="x_tmp_lahir" class="<?= $Page->LeftColumnClass ?>"><?= $Page->tmp_lahir->caption() ?><?= $Page->tmp_lahir->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->tmp_lahir->cellAttributes() ?>>
<span id="el_anggota_tmp_lahir">
<input type="<?= $Page->tmp_lahir->getInputTextType() ?>" data-table="anggota" data-field="x_tmp_lahir" name="x_tmp_lahir" id="x_tmp_lahir" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->tmp_lahir->getPlaceHolder()) ?>" value="<?= $Page->tmp_lahir->EditValue ?>"<?= $Page->tmp_lahir->editAttributes() ?> aria-describedby="x_tmp_lahir_help">
<?= $Page->tmp_lahir->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->tmp_lahir->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_username->Visible) { // username ?>
    <div id="r__username" class="form-group row">
        <label id="elh_anggota__username" for="x__username" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_username->caption() ?><?= $Page->_username->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_username->cellAttributes() ?>>
<span id="el_anggota__username">
<input type="<?= $Page->_username->getInputTextType() ?>" data-table="anggota" data-field="x__username" name="x__username" id="x__username" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->_username->getPlaceHolder()) ?>" value="<?= $Page->_username->EditValue ?>"<?= $Page->_username->editAttributes() ?> aria-describedby="x__username_help">
<?= $Page->_username->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_username->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_password->Visible) { // password ?>
    <div id="r__password" class="form-group row">
        <label id="elh_anggota__password" for="x__password" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_password->caption() ?><?= $Page->_password->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_password->cellAttributes() ?>>
<span id="el_anggota__password">
<div class="input-group" id="ig__password">
    <input type="password" autocomplete="new-password" data-table="anggota" data-field="x__password" name="x__password" id="x__password" value="<?= $Page->_password->EditValue ?>" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->_password->getPlaceHolder()) ?>"<?= $Page->_password->editAttributes() ?> aria-describedby="x__password_help">
    <div class="input-group-append">
        <button type="button" class="btn btn-default ew-toggle-password" onclick="ew.togglePassword(event);"><i class="fas fa-eye"></i></button>
        <button type="button" class="btn btn-default ew-password-generator rounded-right" title="<?= HtmlTitle($Language->phrase("GeneratePassword")) ?>" data-password-field="x__password" data-password-confirm="c__password"><?= $Language->phrase("GeneratePassword") ?></button>
    </div>
</div>
<?= $Page->_password->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_password->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->id_level->Visible) { // id_level ?>
    <div id="r_id_level" class="form-group row">
        <label id="elh_anggota_id_level" for="x_id_level" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_level->caption() ?><?= $Page->id_level->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_level->cellAttributes() ?>>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin ?>
<span id="el_anggota_id_level">
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id_level->getDisplayValue($Page->id_level->EditValue))) ?>">
</span>
<?php } else { ?>
<span id="el_anggota_id_level">
    <select
        id="x_id_level"
        name="x_id_level"
        class="form-control ew-select<?= $Page->id_level->isInvalidClass() ?>"
        data-select2-id="anggota_x_id_level"
        data-table="anggota"
        data-field="x_id_level"
        data-value-separator="<?= $Page->id_level->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->id_level->getPlaceHolder()) ?>"
        <?= $Page->id_level->editAttributes() ?>>
        <?= $Page->id_level->selectOptionListHtml("x_id_level") ?>
    </select>
    <?= $Page->id_level->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->id_level->getErrorMessage() ?></div>
<?= $Page->id_level->Lookup->getParamTag($Page, "p_x_id_level") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='anggota_x_id_level']"),
        options = { name: "x_id_level", selectId: "anggota_x_id_level", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.anggota.fields.id_level.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->no_handphone->Visible) { // no_handphone ?>
    <div id="r_no_handphone" class="form-group row">
        <label id="elh_anggota_no_handphone" for="x_no_handphone" class="<?= $Page->LeftColumnClass ?>"><?= $Page->no_handphone->caption() ?><?= $Page->no_handphone->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->no_handphone->cellAttributes() ?>>
<span id="el_anggota_no_handphone">
<input type="<?= $Page->no_handphone->getInputTextType() ?>" data-table="anggota" data-field="x_no_handphone" name="x_no_handphone" id="x_no_handphone" size="30" maxlength="24" placeholder="<?= HtmlEncode($Page->no_handphone->getPlaceHolder()) ?>" value="<?= $Page->no_handphone->EditValue ?>"<?= $Page->no_handphone->editAttributes() ?> aria-describedby="x_no_handphone_help">
<?= $Page->no_handphone->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->no_handphone->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_email->Visible) { // email ?>
    <div id="r__email" class="form-group row">
        <label id="elh_anggota__email" for="x__email" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_email->caption() ?><?= $Page->_email->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->_email->cellAttributes() ?>>
<span id="el_anggota__email">
<input type="<?= $Page->_email->getInputTextType() ?>" data-table="anggota" data-field="x__email" name="x__email" id="x__email" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->_email->getPlaceHolder()) ?>" value="<?= $Page->_email->EditValue ?>"<?= $Page->_email->editAttributes() ?> aria-describedby="x__email_help">
<?= $Page->_email->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_email->getErrorMessage() ?></div>
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
    ew.addEventHandlers("anggota");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
