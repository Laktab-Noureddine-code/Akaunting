<?php
namespace App\Http\Controllers\Common;
use App\Abstracts\Http\Controller;
use App\Models\Common\Media;
use App\Traits\Uploads as Helper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
class Uploads extends Controller
{
    use Helper;
    public function get($id)
    {
        try {
            $media = Media::find($id);
        } catch (\Exception $e) {
            return response(null, 204);
        }
        if (!$this->getMediaPathOnStorage($media)) {
            return response(null, 204);
        }
        return $this->streamMedia($media);
    }
    public function inline($id)
    {
        try {
            $media = Media::find($id);
        } catch (\Exception $e) {
            return response(null, 204);
        }
        if (!$this->getMediaPathOnStorage($media)) {
            return response(null, 204);
        }
        return $this->streamMedia($media, 'inline');
    }
    public function show($id, Request $request)
    {
        $file = false;
        $options = false;
        $column_name = 'attachment';
        if ($request->has('column_name')) {
            $column_name = $request->get('column_name');
        }
        if ($request->has('page')) {
            $options = [
                'page' => $request->get('page'),
                'key' => $request->get('key'),
            ];
        }
        try {
            $media = Media::find($id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => true,
                'data'    => [],
                'message' => 'null',
                'html'    => '',
            ]);
        }
        if (!$this->getMediaPathOnStorage($media)) {
            return response()->json([
                'success' => false,
                'error'   => true,
                'data'    => [],
                'message' => 'null',
                'html'    => '',
            ]);
        }
        $file = $media;
        $html = view('components.media.file', compact('file', 'column_name', 'options'))->render();
        return response()->json([
            'success' => true,
            'error'   => false,
            'data'    => [],
            'message' => 'null',
            'html'    => $html,
        ]);
    }
    public function download($id)
    {
        try {
            $media = Media::find($id);
        } catch (\Exception $e) {
            return false;
        }
        if (!$this->getMediaPathOnStorage($media)) {
            return false;
        }
        return $this->streamMedia($media);
    }
    public function destroy($id, Request $request)
    {
        $return = back();
        if ($request->has('ajax') && $request->get('ajax')) {
            $return = [
                'success' => true,
                'errors' => false,
                'message' => '',
                'redirect' => $request->get('redirect')
            ];
        }
        try {
            $media = Media::find($id);
        } catch (\Exception $e) {
            return $return;
        }
        if (!$path = $this->getMediaPathOnStorage($media)) {
            $message = trans('messages.warning.deleted', ['name' => $media->basename, 'text' => $media->basename]);
            flash($message)->warning()->important();
            return $return;
        }
        $media->delete(); 
        Storage::delete($path);
        if (!empty($request->input('page'))) {
            switch ($request->input('page')) {
                case 'setting':
                    setting()->set($request->input('key'), '');
                    setting()->save();
                    break;
            }
        }
        return $return;
    }
}
