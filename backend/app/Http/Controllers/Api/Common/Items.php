<?php
namespace App\Http\Controllers\Api\Common;
use App\Abstracts\Http\ApiController;
use App\Http\Requests\Common\Item as Request;
use App\Http\Resources\Common\Item as Resource;
use App\Jobs\Common\CreateItem;
use App\Jobs\Common\DeleteItem;
use App\Jobs\Common\UpdateItem;
use App\Models\Common\Item;
class Items extends ApiController
{
    public function index()
    {
        $items = Item::with('category', 'taxes')->collect();
        return Resource::collection($items);
    }
    public function show($id)
    {
        $item = Item::with('category', 'taxes')->find($id);
        if (! $item instanceof Item) {
            return $this->errorInternal('No query results for model [' . Item::class . '] ' . $id);
        }
        return new Resource($item);
    }
    public function store(Request $request)
    {
        $item = $this->dispatch(new CreateItem($request));
        return $this->created(route('api.items.show', $item->id), new Resource($item));
    }
    public function update(Item $item, Request $request)
    {
        $item = $this->dispatch(new UpdateItem($item, $request));
        return new Resource($item->fresh());
    }
    public function enable(Item $item)
    {
        try {
            $item = $this->dispatch(new UpdateItem($item, request()->merge(['enabled' => 1])));
            return new Resource($item->fresh());
        } catch (\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function disable(Item $item)
    {
        try {
            $item = $this->dispatch(new UpdateItem($item, request()->merge(['enabled' => 0])));
            return new Resource($item->fresh());
        } catch (\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function destroy(Item $item)
    {
        try {
            $this->dispatch(new DeleteItem($item));
            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
