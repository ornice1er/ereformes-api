<?php
namespace Database\Factories;

use App\Models\RegleConservation;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegleConservationFactory extends Factory
{
    protected $model = RegleConservation::class;

    public function definition()
    {
        return [
            'nom' => $this->faker->word(),
            'duree_annees' => $this->faker->numberBetween(1, 10),
            'action_post_duree' => $this->faker->randomElement(['destruction', 'anonymisation']),
        ];
    }
}
