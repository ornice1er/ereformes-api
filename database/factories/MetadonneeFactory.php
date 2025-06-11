<?php
namespace Database\Factories;

use App\Models\Metadonnee;
use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;

class MetadonneeFactory extends Factory
{
    protected $model = Metadonnee::class;

    public function definition()
    {
        return [
            'cle' => $this->faker->word(),
            'valeur' => $this->faker->sentence(),
            'document_id' => Document::factory(),
        ];
    }
}
