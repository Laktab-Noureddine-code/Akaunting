<?php
namespace App\Http\Controllers\Modals;
use App\Abstracts\Http\Controller;
use App\Http\Requests\Setting\Category as Request;
use App\Jobs\Setting\CreateCategory;
use App\Models\Setting\Category;
use Illuminate\Http\Request as IRequest;
class Categories extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create-settings-categories')->only('create', 'store', 'duplicate', 'import');
        $this->middleware('permission:read-settings-categories')->only('index', 'show', 'edit', 'export');
        $this->middleware('permission:update-settings-categories')->only('update', 'enable', 'disable');
        $this->middleware('permission:delete-settings-categories')->only('destroy');
    }
    public function create(IRequest $request)
    {
        $type = $request->get('type', 'item');
        $categories = collect();
        Category::type($type)->enabled()->orderBy('name')->get()->each(function ($category) use (&$categories) {
            $categories->push([
                'id' => $category->id,
                'title' => $category->name,
                'level' => $category->level,
            ]);
        });
        $html = view('modals.categories.create', compact('type', 'categories'))->render();
        return response()->json([
            'success' => true,
            'error' => false,
            'message' => 'null',
            'html' => $html,
        ]);
    }
    public function store(Request $request)
    {
        $request['enabled'] = 1;
        $request['type'] = $request->get('type', 'income');
        $request['color'] = $request->get('color', '
        $response = $this->ajaxDispatch(new CreateCategory($request));
        if ($response['success']) {
            $response['message'] = trans('messages.success.created', ['type' => trans_choice('general.categories', 1)]);
        }
        return response()->json($response);
    }
}
