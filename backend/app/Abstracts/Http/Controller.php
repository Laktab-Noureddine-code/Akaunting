<?php
namespace App\Abstracts\Http;
use App\Abstracts\Http\Response;
use App\Traits\Jobs;
use App\Traits\Permissions;
use App\Traits\Relationships;
use App\Traits\SearchString;
use App\Utilities\Export;
use App\Utilities\Import;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
abstract class Controller extends BaseController
{
    use AuthorizesRequests, Jobs, Permissions, Relationships, SearchString, ValidatesRequests;
    public function __construct()
    {
        $this->assignPermissionsToController();
    }
    public function paginate($items, $perPage = null, $page = null, $options = [])
    {
        $perPage = $perPage ?: (int) request('limit', setting('default.list_limit', '25'));
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
    public function response($view, $data = [])
    {
        $class_name = str_replace('Controllers', 'Responses', get_class($this));
        if (class_exists($class_name)) {
            $response = new $class_name($view, $data);
        } else {
            $response = new class($view, $data) extends Response {};
        }
        return $response;
    }
    public function importExcel($class, $request, $translation)
    {
        return Import::fromExcel($class, $request, $translation);
    }
    public function exportExcel($class, $translation, $extension = 'xlsx')
    {
        return Export::toExcel($class, $translation, $extension);
    }
    public function setActiveTabForDocuments(): void
    {
        if (! request()->has('list_records') && ! request()->has('search')) {
            $tab_pins = setting('favorites.tab.' . user()->id, []);
            $tab_pins = ! empty($tab_pins) ? json_decode($tab_pins, true) : [];
            if (! empty($tab_pins) && ! empty($tab_pins[$this->type])) {
                $data = config('type.document.' . $this->type . '.route.params.' . $tab_pins[$this->type]);
                if (! empty($data)) {
                    request()->merge($data);
                }
            }
        }
        if (request()->get('list_records') == 'all') {
            return;
        }
        $status = $this->getSearchStringValue('status');
        if (empty($status)) {
            $search = config('type.document.' . $this->type . '.route.params.unpaid.search');
            request()->offsetSet('search', $search);
            request()->offsetSet('programmatic', '1');
        } else {
            $unpaid = str_replace('status:', '', config('type.document.' . $this->type . '.route.params.unpaid.search'));
            $draft = str_replace('status:', '', config('type.document.' . $this->type . '.route.params.draft.search'));
            if (($status == $unpaid) || ($status == $draft)) {
                return;
            }
            request()->offsetSet('list_records', 'all');
        }
    }
    public function setActiveTabForTransactions(): void
    {
        if (! request()->has('list_records') && ! request()->has('search')) {
            $tab_pins = setting('favorites.tab.' . user()->id, []);
            $tab_pins = ! empty($tab_pins) ? json_decode($tab_pins, true) : [];
            if (! empty($tab_pins) && ! empty($tab_pins['transactions'])) {
                $data = config('type.transaction.transactions.route.params.' . $tab_pins['transactions']);
                if (! empty($data)) {
                    request()->merge($data);
                }
            }
        }
    }
}
