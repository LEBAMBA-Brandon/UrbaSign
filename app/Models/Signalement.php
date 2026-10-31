<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['longitude', 'latitude', 'description', 'quartier_id', 'gravite', 'categorie', 'statut', 'auteur_id', 'photo'])]

class Signalement extends Model
{

    use HasFactory;

    const CREATED_AT = 'date_creation';
    const UPDATED_AT = null;

    const CATEGORIES = ['Voirie', 'Ordures', 'Éclairage public', 'Caniveau/Inondation', 'Autre'];
    const GRAVITES = ['Faible', 'Moyenne', 'Critique'];
    const STATUTS = ['Signalé', 'En cours', 'Résolu'];

    protected $appends = ['photo_url'];

    protected function casts(): array
    {
        return [
            'longitude' => 'float',
            'latitude' => 'float',
            'date_creation' => 'datetime',
        ];
    }

    protected function photoUrl(): Attribute
    {
        return Attribute::get(
            fn () => $this->photo ? asset('storage/' .$this->photo) : null
        );
    }

    public function auteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auteur_id');
    }

    public function quartier(): BelongsTo
    {
        return $this->belongsTo(Quartier::class, 'quartier_id');
    }
}
