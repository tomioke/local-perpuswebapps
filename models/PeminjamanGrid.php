<?php

namespace PHPMaker2021\perpus;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class PeminjamanGrid extends Peminjaman
{
    use MessagesTrait;

    // Page ID
    public $PageID = "grid";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'peminjaman';

    // Page object name
    public $PageObjName = "PeminjamanGrid";

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "fpeminjamangrid";
    public $FormActionName = "k_action";
    public $FormBlankRowName = "k_blankrow";
    public $FormKeyCountName = "key_count";

    // Page URLs
    public $AddUrl;
    public $EditUrl;
    public $CopyUrl;
    public $DeleteUrl;
    public $ViewUrl;
    public $ListUrl;

    // Page headings
    public $Heading = "";
    public $Subheading = "";
    public $PageHeader;
    public $PageFooter;

    // Page terminated
    private $terminated = false;

    // Page heading
    public function pageHeading()
    {
        global $Language;
        if ($this->Heading != "") {
            return $this->Heading;
        }
        if (method_exists($this, "tableCaption")) {
            return $this->tableCaption();
        }
        return "";
    }

    // Page subheading
    public function pageSubheading()
    {
        global $Language;
        if ($this->Subheading != "") {
            return $this->Subheading;
        }
        if ($this->TableName) {
            return $Language->phrase($this->PageID);
        }
        return "";
    }

    // Page name
    public function pageName()
    {
        return CurrentPageName();
    }

    // Page URL
    public function pageUrl()
    {
        $url = ScriptName() . "?";
        if ($this->UseTokenInUrl) {
            $url .= "t=" . $this->TableVar . "&"; // Add page token
        }
        return $url;
    }

    // Show Page Header
    public function showPageHeader()
    {
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        if ($header != "") { // Header exists, display
            echo '<p id="ew-page-header">' . $header . '</p>';
        }
    }

    // Show Page Footer
    public function showPageFooter()
    {
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        if ($footer != "") { // Footer exists, display
            echo '<p id="ew-page-footer">' . $footer . '</p>';
        }
    }

    // Validate page request
    protected function isPageRequest()
    {
        global $CurrentForm;
        if ($this->UseTokenInUrl) {
            if ($CurrentForm) {
                return ($this->TableVar == $CurrentForm->getValue("t"));
            }
            if (Get("t") !== null) {
                return ($this->TableVar == Get("t"));
            }
        }
        return true;
    }

    // Constructor
    public function __construct()
    {
        global $Language, $DashboardReport, $DebugTimer;
        global $UserTable;

        // Initialize
        $this->FormActionName .= "_" . $this->FormName;
        $this->OldKeyName .= "_" . $this->FormName;
        $this->FormBlankRowName .= "_" . $this->FormName;
        $this->FormKeyCountName .= "_" . $this->FormName;
        $GLOBALS["Grid"] = &$this;
        $this->TokenTimeout = SessionTimeoutTime();

        // Language object
        $Language = Container("language");

        // Parent constuctor
        parent::__construct();

        // Table object (peminjaman)
        if (!isset($GLOBALS["peminjaman"]) || get_class($GLOBALS["peminjaman"]) == PROJECT_NAMESPACE . "peminjaman") {
            $GLOBALS["peminjaman"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();
        $this->AddUrl = "PeminjamanAdd";

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'peminjaman');
        }

        // Start timer
        $DebugTimer = Container("timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] = $GLOBALS["Conn"] ?? $this->getConnection();

        // User table object
        $UserTable = Container("usertable");

        // List options
        $this->ListOptions = new ListOptions();
        $this->ListOptions->TableVar = $this->TableVar;

        // Other options
        if (!$this->OtherOptions) {
            $this->OtherOptions = new ListOptionsArray();
        }
        $this->OtherOptions["addedit"] = new ListOptions("div");
        $this->OtherOptions["addedit"]->TagClassName = "ew-add-edit-option";
    }

    // Get content from stream
    public function getContents($stream = null): string
    {
        global $Response;
        return is_object($Response) ? $Response->getBody() : ob_get_clean();
    }

    // Is terminated
    public function isTerminated()
    {
        return $this->terminated;
    }

    /**
     * Terminate page
     *
     * @param string $url URL for direction
     * @return void
     */
    public function terminate($url = "")
    {
        if ($this->terminated) {
            return;
        }
        global $ExportFileName, $TempImages, $DashboardReport;

        // Page is terminated
        $this->terminated = true;

        // Export
        if ($this->CustomExport && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, Config("EXPORT_CLASSES"))) {
            $content = $this->getContents();
            if ($ExportFileName == "") {
                $ExportFileName = $this->TableVar;
            }
            $class = PROJECT_NAMESPACE . Config("EXPORT_CLASSES." . $this->CustomExport);
            if (class_exists($class)) {
                $doc = new $class(Container("peminjaman"));
                $doc->Text = @$content;
                if ($this->isExport("email")) {
                    echo $this->exportEmail($doc->Text);
                } else {
                    $doc->export();
                }
                DeleteTempImages(); // Delete temp images
                return;
            }
        }
        unset($GLOBALS["Grid"]);
        if ($url === "") {
            return;
        }
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Return for API
        if (IsApi()) {
            $res = $url === true;
            if (!$res) { // Show error
                WriteJson(array_merge(["success" => false], $this->getMessages()));
            }
            return;
        }

        // Go to URL if specified
        if ($url != "") {
            if (!Config("DEBUG") && ob_get_length()) {
                ob_end_clean();
            }
            SaveDebugMessage();
            Redirect(GetUrl($url));
        }
        return; // Return to controller
    }

