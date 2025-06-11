<?php
namespace Database\Factories;

use App\Models\JournalAudit;
use App\Models\User;
use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;

class JournalAuditFactory extends Factory
{
    protected $model = JournalAudit::class;

    public function definition()
    {
        return [
            'action' => $this->faker->randomElement(['consultation', 'telechargement']),
            'utilisateur_id' => User::factory(),
            'document_id' => Document::factory(),
            'date_action' => $this->faker->dateTime(),
            'details' => $this->faker->sentence(),
        ];
    }
}
