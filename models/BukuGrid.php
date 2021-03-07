<?php

namespace PHPMaker2021\perpus;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class BukuGrid extends Buku
{
    use MessagesTrait;

    // Page ID
    public $PageID = "grid";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'buku';

    // Page object name
    public $PageObjName = "BukuGrid";

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "fbukugrid";
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

        // Table object (buku)
        if (!isset($GLOBALS["buku"]) || get_class($GLOBALS["buku"]) == PROJECT_NAMESPACE . "buku") {
            $GLOBALS["buku"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();
        $this->AddUrl = "BukuAdd";

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'buku');
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
                $doc = new $class(Container("buku"));
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
            $key .= @$ar['id_buku'];
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
            $this->id_buku->Visible = false;
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
        $this->cover->setVisibility();
        $this->id_buku->Visible = false;
        $this->nama_buku->setVisibility();
        $this->pengarang->setVisibility();
        $this->penerbit->setVisibility();
        $this->kode_isbn->setVisibility();
        $this->rangkuman->Visible = false;
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
        $this->setupLookupOptions($this->pengarang);
        $this->setupLookupOptions($this->penerbit);

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
        if ($this->CurrentMode != "add" && $this->getMasterFilter() != "" && $this->getCurrentMasterTable() == "penerbit") {
            $masterTbl = Container("penerbit");
            $rsmaster = $masterTbl->loadRs($this->DbMasterFilter)->fetch(\PDO::FETCH_ASSOC);
            $this->MasterRecordExists = $rsmaster !== false;
            if (!$this->MasterRecordExists) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record found
                $this->terminate("PenerbitList"); // Return to master page
                return;
            } else {
                $masterTbl->loadListRowValues($rsmaster);
                $masterTbl->RowType = ROWTYPE_MASTER; // Master row
                $masterTbl->renderListRow();
            }
        }

