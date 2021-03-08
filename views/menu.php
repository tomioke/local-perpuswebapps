<?php

namespace PHPMaker2021\perpusupdate;

// Menu Language
if ($Language && function_exists(PROJECT_NAMESPACE . "Config") && $Language->LanguageFolder == Config("LANGUAGE_FOLDER")) {
    $MenuRelativePath = "";
    $MenuLanguage = &$Language;
} else { // Compat reports
    $LANGUAGE_FOLDER = "../lang/";
    $MenuRelativePath = "../";
    $MenuLanguage = Container("language");
}

// Navbar menu
$topMenu = new Menu("navbar", true, true);
echo $topMenu->toScript();

// Sidebar menu
$sideMenu = new Menu("menu", true, false);
$sideMenu->addMenuItem(11, "mci_Master_Input_Buku", $MenuLanguage->MenuPhrase("11", "MenuText"), "", -1, "", true, false, true, "", "", false);
$sideMenu->addMenuItem(1, "mi_anggota", $MenuLanguage->MenuPhrase("1", "MenuText"), $MenuRelativePath . "AnggotaList", 11, "", AllowListMenu('{E9A867BB-2A2C-4629-AFCE-088C627AB559}anggota'), false, false, "", "", false);
$sideMenu->addMenuItem(2, "mi_buku", $MenuLanguage->MenuPhrase("2", "MenuText"), $MenuRelativePath . "BukuList?cmd=resetall", 11, "", AllowListMenu('{E9A867BB-2A2C-4629-AFCE-088C627AB559}buku'), false, false, "", "", false);
$sideMenu->addMenuItem(6, "mi_penerbit", $MenuLanguage->MenuPhrase("6", "MenuText"), $MenuRelativePath . "PenerbitList", 11, "", AllowListMenu('{E9A867BB-2A2C-4629-AFCE-088C627AB559}penerbit'), false, false, "", "", false);
$sideMenu->addMenuItem(7, "mi_pengarang", $MenuLanguage->MenuPhrase("7", "MenuText"), $MenuRelativePath . "PengarangList", 11, "", AllowListMenu('{E9A867BB-2A2C-4629-AFCE-088C627AB559}pengarang'), false, false, "", "", false);
$sideMenu->addMenuItem(13, "mci_Pelayanan", $MenuLanguage->MenuPhrase("13", "MenuText"), "", -1, "", true, false, true, "", "", false);
$sideMenu->addMenuItem(5, "mi_peminjaman", $MenuLanguage->MenuPhrase("5", "MenuText"), $MenuRelativePath . "PeminjamanList", 13, "", AllowListMenu('{E9A867BB-2A2C-4629-AFCE-088C627AB559}peminjaman'), false, false, "", "", false);
$sideMenu->addMenuItem(8, "mi_pengembalian", $MenuLanguage->MenuPhrase("8", "MenuText"), $MenuRelativePath . "PengembalianList", 13, "", AllowListMenu('{E9A867BB-2A2C-4629-AFCE-088C627AB559}pengembalian'), false, false, "", "", false);
$sideMenu->addMenuItem(10, "mi_datapengemalianbuku", $MenuLanguage->MenuPhrase("10", "MenuText"), $MenuRelativePath . "DatapengemalianbukuList", 13, "", AllowListMenu('{E9A867BB-2A2C-4629-AFCE-088C627AB559}data-pengemalian-buku'), false, false, "", "", false);
$sideMenu->addMenuItem(14, "mi_level", $MenuLanguage->MenuPhrase("14", "MenuText"), $MenuRelativePath . "LevelList", -1, "", AllowListMenu('{E9A867BB-2A2C-4629-AFCE-088C627AB559}level'), false, false, "", "", false);
$sideMenu->addMenuItem(15, "mi_permission2", $MenuLanguage->MenuPhrase("15", "MenuText"), $MenuRelativePath . "Permission2List", -1, "", AllowListMenu('{E9A867BB-2A2C-4629-AFCE-088C627AB559}permission'), false, false, "", "", false);
echo $sideMenu->toScript();
