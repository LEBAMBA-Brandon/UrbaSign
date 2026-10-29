<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['longitude', 'latitude', 'description', 'quartier_id', 'gravite', 'categorie', 'statut', 'auteur_id', 'date_creation', 'photo'])]

class Signalement extends Model
{
    protected function casts(): array
    {
        return [
            'longitude' => 'float',
            'latitude' => 'float',
            'date_creation' => 'datetime',
        ];
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