        // Load master record
        if ($this->CurrentMode != "add" && $this->getMasterFilter() != "" && $this->getCurrentMasterTable() == "pengarang") {
            $masterTbl = Container("pengarang");
            $rsmaster = $masterTbl->loadRs($this->DbMasterFilter)->fetch(\PDO::FETCH_ASSOC);
            $this->MasterRecordExists = $rsmaster !== false;
            if (!$this->MasterRecordExists) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record found
                $this->terminate("PengarangList"); // Return to master page
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
                    $key .= $this->id_buku->CurrentValue;

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
        if (!EmptyValue($this->cover->Upload->Value)) {
            return false;
        }
        if ($CurrentForm->hasValue("x_nama_buku") && $CurrentForm->hasValue("o_nama_buku") && $this->nama_buku->CurrentValue != $this->nama_buku->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_pengarang") && $CurrentForm->hasValue("o_pengarang") && $this->pengarang->CurrentValue != $this->pengarang->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_penerbit") && $CurrentForm->hasValue("o_penerbit") && $this->penerbit->CurrentValue != $this->penerbit->OldValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_kode_isbn") && $CurrentForm->hasValue("o_kode_isbn") && $this->kode_isbn->CurrentValue != $this->kode_isbn->OldValue) {
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
        $this->cover->clearErrorMessage();
        $this->nama_buku->clearErrorMessage();
        $this->pengarang->clearErrorMessage();
        $this->penerbit->clearErrorMessage();
        $this->kode_isbn->clearErrorMessage();
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
            $this->DefaultSort = "`nama_buku` ASC,`pengarang` ASC,`penerbit` ASC";
            if ($this->getSqlOrderBy() != "") {
                $useDefaultSort = true;
                if ($this->nama_buku->getSort() != "") {
                    $useDefaultSort = false;
                }
                if ($this->pengarang->getSort() != "") {
                    $useDefaultSort = false;
                }
                if ($this->penerbit->getSort() != "") {
                    $useDefaultSort = false;
                }
                if ($useDefaultSort) {
                    $this->nama_buku->setSort("ASC");
                    $this->pengarang->setSort("ASC");
                    $this->penerbit->setSort("ASC");
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
                        $this->penerbit->setSessionValue("");
                        $this->pengarang->setSessionValue("");
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
            if ($Security->canView()) {
                $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . HtmlEncode(GetUrl($this->ViewUrl)) . "\">" . $Language->phrase("ViewLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "edit"
            $opt = $this->ListOptions["edit"];
            $editcaption = HtmlTitle($Language->phrase("EditLink"));
            if ($Security->canEdit()) {
                $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . HtmlTitle($Language->phrase("EditLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("EditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\">" . $Language->phrase("EditLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "copy"
            $opt = $this->ListOptions["copy"];
            $copycaption = HtmlTitle($Language->phrase("CopyLink"));
            if ($Security->canAdd()) {
                $opt->Body = "<a class=\"ew-row-link ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\">" . $Language->phrase("CopyLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "delete"
            $opt = $this->ListOptions["delete"];
            if ($Security->canDelete()) {
            $opt->Body = "<a class=\"ew-row-link ew-delete\"" . "" . " title=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->DeleteUrl)) . "\">" . $Language->phrase("DeleteLink") . "</a>";
            } else {
                $opt->Body = "";
            }
        } // End View mode
        if ($this->CurrentMode == "edit" && is_numeric($this->RowIndex) && $this->RowAction != "delete") {
            if ($keyName != "") {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $keyName . "\" id=\"" . $keyName . "\" value=\"" . $this->id_buku->CurrentValue . "\">";
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
    }

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
        $this->cover->Upload->Index = $CurrentForm->Index;
        $this->cover->Upload->uploadFile();
        $this->cover->CurrentValue = $this->cover->Upload->FileName;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->cover->Upload->DbValue = null;
        $this->cover->OldValue = $this->cover->Upload->DbValue;
        $this->cover->Upload->Index = $this->RowIndex;
        $this->id_buku->CurrentValue = null;
        $this->id_buku->OldValue = $this->id_buku->CurrentValue;
        $this->nama_buku->CurrentValue = null;
        $this->nama_buku->OldValue = $this->nama_buku->CurrentValue;
        $this->pengarang->CurrentValue = null;
        $this->pengarang->OldValue = $this->pengarang->CurrentValue;
        $this->penerbit->CurrentValue = null;
        $this->penerbit->OldValue = $this->penerbit->CurrentValue;
        $this->kode_isbn->CurrentValue = null;
        $this->kode_isbn->OldValue = $this->kode_isbn->CurrentValue;
        $this->rangkuman->CurrentValue = null;
        $this->rangkuman->OldValue = $this->rangkuman->CurrentValue;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $CurrentForm->FormName = $this->FormName;

        // Check field name 'nama_buku' first before field var 'x_nama_buku'
        $val = $CurrentForm->hasValue("nama_buku") ? $CurrentForm->getValue("nama_buku") : $CurrentForm->getValue("x_nama_buku");
        if (!$this->nama_buku->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nama_buku->Visible = false; // Disable update for API request
            } else {
                $this->nama_buku->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_nama_buku")) {
            $this->nama_buku->setOldValue($CurrentForm->getValue("o_nama_buku"));
        }

        // Check field name 'pengarang' first before field var 'x_pengarang'
        $val = $CurrentForm->hasValue("pengarang") ? $CurrentForm->getValue("pengarang") : $CurrentForm->getValue("x_pengarang");
        if (!$this->pengarang->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->pengarang->Visible = false; // Disable update for API request
            } else {
                $this->pengarang->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_pengarang")) {
            $this->pengarang->setOldValue($CurrentForm->getValue("o_pengarang"));
        }

        // Check field name 'penerbit' first before field var 'x_penerbit'
        $val = $CurrentForm->hasValue("penerbit") ? $CurrentForm->getValue("penerbit") : $CurrentForm->getValue("x_penerbit");
        if (!$this->penerbit->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->penerbit->Visible = false; // Disable update for API request
            } else {
                $this->penerbit->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_penerbit")) {
            $this->penerbit->setOldValue($CurrentForm->getValue("o_penerbit"));
        }

        // Check field name 'kode_isbn' first before field var 'x_kode_isbn'
        $val = $CurrentForm->hasValue("kode_isbn") ? $CurrentForm->getValue("kode_isbn") : $CurrentForm->getValue("x_kode_isbn");
        if (!$this->kode_isbn->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->kode_isbn->Visible = false; // Disable update for API request
            } else {
                $this->kode_isbn->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_kode_isbn")) {
            $this->kode_isbn->setOldValue($CurrentForm->getValue("o_kode_isbn"));
        }

        // Check field name 'id_buku' first before field var 'x_id_buku'
        $val = $CurrentForm->hasValue("id_buku") ? $CurrentForm->getValue("id_buku") : $CurrentForm->getValue("x_id_buku");
        if (!$this->id_buku->IsDetailKey && !$this->isGridAdd() && !$this->isAdd()) {
            $this->id_buku->setFormValue($val);
        }
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        if (!$this->isGridAdd() && !$this->isAdd()) {
            $this->id_buku->CurrentValue = $this->id_buku->FormValue;
        }
        $this->nama_buku->CurrentValue = $this->nama_buku->FormValue;
        $this->pengarang->CurrentValue = $this->pengarang->FormValue;
        $this->penerbit->CurrentValue = $this->penerbit->FormValue;
        $this->kode_isbn->CurrentValue = $this->kode_isbn->FormValue;
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
        $this->cover->Upload->DbValue = $row['cover'];
        $this->cover->setDbValue($this->cover->Upload->DbValue);
        $this->cover->Upload->Index = $this->RowIndex;
        $this->id_buku->setDbValue($row['id_buku']);
        $this->nama_buku->setDbValue($row['nama_buku']);
        $this->pengarang->setDbValue($row['pengarang']);
        if (array_key_exists('EV__pengarang', $row)) {
            $this->pengarang->VirtualValue = $row['EV__pengarang']; // Set up virtual field value
        } else {
            $this->pengarang->VirtualValue = ""; // Clear value
        }
        $this->penerbit->setDbValue($row['penerbit']);
        if (array_key_exists('EV__penerbit', $row)) {
            $this->penerbit->VirtualValue = $row['EV__penerbit']; // Set up virtual field value
        } else {
            $this->penerbit->VirtualValue = ""; // Clear value
        }
        $this->kode_isbn->setDbValue($row['kode_isbn']);
        $this->rangkuman->setDbValue($row['rangkuman']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $this->loadDefaultValues();
        $row = [];
        $row['cover'] = $this->cover->Upload->DbValue;
        $row['id_buku'] = $this->id_buku->CurrentValue;
        $row['nama_buku'] = $this->nama_buku->CurrentValue;
        $row['pengarang'] = $this->pengarang->CurrentValue;
        $row['penerbit'] = $this->penerbit->CurrentValue;
        $row['kode_isbn'] = $this->kode_isbn->CurrentValue;
        $row['rangkuman'] = $this->rangkuman->CurrentValue;
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

        // cover

        // id_buku

        // nama_buku

        // pengarang

        // penerbit

        // kode_isbn

        // rangkuman
        if ($this->RowType == ROWTYPE_VIEW) {
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
                $this->cover->LinkAttrs["data-rel"] = "buku_x" . $this->RowCount . "_cover";
                $this->cover->LinkAttrs->appendClass("ew-lightbox");
            }

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
        } elseif ($this->RowType == ROWTYPE_ADD) {
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
            if (is_numeric($this->RowIndex)) {
                RenderUploadField($this->cover, $this->RowIndex);
            }

            // nama_buku
            $this->nama_buku->EditAttrs["class"] = "form-control";
            $this->nama_buku->EditCustomAttributes = "";
            if (!$this->nama_buku->Raw) {
                $this->nama_buku->CurrentValue = HtmlDecode($this->nama_buku->CurrentValue);
            }
            $this->nama_buku->EditValue = HtmlEncode($this->nama_buku->CurrentValue);
            $this->nama_buku->PlaceHolder = RemoveHtml($this->nama_buku->caption());

            // pengarang
            $this->pengarang->EditAttrs["class"] = "form-control";
            $this->pengarang->EditCustomAttributes = "";
            if ($this->pengarang->getSessionValue() != "") {
                $this->pengarang->CurrentValue = GetForeignKeyValue($this->pengarang->getSessionValue());
                $this->pengarang->OldValue = $this->pengarang->CurrentValue;
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
                $curVal = trim(strval($this->pengarang->CurrentValue));
                if ($curVal != "") {
                    $this->pengarang->ViewValue = $this->pengarang->lookupCacheOption($curVal);
                } else {
                    $this->pengarang->ViewValue = $this->pengarang->Lookup !== null && is_array($this->pengarang->Lookup->Options) ? $curVal : null;
                }
                if ($this->pengarang->ViewValue !== null) { // Load from cache
                    $this->pengarang->EditValue = array_values($this->pengarang->Lookup->Options);
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = "`id_pengarang`" . SearchString("=", $this->pengarang->CurrentValue, DATATYPE_NUMBER, "");
                    }
                    $sqlWrk = $this->pengarang->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    $arwrk = $rswrk;
                    $this->pengarang->EditValue = $arwrk;
                }
                $this->pengarang->PlaceHolder = RemoveHtml($this->pengarang->caption());
            }

            // penerbit
            $this->penerbit->EditAttrs["class"] = "form-control";
            $this->penerbit->EditCustomAttributes = "";
            if ($this->penerbit->getSessionValue() != "") {
                $this->penerbit->CurrentValue = GetForeignKeyValue($this->penerbit->getSessionValue());
                $this->penerbit->OldValue = $this->penerbit->CurrentValue;
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
                $curVal = trim(strval($this->penerbit->CurrentValue));
                if ($curVal != "") {
                    $this->penerbit->ViewValue = $this->penerbit->lookupCacheOption($curVal);
                } else {
                    $this->penerbit->ViewValue = $this->penerbit->Lookup !== null && is_array($this->penerbit->Lookup->Options) ? $curVal : null;
                }
                if ($this->penerbit->ViewValue !== null) { // Load from cache
                    $this->penerbit->EditValue = array_values($this->penerbit->Lookup->Options);
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = "`id_penerbit`" . SearchString("=", $this->penerbit->CurrentValue, DATATYPE_NUMBER, "");
                    }
                    $sqlWrk = $this->penerbit->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    $arwrk = $rswrk;
                    $this->penerbit->EditValue = $arwrk;
                }
                $this->penerbit->PlaceHolder = RemoveHtml($this->penerbit->caption());
            }

            // kode_isbn
            $this->kode_isbn->EditAttrs["class"] = "form-control";
            $this->kode_isbn->EditCustomAttributes = "";
            if (!$this->kode_isbn->Raw) {
                $this->kode_isbn->CurrentValue = HtmlDecode($this->kode_isbn->CurrentValue);
            }
            $this->kode_isbn->EditValue = HtmlEncode($this->kode_isbn->CurrentValue);
            $this->kode_isbn->PlaceHolder = RemoveHtml($this->kode_isbn->caption());

            // Add refer script

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

            // nama_buku
            $this->nama_buku->LinkCustomAttributes = "";
            $this->nama_buku->HrefValue = "";

            // pengarang
            $this->pengarang->LinkCustomAttributes = "";
            $this->pengarang->HrefValue = "";

            // penerbit
            $this->penerbit->LinkCustomAttributes = "";
            $this->penerbit->HrefValue = "";

            // kode_isbn
            $this->kode_isbn->LinkCustomAttributes = "";
            $this->kode_isbn->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
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
            if (is_numeric($this->RowIndex)) {
                RenderUploadField($this->cover, $this->RowIndex);
            }

            // nama_buku
            $this->nama_buku->EditAttrs["class"] = "form-control";
            $this->nama_buku->EditCustomAttributes = "";
            if (!$this->nama_buku->Raw) {
                $this->nama_buku->CurrentValue = HtmlDecode($this->nama_buku->CurrentValue);
            }
            $this->nama_buku->EditValue = HtmlEncode($this->nama_buku->CurrentValue);
            $this->nama_buku->PlaceHolder = RemoveHtml($this->nama_buku->caption());

            // pengarang
            $this->pengarang->EditAttrs["class"] = "form-control";
            $this->pengarang->EditCustomAttributes = "";
            if ($this->pengarang->getSessionValue() != "") {
                $this->pengarang->CurrentValue = GetForeignKeyValue($this->pengarang->getSessionValue());
                $this->pengarang->OldValue = $this->pengarang->CurrentValue;
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
                $curVal = trim(strval($this->pengarang->CurrentValue));
                if ($curVal != "") {
                    $this->pengarang->ViewValue = $this->pengarang->lookupCacheOption($curVal);
                } else {
                    $this->pengarang->ViewValue = $this->pengarang->Lookup !== null && is_array($this->pengarang->Lookup->Options) ? $curVal : null;
                }
                if ($this->pengarang->ViewValue !== null) { // Load from cache
                    $this->pengarang->EditValue = array_values($this->pengarang->Lookup->Options);
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = "`id_pengarang`" . SearchString("=", $this->pengarang->CurrentValue, DATATYPE_NUMBER, "");
                    }
                    $sqlWrk = $this->pengarang->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    $arwrk = $rswrk;
                    $this->pengarang->EditValue = $arwrk;
                }
                $this->pengarang->PlaceHolder = RemoveHtml($this->pengarang->caption());
            }

            // penerbit
            $this->penerbit->EditAttrs["class"] = "form-control";
            $this->penerbit->EditCustomAttributes = "";
            if ($this->penerbit->getSessionValue() != "") {
                $this->penerbit->CurrentValue = GetForeignKeyValue($this->penerbit->getSessionValue());
                $this->penerbit->OldValue = $this->penerbit->CurrentValue;
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
                $curVal = trim(strval($this->penerbit->CurrentValue));
                if ($curVal != "") {
                    $this->penerbit->ViewValue = $this->penerbit->lookupCacheOption($curVal);
                } else {
                    $this->penerbit->ViewValue = $this->penerbit->Lookup !== null && is_array($this->penerbit->Lookup->Options) ? $curVal : null;
                }
                if ($this->penerbit->ViewValue !== null) { // Load from cache
                    $this->penerbit->EditValue = array_values($this->penerbit->Lookup->Options);
                } else { // Lookup from database
                    if ($curVal == "") {
                        $filterWrk = "0=1";
                    } else {
                        $filterWrk = "`id_penerbit`" . SearchString("=", $this->penerbit->CurrentValue, DATATYPE_NUMBER, "");
                    }
                    $sqlWrk = $this->penerbit->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                    $rswrk = Conn()->executeQuery($sqlWrk)->fetchAll(\PDO::FETCH_BOTH);
                    $ari = count($rswrk);
                    $arwrk = $rswrk;
                    $this->penerbit->EditValue = $arwrk;
                }
                $this->penerbit->PlaceHolder = RemoveHtml($this->penerbit->caption());
            }

            // kode_isbn
            $this->kode_isbn->EditAttrs["class"] = "form-control";
            $this->kode_isbn->EditCustomAttributes = "";
            if (!$this->kode_isbn->Raw) {
                $this->kode_isbn->CurrentValue = HtmlDecode($this->kode_isbn->CurrentValue);
            }
            $this->kode_isbn->EditValue = HtmlEncode($this->kode_isbn->CurrentValue);
            $this->kode_isbn->PlaceHolder = RemoveHtml($this->kode_isbn->caption());

            // Edit refer script

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

            // nama_buku
            $this->nama_buku->LinkCustomAttributes = "";
            $this->nama_buku->HrefValue = "";

            // pengarang
            $this->pengarang->LinkCustomAttributes = "";
            $this->pengarang->HrefValue = "";

            // penerbit
            $this->penerbit->LinkCustomAttributes = "";
            $this->penerbit->HrefValue = "";

            // kode_isbn
            $this->kode_isbn->LinkCustomAttributes = "";
            $this->kode_isbn->HrefValue = "";
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
        if ($this->cover->Required) {
            if ($this->cover->Upload->FileName == "" && !$this->cover->Upload->KeepFile) {
                $this->cover->addErrorMessage(str_replace("%s", $this->cover->caption(), $this->cover->RequiredErrorMessage));
            }
        }
        if ($this->nama_buku->Required) {
            if (!$this->nama_buku->IsDetailKey && EmptyValue($this->nama_buku->FormValue)) {
                $this->nama_buku->addErrorMessage(str_replace("%s", $this->nama_buku->caption(), $this->nama_buku->RequiredErrorMessage));
            }
        }
        if ($this->pengarang->Required) {
            if (!$this->pengarang->IsDetailKey && EmptyValue($this->pengarang->FormValue)) {
                $this->pengarang->addErrorMessage(str_replace("%s", $this->pengarang->caption(), $this->pengarang->RequiredErrorMessage));
            }
        }
        if ($this->penerbit->Required) {
            if (!$this->penerbit->IsDetailKey && EmptyValue($this->penerbit->FormValue)) {
                $this->penerbit->addErrorMessage(str_replace("%s", $this->penerbit->caption(), $this->penerbit->RequiredErrorMessage));
            }
        }
        if ($this->kode_isbn->Required) {
            if (!$this->kode_isbn->IsDetailKey && EmptyValue($this->kode_isbn->FormValue)) {
                $this->kode_isbn->addErrorMessage(str_replace("%s", $this->kode_isbn->caption(), $this->kode_isbn->RequiredErrorMessage));
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
                $thisKey .= $row['id_buku'];
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

            // cover
            if ($this->cover->Visible && !$this->cover->ReadOnly && !$this->cover->Upload->KeepFile) {
                $this->cover->Upload->DbValue = $rsold['cover']; // Get original value
                if ($this->cover->Upload->FileName == "") {
                    $rsnew['cover'] = null;
                } else {
                    $rsnew['cover'] = $this->cover->Upload->FileName;
                }
                $this->cover->ImageWidth = 250; // Resize width
                $this->cover->ImageHeight = 350; // Resize height
            }

            // nama_buku
            $this->nama_buku->setDbValueDef($rsnew, $this->nama_buku->CurrentValue, "", $this->nama_buku->ReadOnly);

            // pengarang
            if ($this->pengarang->getSessionValue() != "") {
                $this->pengarang->ReadOnly = true;
            }
            $this->pengarang->setDbValueDef($rsnew, $this->pengarang->CurrentValue, 0, $this->pengarang->ReadOnly);

            // penerbit
            if ($this->penerbit->getSessionValue() != "") {
                $this->penerbit->ReadOnly = true;
            }
            $this->penerbit->setDbValueDef($rsnew, $this->penerbit->CurrentValue, 0, $this->penerbit->ReadOnly);

            // kode_isbn
            $this->kode_isbn->setDbValueDef($rsnew, $this->kode_isbn->CurrentValue, "", $this->kode_isbn->ReadOnly);
            if ($this->cover->Visible && !$this->cover->Upload->KeepFile) {
                $oldFiles = EmptyValue($this->cover->Upload->DbValue) ? [] : [$this->cover->htmlDecode($this->cover->Upload->DbValue)];
                if (!EmptyValue($this->cover->Upload->FileName)) {
                    $newFiles = [$this->cover->Upload->FileName];
                    $NewFileCount = count($newFiles);
                    for ($i = 0; $i < $NewFileCount; $i++) {
                        if ($newFiles[$i] != "") {
                            $file = $newFiles[$i];
                            $tempPath = UploadTempPath($this->cover, $this->cover->Upload->Index);
                            if (file_exists($tempPath . $file)) {
                                if (Config("DELETE_UPLOADED_FILES")) {
                                    $oldFileFound = false;
                                    $oldFileCount = count($oldFiles);
                                    for ($j = 0; $j < $oldFileCount; $j++) {
                                        $oldFile = $oldFiles[$j];
                                        if ($oldFile == $file) { // Old file found, no need to delete anymore
                                            array_splice($oldFiles, $j, 1);
                                            $oldFileFound = true;
                                            break;
                                        }
                                    }
                                    if ($oldFileFound) { // No need to check if file exists further
                                        continue;
                                    }
                                }
                                $file1 = UniqueFilename($this->cover->physicalUploadPath(), $file); // Get new file name
                                if ($file1 != $file) { // Rename temp file
                                    while (file_exists($tempPath . $file1) || file_exists($this->cover->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                        $file1 = UniqueFilename([$this->cover->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                    }
                                    rename($tempPath . $file, $tempPath . $file1);
                                    $newFiles[$i] = $file1;
                                }
                            }
                        }
                    }
                    $this->cover->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                    $this->cover->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                    $this->cover->setDbValueDef($rsnew, $this->cover->Upload->FileName, "", $this->cover->ReadOnly);
                }
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
                    if ($this->cover->Visible && !$this->cover->Upload->KeepFile) {
                        $oldFiles = EmptyValue($this->cover->Upload->DbValue) ? [] : [$this->cover->htmlDecode($this->cover->Upload->DbValue)];
                        if (!EmptyValue($this->cover->Upload->FileName)) {
                            $newFiles = [$this->cover->Upload->FileName];
                            $newFiles2 = [$this->cover->htmlDecode($rsnew['cover'])];
                            $newFileCount = count($newFiles);
                            for ($i = 0; $i < $newFileCount; $i++) {
                                if ($newFiles[$i] != "") {
                                    $file = UploadTempPath($this->cover, $this->cover->Upload->Index) . $newFiles[$i];
                                    if (file_exists($file)) {
                                        if (@$newFiles2[$i] != "") { // Use correct file name
                                            $newFiles[$i] = $newFiles2[$i];
                                        }
                                        if (!$this->cover->Upload->ResizeAndSaveToFile($this->cover->ImageWidth, $this->cover->ImageHeight, 100, $newFiles[$i], true, $i)) {
                                            $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                            return false;
                                        }
                                    }
                                }
                            }
                        } else {
                            $newFiles = [];
                        }
                        if (Config("DELETE_UPLOADED_FILES")) {
                            foreach ($oldFiles as $oldFile) {
                                if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                    @unlink($this->cover->oldPhysicalUploadPath() . $oldFile);
                                }
                            }
                        }
                    }
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
            // cover
            CleanUploadTempPath($this->cover, $this->cover->Upload->Index);
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

        // Set up foreign key field value from Session
        if ($this->getCurrentMasterTable() == "penerbit") {
            $this->penerbit->CurrentValue = $this->penerbit->getSessionValue();
        }
        if ($this->getCurrentMasterTable() == "pengarang") {
            $this->pengarang->CurrentValue = $this->pengarang->getSessionValue();
        }
        $conn = $this->getConnection();

        // Load db values from rsold
        $this->loadDbValues($rsold);
        if ($rsold) {
        }
        $rsnew = [];

        // cover
        if ($this->cover->Visible && !$this->cover->Upload->KeepFile) {
            $this->cover->Upload->DbValue = ""; // No need to delete old file
            if ($this->cover->Upload->FileName == "") {
                $rsnew['cover'] = null;
            } else {
                $rsnew['cover'] = $this->cover->Upload->FileName;
            }
            $this->cover->ImageWidth = 250; // Resize width
            $this->cover->ImageHeight = 350; // Resize height
        }

        // nama_buku
        $this->nama_buku->setDbValueDef($rsnew, $this->nama_buku->CurrentValue, "", false);

        // pengarang
        $this->pengarang->setDbValueDef($rsnew, $this->pengarang->CurrentValue, 0, false);

        // penerbit
        $this->penerbit->setDbValueDef($rsnew, $this->penerbit->CurrentValue, 0, false);

        // kode_isbn
        $this->kode_isbn->setDbValueDef($rsnew, $this->kode_isbn->CurrentValue, "", false);
        if ($this->cover->Visible && !$this->cover->Upload->KeepFile) {
            $oldFiles = EmptyValue($this->cover->Upload->DbValue) ? [] : [$this->cover->htmlDecode($this->cover->Upload->DbValue)];
            if (!EmptyValue($this->cover->Upload->FileName)) {
                $newFiles = [$this->cover->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->cover, $this->cover->Upload->Index);
                        if (file_exists($tempPath . $file)) {
                            if (Config("DELETE_UPLOADED_FILES")) {
                                $oldFileFound = false;
                                $oldFileCount = count($oldFiles);
                                for ($j = 0; $j < $oldFileCount; $j++) {
                                    $oldFile = $oldFiles[$j];
                                    if ($oldFile == $file) { // Old file found, no need to delete anymore
                                        array_splice($oldFiles, $j, 1);
                                        $oldFileFound = true;
                                        break;
                                    }
                                }
                                if ($oldFileFound) { // No need to check if file exists further
                                    continue;
                                }
                            }
                            $file1 = UniqueFilename($this->cover->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->cover->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->cover->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->cover->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->cover->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->cover->setDbValueDef($rsnew, $this->cover->Upload->FileName, "", false);
            }
        }

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
                if ($this->cover->Visible && !$this->cover->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->cover->Upload->DbValue) ? [] : [$this->cover->htmlDecode($this->cover->Upload->DbValue)];
                    if (!EmptyValue($this->cover->Upload->FileName)) {
                        $newFiles = [$this->cover->Upload->FileName];
                        $newFiles2 = [$this->cover->htmlDecode($rsnew['cover'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->cover, $this->cover->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->cover->Upload->ResizeAndSaveToFile($this->cover->ImageWidth, $this->cover->ImageHeight, 100, $newFiles[$i], true, $i)) {
                                        $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                        return false;
                                    }
                                }
                            }
                        }
                    } else {
                        $newFiles = [];
                    }
                    if (Config("DELETE_UPLOADED_FILES")) {
                        foreach ($oldFiles as $oldFile) {
                            if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                @unlink($this->cover->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
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
            // cover
            CleanUploadTempPath($this->cover, $this->cover->Upload->Index);
        }

        // Write JSON for API request
        if (IsApi() && $addRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $addRow;
    }

    // Set up master/detail based on QueryString
    protected function setupMasterParms()
    {
        // Hide foreign keys
        $masterTblVar = $this->getCurrentMasterTable();
        if ($masterTblVar == "penerbit") {
            $masterTbl = Container("penerbit");
            $this->penerbit->Visible = false;
            if ($masterTbl->EventCancelled) {
                $this->EventCancelled = true;
            }
        }
        if ($masterTblVar == "pengarang") {
            $masterTbl = Container("pengarang");
            $this->pengarang->Visible = false;
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
                case "x_pengarang":
                    break;
                case "x_penerbit":
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