<?php

namespace App\Http\Repositories;

use App\Models\Media;
use App\Traits\Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MediaRepository
{
    use Repository;

    /**
     * The model being queried.
     *
     * @var Media
     */
    protected $model;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Don't forget to update the model's name
        $this->model = app(Media::class);
    }

    /**
     * Check if media exists
     */
    public function ifExist($id): ?Media
    {
        return $this->find($id);
    }

    /**
     * Get all medias with filtering, pagination, and sorting
     * Inspiré de la méthode index() du contrôleur Symfony
     */
    public function getAll($request)
    {
        $per_page = 10;

        $req = Media::with(['projetMedia']) // Eager loading des relations
            ->ignoreRequest(['per_page'])
            ->orderByDesc('created_at');

        if (array_key_exists('per_page', $request->all())) {
            $per_page = $request['per_page'];
            return $req->paginate($per_page);
        } else {
            return $req->get();
        }
    }

    /**
     * Get all medias for index display (like Symfony's index method)
     */
    public function findAllForIndex(): Collection
    {
        return Media::with(['projetMedia'])
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Get a specific media by id
     * Inspiré de la méthode show() du contrôleur Symfony
     */
    public function get($id): Media
    {
        return $this->findOrFail($id);
    }

    /**
     * Find media for show page with relations
     */
    public function findForShow($id): Media
    {
        return Media::with(['projetMedia'])
            ->findOrFail($id);
    }

    /**
     * Store a new media
     * Inspiré de la méthode new() du contrôleur Symfony
     */
    public function makeStore(array $data): Media
    {
        try {
            DB::beginTransaction();

            // Validation et nettoyage des données
            $cleanData = $this->cleanDataForStore($data);

            // Création du média
            $media = Media::create($cleanData);

            // Log de l'action
            Log::info('Nouveau média créé', [
                'media_id' => $media->id,
                'projet_media_id' => $media->projets_media_id,
                'name' => $media->name
            ]);

            DB::commit();

            return $media->fresh(['projetMedia']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création du média', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Update an existing media
     * Inspiré de la méthode edit() du contrôleur Symfony
     */
    public function makeUpdate($id, array $data): Media
    {
        try {
            DB::beginTransaction();

            $model = Media::findOrFail($id);

            // Sauvegarde des anciennes valeurs pour les logs
            $oldValues = $model->toArray();

            // Validation et nettoyage des données
            $cleanData = $this->cleanDataForUpdate($data);

            // Mise à jour des données média
            $model->update($cleanData);

            // Log de l'action
            Log::info('Média mis à jour', [
                'media_id' => $model->id,
                'old_values' => $oldValues,
                'new_values' => $cleanData
            ]);

            DB::commit();

            return $model->fresh(['projetMedia']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la mise à jour du média', [
                'media_id' => $id,
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Delete a media
     * Inspiré de la méthode delete() du contrôleur Symfony
     */
    public function makeDestroy($id): bool
    {
        try {
            DB::beginTransaction();

            $media = $this->findOrFail($id);

            // Sauvegarde des données pour les logs
            $mediaData = $media->toArray();

            // Suppression du fichier physique si nécessaire
            $this->deletePhysicalFile($media);

            // Suppression de l'enregistrement
            $result = $media->delete();

            // Log de l'action
            Log::info('Média supprimé', [
                'media_id' => $id,
                'deleted_data' => $mediaData
            ]);

            DB::commit();

            return $result;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression du média', [
                'media_id' => $id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get the latest medias
     */
    public function getLatest(int $limit = 10): Collection
    {
        return Media::with(['projetMedia'])
            ->latest('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Set media status (active/inactive)
     */
    public function setStatus($id, $status): bool
    {
        try {
            $media = $this->findOrFail($id);
            $result = $media->update(['is_active' => $status]);

            Log::info('Statut du média modifié', [
                'media_id' => $id,
                'new_status' => $status
            ]);

            return $result;

        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut', [
                'media_id' => $id,
                'status' => $status,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Search for medias by name, chemin, or projet
     */
    public function search($term): Collection
    {
        $query = Media::with(['projetMedia']);
        $attrs = ['name', 'chemin']; // Attributs de recherche adaptés au modèle Media

        foreach ($attrs as $value) {
            $query->orWhere($value, 'like', '%'.$term.'%');
        }

        // Recherche aussi dans les projets média associés
        $query->orWhereHas('projetMedia', function ($q) use ($term) {
            $q->where('name', 'like', '%'.$term.'%')
              ->orWhere('description', 'like', '%'.$term.'%');
        });

        return $query->orderByDesc('created_at')->get();
    }

    /**
     * Get medias by project ID
     */
    public function getByProject($projectId): Collection
    {
        return Media::with(['projetMedia'])
            ->where('projets_media_id', $projectId)
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Get medias with files only
     */
    public function getWithFiles(): Collection
    {
        return Media::with(['projetMedia'])
            ->whereNotNull('name')
            ->where('name', '!=', '')
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Bulk delete medias
     */
    public function bulkDelete(array $ids): int
    {
        try {
            DB::beginTransaction();

            // Récupération des médias avant suppression pour les logs
            $medias = Media::whereIn('id', $ids)->get();

            // Suppression des fichiers physiques
            foreach ($medias as $media) {
                $this->deletePhysicalFile($media);
            }

            // Suppression en masse
            $deletedCount = Media::whereIn('id', $ids)->delete();

            Log::info('Suppression en masse de médias', [
                'deleted_count' => $deletedCount,
                'media_ids' => $ids
            ]);

            DB::commit();

            return $deletedCount;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression en masse', [
                'media_ids' => $ids,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Clean and validate data for store operation
     */
    private function cleanDataForStore(array $data): array
    {
        // Nettoyage des données
        if (isset($data['name'])) {
            $data['name'] = trim($data['name']);
        }

        if (isset($data['chemin'])) {
            $data['chemin'] = trim($data['chemin']);
            // Normalisation du chemin
            $data['chemin'] = rtrim($data['chemin'], '/');
        }

        return $data;
    }

    /**
     * Clean and validate data for update operation
     */
    private function cleanDataForUpdate(array $data): array
    {
        return $this->cleanDataForStore($data);
    }

    /**
     * Delete physical file associated with media
     */
    private function deletePhysicalFile(Media $media): void
    {
        if ($media->chemin && $media->name) {
            $fullPath = storage_path('app/public/' . $media->chemin . '/' . $media->name);

            if (file_exists($fullPath)) {
                unlink($fullPath);
                Log::info('Fichier physique supprimé', ['path' => $fullPath]);
            }
        }
    }

    /**
     * Get media statistics
     */
    public function getStats(): array
    {
        return [
            'total' => Media::count(),
            'with_files' => Media::whereNotNull('name')->where('name', '!=', '')->count(),
            'without_files' => Media::whereNull('name')->orWhere('name', '')->count(),
            'active' => Media::where('is_active', true)->count(),
            'inactive' => Media::where('is_active', false)->count(),
            'latest_month' => Media::where('created_at', '>=', now()->subMonth())->count(),
        ];
    }

    /**
     * Create a new media instance (for forms)
     */
    public function createNew(): Media
    {
        return new Media();
    }
}