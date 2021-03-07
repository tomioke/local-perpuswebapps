<?php

namespace PHPMaker2021\perpus;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class BukuEdit extends Buku
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'buku';

    // Page object name
    public $PageObjName = "BukuEdit";

    // Rendering View
    public $RenderingView = false;

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
        $GLOBALS["Page"] = &$this;
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

         // Page Unload event
        if (method_exists($this, "pageUnload")) {
            $this->pageUnload();
        }

        // Global Page Unloaded event (in userfn*.php)
        Page_Unloaded();

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
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Close connection
        CloseConnections();

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

            // Handle modal response
            if ($this->IsModal) { // Show as modal
                $row = ["url" => GetUrl($url), "modal" => "1"];
                $pageName = GetPageName($url);
                if ($pageName != $this->getListUrl()) { // Not List page
                    $row["caption"] = $this->getModalCaption($pageName);
                    if ($pageName == "BukuView") {
                        $row["view"] = "1";
                    }
                } else { // List page should not be shown as modal => error
                    $row["error"] = $this->getFailureMessage();
                    $this->clearFailureMessage();
                }
                WriteJson($row);
            } else {
                SaveDebugMessage();
                Redirect(GetUrl($url));
            }
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
    public $FormClassName = "ew-horizontal ew-form ew-edit-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter;
    public $DbDetailFilter;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm,
            $SkipHeaderFooter;

        // Is modal
        $this->IsModal = Param("modal") == "1";

        // Create form object
        $CurrentForm = new HttpForm();
        $this->CurrentAction = Param("action"); // Set up current action
        $this->cover->setVisibility();
        $this->id_buku->setVisibility();
        $this->nama_buku->setVisibility();
        $this->pengarang->setVisibility();
        $this->penerbit->setVisibility();
        $this->kode_isbn->setVisibility();
        $this->rangkuman->setVisibility();
        $this->hideFieldsForAddEdit();

        // Do not use lookup cache
        $this->setUseLookupCache(false);

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->pengarang);
        $this->setupLookupOptions($this->penerbit);

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $this->FormClassName = "ew-form ew-edit-form ew-horizontal";
        $loaded = false;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load key values
            $loaded = true;
            if (($keyValue = Get("id_buku") ?? Key(0) ?? Route(2)) !== null) {
                $this->id_buku->setQueryStringValue($keyValue);
                $this->id_buku->setOldValue($this->id_buku->QueryStringValue);
            } elseif (Post("id_buku") !== null) {
                $this->id_buku->setFormValue(Post("id_buku"));
                $this->id_buku->setOldValue($this->id_buku->FormValue);
            } else {
                $loaded = false; // Unable to load key
            }

            // Load record
            if ($loaded) {
                $loaded = $this->loadRow();
            }
            if (!$loaded) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                $this->terminate();
                return;
            }
            $this->CurrentAction = "update"; // Update record directly
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $postBack = true;
        } else {
            if (Post("action") !== null) {
                $this->CurrentAction = Post("action"); // Get action code
                if (!$this->isShow()) { // Not reload record, handle as postback
                    $postBack = true;
                }

                // Get key from Form
                $this->setKey(Post($this->OldKeyName));
            } else {
                $this->CurrentAction = "show"; // Default action is display

                // Load key from QueryString
                $loadByQuery = false;
                if (($keyValue = Get("id_buku") ?? Route("id_buku")) !== null) {
                    $this->id_buku->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->id_buku->CurrentValue = null;
                }
            }

            // Set up master detail parameters
            $this->setupMasterParms();

            // Load recordset
            if ($this->isShow()) {
                // Load current record
                $loaded = $this->loadRow();
                $this->OldKey = $loaded ? $this->getKey(true) : ""; // Get from CurrentValue
            }
        }

        // Process form if post back
        if ($postBack) {
            $this->loadFormValues(); // Get form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues();
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = ""; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "show": // Get a record to display
                if (!$loaded) { // Load record based on key
                    if ($this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                    }
                    $this->terminate("BukuList"); // No matching record, return to list
                    return;
                }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "BukuList") {
                    $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                }
                $this->SendEmail = true; // Send email on update success
                if ($this->editRow()) { // Update record based on key
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Update success
                    }
                    if (IsApi()) {
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl); // Return to caller
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } elseif ($this->getFailureMessage() == $Language->phrase("NoRecord")) {
                    $this->terminate($returnUrl); // Return to caller
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Restore form values if update failed
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render the record
        $this->RowType = ROWTYPE_EDIT; // Render as Edit
        $this->resetAttributes();
        $this->renderRow();

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

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
        $this->cover->Upload->Index = $CurrentForm->Index;
        $this->cover->Upload->uploadFile();
        $this->cover->CurrentValue = $this->cover->Upload->FileName;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;

        // Check field name 'id_buku' first before field var 'x_id_buku'
        $val = $CurrentForm->hasValue("id_buku") ? $CurrentForm->getValue("id_buku") : $CurrentForm->getValue("x_id_buku");
        if (!$this->id_buku->IsDetailKey) {
            $this->id_buku->setFormValue($val);
        }

        // Check field name 'nama_buku' first before field var 'x_nama_buku'
        $val = $CurrentForm->hasValue("nama_buku") ? $CurrentForm->getValue("nama_buku") : $CurrentForm->getValue("x_nama_buku");
        if (!$this->nama_buku->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->nama_buku->Visible = false; // Disable update for API request
            } else {
                $this->nama_buku->setFormValue($val);
            }
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

        // Check field name 'penerbit' first before field var 'x_penerbit'
        $val = $CurrentForm->hasValue("penerbit") ? $CurrentForm->getValue("penerbit") : $CurrentForm->getValue("x_penerbit");
        if (!$this->penerbit->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->penerbit->Visible = false; // Disable update for API request
            } else {
                $this->penerbit->setFormValue($val);
            }
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

        // Check field name 'rangkuman' first before field var 'x_rangkuman'
        $val = $CurrentForm->hasValue("rangkuman") ? $CurrentForm->getValue("rangkuman") : $CurrentForm->getValue("x_rangkuman");
        if (!$this->rangkuman->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->rangkuman->Visible = false; // Disable update for API request
            } else {
                $this->rangkuman->setFormValue($val);
            }
        }
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->id_buku->CurrentValue = $this->id_buku->FormValue;
        $this->nama_buku->CurrentValue = $this->nama_buku->FormValue;
        $this->pengarang->CurrentValue = $this->pengarang->FormValue;
        $this->penerbit->CurrentValue = $this->penerbit->FormValue;
        $this->kode_isbn->CurrentValue = $this->kode_isbn->FormValue;
        $this->rangkuman->CurrentValue = $this->rangkuman->FormValue;
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
        $row = [];
        $row['cover'] = null;
        $row['id_buku'] = null;
        $row['nama_buku'] = null;
        $row['pengarang'] = null;
        $row['penerbit'] = null;
        $row['kode_isbn'] = null;
        $row['rangkuman'] = null;
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
            if ($this->isShow()) {
                RenderUploadField($this->cover);
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
            $this->nama_buku->EditValue = HtmlEncode($this->nama_buku->CurrentValue);
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

            // rangkuman
            $this->rangkuman->EditAttrs["class"] = "form-control";
            $this->rangkuman->EditCustomAttributes = "";
            $this->rangkuman->EditValue = HtmlEncode($this->rangkuman->CurrentValue);
            $this->rangkuman->PlaceHolder = RemoveHtml($this->rangkuman->caption());

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

            // id_buku
            $this->id_buku->LinkCustomAttributes = "";
            $this->id_buku->HrefValue = "";

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

            // rangkuman
            $this->rangkuman->LinkCustomAttributes = "";
            $this->rangkuman->HrefValue = "";
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
        if ($this->id_buku->Required) {
            if (!$this->id_buku->IsDetailKey && EmptyValue($this->id_buku->FormValue)) {
                $this->id_buku->addErrorMessage(str_replace("%s", $this->id_buku->caption(), $this->id_buku->RequiredErrorMessage));
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
        if ($this->rangkuman->Required) {
            if (!$this->rangkuman->IsDetailKey && EmptyValue($this->rangkuman->FormValue)) {
                $this->rangkuman->addErrorMessage(str_replace("%s", $this->rangkuman->caption(), $this->rangkuman->RequiredErrorMessage));
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

            // rangkuman
            $this->rangkuman->setDbValueDef($rsnew, $this->rangkuman->CurrentValue, "", $this->rangkuman->ReadOnly);
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

    // Set up master/detail based on QueryString
    protected function setupMasterParms()
    {
        $validMaster = false;
        // Get the keys for master table
        if (($master = Get(Config("TABLE_SHOW_MASTER"), Get(Config("TABLE_MASTER")))) !== null) {
            $masterTblVar = $master;
            if ($masterTblVar == "") {
                $validMaster = true;
                $this->DbMasterFilter = "";
                $this->DbDetailFilter = "";
            }
            if ($masterTblVar == "penerbit") {
                $validMaster = true;
                $masterTbl = Container("penerbit");
                if (($parm = Get("fk_id_penerbit", Get("penerbit"))) !== null) {
                    $masterTbl->id_penerbit->setQueryStringValue($parm);
                    $this->penerbit->setQueryStringValue($masterTbl->id_penerbit->QueryStringValue);
                    $this->penerbit->setSessionValue($this->penerbit->QueryStringValue);
                    if (!is_numeric($masterTbl->id_penerbit->QueryStringValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
            if ($masterTblVar == "pengarang") {
                $validMaster = true;
                $masterTbl = Container("pengarang");
                if (($parm = Get("fk_id_pengarang", Get("pengarang"))) !== null) {
                    $masterTbl->id_pengarang->setQueryStringValue($parm);
                    $this->pengarang->setQueryStringValue($masterTbl->id_pengarang->QueryStringValue);
                    $this->pengarang->setSessionValue($this->pengarang->QueryStringValue);
                    if (!is_numeric($masterTbl->id_pengarang->QueryStringValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
        } elseif (($master = Post(Config("TABLE_SHOW_MASTER"), Post(Config("TABLE_MASTER")))) !== null) {
            $masterTblVar = $master;
            if ($masterTblVar == "") {
                    $validMaster = true;
                    $this->DbMasterFilter = "";
                    $this->DbDetailFilter = "";
            }
            if ($masterTblVar == "penerbit") {
                $validMaster = true;
                $masterTbl = Container("penerbit");
                if (($parm = Post("fk_id_penerbit", Post("penerbit"))) !== null) {
                    $masterTbl->id_penerbit->setFormValue($parm);
                    $this->penerbit->setFormValue($masterTbl->id_penerbit->FormValue);
                    $this->penerbit->setSessionValue($this->penerbit->FormValue);
                    if (!is_numeric($masterTbl->id_penerbit->FormValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
            if ($masterTblVar == "pengarang") {
                $validMaster = true;
                $masterTbl = Container("pengarang");
                if (($parm = Post("fk_id_pengarang", Post("pengarang"))) !== null) {
                    $masterTbl->id_pengarang->setFormValue($parm);
                    $this->pengarang->setFormValue($masterTbl->id_pengarang->FormValue);
                    $this->pengarang->setSessionValue($this->pengarang->FormValue);
                    if (!is_numeric($masterTbl->id_pengarang->FormValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
        }
        if ($validMaster) {
            // Save current master table
            $this->setCurrentMasterTable($masterTblVar);
            $this->setSessionWhere($this->getDetailFilter());

            // Reset start record counter (new master key)
            if (!$this->isAddOrEdit()) {
                $this->StartRecord = 1;
                $this->setStartRecordNumber($this->StartRecord);
            }

            // Clear previous master key from Session
            if ($masterTblVar != "penerbit") {
                if ($this->penerbit->CurrentValue == "") {
                    $this->penerbit->setSessionValue("");
                }
            }
            if ($masterTblVar != "pengarang") {
                if ($this->pengarang->CurrentValue == "") {
                    $this->pengarang->setSessionValue("");
                }
            }
        }
        $this->DbMasterFilter = $this->getMasterFilter(); // Get master filter
        $this->DbDetailFilter = $this->getDetailFilter(); // Get detail filter
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("BukuList"), "", $this->TableVar, true);
        $pageId = "edit";
        $Breadcrumb->add("edit", $pageId, $url);
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

    // Set up starting record parameters
    public function setupStartRecord()
    {
        if ($this->DisplayRecords == 0) {
            return;
        }
        if ($this->isPageRequest()) { // Validate request
            $startRec = Get(Config("TABLE_START_REC"));
            $pageNo = Get(Config("TABLE_PAGE_NO"));
            if ($pageNo !== null) { // Check for "pageno" parameter first
                if (is_numeric($pageNo)) {
                    $this->StartRecord = ($pageNo - 1) * $this->DisplayRecords + 1;
                    if ($this->StartRecord <= 0) {
                        $this->StartRecord = 1;
                    } elseif ($this->StartRecord >= (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1) {
                        $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1;
                    }
                    $this->setStartRecordNumber($this->StartRecord);
                }
            } elseif ($startRec !== null) { // Check for "start" parameter
                $this->StartRecord = $startRec;
                $this->setStartRecordNumber($this->StartRecord);
            }
        }
        $this->StartRecord = $this->getStartRecordNumber();

        // Check if correct start record counter
        if (!is_numeric($this->StartRecord) || $this->StartRecord == "") { // Avoid invalid start record counter
            $this->StartRecord = 1; // Reset start record counter
            $this->setStartRecordNumber($this->StartRecord);
        } elseif ($this->StartRecord > $this->TotalRecords) { // Avoid starting record > total records
            $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to last page first record
            $this->setStartRecordNumber($this->StartRecord);
        } elseif (($this->StartRecord - 1) % $this->DisplayRecords != 0) {
            $this->StartRecord = (int)(($this->StartRecord - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to page boundary
            $this->setStartRecordNumber($this->StartRecord);
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
}
