<?php
namespace Database\Factories;
use App\Abstracts\Factory;
use App\Models\Setting\Currency as Model;
class Currency extends Factory
{
    protected $model = Model::class;
    public function definition()
    {
        $currencies = config('money.currencies');
        Model::pluck('code')->each(function ($db_code) use (&$currencies) {
            unset($currencies[$db_code]);
        });
        $random = $this->faker->randomElement($currencies);
        $filtered = array_filter($currencies, function ($value) use ($random) {
            return ($value['code'] == $random['code']);
        });
        $code = key($filtered);
        $currency = $filtered[$code];
        return [
            'company_id' => $this->company->id,
            'name' => $currency['name'],
            'code' => $code,
            'rate' => $this->faker->randomFloat($currency['precision'], 1, 10),
            'precision' => $currency['precision'],
            'symbol' => $currency['symbol'],
            'symbol_first' => $currency['symbol_first'],
            'decimal_mark' => $currency['decimal_mark'],
            'thousands_separator' => $currency['thousands_separator'],
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
