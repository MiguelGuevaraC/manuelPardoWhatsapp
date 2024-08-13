<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WhatsappSend>
 */
class WhatsappSendFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'number' => $this->faker->phoneNumber,
            'userResponsability' => $this->faker->name,
            'namesStudent' => $this->faker->name,
            'dniStudent' => $this->faker->numberBetween(10000000, 99999999),
            'namesParent' => $this->faker->name,
            'infoStudent' => $this->faker->text,
            'telephone' => $this->faker->phoneNumber,
            'description' => $this->faker->text,
            'conceptSend' => $this->faker->word,
            'paymentAmount' => $this->faker->randomFloat(2, 0, 500),
            'expirationDate' => $this->faker->date,
            'cuota' => $this->faker->randomNumber(),
            'status' => $this->faker->word,
            'created_at' => $this->faker->dateTimeBetween('-12 months', 'now'),
            'student_id' => null,
            'user_id' => 1,
            'comminment_id' => null,
        ];
    }
}
