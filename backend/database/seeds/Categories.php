<?php
namespace Database\Seeds;
use App\Abstracts\Model;
use App\Jobs\Setting\CreateCategory;
use App\Traits\Jobs;
use Illuminate\Database\Seeder;
class Categories extends Seeder
{
    use Jobs;
    public function run()
    {
        Model::unguard();
        $this->create();
        Model::reguard();
    }
    private function create()
    {
        $company_id = $this->command->argument('company');
        $rows = [
            [
                'company_id' => $company_id,
                'name' => trans_choice('general.transfers', 1),
                'type' => 'other',
                'color' => '
                'enabled' => '1',
            ],
            [
                'company_id' => $company_id,
                'name' => trans('demo.categories.deposit'),
                'type' => 'income',
                'color' => '
                'enabled' => '1',
            ],
            [
                'company_id' => $company_id,
                'name' => trans('demo.categories.sales'),
                'type' => 'income',
                'color' => '
                'enabled' => '1',
            ],
            [
                'company_id' => $company_id,
                'name' => trans_choice('general.others', 1),
                'type' => 'expense',
                'color' => '
                'enabled' => '1',
            ],
            [
                'company_id' => $company_id,
                'name' => trans('general.general'),
                'type' => 'item',
                'color' => '
                'enabled' => '1',
            ],
        ];
        $income_category_id = $expense_category_id = 0;
        foreach ($rows as $row) {
            $row['created_from'] = 'core::seed';
            $category = $this->dispatch(new CreateCategory($row));
            switch ($category->type) {
                case 'income':
                    if (empty($income_category_id)) {
                        $income_category_id = $category->id;
                    }
                    break;
                case 'expense':
                    if (empty($expense_category_id)) {
                        $expense_category_id = $category->id;
                    }
                    break;
            }
        }
        setting()->set('default.income_category', $income_category_id);
        setting()->set('default.expense_category', $expense_category_id);
    }
}