    // Get records from recordset
    protected function getRecordsFromRecordset($rs, $current = false)
    {
        $rows = [];
        if (is_object($rs)) { // Recordset
            while ($rs && !$rs->EOF) {
                $this->loadRowValues($rs); // Set up DbValue/CurrentValue
                $row = $this->getRecordFromArray($rs->fields);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
                $rs->moveNext();
            }
        } elseif (is_array($rs)) {
            foreach ($rs as $ar) {
                $row = $this->getRecordFromArray($ar);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        }
        return $rows;
    }

    // Get record from array
    protected function getRecordFromArray($ar)
    {
        $row = [];
        if (is_array($ar)) {
            foreach ($ar as $fldname => $val) {
                if (array_key_exists($fldname, $this->Fields) && ($this->Fields[$fldname]->Visible || $this->Fields[$fldname]->IsPrimaryKey)) { // Primary key or Visible
                    $fld = &$this->Fields[$fldname];
                    if ($fld->HtmlTag == "FILE") { // Upload field
                        if (EmptyValue($val)) {
                            $row[$fldname] = null;
                        } else {
                            if ($fld->DataType == DATATYPE_BLOB) {
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . $fld->Param . "/" . rawurlencode($this->getRecordKeyValue($ar))));
                                $row[$fldname] = ["type" => ContentType($val), "url" => $url, "name" => $fld->Param . ContentExtension($val)];
                            } elseif (!$fld->UploadMultiple || !ContainsString($val, Config("MULTIPLE_UPLOAD_SEPARATOR"))) { // Single file
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $val)));
                                $row[$fldname] = ["type" => MimeContentType($val), "url" => $url, "name" => $val];
                            } else { // Multiple files
                                $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                                $ar = [];
                                foreach ($files as $file) {
                                    $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                        "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                                    if (!EmptyValue($file)) {
                                        $ar[] = ["type" => MimeContentType($file), "url" => $url, "name" => $file];
                                    }
                                }
                                $row[$fldname] = $ar;
                            }
                        }
                    } else {
                        $row[$fldname] = $val;
                    }
                }
            }
        }
        return $row;
    }

    // Get record key value from array
    protected function getRecordKeyValue($ar)
    {
        $key = "";
        if (is_array($ar)) {
            $key .= @$ar['id_peminjaman'];
        }
        return $key;
    }

    /**
     * Hide fields for add/edit
     *
     * @return void
     */
    protected function hideFieldsForAddEdit()
    {
        if ($this->isAdd() || $this->isCopy() || $this->isGridAdd()) {
            $this->id_peminjaman->Visible = false;
        }
        if ($this->isAddOrEdit()) {
            $this->tgl_peminjaman->Visible = false;
        }
    }

    // Lookup data
    public function lookup()
    {
        global $Language, $Security;

        // Get lookup object
        $fieldName = Post("field");
        $lookup = $this->Fields[$fieldName]->Lookup;

        // Get lookup parameters
        $lookupType = Post("ajax", "unknown");
        $pageSize = -1;
        $offset = -1;
        $searchValue = "";
        if (SameText($lookupType, "modal")) {
            $searchValue = Post("sv", "");
            $pageSize = Post("recperpage", 10);
            $offset = Post("start", 0);
        } elseif (SameText($lookupType, "autosuggest")) {
            $searchValue = Param("q", "");
            $pageSize = Param("n", -1);
            $pageSize = is_numeric($pageSize) ? (int)$pageSize : -1;
            if ($pageSize <= 0) {
                $pageSize = Config("AUTO_SUGGEST_MAX_ENTRIES");
            }
            $start = Param("start", -1);
            $start = is_numeric($start) ? (int)$start : -1;
            $page = Param("page", -1);
            $page = is_numeric($page) ? (int)$page : -1;
            $offset = $start >= 0 ? $start : ($page > 0 && $pageSize > 0 ? ($page - 1) * $pageSize : 0);
        }
        $userSelect = Decrypt(Post("s", ""));
        $userFilter = Decrypt(Post("f", ""));
        $userOrderBy = Decrypt(Post("o", ""));
        $keys = Post("keys");
        $lookup->LookupType = $lookupType; // Lookup type
        if ($keys !== null) { // Selected records from modal
            if (is_array($keys)) {
                $keys = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $keys);
            }
            $lookup->FilterFields = []; // Skip parent fields if any
            $lookup->FilterValues[] = $keys; // Lookup values
            $pageSize = -1; // Show all records
        } else { // Lookup values
            $lookup->FilterValues[] = Post("v0", Post("lookupValue", ""));
        }
        $cnt = is_array($lookup->FilterFields) ? count($lookup->FilterFields) : 0;
        for ($i = 1; $i <= $cnt; $i++) {
            $lookup->FilterValues[] = Post("v" . $i, "");
        }
        $lookup->SearchValue = $searchValue;
        $lookup->PageSize = $pageSize;
        $lookup->Offset = $offset;
        if ($userSelect != "") {
            $lookup->UserSelect = $userSelect;
        }
        if ($userFilter != "") {
            $lookup->UserFilter = $userFilter;
        }
        if ($userOrderBy != "") {
            $lookup->UserOrderBy = $userOrderBy;
        }
        $lookup->toJson($this); // Use settings from current page
    }

    // Class variables
    public $ListOptions; // List options
    public $ExportOptions; // Export options
    public $SearchOptions; // Search options
    public $OtherOptions; // Other options
    public $FilterOptions; // Filter options
    public $ImportOptions; // Import options
    public $ListActions; // List actions
    public $SelectedCount = 0;
    public $SelectedIndex = 0;
    public $ShowOtherOptions = false;
    public $DisplayRecords = 20;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $PageSizes = "20,40,60,-1"; // Page sizes (comma separated)
    public $DefaultSearchWhere = ""; // Default search WHERE clause
    public $SearchWhere = ""; // Search WHERE clause
    public $SearchPanelClass = "ew-search-panel collapse show"; // Search Panel class
    public $SearchRowCount = 0; // For extended search
    public $SearchColumnCount = 0; // For extended search
    public $SearchFieldsPerRow = 1; // For extended search
    public $RecordCount = 0; // Record count
    public $EditRowCount;
    public $StartRowCount = 1;
    public $RowCount = 0;
    public $Attrs = []; // Row attributes and cell attributes
    public $RowIndex = 0; // Row index
    public $KeyCount = 0; // Key count
    public $RowAction = ""; // Row action
    public $MultiColumnClass = "col-sm";
    public $MultiColumnEditClass = "w-100";
    public $DbMasterFilter = ""; // Master filter
    public $DbDetailFilter = ""; // Detail filter
    public $MasterRecordExists;
    public $MultiSelectKey;
    public $Command;
    public $RestoreSearch = false;
            public $pengembalian_Count;
    public $DetailPages;
    public $OldRecordset;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm;

        // Get grid add count
        $gridaddcnt = Get(Config("TABLE_GRID_ADD_ROW_COUNT"), "");
        if (is_numeric($gridaddcnt) && $gridaddcnt > 0) {
            $this->GridAddRowCount = $gridaddcnt;
        }

        // Set up list options
        $this->setupListOptions();
        $this->id_peminjaman->setVisibility();
        $this->berita_peminjaman->setVisibility();
        $this->id_buku->setVisibility();
        $this->id_anggota->setVisibility();
        $this->tgl_peminjaman->setVisibility();
        $this->rencana_tgl_kembali->setVisibility();
        $this->kondisi_buku_peminjaman->setVisibility();
        $this->hideFieldsForAddEdit();

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Set up master detail parameters
        $this->setupMasterParms();

        // Setup other options
        $this->setupOtherOptions();

        // Set up lookup cache
        $this->setupLookupOptions($this->id_buku);
        $this->setupLookupOptions($this->id_anggota);

        // Search filters
        $srchAdvanced = ""; // Advanced search filter
        $srchBasic = ""; // Basic search filter
        $filter = "";

        // Get command
        $this->Command = strtolower(Get("cmd"));
        if ($this->isPageRequest()) {
            // Set up records per page
            $this->setupDisplayRecords();

            // Handle reset command
            $this->resetCmd();

            // Hide list options
            if ($this->isExport()) {
                $this->ListOptions->hideAllOptions(["sequence"]);
                $this->ListOptions->UseDropDownButton = false; // Disable drop down button
                $this->ListOptions->UseButtonGroup = false; // Disable button group
            } elseif ($this->isGridAdd() || $this->isGridEdit()) {
                $this->ListOptions->hideAllOptions();
                $this->ListOptions->UseDropDownButton = false; // Disable drop down button
                $this->ListOptions->UseButtonGroup = false; // Disable button group
            }

            // Show grid delete link for grid add / grid edit
            if ($this->AllowAddDeleteRow) {
                if ($this->isGridAdd() || $this->isGridEdit()) {
                    $item = $this->ListOptions["griddelete"];
                    if ($item) {
                        $item->Visible = true;
                    }
                }
            }

            // Set up sorting order
            $this->setupSortOrder();
        }

        // Restore display records
        if ($this->Command != "json" && $this->getRecordsPerPage() != "") {
            $this->DisplayRecords = $this->getRecordsPerPage(); // Restore from Session
        } else {
            $this->DisplayRecords = 20; // Load default
            $this->setRecordsPerPage($this->DisplayRecords); // Save default to Session
        }

        // Load Sorting Order
        if ($this->Command != "json") {
            $this->loadSortOrder();
        }

        // Build filter
        $filter = "";
        if (!$Security->canList()) {
            $filter = "(0=1)"; // Filter all records
        }

        // Restore master/detail filter
        $this->DbMasterFilter = $this->getMasterFilter(); // Restore master filter
        $this->DbDetailFilter = $this->getDetailFilter(); // Restore detail filter
        AddFilter($filter, $this->DbDetailFilter);
        AddFilter($filter, $this->SearchWhere);

        // Load master record
        if ($this->CurrentMode != "add" && $this->getMasterFilter() != "" && $this->getCurrentMasterTable() == "anggota") {
            $masterTbl = Container("anggota");
            $rsmaster = $masterTbl->loadRs($this->DbMasterFilter)->fetch(\PDO::FETCH_ASSOC);
            $this->MasterRecordExists = $rsmaster !== false;
            if (!$this->MasterRecordExists) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record found
                $this->terminate("AnggotaList"); // Return to master page
                return;
            } else {
                $masterTbl->loadListRowValues($rsmaster);
                $masterTbl->RowType = ROWTYPE_MASTER; // Master row
                $masterTbl->renderListRow();
            }
        }

        // Set up filter
        if ($this->Command == "json") {
            $this->UseSessionForListSql = false; // Do not use session for ListSQL
            $this->CurrentFilter = $filter;
        } else {
            $this->setSessionWhere($filter);
            $this->CurrentFilter = "";
        }
        if ($this->isGridAdd()) {
            if ($this->CurrentMode == "copy") {
                $this->TotalRecords = $this->listRecordCount();
                $this->StartRecord = 1;
                $this->DisplayRecords = $this->TotalRecords;
                $this->Recordset = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords);
            } else {
                $this->CurrentFilter = "0=1";
                $this->StartRecord = 1;
                $this->DisplayRecords = $this->GridAddRowCount;
            }
            $this->TotalRecords = $this->DisplayRecords;
            $this->StopRecord = $this->DisplayRecords;
        } else {
            $this->TotalRecords = $this->listRecordCount();
            $this->StartRecord = 1;
            $this->DisplayRecords = $this->TotalRecords; // Display all records
            $this->Recordset = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords);
        }

        // Normal return
        if (IsApi()) {
            $rows = $this->getRecordsFromRecordset($this->Recordset);
            $this->Recordset->close();
            WriteJson(["success" => true, $this->TableVar => $rows, "totalRecordCount" => $this->TotalRecords]);
            $this->terminate(true);
            return;
        }

        // Set up pager
        $this->Pager = new PrevNextPager($this->StartRecord, $this->getRecordsPerPage(), $this->TotalRecords, $this->PageSizes, $this->RecordRange, $this->AutoHidePager, $this->AutoHidePageSizeSelector);

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Pass table and field properties to client side
            $this->toClientVar(["tableCaption"], ["caption", "Required", "IsInvalid", "Raw"]);

            // Setup login status
            SetupLoginStatus();

            // Pass login status to client side
            SetClientVar("login", LoginStatus());

            // Global Page Rendering event (in userfn*.php)
            Page_Rendering();

            // Page Rendering event
            if (method_exists($this, "pageRender")) {
                $this->pageRender();
            }
        }
    }

    // Set up number of records displayed per page
    protected function setupDisplayRecords()
    {
        $wrk = Get(Config("TABLE_REC_PER_PAGE"), "");
        if ($wrk != "") {
            if (is_numeric($wrk)) {
                $this->DisplayRecords = (int)$wrk;
            } else {
                if (SameText($wrk, "all")) { // Display all records
                    $this->DisplayRecords = -1;
                } else {
                    $this->DisplayRecords = 20; // Non-numeric, load default
                }
            }
            $this->setRecordsPerPage($this->DisplayRecords); // Save to Session
            // Reset start position
            $this->StartRecord = 1;
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Exit inline mode
    protected function clearInlineMode()
    {
        $this->LastAction = $this->CurrentAction; // Save last action
        $this->CurrentAction = ""; // Clear action
        $_SESSION[SESSION_INLINE_MODE] = ""; // Clear inline mode
    }

    // Switch to Grid Add mode
    protected function gridAddMode()
    {
        $this->CurrentAction = "gridadd";
        $_SESSION[SESSION_INLINE_MODE] = "gridadd";
        $this->hideFieldsForAddEdit();
    }

    // Switch to Grid Edit mode
    protected function gridEditMode()
    {
        $this->CurrentAction = "gridedit";
        $_SESSION[SESSION_INLINE_MODE] = "gridedit";
        $this->hideFieldsForAddEdit();
    }

    // Perform update to grid
    public function gridUpdate()
    {
        global $Language, $CurrentForm;
        $gridUpdate = true;

        // Get old recordset
        $this->CurrentFilter = $this->buildKeyFilter();
        if ($this->CurrentFilter == "") {
            $this->CurrentFilter = "0=1";
        }
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        if ($rs = $conn->executeQuery($sql)) {
            $rsold = $rs->fetchAll();
            $rs->closeCursor();
        }

        // Call Grid Updating event
        if (!$this->gridUpdating($rsold)) {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("GridEditCancelled")); // Set grid edit cancelled message
            }
            return false;
        }
        $key = "";

        // Update row index and get row key
        $CurrentForm->Index = -1;
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }

        // Update all rows based on key
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            $CurrentForm->Index = $rowindex;
            $this->setKey($CurrentForm->getValue($this->OldKeyName));
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));

            // Load all values and keys
            if ($rowaction != "insertdelete") { // Skip insert then deleted rows
                $this->loadFormValues(); // Get form values
                if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
                    $gridUpdate = $this->OldKey != ""; // Key must not be empty
                } else {
                    $gridUpdate = true;
                }

                // Skip empty row
                if ($rowaction == "insert" && $this->emptyRow()) {
                // Validate form and insert/update/delete record
                } elseif ($gridUpdate) {
                    if ($rowaction == "delete") {
                        $this->CurrentFilter = $this->getRecordFilter();
                        $gridUpdate = $this->deleteRows(); // Delete this row
                    //} elseif (!$this->validateForm()) { // Already done in validateGridForm
                    //    $gridUpdate = false; // Form error, reset action
                    } else {
                        if ($rowaction == "insert") {
                            $gridUpdate = $this->addRow(); // Insert this row
                        } else {
                            if ($this->OldKey != "") {
                                $this->SendEmail = false; // Do not send email on update success
                                $gridUpdate = $this->editRow(); // Update this row
                            }
                        } // End update
                    }
                }
                if ($gridUpdate) {
                    if ($key != "") {
                        $key .= ", ";
                    }
                    $key .= $this->OldKey;
                } else {
                    break;
                }
            }
        }
        if ($gridUpdate) {
            // Get new records
            $rsnew = $conn->fetchAll($sql);

            // Call Grid_Updated event
            $this->gridUpdated($rsold, $rsnew);
            $this->clearInlineMode(); // Clear inline edit mode
        } else {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("UpdateFailed")); // Set update failed message
            }
        }
        return $gridUpdate;
    }

    // Build filter for all keys
    protected function buildKeyFilter()
    {
        global $CurrentForm;
        $wrkFilter = "";

        // Update row index and get row key
        $rowindex = 1;
        $CurrentForm->Index = $rowindex;
        $thisKey = strval($CurrentForm->getValue($this->OldKeyName));
        while ($thisKey != "") {
            $this->setKey($thisKey);
            if ($this->OldKey != "") {
                $filter = $this->getRecordFilter();
                if ($wrkFilter != "") {
                    $wrkFilter .= " OR ";
                }
                $wrkFilter .= $filter;
            } else {
                $wrkFilter = "0=1";
                break;
            }

            // Update row index and get row key
            $rowindex++; // Next row
            $CurrentForm->Index = $rowindex;
            $thisKey = strval($CurrentForm->getValue($this->OldKeyName));
        }
        return $wrkFilter;
    }

    // Perform Grid Add
    public function gridInsert()
    {
        global $Language, $CurrentForm;
        $rowindex = 1;
        $gridInsert = false;
        $conn = $this->getConnection();

        // Call Grid Inserting event
        if (!$this->gridInserting()) {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("GridAddCancelled")); // Set grid add cancelled message
            }
            return false;
        }

        // Init key filter
        $wrkfilter = "";
        $addcnt = 0;
        $key = "";

        // Get row count
        $CurrentForm->Index = -1;
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }

        // Insert all rows
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            // Load current row values
            $CurrentForm->Index = $rowindex;
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));
            if ($rowaction != "" && $rowaction != "insert") {
                continue; // Skip
            }
            if ($rowaction == "insert") {
                $this->OldKey = strval($CurrentForm->getValue($this->OldKeyName));
                $this->loadOldRecord(); // Load old record
            }
            $this->loadFormValues(); // Get form values
            if (!$this->emptyRow()) {
                $addcnt++;
                $this->SendEmail = false; // Do not send email on insert success

                // Validate form // Already done in validateGridForm
                //if (!$this->validateForm()) {
                //    $gridInsert = false; // Form error, reset action
                //} else {
                    $gridInsert = $this->addRow($this->OldRecordset); // Insert this row
                //}
                if ($gridInsert) {
                    if ($key != "") {
                        $key .= Config("COMPOSITE_KEY_SEPARATOR");
                    }
                    $key .= $this->id_peminjaman->CurrentValue;

                    // Add filter for this record
                    $filter = $this->getRecordFilter();
                    if ($wrkfilter != "") {
                        $wrkfilter .= " OR ";
                    }
                    $wrkfilter .= $filter;
                } else {
                    break;
                }
            }
        }
        if ($addcnt == 0) { // No record inserted
            $this->clearInlineMode(); // Clear grid add mode and return
            return true;
        }
        if ($gridInsert) {
            // Get new records
            $this->CurrentFilter = $wrkfilter;
            $sql = $this->getCurrentSql();
            $rsnew = $conn->fetchAll($sql);

            // Call Grid_Inserted event
            $this->gridInserted($rsnew);
            $this->clearInlineMode(); // Clear grid add mode
        } else {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("InsertFailed")); // Set insert failed message
            }
        }
        return $gridInsert;
    }

    // Check if empty row
    public function emptyRow()
    {
        global $CurrentForm;
        if ($CurrentForm->hasValue("x_berita_peminjaman") && $CurrentForm->hasValue("o_berita_peminjaman") && $this->berita_peminjaman->CurrentValue != $this->berita_peminjaman->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_id_buku") && $CurrentForm->hasValue("o_id_buku") && $this->id_buku->CurrentValue != $this->id_buku->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_id_anggota") && $CurrentForm->hasValue("o_id_anggota") && $this->id_anggota->CurrentValue != $this->id_anggota->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_rencana_tgl_kembali") && $CurrentForm->hasValue("o_rencana_tgl_kembali") && $this->rencana_tgl_kembali->CurrentValue != $this->rencana_tgl_kembali->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_kondisi_buku_peminjaman") && $CurrentForm->hasValue("o_kondisi_buku_peminjaman") && $this->kondisi_buku_peminjaman->CurrentValue != $this->kondisi_buku_peminjaman->OldValue) {
            return false;
        }
        return true;
    }

    // Validate grid form
    public function validateGridForm()
    {
        global $CurrentForm;
        // Get row count
        $CurrentForm->Index = -1;
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }

        // Validate all records
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            // Load current row values
            $CurrentForm->Index = $rowindex;
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));
            if ($rowaction != "delete" && $rowaction != "insertdelete") {
                $this->loadFormValues(); // Get form values
                if ($rowaction == "insert" && $this->emptyRow()) {
                    // Ignore
                } elseif (!$this->validateForm()) {
                    return false;
                }
            }
        }
        return true;
    }

    // Get all form values of the grid
    public function getGridFormValues()
    {
        global $CurrentForm;
        // Get row count
        $CurrentForm->Index = -1;
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }
        $rows = [];

        // Loop through all records
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            // Load current row values
            $CurrentForm->Index = $rowindex;
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));
            if ($rowaction != "delete" && $rowaction != "insertdelete") {
                $this->loadFormValues(); // Get form values
                if ($rowaction == "insert" && $this->emptyRow()) {
                    // Ignore
                } else {
                    $rows[] = $this->getFieldValues("FormValue"); // Return row as array
                }
            }
        }
        return $rows; // Return as array of array
    }

    // Restore form values for current row
    public function restoreCurrentRowFormValues($idx)
    {
        global $CurrentForm;

        // Get row based on current index
        $CurrentForm->Index = $idx;
        $rowaction = strval($CurrentForm->getValue($this->FormActionName));
        $this->loadFormValues(); // Load form values
        // Set up invalid status correctly
        $this->resetFormError();
        if ($rowaction == "insert" && $this->emptyRow()) {
            // Ignore
        } else {
            $this->validateForm();
        }
    }

    // Reset form status
    public function resetFormError()
    {
        $this->id_peminjaman->clearErrorMessage();
        $this->berita_peminjaman->clearErrorMessage();
        $this->id_buku->clearErrorMessage();
        $this->id_anggota->clearErrorMessage();
        $this->tgl_peminjaman->clearErrorMessage();
        $this->rencana_tgl_kembali->clearErrorMessage();
        $this->kondisi_buku_peminjaman->clearErrorMessage();
    }

    // Set up sort parameters
    protected function setupSortOrder()
    {
        // Check for "order" parameter
        if (Get("order") !== null) {
            $this->CurrentOrder = Get("order");
            $this->CurrentOrderType = Get("ordertype", "");
            $this->setStartRecordNumber(1); // Reset start position
        }
    }

    // Load sort order parameters
    protected function loadSortOrder()
    {
        $orderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
        if ($orderBy == "") {
            $this->DefaultSort = "`tgl_peminjaman` ASC";
            if ($this->getSqlOrderBy() != "") {
                $useDefaultSort = true;
                if ($this->tgl_peminjaman->getSort() != "") {
                    $useDefaultSort = false;
                }
                if ($useDefaultSort) {
                    $this->tgl_peminjaman->setSort("ASC");
                    $orderBy = $this->getSqlOrderBy();
                    $this->setSessionOrderBy($orderBy);
                } else {
                    $this->setSessionOrderBy("");
                }
            }
        }
    }

    // Reset command
    // - cmd=reset (Reset search parameters)
    // - cmd=resetall (Reset search and master/detail parameters)
    // - cmd=resetsort (Reset sort parameters)
    protected function resetCmd()
    {
        // Check if reset command
        if (StartsString("reset", $this->Command)) {
            // Reset master/detail keys
            if ($this->Command == "resetall") {
                $this->setCurrentMasterTable(""); // Clear master table
                $this->DbMasterFilter = "";
                $this->DbDetailFilter = "";
                        $this->id_anggota->setSessionValue("");
            }

            // Reset (clear) sorting order
            if ($this->Command == "resetsort") {
                $orderBy = "";
                $this->setSessionOrderBy($orderBy);
                $this->setSessionOrderByList($orderBy);
            }

            // Reset start position
            $this->StartRecord = 1;
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Set up list options
    protected function setupListOptions()
    {
        global $Security, $Language;

        // "griddelete"
        if ($this->AllowAddDeleteRow) {
            $item = &$this->ListOptions->add("griddelete");
            $item->CssClass = "text-nowrap";
            $item->OnLeft = true;
            $item->Visible = false; // Default hidden
        }

        // Add group option item
        $item = &$this->ListOptions->add($this->ListOptions->GroupOptionName);
        $item->Body = "";
        $item->OnLeft = true;
        $item->Visible = false;

        // "view"
        $item = &$this->ListOptions->add("view");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canView();
        $item->OnLeft = true;

        // "edit"
        $item = &$this->ListOptions->add("edit");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canEdit();
        $item->OnLeft = true;

        // "copy"
        $item = &$this->ListOptions->add("copy");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canAdd();
        $item->OnLeft = true;

        // "delete"
        $item = &$this->ListOptions->add("delete");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canDelete();
        $item->OnLeft = true;

        // "sequence"
        $item = &$this->ListOptions->add("sequence");
        $item->CssClass = "text-nowrap";
        $item->Visible = true;
        $item->OnLeft = true; // Always on left
        $item->ShowInDropDown = false;
        $item->ShowInButtonGroup = false;

        // Drop down button for ListOptions
        $this->ListOptions->UseDropDownButton = false;
        $this->ListOptions->DropDownButtonPhrase = $Language->phrase("ButtonListOptions");
        $this->ListOptions->UseButtonGroup = false;
        if ($this->ListOptions->UseButtonGroup && IsMobile()) {
            $this->ListOptions->UseDropDownButton = true;
        }

        //$this->ListOptions->ButtonClass = ""; // Class for button group

        // Call ListOptions_Load event
        $this->listOptionsLoad();
        $item = $this->ListOptions[$this->ListOptions->GroupOptionName];
        $item->Visible = $this->ListOptions->groupOptionVisible();
    }

    // Render list options
    public function renderListOptions()
    {
        global $Security, $Language, $CurrentForm;
        $this->ListOptions->loadDefault();

        // Call ListOptions_Rendering event
        $this->listOptionsRendering();

        // Set up row action and key
        $keyName = "";
        if ($CurrentForm && is_numeric($this->RowIndex) && $this->RowType != "view") {
            $CurrentForm->Index = $this->RowIndex;
            $actionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
            $oldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->OldKeyName);
            $blankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
            if ($this->RowAction != "") {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $actionName . "\" id=\"" . $actionName . "\" value=\"" . $this->RowAction . "\">";
            }
            $oldKey = $this->getKey(false); // Get from OldValue
            if ($oldKeyName != "" && $oldKey != "") {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $oldKeyName . "\" id=\"" . $oldKeyName . "\" value=\"" . HtmlEncode($oldKey) . "\">";
            }
            if ($this->RowAction == "insert" && $this->isConfirm() && $this->emptyRow()) {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $blankRowName . "\" id=\"" . $blankRowName . "\" value=\"1\">";
            }
        }

        // "delete"
        if ($this->AllowAddDeleteRow) {
            if ($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") {
                $options = &$this->ListOptions;
                $options->UseButtonGroup = true; // Use button group for grid delete button
                $opt = $options["griddelete"];
                if (!$Security->canDelete() && is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
                    $opt->Body = "&nbsp;";
                } else {
                    $opt->Body = "<a class=\"ew-grid-link ew-grid-delete\" title=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" onclick=\"return ew.deleteGridRow(this, " . $this->RowIndex . ");\">" . $Language->phrase("DeleteLink") . "</a>";
                }
            }
        }

        // "sequence"
        $opt = $this->ListOptions["sequence"];
        $opt->Body = FormatSequenceNumber($this->RecordCount);
        if ($this->CurrentMode == "view") {
            // "view"
            $opt = $this->ListOptions["view"];
            $viewcaption = HtmlTitle($Language->phrase("ViewLink"));
            if ($Security->canView() && $this->showOptionLink("view")) {
                $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . HtmlEncode(GetUrl($this->ViewUrl)) . "\">" . $Language->phrase("ViewLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "edit"
            $opt = $this->ListOptions["edit"];
            $editcaption = HtmlTitle($Language->phrase("EditLink"));
            if ($Security->canEdit() && $this->showOptionLink("edit")) {
                $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . HtmlTitle($Language->phrase("EditLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("EditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\">" . $Language->phrase("EditLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "copy"
            $opt = $this->ListOptions["copy"];
            $copycaption = HtmlTitle($Language->phrase("CopyLink"));
            if ($Security->canAdd() && $this->showOptionLink("add")) {
                $opt->Body = "<a class=\"ew-row-link ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\">" . $Language->phrase("CopyLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "delete"
            $opt = $this->ListOptions["delete"];
            if ($Security->canDelete() && $this->showOptionLink("delete")) {
            $opt->Body = "<a class=\"ew-row-link ew-delete\"" . "" . " title=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->DeleteUrl)) . "\">" . $Language->phrase("DeleteLink") . "</a>";
            } else {
                $opt->Body = "";
            }
        } // End View mode
        if ($this->CurrentMode == "edit" && is_numeric($this->RowIndex) && $this->RowAction != "delete") {
            if ($keyName != "") {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $keyName . "\" id=\"" . $keyName . "\" value=\"" . $this->id_peminjaman->CurrentValue . "\">";
            }
        }
        $this->renderListOptionsExt();

        // Call ListOptions_Rendered event
        $this->listOptionsRendered();
    }

    // Set up other options
    protected function setupOtherOptions()
    {
        global $Language, $Security;
        $option = $this->OtherOptions["addedit"];
        $option->UseDropDownButton = false;
        $option->DropDownButtonPhrase = $Language->phrase("ButtonAddEdit");
        $option->UseButtonGroup = true;
        //$option->ButtonClass = ""; // Class for button group
        $item = &$option->add($option->GroupOptionName);
        $item->Body = "";
        $item->Visible = false;

        // Add
        if ($this->CurrentMode == "view") { // Check view mode
            $item = &$option->add("add");
            $addcaption = HtmlTitle($Language->phrase("AddLink"));
            $this->AddUrl = $this->getAddUrl();
            $item->Body = "<a class=\"ew-add-edit ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\">" . $Language->phrase("AddLink") . "</a>";
            $item->Visible = $this->AddUrl != "" && $Security->canAdd();
        }
    }

    // Render other options
    public function renderOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
        if (($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") && !$this->isConfirm()) { // Check add/copy/edit mode
            if ($this->AllowAddDeleteRow) {
                $option = $options["addedit"];
                $option->UseDropDownButton = false;
                $item = &$option->add("addblankrow");
                $item->Body = "<a class=\"ew-add-edit ew-add-blank-row\" title=\"" . HtmlTitle($Language->phrase("AddBlankRow")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("AddBlankRow")) . "\" href=\"#\" onclick=\"return ew.addGridRow(this);\">" . $Language->phrase("AddBlankRow") . "</a>";
                $item->Visible = $Security->canAdd();
                $this->ShowOtherOptions = $item->Visible;
            }
        }
        if ($this->CurrentMode == "view") { // Check view mode
            $option = $options["addedit"];
            $item = $option["add"];
            $this->ShowOtherOptions = $item && $item->Visible;
        }
    }

    // Set up list options (extended codes)
    protected function setupListOptionsExt()
    {
    }

    // Render list options (extended codes)
    protected function renderListOptionsExt()
    {
        global $Security, $Language;
        $links = "";
        $btngrps = "";
        $sqlwrk = "`id_peminjaman`=" . AdjustSql($this->id_peminjaman->CurrentValue, $this->Dbid) . "";

        // Column "detail_pengembalian"
        if ($this->DetailPages && $this->DetailPages["pengembalian"] && $this->DetailPages["pengembalian"]->Visible) {
            $link = "";
            $option = $this->ListOptions["detail_pengembalian"];
            $url = "PengembalianPreview?t=peminjaman&f=" . Encrypt($sqlwrk);
            $btngrp = "<div data-table=\"pengembalian\" data-url=\"" . $url . "\">";
            if ($Security->allowList(CurrentProjectID() . 'peminjaman')) {
                $label = $Language->TablePhrase("pengembalian", "TblCaption");
                $label .= "&nbsp;" . JsEncode(str_replace("%c", $this->pengembalian_Count, $Language->phrase("DetailCount")));
                $link = "<li class=\"nav-item\"><a href=\"#\" class=\"nav-link\" data-toggle=\"tab\" data-table=\"pengembalian\" data-url=\"" . $url . "\">" . $label . "</a></li>";
                $links .= $link;
                $detaillnk = JsEncodeAttribute("PengembalianList?" . Config("TABLE_SHOW_MASTER") . "=peminjaman&" . GetForeignKeyUrl("fk_id_peminjaman", $this->id_peminjaman->CurrentValue) . "");
                $btngrp .= "<a href=\"#\" class=\"mr-2\" title=\"" . $Language->TablePhrase("pengembalian", "TblCaption") . "\" onclick=\"window.location='" . $detaillnk . "';return false;\">" . $Language->phrase("MasterDetailListLink") . "</a>";
            }
            $detailPageObj = Container("PengembalianGrid");
            if ($detailPageObj->DetailView && $Security->canView() && $this->showOptionLink("view") && $Security->allowView(CurrentProjectID() . 'peminjaman')) {
                $caption = $Language->phrase("MasterDetailViewLink");
                $url = $this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=pengembalian");
                $btngrp .= "<a href=\"#\" class=\"mr-2\" title=\"" . HtmlTitle($caption) . "\" onclick=\"window.location='" . HtmlEncode($url) . "';return false;\">" . $caption . "</a>";
            }
            if ($detailPageObj->DetailEdit && $Security->canEdit() && $this->showOptionLink("edit") && $Security->allowEdit(CurrentProjectID() . 'peminjaman')) {
                $caption = $Language->phrase("MasterDetailEditLink");
                $url = $this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=pengembalian");
                $btngrp .= "<a href=\"#\" class=\"mr-2\" title=\"" . HtmlTitle($caption) . "\" onclick=\"window.location='" . HtmlEncode($url) . "';return false;\">" . $caption . "</a>";
            }
            if ($detailPageObj->DetailAdd && $Security->canAdd() && $this->showOptionLink("add") && $Security->allowAdd(CurrentProjectID() . 'peminjaman')) {
                $caption = $Language->phrase("MasterDetailCopyLink");
                $url = $this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=pengembalian");
                $btngrp .= "<a href=\"#\" class=\"mr-2\" title=\"" . HtmlTitle($caption) . "\" onclick=\"window.location='" . HtmlEncode($url) . "';return false;\">" . $caption . "</a>";
            }
            $btngrp .= "</div>";
            if ($link != "") {
                $btngrps .= $btngrp;
                $option->Body .= "<div class=\"d-none ew-preview\">" . $link . $btngrp . "</div>";
            }
        }

        // Column "details" (Multiple details)
        $option = $this->ListOptions["details"];
        if ($option) {
            $option->Body .= "<div class=\"d-none ew-preview\">" . $links . $btngrps . "</div>";
            if ($option->Visible) {
                $option->Visible = $links != "";
            }
        }
    }

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->id_peminjaman->CurrentValue = null;
        $this->id_peminjaman->OldValue = $this->id_peminjaman->CurrentValue;
        $this->berita_peminjaman->CurrentValue = null;
        $this->berita_peminjaman->OldValue = $this->berita_peminjaman->CurrentValue;
        $this->id_buku->CurrentValue = null;
        $this->id_buku->OldValue = $this->id_buku->CurrentValue;
        $this->id_anggota->CurrentValue = CurrentUserID();
        $this->id_anggota->OldValue = $this->id_anggota->CurrentValue;
        $this->tgl_peminjaman->CurrentValue = CurrentDate();
        $this->tgl_peminjaman->OldValue = $this->tgl_peminjaman->CurrentValue;
        $this->rencana_tgl_kembali->CurrentValue = null;
        $this->rencana_tgl_kembali->OldValue = $this->rencana_tgl_kembali->CurrentValue;
        $this->kondisi_buku_peminjaman->CurrentValue = null;
        $this->kondisi_buku_peminjaman->OldValue = $this->kondisi_buku_peminjaman->CurrentValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $CurrentForm->FormName = $this->FormName;

        // Check field name 'id_peminjaman' first before field var 'x_id_peminjaman'
        $val = $CurrentForm->hasValue("id_peminjaman") ? $CurrentForm->getValue("id_peminjaman") : $CurrentForm->getValue("x_id_peminjaman");
        if (!$this->id_peminjaman->IsDetailKey && !$this->isGridAdd() && !$this->isAdd()) {
            $this->id_peminjaman->setFormValue($val);
        }

        // Check field name 'berita_peminjaman' first before field var 'x_berita_peminjaman'
        $val = $CurrentForm->hasValue("berita_peminjaman") ? $CurrentForm->getValue("berita_peminjaman") : $CurrentForm->getValue("x_berita_peminjaman");
        if (!$this->berita_peminjaman->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->berita_peminjaman->Visible = false; // Disable update for API request
            } else {
                $this->berita_peminjaman->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_berita_peminjaman")) {
            $this->berita_peminjaman->setOldValue($CurrentForm->getValue("o_berita_peminjaman"));
        }

        // Check field name 'id_buku' first before field var 'x_id_buku'
        $val = $CurrentForm->hasValue("id_buku") ? $CurrentForm->getValue("id_buku") : $CurrentForm->getValue("x_id_buku");
        if (!$this->id_buku->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->id_buku->Visible = false; // Disable update for API request
            } else {
                $this->id_buku->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_id_buku")) {
            $this->id_buku->setOldValue($CurrentForm->getValue("o_id_buku"));
        }

        // Check field name 'id_anggota' first before field var 'x_id_anggota'
        $val = $CurrentForm->hasValue("id_anggota") ? $CurrentForm->getValue("id_anggota") : $CurrentForm->getValue("x_id_anggota");
        if (!$this->id_anggota->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->id_anggota->Visible = false; // Disable update for API request
            } else {
                $this->id_anggota->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_id_anggota")) {
            $this->id_anggota->setOldValue($CurrentForm->getValue("o_id_anggota"));
        }

        // Check field name 'tgl_peminjaman' first before field var 'x_tgl_peminjaman'
        $val = $CurrentForm->hasValue("tgl_peminjaman") ? $CurrentForm->getValue("tgl_peminjaman") : $CurrentForm->getValue("x_tgl_peminjaman");
        if (!$this->tgl_peminjaman->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->tgl_peminjaman->Visible = false; // Disable update for API request
            } else {
                $this->tgl_peminjaman->setFormValue($val);
            }
            $this->tgl_peminjaman->CurrentValue = UnFormatDateTime($this->tgl_peminjaman->CurrentValue, 0);
        }
        if ($CurrentForm->hasValue("o_tgl_peminjaman")) {
            $this->tgl_peminjaman->setOldValue($CurrentForm->getValue("o_tgl_peminjaman"));
        }

        // Check field name 'rencana_tgl_kembali' first before field var 'x_rencana_tgl_kembali'
        $val = $CurrentForm->hasValue("rencana_tgl_kembali") ? $CurrentForm->getValue("rencana_tgl_kembali") : $CurrentForm->getValue("x_rencana_tgl_kembali");
        if (!$this->rencana_tgl_kembali->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->rencana_tgl_kembali->Visible = false; // Disable update for API request
            } else {
                $this->rencana_tgl_kembali->setFormValue($val);
            }
            $this->rencana_tgl_kembali->CurrentValue = UnFormatDateTime($this->rencana_tgl_kembali->CurrentValue, 0);
        }
        if ($CurrentForm->hasValue("o_rencana_tgl_kembali")) {
            $this->rencana_tgl_kembali->setOldValue($CurrentForm->getValue("o_rencana_tgl_kembali"));
        }

        // Check field name 'kondisi_buku_peminjaman' first before field var 'x_kondisi_buku_peminjaman'
        $val = $CurrentForm->hasValue("kondisi_buku_peminjaman") ? $CurrentForm->getValue("kondisi_buku_peminjaman") : $CurrentForm->getValue("x_kondisi_buku_peminjaman");
        if (!$this->kondisi_buku_peminjaman->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->kondisi_buku_peminjaman->Visible = false; // Disable update for API request
            } else {
                $this->kondisi_buku_peminjaman->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_kondisi_buku_peminjaman")) {
            $this->kondisi_buku_peminjaman->setOldValue($CurrentForm->getValue("o_kondisi_buku_peminjaman"));
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        if (!$this->isGridAdd() && !$this->isAdd()) {
            $this->id_peminjaman->CurrentValue = $this->id_peminjaman->FormValue;
        }
        $this->berita_peminjaman->CurrentValue = $this->berita_peminjaman->FormValue;
        $this->id_buku->CurrentValue = $this->id_buku->FormValue;
        $this->id_anggota->CurrentValue = $this->id_anggota->FormValue;
        $this->tgl_peminjaman->CurrentValue = $this->tgl_peminjaman->FormValue;
        $this->tgl_peminjaman->CurrentValue = UnFormatDateTime($this->tgl_peminjaman->CurrentValue, 0);
        $this->rencana_tgl_kembali->CurrentValue = $this->rencana_tgl_kembali->FormValue;
        $this->rencana_tgl_kembali->CurrentValue = UnFormatDateTime($this->rencana_tgl_kembali->CurrentValue, 0);
        $this->kondisi_buku_peminjaman->CurrentValue = $this->kondisi_buku_peminjaman->FormValue;
    }

    // Load recordset
    public function loadRecordset($offset = -1, $rowcnt = -1)
    {
        // Load List page SQL (QueryBuilder)
        $sql = $this->getListSql();

        // Load recordset
        if ($offset > -1) {
            $sql->setFirstResult($offset);
        }
        if ($rowcnt > 0) {
            $sql->setMaxResults($rowcnt);
        }
        $stmt = $sql->execute();
        $rs = new Recordset($stmt, $sql);

        // Call Recordset Selected event
        $this->recordsetSelected($rs);
        return $rs;
    }

    /**
     * Load row based on key values
     *
     * @return void
     */
    public function loadRow()
    {
        global $Security, $Language;
        $filter = $this->getRecordFilter();

        // Call Row Selecting event
        $this->rowSelecting($filter);

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $res = false;
        $row = $conn->fetchAssoc($sql);
        if ($row) {
            $res = true;
            $this->loadRowValues($row); // Load row values
        }
        return $res;
    }

    /**
     * Load row values from recordset or record
     *
     * @param Recordset|array $rs Record
     * @return void
     */
    public function loadRowValues($rs = null)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            $row = $this->newRow();
        }

        // Call Row Selected event
        $this->rowSelected($row);
        if (!$rs) {
            return;
        }
        $this->id_peminjaman->setDbValue($row['id_peminjaman']);
        $this->berita_peminjaman->setDbValue($row['berita_peminjaman']);
        $this->id_buku->setDbValue($row['id_buku']);
        if (array_key_exists('EV__id_buku', $row)) {
            $this->id_buku->VirtualValue = $row['EV__id_buku']; // Set up virtual field value
        } else {
            $this->id_buku->VirtualValue = ""; // Clear value
        }
        $this->id_anggota->setDbValue($row['id_anggota']);
        if (array_key_exists('EV__id_anggota', $row)) {
            $this->id_anggota->VirtualValue = $row['EV__id_anggota']; // Set up virtual field value
        } else {
            $this->id_anggota->VirtualValue = ""; // Clear value
        }
        $this->tgl_peminjaman->setDbValue($row['tgl_peminjaman']);
        $this->rencana_tgl_kembali->setDbValue($row['rencana_tgl_kembali']);
        $this->kondisi_buku_peminjaman->setDbValue($row['kondisi_buku_peminjaman']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['id_peminjaman'] = $this->id_peminjaman->CurrentValue;
        $row['berita_peminjaman'] = $this->berita_peminjaman->CurrentValue;
        $row['id_buku'] = $this->id_buku->CurrentValue;
        $row['id_anggota'] = $this->id_anggota->CurrentValue;
        $row['tgl_peminjaman'] = $this->tgl_peminjaman->CurrentValue;
        $row['rencana_tgl_kembali'] = $this->rencana_tgl_kembali->CurrentValue;
        $row['kondisi_buku_peminjaman'] = $this->kondisi_buku_peminjaman->CurrentValue;
        return $row;
    }

    // Load old record
    protected function loadOldRecord()
    {
        // Load old record
        $this->OldRecordset = null;
        $validKey = $this->OldKey != "";
        if ($validKey) {
            $this->CurrentFilter = $this->getRecordFilter();
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $this->OldRecordset = LoadRecordset($sql, $conn);
        }
        $this->loadRowValues($this->OldRecordset); // Load row values
        return $validKey;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs
        $this->ViewUrl = $this->getViewUrl();
        $this->EditUrl = $this->getEditUrl();
        $this->CopyUrl = $this->getCopyUrl();
        $this->DeleteUrl = $this->getDeleteUrl();

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // id_peminjaman

        // berita_peminjaman

        // id_buku

        // id_anggota

        // tgl_peminjaman

        // rencana_tgl_kembali

        // kondisi_buku_peminjaman
        if ($this->RowType == ROWTYPE_VIEW) {
            // id_peminjaman
            $this->id_peminjaman->ViewValue = $this->id_peminjaman->CurrentValue;
            $this->id_peminjaman->ViewCustomAttributes = "";

            // berita_peminjaman
            $this->berita_peminjaman->ViewValue = $this->berita_peminjaman->CurrentValue;
            $this->berita_peminjaman->ViewCustomAttributes = "";

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

            // id_peminjaman
            $this->id_peminjaman->LinkCustomAttributes = "";
            $this->id_peminjaman->HrefValue = "";
            $this->id_peminjaman->TooltipValue = "";

            // berita_peminjaman
            $this->berita_peminjaman->LinkCustomAttributes = "";
            $this->berita_peminjaman->HrefValue = "";
            $this->berita_peminjaman->TooltipValue = "";

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
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // id_peminjaman

            // berita_peminjaman
            $this->berita_peminjaman->EditAttrs["class"] = "form-control";
            $this->berita_peminjaman->EditCustomAttributes = "";
            if (!$this->berita_peminjaman->Raw) {
                $this->berita_peminjaman->CurrentValue = HtmlDecode($this->berita_peminjaman->CurrentValue);
            }
            $this->berita_peminjaman->EditValue = HtmlEncode($this->berita_peminjaman->CurrentValue);
            $this->berita_peminjaman->PlaceHolder = RemoveHtml($this->berita_peminjaman->caption());

            // id_buku
            $this->id_buku->EditAttrs["class"] = "form-control";
            $this->id_buku->EditCustomAttributes = "";
            $curVal = trim(strval($this->id_buku->CurrentValue));
            if ($curVal != "") {
                $this->id_buku->ViewValue = $this->id_buku->lookupCacheOption($curVal);
            } else {
                $this->id_buku->ViewValue = $this->id_buku->Lookup !== null && is_array($this->id_buku->Lookup->Options) ? $curVal : null;
            }
            if ($this->id_buku->ViewValue !== null) { // Load from cache
                $this->id_buku->EditValue = array_values($this->id_buku->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`id_buku`" . SearchString("=", $this->id_buku->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->id_buku->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->id_buku->EditValue = $arwrk;
            }
            $this->id_buku->PlaceHolder = RemoveHtml($this->id_buku->caption());

            // id_anggota
            $this->id_anggota->EditAttrs["class"] = "form-control";
            $this->id_anggota->EditCustomAttributes = "";
            if ($this->id_anggota->getSessionValue() != "") {
                $this->id_anggota->CurrentValue = GetForeignKeyValue($this->id_anggota->getSessionValue());
                $this->id_anggota->OldValue = $this->id_anggota->CurrentValue;
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
            } elseif (!$Security->isAdmin() && $Security->isLoggedIn() && !$this->userIDAllow("grid")) { // Non system admin
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
                $curVal = trim(strval($this->id_anggota->CurrentValue));
                if ($curVal != "") {
                    $this->id_anggota->ViewValue = $this->id_anggota->lookupCacheOption($curVal);
                } else {
                    $this->id_anggota->ViewValue = $this->id_anggota->Lookup !== null && is_array($this->id_anggota->Lookup->Options) ? $curVal : null;
                }
                if ($this->id_anggota->ViewValue !== null) { // Load from cache
                    $this->id_anggota->EditValue = array_values($this->id_anggota->Lookup->Options);
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = "`id_anggota`" . SearchString("=", $this->id_anggota->CurrentValue, DATATYPE_NUMBER, "");
                    }
                    $sqlWrk = $this->id_anggota->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    $arwrk = $rswrk;
                    $this->id_anggota->EditValue = $arwrk;
                }
                $this->id_anggota->PlaceHolder = RemoveHtml($this->id_anggota->caption());
            }

            // tgl_peminjaman

            // rencana_tgl_kembali
            $this->rencana_tgl_kembali->EditAttrs["class"] = "form-control";
            $this->rencana_tgl_kembali->EditCustomAttributes = "";
            $this->rencana_tgl_kembali->EditValue = HtmlEncode(FormatDateTime($this->rencana_tgl_kembali->CurrentValue, 8));
            $this->rencana_tgl_kembali->PlaceHolder = RemoveHtml($this->rencana_tgl_kembali->caption());

            // kondisi_buku_peminjaman
            $this->kondisi_buku_peminjaman->EditAttrs["class"] = "form-control";
            $this->kondisi_buku_peminjaman->EditCustomAttributes = "";
            if (!$this->kondisi_buku_peminjaman->Raw) {
                $this->kondisi_buku_peminjaman->CurrentValue = HtmlDecode($this->kondisi_buku_peminjaman->CurrentValue);
            }
            $this->kondisi_buku_peminjaman->EditValue = HtmlEncode($this->kondisi_buku_peminjaman->CurrentValue);
            $this->kondisi_buku_peminjaman->PlaceHolder = RemoveHtml($this->kondisi_buku_peminjaman->caption());

            // Add refer script

            // id_peminjaman
            $this->id_peminjaman->LinkCustomAttributes = "";
            $this->id_peminjaman->HrefValue = "";

            // berita_peminjaman
            $this->berita_peminjaman->LinkCustomAttributes = "";
            $this->berita_peminjaman->HrefValue = "";

            // id_buku
            $this->id_buku->LinkCustomAttributes = "";
            $this->id_buku->HrefValue = "";

            // id_anggota
            $this->id_anggota->LinkCustomAttributes = "";
            $this->id_anggota->HrefValue = "";

            // tgl_peminjaman
            $this->tgl_peminjaman->LinkCustomAttributes = "";
            $this->tgl_peminjaman->HrefValue = "";

            // rencana_tgl_kembali
            $this->rencana_tgl_kembali->LinkCustomAttributes = "";
            $this->rencana_tgl_kembali->HrefValue = "";

            // kondisi_buku_peminjaman
            $this->kondisi_buku_peminjaman->LinkCustomAttributes = "";
            $this->kondisi_buku_peminjaman->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // id_peminjaman
            $this->id_peminjaman->EditAttrs["class"] = "form-control";
            $this->id_peminjaman->EditCustomAttributes = "";
            $this->id_peminjaman->EditValue = $this->id_peminjaman->CurrentValue;
            $this->id_peminjaman->ViewCustomAttributes = "";

            // berita_peminjaman
            $this->berita_peminjaman->EditAttrs["class"] = "form-control";
            $this->berita_peminjaman->EditCustomAttributes = "";
            if (!$this->berita_peminjaman->Raw) {
                $this->berita_peminjaman->CurrentValue = HtmlDecode($this->berita_peminjaman->CurrentValue);
            }
            $this->berita_peminjaman->EditValue = HtmlEncode($this->berita_peminjaman->CurrentValue);
            $this->berita_peminjaman->PlaceHolder = RemoveHtml($this->berita_peminjaman->caption());

            // id_buku
            $this->id_buku->EditAttrs["class"] = "form-control";
            $this->id_buku->EditCustomAttributes = "";
            $curVal = trim(strval($this->id_buku->CurrentValue));
            if ($curVal != "") {
                $this->id_buku->ViewValue = $this->id_buku->lookupCacheOption($curVal);
            } else {
                $this->id_buku->ViewValue = $this->id_buku->Lookup !== null && is_array($this->id_buku->Lookup->Options) ? $curVal : null;
            }
            if ($this->id_buku->ViewValue !== null) { // Load from cache
                $this->id_buku->EditValue = array_values($this->id_buku->Lookup->Options);
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`id_buku`" . SearchString("=", $this->id_buku->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->id_buku->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->id_buku->EditValue = $arwrk;
            }
            $this->id_buku->PlaceHolder = RemoveHtml($this->id_buku->caption());

            // id_anggota
            $this->id_anggota->EditAttrs["class"] = "form-control";
            $this->id_anggota->EditCustomAttributes = "";
            if ($this->id_anggota->getSessionValue() != "") {
                $this->id_anggota->CurrentValue = GetForeignKeyValue($this->id_anggota->getSessionValue());
                $this->id_anggota->OldValue = $this->id_anggota->CurrentValue;
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
            } elseif (!$Security->isAdmin() && $Security->isLoggedIn() && !$this->userIDAllow("grid")) { // Non system admin
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
                $curVal = trim(strval($this->id_anggota->CurrentValue));
                if ($curVal != "") {
                    $this->id_anggota->ViewValue = $this->id_anggota->lookupCacheOption($curVal);
                } else {
                    $this->id_anggota->ViewValue = $this->id_anggota->Lookup !== null && is_array($this->id_anggota->Lookup->Options) ? $curVal : null;
                }
                if ($this->id_anggota->ViewValue !== null) { // Load from cache
                    $this->id_anggota->EditValue = array_values($this->id_anggota->Lookup->Options);
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = "`id_anggota`" . SearchString("=", $this->id_anggota->CurrentValue, DATATYPE_NUMBER, "");
                    }
                    $sqlWrk = $this->id_anggota->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    $arwrk = $rswrk;
                    $this->id_anggota->EditValue = $arwrk;
                }
                $this->id_anggota->PlaceHolder = RemoveHtml($this->id_anggota->caption());
            }

            // tgl_peminjaman

            // rencana_tgl_kembali
            $this->rencana_tgl_kembali->EditAttrs["class"] = "form-control";
            $this->rencana_tgl_kembali->EditCustomAttributes = "";
            $this->rencana_tgl_kembali->EditValue = HtmlEncode(FormatDateTime($this->rencana_tgl_kembali->CurrentValue, 8));
            $this->rencana_tgl_kembali->PlaceHolder = RemoveHtml($this->rencana_tgl_kembali->caption());

            // kondisi_buku_peminjaman
            $this->kondisi_buku_peminjaman->EditAttrs["class"] = "form-control";
            $this->kondisi_buku_peminjaman->EditCustomAttributes = "";
            if (!$this->kondisi_buku_peminjaman->Raw) {
                $this->kondisi_buku_peminjaman->CurrentValue = HtmlDecode($this->kondisi_buku_peminjaman->CurrentValue);
            }
            $this->kondisi_buku_peminjaman->EditValue = HtmlEncode($this->kondisi_buku_peminjaman->CurrentValue);
            $this->kondisi_buku_peminjaman->PlaceHolder = RemoveHtml($this->kondisi_buku_peminjaman->caption());

            // Edit refer script

            // id_peminjaman
            $this->id_peminjaman->LinkCustomAttributes = "";
            $this->id_peminjaman->HrefValue = "";

            // berita_peminjaman
            $this->berita_peminjaman->LinkCustomAttributes = "";
            $this->berita_peminjaman->HrefValue = "";

            // id_buku
            $this->id_buku->LinkCustomAttributes = "";
            $this->id_buku->HrefValue = "";

            // id_anggota
            $this->id_anggota->LinkCustomAttributes = "";
            $this->id_anggota->HrefValue = "";

            // tgl_peminjaman
            $this->tgl_peminjaman->LinkCustomAttributes = "";
            $this->tgl_peminjaman->HrefValue = "";

            // rencana_tgl_kembali
            $this->rencana_tgl_kembali->LinkCustomAttributes = "";
            $this->rencana_tgl_kembali->HrefValue = "";

            // kondisi_buku_peminjaman
            $this->kondisi_buku_peminjaman->LinkCustomAttributes = "";
            $this->kondisi_buku_peminjaman->HrefValue = "";
        }
        if ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate form
    protected function validateForm()
    {
        global $Language;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        if ($this->id_peminjaman->Required) {
            if (!$this->id_peminjaman->IsDetailKey && EmptyValue($this->id_peminjaman->FormValue)) {
                $this->id_peminjaman->addErrorMessage(str_replace("%s", $this->id_peminjaman->caption(), $this->id_peminjaman->RequiredErrorMessage));
            }
        }
        if ($this->berita_peminjaman->Required) {
            if (!$this->berita_peminjaman->IsDetailKey && EmptyValue($this->berita_peminjaman->FormValue)) {
                $this->berita_peminjaman->addErrorMessage(str_replace("%s", $this->berita_peminjaman->caption(), $this->berita_peminjaman->RequiredErrorMessage));
            }
        }
        if ($this->id_buku->Required) {
            if (!$this->id_buku->IsDetailKey && EmptyValue($this->id_buku->FormValue)) {
                $this->id_buku->addErrorMessage(str_replace("%s", $this->id_buku->caption(), $this->id_buku->RequiredErrorMessage));
            }
        }
        if ($this->id_anggota->Required) {
            if (!$this->id_anggota->IsDetailKey && EmptyValue($this->id_anggota->FormValue)) {
                $this->id_anggota->addErrorMessage(str_replace("%s", $this->id_anggota->caption(), $this->id_anggota->RequiredErrorMessage));
            }
        }
        if ($this->tgl_peminjaman->Required) {
            if (!$this->tgl_peminjaman->IsDetailKey && EmptyValue($this->tgl_peminjaman->FormValue)) {
                $this->tgl_peminjaman->addErrorMessage(str_replace("%s", $this->tgl_peminjaman->caption(), $this->tgl_peminjaman->RequiredErrorMessage));
            }
        }
        if ($this->rencana_tgl_kembali->Required) {
            if (!$this->rencana_tgl_kembali->IsDetailKey && EmptyValue($this->rencana_tgl_kembali->FormValue)) {
                $this->rencana_tgl_kembali->addErrorMessage(str_replace("%s", $this->rencana_tgl_kembali->caption(), $this->rencana_tgl_kembali->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->rencana_tgl_kembali->FormValue)) {
            $this->rencana_tgl_kembali->addErrorMessage($this->rencana_tgl_kembali->getErrorMessage(false));
        }
        if ($this->kondisi_buku_peminjaman->Required) {
            if (!$this->kondisi_buku_peminjaman->IsDetailKey && EmptyValue($this->kondisi_buku_peminjaman->FormValue)) {
                $this->kondisi_buku_peminjaman->addErrorMessage(str_replace("%s", $this->kondisi_buku_peminjaman->caption(), $this->kondisi_buku_peminjaman->RequiredErrorMessage));
            }
        }

        // Return validate result
        $validateForm = !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Delete records based on current filter
    protected function deleteRows()
    {
        global $Language, $Security;
        if (!$Security->canDelete()) {
            $this->setFailureMessage($Language->phrase("NoDeletePermission")); // No delete permission
            return false;
        }
        $deleteRows = true;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $rows = $conn->fetchAll($sql);
        if (count($rows) == 0) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
            return false;
        }

        // Clone old rows
        $rsold = $rows;

        // Call row deleting event
        if ($deleteRows) {
            foreach ($rsold as $row) {
                $deleteRows = $this->rowDeleting($row);
                if (!$deleteRows) {
                    break;
                }
            }
        }
        if ($deleteRows) {
            $key = "";
            foreach ($rsold as $row) {
                $thisKey = "";
                if ($thisKey != "") {
                    $thisKey .= Config("COMPOSITE_KEY_SEPARATOR");
                }
                $thisKey .= $row['id_peminjaman'];
                if (Config("DELETE_UPLOADED_FILES")) { // Delete old files
                    $this->deleteUploadedFiles($row);
                }
                $deleteRows = $this->delete($row); // Delete
                if ($deleteRows === false) {
                    break;
                }
                if ($key != "") {
                    $key .= ", ";
                }
                $key .= $thisKey;
            }
        }
        if (!$deleteRows) {
            // Set up error message
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("DeleteCancelled"));
            }
        }

        // Call Row Deleted event
        if ($deleteRows) {
            foreach ($rsold as $row) {
                $this->rowDeleted($row);
            }
        }

        // Write JSON for API request
        if (IsApi() && $deleteRows) {
            $row = $this->getRecordsFromRecordset($rsold);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $deleteRows;
    }

    // Update record based on key values
    protected function editRow()
    {
        global $Security, $Language;
        $oldKeyFilter = $this->getRecordFilter();
        $filter = $this->applyUserIDFilters($oldKeyFilter);
        $conn = $this->getConnection();
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAssoc($sql);
        if (!$rsold) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
            $editRow = false; // Update Failed
        } else {
            // Save old values
            $this->loadDbValues($rsold);
            $rsnew = [];

            // berita_peminjaman
            $this->berita_peminjaman->setDbValueDef($rsnew, $this->berita_peminjaman->CurrentValue, "", $this->berita_peminjaman->ReadOnly);

            // id_buku
            $this->id_buku->setDbValueDef($rsnew, $this->id_buku->CurrentValue, 0, $this->id_buku->ReadOnly);

            // id_anggota
            if ($this->id_anggota->getSessionValue() != "") {
                $this->id_anggota->ReadOnly = true;
            }
            $this->id_anggota->setDbValueDef($rsnew, $this->id_anggota->CurrentValue, 0, $this->id_anggota->ReadOnly);

            // tgl_peminjaman
            $this->tgl_peminjaman->CurrentValue = CurrentDate();
            $this->tgl_peminjaman->setDbValueDef($rsnew, $this->tgl_peminjaman->CurrentValue, CurrentDate());

            // rencana_tgl_kembali
            $this->rencana_tgl_kembali->setDbValueDef($rsnew, UnFormatDateTime($this->rencana_tgl_kembali->CurrentValue, 0), CurrentDate(), $this->rencana_tgl_kembali->ReadOnly);

            // kondisi_buku_peminjaman
            $this->kondisi_buku_peminjaman->setDbValueDef($rsnew, $this->kondisi_buku_peminjaman->CurrentValue, "", $this->kondisi_buku_peminjaman->ReadOnly);

            // Check referential integrity for master table 'anggota'
            $validMasterRecord = true;
            $masterFilter = $this->sqlMasterFilter_anggota();
            $keyValue = $rsnew['id_anggota'] ?? $rsold['id_anggota'];
            if (strval($keyValue) != "") {
                $masterFilter = str_replace("@id_anggota@", AdjustSql($keyValue), $masterFilter);
            } else {
                $validMasterRecord = false;
            }
            if ($validMasterRecord) {
                $rsmaster = Container("anggota")->loadRs($masterFilter)->fetch();
                $validMasterRecord = $rsmaster !== false;
            }
            if (!$validMasterRecord) {
                $relatedRecordMsg = str_replace("%t", "anggota", $Language->phrase("RelatedRecordRequired"));
                $this->setFailureMessage($relatedRecordMsg);
                return false;
            }

            // Call Row Updating event
            $updateRow = $this->rowUpdating($rsold, $rsnew);
            if ($updateRow) {
                if (count($rsnew) > 0) {
                    $editRow = $this->update($rsnew, "", $rsold);
                } else {
                    $editRow = true; // No field to update
                }
                if ($editRow) {
                }
            } else {
                if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                    // Use the message, do nothing
                } elseif ($this->CancelMessage != "") {
                    $this->setFailureMessage($this->CancelMessage);
                    $this->CancelMessage = "";
                } else {
                    $this->setFailureMessage($Language->phrase("UpdateCancelled"));
                }
                $editRow = false;
            }
        }

        // Call Row_Updated event
        if ($editRow) {
            $this->rowUpdated($rsold, $rsnew);
        }

        // Clean upload path if any
        if ($editRow) {
        }

        // Write JSON for API request
        if (IsApi() && $editRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $editRow;
    }

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

        // Check if valid User ID
        $validUser = false;
        if ($Security->currentUserID() != "" && !EmptyValue($this->id_anggota->CurrentValue) && !$Security->isAdmin()) { // Non system admin
            $validUser = $Security->isValidUserID($this->id_anggota->CurrentValue);
            if (!$validUser) {
                $userIdMsg = str_replace("%c", CurrentUserID(), $Language->phrase("UnAuthorizedUserID"));
                $userIdMsg = str_replace("%u", $this->id_anggota->CurrentValue, $userIdMsg);
                $this->setFailureMessage($userIdMsg);
                return false;
            }
        }

        // Set up foreign key field value from Session
        if ($this->getCurrentMasterTable() == "anggota") {
            $this->id_anggota->CurrentValue = $this->id_anggota->getSessionValue();
        }

        // Check referential integrity for master table 'peminjaman'
        $validMasterRecord = true;
        $masterFilter = $this->sqlMasterFilter_anggota();
        if (strval($this->id_anggota->CurrentValue) != "") {
            $masterFilter = str_replace("@id_anggota@", AdjustSql($this->id_anggota->CurrentValue, "DB"), $masterFilter);
        } else {
            $validMasterRecord = false;
        }
        if ($validMasterRecord) {
            $rsmaster = Container("anggota")->loadRs($masterFilter)->fetch();
            $validMasterRecord = $rsmaster !== false;
        }
        if (!$validMasterRecord) {
            $relatedRecordMsg = str_replace("%t", "anggota", $Language->phrase("RelatedRecordRequired"));
            $this->setFailureMessage($relatedRecordMsg);
            return false;
        }
        $conn = $this->getConnection();

        // Load db values from rsold
        $this->loadDbValues($rsold);
        if ($rsold) {
        }
        $rsnew = [];

        // berita_peminjaman
        $this->berita_peminjaman->setDbValueDef($rsnew, $this->berita_peminjaman->CurrentValue, "", false);

        // id_buku
        $this->id_buku->setDbValueDef($rsnew, $this->id_buku->CurrentValue, 0, false);

        // id_anggota
        $this->id_anggota->setDbValueDef($rsnew, $this->id_anggota->CurrentValue, 0, false);

        // tgl_peminjaman
        $this->tgl_peminjaman->CurrentValue = CurrentDate();
        $this->tgl_peminjaman->setDbValueDef($rsnew, $this->tgl_peminjaman->CurrentValue, CurrentDate());

        // rencana_tgl_kembali
        $this->rencana_tgl_kembali->setDbValueDef($rsnew, UnFormatDateTime($this->rencana_tgl_kembali->CurrentValue, 0), CurrentDate(), false);

        // kondisi_buku_peminjaman
        $this->kondisi_buku_peminjaman->setDbValueDef($rsnew, $this->kondisi_buku_peminjaman->CurrentValue, "", false);

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("InsertCancelled"));
            }
            $addRow = false;
        }
        if ($addRow) {
            // Call Row Inserted event
            $this->rowInserted($rsold, $rsnew);
        }

        // Clean upload path if any
        if ($addRow) {
        }

        // Write JSON for API request
        if (IsApi() && $addRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $addRow;
    }

    // Show link optionally based on User ID
    protected function showOptionLink($id = "")
    {
        global $Security;
        if ($Security->isLoggedIn() && !$Security->isAdmin() && !$this->userIDAllow($id)) {
            return $Security->isValidUserID($this->id_anggota->CurrentValue);
        }
        return true;
    }

    // Set up master/detail based on QueryString
    protected function setupMasterParms()
    {
        // Hide foreign keys
        $masterTblVar = $this->getCurrentMasterTable();
        if ($masterTblVar == "anggota") {
            $masterTbl = Container("anggota");
            $this->id_anggota->Visible = false;
            if ($masterTbl->EventCancelled) {
                $this->EventCancelled = true;
            }
        }
        $this->DbMasterFilter = $this->getMasterFilter(); // Get master filter
        $this->DbDetailFilter = $this->getDetailFilter(); // Get detail filter
    }

    // Setup lookup options
    public function setupLookupOptions($fld)
    {
        if ($fld->Lookup !== null && $fld->Lookup->Options === null) {
            // Get default connection and filter
            $conn = $this->getConnection();
            $lookupFilter = "";

            // No need to check any more
            $fld->Lookup->Options = [];

            // Set up lookup SQL and connection
            switch ($fld->FieldVar) {
                case "x_id_buku":
                    break;
                case "x_id_anggota":
                    break;
                default:
                    $lookupFilter = "";
                    break;
            }

            // Always call to Lookup->getSql so that user can setup Lookup->Options in Lookup_Selecting server event
            $sql = $fld->Lookup->getSql(false, "", $lookupFilter, $this);

            // Set up lookup cache
            if ($fld->UseLookupCache && $sql != "" && count($fld->Lookup->Options) == 0) {
                $totalCnt = $this->getRecordCount($sql, $conn);
                if ($totalCnt > $fld->LookupCacheCount) { // Total count > cache count, do not cache
                    return;
                }
                $rows = $conn->executeQuery($sql)->fetchAll(\PDO::FETCH_BOTH);
                $ar = [];
                foreach ($rows as $row) {
                    $row = $fld->Lookup->renderViewRow($row);
                    $ar[strval($row[0])] = $row;
                }
                $fld->Lookup->Options = $ar;
            }
        }
    }

    // Page Load event
    public function pageLoad()
    {
        //Log("Page Load");
    }

    // Page Unload event
    public function pageUnload()
    {
        //Log("Page Unload");
    }

    // Page Redirecting event
    public function pageRedirecting(&$url)
    {
        // Example:
        //$url = "your URL";
    }

    // Message Showing event
    // $type = ''|'success'|'failure'|'warning'
    public function messageShowing(&$msg, $type)
    {
        if ($type == 'success') {
            //$msg = "your success message";
        } elseif ($type == 'failure') {
            //$msg = "your failure message";
        } elseif ($type == 'warning') {
            //$msg = "your warning message";
        } else {
            //$msg = "your message";
        }
    }

    // Page Render event
    public function pageRender()
    {
        //Log("Page Render");
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header)
    {
        // Example:
        //$header = "your header";
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer)
    {
        // Example:
        //$footer = "your footer";
    }

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in CustomError
        return true;
    }

    // ListOptions Load event
    public function listOptionsLoad()
    {
        // Example:
        //$opt = &$this->ListOptions->Add("new");
        //$opt->Header = "xxx";
        //$opt->OnLeft = true; // Link on left
        //$opt->MoveTo(0); // Move to first column
    }

    // ListOptions Rendering event
    public function listOptionsRendering()
    {
        //Container("DetailTableGrid")->DetailAdd = (...condition...); // Set to true or false conditionally
        //Container("DetailTableGrid")->DetailEdit = (...condition...); // Set to true or false conditionally
        //Container("DetailTableGrid")->DetailView = (...condition...); // Set to true or false conditionally
    }

    // ListOptions Rendered event
    public function listOptionsRendered()
    {
        // Example:
        //$this->ListOptions["new"]->Body = "xxx";
    }
}
