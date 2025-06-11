<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefautDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            \App\Models\RegleConservation::factory(5)->create();
            \App\Models\Dossier::factory(10)->create()->each(function ($dossier) {
                    // Crée des sous-dossiers (hiérarchie simple)
                    \App\Models\Dossier::factory(2)->create([
                        'parent_id' => $dossier->id,
                        'regle_conservation_id' => $dossier->regle_conservation_id,
                    ]);
                });

            \App\Models\User::factory(10)->create();
            \App\Models\Document::factory(20)->create()->each(function ($doc) {
                    // Métadonnées pour chaque document
                    \App\Models\Metadonnee::factory(3)->create([
                        'document_id' => $doc->id,
                    ]);

                    // Journal d'audit
                    \App\Models\JournalAudit::factory(2)->create([
                        'document_id' => $doc->id,
                        'utilisateur_id' => $doc->utilisateur_id,
                    ]);
                });
    }
}
