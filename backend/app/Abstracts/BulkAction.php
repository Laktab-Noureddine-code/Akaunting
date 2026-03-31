<?php
namespace App\Abstracts;
use App\Jobs\Common\CreateMediableForDownload;
use App\Jobs\Common\CreateZipForDownload;
use App\Jobs\Common\DeleteContact;
use App\Jobs\Common\UpdateContact;
use App\Jobs\Banking\DeleteTransaction;
use App\Traits\Jobs;
use App\Traits\Relationships;
use App\Traits\Translations;
use App\Utilities\Export;
use App\Utilities\Import;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Bus;
use Throwable;
abstract class BulkAction
{
    use Jobs, Relationships, Translations;
    public $model = false;
    public $actions = [
        'enable'    => [
            'name'          => 'general.enable',
            'message'       => 'bulk_actions.message.enable',
            'permission'    => 'update-common-items',
        ],
        'disable'   => [
            'name'          => 'general.disable',
            'message'       => 'bulk_actions.message.disable',
            'permission'    => 'update-common-items',
        ],
        'delete'    => [
            'name'          => 'general.delete',
            'message'       => 'bulk_actions.message.delete',
            'permission'    => 'delete-common-items',
        ],
        'export'    => [
            'name'          => 'general.export',
            'message'       => 'bulk_actions.message.export',
            'type'          => 'download'
        ],
        'download' => [
            'name'          => 'general.download',
            'message'       => 'bulk_actions.message.download',
            'type'          => 'download',
        ],
    ];
    public $icons = [
        'enable'        => 'check_circle',
        'disable'       => 'hide_source',
        'delete'        => 'delete',
        'duplicate'     => 'file_copy',
        'export'        => 'file_download',
        'download'      => 'download',
        'reconcile'     => 'published_with_changes',
        'unreconcile'   => 'layers_clear',
        'received'      => 'call_received',
        'cancelled'     => 'cancel',
        'sent'          => 'send',
        'approved'      => 'approval',
        'refused'       => 'do_not_disturb_on',
        'issued'        => 'mark_email_read',
        'confirmed'     => 'thumb_up_alt',
    ];
    public $messages = [
        'general'   => 'bulk_actions.success.general',
        'enable'    => 'messages.success.enabled',
        'disable'   => 'messages.success.disabled',
        'delete'    => 'messages.success.deleted',
        'duplicate' => 'messages.success.duplicated',
        'invite'    => 'messages.success.invited',
        'end'       => 'messages.success.ended',
    ];
    public function getSelectedRecords($request, $relationships = null)
    {
        if (empty($relationships)) {
            $model = $this->model::query();
        } else {
            $relationships = Arr::wrap($relationships);
            $model = $this->model::with($relationships);
        }
        return $model->find($this->getSelectedInput($request));
    }
    public function getSelectedInput($request)
    {
        return $request->get('selected', []);
    }
    public function getUpdateRequest($request)
    {
        foreach ($request->all() as $key => $value) {
            if (empty($value)) {
                unset($request[$key]);
            }
        }
        return $request;
    }
    public function response($view, $data = [])
    {
        $method = request()->get('handle', 'edit');
        $handle = $this->actions[$method]['handle'] ?? 'update';
        $url = route('bulk-actions.action', $this->path);
        $html = view('components.index.bulkaction.modal', [
            'url' => $url,
            'handle' => $handle,
            'selected' => $data['selected'] ?? $this->getSelectedInput(request()),
            'html' => view($view, $data)->render(),
        ])->render();
        return response()->json([
            'success' => true,
            'error' => false,
            'message' => '',
            'data' => [
                'title' => $this->findTranslation($this->text),
                'path' => $url,
                'handle' => $handle,
            ],
            'html' => $html,
        ]);
    }
    public function duplicate($request)
    {
        $items = $this->getSelectedRecords($request);
        foreach ($items as $item) {
            $item->duplicate();
        }
    }
    public function enable($request)
    {
        $items = $this->getSelectedRecords($request);
        foreach ($items as $item) {
            $item->enabled = true;
            $item->save();
        }
    }
    public function disable($request)
    {
        $items = $this->getSelectedRecords($request);
        foreach ($items as $item) {
            $item->enabled = false;
            $item->save();
        }
    }
    public function delete($request)
    {
        $this->destroy($request);
    }
    public function destroy($request)
    {
        $items = $this->getSelectedRecords($request);
        foreach ($items as $item) {
            $item->delete();
        }
    }
    public function disableContacts($request)
    {
        $contacts = $this->getSelectedRecords($request, 'user');
        foreach ($contacts as $contact) {
            try {
                $this->dispatch(new UpdateContact($contact, request()->merge(['enabled' => 0])));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }
    public function deleteContacts($request)
    {
        $contacts = $this->getSelectedRecords($request, 'user');
        foreach ($contacts as $contact) {
            try {
                $this->dispatch(new DeleteContact($contact));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }
    public function deleteTransactions($request)
    {
        $transactions = $this->getSelectedRecords($request, 'category');
        foreach ($transactions as $transaction) {
            try {
                $this->dispatch(new DeleteTransaction($transaction));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }
    public function importExcel($class, $request, $translation)
    {
        return Import::fromExcel($class, $request, $translation);
    }
    public function exportExcel($class, $translation, $extension = 'xlsx')
    {
        return Export::toExcel($class, $translation, $extension);
    }
    public function downloadPdf($selected, $class, $file_name, $translation)
    {
        try {
            if (should_queue()) {
                $batch[] = new CreateZipForDownload($selected, $class, $file_name);
                $batch[] = new CreateMediableForDownload(user(), $file_name, $translation);
                Bus::chain($batch)->onQueue('jobs')->dispatch();
                $message = trans('messages.success.download_queued', ['type' => $translation]);
                flash($message)->success();
                return back();
            } else {
                $this->dispatch(new CreateZipForDownload($selected, $class, $file_name));
                $folder_path = 'app' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . company_id() . DIRECTORY_SEPARATOR . 'bulk_actions' . DIRECTORY_SEPARATOR;
                return response()->download(get_storage_path($folder_path . $file_name . '.zip'))->deleteFileAfterSend(true);
            }
        } catch (Throwable $e) {
            report($e);
            flash($e->getMessage())->error()->important();
            return back();
        }
    }
}
