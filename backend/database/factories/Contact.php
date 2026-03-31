<?php
namespace Database\Factories;
use App\Abstracts\Factory;
use App\Models\Common\Contact as Model;
use App\Traits\Contacts;
class Contact extends Factory
{
    use Contacts;
    protected $model = Model::class;
    public function definition()
    {
        $types = array_merge($this->getCustomerTypes(), $this->getVendorTypes());
        $countries = array_keys(trans('countries'));
        return [
            'company_id' => $this->company->id,
            'type' => $this->faker->randomElement($types),
            'name' => $this->faker->name,
            'email' => $this->faker->freeEmail,
            'user_id' => null,
            'tax_number' => $this->faker->randomNumber(9),
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'country' => $this->faker->randomElement($countries),
            'website' => 'https://akaunting.com',
            'currency_code' => default_currency(),
            'reference' => $this->faker->text(5),
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
    public function customer()
    {
        return $this->state([
            'type' => 'customer',
        ]);
    }
    public function vendor()
    {
        return $this->state([
            'type' => 'vendor',
        ]);
    }
}
