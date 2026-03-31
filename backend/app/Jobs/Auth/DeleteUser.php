<?php
namespace App\Jobs\Auth;
use App\Abstracts\Job;
use App\Events\Auth\UserDeleted;
use App\Events\Auth\UserDeleting;
use App\Interfaces\Job\ShouldDelete;
class DeleteUser extends Job implements ShouldDelete
{
    public function handle(): bool
    {
        $this->authorize();
        event(new UserDeleting($this->model));
        \DB::transaction(function () {
            $this->deleteRelationships($this->model, ['invitation']);
            $this->model->delete();
            $this->model->flushCache();
        });
        event(new UserDeleted($this->model));
        return true;
    }
    public function authorize(): void
    {
        if ($this->model->id == user()->id) {
            $message = trans('auth.error.self_delete');
            throw new \Exception($message);
        }
    }
}
