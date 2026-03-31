<?php
namespace App\Abstracts\View\Components\Documents;
use App\Abstracts\View\Component;
use App\Traits\Documents;
use App\Traits\Modules;
use App\Traits\SearchString;
use App\Traits\ViewComponents;
use App\Models\Document\Document;
use Illuminate\Support\Str;
abstract class Index extends Component
{
    use Documents, Modules, SearchString, ViewComponents;
    public const OBJECT_TYPE = 'document';
    public const DEFAULT_TYPE = 'invoice';
    public const DEFAULT_PLURAL_TYPE = 'invoices';
    public $type;
    public $contact_type;
    public $category_type;
    public $alias;
    public $documents;
    public $totalDocuments;
    public $group;
    public $page;
    public $textTabDocument;
    public $routeTabDocument;
    public $routeTabRecurring;
    public $routeParamsTabUnpaid;
    public $routeParamsTabDraft;
    public $textPage;
    public $permissionCreate;
    public $permissionUpdate;
    public $permissionDelete;
    public $hideAcceptPayment;
    public $checkPermissionCreate;
    public $hideCreate;
    public $hideImport;
    public $hideExport;
    public $createRoute;
    public $importRoute;
    public $importRouteParameters;
    public $exportRoute;
    public $hideEmptyPage;
    public $emptyPageButtons;
    public $imageEmptyPage;
    public $textEmptyPage;
    public $urlDocsPath;
    public $hideSummary;
    public $summaryItems;
    public $withoutTabs;
    public $tabActive;
    public $tabSuffix;
    public $hideRecurringTemplates;
    public $hideSearchString;
    public $hideBulkAction;
    public $searchStringModel;
    public $bulkActionClass;
    public $bulkActionRouteParameters;
    public $searchRoute;
    public $classBulkAction;
    public $hideDueAt;
    public $hideIssuedAt;
    public $classDueAtAndIssueAt;
    public $textDueAt;
    public $textIssuedAt;
    public $hideStartedAt;
    public $hideEndedAt;
    public $classStartedAtAndEndedAt;
    public $textStartedAt;
    public $textEndedAt;
    public $hideStatus;
    public $classStatus;
    public $hideContactName;
    public $hideCategory;
    public $hideCurrency;
    public $textCategory;
    public $hideFrequency;
    public $classFrequencyAndDuration;
    public $hideDuration;
    public $hideDocumentNumber;
    public $classContactNameAndDocumentNumber;
    public $textContactName;
    public $showContactRoute;
    public $textDocumentNumber;
    public $hideAmount;
    public $classAmount;
    public $hideShow;
    public $showRoute;
    public $hideEdit;
    public $editRoute;
    public $hideDuplicate;
    public $duplicateRoute;
    public $textDocumentStatus;
    public $checkButtonReconciled;
    public $checkButtonCancelled;
    public function __construct(
        string $type, string $contact_type = '', string $category_type = '', string $alias = '', $documents = [], int $totalDocuments = null, string $group = '', string $page = '', string $textTabDocument = '', string $textPage = '',
        string $routeTabDocument = '', string $routeTabRecurring = '', string $routeParamsTabUnpaid = '', string $routeParamsTabDraft = '',
        string $permissionCreate = '', string $permissionUpdate = '', string $permissionDelete = '',
        bool $hideAcceptPayment = false, bool $checkPermissionCreate = true,
        bool $hideCreate = false, bool $hideImport = false, bool $hideExport = false,
        string $createRoute = '', string $importRoute = '', array $importRouteParameters = [], string $exportRoute = '',
        bool $hideEmptyPage = false, array $emptyPageButtons = [], string $imageEmptyPage = '', string $textEmptyPage = '', string $urlDocsPath = '',
        bool $hideSummary = false, array $summaryItems = [],
        bool $withoutTabs = false, string $tabActive = '', string $tabSuffix = '', bool $hideRecurringTemplates = false,
        bool $hideSearchString = false, bool $hideBulkAction = false,
        string $searchStringModel = '', string $bulkActionClass = '', array $bulkActions = [], array $bulkActionRouteParameters = [], string $searchRoute = '', string $classBulkAction = '',
        bool $hideDueAt = false, bool $hideIssuedAt = false, string $classDueAtAndIssueAt = '', string $textDueAt = '', string $textIssuedAt = '',
        bool $hideStartedAt = false, bool $hideEndedAt = false, string $classStartedAtAndEndedAt = '', string $textStartedAt = '', string $textEndedAt = '',
        bool $hideStatus = false, string $classStatus = '',
        bool $hideCategory = false, string $textCategory = '', bool $hideCurrency = false,
        bool $hideFrequency = false, bool $hideDuration = false, string $classFrequencyAndDuration = '',
        bool $hideContactName = false, bool $hideDocumentNumber = false, string $classContactNameAndDocumentNumber = '', string $textContactName = '', string $showContactRoute = '', string $textDocumentNumber = '',
        bool $hideAmount = false, string $classAmount = '',
        bool $hideShow = false, string $showRoute = '', bool $hideEdit = false, string $editRoute = '', bool $hideDuplicate = false, string $duplicateRoute = '',
        string $textDocumentStatus = '',
        bool $checkButtonReconciled = true, bool $checkButtonCancelled = true
    ) {
        $this->type = $type;
        $this->contact_type = $this->getTypeContact($type, $contact_type);
        $this->category_type = $this->getTypeCategory($type, $category_type);
        $this->alias = $this->getAlias($type, $alias);
        $this->documents = ($documents) ? $documents : collect();
        $this->totalDocuments = $this->getTotalDocuments($totalDocuments);
        $this->group = $this->getGroup($type, $group);
        $this->page = $this->getPage($type, $page);
        $this->textTabDocument = $this->getTextTabDocument($type, $textTabDocument);
        $this->textPage = $this->getTextPage($type, $textPage);
        $this->permissionCreate = $this->getPermissionCreate($type, $permissionCreate);
        $this->permissionUpdate = $this->getPermissionUpdate($type, $permissionUpdate);
        $this->permissionDelete = $this->getPermissionDelete($type, $permissionDelete);
        $this->routeTabDocument = $this->getRouteTabDocument($type, $routeTabDocument);
        $this->routeParamsTabUnpaid = $this->getRouteParamsTabUnpaid($type, $routeParamsTabUnpaid);
        $this->routeParamsTabDraft = $this->getRouteParamsTabDraft($type, $routeParamsTabDraft);
        $this->routeTabRecurring = $this->getRouteTabRecurring($type, $routeTabRecurring);
        $this->hideAcceptPayment = $hideAcceptPayment;
        $this->checkPermissionCreate = $checkPermissionCreate;
        $this->hideCreate = $hideCreate;
        $this->hideImport = $hideImport;
        $this->hideExport = $hideExport;
        $this->createRoute = $this->getCreateRoute($type, $createRoute);
        $this->importRoute = $this->getImportRoute($importRoute);
        $this->importRouteParameters = $this->getImportRouteParameters($type, $importRouteParameters);
        $this->exportRoute = $this->getExportRoute($type, $exportRoute);
        $this->hideEmptyPage = $this->getHideEmptyPage($hideEmptyPage);
        $this->emptyPageButtons = $this->getEmptyPageButtons($type, $emptyPageButtons);
        $this->imageEmptyPage = $this->getImageEmptyPage($type, $imageEmptyPage);
        $this->textEmptyPage = $this->getTextEmptyPage($type, $textEmptyPage);
        $this->urlDocsPath = $this->getUrlDocsPath($type, $urlDocsPath);
        $this->hideSummary = $hideSummary;
        $this->summaryItems = $this->getSummaryItems($type, $summaryItems);
        $this->withoutTabs = $withoutTabs;
        $this->tabSuffix = $this->getTabSuffix($type, $tabSuffix);
        $this->tabActive = $this->getTabActive($type, $tabActive);
        $this->hideRecurringTemplates = $hideRecurringTemplates;
        $this->hideSearchString = $hideSearchString;
        $this->hideBulkAction = $hideBulkAction;
        $this->searchStringModel = $this->getSearchStringModel($type, $searchStringModel);
        $this->bulkActionClass = $this->getBulkActionClass($type, $bulkActionClass);
        $this->bulkActionRouteParameters = $this->getBulkActionRouteParameters($type, $bulkActionRouteParameters);
        $this->searchRoute = $this->getIndexRoute($type, $searchRoute);
        $this->classBulkAction = $this->getClassBulkAction($type, $classBulkAction);
        $this->hideDueAt = $hideDueAt;
        $this->hideIssuedAt = $hideIssuedAt;
        $this->classDueAtAndIssueAt = $this->getClassDueAtAndIssueAt($type, $classDueAtAndIssueAt);
        $this->textDueAt = $this->getTextDueAt($type, $textDueAt);
        $this->textIssuedAt = $this->getTextIssuedAt($type, $textIssuedAt);
        $this->hideStartedAt = $hideStartedAt;
        $this->hideEndedAt = $hideEndedAt;
        $this->classStartedAtAndEndedAt = $this->getClassStartedAndEndedAt($type, $classStartedAtAndEndedAt);
        $this->textStartedAt = $this->getTextStartedAt($type, $textStartedAt);
        $this->textEndedAt = $this->getTextEndedAt($type, $textEndedAt);
        $this->hideStatus = $hideStatus;
        $this->classStatus = $this->getClassStatus($type, $classStatus);
        $this->hideCategory = $hideCategory;
        $this->textCategory = $this->getTextCategory($type, $textCategory);
        $this->hideCurrency = $hideCurrency;
        $this->hideFrequency = $hideFrequency;
        $this->hideDuration = $hideDuration;
        $this->classFrequencyAndDuration = $this->getClassFrequencyAndDuration($type, $classFrequencyAndDuration);
        $this->hideContactName = $hideContactName;
        $this->hideDocumentNumber = $hideDocumentNumber;
        $this->classContactNameAndDocumentNumber = $this->getClassContactNameAndDocumentNumber($type, $classContactNameAndDocumentNumber);
        $this->textContactName = $this->getTextContactName($type, $textContactName);
        $this->showContactRoute = $this->getShowContactRoute($type, $showContactRoute);
        $this->textDocumentNumber = $this->getTextDocumentNumber($type, $textDocumentNumber);
        $this->hideAmount = $hideAmount;
        $this->classAmount = $this->getClassAmount($type, $classAmount);
        $this->hideShow = $hideShow;
        $this->showRoute = $this->getShowRoute($type, $showRoute);
        $this->hideEdit = $hideEdit;
        $this->editRoute = $this->getEditRoute($type, $editRoute);
        $this->hideDuplicate = $hideDuplicate;
        $this->duplicateRoute = $this->getDuplicateRoute($type, $duplicateRoute);
        $this->textDocumentStatus = $this->getTextDocumentStatus($type, $textDocumentStatus);
        $this->checkButtonReconciled = $checkButtonReconciled;
        $this->checkButtonCancelled = $checkButtonCancelled;
        $this->setParentData();
    }
    protected function getTypeContact($type, $typeContact)
    {
        if (! empty($typeContact)) {
            return $typeContact;
        }
        return config('type.' . static::OBJECT_TYPE . '.' . $type . '.contact_type', 'customer');
    }
    protected function getTypeCategory($type, $typeCategory)
    {
        if (!empty($typeCategory)) {
            return $typeCategory;
        }
        if ($category_type = config('type.' . static::OBJECT_TYPE . '.' . $type . '.category_type')) {
            return $category_type;
        }
        $type = Document::INVOICE_TYPE;
        return config('type.' . static::OBJECT_TYPE .'.' . $type . '.category_type');
    }
    protected function getTotalDocuments($totalDocuments)
    {
        if (! is_null($totalDocuments)) {
            return $totalDocuments;
        }
        return $this->documents->count();
    }
    protected function getHideEmptyPage($hideEmptyPage): bool
    {
        if ($hideEmptyPage) {
            return $hideEmptyPage;
        }
        if ($this->totalDocuments > 0) {
            return true;
        }
        if (request()->has('search') && ($this->totalDocuments > 0)) {
            return true;
        }
        return false;
    }
    protected function getEmptyPageButtons($type, $emptyPageButtons)
    {
        if (! empty($emptyPageButtons)) {
            return $emptyPageButtons;
        }
        $prefix = config('type.' . static::OBJECT_TYPE . '.' . $type . '.route.prefix', 'invoices');
        $buttons = [];
        if (! $this->hideCreate) {
            $route = $this->getRouteFromConfig($type, 'create');
            $buttons[] = [
                'permission'    => $this->permissionCreate,
                'url'           => route($this->createRoute),
                'text'          => trans('general.title.new', ['type' => trans_choice($this->textPage ?? 'general.' . $prefix, 1)]),
                'description'   => trans('general.empty.actions.new', ['type' => strtolower(trans_choice($this->textPage ?? 'general.' . $prefix, 1))]),
                'active_badge'  => true,
                'stack'         => 'create_button',
            ];
        }
        if (! $this->hideImport) {
            $route = $this->getRouteFromConfig($type, 'import');
            $buttons[] = [
                'permission'    => $this->permissionCreate,
                'url'           => route($this->importRoute, $this->importRouteParameters),
                'text'          => trans('import.title', ['type' => trans_choice($this->textPage ?? 'general.' . $prefix, 2)]),
                'description'   => trans('general.empty.actions.import', ['type' => strtolower(trans_choice($this->textPage ?? 'general.' . $prefix, 2))]),
                'stack'         => 'import_button',
            ];
        }
        return $buttons;
    }
    public function getSummaryItems($type, $summaryItems)
    {
        if (! empty($summaryItems)) {
            return $summaryItems;
        }
        $route = $this->getIndexRoute($type, null);
        $totals = $this->getTotalsForFutureDocuments($type);
        $items = [];
        foreach ($totals as $key => $total) {
            $title = ($key == 'overdue') ? trans('general.overdue') : trans('documents.statuses.' . $key);
            $href = route($route, ['search' => 'status:' . $key]);
            $amount = money($total)->formatForHumans();
            $tooltip = money($total)->format();
            $items[] = [
                'title'     => $title,
                'amount'    => $amount,
                'tooltip'   => $tooltip,
            ];
        }
        return $items;
    }
    protected function getTextTabDocument($type, $textTabDocument)
    {
        if (! empty($textTabDocument)) {
            return $textTabDocument;
        }
        $default_key = config('type.' . static::OBJECT_TYPE . '.' . $type . '.translation.prefix');
        $translation = $this->getTextFromConfig($type, 'tab_document', $default_key);
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.invoices';
    }
    public function getTabActive($type, $tabActive)
    {
        if (! empty($tabActive)) {
            return $tabActive;
        }
        return $type . '-' . $this->tabSuffix;
    }
    public function getTabSuffix($type, $tabSuffix)
    {
        if (! empty($tabSuffix)) {
            return $tabSuffix;
        }
        if (request()->get('list_records') == 'all') {
            return 'all';
        }
        $status = $this->getSearchStringValue('status');
        $unpaid = str_replace('status:', '', config('type.document.' . $type . '.route.params.unpaid.search'));
        if ($status == $unpaid) {
            return 'unpaid';
        }
        $draft = str_replace('status:', '', config('type.document.' . $type . '.route.params.draft.search'));
        if ($status == $draft) {
            return 'draft';
        }
        $suffix = $this->getTabActiveFromSetting($type);
        if (! empty($suffix)) {
            return $suffix;
        }
        return 'unpaid';
    }
    protected function getRouteTabDocument($type, $routeTabDocument)
    {
        if (! empty($routeTabDocument)) {
            return $routeTabDocument;
        }
        $route = $this->getRouteFromConfig($type, 'document', 'invoices');
        if (! empty($route)) {
            return $route;
        }
        return 'invoices.index';
    }
    protected function getRouteParamsTabUnpaid($type, $routeParamsTabUnpaid)
    {
        if (! empty($routeParamsTabUnpaid)) {
            return $routeParamsTabUnpaid;
        }
        $params = $this->getRouteParamsFromConfig($type, 'unpaid');
        if (! empty($params)) {
            return $params;
        }
        return ['search' => 'status:sent,viewed,partial'];
    }
    protected function getRouteParamsTabDraft($type, $routeParamsTabDraft)
    {
        if (! empty($routeParamsTabDraft)) {
            return $routeParamsTabDraft;
        }
        $params = $this->getRouteParamsFromConfig($type, 'draft');
        if (! empty($params)) {
            return $params;
        }
        return ['search' => 'status:draft'];
    }
    protected function getRouteTabRecurring($type, $routeTabDocument)
    {
        if (! empty($routeTabDocument)) {
            return $routeTabDocument;
        }
        $route = $this->getRouteFromConfig($type, 'recurring', 'recurring-invoices');
        if (! empty($route)) {
            return $route;
        }
        return 'recurring-invoices.index';
    }
    protected function getClassDueAtAndIssueAt($type, $classDueAtAndIssueAt)
    {
        if (! empty($classDueAtAndIssueAt)) {
            return $classDueAtAndIssueAt;
        }
        $class = $this->getClassFromConfig($type, 'due_at_and_issue_at');
        if (! empty($class)) {
            return $class;
        }
        return 'w-4/12 table-title hidden sm:table-cell';
    }
    protected function getTextDueAt($type, $textDueAt)
    {
        if (! empty($textDueAt)) {
            return $textDueAt;
        }
        $translation = $this->getTextFromConfig($type, 'due_at', 'due_date');
        if (! empty($translation)) {
            return $translation;
        }
        return 'invoices.due_date';
    }
    protected function getTextIssuedAt($type, $textIssuedAt)
    {
        if (! empty($textIssuedAt)) {
            return $textIssuedAt;
        }
        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $default_key = 'bill_date';
                break;
            default:
                $default_key = 'invoice_date';
                break;
        }
        $translation = $this->getTextFromConfig($type, 'issued_at', $default_key);
        if (! empty($translation)) {
            return $translation;
        }
        return 'invoices.invoice_date';
    }
    protected function getClassStartedAndEndedAt($type, $classStartedAtAndEndedAt)
    {
        if (! empty($classStartedAtAndEndedAt)) {
            return $classStartedAtAndEndedAt;
        }
        $class = $this->getClassFromConfig($type, 'started_at_and_end_at');
        if (! empty($class)) {
            return $class;
        }
        return 'w-4/12 table-title hidden sm:table-cell';
    }
    protected function getClassFrequencyAndDuration($type, $classFrequencyAndDuration)
    {
        if (! empty($classFrequencyAndDuration)) {
            return $classFrequencyAndDuration;
        }
        $class = $this->getClassFromConfig($type, 'frequency_and_duration');
        if (! empty($class)) {
            return $class;
        }
        return 'w-2/12';
    }
    protected function getTextStartedAt($type, $textStartedAt)
    {
        if (! empty($textStartedAt)) {
            return $textStartedAt;
        }
        $translation = $this->getTextFromConfig($type, 'started_at', 'started_date');
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.start_date';
    }
    protected function getTextEndedAt($type, $textEndedAt)
    {
        if (! empty($textEndedAt)) {
            return $textEndedAt;
        }
        $translation = $this->getTextFromConfig($type, 'ended_at', 'ended_date');
        if (! empty($translation)) {
            return $translation;
        }
        return 'recurring.last_issued';
    }
    protected function getClassStatus($type, $classStatus)
    {
        if (! empty($classStatus)) {
            return $classStatus;
        }
        $class = $this->getClassFromConfig($type, 'status');
        if (! empty($class)) {
            return $class;
        }
        return 'w-3/12 table-title hidden sm:table-cell';
    }
    protected function getTextCategory($type, $textCategory)
    {
        if (!empty($textCategory)) {
            return $textCategory;
        }
        $translation = $this->getTextFromConfig($type, 'categories', 'categories', 'trans_choice');
        if (!empty($translation)) {
            return $translation;
        }
        return 'general.categories';
    }
    protected function getClassContactNameAndDocumentNumber($type, $classContactNameAndDocumentNumber)
    {
        if (! empty($classContactNameAndDocumentNumber)) {
            return $classContactNameAndDocumentNumber;
        }
        $class = $this->getClassFromConfig($type, 'contact_name');
        if (! empty($class)) {
            return $class;
        }
        return 'w-6/12 sm:w-3/12 table-title';
    }
    protected function getTextContactName($type, $textContactName)
    {
        if (! empty($textContactName)) {
            return $textContactName;
        }
        $default_key = Str::plural(config('type.' . static::OBJECT_TYPE . '.' . $type . '.contact_type'), 2);
        $translation = $this->getTextFromConfig($type, 'contact_name', $default_key, 'trans_choice');
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.customers';
    }
    protected function getShowContactRoute($type, $showContactRoute)
    {
        if (! empty($showContactRoute)) {
            return $showContactRoute;
        }
        if (! empty($showRoute)) {
            return $showRoute;
        }
        $route = $this->getRouteFromConfig($type, 'contact.show', 1);
        if (!empty($route)) {
            return $route;
        }
        $default_key = Str::plural(config('type.' . static::OBJECT_TYPE . '.' . $type . '.contact_type'), 2);
        return $default_key . '.show';
    }
    protected function getTextDocumentNumber($type, $textDocumentNumber)
    {
        if (! empty($textDocumentNumber)) {
            return $textDocumentNumber;
        }
        $translation = $this->getTextFromConfig($type, 'document_number', 'numbers');
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.numbers';
    }
    protected function getClassAmount($type, $classAmount)
    {
        if (! empty($classAmount)) {
            return $classAmount;
        }
        $class = $this->getClassFromConfig($type, 'amount');
        if (! empty($class)) {
            return $class;
        }
        return 'w-6/12 sm:w-2/12';
    }
    protected function getTextDocumentStatus($type, $textDocumentStatus)
    {
        if (! empty($textDocumentStatus)) {
            return $textDocumentStatus;
        }
        $translation = $this->getTextFromConfig($type, 'document_status', 'statuses.');
        if (! empty($translation)) {
            return $translation;
        }
        $alias = config('type.' . static::OBJECT_TYPE . '.' . $type . '.alias');
        if (! empty($alias)) {
            $translation = $alias . '::' . config('type.' . static::OBJECT_TYPE . '.' . $type . '.translation.prefix') . '.statuses';
            if (is_array(trans($translation))) {
                return $translation . '.';
            }
        }
        return 'documents.statuses.';
    }
}
