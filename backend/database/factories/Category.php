<?php
namespace Database\Factories;
use App\Abstracts\Factory;
use App\Models\Setting\Category as Model;
class Category extends Factory
{
    protected $model = Model::class;
    public function definition()
    {
        $types = ['income', 'expense', 'item', 'other'];
        return [
            'company_id' => $this->company->id,
            'name' => $this->faker->text(15),
            'type' => $this->faker->randomElement($types),
            'color' => $this->faker->hexColor,
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
    public function income()
    {
        return $this->state([
            'type' => 'income',
        ]);
    }
    public function expense()
    {
        return $this->state([
            'type' => 'expense',
        ]);
    }
    public function item()
    {
        return $this->state([
            'type' => 'item',
        ]);
    }
    public function other()
    {
        return $this->state([
            'type' => 'other',
        ]);
    }
}
