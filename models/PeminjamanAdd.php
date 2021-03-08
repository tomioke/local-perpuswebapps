<?php

namespace PHPMaker2021\perpusupdate;

use Doctrine\DBAL\ParameterType;

/**
 * Page class
 */
class PeminjamanAdd extends Peminjaman
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'peminjaman';

    // Page object name
    public $PageObjName = "PeminjamanAdd";

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
    }

    // Get content from stream
    public function getContents($stream = null): string
    {
        global $Response;
        return is_object($Response) ? $Response->getBody() : ob_get_clean();
    }

    // Is lookup
    public function isLookup()
    {
        return SameText(Route(0), Config("API_LOOKUP_ACTION"));
    }

    // Is AutoFill
    public function isAutoFill()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autofill");
    }

    // Is AutoSuggest
    public function isAutoSuggest()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autosuggest");
    }

    // Is modal lookup
    public function isModalLookup()
    {
        return $this->isLookup() && SameText(Post("ajax"), "modal");
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
        global $ExportFileName, $TempImages, $DashboardReport, $Response;

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
        } else { // Check if response is JSON
            if (StartsString("application/json", $Response->getHeaderLine("Content-type")) && $Response->getBody()->getSize()) { // With JSON response
                $this->clearMessages();
                return;
            }
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
                    if ($pageName == "PeminjamanView") {
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
    public $FormClassName = "ew-horizontal ew-form ew-add-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter = "";
    public $DbDetailFilter = "";
    public $StartRecord;
    public $Priv = 0;
    public $OldRecordset;
    public $CopyRecord;

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
        $this->id_peminjaman->Visible = false;
        $this->berita_peminjaman->setVisibility();
        $this->id_buku->setVisibility();
        $this->id_anggota->setVisibility();
        $this->tgl_peminjaman->setVisibility();
        $this->rencana_tgl_kembali->setVisibility();
        $this->kondisi_buku_peminjaman->setVisibility();
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
        $this->setupLookupOptions($this->id_buku);
        $this->setupLookupOptions($this->id_anggota);

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $this->FormClassName = "ew-form ew-add-form ew-horizontal";
        $postBack = false;

        // Set up current action
        if (IsApi()) {
            $this->CurrentAction = "insert"; // Add record directly
            $postBack = true;
        } elseif (Post("action") !== null) {
            $this->CurrentAction = Post("action"); // Get form action
            $this->setKey(Post($this->OldKeyName));
            $postBack = true;
        } else {
            // Load key values from QueryString
            if (($keyValue = Get("id_peminjaman") ?? Route("id_peminjaman")) !== null) {
                $this->id_peminjaman->setQueryStringValue($keyValue);
            }
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $this->CopyRecord = !EmptyValue($this->OldKey);
            if ($this->CopyRecord) {
                $this->CurrentAction = "copy"; // Copy record
            } else {
                $this->CurrentAction = "show"; // Display blank record
            }
        }

        // Load old record / default values
        $loaded = $this->loadOldRecord();

        // Load form values
        if ($postBack) {
            $this->loadFormValues(); // Load form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues(); // Restore form values
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = "show"; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "copy": // Copy an existing record
                if (!$loaded) { // Record not loaded
                    if ($this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                    }
                    $this->terminate("PeminjamanList"); // No matching record, return to list
                    return;
                }
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($this->OldRecordset)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = $this->getReturnUrl();
                    if (GetPageName($returnUrl) == "PeminjamanList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "PeminjamanView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }
                    if (IsApi()) { // Return to caller
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl);
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Add failed, restore form values
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render row based on row type
        $this->RowType = ROWTYPE_ADD; // Render add type

        // Render row
        $this->resetAttributes();
        $this->renderRow();

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Pass table and field properties to client side
            $this->toClientVar(["tableCaption"], ["caption", "Visible", "Required", "IsInvalid", "Raw"]);

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
        $this->tgl_peminjaman->CurrentValue = CurrentDate();
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

        // Check field name 'berita_peminjaman' first before field var 'x_berita_peminjaman'
        $val = $CurrentForm->hasValue("berita_peminjaman") ? $CurrentForm->getValue("berita_peminjaman") : $CurrentForm->getValue("x_berita_peminjaman");
        if (!$this->berita_peminjaman->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->berita_peminjaman->Visible = false; // Disable update for API request
            } else {
                $this->berita_peminjaman->setFormValue($val);
            }
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

        // Check field name 'id_anggota' first before field var 'x_id_anggota'
        $val = $CurrentForm->hasValue("id_anggota") ? $CurrentForm->getValue("id_anggota") : $CurrentForm->getValue("x_id_anggota");
        if (!$this->id_anggota->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->id_anggota->Visible = false; // Disable update for API request
            } else {
                $this->id_anggota->setFormValue($val);
            }
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

        // Check field name 'kondisi_buku_peminjaman' first before field var 'x_kondisi_buku_peminjaman'
        $val = $CurrentForm->hasValue("kondisi_buku_peminjaman") ? $CurrentForm->getValue("kondisi_buku_peminjaman") : $CurrentForm->getValue("x_kondisi_buku_peminjaman");
        if (!$this->kondisi_buku_peminjaman->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->kondisi_buku_peminjaman->Visible = false; // Disable update for API request
            } else {
                $this->kondisi_buku_peminjaman->setFormValue($val);
            }
        }

        // Check field name 'id_peminjaman' first before field var 'x_id_peminjaman'
        $val = $CurrentForm->hasValue("id_peminjaman") ? $CurrentForm->getValue("id_peminjaman") : $CurrentForm->getValue("x_id_peminjaman");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->berita_peminjaman->CurrentValue = $this->berita_peminjaman->FormValue;
        $this->id_buku->CurrentValue = $this->id_buku->FormValue;
        $this->id_anggota->CurrentValue = $this->id_anggota->FormValue;
        $this->tgl_peminjaman->CurrentValue = $this->tgl_peminjaman->FormValue;
        $this->tgl_peminjaman->CurrentValue = UnFormatDateTime($this->tgl_peminjaman->CurrentValue, 0);
        $this->rencana_tgl_kembali->CurrentValue = $this->rencana_tgl_kembali->FormValue;
        $this->rencana_tgl_kembali->CurrentValue = UnFormatDateTime($this->rencana_tgl_kembali->CurrentValue, 0);
        $this->kondisi_buku_peminjaman->CurrentValue = $this->kondisi_buku_peminjaman->FormValue;
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

        // Check if valid User ID
        if ($res) {
            $res = $this->showOptionLink("add");
            if (!$res) {
                $userIdMsg = DeniedMessage();
                $this->setFailureMessage($userIdMsg);
            }
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
            if (!$Security->isAdmin() && $Security->isLoggedIn() && !$this->userIDAllow("add")) { // Non system admin
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
        $addRow = false;
        if ($insertRow) {
            try {
                $addRow = $this->insert($rsnew);
            } catch (\Exception $e) {
                $this->setFailureMessage($e->getMessage());
            }
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

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("PeminjamanList"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $Breadcrumb->add("add", $pageId, $url);
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
}
