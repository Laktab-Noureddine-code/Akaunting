<?php
namespace App\Http\Controllers\Api\Banking;
use App\Abstracts\Http\ApiController;
use App\Http\Requests\Banking\Transaction as Request;
use App\Http\Resources\Banking\Transaction as Resource;
use App\Jobs\Banking\CreateTransaction;
use App\Jobs\Banking\DeleteTransaction;
use App\Jobs\Banking\UpdateTransaction;
use App\Models\Banking\Transaction;
class Transactions extends ApiController
{
    public function index()
    {
        $transactions = Transaction::with('account', 'category', 'contact')->collect(['paid_at'=> 'desc']);
        return Resource::collection($transactions);
    }
    public function show(Transaction $transaction)
    {
        return new Resource($transaction);
    }
    public function store(Request $request)
    {
        if ($request->has('document_id')) {
            return $this->errorBadRequest(trans('transactions.messages.create_document_transaction_error'));
        }
        $transaction = $this->dispatch(new CreateTransaction($request));
        return $this->created(route('api.transactions.show', $transaction->id), new Resource($transaction));
    }
    public function update(Transaction $transaction, Request $request)
    {
        if ($request->has('document_id')) {
            return $this->errorBadRequest(trans('transactions.messages.update_document_transaction_error'));
        }
        $transaction = $this->dispatch(new UpdateTransaction($transaction, $request));
        return new Resource($transaction->fresh());
    }
    public function destroy(Transaction $transaction)
    {
        if ($transaction->document_id) {
            return $this->errorBadRequest(trans('transactions.messages.delete_document_transaction_error'));
        }
        try {
            $this->dispatch(new DeleteTransaction($transaction));
            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
