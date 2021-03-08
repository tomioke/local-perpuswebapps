<?php

namespace PHPMaker2021\perpusupdate;

use Doctrine\DBAL\ParameterType;

/**
 * Table class for buku
 */
class Buku extends DbTable
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
    public $cover;
    public $id_buku;
    public $nama_buku;
    public $pengarang;
    public $penerbit;
    public $kode_isbn;
    public $rangkuman;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'buku';
        $this->TableName = 'buku';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`buku`";
        $this->Dbid = 'DB';
        $this->ExportAll = true;
        $this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
        $this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
        $this->ExportPageSize = "a4"; // Page size (PDF only)
        $this->ExportExcelPageOrientation = \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_DEFAULT; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4; // Page size (PhpSpreadsheet only)
        $this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
        $this->ExportWordColumnWidth = null; // Cell width (PHPWord only)
        $this->DetailAdd = true; // Allow detail add
        $this->DetailEdit = true; // Allow detail edit
        $this->DetailView = true; // Allow detail view
        $this->ShowMultipleDetails = false; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this->TableVar);

        // cover
        $this->cover = new DbField('buku', 'buku', 'x_cover', 'cover', '`cover`', '`cover`', 200, 200, -1, true, '`cover`', false, false, false, 'IMAGE', 'FILE');
        $this->cover->Nullable = false; // NOT NULL field
        $this->cover->Required = true; // Required field
        $this->cover->Sortable = true; // Allow sort
        $this->cover->ImageResize = true;
        $this->cover->UploadAllowedFileExt = "jpg,jpeg,png,bmp,gif";
        $this->cover->UploadMaxFileSize = 999999999;
        $this->cover->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->cover->Param, "CustomMsg");
        $this->Fields['cover'] = &$this->cover;

        // id_buku
        $this->id_buku = new DbField('buku', 'buku', 'x_id_buku', 'id_buku', '`id_buku`', '`id_buku`', 3, 100, -1, false, '`id_buku`', false, false, false, 'FORMATTED TEXT', 'SELECT');
        $this->id_buku->IsAutoIncrement = true; // Autoincrement field
        $this->id_buku->IsPrimaryKey = true; // Primary key field
        $this->id_buku->Sortable = true; // Allow sort
        $this->id_buku->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->id_buku->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->id_buku->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->id_buku->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->id_buku->Param, "CustomMsg");
        $this->Fields['id_buku'] = &$this->id_buku;

        // nama_buku
        $this->nama_buku = new DbField('buku', 'buku', 'x_nama_buku', 'nama_buku', '`nama_buku`', '`nama_buku`', 200, 200, -1, false, '`nama_buku`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->nama_buku->Nullable = false; // NOT NULL field
        $this->nama_buku->Required = true; // Required field
        $this->nama_buku->Sortable = true; // Allow sort
        $this->nama_buku->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->nama_buku->Param, "CustomMsg");
        $this->Fields['nama_buku'] = &$this->nama_buku;

        // pengarang
        $this->pengarang = new DbField('buku', 'buku', 'x_pengarang', 'pengarang', '`pengarang`', '`pengarang`', 3, 100, -1, false, '`EV__pengarang`', true, true, true, 'FORMATTED TEXT', 'SELECT');
        $this->pengarang->IsForeignKey = true; // Foreign key field
        $this->pengarang->Nullable = false; // NOT NULL field
        $this->pengarang->Required = true; // Required field
        $this->pengarang->Sortable = true; // Allow sort
        $this->pengarang->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->pengarang->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->pengarang->Lookup = new Lookup('pengarang', 'pengarang', false, 'id_pengarang', ["nama_pengarang","","",""], [], [], [], [], [], [], '', '');
        $this->pengarang->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->pengarang->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->pengarang->Param, "CustomMsg");
        $this->Fields['pengarang'] = &$this->pengarang;

        // penerbit
        $this->penerbit = new DbField('buku', 'buku', 'x_penerbit', 'penerbit', '`penerbit`', '`penerbit`', 3, 100, -1, false, '`EV__penerbit`', true, true, true, 'FORMATTED TEXT', 'SELECT');
        $this->penerbit->IsForeignKey = true; // Foreign key field
        $this->penerbit->Nullable = false; // NOT NULL field
        $this->penerbit->Required = true; // Required field
        $this->penerbit->Sortable = true; // Allow sort
        $this->penerbit->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->penerbit->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->penerbit->Lookup = new Lookup('penerbit', 'penerbit', false, 'id_penerbit', ["nama_penerbit","","",""], [], [], [], [], [], [], '', '');
        $this->penerbit->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->penerbit->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->penerbit->Param, "CustomMsg");
        $this->Fields['penerbit'] = &$this->penerbit;

        // kode_isbn
        $this->kode_isbn = new DbField('buku', 'buku', 'x_kode_isbn', 'kode_isbn', '`kode_isbn`', '`kode_isbn`', 200, 200, -1, false, '`kode_isbn`', false, false, false, 'FORMATTED TEXT', 'TEXT');
        $this->kode_isbn->Nullable = false; // NOT NULL field
        $this->kode_isbn->Required = true; // Required field
        $this->kode_isbn->Sortable = true; // Allow sort
        $this->kode_isbn->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->kode_isbn->Param, "CustomMsg");
        $this->Fields['kode_isbn'] = &$this->kode_isbn;

        // rangkuman
        $this->rangkuman = new DbField('buku', 'buku', 'x_rangkuman', 'rangkuman', '`rangkuman`', '`rangkuman`', 201, 65535, -1, false, '`rangkuman`', false, false, false, 'FORMATTED TEXT', 'TEXTAREA');
        $this->rangkuman->Nullable = false; // NOT NULL field
        $this->rangkuman->Required = true; // Required field
        $this->rangkuman->Sortable = true; // Allow sort
        $this->rangkuman->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->rangkuman->Param, "CustomMsg");
        $this->Fields['rangkuman'] = &$this->rangkuman;
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
        return Session(PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_ORDER_BY_LIST"));
    }

    public function setSessionOrderByList($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_ORDER_BY_LIST")] = $v;
    }

    // Current master table name
    public function getCurrentMasterTable()
    {
        return Session(PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_MASTER_TABLE"));
    }

    public function setCurrentMasterTable($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_MASTER_TABLE")] = $v;
    }

    // Session master WHERE clause
    public function getMasterFilter()
    {
        // Master filter
        $masterFilter = "";
        if ($this->getCurrentMasterTable() == "penerbit") {
            if ($this->penerbit->getSessionValue() != "") {
                $masterFilter .= "" . GetForeignKeySql("`id_penerbit`", $this->penerbit->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        if ($this->getCurrentMasterTable() == "pengarang") {
            if ($this->pengarang->getSessionValue() != "") {
                $masterFilter .= "" . GetForeignKeySql("`id_pengarang`", $this->pengarang->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        return $masterFilter;
    }

    // Session detail WHERE clause
    public function getDetailFilter()
    {
        // Detail filter
        $detailFilter = "";
        if ($this->getCurrentMasterTable() == "penerbit") {
            if ($this->penerbit->getSessionValue() != "") {
                $detailFilter .= "" . GetForeignKeySql("`penerbit`", $this->penerbit->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        if ($this->getCurrentMasterTable() == "pengarang") {
            if ($this->pengarang->getSessionValue() != "") {
                $detailFilter .= "" . GetForeignKeySql("`pengarang`", $this->pengarang->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        return $detailFilter;
    }

    // Master filter
    public function sqlMasterFilter_penerbit()
    {
        return "`id_penerbit`=@id_penerbit@";
    }
    // Detail filter
    public function sqlDetailFilter_penerbit()
    {
        return "`penerbit`=@penerbit@";
    }

    // Master filter
    public function sqlMasterFilter_pengarang()
    {
        return "`id_pengarang`=@id_pengarang@";
    }
    // Detail filter
    public function sqlDetailFilter_pengarang()
    {
        return "`pengarang`=@pengarang@";
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`buku`";
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
        $from = "(SELECT *, (SELECT `nama_pengarang` FROM `pengarang` `TMP_LOOKUPTABLE` WHERE `TMP_LOOKUPTABLE`.`id_pengarang` = `buku`.`pengarang` LIMIT 1) AS `EV__pengarang`, (SELECT `nama_penerbit` FROM `penerbit` `TMP_LOOKUPTABLE` WHERE `TMP_LOOKUPTABLE`.`id_penerbit` = `buku`.`penerbit` LIMIT 1) AS `EV__penerbit` FROM `buku`)";
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
            $sqlwrk = clone $sql;
            $sqlwrk = $sqlwrk->resetQueryPart("orderBy")->getSQL();
        } else {
            $sqlwrk = $sql;
        }
        $pattern = '/^SELECT\s([\s\S]+)\sFROM\s/i';
        // Skip Custom View / SubQuery / SELECT DISTINCT / ORDER BY
        if (
            ($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') &&
            preg_match($pattern, $sqlwrk) && !preg_match('/\(\s*(SELECT[^)]+)\)/i', $sqlwrk) &&
            !preg_match('/^\s*select\s+distinct\s+/i', $sqlwrk) && !preg_match('/\s+order\s+by\s+/i', $sqlwrk)
        ) {
            $sqlwrk = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sqlwrk);
        } else {
            $sqlwrk = "SELECT COUNT(*) FROM (" . $sqlwrk . ") COUNT_TABLE";
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
            $this->pengarang->AdvancedSearch->SearchValue != "" ||
            $this->pengarang->AdvancedSearch->SearchValue2 != "" ||
            ContainsString($where, " " . $this->pengarang->VirtualExpression . " ")
        ) {
            return true;
        }
        if (ContainsString($orderBy, " " . $this->pengarang->VirtualExpression . " ")) {
            return true;
        }
        if (
            $this->penerbit->AdvancedSearch->SearchValue != "" ||
            $this->penerbit->AdvancedSearch->SearchValue2 != "" ||
            ContainsString($where, " " . $this->penerbit->VirtualExpression . " ")
        ) {
            return true;
        }
        if (ContainsString($orderBy, " " . $this->penerbit->VirtualExpression . " ")) {
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
            $this->id_buku->setDbValue($conn->lastInsertId());
            $rs['id_buku'] = $this->id_buku->DbValue;
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
            if (array_key_exists('id_buku', $rs)) {
                AddFilter($where, QuotedName('id_buku', $this->Dbid) . '=' . QuotedValue($rs['id_buku'], $this->id_buku->DataType, $this->Dbid));
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
        $this->cover->Upload->DbValue = $row['cover'];
        $this->id_buku->DbValue = $row['id_buku'];
        $this->nama_buku->DbValue = $row['nama_buku'];
        $this->pengarang->DbValue = $row['pengarang'];
        $this->penerbit->DbValue = $row['penerbit'];
        $this->kode_isbn->DbValue = $row['kode_isbn'];
        $this->rangkuman->DbValue = $row['rangkuman'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $oldFiles = EmptyValue($row['cover']) ? [] : [$row['cover']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->cover->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->cover->oldPhysicalUploadPath() . $oldFile);
            }
        }
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`id_buku` = @id_buku@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->id_buku->CurrentValue : $this->id_buku->OldValue;
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
                $this->id_buku->CurrentValue = $keys[0];
            } else {
                $this->id_buku->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('id_buku', $row) ? $row['id_buku'] : null;
        } else {
            $val = $this->id_buku->OldValue !== null ? $this->id_buku->OldValue : $this->id_buku->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@id_buku@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
        }
        return $keyFilter;
    }

    // Return page URL
    public function getReturnUrl()
    {
        $referUrl = ReferUrl();
        $referPageName = ReferPageName();
        $name = PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL");
        // Get referer URL automatically
        if ($referUrl != "" && $referPageName != CurrentPageName() && $referPageName != "login") { // Referer not same page or login page
            $_SESSION[$name] = $referUrl; // Save to Session
        }
        return $_SESSION[$name] ?? GetUrl("BukuList");
    }

    // Set return page URL
    public function setReturnUrl($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL")] = $v;
    }

    // Get modal caption
    public function getModalCaption($pageName)
    {
        global $Language;
        if ($pageName == "BukuView") {
            return $Language->phrase("View");
        } elseif ($pageName == "BukuEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "BukuAdd") {
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
                return "BukuView";
            case Config("API_ADD_ACTION"):
                return "BukuAdd";
            case Config("API_EDIT_ACTION"):
                return "BukuEdit";
            case Config("API_DELETE_ACTION"):
                return "BukuDelete";
            case Config("API_LIST_ACTION"):
                return "BukuList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "BukuList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("BukuView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("BukuView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "BukuAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "BukuAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("BukuEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("BukuAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("BukuDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        if ($this->getCurrentMasterTable() == "penerbit" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_id_penerbit", $this->penerbit->CurrentValue ?? $this->penerbit->getSessionValue());
        }
        if ($this->getCurrentMasterTable() == "pengarang" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_id_pengarang", $this->pengarang->CurrentValue ?? $this->pengarang->getSessionValue());
        }
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "id_buku:" . JsonEncode($this->id_buku->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->id_buku->CurrentValue !== null) {
            $url .= "/" . rawurlencode($this->id_buku->CurrentValue);
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
            if (($keyValue = Param("id_buku") ?? Route("id_buku")) !== null) {
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
                $this->id_buku->CurrentValue = $key;
            } else {
                $this->id_buku->OldValue = $key;
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
        $this->cover->Upload->DbValue = $row['cover'];
        $this->id_buku->setDbValue($row['id_buku']);
        $this->nama_buku->setDbValue($row['nama_buku']);
        $this->pengarang->setDbValue($row['pengarang']);
        $this->penerbit->setDbValue($row['penerbit']);
        $this->kode_isbn->setDbValue($row['kode_isbn']);
        $this->rangkuman->setDbValue($row['rangkuman']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // cover

        // id_buku

        // nama_buku

        // pengarang

        // penerbit

        // kode_isbn

        // rangkuman

        // cover
        if (!EmptyValue($this->cover->Upload->DbValue)) {
            $this->cover->ImageWidth = 150;
            $this->cover->ImageHeight = 250;
            $this->cover->ImageAlt = $this->cover->alt();
            $this->cover->ViewValue = $this->cover->Upload->DbValue;
        } else {
            $this->cover->ViewValue = "";
        }
        $this->cover->ViewCustomAttributes = "";

        // id_buku
        $this->id_buku->ViewCustomAttributes = "";

        // nama_buku
        $this->nama_buku->ViewValue = $this->nama_buku->CurrentValue;
        $this->nama_buku->ViewCustomAttributes = "";

        // pengarang
        if ($this->pengarang->VirtualValue != "") {
            $this->pengarang->ViewValue = $this->pengarang->VirtualValue;
        } else {
            $curVal = strval($this->pengarang->CurrentValue);
            if ($curVal != "") {
                $this->pengarang->ViewValue = $this->pengarang->lookupCacheOption($curVal);
                if ($this->pengarang->ViewValue === null) { // Lookup from database
                    $filterWrk = "`id_pengarang`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->pengarang->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->pengarang->Lookup->renderViewRow($rswrk[0]);
                        $this->pengarang->ViewValue = $this->pengarang->displayValue($arwrk);
                    } else {
                        $this->pengarang->ViewValue = $this->pengarang->CurrentValue;
                    }
                }
            } else {
                $this->pengarang->ViewValue = null;
            }
        }
        $this->pengarang->ViewCustomAttributes = "";

        // penerbit
        if ($this->penerbit->VirtualValue != "") {
            $this->penerbit->ViewValue = $this->penerbit->VirtualValue;
        } else {
            $curVal = strval($this->penerbit->CurrentValue);
            if ($curVal != "") {
                $this->penerbit->ViewValue = $this->penerbit->lookupCacheOption($curVal);
                if ($this->penerbit->ViewValue === null) { // Lookup from database
                    $filterWrk = "`id_penerbit`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->penerbit->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->penerbit->Lookup->renderViewRow($rswrk[0]);
                        $this->penerbit->ViewValue = $this->penerbit->displayValue($arwrk);
                    } else {
                        $this->penerbit->ViewValue = $this->penerbit->CurrentValue;
                    }
                }
            } else {
                $this->penerbit->ViewValue = null;
            }
        }
        $this->penerbit->ViewCustomAttributes = "";

        // kode_isbn
        $this->kode_isbn->ViewValue = $this->kode_isbn->CurrentValue;
        $this->kode_isbn->ViewCustomAttributes = "";

        // rangkuman
        $this->rangkuman->ViewValue = $this->rangkuman->CurrentValue;
        $this->rangkuman->ViewCustomAttributes = "";

        // cover
        $this->cover->LinkCustomAttributes = "";
        if (!EmptyValue($this->cover->Upload->DbValue)) {
            $this->cover->HrefValue = GetFileUploadUrl($this->cover, $this->cover->htmlDecode($this->cover->Upload->DbValue)); // Add prefix/suffix
            $this->cover->LinkAttrs["target"] = ""; // Add target
            if ($this->isExport()) {
                $this->cover->HrefValue = FullUrl($this->cover->HrefValue, "href");
            }
        } else {
            $this->cover->HrefValue = "";
        }
        $this->cover->ExportHrefValue = $this->cover->UploadPath . $this->cover->Upload->DbValue;
        $this->cover->TooltipValue = "";
        if ($this->cover->UseColorbox) {
            if (EmptyValue($this->cover->TooltipValue)) {
                $this->cover->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
            }
            $this->cover->LinkAttrs["data-rel"] = "buku_x_cover";
            $this->cover->LinkAttrs->appendClass("ew-lightbox");
        }

        // id_buku
        $this->id_buku->LinkCustomAttributes = "";
        $this->id_buku->HrefValue = "";
        $this->id_buku->TooltipValue = "";

        // nama_buku
        $this->nama_buku->LinkCustomAttributes = "";
        $this->nama_buku->HrefValue = "";
        $this->nama_buku->TooltipValue = "";

        // pengarang
        $this->pengarang->LinkCustomAttributes = "";
        $this->pengarang->HrefValue = "";
        $this->pengarang->TooltipValue = "";

        // penerbit
        $this->penerbit->LinkCustomAttributes = "";
        $this->penerbit->HrefValue = "";
        $this->penerbit->TooltipValue = "";

        // kode_isbn
        $this->kode_isbn->LinkCustomAttributes = "";
        $this->kode_isbn->HrefValue = "";
        $this->kode_isbn->TooltipValue = "";

        // rangkuman
        $this->rangkuman->LinkCustomAttributes = "";
        $this->rangkuman->HrefValue = "";
        $this->rangkuman->TooltipValue = "";

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

        // cover
        $this->cover->EditAttrs["class"] = "form-control";
        $this->cover->EditCustomAttributes = "";
        if (!EmptyValue($this->cover->Upload->DbValue)) {
            $this->cover->ImageWidth = 150;
            $this->cover->ImageHeight = 250;
            $this->cover->ImageAlt = $this->cover->alt();
            $this->cover->EditValue = $this->cover->Upload->DbValue;
        } else {
            $this->cover->EditValue = "";
        }
        if (!EmptyValue($this->cover->CurrentValue)) {
            $this->cover->Upload->FileName = $this->cover->CurrentValue;
        }

        // id_buku
        $this->id_buku->EditAttrs["class"] = "form-control";
        $this->id_buku->EditCustomAttributes = "";
        $this->id_buku->ViewCustomAttributes = "";

        // nama_buku
        $this->nama_buku->EditAttrs["class"] = "form-control";
        $this->nama_buku->EditCustomAttributes = "";
        if (!$this->nama_buku->Raw) {
            $this->nama_buku->CurrentValue = HtmlDecode($this->nama_buku->CurrentValue);
        }
        $this->nama_buku->EditValue = $this->nama_buku->CurrentValue;
        $this->nama_buku->PlaceHolder = RemoveHtml($this->nama_buku->caption());

        // pengarang
        $this->pengarang->EditAttrs["class"] = "form-control";
        $this->pengarang->EditCustomAttributes = "";
        if ($this->pengarang->getSessionValue() != "") {
            $this->pengarang->CurrentValue = GetForeignKeyValue($this->pengarang->getSessionValue());
            if ($this->pengarang->VirtualValue != "") {
                $this->pengarang->ViewValue = $this->pengarang->VirtualValue;
            } else {
                $curVal = strval($this->pengarang->CurrentValue);
                if ($curVal != "") {
                    $this->pengarang->ViewValue = $this->pengarang->lookupCacheOption($curVal);
                    if ($this->pengarang->ViewValue === null) { // Lookup from database
                        $filterWrk = "`id_pengarang`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->pengarang->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->pengarang->Lookup->renderViewRow($rswrk[0]);
                            $this->pengarang->ViewValue = $this->pengarang->displayValue($arwrk);
                        } else {
                            $this->pengarang->ViewValue = $this->pengarang->CurrentValue;
                        }
                    }
                } else {
                    $this->pengarang->ViewValue = null;
                }
            }
            $this->pengarang->ViewCustomAttributes = "";
        } else {
            $this->pengarang->PlaceHolder = RemoveHtml($this->pengarang->caption());
        }

        // penerbit
        $this->penerbit->EditAttrs["class"] = "form-control";
        $this->penerbit->EditCustomAttributes = "";
        if ($this->penerbit->getSessionValue() != "") {
            $this->penerbit->CurrentValue = GetForeignKeyValue($this->penerbit->getSessionValue());
            if ($this->penerbit->VirtualValue != "") {
                $this->penerbit->ViewValue = $this->penerbit->VirtualValue;
            } else {
                $curVal = strval($this->penerbit->CurrentValue);
                if ($curVal != "") {
                    $this->penerbit->ViewValue = $this->penerbit->lookupCacheOption($curVal);
                    if ($this->penerbit->ViewValue === null) { // Lookup from database
                        $filterWrk = "`id_penerbit`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->penerbit->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->penerbit->Lookup->renderViewRow($rswrk[0]);
                            $this->penerbit->ViewValue = $this->penerbit->displayValue($arwrk);
                        } else {
                            $this->penerbit->ViewValue = $this->penerbit->CurrentValue;
                        }
                    }
                } else {
                    $this->penerbit->ViewValue = null;
                }
            }
            $this->penerbit->ViewCustomAttributes = "";
        } else {
            $this->penerbit->PlaceHolder = RemoveHtml($this->penerbit->caption());
        }

        // kode_isbn
        $this->kode_isbn->EditAttrs["class"] = "form-control";
        $this->kode_isbn->EditCustomAttributes = "";
        if (!$this->kode_isbn->Raw) {
            $this->kode_isbn->CurrentValue = HtmlDecode($this->kode_isbn->CurrentValue);
        }
        $this->kode_isbn->EditValue = $this->kode_isbn->CurrentValue;
        $this->kode_isbn->PlaceHolder = RemoveHtml($this->kode_isbn->caption());

        // rangkuman
        $this->rangkuman->EditAttrs["class"] = "form-control";
        $this->rangkuman->EditCustomAttributes = "";
        $this->rangkuman->EditValue = $this->rangkuman->CurrentValue;
        $this->rangkuman->PlaceHolder = RemoveHtml($this->rangkuman->caption());

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
                    $doc->exportCaption($this->cover);
                    $doc->exportCaption($this->id_buku);
                    $doc->exportCaption($this->nama_buku);
                    $doc->exportCaption($this->pengarang);
                    $doc->exportCaption($this->penerbit);
                    $doc->exportCaption($this->kode_isbn);
                    $doc->exportCaption($this->rangkuman);
                } else {
                    $doc->exportCaption($this->cover);
                    $doc->exportCaption($this->id_buku);
                    $doc->exportCaption($this->nama_buku);
                    $doc->exportCaption($this->pengarang);
                    $doc->exportCaption($this->penerbit);
                    $doc->exportCaption($this->kode_isbn);
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
                        $doc->exportField($this->cover);
                        $doc->exportField($this->id_buku);
                        $doc->exportField($this->nama_buku);
                        $doc->exportField($this->pengarang);
                        $doc->exportField($this->penerbit);
                        $doc->exportField($this->kode_isbn);
                        $doc->exportField($this->rangkuman);
                    } else {
                        $doc->exportField($this->cover);
                        $doc->exportField($this->id_buku);
                        $doc->exportField($this->nama_buku);
                        $doc->exportField($this->pengarang);
                        $doc->exportField($this->penerbit);
                        $doc->exportField($this->kode_isbn);
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

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        $width = ($width > 0) ? $width : Config("THUMBNAIL_DEFAULT_WIDTH");
        $height = ($height > 0) ? $height : Config("THUMBNAIL_DEFAULT_HEIGHT");

        // Set up field name / file name field / file type field
        $fldName = "";
        $fileNameFld = "";
        $fileTypeFld = "";
        if ($fldparm == 'cover') {
            $fldName = "cover";
            $fileNameFld = "cover";
        } else {
            return false; // Incorrect field
        }

        // Set up key values
        $ar = explode(Config("COMPOSITE_KEY_SEPARATOR"), $key);
        if (count($ar) == 1) {
            $this->id_buku->CurrentValue = $ar[0];
        } else {
            return false; // Incorrect key
        }

        // Set up filter (WHERE Clause)
        $filter = $this->getRecordFilter();
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $dbtype = GetConnectionType($this->Dbid);
        if ($row = $conn->fetchAssoc($sql)) {
            $val = $row[$fldName];
            if (!EmptyValue($val)) {
                $fld = $this->Fields[$fldName];

                // Binary data
                if ($fld->DataType == DATATYPE_BLOB) {
                    if ($dbtype != "MYSQL") {
                        if (is_resource($val) && get_resource_type($val) == "stream") { // Byte array
                            $val = stream_get_contents($val);
                        }
                    }
                    if ($resize) {
                        ResizeBinary($val, $width, $height, 100, $plugins);
                    }

                    // Write file type
                    if ($fileTypeFld != "" && !EmptyValue($row[$fileTypeFld])) {
                        AddHeader("Content-type", $row[$fileTypeFld]);
                    } else {
                        AddHeader("Content-type", ContentType($val));
                    }

                    // Write file name
                    $downloadPdf = !Config("EMBED_PDF") && Config("DOWNLOAD_PDF_FILE");
                    if ($fileNameFld != "" && !EmptyValue($row[$fileNameFld])) {
                        $fileName = $row[$fileNameFld];
                        $pathinfo = pathinfo($fileName);
                        $ext = strtolower(@$pathinfo["extension"]);
                        $isPdf = SameText($ext, "pdf");
                        if ($downloadPdf || !$isPdf) { // Skip header if not download PDF
                            AddHeader("Content-Disposition", "attachment; filename=\"" . $fileName . "\"");
                        }
                    } else {
                        $ext = ContentExtension($val);
                        $isPdf = SameText($ext, ".pdf");
                        if ($isPdf && $downloadPdf) { // Add header if download PDF
                            AddHeader("Content-Disposition", "attachment; filename=\"" . $fileName . "\"");
                        }
                    }

                    // Write file data
                    if (
                        StartsString("PK", $val) &&
                        ContainsString($val, "[Content_Types].xml") &&
                        ContainsString($val, "_rels") &&
                        ContainsString($val, "docProps")
                    ) { // Fix Office 2007 documents
                        if (!EndsString("\0\0\0", $val)) { // Not ends with 3 or 4 \0
                            $val .= "\0\0\0\0";
                        }
                    }

                    // Clear any debug message
                    if (ob_get_length()) {
                        ob_end_clean();
                    }

                    // Write binary data
                    Write($val);

                // Upload to folder
                } else {
                    if ($fld->UploadMultiple) {
                        $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                    } else {
                        $files = [$val];
                    }
                    $data = [];
                    $ar = [];
                    foreach ($files as $file) {
                        if (!EmptyValue($file)) {
                            if (Config("ENCRYPT_FILE_PATH")) {
                                $ar[$file] = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $this->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                            } else {
                                $ar[$file] = FullUrl($fld->hrefPath() . $file);
                            }
                        }
                    }
                    $data[$fld->Param] = $ar;
                    WriteJson($data);
                }
            }
            return true;
        }
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
