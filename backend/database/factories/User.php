<?php
namespace Database\Factories;
use App\Abstracts\Factory;
use Illuminate\Support\Str;
class User extends Factory
{
    protected $model = false;
    public function __construct()
    {
        parent::__construct();
        $this->model = user_model_class();
    }
    public function definition()
    {
        $password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; 
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->freeEmail,
            'password' => $password,
            'password_confirmation' => $password,
            'remember_token' => Str::random(10),
            'locale' => 'en-GB',
            'companies' => ['1'],
            'roles' => '1',
            'enabled' => $this->faker->boolean ? 1 : 0,
            'landing_page' => 'dashboard',
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
