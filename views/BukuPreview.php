<?php

namespace PHPMaker2021\perpusupdate;

// Page object
$BukuPreview = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid buku"><!-- .card -->
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
<?php if ($Page->cover->Visible) { // cover ?>
    <?php if ($Page->SortUrl($Page->cover) == "") { ?>
        <th class="<?= $Page->cover->headerCellClass() ?>"><?= $Page->cover->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->cover->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->cover->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->cover->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->cover->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->cover->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->nama_buku->Visible) { // nama_buku ?>
    <?php if ($Page->SortUrl($Page->nama_buku) == "") { ?>
        <th class="<?= $Page->nama_buku->headerCellClass() ?>"><?= $Page->nama_buku->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->nama_buku->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->nama_buku->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->nama_buku->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->nama_buku->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->nama_buku->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->pengarang->Visible) { // pengarang ?>
    <?php if ($Page->SortUrl($Page->pengarang) == "") { ?>
        <th class="<?= $Page->pengarang->headerCellClass() ?>"><?= $Page->pengarang->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->pengarang->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->pengarang->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->pengarang->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->pengarang->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->pengarang->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->penerbit->Visible) { // penerbit ?>
    <?php if ($Page->SortUrl($Page->penerbit) == "") { ?>
        <th class="<?= $Page->penerbit->headerCellClass() ?>"><?= $Page->penerbit->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->penerbit->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->penerbit->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->penerbit->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->penerbit->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->penerbit->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->kode_isbn->Visible) { // kode_isbn ?>
    <?php if ($Page->SortUrl($Page->kode_isbn) == "") { ?>
        <th class="<?= $Page->kode_isbn->headerCellClass() ?>"><?= $Page->kode_isbn->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->kode_isbn->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->kode_isbn->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->kode_isbn->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->kode_isbn->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->kode_isbn->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
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
<?php if ($Page->cover->Visible) { // cover ?>
        <!-- cover -->
        <td<?= $Page->cover->cellAttributes() ?>>
<span>
<?= GetFileViewTag($Page->cover, $Page->cover->getViewValue(), false) ?>
</span>
</td>
<?php } ?>
<?php if ($Page->nama_buku->Visible) { // nama_buku ?>
        <!-- nama_buku -->
        <td<?= $Page->nama_buku->cellAttributes() ?>>
<span<?= $Page->nama_buku->viewAttributes() ?>>
<?= $Page->nama_buku->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->pengarang->Visible) { // pengarang ?>
        <!-- pengarang -->
        <td<?= $Page->pengarang->cellAttributes() ?>>
<span<?= $Page->pengarang->viewAttributes() ?>>
<?= $Page->pengarang->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->penerbit->Visible) { // penerbit ?>
        <!-- penerbit -->
        <td<?= $Page->penerbit->cellAttributes() ?>>
<span<?= $Page->penerbit->viewAttributes() ?>>
<?= $Page->penerbit->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->kode_isbn->Visible) { // kode_isbn ?>
        <!-- kode_isbn -->
        <td<?= $Page->kode_isbn->cellAttributes() ?>>
<span<?= $Page->kode_isbn->viewAttributes() ?>>
<?= $Page->kode_isbn->getViewValue() ?></span>
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
