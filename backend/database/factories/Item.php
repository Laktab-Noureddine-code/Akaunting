<?php
namespace Database\Factories;
use App\Abstracts\Factory;
use App\Models\Common\Item as Model;
class Item extends Factory
{
    protected $model = Model::class;
    public function definition()
    {
        $types = ['product', 'service'];
        return [
            'company_id' => $this->company->id,
            'type' => $this->faker->randomElement($types),
            'name' => $this->faker->text(15),
            'description' => $this->faker->text(100),
            'purchase_price' => $this->faker->randomFloat(2, 10, 20),
            'sale_price' => $this->faker->randomFloat(2, 10, 20),
            'category_id' => $this->company->categories()->item()->get()->random(1)->pluck('id')->first(),
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
}
