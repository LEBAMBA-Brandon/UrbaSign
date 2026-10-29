<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nom_commune'])]

class Commune extends Model
{
    public function arrondissement(): HasMany
    {
        return $this->hasMany(Arrondissement::class, 'commune_id');
    }
}
