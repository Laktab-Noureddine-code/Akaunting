<?php
namespace App\Http\Controllers\Api\Common;
use App\Abstracts\Http\ApiController;
use App\Http\Requests\Common\Contact as Request;
use App\Http\Resources\Common\Contact as Resource;
use App\Jobs\Common\CreateContact;
use App\Jobs\Common\DeleteContact;
use App\Jobs\Common\UpdateContact;
use App\Models\Common\Contact;
use App\Traits\Uploads;
class Contacts extends ApiController
{
    use Uploads;
    public function index()
    {
        $contacts = Contact::collect();
        return Resource::collection($contacts);
    }
    public function show($id)
    {
        if (is_numeric($id)) {
            $contact = Contact::find($id);
        } else {
            $contact = Contact::where('email', $id)->first();
        }
        if (! $contact instanceof Contact) {
            return $this->errorInternal('No query results for model [' . Contact::class . '] ' . $id);
        }
        return new Resource($contact);
    }
    public function store(Request $request)
    {
        $contact = $this->dispatch(new CreateContact($request));
        return $this->created(route('api.contacts.show', $contact->id), new Resource($contact));
    }
    public function update(Contact $contact, Request $request)
    {
        $contact = $this->dispatch(new UpdateContact($contact, $request));
        return new Resource($contact->fresh());
    }
    public function enable(Contact $contact)
    {
        try {
            $contact = $this->dispatch(new UpdateContact($contact, request()->merge(['enabled' => 1])));
            return new Resource($contact->fresh());
        } catch (\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function disable(Contact $contact)
    {
        try {
            $contact = $this->dispatch(new UpdateContact($contact, request()->merge(['enabled' => 0])));
            return new Resource($contact->fresh());
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
    public function destroy(Contact $contact)
    {
        try {
            $this->dispatch(new DeleteContact($contact));
            return $this->noContent();
        } catch(\Exception $e) {
            $this->errorUnauthorized($e->getMessage());
        }
    }
}
