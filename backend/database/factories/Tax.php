<?php
namespace Database\Factories;
use App\Abstracts\Factory;
use App\Models\Setting\Tax as Model;
class Tax extends Factory
{
    protected $model = Model::class;
    public function definition()
    {
        $types = ['normal', 'inclusive', 'compound', 'fixed', 'withholding'];
        return [
            'company_id' => $this->company->id,
            'name' => $this->faker->text(15),
            'rate' => $this->faker->randomFloat(2, 10, 20),
            'type' => $this->faker->randomElement($types),
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
    public function normal()
    {
        return $this->state([
            'type' => 'normal',
        ]);
    }
    public function inclusive()
    {
        return $this->state([
            'type' => 'inclusive',
        ]);
    }
    public function compound()
    {
        return $this->state([
            'type' => 'compound',
        ]);
    }
    public function fixed()
    {
        return $this->state([
            'type' => 'fixed',
        ]);
    }
    public function withholding()
    {
        return $this->state([
            'type' => 'withholding',
        ]);
    }
}
