<?php

namespace PHPMaker2021\perpus;

// Page object
$PeminjamanPreview = &$Page;
?>
<?php $Page->showPageHeader(); ?>
<?php if ($Page->TotalRecords > 0) { ?>
<div class="card ew-grid peminjaman"><!-- .card -->
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
<?php if ($Page->id_peminjaman->Visible) { // id_peminjaman ?>
    <?php if ($Page->SortUrl($Page->id_peminjaman) == "") { ?>
        <th class="<?= $Page->id_peminjaman->headerCellClass() ?>"><?= $Page->id_peminjaman->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->id_peminjaman->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->id_peminjaman->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->id_peminjaman->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->id_peminjaman->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->id_peminjaman->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->berita_peminjaman->Visible) { // berita_peminjaman ?>
    <?php if ($Page->SortUrl($Page->berita_peminjaman) == "") { ?>
        <th class="<?= $Page->berita_peminjaman->headerCellClass() ?>"><?= $Page->berita_peminjaman->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->berita_peminjaman->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->berita_peminjaman->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->berita_peminjaman->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->berita_peminjaman->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->berita_peminjaman->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->id_buku->Visible) { // id_buku ?>
    <?php if ($Page->SortUrl($Page->id_buku) == "") { ?>
        <th class="<?= $Page->id_buku->headerCellClass() ?>"><?= $Page->id_buku->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->id_buku->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->id_buku->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->id_buku->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->id_buku->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->id_buku->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->id_anggota->Visible) { // id_anggota ?>
    <?php if ($Page->SortUrl($Page->id_anggota) == "") { ?>
        <th class="<?= $Page->id_anggota->headerCellClass() ?>"><?= $Page->id_anggota->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->id_anggota->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->id_anggota->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->id_anggota->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->id_anggota->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->id_anggota->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->tgl_peminjaman->Visible) { // tgl_peminjaman ?>
    <?php if ($Page->SortUrl($Page->tgl_peminjaman) == "") { ?>
        <th class="<?= $Page->tgl_peminjaman->headerCellClass() ?>"><?= $Page->tgl_peminjaman->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->tgl_peminjaman->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->tgl_peminjaman->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->tgl_peminjaman->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->tgl_peminjaman->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->tgl_peminjaman->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->rencana_tgl_kembali->Visible) { // rencana_tgl_kembali ?>
    <?php if ($Page->SortUrl($Page->rencana_tgl_kembali) == "") { ?>
        <th class="<?= $Page->rencana_tgl_kembali->headerCellClass() ?>"><?= $Page->rencana_tgl_kembali->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->rencana_tgl_kembali->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->rencana_tgl_kembali->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->rencana_tgl_kembali->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->rencana_tgl_kembali->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->rencana_tgl_kembali->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
        </div></div></th>
    <?php } ?>
<?php } ?>
<?php if ($Page->kondisi_buku_peminjaman->Visible) { // kondisi_buku_peminjaman ?>
    <?php if ($Page->SortUrl($Page->kondisi_buku_peminjaman) == "") { ?>
        <th class="<?= $Page->kondisi_buku_peminjaman->headerCellClass() ?>"><?= $Page->kondisi_buku_peminjaman->caption() ?></th>
    <?php } else { ?>
        <th class="<?= $Page->kondisi_buku_peminjaman->headerCellClass() ?>"><div class="ew-pointer" data-sort="<?= HtmlEncode($Page->kondisi_buku_peminjaman->Name) ?>" data-sort-order="<?= $Page->SortField == $Page->kondisi_buku_peminjaman->Name && $Page->SortOrder == "ASC" ? "DESC" : "ASC" ?>">
            <div class="ew-table-header-btn"><span class="ew-table-header-caption"><?= $Page->kondisi_buku_peminjaman->caption() ?></span><span class="ew-table-header-sort"><?php if ($Page->SortField == $Page->kondisi_buku_peminjaman->Name) { ?><?php if ($Page->SortOrder == "ASC") { ?><i class="fas fa-sort-up"></i><?php } elseif ($Page->SortOrder == "DESC") { ?><i class="fas fa-sort-down"></i><?php } ?><?php } ?></span>
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
<?php if ($Page->id_peminjaman->Visible) { // id_peminjaman ?>
        <!-- id_peminjaman -->
        <td<?= $Page->id_peminjaman->cellAttributes() ?>>
<span<?= $Page->id_peminjaman->viewAttributes() ?>>
<?= $Page->id_peminjaman->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->berita_peminjaman->Visible) { // berita_peminjaman ?>
        <!-- berita_peminjaman -->
        <td<?= $Page->berita_peminjaman->cellAttributes() ?>>
<span<?= $Page->berita_peminjaman->viewAttributes() ?>>
<?= $Page->berita_peminjaman->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->id_buku->Visible) { // id_buku ?>
        <!-- id_buku -->
        <td<?= $Page->id_buku->cellAttributes() ?>>
<span<?= $Page->id_buku->viewAttributes() ?>>
<?= $Page->id_buku->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->id_anggota->Visible) { // id_anggota ?>
        <!-- id_anggota -->
        <td<?= $Page->id_anggota->cellAttributes() ?>>
<span<?= $Page->id_anggota->viewAttributes() ?>>
<?= $Page->id_anggota->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->tgl_peminjaman->Visible) { // tgl_peminjaman ?>
        <!-- tgl_peminjaman -->
        <td<?= $Page->tgl_peminjaman->cellAttributes() ?>>
<span<?= $Page->tgl_peminjaman->viewAttributes() ?>>
<?= $Page->tgl_peminjaman->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->rencana_tgl_kembali->Visible) { // rencana_tgl_kembali ?>
        <!-- rencana_tgl_kembali -->
        <td<?= $Page->rencana_tgl_kembali->cellAttributes() ?>>
<span<?= $Page->rencana_tgl_kembali->viewAttributes() ?>>
<?= $Page->rencana_tgl_kembali->getViewValue() ?></span>
</td>
<?php } ?>
<?php if ($Page->kondisi_buku_peminjaman->Visible) { // kondisi_buku_peminjaman ?>
        <!-- kondisi_buku_peminjaman -->
        <td<?= $Page->kondisi_buku_peminjaman->cellAttributes() ?>>
<span<?= $Page->kondisi_buku_peminjaman->viewAttributes() ?>>
<?= $Page->kondisi_buku_peminjaman->getViewValue() ?></span>
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
