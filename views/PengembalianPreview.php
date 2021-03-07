<?php

namespace PHPMaker2021\perpus;

// Page object
$PengembalianPreview = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid pengembalian"><!-- .card -->
<div class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel ew-preview-middle-panel"><!-- .table-responsive -->
<table class="table ew-table ew-preview-table"><!-- .table -->
    <thead><!-- Table header -->
        <tr class="ew-table-header">
<?php
// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->id_kembali->Visible) { // id_kembali ?>
    <?php if ($Page->SortUrl($Page->id_kembali) == "") { ?>
        <th class="<?= $Page->id_kembali->headerCellClass() ?>"><?= $Page->id_kembali->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->id_kembali->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->id_kembali->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->id_kembali->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->id_kembali->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->id_kembali->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->id_peminjaman->Visible) { // id_peminjaman ?>
    <?php if ($Page->SortUrl($Page->id_peminjaman) == "") { ?>
        <th class="<?= $Page->id_peminjaman->headerCellClass() ?>"><?= $Page->id_peminjaman->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->id_peminjaman->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->id_peminjaman->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->id_peminjaman->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->id_peminjaman->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->id_peminjaman->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->tgl_kembali->Visible) { // tgl_kembali ?>
    <?php if ($Page->SortUrl($Page->tgl_kembali) == "") { ?>
        <th class="<?= $Page->tgl_kembali->headerCellClass() ?>"><?= $Page->tgl_kembali->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->tgl_kembali->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->tgl_kembali->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->tgl_kembali->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->tgl_kembali->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->tgl_kembali->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->kondisi_buku_kembali->Visible) { // kondisi_buku_kembali ?>
    <?php if ($Page->SortUrl($Page->kondisi_buku_kembali) == "") { ?>
        <th class="<?= $Page->kondisi_buku_kembali->headerCellClass() ?>"><?= $Page->kondisi_buku_kembali->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->kondisi_buku_kembali->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->kondisi_buku_kembali->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->kondisi_buku_kembali->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->kondisi_buku_kembali->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->kondisi_buku_kembali->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
        </tr>
    </thead>
    <tbody><!-- Table body -->
<?php
$Page->RecCount = 0;
$Page->RowCount = 0;
while ($Page->Recordset && !$Page->Recordset->EOF) {
    // Init row class and style
    $Page->RecCount++;
    $Page->RowCount++;
    $Page->CssStyle = "";
    $Page->loadListRowValues($Page->Recordset);

    // Render row
    $Page->RowType = ROWTYPE_PREVIEW; // Preview record
    $Page->resetAttributes();
    $Page->renderListRow();

    // Render list options
    $Page->renderListOptions();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
<?php if ($Page->id_kembali->Visible) { // id_kembali ?>
        <!-- id_kembali -->
        <td<?= $Page->id_kembali->cellAttributes() ?>>
<span<?= $Page->id_kembali->viewAttributes() ?>>
<?= $Page->id_kembali->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->id_peminjaman->Visible) { // id_peminjaman ?>
        <!-- id_peminjaman -->
        <td<?= $Page->id_peminjaman->cellAttributes() ?>>
<span<?= $Page->id_peminjaman->viewAttributes() ?>>
<?= $Page->id_peminjaman->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->tgl_kembali->Visible) { // tgl_kembali ?>
        <!-- tgl_kembali -->
        <td<?= $Page->tgl_kembali->cellAttributes() ?>>
<span<?= $Page->tgl_kembali->viewAttributes() ?>>
<?= $Page->tgl_kembali->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->kondisi_buku_kembali->Visible) { // kondisi_buku_kembali ?>
        <!-- kondisi_buku_kembali -->
        <td<?= $Page->kondisi_buku_kembali->cellAttributes() ?>>
<span<?= $Page->kondisi_buku_kembali->viewAttributes() ?>>
<?= $Page->kondisi_buku_kembali->getViewValue() ?></span>
</td>
<?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php
    $Page->Recordset->moveNext();
} // while
?>
    </tbody>
</table><!-- /.table -->
</div><!-- /.table-responsive -->
<div class="card-footer ew-grid-lower-panel ew-preview-lower-panel"><!-- .card-footer -->
<?= $Page->Pager->render() ?>
<?php } else { // No record ?>
<div class="card no-border">
<div class="ew-detail-count"><?= $Language->phrase("NoRecord") ?></div>
<?php } ?>
<div class="ew-preview-other-options">
<?php
    foreach ($Page->OtherOptions as $option)
        $option->render("body");
?>
</div>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="clearfix"></div>
</div><!-- /.card-footer -->
<?php } ?>
</div><!-- /.card -->
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php
if ($Page->Recordset) {
    $Page->Recordset->close();
}
?>
