<?php

namespace PHPMaker2021\perpus;

// Table
$peminjaman = Container("peminjaman");
?>
<?php if ($peminjaman->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_peminjamanmaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($peminjaman->id_peminjaman->Visible) { // id_peminjaman ?>
        <tr id="r_id_peminjaman">
            <td class="<?= $peminjaman->TableLeftColumnClass ?>"><?= $peminjaman->id_peminjaman->caption() ?></td>
            <td <?= $peminjaman->id_peminjaman->cellAttributes() ?>>
<span id="el_peminjaman_id_peminjaman">
<span<?= $peminjaman->id_peminjaman->viewAttributes() ?>>
<?= $peminjaman->id_peminjaman->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($peminjaman->berita_peminjaman->Visible) { // berita_peminjaman ?>
        <tr id="r_berita_peminjaman">
            <td class="<?= $peminjaman->TableLeftColumnClass ?>"><?= $peminjaman->berita_peminjaman->caption() ?></td>
            <td <?= $peminjaman->berita_peminjaman->cellAttributes() ?>>
<span id="el_peminjaman_berita_peminjaman">
<span<?= $peminjaman->berita_peminjaman->viewAttributes() ?>>
<?= $peminjaman->berita_peminjaman->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($peminjaman->id_buku->Visible) { // id_buku ?>
        <tr id="r_id_buku">
            <td class="<?= $peminjaman->TableLeftColumnClass ?>"><?= $peminjaman->id_buku->caption() ?></td>
            <td <?= $peminjaman->id_buku->cellAttributes() ?>>
<span id="el_peminjaman_id_buku">
<span<?= $peminjaman->id_buku->viewAttributes() ?>>
<?= $peminjaman->id_buku->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($peminjaman->id_anggota->Visible) { // id_anggota ?>
        <tr id="r_id_anggota">
            <td class="<?= $peminjaman->TableLeftColumnClass ?>"><?= $peminjaman->id_anggota->caption() ?></td>
            <td <?= $peminjaman->id_anggota->cellAttributes() ?>>
<span id="el_peminjaman_id_anggota">
<span<?= $peminjaman->id_anggota->viewAttributes() ?>>
<?= $peminjaman->id_anggota->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($peminjaman->tgl_peminjaman->Visible) { // tgl_peminjaman ?>
        <tr id="r_tgl_peminjaman">
            <td class="<?= $peminjaman->TableLeftColumnClass ?>"><?= $peminjaman->tgl_peminjaman->caption() ?></td>
            <td <?= $peminjaman->tgl_peminjaman->cellAttributes() ?>>
<span id="el_peminjaman_tgl_peminjaman">
<span<?= $peminjaman->tgl_peminjaman->viewAttributes() ?>>
<?= $peminjaman->tgl_peminjaman->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($peminjaman->rencana_tgl_kembali->Visible) { // rencana_tgl_kembali ?>
        <tr id="r_rencana_tgl_kembali">
            <td class="<?= $peminjaman->TableLeftColumnClass ?>"><?= $peminjaman->rencana_tgl_kembali->caption() ?></td>
            <td <?= $peminjaman->rencana_tgl_kembali->cellAttributes() ?>>
<span id="el_peminjaman_rencana_tgl_kembali">
<span<?= $peminjaman->rencana_tgl_kembali->viewAttributes() ?>>
<?= $peminjaman->rencana_tgl_kembali->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($peminjaman->kondisi_buku_peminjaman->Visible) { // kondisi_buku_peminjaman ?>
        <tr id="r_kondisi_buku_peminjaman">
            <td class="<?= $peminjaman->TableLeftColumnClass ?>"><?= $peminjaman->kondisi_buku_peminjaman->caption() ?></td>
            <td <?= $peminjaman->kondisi_buku_peminjaman->cellAttributes() ?>>
<span id="el_peminjaman_kondisi_buku_peminjaman">
<span<?= $peminjaman->kondisi_buku_peminjaman->viewAttributes() ?>>
<?= $peminjaman->kondisi_buku_peminjaman->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
