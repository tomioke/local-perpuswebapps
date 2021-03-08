<?php

namespace PHPMaker2021\perpusupdate;

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

// Handle Routes
return function (App $app) {
    // anggota
    $app->any('/AnggotaList[/{id_anggota}]', AnggotaController::class . ':list')->add(PermissionMiddleware::class)->setName('AnggotaList-anggota-list'); // list
    $app->any('/AnggotaAdd[/{id_anggota}]', AnggotaController::class . ':add')->add(PermissionMiddleware::class)->setName('AnggotaAdd-anggota-add'); // add
    $app->any('/AnggotaView[/{id_anggota}]', AnggotaController::class . ':view')->add(PermissionMiddleware::class)->setName('AnggotaView-anggota-view'); // view
    $app->any('/AnggotaEdit[/{id_anggota}]', AnggotaController::class . ':edit')->add(PermissionMiddleware::class)->setName('AnggotaEdit-anggota-edit'); // edit
    $app->any('/AnggotaDelete[/{id_anggota}]', AnggotaController::class . ':delete')->add(PermissionMiddleware::class)->setName('AnggotaDelete-anggota-delete'); // delete
    $app->group(
        '/anggota',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id_anggota}]', AnggotaController::class . ':list')->add(PermissionMiddleware::class)->setName('anggota/list-anggota-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id_anggota}]', AnggotaController::class . ':add')->add(PermissionMiddleware::class)->setName('anggota/add-anggota-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id_anggota}]', AnggotaController::class . ':view')->add(PermissionMiddleware::class)->setName('anggota/view-anggota-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id_anggota}]', AnggotaController::class . ':edit')->add(PermissionMiddleware::class)->setName('anggota/edit-anggota-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id_anggota}]', AnggotaController::class . ':delete')->add(PermissionMiddleware::class)->setName('anggota/delete-anggota-delete-2'); // delete
        }
    );

    // buku
    $app->any('/BukuList[/{id_buku}]', BukuController::class . ':list')->add(PermissionMiddleware::class)->setName('BukuList-buku-list'); // list
    $app->any('/BukuAdd[/{id_buku}]', BukuController::class . ':add')->add(PermissionMiddleware::class)->setName('BukuAdd-buku-add'); // add
    $app->any('/BukuView[/{id_buku}]', BukuController::class . ':view')->add(PermissionMiddleware::class)->setName('BukuView-buku-view'); // view
    $app->any('/BukuEdit[/{id_buku}]', BukuController::class . ':edit')->add(PermissionMiddleware::class)->setName('BukuEdit-buku-edit'); // edit
    $app->any('/BukuDelete[/{id_buku}]', BukuController::class . ':delete')->add(PermissionMiddleware::class)->setName('BukuDelete-buku-delete'); // delete
    $app->any('/BukuPreview', BukuController::class . ':preview')->add(PermissionMiddleware::class)->setName('BukuPreview-buku-preview'); // preview
    $app->group(
        '/buku',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id_buku}]', BukuController::class . ':list')->add(PermissionMiddleware::class)->setName('buku/list-buku-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id_buku}]', BukuController::class . ':add')->add(PermissionMiddleware::class)->setName('buku/add-buku-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id_buku}]', BukuController::class . ':view')->add(PermissionMiddleware::class)->setName('buku/view-buku-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id_buku}]', BukuController::class . ':edit')->add(PermissionMiddleware::class)->setName('buku/edit-buku-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id_buku}]', BukuController::class . ':delete')->add(PermissionMiddleware::class)->setName('buku/delete-buku-delete-2'); // delete
            $group->any('/' . Config("PREVIEW_ACTION") . '', BukuController::class . ':preview')->add(PermissionMiddleware::class)->setName('buku/preview-buku-preview-2'); // preview
        }
    );

    // peminjaman
    $app->any('/PeminjamanList[/{id_peminjaman}]', PeminjamanController::class . ':list')->add(PermissionMiddleware::class)->setName('PeminjamanList-peminjaman-list'); // list
    $app->any('/PeminjamanAdd[/{id_peminjaman}]', PeminjamanController::class . ':add')->add(PermissionMiddleware::class)->setName('PeminjamanAdd-peminjaman-add'); // add
    $app->any('/PeminjamanView[/{id_peminjaman}]', PeminjamanController::class . ':view')->add(PermissionMiddleware::class)->setName('PeminjamanView-peminjaman-view'); // view
    $app->any('/PeminjamanEdit[/{id_peminjaman}]', PeminjamanController::class . ':edit')->add(PermissionMiddleware::class)->setName('PeminjamanEdit-peminjaman-edit'); // edit
    $app->any('/PeminjamanDelete[/{id_peminjaman}]', PeminjamanController::class . ':delete')->add(PermissionMiddleware::class)->setName('PeminjamanDelete-peminjaman-delete'); // delete
    $app->group(
        '/peminjaman',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id_peminjaman}]', PeminjamanController::class . ':list')->add(PermissionMiddleware::class)->setName('peminjaman/list-peminjaman-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id_peminjaman}]', PeminjamanController::class . ':add')->add(PermissionMiddleware::class)->setName('peminjaman/add-peminjaman-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id_peminjaman}]', PeminjamanController::class . ':view')->add(PermissionMiddleware::class)->setName('peminjaman/view-peminjaman-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id_peminjaman}]', PeminjamanController::class . ':edit')->add(PermissionMiddleware::class)->setName('peminjaman/edit-peminjaman-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id_peminjaman}]', PeminjamanController::class . ':delete')->add(PermissionMiddleware::class)->setName('peminjaman/delete-peminjaman-delete-2'); // delete
        }
    );

    // penerbit
    $app->any('/PenerbitList[/{id_penerbit}]', PenerbitController::class . ':list')->add(PermissionMiddleware::class)->setName('PenerbitList-penerbit-list'); // list
    $app->any('/PenerbitAdd[/{id_penerbit}]', PenerbitController::class . ':add')->add(PermissionMiddleware::class)->setName('PenerbitAdd-penerbit-add'); // add
    $app->any('/PenerbitView[/{id_penerbit}]', PenerbitController::class . ':view')->add(PermissionMiddleware::class)->setName('PenerbitView-penerbit-view'); // view
    $app->any('/PenerbitEdit[/{id_penerbit}]', PenerbitController::class . ':edit')->add(PermissionMiddleware::class)->setName('PenerbitEdit-penerbit-edit'); // edit
    $app->any('/PenerbitDelete[/{id_penerbit}]', PenerbitController::class . ':delete')->add(PermissionMiddleware::class)->setName('PenerbitDelete-penerbit-delete'); // delete
    $app->group(
        '/penerbit',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id_penerbit}]', PenerbitController::class . ':list')->add(PermissionMiddleware::class)->setName('penerbit/list-penerbit-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id_penerbit}]', PenerbitController::class . ':add')->add(PermissionMiddleware::class)->setName('penerbit/add-penerbit-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id_penerbit}]', PenerbitController::class . ':view')->add(PermissionMiddleware::class)->setName('penerbit/view-penerbit-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id_penerbit}]', PenerbitController::class . ':edit')->add(PermissionMiddleware::class)->setName('penerbit/edit-penerbit-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id_penerbit}]', PenerbitController::class . ':delete')->add(PermissionMiddleware::class)->setName('penerbit/delete-penerbit-delete-2'); // delete
        }
    );

    // pengarang
    $app->any('/PengarangList[/{id_pengarang}]', PengarangController::class . ':list')->add(PermissionMiddleware::class)->setName('PengarangList-pengarang-list'); // list
    $app->any('/PengarangAdd[/{id_pengarang}]', PengarangController::class . ':add')->add(PermissionMiddleware::class)->setName('PengarangAdd-pengarang-add'); // add
    $app->any('/PengarangView[/{id_pengarang}]', PengarangController::class . ':view')->add(PermissionMiddleware::class)->setName('PengarangView-pengarang-view'); // view
    $app->any('/PengarangEdit[/{id_pengarang}]', PengarangController::class . ':edit')->add(PermissionMiddleware::class)->setName('PengarangEdit-pengarang-edit'); // edit
    $app->any('/PengarangDelete[/{id_pengarang}]', PengarangController::class . ':delete')->add(PermissionMiddleware::class)->setName('PengarangDelete-pengarang-delete'); // delete
    $app->group(
        '/pengarang',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id_pengarang}]', PengarangController::class . ':list')->add(PermissionMiddleware::class)->setName('pengarang/list-pengarang-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id_pengarang}]', PengarangController::class . ':add')->add(PermissionMiddleware::class)->setName('pengarang/add-pengarang-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id_pengarang}]', PengarangController::class . ':view')->add(PermissionMiddleware::class)->setName('pengarang/view-pengarang-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id_pengarang}]', PengarangController::class . ':edit')->add(PermissionMiddleware::class)->setName('pengarang/edit-pengarang-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id_pengarang}]', PengarangController::class . ':delete')->add(PermissionMiddleware::class)->setName('pengarang/delete-pengarang-delete-2'); // delete
        }
    );

    // pengembalian
    $app->any('/PengembalianList[/{id_kembali}]', PengembalianController::class . ':list')->add(PermissionMiddleware::class)->setName('PengembalianList-pengembalian-list'); // list
    $app->any('/PengembalianAdd[/{id_kembali}]', PengembalianController::class . ':add')->add(PermissionMiddleware::class)->setName('PengembalianAdd-pengembalian-add'); // add
    $app->any('/PengembalianView[/{id_kembali}]', PengembalianController::class . ':view')->add(PermissionMiddleware::class)->setName('PengembalianView-pengembalian-view'); // view
    $app->any('/PengembalianEdit[/{id_kembali}]', PengembalianController::class . ':edit')->add(PermissionMiddleware::class)->setName('PengembalianEdit-pengembalian-edit'); // edit
    $app->any('/PengembalianDelete[/{id_kembali}]', PengembalianController::class . ':delete')->add(PermissionMiddleware::class)->setName('PengembalianDelete-pengembalian-delete'); // delete
    $app->group(
        '/pengembalian',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id_kembali}]', PengembalianController::class . ':list')->add(PermissionMiddleware::class)->setName('pengembalian/list-pengembalian-list-2'); // list
            $group->any('/' . Config("ADD_ACTION") . '[/{id_kembali}]', PengembalianController::class . ':add')->add(PermissionMiddleware::class)->setName('pengembalian/add-pengembalian-add-2'); // add
            $group->any('/' . Config("VIEW_ACTION") . '[/{id_kembali}]', PengembalianController::class . ':view')->add(PermissionMiddleware::class)->setName('pengembalian/view-pengembalian-view-2'); // view
            $group->any('/' . Config("EDIT_ACTION") . '[/{id_kembali}]', PengembalianController::class . ':edit')->add(PermissionMiddleware::class)->setName('pengembalian/edit-pengembalian-edit-2'); // edit
            $group->any('/' . Config("DELETE_ACTION") . '[/{id_kembali}]', PengembalianController::class . ':delete')->add(PermissionMiddleware::class)->setName('pengembalian/delete-pengembalian-delete-2'); // delete
        }
    );

    // datapengemalianbuku
    $app->any('/DatapengemalianbukuList[/{id_kembali}]', DatapengemalianbukuController::class . ':list')->add(PermissionMiddleware::class)->setName('DatapengemalianbukuList-datapengemalianbuku-list'); // list
    $app->any('/DatapengemalianbukuView[/{id_kembali}]', DatapengemalianbukuController::class . ':view')->add(PermissionMiddleware::class)->setName('DatapengemalianbukuView-datapengemalianbuku-view'); // view
    $app->group(
        '/datapengemalianbuku',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '[/{id_kembali}]', DatapengemalianbukuController::class . ':list')->add(PermissionMiddleware::class)->setName('datapengemalianbuku/list-datapengemalianbuku-list-2'); // list
            $group->any('/' . Config("VIEW_ACTION") . '[/{id_kembali}]', DatapengemalianbukuController::class . ':view')->add(PermissionMiddleware::class)->setName('datapengemalianbuku/view-datapengemalianbuku-view-2'); // view
        }
    );

    // level
    $app->any('/LevelList', LevelController::class . ':list')->add(PermissionMiddleware::class)->setName('LevelList-level-list'); // list
    $app->group(
        '/level',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '', LevelController::class . ':list')->add(PermissionMiddleware::class)->setName('level/list-level-list-2'); // list
        }
    );

    // permission2
    $app->any('/Permission2List', Permission2Controller::class . ':list')->add(PermissionMiddleware::class)->setName('Permission2List-permission2-list'); // list
    $app->group(
        '/permission2',
        function (RouteCollectorProxy $group) {
            $group->any('/' . Config("LIST_ACTION") . '', Permission2Controller::class . ':list')->add(PermissionMiddleware::class)->setName('permission2/list-permission2-list-2'); // list
        }
    );

    // error
    $app->any('/error', OthersController::class . ':error')->add(PermissionMiddleware::class)->setName('error');

    // personal_data
    $app->any('/personaldata', OthersController::class . ':personaldata')->add(PermissionMiddleware::class)->setName('personaldata');

    // login
    $app->any('/login', OthersController::class . ':login')->add(PermissionMiddleware::class)->setName('login');

    // userpriv
    $app->any('/userpriv', OthersController::class . ':userpriv')->add(PermissionMiddleware::class)->setName('userpriv');

    // logout
    $app->any('/logout', OthersController::class . ':logout')->add(PermissionMiddleware::class)->setName('logout');

    // Swagger
    $app->get('/' . Config("SWAGGER_ACTION"), OthersController::class . ':swagger')->setName(Config("SWAGGER_ACTION")); // Swagger

    // Index
    $app->any('/[index]', OthersController::class . ':index')->add(PermissionMiddleware::class)->setName('index');

    // Route Action event
    if (function_exists(PROJECT_NAMESPACE . "Route_Action")) {
        Route_Action($app);
    }

    /**
     * Catch-all route to serve a 404 Not Found page if none of the routes match
     * NOTE: Make sure this route is defined last.
     */
    $app->map(
        ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
        '/{routes:.+}',
        function ($request, $response, $params) {
            $error = [
                "statusCode" => "404",
                "error" => [
                    "class" => "text-warning",
                    "type" => Container("language")->phrase("Error"),
                    "description" => str_replace("%p", $params["routes"], Container("language")->phrase("PageNotFound")),
                ],
            ];
            Container("flash")->addMessage("error", $error);
            return $response->withStatus(302)->withHeader("Location", GetUrl("error")); // Redirect to error page
        }
    );
};
