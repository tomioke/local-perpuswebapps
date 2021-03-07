<?php

namespace PHPMaker2021\perpus;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for data-pengemalian-buku
 */
class Datapengemalianbuku extends DbTable
{
    protected $SqlFrom = "";
    protected $SqlSelect = null;
    protected $SqlSelectList = null;
    protected $SqlWhere = "";
    protected $SqlGroupBy = "";
    protected $SqlHaving = "";
    protected $SqlOrderBy = "";
    public $UseSessionForListSql = true;

    // Column CSS classes
    public $LeftColumnClass = "col-sm-2 col-form-label ew-label";
    public $RightColumnClass = "col-sm-10";
    public $OffsetColumnClass = "col-sm-10 offset-sm-2";
    public $TableLeftColumnClass = "w-col-2";

    // Export
    public $ExportDoc;

    // Fields
    public $id_kembali;
    public $berita_peminjaman;
    public $id_peminjaman;
    public $id_buku;
    public $id_anggota;
    public $tgl_peminjaman;
    public $rencana_tgl_kembali;
    public $kondisi_buku_peminjaman;
    public $tgl_kembali;
    public $kondisi_buku_kembali;
    public $Lama_Kembali;
    public $Lama_Pinjam;
    public $Terlambat;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'datapengemalianbuku';
        $this->TableName = 'data-pengemalian-buku';
        $this->TableType = 'VIEW';

        // Update Table
        $this->UpdateTable = "`data-pengemalian-buku`";
        $this->Dbid = 'DB';
        $this->ExportAll = true;
        $this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
        $this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
        $this->ExportPageSize = "a4"; // Page size (PDF only)
        $this->ExportExcelPageOrientation = \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_DEFAULT; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4; // Page size (PhpSpreadsheet only)
        $this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
        $this->ExportWordColumnWidth = null; // Cell width (PHPWord only)
        $this->DetailAdd = false; // Allow detail add
        $this->DetailEdit = false; // Allow detail edit
        $this->DetailView = false; // Allow detail view
        $this->ShowMultipleDetails = false; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->BasicSearch = new BasicSearch($this->TableVar);

        // id_kembali
        $this->id_kembali = new DbField('datapengemalianbuku', 'data-pengemalian-buku', 'x_id_kembali', 'id_kembali', '`id_kembali`', '`id_kembali`', 3, 100, -1, false, '`id_kembali`', false, false, false, 'FORMATTED TEXT', 'NO');
        $this->id_kembali->IsAutoIncrement = true; // Autoincrement field
        $this->id_kembali->IsPrimaryKey = true; // Primary key field
        $this->id_kembali->Sortable = true; // Allow sort
        $this->id_kembali->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['id_kembali'] = &$this->id_kembali;

