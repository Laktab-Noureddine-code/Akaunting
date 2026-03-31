<?php
namespace Database\Factories;
use App\Abstracts\Factory;
use App\Models\Common\Dashboard as Model;
class Dashboard extends Factory
{
    protected $model = Model::class;
    public function definition()
    {
        return [
            'company_id' => $this->company->id,
            'name' => $this->faker->text(15),
            'enabled' => $this->faker->boolean ? 1 : 0,
            'created_from' => 'core::factory',
        ];
    }
    public function enabled()
    {
        return $this->state([
            'enabled' => 1,
        ]);
    }
    public function disabled()
    {
        return $this->state([
            'enabled' => 0,
        ]);
    }
    public function users()
    {
        return $this->state([
            'users' => $this->getCompanyUsers(),
        ]);
    }
    public function configure()
    {
        return $this->afterCreating(function (Model $dashboard) {
            $dashboard->users()->attach($this->getCompanyUsers());
        });
    }
}
