<?php

namespace Database\Factories;

use App\Models\CustomerModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'tel_num' => rand(10000000, 10000000),
            'address' => $this->faker->address(),
            'is_active' => rand(0,1),
        ];
    }
}
