<?php
namespace App\Http\Controllers\Api\Banking;
use App\Abstracts\Http\ApiController;
use App\Http\Requests\Banking\Reconciliation as Request;
use App\Http\Resources\Banking\Reconciliation as Resource;
use App\Jobs\Banking\CreateReconciliation;
use App\Jobs\Banking\DeleteReconciliation;
use App\Jobs\Banking\UpdateReconciliation;
use App\Models\Banking\Reconciliation;
class Reconciliations extends ApiController
{
    public function index()
    {
        $reconciliations = Reconciliation::with('account')->collect();
        return Resource::collection($reconciliations);
    }
    public function show(Reconciliation $reconciliation)
    {
        return new Resource($reconciliation);
    }
    public function store(Request $request)
    {
        $reconciliation = $this->dispatch(new CreateReconciliation($request));
        return $this->created(route('api.reconciliations.show', $reconciliation->id), new Resource($reconciliation));
    }
    public function update(Reconciliation $reconciliation, Request $request)
    {
        $reconciliation = $this->dispatch(new UpdateReconciliation($reconciliation, $request));
        return new Resource($reconciliation->fresh());
    }
    public function destroy(Reconciliation $reconciliation)
    {
        try {
            $this->dispatch(new DeleteReconciliation($reconciliation));
            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
