<?php

namespace PHPMaker2021\perpus;

// Page object
$PeminjamanEdit = &$Page;
?>
<script>
if (!ew.vars.tables.peminjaman) ew.vars.tables.peminjaman = <?= JsonEncode(GetClientVar("tables", "peminjaman")) ?>;
var currentForm, currentPageID;
var fpeminjamanedit;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "edit";
    fpeminjamanedit = currentForm = new ew.Form("fpeminjamanedit", "edit");

    // Add fields
    var fields = ew.vars.tables.peminjaman.fields;
    fpeminjamanedit.addFields([
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
        var f = fpeminjamanedit,
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
    fpeminjamanedit.validate = function () {
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
    fpeminjamanedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpeminjamanedit.validateRequired = <?= Config("CLIENT_VALIDATE") ? "true" : "false" ?>;

    // Dynamic selection lists
    fpeminjamanedit.lists.id_buku = <?= $Page->id_buku->toClientList($Page) ?>;
    fpeminjamanedit.lists.id_anggota = <?= $Page->id_anggota->toClientList($Page) ?>;
    loadjs.done("fpeminjamanedit");
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
<form name="fpeminjamanedit" id="fpeminjamanedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="peminjaman">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id_peminjaman->Visible) { // id_peminjaman ?>
    <div id="r_id_peminjaman" class="form-group row">
        <label id="elh_peminjaman_id_peminjaman" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_peminjaman->caption() ?><?= $Page->id_peminjaman->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_peminjaman->cellAttributes() ?>>
<span id="el_peminjaman_id_peminjaman">
<span<?= $Page->id_peminjaman->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id_peminjaman->getDisplayValue($Page->id_peminjaman->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="peminjaman" data-field="x_id_peminjaman" data-hidden="1" name="x_id_peminjaman" id="x_id_peminjaman" value="<?= HtmlEncode($Page->id_peminjaman->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->berita_peminjaman->Visible) { // berita_peminjaman ?>
    <div id="r_berita_peminjaman" class="form-group row">
        <label id="elh_peminjaman_berita_peminjaman" for="x_berita_peminjaman" class="<?= $Page->LeftColumnClass ?>"><?= $Page->berita_peminjaman->caption() ?><?= $Page->berita_peminjaman->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->berita_peminjaman->cellAttributes() ?>>
<span id="el_peminjaman_berita_peminjaman">
<input type="<?= $Page->berita_peminjaman->getInputTextType() ?>" data-table="peminjaman" data-field="x_berita_peminjaman" name="x_berita_peminjaman" id="x_berita_peminjaman" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->berita_peminjaman->getPlaceHolder()) ?>" value="<?= $Page->berita_peminjaman->EditValue ?>"<?= $Page->berita_peminjaman->editAttributes() ?> aria-describedby="x_berita_peminjaman_help">
<?= $Page->berita_peminjaman->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->berita_peminjaman->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->id_buku->Visible) { // id_buku ?>
    <div id="r_id_buku" class="form-group row">
        <label id="elh_peminjaman_id_buku" for="x_id_buku" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_buku->caption() ?><?= $Page->id_buku->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_buku->cellAttributes() ?>>
<span id="el_peminjaman_id_buku">
    <select
        id="x_id_buku"
        name="x_id_buku"
        class="form-control ew-select<?= $Page->id_buku->isInvalidClass() ?>"
        data-select2-id="peminjaman_x_id_buku"
        data-table="peminjaman"
        data-field="x_id_buku"
        data-value-separator="<?= $Page->id_buku->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->id_buku->getPlaceHolder()) ?>"
        <?= $Page->id_buku->editAttributes() ?>>
        <?= $Page->id_buku->selectOptionListHtml("x_id_buku") ?>
    </select>
    <?= $Page->id_buku->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->id_buku->getErrorMessage() ?></div>
<?= $Page->id_buku->Lookup->getParamTag($Page, "p_x_id_buku") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='peminjaman_x_id_buku']"),
        options = { name: "x_id_buku", selectId: "peminjaman_x_id_buku", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.peminjaman.fields.id_buku.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->id_anggota->Visible) { // id_anggota ?>
    <div id="r_id_anggota" class="form-group row">
        <label id="elh_peminjaman_id_anggota" for="x_id_anggota" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id_anggota->caption() ?><?= $Page->id_anggota->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->id_anggota->cellAttributes() ?>>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn() && !$Page->userIDAllow("edit")) { // Non system admin ?>
<span id="el_peminjaman_id_anggota">
<span<?= $Page->id_anggota->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->id_anggota->getDisplayValue($Page->id_anggota->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="peminjaman" data-field="x_id_anggota" data-hidden="1" name="x_id_anggota" id="x_id_anggota" value="<?= HtmlEncode($Page->id_anggota->CurrentValue) ?>">
<?php } else { ?>
<span id="el_peminjaman_id_anggota">
    <select
        id="x_id_anggota"
        name="x_id_anggota"
        class="form-control ew-select<?= $Page->id_anggota->isInvalidClass() ?>"
        data-select2-id="peminjaman_x_id_anggota"
        data-table="peminjaman"
        data-field="x_id_anggota"
        data-value-separator="<?= $Page->id_anggota->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->id_anggota->getPlaceHolder()) ?>"
        <?= $Page->id_anggota->editAttributes() ?>>
        <?= $Page->id_anggota->selectOptionListHtml("x_id_anggota") ?>
    </select>
    <?= $Page->id_anggota->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->id_anggota->getErrorMessage() ?></div>
<?= $Page->id_anggota->Lookup->getParamTag($Page, "p_x_id_anggota") ?>
<script>
loadjs.ready("head", function() {
    var el = document.querySelector("select[data-select2-id='peminjaman_x_id_anggota']"),
        options = { name: "x_id_anggota", selectId: "peminjaman_x_id_anggota", language: ew.LANGUAGE_ID, dir: ew.IS_RTL ? "rtl" : "ltr" };
    options.dropdownParent = $(el).closest("#ew-modal-dialog, #ew-add-opt-dialog")[0];
    Object.assign(options, ew.vars.tables.peminjaman.fields.id_anggota.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->rencana_tgl_kembali->Visible) { // rencana_tgl_kembali ?>
    <div id="r_rencana_tgl_kembali" class="form-group row">
        <label id="elh_peminjaman_rencana_tgl_kembali" for="x_rencana_tgl_kembali" class="<?= $Page->LeftColumnClass ?>"><?= $Page->rencana_tgl_kembali->caption() ?><?= $Page->rencana_tgl_kembali->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->rencana_tgl_kembali->cellAttributes() ?>>
<span id="el_peminjaman_rencana_tgl_kembali">
<input type="<?= $Page->rencana_tgl_kembali->getInputTextType() ?>" data-table="peminjaman" data-field="x_rencana_tgl_kembali" name="x_rencana_tgl_kembali" id="x_rencana_tgl_kembali" placeholder="<?= HtmlEncode($Page->rencana_tgl_kembali->getPlaceHolder()) ?>" value="<?= $Page->rencana_tgl_kembali->EditValue ?>"<?= $Page->rencana_tgl_kembali->editAttributes() ?> aria-describedby="x_rencana_tgl_kembali_help">
<?= $Page->rencana_tgl_kembali->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->rencana_tgl_kembali->getErrorMessage() ?></div>
<?php if (!$Page->rencana_tgl_kembali->ReadOnly && !$Page->rencana_tgl_kembali->Disabled && !isset($Page->rencana_tgl_kembali->EditAttrs["readonly"]) && !isset($Page->rencana_tgl_kembali->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpeminjamanedit", "datetimepicker"], function() {
    ew.createDateTimePicker("fpeminjamanedit", "x_rencana_tgl_kembali", {"ignoreReadonly":true,"useCurrent":false,"format":0});
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->kondisi_buku_peminjaman->Visible) { // kondisi_buku_peminjaman ?>
    <div id="r_kondisi_buku_peminjaman" class="form-group row">
        <label id="elh_peminjaman_kondisi_buku_peminjaman" for="x_kondisi_buku_peminjaman" class="<?= $Page->LeftColumnClass ?>"><?= $Page->kondisi_buku_peminjaman->caption() ?><?= $Page->kondisi_buku_peminjaman->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div <?= $Page->kondisi_buku_peminjaman->cellAttributes() ?>>
<span id="el_peminjaman_kondisi_buku_peminjaman">
<input type="<?= $Page->kondisi_buku_peminjaman->getInputTextType() ?>" data-table="peminjaman" data-field="x_kondisi_buku_peminjaman" name="x_kondisi_buku_peminjaman" id="x_kondisi_buku_peminjaman" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->kondisi_buku_peminjaman->getPlaceHolder()) ?>" value="<?= $Page->kondisi_buku_peminjaman->EditValue ?>"<?= $Page->kondisi_buku_peminjaman->editAttributes() ?> aria-describedby="x_kondisi_buku_peminjaman_help">
<?= $Page->kondisi_buku_peminjaman->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->kondisi_buku_peminjaman->getErrorMessage() ?></div>
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
    ew.addEventHandlers("peminjaman");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
