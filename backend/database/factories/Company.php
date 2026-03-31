<?php
namespace Database\Factories;
use App\Abstracts\Factory;
use App\Models\Common\Company as Model;
class Company extends Factory
{
    protected $model = Model::class;
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'email' => $this->faker->freeEmail,
            'currency' => $this->faker->randomElement(array_keys(\Akaunting\Money\Currency::getCurrencies())),
            'country' => $this->faker->randomElement(array_keys(trans('countries'))),
            'enabled' => $this->faker->boolean ? 1 : 0,
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
