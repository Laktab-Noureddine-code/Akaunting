<?php
namespace App\Http\Controllers\Api\Document;
use App\Abstracts\Http\ApiController;
use App\Http\Requests\Banking\Transaction as Request;
use App\Http\Resources\Banking\Transaction as Resource;
use App\Jobs\Banking\CreateBankingDocumentTransaction;
use App\Jobs\Banking\UpdateBankingDocumentTransaction;
use App\Jobs\Banking\DeleteTransaction;
use App\Models\Banking\Transaction;
use App\Models\Document\Document;
class DocumentTransactions extends ApiController
{
    public function __construct()
    {
        $this->middleware('permission:create-banking-transactions')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-banking-transactions')->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-banking-transactions')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-banking-transactions')->only('destroy');
    }
    public function index($document_id)
    {
        $transactions = Transaction::with(['document', 'taxes'])->documentId($document_id)->get();
        return Resource::collection($transactions);
    }
    public function show($document_id, $id)
    {
        $transaction = Transaction::with(['document', 'taxes'])->documentId($document_id)->find($id);
        if (! $transaction instanceof Transaction) {
            return $this->errorInternal('No query results for model [' . Transaction::class . '] ' . $id);
        }
        return new Resource($transaction);
    }
    public function store($document_id, Request $request)
    {
        $document = Document::find($document_id);
        $transaction = $this->dispatch(new CreateBankingDocumentTransaction($document, $request));
        return $this->created(route('api.documents.transactions.show', [$document_id, $transaction->id]), new Resource($transaction));
    }
    public function update($document_id, $id, Request $request)
    {
        $document = Document::find($document_id);
        $transaction = Transaction::documentId($document_id)->find($id);
        $transaction = $this->dispatch(new UpdateBankingDocumentTransaction($document, $transaction, $request));
        return $this->created(route('api.documents.transactions.show', [$document_id, $transaction->id]), new Resource($transaction));
    }
    public function destroy($document_id, $id)
    {
        $transaction = Transaction::documentId($document_id)->find($id);
        $this->dispatch(new DeleteTransaction($transaction));
        return $this->noContent();
    }
}
