<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Support\Facades\Storage;

class Backup extends Model

{

   use HasFactory, Filterable;

    protected $fillable = [
        'name',
        'filename',
        'type',
        'size',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Accesseurs
    public function getTypeLibelleAttribute(): string
    {
        return match($this->type) {
            'doc' => 'Documents',
            'database' => 'Base de données',
            'full' => 'Sauvegarde complète',
            'config' => 'Configuration',
            default => 'Inconnu'
        };
    }

    public function getFormattedSizeAttribute(): string
    {
        if (!$this->size) {
            return 'Taille inconnue';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $size = $this->size;
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 2) . ' ' . $units[$unitIndex];
    }

    public function getIsActiveLibelleAttribute(): string
    {
        return $this->is_active ? 'Actif' : 'Inactif';
    }

    // Méthodes utiles
    public function fileExists(): bool
    {
        return file_exists($this->filename);
    }

    public function getFileSize(): int
    {
        return $this->fileExists() ? filesize($this->filename) : 0;
    }

    public function updateFileSize(): void
    {
        $this->update(['size' => $this->getFileSize()]);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