        // berita_peminjaman
        $this->berita_peminjaman = new DbField('datapengemalianbuku', 'data-pengemalian-buku', 'x_berita_peminjaman', 'berita_peminjaman', '`berita_peminjaman`', '`berita_peminjaman`', 200, 200, -1, false, '`berita_peminjaman`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->berita_peminjaman->Nullable = false; // NOT NULL field
        $this->berita_peminjaman->Required = true; // Required field
        $this->berita_peminjaman->Sortable = true; // Allow sort
        $this->Fields['berita_peminjaman'] = &$this->berita_peminjaman;

        // id_peminjaman
        $this->id_peminjaman = new DbField('datapengemalianbuku', 'data-pengemalian-buku', 'x_id_peminjaman', 'id_peminjaman', '`id_peminjaman`', '`id_peminjaman`', 3, 100, -1, false, '`id_peminjaman`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->id_peminjaman->Nullable = false; // NOT NULL field
        $this->id_peminjaman->Required = true; // Required field
        $this->id_peminjaman->Sortable = true; // Allow sort
        $this->id_peminjaman->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['id_peminjaman'] = &$this->id_peminjaman;

        // id_buku
        $this->id_buku = new DbField('datapengemalianbuku', 'data-pengemalian-buku', 'x_id_buku', 'id_buku', '`id_buku`', '`id_buku`', 3, 100, -1, false, '`EV__id_buku`', true, true, true, 'FORMATTED TEXT', 'SELECT');
        $this->id_buku->Nullable = false; // NOT NULL field
        $this->id_buku->Required = true; // Required field
        $this->id_buku->Sortable = true; // Allow sort
        $this->id_buku->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->id_buku->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->id_buku->Lookup = new Lookup('id_buku', 'buku', false, 'id_buku', ["nama_buku","","",""], [], [], [], [], [], [], '', '');
        $this->id_buku->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['id_buku'] = &$this->id_buku;

        // id_anggota
        $this->id_anggota = new DbField('datapengemalianbuku', 'data-pengemalian-buku', 'x_id_anggota', 'id_anggota', '`id_anggota`', '`id_anggota`', 3, 100, -1, false, '`EV__id_anggota`', true, true, true, 'FORMATTED TEXT', 'SELECT');
        $this->id_anggota->Nullable = false; // NOT NULL field
        $this->id_anggota->Required = true; // Required field
        $this->id_anggota->Sortable = true; // Allow sort
        $this->id_anggota->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->id_anggota->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->id_anggota->Lookup = new Lookup('id_anggota', 'anggota', false, 'id_anggota', ["nama_anggota","","",""], [], [], [], [], [], [], '', '');
        $this->id_anggota->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['id_anggota'] = &$this->id_anggota;

        // tgl_peminjaman
        $this->tgl_peminjaman = new DbField('datapengemalianbuku', 'data-pengemalian-buku', 'x_tgl_peminjaman', 'tgl_peminjaman', '`tgl_peminjaman`', CastDateFieldForLike("`tgl_peminjaman`", 0, "DB"), 133, 10, 0, false, '`tgl_peminjaman`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tgl_peminjaman->Nullable = false; // NOT NULL field
        $this->tgl_peminjaman->Sortable = true; // Allow sort
        $this->tgl_peminjaman->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['tgl_peminjaman'] = &$this->tgl_peminjaman;

        // rencana_tgl_kembali
        $this->rencana_tgl_kembali = new DbField('datapengemalianbuku', 'data-pengemalian-buku', 'x_rencana_tgl_kembali', 'rencana_tgl_kembali', '`rencana_tgl_kembali`', CastDateFieldForLike("`rencana_tgl_kembali`", 0, "DB"), 133, 10, 0, false, '`rencana_tgl_kembali`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->rencana_tgl_kembali->Nullable = false; // NOT NULL field
        $this->rencana_tgl_kembali->Required = true; // Required field
        $this->rencana_tgl_kembali->Sortable = true; // Allow sort
        $this->rencana_tgl_kembali->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['rencana_tgl_kembali'] = &$this->rencana_tgl_kembali;

        // kondisi_buku_peminjaman
        $this->kondisi_buku_peminjaman = new DbField('datapengemalianbuku', 'data-pengemalian-buku', 'x_kondisi_buku_peminjaman', 'kondisi_buku_peminjaman', '`kondisi_buku_peminjaman`', '`kondisi_buku_peminjaman`', 200, 200, -1, false, '`kondisi_buku_peminjaman`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->kondisi_buku_peminjaman->Nullable = false; // NOT NULL field
        $this->kondisi_buku_peminjaman->Required = true; // Required field
        $this->kondisi_buku_peminjaman->Sortable = true; // Allow sort
        $this->Fields['kondisi_buku_peminjaman'] = &$this->kondisi_buku_peminjaman;

        // tgl_kembali
        $this->tgl_kembali = new DbField('datapengemalianbuku', 'data-pengemalian-buku', 'x_tgl_kembali', 'tgl_kembali', '`tgl_kembali`', CastDateFieldForLike("`tgl_kembali`", 0, "DB"), 133, 10, 0, false, '`tgl_kembali`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->tgl_kembali->Nullable = false; // NOT NULL field
        $this->tgl_kembali->Required = true; // Required field
        $this->tgl_kembali->Sortable = true; // Allow sort
        $this->tgl_kembali->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['tgl_kembali'] = &$this->tgl_kembali;

        // kondisi_buku_kembali
        $this->kondisi_buku_kembali = new DbField('datapengemalianbuku', 'data-pengemalian-buku', 'x_kondisi_buku_kembali', 'kondisi_buku_kembali', '`kondisi_buku_kembali`', '`kondisi_buku_kembali`', 200, 200, -1, false, '`kondisi_buku_kembali`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->kondisi_buku_kembali->Nullable = false; // NOT NULL field
        $this->kondisi_buku_kembali->Required = true; // Required field
        $this->kondisi_buku_kembali->Sortable = true; // Allow sort
        $this->Fields['kondisi_buku_kembali'] = &$this->kondisi_buku_kembali;

        // Lama_Kembali
        $this->Lama_Kembali = new DbField('datapengemalianbuku', 'data-pengemalian-buku', 'x_Lama_Kembali', 'Lama_Kembali', '`Lama_Kembali`', '`Lama_Kembali`', 20, 10, -1, false, '`Lama_Kembali`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->Lama_Kembali->Nullable = false; // NOT NULL field
        $this->Lama_Kembali->Sortable = true; // Allow sort
        $this->Lama_Kembali->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['Lama_Kembali'] = &$this->Lama_Kembali;

        // Lama_Pinjam
        $this->Lama_Pinjam = new DbField('datapengemalianbuku', 'data-pengemalian-buku', 'x_Lama_Pinjam', 'Lama_Pinjam', '`Lama_Pinjam`', '`Lama_Pinjam`', 20, 10, -1, false, '`Lama_Pinjam`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->Lama_Pinjam->Nullable = false; // NOT NULL field
        $this->Lama_Pinjam->Sortable = true; // Allow sort
        $this->Lama_Pinjam->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['Lama_Pinjam'] = &$this->Lama_Pinjam;

        // Terlambat
        $this->Terlambat = new DbField('datapengemalianbuku', 'data-pengemalian-buku', 'x_Terlambat', 'Terlambat', '`Terlambat`', '`Terlambat`', 20, 11, -1, false, '`Terlambat`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->Terlambat->Nullable = false; // NOT NULL field
        $this->Terlambat->Sortable = true; // Allow sort
        $this->Terlambat->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['Terlambat'] = &$this->Terlambat;
    }

    // Field Visibility
    public function getFieldVisibility($fldParm)
    {
        global $Security;
        return $this->$fldParm->Visible; // Returns original value
    }

    // Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
    public function setLeftColumnClass($class)
    {
        if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
            $this->LeftColumnClass = $class . " col-form-label ew-label";
            $this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - (int)$match[2]);
            $this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace("col-", "offset-", $class);
            $this->TableLeftColumnClass = preg_replace('/^col-\w+-(\d+)$/', "w-col-$1", $class); // Change to w-col-*
        }
    }

    // Multiple column sort
    public function updateSort(&$fld, $ctrl)
    {
        if ($this->CurrentOrder == $fld->Name) {
            $sortField = $fld->Expression;
            $lastSort = $fld->getSort();
            if (in_array($this->CurrentOrderType, ["ASC", "DESC", "NO"])) {
                $curSort = $this->CurrentOrderType;
            } else {
                $curSort = $lastSort;
            }
            $fld->setSort($curSort);
            $lastOrderBy = in_array($lastSort, ["ASC", "DESC"]) ? $sortField . " " . $lastSort : "";
            $curOrderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortField . " " . $curSort : "";
            if ($ctrl) {
                $orderBy = $this->getSessionOrderBy();
                $arOrderBy = !empty($orderBy) ? explode(", ", $orderBy) : [];
                if ($lastOrderBy != "" && in_array($lastOrderBy, $arOrderBy)) {
                    foreach ($arOrderBy as $key => $val) {
                        if ($val == $lastOrderBy) {
                            if ($curOrderBy == "") {
                                unset($arOrderBy[$key]);
                            } else {
                                $arOrderBy[$key] = $curOrderBy;
                            }
                        }
                    }
                } elseif ($curOrderBy != "") {
                    $arOrderBy[] = $curOrderBy;
                }
                $orderBy = implode(", ", $arOrderBy);
                $this->setSessionOrderBy($orderBy); // Save to Session
            } else {
                $this->setSessionOrderBy($curOrderBy); // Save to Session
            }
            $sortFieldList = ($fld->VirtualExpression != "") ? $fld->VirtualExpression : $sortField;
            $lastOrderBy = in_array($lastSort, ["ASC", "DESC"]) ? $sortFieldList . " " . $lastSort : "";
            $curOrderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortFieldList . " " . $curSort : "";
            if ($ctrl) {
                $orderByList = $this->getSessionOrderByList();
                $arOrderBy = !empty($orderByList) ? explode(", ", $orderByList) : [];
                if ($lastOrderBy != "" && in_array($lastOrderBy, $arOrderBy)) {
                    foreach ($arOrderBy as $key => $val) {
                        if ($val == $lastOrderBy) {
                            if ($curOrderBy == "") {
                                unset($arOrderBy[$key]);
                            } else {
                                $arOrderBy[$key] = $curOrderBy;
                            }
                        }
                    }
                } elseif ($curOrderBy != "") {
                    $arOrderBy[] = $curOrderBy;
                }
                $orderByList = implode(", ", $arOrderBy);
                $this->setSessionOrderByList($orderByList); // Save to Session
            } else {
                $this->setSessionOrderByList($curOrderBy); // Save to Session
            }
        } else {
            if (!$ctrl) {
                $fld->setSort("");
            }
        }
    }

    // Session ORDER BY for List page
    public function getSessionOrderByList()
    {
        return @$_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_ORDER_BY_LIST")];
    }

    public function setSessionOrderByList($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_ORDER_BY_LIST")] = $v;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`data-pengemalian-buku`";
    }

    public function sqlFrom() // For backward compatibility
    {
        return $this->getSqlFrom();
    }

    public function setSqlFrom($v)
    {
        $this->SqlFrom = $v;
    }

    public function getSqlSelect() // Select
    {
        return $this->SqlSelect ?? $this->getQueryBuilder()->select("*");
    }

    public function sqlSelect() // For backward compatibility
    {
        return $this->getSqlSelect();
    }

    public function setSqlSelect($v)
    {
        $this->SqlSelect = $v;
    }

    public function getSqlSelectList() // Select for List page
    {
        if ($this->SqlSelectList) {
            return $this->SqlSelectList;
        }
        $from = "(SELECT *, (SELECT `nama_buku` FROM `buku` `TMP_LOOKUPTABLE` WHERE `TMP_LOOKUPTABLE`.`id_buku` = `data-pengemalian-buku`.`id_buku` LIMIT 1) AS `EV__id_buku`, (SELECT `nama_anggota` FROM `anggota` `TMP_LOOKUPTABLE` WHERE `TMP_LOOKUPTABLE`.`id_anggota` = `data-pengemalian-buku`.`id_anggota` LIMIT 1) AS `EV__id_anggota` FROM `data-pengemalian-buku`)";
        return $from . " `TMP_TABLE`";
    }

    public function sqlSelectList() // For backward compatibility
    {
        return $this->getSqlSelectList();
    }

    public function setSqlSelectList($v)
    {
        $this->SqlSelectList = $v;
    }

    public function getSqlWhere() // Where
    {
        $where = ($this->SqlWhere != "") ? $this->SqlWhere : "";
        $this->DefaultFilter = "";
        AddFilter($where, $this->DefaultFilter);
        return $where;
    }

    public function sqlWhere() // For backward compatibility
    {
        return $this->getSqlWhere();
    }

    public function setSqlWhere($v)
    {
        $this->SqlWhere = $v;
    }

    public function getSqlGroupBy() // Group By
    {
        return ($this->SqlGroupBy != "") ? $this->SqlGroupBy : "";
    }

    public function sqlGroupBy() // For backward compatibility
    {
        return $this->getSqlGroupBy();
    }

    public function setSqlGroupBy($v)
    {
        $this->SqlGroupBy = $v;
    }

    public function getSqlHaving() // Having
    {
        return ($this->SqlHaving != "") ? $this->SqlHaving : "";
    }

    public function sqlHaving() // For backward compatibility
    {
        return $this->getSqlHaving();
    }

    public function setSqlHaving($v)
    {
        $this->SqlHaving = $v;
    }

    public function getSqlOrderBy() // Order By
    {
        return ($this->SqlOrderBy != "") ? $this->SqlOrderBy : $this->DefaultSort;
    }

    public function sqlOrderBy() // For backward compatibility
    {
        return $this->getSqlOrderBy();
    }

    public function setSqlOrderBy($v)
    {
        $this->SqlOrderBy = $v;
    }

    // Apply User ID filters
    public function applyUserIDFilters($filter)
    {
        global $Security;
        // Add User ID filter
        if ($Security->currentUserID() != "" && !$Security->isAdmin()) { // Non system admin
            $filter = $this->addUserIDFilter($filter);
        }
        return $filter;
    }

    // Check if User ID security allows view all
    public function userIDAllow($id = "")
    {
        $allow = $this->UserIDAllowSecurity;
        switch ($id) {
            case "add":
            case "copy":
            case "gridadd":
            case "register":
            case "addopt":
                return (($allow & 1) == 1);
            case "edit":
            case "gridedit":
            case "update":
            case "changepassword":
            case "resetpassword":
                return (($allow & 4) == 4);
            case "delete":
                return (($allow & 2) == 2);
            case "view":
                return (($allow & 32) == 32);
            case "search":
                return (($allow & 64) == 64);
            default:
                return (($allow & 8) == 8);
        }
    }

    /**
     * Get record count
     *
     * @param string|QueryBuilder $sql SQL or QueryBuilder
     * @param mixed $c Connection
     * @return int
     */
    public function getRecordCount($sql, $c = null)
    {
        $cnt = -1;
        $rs = null;
        if ($sql instanceof \Doctrine\DBAL\Query\QueryBuilder) { // Query builder
            $sql = $sql->resetQueryPart("orderBy")->getSQL();
        }
        $pattern = '/^SELECT\s([\s\S]+)\sFROM\s/i';
        // Skip Custom View / SubQuery / SELECT DISTINCT / ORDER BY
        if (
            ($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') &&
            preg_match($pattern, $sql) && !preg_match('/\(\s*(SELECT[^)]+)\)/i', $sql) &&
            !preg_match('/^\s*select\s+distinct\s+/i', $sql) && !preg_match('/\s+order\s+by\s+/i', $sql)
        ) {
            $sqlwrk = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sql);
        } else {
            $sqlwrk = "SELECT COUNT(*) FROM (" . $sql . ") COUNT_TABLE";
        }
        $conn = $c ?? $this->getConnection();
        $rs = $conn->executeQuery($sqlwrk);
        $cnt = $rs->fetchColumn();
        if ($cnt !== false) {
            return (int)$cnt;
        }

        // Unable to get count by SELECT COUNT(*), execute the SQL to get record count directly
        return ExecuteRecordCount($sql, $conn);
    }

    // Get SQL
    public function getSql($where, $orderBy = "")
    {
        return $this->buildSelectSql(
            $this->getSqlSelect(),
            $this->getSqlFrom(),
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $where,
            $orderBy
        )->getSQL();
    }

    // Table SQL
    public function getCurrentSql()
    {
        $filter = $this->CurrentFilter;
        $filter = $this->applyUserIDFilters($filter);
        $sort = $this->getSessionOrderBy();
        return $this->getSql($filter, $sort);
    }

    /**
     * Table SQL with List page filter
     *
     * @return QueryBuilder
     */
    public function getListSql()
    {
        $filter = $this->UseSessionForListSql ? $this->getSessionWhere() : "";
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        if ($this->useVirtualFields()) {
            $select = "*";
            $from = $this->getSqlSelectList();
            $sort = $this->UseSessionForListSql ? $this->getSessionOrderByList() : "";
        } else {
            $select = $this->getSqlSelect();
            $from = $this->getSqlFrom();
            $sort = $this->UseSessionForListSql ? $this->getSessionOrderBy() : "";
        }
        $this->Sort = $sort;
        return $this->buildSelectSql(
            $select,
            $from,
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $filter,
            $sort
        );
    }

    // Get ORDER BY clause
    public function getOrderBy()
    {
        $orderBy = $this->getSqlOrderBy();
        $sort = ($this->useVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
        if ($orderBy != "" && $sort != "") {
            $orderBy .= ", " . $sort;
        } elseif ($sort != "") {
            $orderBy = $sort;
        }
        return $orderBy;
    }

    // Check if virtual fields is used in SQL
    protected function useVirtualFields()
    {
        $where = $this->UseSessionForListSql ? $this->getSessionWhere() : $this->CurrentFilter;
        $orderBy = $this->UseSessionForListSql ? $this->getSessionOrderByList() : "";
        if ($where != "") {
            $where = " " . str_replace(["(", ")"], ["", ""], $where) . " ";
        }
        if ($orderBy != "") {
            $orderBy = " " . str_replace(["(", ")"], ["", ""], $orderBy) . " ";
        }
        if (
            $this->id_buku->AdvancedSearch->SearchValue != "" ||
            $this->id_buku->AdvancedSearch->SearchValue2 != "" ||
            ContainsString($where, " " . $this->id_buku->VirtualExpression . " ")
        ) {
            return true;
        }
        if (ContainsString($orderBy, " " . $this->id_buku->VirtualExpression . " ")) {
            return true;
        }
        if (
            $this->id_anggota->AdvancedSearch->SearchValue != "" ||
            $this->id_anggota->AdvancedSearch->SearchValue2 != "" ||
            ContainsString($where, " " . $this->id_anggota->VirtualExpression . " ")
        ) {
            return true;
        }
        if (ContainsString($orderBy, " " . $this->id_anggota->VirtualExpression . " ")) {
            return true;
        }
        return false;
    }

    // Get record count based on filter (for detail record count in master table pages)
    public function loadRecordCount($filter)
    {
        $origFilter = $this->CurrentFilter;
        $this->CurrentFilter = $filter;
        $this->recordsetSelecting($this->CurrentFilter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
        $cnt = $this->getRecordCount($sql);
        $this->CurrentFilter = $origFilter;
        return $cnt;
    }

    // Get record count (for current List page)
    public function listRecordCount()
    {
        $filter = $this->getSessionWhere();
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        if ($this->useVirtualFields()) {
            $sql = $this->buildSelectSql("*", $this->getSqlSelectList(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        } else {
            $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        }
        $cnt = $this->getRecordCount($sql);
        return $cnt;
    }

    /**
     * INSERT statement
     *
     * @param mixed $rs
     * @return QueryBuilder
     */
    protected function insertSql(&$rs)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->insert($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->setValue($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        return $queryBuilder;
    }

    // Insert
    public function insert(&$rs)
    {
        $conn = $this->getConnection();
        $success = $this->insertSql($rs)->execute();
        if ($success) {
            // Get insert id if necessary
            $this->id_kembali->setDbValue($conn->lastInsertId());
            $rs['id_kembali'] = $this->id_kembali->DbValue;
        }
        return $success;
    }

    /**
     * UPDATE statement
     *
     * @param array $rs Data to be updated
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    protected function updateSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->update($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom || $this->Fields[$name]->IsAutoIncrement) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->set($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        AddFilter($filter, $where);
        if ($filter != "") {
            $queryBuilder->where($filter);
        }
        return $queryBuilder;
    }

    // Update
    public function update(&$rs, $where = "", $rsold = null, $curfilter = true)
    {
        // If no field is updated, execute may return 0. Treat as success
        $success = $this->updateSql($rs, $where, $curfilter)->execute();
        $success = ($success > 0) ? $success : true;
        return $success;
    }

    /**
     * DELETE statement
     *
     * @param array $rs Key values
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    protected function deleteSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->delete($this->UpdateTable);
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        if ($rs) {
            if (array_key_exists('id_kembali', $rs)) {
                AddFilter($where, QuotedName('id_kembali', $this->Dbid) . '=' . QuotedValue($rs['id_kembali'], $this->id_kembali->DataType, $this->Dbid));
            }
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        AddFilter($filter, $where);
        return $queryBuilder->where($filter != "" ? $filter : "0=1");
    }

    // Delete
    public function delete(&$rs, $where = "", $curfilter = false)
    {
        $success = true;
        if ($success) {
            $success = $this->deleteSql($rs, $where, $curfilter)->execute();
        }
        return $success;
    }

    // Load DbValue from recordset or array
    protected function loadDbValues($row)
    {
        if (!is_array($row)) {
            return;
        }
        $this->id_kembali->DbValue = $row['id_kembali'];
        $this->berita_peminjaman->DbValue = $row['berita_peminjaman'];
        $this->id_peminjaman->DbValue = $row['id_peminjaman'];
        $this->id_buku->DbValue = $row['id_buku'];
        $this->id_anggota->DbValue = $row['id_anggota'];
        $this->tgl_peminjaman->DbValue = $row['tgl_peminjaman'];
        $this->rencana_tgl_kembali->DbValue = $row['rencana_tgl_kembali'];
        $this->kondisi_buku_peminjaman->DbValue = $row['kondisi_buku_peminjaman'];
        $this->tgl_kembali->DbValue = $row['tgl_kembali'];
        $this->kondisi_buku_kembali->DbValue = $row['kondisi_buku_kembali'];
        $this->Lama_Kembali->DbValue = $row['Lama_Kembali'];
        $this->Lama_Pinjam->DbValue = $row['Lama_Pinjam'];
        $this->Terlambat->DbValue = $row['Terlambat'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`id_kembali` = @id_kembali@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->id_kembali->CurrentValue : $this->id_kembali->OldValue;
        if (EmptyValue($val)) {
            return "";
        } else {
            $keys[] = $val;
        }
        return implode(Config("COMPOSITE_KEY_SEPARATOR"), $keys);
    }

    // Set Key
    public function setKey($key, $current = false)
    {
        $this->OldKey = strval($key);
        $keys = explode(Config("COMPOSITE_KEY_SEPARATOR"), $this->OldKey);
        if (count($keys) == 1) {
            if ($current) {
                $this->id_kembali->CurrentValue = $keys[0];
            } else {
                $this->id_kembali->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('id_kembali', $row) ? $row['id_kembali'] : null;
        } else {
            $val = $this->id_kembali->OldValue !== null ? $this->id_kembali->OldValue : $this->id_kembali->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@id_kembali@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
        }
        return $keyFilter;
    }

    // Return page URL
    public function getReturnUrl()
    {
        $name = PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL");
        // Get referer URL automatically
        if (ReferUrl() != "" && ReferPageName() != CurrentPageName() && ReferPageName() != "login") { // Referer not same page or login page
            $_SESSION[$name] = ReferUrl(); // Save to Session
        }
        if (@$_SESSION[$name] != "") {
            return $_SESSION[$name];
        } else {
            return GetUrl("DatapengemalianbukuList");
        }
    }

    public function setReturnUrl($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL")] = $v;
    }

    // Get modal caption
    public function getModalCaption($pageName)
    {
        global $Language;
        if ($pageName == "DatapengemalianbukuView") {
            return $Language->phrase("View");
        } elseif ($pageName == "DatapengemalianbukuEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "DatapengemalianbukuAdd") {
            return $Language->phrase("Add");
        } else {
            return "";
        }
    }

    // API page name
    public function getApiPageName($action)
    {
        switch (strtolower($action)) {
            case Config("API_VIEW_ACTION"):
                return "DatapengemalianbukuView";
            case Config("API_ADD_ACTION"):
                return "DatapengemalianbukuAdd";
            case Config("API_EDIT_ACTION"):
                return "DatapengemalianbukuEdit";
            case Config("API_DELETE_ACTION"):
                return "DatapengemalianbukuDelete";
            case Config("API_LIST_ACTION"):
                return "DatapengemalianbukuList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "DatapengemalianbukuList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("DatapengemalianbukuView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("DatapengemalianbukuView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "DatapengemalianbukuAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "DatapengemalianbukuAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("DatapengemalianbukuEdit", $this->getUrlParm($parm));
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl(CurrentPageName(), $this->getUrlParm("action=edit"));
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        $url = $this->keyUrl("DatapengemalianbukuAdd", $this->getUrlParm($parm));
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl(CurrentPageName(), $this->getUrlParm("action=copy"));
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl()
    {
        return $this->keyUrl("DatapengemalianbukuDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "id_kembali:" . JsonEncode($this->id_kembali->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->id_kembali->CurrentValue !== null) {
            $url .= "/" . rawurlencode($this->id_kembali->CurrentValue);
        } else {
            return "javascript:ew.alert(ew.language.phrase('InvalidRecord'));";
        }
        if ($parm != "") {
            $url .= "?" . $parm;
        }
        return $url;
    }

    // Render sort
    public function renderSort($fld)
    {
        $classId = $fld->TableVar . "_" . $fld->Param;
        $scriptId = str_replace("%id%", $classId, "tpc_%id%");
        $scriptStart = $this->UseCustomTemplate ? "<template id=\"" . $scriptId . "\">" : "";
        $scriptEnd = $this->UseCustomTemplate ? "</template>" : "";
        $jsSort = " class=\"ew-pointer\" onclick=\"ew.sort(event, '" . $this->sortUrl($fld) . "', 2);\"";
        if ($this->sortUrl($fld) == "") {
            $html = <<<NOSORTHTML
{$scriptStart}<div class="ew-table-header-caption">{$fld->caption()}</div>{$scriptEnd}
NOSORTHTML;
        } else {
            if ($fld->getSort() == "ASC") {
                $sortIcon = '<i class="fas fa-sort-up"></i>';
            } elseif ($fld->getSort() == "DESC") {
                $sortIcon = '<i class="fas fa-sort-down"></i>';
            } else {
                $sortIcon = '';
            }
            $html = <<<SORTHTML
{$scriptStart}<div{$jsSort}><div class="ew-table-header-btn"><span class="ew-table-header-caption">{$fld->caption()}</span><span class="ew-table-header-sort">{$sortIcon}</span></div></div>{$scriptEnd}
SORTHTML;
        }
        return $html;
    }

    // Sort URL
    public function sortUrl($fld)
    {
        if (
            $this->CurrentAction || $this->isExport() ||
            in_array($fld->Type, [128, 204, 205])
        ) { // Unsortable data type
                return "";
        } elseif ($fld->Sortable) {
            $urlParm = $this->getUrlParm("order=" . urlencode($fld->Name) . "&amp;ordertype=" . $fld->getNextSort());
            return $this->addMasterUrl(CurrentPageName() . "?" . $urlParm);
        } else {
            return "";
        }
    }

    // Get record keys from Post/Get/Session
    public function getRecordKeys()
    {
        $arKeys = [];
        $arKey = [];
        if (Param("key_m") !== null) {
            $arKeys = Param("key_m");
            $cnt = count($arKeys);
        } else {
            if (($keyValue = Param("id_kembali") ?? Route("id_kembali")) !== null) {
                $arKeys[] = $keyValue;
            } elseif (IsApi() && (($keyValue = Key(0) ?? Route(2)) !== null)) {
                $arKeys[] = $keyValue;
            } else {
                $arKeys = null; // Do not setup
            }

            //return $arKeys; // Do not return yet, so the values will also be checked by the following code
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
                if (!is_numeric($key)) {
                    continue;
                }
                $ar[] = $key;
            }
        }
        return $ar;
    }

    // Get filter from record keys
    public function getFilterFromRecordKeys($setCurrent = true)
    {
        $arKeys = $this->getRecordKeys();
        $keyFilter = "";
        foreach ($arKeys as $key) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            if ($setCurrent) {
                $this->id_kembali->CurrentValue = $key;
            } else {
                $this->id_kembali->OldValue = $key;
            }
            $keyFilter .= "(" . $this->getRecordFilter() . ")";
        }
        return $keyFilter;
    }

    // Load recordset based on filter
    public function &loadRs($filter)
    {
        $sql = $this->getSql($filter); // Set up filter (WHERE Clause)
        $conn = $this->getConnection();
        $stmt = $conn->executeQuery($sql);
        return $stmt;
    }

    // Load row values from record
    public function loadListRowValues(&$rs)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            return;
        }
        $this->id_kembali->setDbValue($row['id_kembali']);
        $this->berita_peminjaman->setDbValue($row['berita_peminjaman']);
        $this->id_peminjaman->setDbValue($row['id_peminjaman']);
        $this->id_buku->setDbValue($row['id_buku']);
        $this->id_anggota->setDbValue($row['id_anggota']);
        $this->tgl_peminjaman->setDbValue($row['tgl_peminjaman']);
        $this->rencana_tgl_kembali->setDbValue($row['rencana_tgl_kembali']);
        $this->kondisi_buku_peminjaman->setDbValue($row['kondisi_buku_peminjaman']);
        $this->tgl_kembali->setDbValue($row['tgl_kembali']);
        $this->kondisi_buku_kembali->setDbValue($row['kondisi_buku_kembali']);
        $this->Lama_Kembali->setDbValue($row['Lama_Kembali']);
        $this->Lama_Pinjam->setDbValue($row['Lama_Pinjam']);
        $this->Terlambat->setDbValue($row['Terlambat']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // id_kembali

        // berita_peminjaman

        // id_peminjaman

        // id_buku

        // id_anggota

        // tgl_peminjaman

        // rencana_tgl_kembali

        // kondisi_buku_peminjaman

        // tgl_kembali

        // kondisi_buku_kembali

        // Lama_Kembali

        // Lama_Pinjam

        // Terlambat

        // id_kembali
        $this->id_kembali->ViewValue = $this->id_kembali->CurrentValue;
        $this->id_kembali->ViewCustomAttributes = "";

        // berita_peminjaman
        $this->berita_peminjaman->ViewValue = $this->berita_peminjaman->CurrentValue;
        $this->berita_peminjaman->ViewCustomAttributes = "";

        // id_peminjaman
        $this->id_peminjaman->ViewValue = $this->id_peminjaman->CurrentValue;
        $this->id_peminjaman->ViewValue = FormatNumber($this->id_peminjaman->ViewValue, 0, -2, -2, -2);
        $this->id_peminjaman->ViewCustomAttributes = "";

        // id_buku
        if ($this->id_buku->VirtualValue != "") {
            $this->id_buku->ViewValue = $this->id_buku->VirtualValue;
        } else {
            $curVal = strval($this->id_buku->CurrentValue);
            if ($curVal != "") {
                $this->id_buku->ViewValue = $this->id_buku->lookupCacheOption($curVal);
                if ($this->id_buku->ViewValue === null) { // Lookup from database
                    $filterWrk = "`id_buku`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->id_buku->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->id_buku->Lookup->renderViewRow($rswrk[0]);
                        $this->id_buku->ViewValue = $this->id_buku->displayValue($arwrk);
                    } else {
                        $this->id_buku->ViewValue = $this->id_buku->CurrentValue;
                    }
                }
            } else {
                $this->id_buku->ViewValue = null;
            }
        }
        $this->id_buku->ViewCustomAttributes = "";

        // id_anggota
        if ($this->id_anggota->VirtualValue != "") {
            $this->id_anggota->ViewValue = $this->id_anggota->VirtualValue;
        } else {
            $curVal = strval($this->id_anggota->CurrentValue);
            if ($curVal != "") {
                $this->id_anggota->ViewValue = $this->id_anggota->lookupCacheOption($curVal);
                if ($this->id_anggota->ViewValue === null) { // Lookup from database
                    $filterWrk = "`id_anggota`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->id_anggota->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->id_anggota->Lookup->renderViewRow($rswrk[0]);
                        $this->id_anggota->ViewValue = $this->id_anggota->displayValue($arwrk);
                    } else {
                        $this->id_anggota->ViewValue = $this->id_anggota->CurrentValue;
                    }
                }
            } else {
                $this->id_anggota->ViewValue = null;
            }
        }
        $this->id_anggota->ViewCustomAttributes = "";

        // tgl_peminjaman
        $this->tgl_peminjaman->ViewValue = $this->tgl_peminjaman->CurrentValue;
        $this->tgl_peminjaman->ViewValue = FormatDateTime($this->tgl_peminjaman->ViewValue, 0);
        $this->tgl_peminjaman->ViewCustomAttributes = "";

        // rencana_tgl_kembali
        $this->rencana_tgl_kembali->ViewValue = $this->rencana_tgl_kembali->CurrentValue;
        $this->rencana_tgl_kembali->ViewValue = FormatDateTime($this->rencana_tgl_kembali->ViewValue, 0);
        $this->rencana_tgl_kembali->ViewCustomAttributes = "";

        // kondisi_buku_peminjaman
        $this->kondisi_buku_peminjaman->ViewValue = $this->kondisi_buku_peminjaman->CurrentValue;
        $this->kondisi_buku_peminjaman->ViewCustomAttributes = "";

        // tgl_kembali
        $this->tgl_kembali->ViewValue = $this->tgl_kembali->CurrentValue;
        $this->tgl_kembali->ViewValue = FormatDateTime($this->tgl_kembali->ViewValue, 0);
        $this->tgl_kembali->ViewCustomAttributes = "";

        // kondisi_buku_kembali
        $this->kondisi_buku_kembali->ViewValue = $this->kondisi_buku_kembali->CurrentValue;
        $this->kondisi_buku_kembali->ViewCustomAttributes = "";

        // Lama_Kembali
        $this->Lama_Kembali->ViewValue = $this->Lama_Kembali->CurrentValue;
        $this->Lama_Kembali->ViewValue = FormatNumber($this->Lama_Kembali->ViewValue, 0, -2, -2, -2);
        $this->Lama_Kembali->ViewCustomAttributes = "";

        // Lama_Pinjam
        $this->Lama_Pinjam->ViewValue = $this->Lama_Pinjam->CurrentValue;
        $this->Lama_Pinjam->ViewValue = FormatNumber($this->Lama_Pinjam->ViewValue, 0, -2, -2, -2);
        $this->Lama_Pinjam->ViewCustomAttributes = "";

        // Terlambat
        $this->Terlambat->ViewValue = $this->Terlambat->CurrentValue;
        $this->Terlambat->ViewValue = FormatNumber($this->Terlambat->ViewValue, 0, -2, -2, -2);
        $this->Terlambat->ViewCustomAttributes = "";

        // id_kembali
        $this->id_kembali->LinkCustomAttributes = "";
        $this->id_kembali->HrefValue = "";
        $this->id_kembali->TooltipValue = "";

        // berita_peminjaman
        $this->berita_peminjaman->LinkCustomAttributes = "";
        $this->berita_peminjaman->HrefValue = "";
        $this->berita_peminjaman->TooltipValue = "";

        // id_peminjaman
        $this->id_peminjaman->LinkCustomAttributes = "";
        $this->id_peminjaman->HrefValue = "";
        $this->id_peminjaman->TooltipValue = "";

        // id_buku
        $this->id_buku->LinkCustomAttributes = "";
        $this->id_buku->HrefValue = "";
        $this->id_buku->TooltipValue = "";

        // id_anggota
        $this->id_anggota->LinkCustomAttributes = "";
        $this->id_anggota->HrefValue = "";
        $this->id_anggota->TooltipValue = "";

        // tgl_peminjaman
        $this->tgl_peminjaman->LinkCustomAttributes = "";
        $this->tgl_peminjaman->HrefValue = "";
        $this->tgl_peminjaman->TooltipValue = "";

        // rencana_tgl_kembali
        $this->rencana_tgl_kembali->LinkCustomAttributes = "";
        $this->rencana_tgl_kembali->HrefValue = "";
        $this->rencana_tgl_kembali->TooltipValue = "";

        // kondisi_buku_peminjaman
        $this->kondisi_buku_peminjaman->LinkCustomAttributes = "";
        $this->kondisi_buku_peminjaman->HrefValue = "";
        $this->kondisi_buku_peminjaman->TooltipValue = "";

        // tgl_kembali
        $this->tgl_kembali->LinkCustomAttributes = "";
        $this->tgl_kembali->HrefValue = "";
        $this->tgl_kembali->TooltipValue = "";

        // kondisi_buku_kembali
        $this->kondisi_buku_kembali->LinkCustomAttributes = "";
        $this->kondisi_buku_kembali->HrefValue = "";
        $this->kondisi_buku_kembali->TooltipValue = "";

        // Lama_Kembali
        $this->Lama_Kembali->LinkCustomAttributes = "";
        $this->Lama_Kembali->HrefValue = "";
        $this->Lama_Kembali->TooltipValue = "";

        // Lama_Pinjam
        $this->Lama_Pinjam->LinkCustomAttributes = "";
        $this->Lama_Pinjam->HrefValue = "";
        $this->Lama_Pinjam->TooltipValue = "";

        // Terlambat
        $this->Terlambat->LinkCustomAttributes = "";
        $this->Terlambat->HrefValue = "";
        $this->Terlambat->TooltipValue = "";

        // Call Row Rendered event
        $this->rowRendered();

        // Save data for Custom Template
        $this->Rows[] = $this->customTemplateFieldValues();
    }

    // Render edit row values
    public function renderEditRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // id_kembali
        $this->id_kembali->EditAttrs["class"] = "form-control";
        $this->id_kembali->EditCustomAttributes = "";
        $this->id_kembali->EditValue = $this->id_kembali->CurrentValue;
        $this->id_kembali->ViewCustomAttributes = "";

        // berita_peminjaman
        $this->berita_peminjaman->EditAttrs["class"] = "form-control";
        $this->berita_peminjaman->EditCustomAttributes = "";
        if (!$this->berita_peminjaman->Raw) {
            $this->berita_peminjaman->CurrentValue = HtmlDecode($this->berita_peminjaman->CurrentValue);
        }
        $this->berita_peminjaman->EditValue = $this->berita_peminjaman->CurrentValue;
        $this->berita_peminjaman->PlaceHolder = RemoveHtml($this->berita_peminjaman->caption());

        // id_peminjaman
        $this->id_peminjaman->EditAttrs["class"] = "form-control";
        $this->id_peminjaman->EditCustomAttributes = "";
        $this->id_peminjaman->EditValue = $this->id_peminjaman->CurrentValue;
        $this->id_peminjaman->PlaceHolder = RemoveHtml($this->id_peminjaman->caption());

        // id_buku
        $this->id_buku->EditAttrs["class"] = "form-control";
        $this->id_buku->EditCustomAttributes = "";
        $this->id_buku->PlaceHolder = RemoveHtml($this->id_buku->caption());

        // id_anggota
        $this->id_anggota->EditAttrs["class"] = "form-control";
        $this->id_anggota->EditCustomAttributes = "";
        if (!$Security->isAdmin() && $Security->isLoggedIn() && !$this->userIDAllow("info")) { // Non system admin
            $this->id_anggota->CurrentValue = CurrentUserID();
            if ($this->id_anggota->VirtualValue != "") {
                $this->id_anggota->EditValue = $this->id_anggota->VirtualValue;
            } else {
                $curVal = strval($this->id_anggota->CurrentValue);
                if ($curVal != "") {
                    $this->id_anggota->EditValue = $this->id_anggota->lookupCacheOption($curVal);
                    if ($this->id_anggota->EditValue === null) { // Lookup from database
                        $filterWrk = "`id_anggota`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->id_anggota->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->id_anggota->Lookup->renderViewRow($rswrk[0]);
                            $this->id_anggota->EditValue = $this->id_anggota->displayValue($arwrk);
                        } else {
                            $this->id_anggota->EditValue = $this->id_anggota->CurrentValue;
                        }
                    }
                } else {
                    $this->id_anggota->EditValue = null;
                }
            }
            $this->id_anggota->ViewCustomAttributes = "";
        } else {
            $this->id_anggota->PlaceHolder = RemoveHtml($this->id_anggota->caption());
        }

        // tgl_peminjaman

        // rencana_tgl_kembali
        $this->rencana_tgl_kembali->EditAttrs["class"] = "form-control";
        $this->rencana_tgl_kembali->EditCustomAttributes = "";
        $this->rencana_tgl_kembali->EditValue = FormatDateTime($this->rencana_tgl_kembali->CurrentValue, 8);
        $this->rencana_tgl_kembali->PlaceHolder = RemoveHtml($this->rencana_tgl_kembali->caption());

        // kondisi_buku_peminjaman
        $this->kondisi_buku_peminjaman->EditAttrs["class"] = "form-control";
        $this->kondisi_buku_peminjaman->EditCustomAttributes = "";
        if (!$this->kondisi_buku_peminjaman->Raw) {
            $this->kondisi_buku_peminjaman->CurrentValue = HtmlDecode($this->kondisi_buku_peminjaman->CurrentValue);
        }
        $this->kondisi_buku_peminjaman->EditValue = $this->kondisi_buku_peminjaman->CurrentValue;
        $this->kondisi_buku_peminjaman->PlaceHolder = RemoveHtml($this->kondisi_buku_peminjaman->caption());

        // tgl_kembali
        $this->tgl_kembali->EditAttrs["class"] = "form-control";
        $this->tgl_kembali->EditCustomAttributes = "";
        $this->tgl_kembali->EditValue = FormatDateTime($this->tgl_kembali->CurrentValue, 8);
        $this->tgl_kembali->PlaceHolder = RemoveHtml($this->tgl_kembali->caption());

        // kondisi_buku_kembali
        $this->kondisi_buku_kembali->EditAttrs["class"] = "form-control";
        $this->kondisi_buku_kembali->EditCustomAttributes = "";
        if (!$this->kondisi_buku_kembali->Raw) {
            $this->kondisi_buku_kembali->CurrentValue = HtmlDecode($this->kondisi_buku_kembali->CurrentValue);
        }
        $this->kondisi_buku_kembali->EditValue = $this->kondisi_buku_kembali->CurrentValue;
        $this->kondisi_buku_kembali->PlaceHolder = RemoveHtml($this->kondisi_buku_kembali->caption());

        // Lama_Kembali
        $this->Lama_Kembali->EditAttrs["class"] = "form-control";
        $this->Lama_Kembali->EditCustomAttributes = "";
        $this->Lama_Kembali->EditValue = $this->Lama_Kembali->CurrentValue;
        $this->Lama_Kembali->PlaceHolder = RemoveHtml($this->Lama_Kembali->caption());

        // Lama_Pinjam
        $this->Lama_Pinjam->EditAttrs["class"] = "form-control";
        $this->Lama_Pinjam->EditCustomAttributes = "";
        $this->Lama_Pinjam->EditValue = $this->Lama_Pinjam->CurrentValue;
        $this->Lama_Pinjam->PlaceHolder = RemoveHtml($this->Lama_Pinjam->caption());

        // Terlambat
        $this->Terlambat->EditAttrs["class"] = "form-control";
        $this->Terlambat->EditCustomAttributes = "";
        $this->Terlambat->EditValue = $this->Terlambat->CurrentValue;
        $this->Terlambat->PlaceHolder = RemoveHtml($this->Terlambat->caption());

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
        // Call Row Rendered event
        $this->rowRendered();
    }

    // Export data in HTML/CSV/Word/Excel/Email/PDF format
    public function exportDocument($doc, $recordset, $startRec = 1, $stopRec = 1, $exportPageType = "")
    {
        if (!$recordset || !$doc) {
            return;
        }
        if (!$doc->ExportCustom) {
            // Write header
            $doc->exportTableHeader();
            if ($doc->Horizontal) { // Horizontal format, write header
                $doc->beginExportRow();
                if ($exportPageType == "view") {
                    $doc->exportCaption($this->id_kembali);
                    $doc->exportCaption($this->berita_peminjaman);
                    $doc->exportCaption($this->id_peminjaman);
                    $doc->exportCaption($this->id_buku);
                    $doc->exportCaption($this->id_anggota);
                    $doc->exportCaption($this->tgl_peminjaman);
                    $doc->exportCaption($this->rencana_tgl_kembali);
                    $doc->exportCaption($this->kondisi_buku_peminjaman);
                    $doc->exportCaption($this->tgl_kembali);
                    $doc->exportCaption($this->kondisi_buku_kembali);
                    $doc->exportCaption($this->Lama_Kembali);
                    $doc->exportCaption($this->Lama_Pinjam);
                    $doc->exportCaption($this->Terlambat);
                } else {
                    $doc->exportCaption($this->id_kembali);
                    $doc->exportCaption($this->berita_peminjaman);
                    $doc->exportCaption($this->id_peminjaman);
                    $doc->exportCaption($this->id_buku);
                    $doc->exportCaption($this->id_anggota);
                    $doc->exportCaption($this->tgl_peminjaman);
                    $doc->exportCaption($this->rencana_tgl_kembali);
                    $doc->exportCaption($this->kondisi_buku_peminjaman);
                    $doc->exportCaption($this->tgl_kembali);
                    $doc->exportCaption($this->kondisi_buku_kembali);
                    $doc->exportCaption($this->Lama_Kembali);
                    $doc->exportCaption($this->Lama_Pinjam);
                    $doc->exportCaption($this->Terlambat);
                }
                $doc->endExportRow();
            }
        }

        // Move to first record
        $recCnt = $startRec - 1;
        $stopRec = ($stopRec > 0) ? $stopRec : PHP_INT_MAX;
        while (!$recordset->EOF && $recCnt < $stopRec) {
            $row = $recordset->fields;
            $recCnt++;
            if ($recCnt >= $startRec) {
                $rowCnt = $recCnt - $startRec + 1;

                // Page break
                if ($this->ExportPageBreakCount > 0) {
                    if ($rowCnt > 1 && ($rowCnt - 1) % $this->ExportPageBreakCount == 0) {
                        $doc->exportPageBreak();
                    }
                }
                $this->loadListRowValues($row);

                // Render row
                $this->RowType = ROWTYPE_VIEW; // Render view
                $this->resetAttributes();
                $this->renderListRow();
                if (!$doc->ExportCustom) {
                    $doc->beginExportRow($rowCnt); // Allow CSS styles if enabled
                    if ($exportPageType == "view") {
                        $doc->exportField($this->id_kembali);
                        $doc->exportField($this->berita_peminjaman);
                        $doc->exportField($this->id_peminjaman);
                        $doc->exportField($this->id_buku);
                        $doc->exportField($this->id_anggota);
                        $doc->exportField($this->tgl_peminjaman);
                        $doc->exportField($this->rencana_tgl_kembali);
                        $doc->exportField($this->kondisi_buku_peminjaman);
                        $doc->exportField($this->tgl_kembali);
                        $doc->exportField($this->kondisi_buku_kembali);
                        $doc->exportField($this->Lama_Kembali);
                        $doc->exportField($this->Lama_Pinjam);
                        $doc->exportField($this->Terlambat);
                    } else {
                        $doc->exportField($this->id_kembali);
                        $doc->exportField($this->berita_peminjaman);
                        $doc->exportField($this->id_peminjaman);
                        $doc->exportField($this->id_buku);
                        $doc->exportField($this->id_anggota);
                        $doc->exportField($this->tgl_peminjaman);
                        $doc->exportField($this->rencana_tgl_kembali);
                        $doc->exportField($this->kondisi_buku_peminjaman);
                        $doc->exportField($this->tgl_kembali);
                        $doc->exportField($this->kondisi_buku_kembali);
                        $doc->exportField($this->Lama_Kembali);
                        $doc->exportField($this->Lama_Pinjam);
                        $doc->exportField($this->Terlambat);
                    }
                    $doc->endExportRow($rowCnt);
                }
            }

            // Call Row Export server event
            if ($doc->ExportCustom) {
                $this->rowExport($row);
            }
            $recordset->moveNext();
        }
        if (!$doc->ExportCustom) {
            $doc->exportTableFooter();
        }
    }

    // Add User ID filter
    public function addUserIDFilter($filter = "")
    {
        global $Security;
        $filterWrk = "";
        $id = (CurrentPageID() == "list") ? $this->CurrentAction : CurrentPageID();
        if (!$this->userIDAllow($id) && !$Security->isAdmin()) {
            $filterWrk = $Security->userIdList();
            if ($filterWrk != "") {
                $filterWrk = '`id_anggota` IN (' . $filterWrk . ')';
            }
        }

        // Call User ID Filtering event
        $this->userIDFiltering($filterWrk);
        AddFilter($filter, $filterWrk);
        return $filter;
    }

    // User ID subquery
    public function getUserIDSubquery(&$fld, &$masterfld)
    {
        global $UserTable;
        $wrk = "";
        $sql = "SELECT " . $masterfld->Expression . " FROM `data-pengemalian-buku`";
        $filter = $this->addUserIDFilter("");
        if ($filter != "") {
            $sql .= " WHERE " . $filter;
        }

        // List all values
        if ($rs = Conn($UserTable->Dbid)->executeQuery($sql)->fetchAll(\PDO::FETCH_NUM)) {
            foreach ($rs as $row) {
                if ($wrk != "") {
                    $wrk .= ",";
                }
                $wrk .= QuotedValue($row[0], $masterfld->DataType, Config("USER_TABLE_DBID"));
            }
        }
        if ($wrk != "") {
            $wrk = $fld->Expression . " IN (" . $wrk . ")";
        } else { // No User ID value found
            $wrk = "0=1";
        }
        return $wrk;
    }

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        // No binary fields
        return false;
    }

    // Table level events

    // Recordset Selecting event
    public function recordsetSelecting(&$filter)
    {
        // Enter your code here
    }

    // Recordset Selected event
    public function recordsetSelected(&$rs)
    {
        //Log("Recordset Selected");
    }

    // Recordset Search Validated event
    public function recordsetSearchValidated()
    {
        // Example:
        //$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value
    }

    // Recordset Searching event
    public function recordsetSearching(&$filter)
    {
        // Enter your code here
    }

    // Row_Selecting event
    public function rowSelecting(&$filter)
    {
        // Enter your code here
    }

    // Row Selected event
    public function rowSelected(&$rs)
    {
        //Log("Row Selected");
    }

    // Row Inserting event
    public function rowInserting($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew)
    {
        //Log("Row Inserted");
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Updated event
    public function rowUpdated($rsold, &$rsnew)
    {
        //Log("Row Updated");
    }

    // Row Update Conflict event
    public function rowUpdateConflict($rsold, &$rsnew)
    {
        // Enter your code here
        // To ignore conflict, set return value to false
        return true;
    }

    // Grid Inserting event
    public function gridInserting()
    {
        // Enter your code here
        // To reject grid insert, set return value to false
        return true;
    }

    // Grid Inserted event
    public function gridInserted($rsnew)
    {
        //Log("Grid Inserted");
    }

    // Grid Updating event
    public function gridUpdating($rsold)
    {
        // Enter your code here
        // To reject grid update, set return value to false
        return true;
    }

    // Grid Updated event
    public function gridUpdated($rsold, $rsnew)
    {
        //Log("Grid Updated");
    }

    // Row Deleting event
    public function rowDeleting(&$rs)
    {
        // Enter your code here
        // To cancel, set return value to False
        return true;
    }

    // Row Deleted event
    public function rowDeleted(&$rs)
    {
        //Log("Row Deleted");
    }

    // Email Sending event
    public function emailSending($email, &$args)
    {
        //var_dump($email); var_dump($args); exit();
        return true;
    }

    // Lookup Selecting event
    public function lookupSelecting($fld, &$filter)
    {
        //var_dump($fld->Name, $fld->Lookup, $filter); // Uncomment to view the filter
        // Enter your code here
    }

    // Row Rendering event
    public function rowRendering()
    {
        // Enter your code here
    }

    // Row Rendered event
    public function rowRendered()
    {
        // To view properties of field class, use:
        //var_dump($this-><FieldName>);
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
