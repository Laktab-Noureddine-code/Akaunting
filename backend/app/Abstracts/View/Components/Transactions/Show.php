<?php
namespace App\Abstracts\View\Components\Transactions;
use App\Abstracts\View\Component;
use App\Models\Common\Media;
use App\Traits\DateTime;
use App\Traits\Transactions;
use App\Utilities\Modules;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Intervention\Image\Exception\NotReadableException;
use App\Traits\ViewComponents;
abstract class Show extends Component
{
    use DateTime, Transactions, ViewComponents;
    public const OBJECT_TYPE = 'transaction';
    public const DEFAULT_TYPE = 'transaction';
    public const DEFAULT_PLURAL_TYPE = 'transactions';
    public $type;
    public $transaction;
    public $transactionTemplate;
    public $logo;
    public $payment_methods;
    public $date_format;
    public $hideButtonAddNew;
    public $hideButtonMoreActions;
    public $hideButtonEdit;
    public $hideButtonDuplicate;
    public $hideButtonConnect;
    public $hideButtonPrint;
    public $hideButtonShare;
    public $hideButtonEmail;
    public $hideButtonPdf;
    public $hideButtonEnd;
    public $hideButtonDelete;
    public $checkButtonReconciled;
    public $hideDivider1;
    public $hideDivider2;
    public $hideDivider3;
    public $hideDivider4;
    public $permissionCreate;
    public $permissionUpdate;
    public $permissionDelete;
    public $routeButtonAddNew;
    public $routeButtonEdit;
    public $routeButtonDuplicate;
    public $routeButtonPrint;
    public $routeContactShow;
    public $shareRoute;
    public $signedUrl;
    public $routeButtonEmail;
    public $routeButtonPdf;
    public $routeButtonEnd;
    public $routeButtonDelete;
    public $textDeleteModal;
    public $hideCompany;
    public $hideCompanyLogo;
    public $hideCompanyDetails;
    public $hideCompanyName;
    public $hideCompanyAddress;
    public $hideCompanyTaxNumber;
    public $hideCompanyPhone;
    public $hideCompanyEmail;
    public $hideContentTitle;
    public $hideNumber;
    public $hidePaidAt;
    public $hideAccount;
    public $hideCategory;
    public $hidePaymentMethods;
    public $hideReference;
    public $hideDescription;
    public $hideAmount;
    public $textButtonAddNew;
    public $textContentTitle;
    public $textNumber;
    public $textPaidAt;
    public $textAccount;
    public $textCategory;
    public $textPaymentMethods;
    public $textReference;
    public $textDescription;
    public $textAmount;
    public $textPaidBy;
    public $hideContact;
    public $hideContactInfo;
    public $hideContactName;
    public $hideContactAddress;
    public $hideContactTaxNumber;
    public $hideContactPhone;
    public $hideContactEmail;
    public $hideRelated;
    public $hideRelatedDocumentNumber;
    public $hideRelatedContact;
    public $hideRelatedDocumentDate;
    public $hideRelatedDocumentAmount;
    public $hideRelatedAmount;
    public $textRelatedTransansaction;
    public $textRelatedDocumentNumber;
    public $textRelatedContact;
    public $textRelatedDocumentDate;
    public $textRelatedDocumentAmount;
    public $textRelatedAmount;
    public $routeDocumentShow;
    public $routeTransactionShow;
    public $hideSchedule;
    public $hideChildren;
    public $hideConnect;
    public $hideTransfer;
    public $hideAttachment;
    public $attachment;
    public $connectTranslations;
    public $textRecurringType;
    public $hideRecurringMessage;
    public $hideConnectMessage;
    public $hideCreated;
    public function __construct(
        $type, $transaction, $transactionTemplate = '', $logo = '', array $payment_methods = [],
        bool $hideButtonAddNew = false, bool $hideButtonMoreActions = false, bool $hideButtonEdit = false, bool $hideButtonDuplicate = false, bool $hideButtonConnect = false, bool $hideButtonPrint = false, bool $hideButtonShare = false,
        bool $hideButtonEmail = false, bool $hideButtonPdf = false, bool $hideButtonEnd = false, bool $hideButtonDelete = false, bool $checkButtonReconciled = true,
        bool $hideDivider1 = false, bool $hideDivider2 = false, bool $hideDivider3 = false, bool $hideDivider4 = false,
        string $permissionCreate = '', string $permissionUpdate = '', string $permissionDelete = '',
        string $routeButtonAddNew = '', string $routeButtonEdit = '', string $routeButtonDuplicate = '', string $routeButtonPrint = '', string $shareRoute = '', string $signedUrl = '',
        string $routeButtonEmail = '', string $routeButtonPdf = '', string $routeButtonEnd = '', string $routeButtonDelete = '', string $routeContactShow = '',
        string $textDeleteModal = '',
        bool $hideCompany = false, bool $hideCompanyLogo = false, bool $hideCompanyDetails = false, bool $hideCompanyName = false, bool $hideCompanyAddress = false,
        bool $hideCompanyTaxNumber = false, bool $hideCompanyPhone = false, bool $hideCompanyEmail = false,
        bool $hideContentTitle = false, bool $hideNumber = false, bool $hidePaidAt = false, bool $hideAccount = false, bool $hideCategory = false, bool $hidePaymentMethods = false, bool $hideReference = false,
        bool $hideDescription = false, bool $hideAmount = false,
        string $textContentTitle = '', string $textNumber = '', string $textPaidAt = '', string $textAccount = '', string $textCategory = '', string $textPaymentMethods = '', string $textReference = '', string $textDescription = '',
        string $textAmount = '', string $textPaidBy = '',
        bool $hideContact = false, bool $hideContactInfo = false, bool $hideContactName = false, bool $hideContactAddress = false, bool $hideContactTaxNumber = false,
        bool $hideContactPhone = false, bool $hideContactEmail = false,
        bool $hideRelated = false, bool $hideRelatedDocumentNumber = false, bool $hideRelatedContact = false, bool $hideRelatedDocumentDate = false, bool $hideRelatedDocumentAmount = false, bool $hideRelatedAmount = false,
        string $textRelatedTransansaction = '', string $textRelatedDocumentNumber = '', string $textRelatedContact = '', string $textRelatedDocumentDate = '', string $textRelatedDocumentAmount = '', string $textRelatedAmount = '',
        string $routeDocumentShow = '', string $routeTransactionShow = '', string $textButtonAddNew = '',
        bool $hideSchedule = false, bool $hideChildren = false, bool $hideConnect = false, bool $hideTransfer = false, bool $hideAttachment = false, $attachment = [],
        array $connectTranslations = [], string $textRecurringType = '', bool $hideRecurringMessage = false, $hideConnectMessage = false, bool $hideCreated = false
    ) {
        $this->type = $type;
        $this->transaction = $transaction;
        $this->transactionTemplate = $this->getTransactionTemplate($type, $transactionTemplate);
        $this->logo = $this->getLogo($logo);
        $this->payment_methods = ($payment_methods) ?: Modules::getPaymentMethods('all');
        $this->date_format = $this->getCompanyDateFormat();
        $this->hideButtonAddNew = $hideButtonAddNew;
        $this->hideButtonMoreActions = $hideButtonMoreActions;
        $this->hideButtonEdit = $hideButtonEdit;
        $this->hideButtonDuplicate = $hideButtonDuplicate;
        $this->hideButtonConnect = $hideButtonConnect;
        $this->hideButtonPrint = $hideButtonPrint;
        $this->hideButtonShare = $hideButtonShare;
        $this->hideButtonEmail = $hideButtonEmail;
        $this->hideButtonPdf = $hideButtonPdf;
        $this->hideButtonEnd = $hideButtonEnd;
        $this->hideButtonDelete = $hideButtonDelete;
        $this->checkButtonReconciled = $checkButtonReconciled;
        $this->hideDivider1 = $hideDivider1;
        $this->hideDivider2 = $hideDivider2;
        $this->hideDivider3 = $hideDivider3;
        $this->hideDivider4 = $hideDivider4;
        $this->permissionCreate = $this->getPermissionCreate($type, $permissionCreate);
        $this->permissionUpdate = $this->getPermissionUpdate($type, $permissionUpdate);
        $this->permissionDelete = $this->getPermissionDelete($type, $permissionDelete);
        $this->routeButtonAddNew = $this->getRouteButtonAddNew($type, $routeButtonAddNew);
        $this->routeButtonEdit = $this->getRouteButtonEdit($type, $routeButtonEdit);
        $this->routeButtonDuplicate = $this->getRouteButtonDuplicate($type, $routeButtonDuplicate);
        $this->routeButtonPrint = $this->getRouteButtonPrint($type, $routeButtonPrint);
        $this->shareRoute = $this->getShareRoute($type, $shareRoute);
        $this->signedUrl = $this->getSignedUrl($type, $signedUrl);
        $this->routeButtonEmail = $this->getRouteButtonEmail($type, $routeButtonEmail);
        $this->routeButtonPdf = $this->getRouteButtonPdf($type, $routeButtonPdf);
        $this->routeButtonEnd = $this->getRouteButtonEnd($type, $routeButtonEnd);
        $this->routeButtonDelete = $this->getRouteButtonDelete($type, $routeButtonDelete);
        $this->routeContactShow = $this->getRouteContactShow($type, $routeContactShow);
        $this->textButtonAddNew = $this->getTextButtonAddNew($type, $textButtonAddNew);
        $this->textDeleteModal = $textDeleteModal;
        $this->hideSchedule = $hideSchedule;
        $this->hideChildren = $hideChildren;
        $this->hideConnect = $hideConnect;
        $this->hideTransfer = $hideTransfer;
        $this->hideAttachment = $hideAttachment;
        $this->hideCompany = $hideCompany;
        $this->hideCompanyLogo = $hideCompanyLogo;
        $this->hideCompanyDetails = $hideCompanyDetails;
        $this->hideCompanyName = $hideCompanyName;
        $this->hideCompanyAddress = $hideCompanyAddress;
        $this->hideCompanyTaxNumber = $hideCompanyTaxNumber;
        $this->hideCompanyPhone = $hideCompanyPhone;
        $this->hideCompanyEmail = $hideCompanyEmail;
        $this->hideContentTitle = $hideContentTitle;
        $this->hideNumber = $hideNumber;
        $this->hidePaidAt = $hidePaidAt;
        $this->hideAccount = $hideAccount;
        $this->hideCategory = $hideCategory;
        $this->hidePaymentMethods = $hidePaymentMethods;
        $this->hideReference = $hideReference;
        $this->hideDescription = $hideDescription;
        $this->hideAmount = $hideAmount;
        $this->textContentTitle = $this->getTextContentTitle($type, $textContentTitle);
        $this->textNumber = $this->getTextNumber($type, $textNumber);
        $this->textPaidAt = $this->getTextPaidAt($type, $textPaidAt);
        $this->textAccount = $this->getTextAccount($type, $textAccount);
        $this->textCategory = $this->getTextCategory($type, $textCategory);
        $this->textPaymentMethods = $this->getTextPaymentMethods($type, $textPaymentMethods);
        $this->textReference = $this->getTextReference($type, $textReference);
        $this->textDescription = $this->getTextDescription($type, $textDescription);
        $this->textAmount = $this->getTextAmount($type, $textAmount);
        $this->textPaidBy = $this->getTextPaidBy($type, $textPaidBy);
        $this->hideContact = $hideContact;
        $this->hideContactInfo = $hideContactInfo;
        $this->hideContactName = $hideContactName;
        $this->hideContactAddress = $hideContactAddress;
        $this->hideContactTaxNumber = $hideContactTaxNumber;
        $this->hideContactPhone = $hideContactPhone;
        $this->hideContactEmail = $hideContactEmail;
        $this->hideRelated = $hideRelated;
        $this->hideRelatedDocumentNumber = $hideRelatedDocumentNumber;
        $this->hideRelatedContact = $hideRelatedContact;
        $this->hideRelatedDocumentDate = $hideRelatedDocumentDate;
        $this->hideRelatedDocumentAmount = $hideRelatedDocumentAmount;
        $this->hideRelatedAmount = $hideRelatedAmount;
        $this->textRelatedTransansaction = $this->getTextRelatedTransansaction($type, $textRelatedTransansaction);
        $this->textRelatedDocumentNumber = $this->getTextRelatedDocumentNumber($type, $textRelatedDocumentNumber);
        $this->textRelatedContact = $this->getTextRelatedContact($type, $textRelatedContact);
        $this->textRelatedDocumentDate = $this->getTextRelatedDocumentDate($type, $textRelatedDocumentDate);
        $this->textRelatedDocumentAmount = $this->getTextRelatedDocumentAmount($type, $textRelatedDocumentAmount);
        $this->textRelatedAmount = $this->getTextRelatedAmount($type, $textRelatedAmount);
        $this->routeDocumentShow = $this->routeDocumentShow($type, $routeDocumentShow);
        $this->routeTransactionShow = $this->routeTransactionShow($type, $routeTransactionShow);
        $this->attachment = '';
        if (! empty($attachment)) {
            $this->attachment = $attachment;
        } else if (! empty($transaction)) {
            $this->attachment = $transaction->attachment;
        }
        $this->connectTranslations = $this->getTranslationsForConnect($type);
        $this->hideConnectMessage = $hideConnectMessage;
        $this->textRecurringType = $this->getTextRecurringType($type, $textRecurringType);
        $this->hideRecurringMessage = $hideRecurringMessage;
        $this->hideCreated = $hideCreated;
    }
    protected function getTransactionTemplate($type, $transactionTemplate)
    {
        if (! empty($transactionTemplate)) {
            return $transactionTemplate;
        }
        if ($template = config('type.transaction.' . $type . '.template', false)) {
            return $template;
        }
        $transactionTemplate = setting($this->getTransactionSettingKey($type, 'template')) ?: 'default';
        return $transactionTemplate;
    }
    protected function getLogo($logo)
    {
        if (! empty($logo)) {
            return $logo;
        }
        static $content;
        if (! empty($content)) {
            return $content;
        }
        $media_id = (!empty($this->transaction->contact->logo) && !empty($this->transaction->contact->logo->id)) ? $this->transaction->contact->logo->id : setting('company.logo');
        $media = Media::find($media_id);
        if (! empty($media)) {
            $path = $media->getDiskPath();
            if (! $media->fileExists()) {
                return $logo;
            }
        } else {
            $path = base_path('public/img/company.png');
        }
        try {
            $image = Image::cache(function($image) use ($media, $path) {
                $width = setting('invoice.logo_size_width');
                $height = setting('invoice.logo_size_height');
                if ($media) {
                    $image->make($media->contents())->resize($width, $height)->encode();
                } else {
                    $image->make($path)->resize($width, $height)->encode();
                }
            });
        } catch (NotReadableException | \Exception $e) {
            Log::info('Company ID: ' . company_id() . ' components/transactionshow.php exception.');
            Log::info($e->getMessage());
            $path = base_path('public/img/company.png');
            $image = Image::cache(function($image) use ($path) {
                $width = setting('invoice.logo_size_width');
                $height = setting('invoice.logo_size_height');
                $image->make($path)->resize($width, $height)->encode();
            });
        }
        if (empty($image)) {
            return $logo;
        }
        $extension = File::extension($path);
        $content = 'data:image/' . $extension . ';base64,' . base64_encode($image);
        return $content;
    }
    protected function getRouteButtonAddNew($type, $routeButtonAddNew)
    {
        if (! empty($routeButtonAddNew)) {
            return $routeButtonAddNew;
        }
        $parameter = 1;
        $route = $this->getRouteFromConfig($type, 'create', $parameter);
        if (! empty($route)) {
            return $route;
        }
        return 'transactions.create';
    }
    protected function getRouteButtonEdit($type, $routeButtonEdit)
    {
        if (! empty($routeButtonEdit)) {
            return $routeButtonEdit;
        }
        $parameter = 1;
        $route = $this->getRouteFromConfig($type, 'edit', $parameter);
        if (! empty($route)) {
            return $route;
        }
        return 'transactions.edit';
    }
    protected function getRouteButtonDuplicate($type, $routeButtonDuplicate)
    {
        if (! empty($routeButtonDuplicate)) {
            return $routeButtonDuplicate;
        }
        $parameter = 1;
        $route = $this->getRouteFromConfig($type, 'duplicate', $parameter);
        if (! empty($route)) {
            return $route;
        }
        return 'transactions.duplicate';
    }
    protected function getRouteButtonPrint($type, $routeButtonPrint)
    {
        if (! empty($routeButtonPrint)) {
            return $routeButtonPrint;
        }
        $parameter = 1;
        $route = $this->getRouteFromConfig($type, 'print', $parameter);
        if (! empty($route)) {
            return $route;
        }
        return 'transactions.print';
    }
    protected function getShareRoute($type, $shareRoute)
    {
        if (! empty($shareRoute)) {
            return $shareRoute;
        }
        $parameter = 1;
        $route = $this->getRouteFromConfig($type, 'share', $parameter);
        if (! empty($route)) {
            return $route;
        }
        return 'modals.transactions.share.create';
    }
    protected function getSignedUrl($type, $signedUrl)
    {
        if (! empty($signedUrl)) {
            return $signedUrl;
        }
        $page = config('type.transaction.' . $type . '.route.prefix');
        $alias = config('type.transaction.' . $type . '.alias');
        $route = '';
        if (! empty($alias)) {
            $route .= $alias . '.';
        }
        $route .= 'signed.' . $page . '.show';
        try {
            route($route, [$this->transaction->id, 'company_id' => company_id()]);
            $signedUrl = URL::signedRoute($route, [$this->transaction->id]);
        } catch (\Exception $e) {
            $signedUrl = URL::signedRoute('signed.payments.show', [$this->transaction->id]);
        }
        return $signedUrl;
    }
    protected function getRouteButtonEmail($type, $routeButtonEmail)
    {
        if (! empty($routeButtonEmail)) {
            return $routeButtonEmail;
        }
        $parameter = 1;
        $route = $this->getRouteFromConfig($type, 'emails.create', $parameter, true);
        if (! empty($route)) {
            return $route;
        }
        return 'modals.transactions.emails.create';
    }
    protected function getRouteButtonPdf($type, $routeButtonPdf)
    {
        if (! empty($routeButtonPdf)) {
            return $routeButtonPdf;
        }
        $parameter = 1;
        $route = $this->getRouteFromConfig($type, 'pdf', $parameter);
        if (! empty($route)) {
            return $route;
        }
        return 'transactions.pdf';
    }
    protected function getRouteButtonEnd($type, $routeButtonEnd)
    {
        if (! empty($routeButtonEnd)) {
            return $routeButtonEnd;
        }
        $parameter = 1;
        $route = $this->getRouteFromConfig($type, 'end', $parameter);
        if (! empty($route)) {
            return $route;
        }
        return 'recurring-transactions.end';
    }
    protected function getRouteButtonDelete($type, $routeButtonDelete)
    {
        if (! empty($routeButtonDelete)) {
            return $routeButtonDelete;
        }
        $parameter = 1;
        $route = $this->getRouteFromConfig($type, 'destroy', $parameter);
        if (! empty($route)) {
            return $route;
        }
        return 'transactions.destroy';
    }
    protected function getRouteContactShow($type, $routeContactShow)
    {
        if (! empty($routeContactShow)) {
            return $routeContactShow;
        }
        $parameter = 1;
        $route = Str::plural(config('type.transaction.' . $type . '.contact_type'), 2) . '.show';
        try {
            route($route, $parameter);
        } catch (\Exception $e) {
            try {
                $route = Str::plural($type, 2) . '.' . $config_key;
                route($route, $parameter);
            } catch (\Exception $e) {
                $route = '';
            }
        }
        if (! empty($route)) {
            return $route;
        }
        return 'customers.show';
    }
    protected function getTextButtonAddNew($type, $textButtonAddNew)
    {
        if (! empty($textButtonAddNew)) {
            return $textButtonAddNew;
        }
        $translation = $this->getTextFromConfig($type, 'transactions');
        if (! empty($translation)) {
            return trans('general.title.new', ['type' => trans_choice($translation, 1)]);
        }
        return trans('general.title.new', ['type' => trans_choice('general.' . Str::plural($type), 1)]);
    }
    protected function getTextContentTitle($type, $textContentTitle)
    {
        if (! empty($textContentTitle)) {
            return $textContentTitle;
        }
        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $default_key = 'payment_made';
                break;
            default:
                $default_key = 'receipts';
                break;
        }
        $translation = $this->getTextFromConfig($type, $type . '_made', $default_key);
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.receipts';
    }
    protected function getTextNumber($type, $textNumber)
    {
        if (! empty($textNumber)) {
            return $textNumber;
        }
        return 'general.numbers';
    }
    protected function getTextPaidAt($type, $textPaidAt)
    {
        if (! empty($textPaidAt)) {
            return $textPaidAt;
        }
        $translation = $this->getTextFromConfig($type, 'paid_at', 'date');
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.date';
    }
    protected function getTextAccount($type, $textAccount)
    {
        if (! empty($textAccount)) {
            return $textAccount;
        }
        $translation = $this->getTextFromConfig($type, 'accounts', 'accounts', 'trans_choice');
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.accounts';
    }
    protected function getTextCategory($type, $textCategory)
    {
        if (! empty($textCategory)) {
            return $textCategory;
        }
        $translation = $this->getTextFromConfig($type, 'categories', 'categories', 'trans_choice');
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.categories';
    }
    protected function getTextPaymentMethods($type, $textPaymentMethods)
    {
        if (! empty($textPaymentMethods)) {
            return $textPaymentMethods;
        }
        $translation = $this->getTextFromConfig($type, 'payment_methods', 'payment_methods', 'trans_choice');
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.payment_methods';
    }
    protected function getTextReference($type, $textReference)
    {
        if (! empty($textReference)) {
            return $textReference;
        }
        $translation = $this->getTextFromConfig($type, 'reference', 'reference');
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.reference';
    }
    protected function getTextDescription($type, $textDescription)
    {
        if (! empty($textDescription)) {
            return $textDescription;
        }
        $translation = $this->getTextFromConfig($type, 'description', 'description');
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.description';
    }
    protected function getTextAmount($type, $textAmount)
    {
        if (! empty($textAmount)) {
            return $textAmount;
        }
        $translation = $this->getTextFromConfig($type, 'amount', 'amount');
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.amount';
    }
    protected function getTextPaidBy($type, $textPaidBy)
    {
        if (! empty($textPaidBy)) {
            return $textPaidBy;
        }
        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $default_key = 'paid_to';
                break;
            default:
                $default_key = 'paid_by';
                break;
        }
        $translation = $this->getTextFromConfig($type, 'paid_to_by', $default_key);
        if (! empty($translation)) {
            return $translation;
        }
        return 'transactions.paid_by';
    }
    protected function getTextRelatedTransansaction($type, $textRelatedTransansaction)
    {
        if (! empty($textRelatedTransansaction)) {
            return $textRelatedTransansaction;
        }
        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $default_key = 'related_bill';
                break;
            default:
                $default_key = 'related_invoice';
                break;
        }
        $translation = $this->getTextFromConfig($type, 'related_type', $default_key);
        if (! empty($translation)) {
            return $translation;
        }
        return 'transactions.related_invoice';
    }
    protected function getTextRelatedDocumentNumber($type, $textRelatedDocumentNumber)
    {
        if (! empty($textRelatedDocumentNumber)) {
            return $textRelatedDocumentNumber;
        }
        $translation = $this->getTextFromConfig($type, 'related_document_number', 'numbers');
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.numbers';
    }
    protected function getTextRelatedContact($type, $textRelatedContact)
    {
        if (! empty($textRelatedContact)) {
            return $textRelatedContact;
        }
        $default_key = Str::plural(config('type.transaction.' . $type . '.contact_type'), 2);
        $translation = $this->getTextFromConfig($type, 'related_contact', $default_key, 'trans_choice');
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.customers';
    }
    protected function getTextRelatedDocumentDate($type, $textRelatedDocumentDate)
    {
        if (! empty($textRelatedDocumentDate)) {
            return $textRelatedDocumentDate;
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
        $translation = $this->getTextFromConfig($type, 'related_document_date', $default_key);
        if (! empty($translation)) {
            return $translation;
        }
        return 'invoices.invoice_date';
    }
    protected function getTextRelatedDocumentAmount($type, $textRelatedDocumentAmount)
    {
        if (! empty($textRelatedDocumentAmount)) {
            return $textRelatedDocumentAmount;
        }
        switch ($type) {
            case 'bill':
            case 'expense':
            case 'purchase':
                $default_key = 'bill_amount';
                break;
            default:
                $default_key = 'invoice_amount';
                break;
        }
        $translation = $this->getTextFromConfig($type, 'related_document_amount', $default_key);
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.amount';
    }
    protected function getTextRelatedAmount($type, $textRelatedAmount)
    {
        if (! empty($textRelatedAmount)) {
            return $textRelatedAmount;
        }
        $translation = $this->getTextFromConfig($type, 'related_amount', 'amount');
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.amount';
    }
    protected function routeDocumentShow($type, $routeDocumentShow)
    {
        if (! empty($routeDocumentShow)) {
            return $routeDocumentShow;
        }
        if (! $this->transaction->document) {
            return $routeDocumentShow;
        }
        $parameter = 1;
        $route = $this->getRouteFromConfig($this->transaction->document->type, 'show', $parameter);
        if (! empty($route)) {
            return $route;
        }
        return 'invoices.show';
    }
    protected function routeTransactionShow($type, $routeTransactionShow)
    {
        if (! empty($routeTransactionShow)) {
            return $routeTransactionShow;
        }
        $parameter = 1;
        $route = $this->getRouteFromConfig($type, 'show', $parameter);
        if (! empty($route)) {
            return $route;
        }
        return 'transactions.show';
    }
    protected function getTextRecurringType($type, $textRecurringType)
    {
        if (! empty($textRecurringType)) {
            return $textRecurringType;
        }
        $translation = config('type.transaction.' . $type . '.translation.transactions');
        if (! empty($translation)) {
            return $translation;
        }
        return 'general.incomes';
    }
}
