<?php
namespace App\Http\Controllers\Api\Banking;
use App\Abstracts\Http\ApiController;
use App\Http\Requests\Banking\Transfer as Request;
use App\Http\Resources\Banking\Transfer as Resource;
use App\Jobs\Banking\CreateTransfer;
use App\Jobs\Banking\UpdateTransfer;
use App\Jobs\Banking\DeleteTransfer;
use App\Models\Banking\Transfer;
class Transfers extends ApiController
{
    public function index()
    {
        $transfers = Transfer::with(
            'expense_transaction', 'expense_transaction.account', 'income_transaction', 'income_transaction.account'
        )->collect('expense_transaction.paid_at');
        $special_key = [
            'expense_transaction.name' => 'from_account',
            'income_transaction.name' => 'to_account',
        ];
        $request = request();
        if (isset($request['sort']) && array_key_exists($request['sort'], $special_key)) {
            $items = $transfers->items();
            $sort_order = [];
            foreach ($items as $key => $value) {
                $sort = $request['sort'];
                if (array_key_exists($request['sort'], $special_key)) {
                    $sort = $special_key[$request['sort']];
                }
                $sort_order[$key] = $value->{$sort};
            }
            $sort_type = (isset($request['order']) && $request['order'] == 'asc') ? SORT_ASC : SORT_DESC;
            array_multisort($sort_order, $sort_type, $items);
            $transfers->setCollection(collect($items));
        }
        return Resource::collection($transfers);
    }
    public function show(Transfer $transfer)
    {
        return new Resource($transfer);
    }
    public function store(Request $request)
    {
        $transfer = $this->dispatch(new CreateTransfer($request));
        return $this->created(route('api.transfers.show', $transfer->id), new Resource($transfer));
    }
    public function update(Transfer $transfer, Request $request)
    {
        $transfer = $this->dispatch(new UpdateTransfer($transfer, $request));
        return new Resource($transfer->fresh());
    }
    public function destroy(Transfer $transfer)
    {
        try {
            $this->dispatch(new DeleteTransfer($transfer));
            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
