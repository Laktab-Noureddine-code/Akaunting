<?php
namespace App\Http\Controllers\Api\Settings;
use App\Abstracts\Http\ApiController;
use App\Http\Requests\Setting\Tax as Request;
use App\Http\Resources\Setting\Tax as Resource;
use App\Jobs\Setting\CreateTax;
use App\Jobs\Setting\DeleteTax;
use App\Jobs\Setting\UpdateTax;
use App\Models\Setting\Tax;
class Taxes extends ApiController
{
    public function index()
    {
        $taxes = Tax::collect();
        return Resource::collection($taxes);
    }
    public function show(Tax $tax)
    {
        return new Resource($tax);
    }
    public function store(Request $request)
    {
        $tax = $this->dispatch(new CreateTax($request));
        return $this->created(route('api.taxes.show', $tax->id), new Resource($tax));
    }
    public function update(Tax $tax, Request $request)
    {
        try {
            $tax = $this->dispatch(new UpdateTax($tax, $request));
            return new Resource($tax->fresh());
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function enable(Tax $tax)
    {
        try {
            $tax = $this->dispatch(new UpdateTax($tax, request()->merge(['enabled' => 1])));
            return new Resource($tax->fresh());
        } catch (\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function disable(Tax $tax)
    {
        try {
            $tax = $this->dispatch(new UpdateTax($tax, request()->merge(['enabled' => 0])));
            return new Resource($tax->fresh());
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function destroy(Tax $tax)
    {
        try {
            $this->dispatch(new DeleteTax($tax));
            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
