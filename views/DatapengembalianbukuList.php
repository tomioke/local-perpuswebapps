<?php

namespace PHPMaker2021\perpus;

// Page object
$DatapengembalianbukuList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentForm, currentPageID;
var fdatapengembalianbukulist;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object
    currentPageID = ew.PAGE_ID = "list";
    fdatapengembalianbukulist = currentForm = new ew.Form("fdatapengembalianbukulist", "list");
    fdatapengembalianbukulist.formKeyCountName = '<?= $Page->FormKeyCountName ?>';
    loadjs.done("fdatapengembalianbukulist");
});
var fdatapengembalianbukulistsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready("head", function () {
    var $ = jQuery;
    // Form object for search
    fdatapengembalianbukulistsrch = currentSearchForm = new ew.Form("fdatapengembalianbukulistsrch");

    // Dynamic selection lists

    // Filters
    fdatapengembalianbukulistsrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fdatapengembalianbukulistsrch");
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
<form name="fdatapengembalianbukulistsrch" id="fdatapengembalianbukulistsrch" class="form-inline ew-form ew-ext-search-form" action="<?= CurrentPageUrl() ?>">
<div id="fdatapengembalianbukulistsrch-search-panel" class="<?= $Page->SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="datapengembalianbuku">
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> datapengembalianbuku">
<?php if (!$Page->isExport()) { ?>
<div class="card-header ew-grid-upper-panel">
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
<form name="fdatapengembalianbukulist" id="fdatapengembalianbukulist" class="form-inline ew-form ew-list-form" action="<?= CurrentPageUrl() ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="datapengembalianbuku">
<div id="gmp_datapengembalianbuku" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_datapengembalianbukulist" class="table ew-table"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Page->RowType = ROWTYPE_HEADER;

// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->berita_peminjaman->Visible) { // berita_peminjaman ?>
        <th data-name="berita_peminjaman" class="<?= $Page->berita_peminjaman->headerCellClass() ?>"><div id="elh_datapengembalianbuku_berita_peminjaman" class="datapengembalianbuku_berita_peminjaman"><?= $Page->renderSort($Page->berita_peminjaman) ?></div></th>
<?php } ?>
<?php if ($Page->id_buku->Visible) { // id_buku ?>
        <th data-name="id_buku" class="<?= $Page->id_buku->headerCellClass() ?>"><div id="elh_datapengembalianbuku_id_buku" class="datapengembalianbuku_id_buku"><?= $Page->renderSort($Page->id_buku) ?></div></th>
<?php } ?>
<?php if ($Page->id_anggota->Visible) { // id_anggota ?>
        <th data-name="id_anggota" class="<?= $Page->id_anggota->headerCellClass() ?>"><div id="elh_datapengembalianbuku_id_anggota" class="datapengembalianbuku_id_anggota"><?= $Page->renderSort($Page->id_anggota) ?></div></th>
<?php } ?>
<?php if ($Page->tgl_peminjaman->Visible) { // tgl_peminjaman ?>
        <th data-name="tgl_peminjaman" class="<?= $Page->tgl_peminjaman->headerCellClass() ?>"><div id="elh_datapengembalianbuku_tgl_peminjaman" class="datapengembalianbuku_tgl_peminjaman"><?= $Page->renderSort($Page->tgl_peminjaman) ?></div></th>
<?php } ?>
<?php if ($Page->rencana_tgl_kembali->Visible) { // rencana_tgl_kembali ?>
        <th data-name="rencana_tgl_kembali" class="<?= $Page->rencana_tgl_kembali->headerCellClass() ?>"><div id="elh_datapengembalianbuku_rencana_tgl_kembali" class="datapengembalianbuku_rencana_tgl_kembali"><?= $Page->renderSort($Page->rencana_tgl_kembali) ?></div></th>
<?php } ?>
<?php if ($Page->kondisi_buku_peminjaman->Visible) { // kondisi_buku_peminjaman ?>
        <th data-name="kondisi_buku_peminjaman" class="<?= $Page->kondisi_buku_peminjaman->headerCellClass() ?>"><div id="elh_datapengembalianbuku_kondisi_buku_peminjaman" class="datapengembalianbuku_kondisi_buku_peminjaman"><?= $Page->renderSort($Page->kondisi_buku_peminjaman) ?></div></th>
<?php } ?>
<?php if ($Page->tgl_kembali->Visible) { // tgl_kembali ?>
        <th data-name="tgl_kembali" class="<?= $Page->tgl_kembali->headerCellClass() ?>"><div id="elh_datapengembalianbuku_tgl_kembali" class="datapengembalianbuku_tgl_kembali"><?= $Page->renderSort($Page->tgl_kembali) ?></div></th>
<?php } ?>
<?php if ($Page->kondisi_buku_kembali->Visible) { // kondisi_buku_kembali ?>
        <th data-name="kondisi_buku_kembali" class="<?= $Page->kondisi_buku_kembali->headerCellClass() ?>"><div id="elh_datapengembalianbuku_kondisi_buku_kembali" class="datapengembalianbuku_kondisi_buku_kembali"><?= $Page->renderSort($Page->kondisi_buku_kembali) ?></div></th>
<?php } ?>
<?php if ($Page->Lama_Kembali->Visible) { // Lama_Kembali ?>
        <th data-name="Lama_Kembali" class="<?= $Page->Lama_Kembali->headerCellClass() ?>"><div id="elh_datapengembalianbuku_Lama_Kembali" class="datapengembalianbuku_Lama_Kembali"><?= $Page->renderSort($Page->Lama_Kembali) ?></div></th>
<?php } ?>
<?php if ($Page->Lama_Pinjam->Visible) { // Lama_Pinjam ?>
        <th data-name="Lama_Pinjam" class="<?= $Page->Lama_Pinjam->headerCellClass() ?>"><div id="elh_datapengembalianbuku_Lama_Pinjam" class="datapengembalianbuku_Lama_Pinjam"><?= $Page->renderSort($Page->Lama_Pinjam) ?></div></th>
<?php } ?>
<?php if ($Page->Terlambat->Visible) { // Terlambat ?>
        <th data-name="Terlambat" class="<?= $Page->Terlambat->headerCellClass() ?>"><div id="elh_datapengembalianbuku_Terlambat" class="datapengembalianbuku_Terlambat"><?= $Page->renderSort($Page->Terlambat) ?></div></th>
<?php } ?>
<?php if ($Page->id_kembali->Visible) { // id_kembali ?>
        <th data-name="id_kembali" class="<?= $Page->id_kembali->headerCellClass() ?>"><div id="elh_datapengembalianbuku_id_kembali" class="datapengembalianbuku_id_kembali"><?= $Page->renderSort($Page->id_kembali) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody>
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
$Page->RecordCount = $Page->StartRecord - 1;
if ($Page->Recordset && !$Page->Recordset->EOF) {
    // Nothing to do
} elseif (!$Page->AllowAddDeleteRow && $Page->StopRecord == 0) {
    $Page->StopRecord = $Page->GridAddRowCount;
}

// Initialize aggregate
$Page->RowType = ROWTYPE_AGGREGATEINIT;
$Page->resetAttributes();
$Page->renderRow();
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
        $Page->RowAttrs->merge(["data-rowindex" => $Page->RowCount, "id" => "r" . $Page->RowCount . "_datapengembalianbuku", "data-rowtype" => $Page->RowType]);

        // Render row
        $Page->renderRow();

        // Render list options
        $Page->renderListOptions();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->berita_peminjaman->Visible) { // berita_peminjaman ?>
        <td data-name="berita_peminjaman" <?= $Page->berita_peminjaman->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_datapengembalianbuku_berita_peminjaman">
<span<?= $Page->berita_peminjaman->viewAttributes() ?>>
<?= $Page->berita_peminjaman->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->id_buku->Visible) { // id_buku ?>
        <td data-name="id_buku" <?= $Page->id_buku->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_datapengembalianbuku_id_buku">
<span<?= $Page->id_buku->viewAttributes() ?>>
<?= $Page->id_buku->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->id_anggota->Visible) { // id_anggota ?>
        <td data-name="id_anggota" <?= $Page->id_anggota->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_datapengembalianbuku_id_anggota">
<span<?= $Page->id_anggota->viewAttributes() ?>>
<?= $Page->id_anggota->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->tgl_peminjaman->Visible) { // tgl_peminjaman ?>
        <td data-name="tgl_peminjaman" <?= $Page->tgl_peminjaman->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_datapengembalianbuku_tgl_peminjaman">
<span<?= $Page->tgl_peminjaman->viewAttributes() ?>>
<?= $Page->tgl_peminjaman->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->rencana_tgl_kembali->Visible) { // rencana_tgl_kembali ?>
        <td data-name="rencana_tgl_kembali" <?= $Page->rencana_tgl_kembali->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_datapengembalianbuku_rencana_tgl_kembali">
<span<?= $Page->rencana_tgl_kembali->viewAttributes() ?>>
<?= $Page->rencana_tgl_kembali->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->kondisi_buku_peminjaman->Visible) { // kondisi_buku_peminjaman ?>
        <td data-name="kondisi_buku_peminjaman" <?= $Page->kondisi_buku_peminjaman->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_datapengembalianbuku_kondisi_buku_peminjaman">
<span<?= $Page->kondisi_buku_peminjaman->viewAttributes() ?>>
<?= $Page->kondisi_buku_peminjaman->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->tgl_kembali->Visible) { // tgl_kembali ?>
        <td data-name="tgl_kembali" <?= $Page->tgl_kembali->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_datapengembalianbuku_tgl_kembali">
<span<?= $Page->tgl_kembali->viewAttributes() ?>>
<?= $Page->tgl_kembali->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->kondisi_buku_kembali->Visible) { // kondisi_buku_kembali ?>
        <td data-name="kondisi_buku_kembali" <?= $Page->kondisi_buku_kembali->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_datapengembalianbuku_kondisi_buku_kembali">
<span<?= $Page->kondisi_buku_kembali->viewAttributes() ?>>
<?= $Page->kondisi_buku_kembali->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Lama_Kembali->Visible) { // Lama_Kembali ?>
        <td data-name="Lama_Kembali" <?= $Page->Lama_Kembali->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_datapengembalianbuku_Lama_Kembali">
<span<?= $Page->Lama_Kembali->viewAttributes() ?>>
<?= $Page->Lama_Kembali->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Lama_Pinjam->Visible) { // Lama_Pinjam ?>
        <td data-name="Lama_Pinjam" <?= $Page->Lama_Pinjam->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_datapengembalianbuku_Lama_Pinjam">
<span<?= $Page->Lama_Pinjam->viewAttributes() ?>>
<?= $Page->Lama_Pinjam->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->Terlambat->Visible) { // Terlambat ?>
        <td data-name="Terlambat" <?= $Page->Terlambat->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_datapengembalianbuku_Terlambat">
<span<?= $Page->Terlambat->viewAttributes() ?>>
<?= $Page->Terlambat->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->id_kembali->Visible) { // id_kembali ?>
        <td data-name="id_kembali" <?= $Page->id_kembali->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_datapengembalianbuku_id_kembali">
<span<?= $Page->id_kembali->viewAttributes() ?>>
<?= $Page->id_kembali->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php
    }
    if (!$Page->isGridAdd()) {
        $Page->Recordset->moveNext();
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
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
<div class="card-footer ew-grid-lower-panel">
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
</div><!-- /.ew-grid -->
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
    ew.addEventHandlers("datapengembalianbuku");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
