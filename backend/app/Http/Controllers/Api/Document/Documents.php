<?php
namespace App\Http\Controllers\Api\Document;
use App\Abstracts\Http\ApiController;
use App\Http\Requests\Document\Document as Request;
use App\Http\Resources\Document\Document as Resource;
use App\Events\Document\DocumentReceived;
use App\Jobs\Document\CreateDocument;
use App\Jobs\Document\DeleteDocument;
use App\Jobs\Document\UpdateDocument;
use App\Models\Document\Document;
class Documents extends ApiController
{
    public function index()
    {
        $documents = Document::with('contact', 'histories', 'items', 'item_taxes', 'totals', 'transactions')->collect(['issued_at'=> 'desc']);
        return Resource::collection($documents);
    }
    public function show($id)
    {
        if (is_numeric($id)) {
            $document = Document::with([
                'contact',
                'histories',
                'items',
                'items.taxes',
                'items.taxes.tax',
                'item_taxes',
                'totals',
                'transactions',
                'transactions.currency',
                'transactions.account',
                'transactions.category',
            ])->find($id);
        } else {
            $document = Document::with([
                'contact',
                'histories',
                'items',
                'items.taxes',
                'items.taxes.tax',
                'item_taxes',
                'totals',
                'transactions',
                'transactions.currency',
                'transactions.account',
                'transactions.category',
            ])->where('document_number', $id)->first();
        }
        if (! $document instanceof Document) {
            return $this->errorInternal('No query results for model [' . Document::class . '] ' . $id);
        }
        return new Resource($document);
    }
    public function store(Request $request)
    {
        $document = $this->dispatch(new CreateDocument($request));
        return $this->created(route('api.documents.show', $document->id), new Resource($document));
    }
    public function update(Document $document, Request $request)
    {
        $document = $this->dispatch(new UpdateDocument($document, $request));
        return new Resource($document->fresh());
    }
    public function received(Document $document)
    {
        try {
            event(new DocumentReceived($document));
            return new Resource($document->fresh());
        } catch (\Exception $e) {
            $this->errorInternal($e->getMessage());
        }
    }
    public function destroy(Document $document)
    {
        try {
            $this->dispatch(new DeleteDocument($document));
            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
