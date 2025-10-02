<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\ApiService;
use App\Models\TokenType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApiToken>
 */
class ApiTokenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_id' => Account::factory(),
            'api_service_id' => ApiService::factory(),
            'token_type_id' => TokenType::factory(),
            'token' => $this->faker->sha256,
        ];
    }
}
