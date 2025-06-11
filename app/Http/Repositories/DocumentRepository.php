<?php
namespace App\Http\Repositories;

use App\Models\Document;
use App\Models\Dossier;
use App\Services\AwsService;
use App\Traits\Repository;
use App\Utilities\FileStorage;

class DocumentRepository
{
    use Repository;

    protected $model;

    public function __construct()
    {
        $this->model = app(Document::class);
    }

    public function getAll($request)
    {
        return Document::with(['dossier', 'metadonnees','utilisateur', 'validateur'])
            ->filter($request->all())
            ->orderByDesc('created_at')
            ->paginate($request->per_page ?? 10);
    }

    public function get($id)
    {
        return $this->findOrFail($id);
    }

    public function makeStore($data)
    {
        $aws= new AwsService();
        if (request()->hasFile('fichier')) {
            $path= $aws->upload(request()->file('fichier'),"documents")['full_url'];
            $data['fichier_path'] = $path;
        }

        $document = Document::create($data);

        if (!empty($data['metadonnees'])) {
            $document->metadonnees()->createMany($data['metadonnees']);
        }

        return $document->load('metadonnees');
    }

    public function makeUpdate($id, $data)
    {
        $document = Document::findOrFail($id);

         $aws= new AwsService();
        if (request()->hasFile('fichier')) {
            $path= $aws->upload(request()->file('fichier'),"documents")['full_url'];
            $data['fichier_path'] = $path;
        }

        // if (request()->hasFile('fichier')) {
        //     FileStorage::deleteFile('documents', $document->chemin);
        //     $data['chemin'] = FileStorage::setFile('documents', request()->file('fichier'), 'archives');
        // }

        $document->update($data);

        if (isset($data['metadonnees'])) {
            $document->metadonnees()->delete();
            $document->metadonnees()->createMany($data['metadonnees']);
        }

        return $document;
    }

    public function makeDestroy($id)
    {
        $document = Document::findOrFail($id);
        FileStorage::deleteFile('documents', $document->chemin);
        return $document->delete();
    }

    public function search($term)
    {
        return Document::where('titre', 'like', "%$term%")->get();
    }

    function getDocumentTree($request) {
          return $this->buildDocumentTree(); // 
    }


    function buildDocumentTree($parentId = null)
{
   return Dossier::where('parent_id', $parentId)->get()->map(function ($dossier) {
        $children = $this->buildDocumentTree($dossier->id)->toArray();

        $docs = $dossier->documents->map(function ($doc) {
            return [
                'name' => $doc->titre,
                'path' => $doc->fichier_path,
                'type' => 'file',
            ];
        })->toArray();

        return [
            'name' => $dossier->nom,
            'type' => 'folder',
            'children' => array_merge($docs, $children),
        ];
    });
}
}
