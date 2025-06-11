<?php
namespace Database\Factories;

use App\Models\Document;
use App\Models\User;
use App\Models\Dossier;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition()
    {
        $statut = $this->faker->randomElement(['en_attente', 'valide', 'rejete']);

        return [
            'titre' => $this->faker->sentence(),
            'statut' => $statut,
           // 'fichier_path' => 'documents/' . $this->faker->uuid . '.pdf',
            'fichier_path' => 'https://project-fondationclaudinetalon-develop.s3.eu-west-3.amazonaws.com/cvtheques/am.pdf__UR9BwkWplFJfgUShyEHZ_170416983250.pdf',
            'hash_sha256' => hash('sha256', $this->faker->text(20)),
            'date_depot' => $this->faker->dateTime(),
            'valide_par' => $statut == 'valide' ? User::factory() : null,
            'date_validation' => $statut == 'valide' ? $this->faker->dateTime() : null,
            'motif_rejet' => $statut == 'rejete' ? $this->faker->sentence() : null,
            'utilisateur_id' => User::factory(),
            'dossier_id' => Dossier::factory(),
        ];
    }
}
