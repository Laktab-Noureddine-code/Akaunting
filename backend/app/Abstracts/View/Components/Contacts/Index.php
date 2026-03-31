<?php
namespace App\Abstracts\View\Components\Contacts;
use App\Abstracts\View\Component;
use App\Traits\Documents;
use App\Traits\ViewComponents;
abstract class Index extends Component
{
    use Documents, ViewComponents;
    public const OBJECT_TYPE = 'contact';
    public const DEFAULT_TYPE = 'customer';
    public const DEFAULT_PLURAL_TYPE = 'customers';
    public $type;
    public $contacts;
    public $textPage;
    public $group;
    public $page;
    public $permissionCreate;
    public $permissionUpdate;
    public $permissionDelete;
    public $checkPermissionCreate;
    public $hideCreate;
    public $hideImport;
    public $hideExport;
    public $createRoute;
    public $importRoute;
    public $importRouteParameters;
    public $exportRoute;
    public $hideEmptyPage;
    public $hideSummary;
    public $summaryItems;
    public $hideSearchString;
    public $hideBulkAction;
    public $searchStringModel;
    public $bulkActionClass;
    public $bulkActionRouteParameters;
    public $searchRoute;
    public $classBulkAction;
    public $showLogo;
    public $hideName;
    public $hideTaxNumber;
    public $classNameAndTaxNumber;
    public $textName;
    public $textTaxNumber;
    public $hideEmail;
    public $hidePhone;
    public $classEmailAndPhone;
    public $textEmail;
    public $textPhone;
    public $hideCountry;
    public $hideCurrencyCode;
    public $classCountryAndCurrencyCode;
    public $textCountry;
    public $textCurrencyCode;
    public $hideOpen;
    public $hideOverdue;
    public $classOpenAndOverdue;
    public $textOpen;
    public $textOverdue;
    public $imageEmptyPage;
    public $textEmptyPage;
    public $urlDocsPath;
    public $routeButtonShow;
    public function __construct(
        string $type, $contacts = [], string $textPage = '', string $group = '', string $page = '',
        string $permissionCreate = '', string $permissionUpdate = '', string $permissionDelete = '',
        bool $checkPermissionCreate = true,
        bool $hideCreate = false, bool $hideImport = false, bool $hideExport = false,
        string $createRoute = '', string $importRoute = '', array $importRouteParameters = [], string $exportRoute = '',
        bool $hideEmptyPage = false, bool $hideSummary = false,  $summaryItems = [], bool $hideSearchString = false, bool $hideBulkAction = false,
        string $searchStringModel = '', string $bulkActionClass = '', array $bulkActions = [], array $bulkActionRouteParameters = [], string $searchRoute = '',
        string $classBulkAction = '',
        bool $showLogo = false, bool $hideName = false, bool $hideTaxNumber = false, string $classNameAndTaxNumber = '', string $textName = '', string $textTaxNumber = '',
        bool $hideEmail = false, bool $hidePhone = false, string $classEmailAndPhone = '', string $textEmail = '', string $textPhone = '',
        bool $hideCountry = false, bool $hideCurrencyCode = false, string $classCountryAndCurrencyCode = '', string $textCountry = '', string $textCurrencyCode = '',
        bool $hideOpen = false, bool $hideOverdue = false, string $classOpenAndOverdue = '', string $textOpen = '', string $textOverdue = '',
        string $imageEmptyPage = '', string $textEmptyPage = '', string $urlDocsPath = '', string $routeButtonShow = '',
    ) {
        $this->type = $type;
        $this->group = $this->getGroup($type, $group);
        $this->page = $this->getPage($type, $page);
        $this->contacts = ($contacts) ? $contacts : collect();
        $this->textPage = $this->getTextPage($type, $textPage);
        $this->permissionCreate = $this->getPermissionCreate($type, $permissionCreate);
        $this->permissionUpdate = $this->getPermissionUpdate($type, $permissionUpdate);
        $this->permissionDelete = $this->getPermissionDelete($type, $permissionDelete);
        $this->checkPermissionCreate = $checkPermissionCreate;
        $this->hideCreate = $hideCreate;
        $this->hideImport = $hideImport;
        $this->hideExport = $hideExport;
        $this->routeButtonShow = $this->getRouteButtonShow($type, $routeButtonShow);
        $this->createRoute = $this->getCreateRoute($type, $createRoute);
        $this->importRoute = $this->getImportRoute($importRoute);
        $this->importRouteParameters = $this->getImportRouteParameters($type, $importRouteParameters);
        $this->exportRoute = $this->getExportRoute($type, $exportRoute);
        $this->hideEmptyPage = $hideEmptyPage;
        $this->hideSummary = $hideSummary;
        $this->summaryItems = $this->getSummaryItems($type, $summaryItems);
        $this->hideSearchString = $hideSearchString;
        $this->hideBulkAction = $hideBulkAction;
        $this->searchStringModel = $this->getSearchStringModel($type, $searchStringModel);
        $this->bulkActionClass = $this->getBulkActionClass($type, $bulkActionClass);
        $this->bulkActionRouteParameters = $this->getBulkActionRouteParameters($type, $bulkActionRouteParameters);
        $this->searchRoute = $this->getIndexRoute($type, $searchRoute);
        $this->classBulkAction = $this->getClassBulkAction($type, $classBulkAction);
        $this->showLogo = $showLogo;
        $this->hideName = $hideName;
        $this->hideTaxNumber = $hideTaxNumber;
        $this->classNameAndTaxNumber = $this->getClassNameAndTaxNumber($type, $classNameAndTaxNumber);
        $this->textName = $this->getTextName($type, $textName);
        $this->textTaxNumber = $this->getTextTaxNumber($type, $textTaxNumber);
        $this->hideEmail = $hideEmail;
        $this->hidePhone = $hidePhone;
        $this->classEmailAndPhone = $this->getClassEmailAndPhone($type, $classEmailAndPhone);
        $this->textEmail = $this->getTextEmail($type, $textEmail);
        $this->textPhone = $this->getTextPhone($type, $textPhone);
        $this->hideCountry = $hideCountry;
        $this->hideCurrencyCode = $hideCurrencyCode;
        $this->classCountryAndCurrencyCode = $this->getClassCountryAndCurrencyCode($type, $classCountryAndCurrencyCode);
        $this->textCountry = $this->getTextCountry($type, $textCountry);
        $this->textCurrencyCode = $this->getTextCurrencyCode($type, $textCurrencyCode);
        $this->hideOpen = $hideOpen;
        $this->hideOverdue = $hideOverdue;
        $this->classOpenAndOverdue = $this->getClassOpenAndOverdue($type, $classOpenAndOverdue);
        $this->textOpen = $this->getTextOpen($type, $textOpen);
        $this->textOverdue = $this->getTextOverdue($type, $textOverdue);
        $this->imageEmptyPage = $this->getImageEmptyPage($type, $imageEmptyPage);
        $this->textEmptyPage = $this->getTextEmptyPage($type, $textEmptyPage);
        $this->urlDocsPath = $this->getUrlDocsPath($type, $urlDocsPath);
        $this->setParentData();
    }
    public function getSummaryItems($type, $summaryItems)
    {
        if (! empty($summaryItems)) {
            return $summaryItems;
        }
        $route = $this->getIndexRoute($type, null);
        $document_type = config('type.contact.' . $type . '.document_type', 'invoice');
        $totals = $this->getTotalsForFutureDocuments($document_type);
        $items = [];
        foreach ($totals as $key => $total) {
            $items[] = [
                'title'     => ($key == 'overdue') ? trans('general.overdue') : trans('documents.statuses.' . $key),
                'amount'    => money($total)->formatForHumans(),
                'tooltip'   => money($total)->format(),
            ];
        }
        return $items;
    }
    protected function getClassNameAndTaxNumber($type, $classNameAndTaxNumber)
    {
        if (! empty($classNameAndTaxNumber)) {
            return $classNameAndTaxNumber;
        }
        $class = $this->getClassFromConfig($type, 'name_and_tax_number');
        if (! empty($class)) {
            return $class;
        }
        return 'w-6/12 sm:w-3/12 truncate';
    }
    protected function getTextName($type, $textName)
    {
        if (! empty($textName)) {
            return $textName;
        }
        $translation = $this->getTextFromConfig($type, 'name', 'name');
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.name';
    }
    protected function getTextTaxNumber($type, $textTaxNumber)
    {
        if (! empty($textTaxNumber)) {
            return $textTaxNumber;
        }
        $translation = $this->getTextFromConfig($type, 'tax_number', 'tax_number');
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.tax_number';
    }
    protected function getClassEmailAndPhone($type, $classEmailAndPhone)
    {
        if (! empty($classEmailAndPhone)) {
            return $classEmailAndPhone;
        }
        $class = $this->getClassFromConfig($type, 'email_and_phone');
        if (! empty($class)) {
            return $class;
        }
        return 'w-3/12 hidden sm:table-cell';
    }
    protected function getTextEmail($type, $textEmail)
    {
        if (! empty($textEmail)) {
            return $textEmail;
        }
        $translation = $this->getTextFromConfig($type, 'email', 'email');
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.email';
    }
    protected function getTextPhone($type, $textPhone)
    {
        if (! empty($textPhone)) {
            return $textPhone;
        }
        $translation = $this->getTextFromConfig($type, 'phone', 'phone');
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.phone';
    }
    protected function getClassCountryAndCurrencyCode($type, $classCountryAndCurrencyCode)
    {
        if (! empty($classCountryAndCurrencyCode)) {
            return $classCountryAndCurrencyCode;
        }
        $class = $this->getClassFromConfig($type, 'country_and_currency_code');
        if (! empty($class)) {
            return $class;
        }
        return 'w-3/12 hidden sm:table-cell';
    }
    protected function getTextCountry($type, $textCountry)
    {
        if (! empty($textCountry)) {
            return $textCountry;
        }
        $translation = $this->getTextFromConfig($type, 'country', 'countries');
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.countries';
    }
    protected function getTextCurrencyCode($type, $textCurrencyCode)
    {
        if (! empty($textCurrencyCode)) {
            return $textCurrencyCode;
        }
        $translation = $this->getTextFromConfig($type, 'currency_code', 'currencies');
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.currencies';
    }
    protected function getClassOpenAndOverdue($type, $classOpenAndOverdue)
    {
        if (! empty($classOpenAndOverdue)) {
            return $classOpenAndOverdue;
        }
        $class = $this->getClassFromConfig($type, 'open_and_overdue');
        if (! empty($class)) {
            return $class;
        }
        return 'w-6/12 sm:w-3/12';
    }
    protected function getTextOpen($type, $textOpen)
    {
        if (! empty($textOpen)) {
            return $textOpen;
        }
        $translation = $this->getTextFromConfig($type, 'open', 'open');
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.open';
    }
    protected function getTextOverdue($type, $textOverdue)
    {
        if (! empty($textOverdue)) {
            return $textOverdue;
        }
        $translation = $this->getTextFromConfig($type, 'overdue', 'overdue');
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.overdue';
    }
    protected function getRouteButtonShow($type, $routeButtonShow)
    {
        if (!empty($routeButtonShow)) {
            return $routeButtonShow;
        }
        $parameter = 1;
        $route = $this->getRouteFromConfig($type, 'show', $parameter);
        if (!empty($route)) {
            return $route;
        }
        return 'customer.show';
    }
}
