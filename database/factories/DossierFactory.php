<?php
namespace Database\Factories;

use App\Models\Dossier;
use App\Models\RegleConservation;
use Illuminate\Database\Eloquent\Factories\Factory;

class DossierFactory extends Factory
{
    protected $model = Dossier::class;

    public function definition()
    {
        return [
            'nom' => $this->faker->words(3, true),
            'parent_id' => null, // Peut être ajusté dans le seeder
            'regle_conservation_id' => RegleConservation::factory(),
            'visibilite' => $this->faker->randomElement(['publique', 'restreinte']),
            'types_autorises' => ['pdf', 'docx'],
        ];
    }
}
