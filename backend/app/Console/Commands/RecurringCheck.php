<?php
namespace App\Console\Commands;
use App\Events\Banking\TransactionCreated;
use App\Events\Banking\TransactionRecurring;
use App\Events\Document\DocumentCreated;
use App\Events\Document\DocumentRecurring;
use App\Models\Banking\Transaction;
use App\Models\Common\Company;
use App\Models\Common\Recurring;
use App\Models\Document\Document;
use App\Utilities\Date;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Recurr\RecurrenceCollection;
class RecurringCheck extends Command
{
    protected $signature = 'recurring:check';
    protected $description = 'Check for recurring';
    public function handle()
    {
        $this->info('Checking for recurring...');
        app()->instance(static::class, $this);
        config(['laravel-model-caching.enabled' => false]);
        $recurring = Recurring::with('company')
                                ->active()
                                ->allCompanies()
                                ->cursor();
        foreach ($recurring as $recur) {
            if (empty($recur->company)) {
                $this->info('Missing company.');
                $recur->delete();
                continue;
            }
            $this->info('Recurring ID: ' . $recur->id);
            $company_name = !empty($recur->company->name) ? $recur->company->name : 'Missing Company Name : ' . $recur->company->id;
            $template = $recur->recurable()->where('company_id', $recur->company_id)->first();
            if (! $recur->company->enabled) {
                $this->info($company_name . ' company is disabled. Skipping...');
                if (Date::parse($recur->company->updated_at)->format('Y-m-d') > Date::now()->subMonth(3)->format('Y-m-d')) {
                    $recur->delete();
                    if ($template) {
                        $template->delete();
                    }
                }
                continue;
            }
            $has_active_users = false;
            foreach ($recur->company->users as $company_user) {
                if (Date::parse($company_user->last_logged_in_at)->format('Y-m-d') > Date::now()->subMonth(3)->format('Y-m-d')) {
                    $has_active_users = true;
                    break;
                }
            }
            if (! $has_active_users) {
                $this->info('No active users for ' . $company_name . ' company. Skipping...');
                $recur->delete();
                if ($template) {
                    $template->delete();
                }
                continue;
            }
            company($recur->company_id)->makeCurrent();
            if (! $template) {
                $this->info('Missing model.');
                $recur->delete();
                continue;
            }
            $this->info('Template ID: ' . $template->id);
            $schedules = $this->getRemainingSchedules($template, $recur);
            if ($schedules->count() == 0) {
                $this->info('All schedules created.');
                $recur->update(['status' => Recurring::COMPLETE_STATUS]);
                continue;
            }
            $schedules = $schedules->endsBefore($recur->getRecurringRuleTomorrowDate());
            if ($schedules->count() == 0) {
                $this->info('No schedules for today.');
                continue;
            }
            foreach ($schedules as $schedule) {
                $schedule_date = Date::parse($schedule->getStart()->format('Y-m-d'));
                $this->info('Schedule date: ' . $schedule_date->format('Y-m-d'));
                $this->recur($template, $schedule_date);
            }
        }
        Company::forgetCurrent();
        app()->forgetInstance(static::class);
        $this->info('Recurring check done!');
    }
    protected function recur(Document|Transaction $template, Date $schedule_date): void
    {
        DB::transaction(function () use ($template, $schedule_date) {
            if (! $model = $this->getModel($template, $schedule_date)) {
                return;
            }
            $this->info('Model created: ' . $model->id);
            switch ($template::class) {
                case Document::class:
                    event(new DocumentCreated($model, request()));
                    event(new DocumentRecurring($model));
                    break;
                case Transaction::class:
                    event(new TransactionCreated($model));
                    event(new TransactionRecurring($model));
                    break;
            }
        });
    }
    protected function getModel(Document|Transaction $template, Date $schedule_date): Document|Transaction
    {
        $function = ($template instanceof Transaction) ? 'getTransactionModel' : 'getDocumentModel';
        try {
            return $this->$function($template, $schedule_date);
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
            report($e);
            return false;
        }
    }
    protected function getDocumentModel(Document $template, Date $schedule_date): Document
    {
        $template->cloneable_relations = ['items', 'totals'];
        $model = $template->duplicate();
        $diff_days = Date::parse($template->due_at)->diffInDays(Date::parse($template->issued_at));
        $model->type = $this->getRealType($template->type);
        $model->parent_id = $template->id;
        $model->issued_at = $schedule_date->format('Y-m-d');
        $model->due_at = $schedule_date->copy()->addDays($diff_days)->format('Y-m-d');
        $model->created_from = 'core::recurring';
        $model->save();
        $this->updateRelationTypes($model, $template->cloneable_relations);
        return $model;
    }
    protected function getTransactionModel(Transaction $template, Date $schedule_date): Transaction
    {
        $template->cloneable_relations = ['taxes'];
        $model = $template->duplicate();
        $model->type = $this->getRealType($template->type);
        $model->parent_id = $template->id;
        $model->paid_at = $schedule_date->format('Y-m-d');
        $model->created_from = 'core::recurring';
        $model->save();
        $this->updateRelationTypes($model, $template->cloneable_relations);
        return $model;
    }
    protected function getRemainingSchedules(Document|Transaction $template, Recurring $recur): RecurrenceCollection
    {
        $date_field = $this->getDateField($template);
        $created_schedules = DB::table($template->getTable())
                                ->where('type', $this->getRealType($template->type))
                                ->where('parent_id', $template->id)
                                ->get($date_field)
                                ->transform(function ($item, $key) use ($date_field) {
                                    return Date::parse($item->$date_field)->format('Y-m-d');
                                })
                                ->toArray();
        $schedules = $recur->getRecurringSchedule()->filter(function ($recurrence) use ($created_schedules) {
            return ! in_array($recurrence->getStart()->format('Y-m-d'), $created_schedules);
        });
        return $schedules;
    }
    protected function getDateField(Document|Transaction $template): string
    {
        return ($template instanceof Transaction) ? 'paid_at' : 'issued_at';
    }
    public function getRealType(string $recurring_type): string
    {
        return Str::replace('-recurring', '', $recurring_type);
    }
    public function updateRelationTypes($model, $relations)
    {
        foreach ($relations as $relation) {
            if (! method_exists($model, $relation)) {
                continue;
            }
            $model->$relation()->update(['type' => $model->type]);
        }
    }
}
