<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nom_arrondissement', 'commune_id'])]

class Arrondissement extends Model
{
    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class, 'commune_id');
    }

    public function quartiers(): HasMany
    {
        return $this->hasMany(Quartier::class, 'arrondissement_id');
    }
}
