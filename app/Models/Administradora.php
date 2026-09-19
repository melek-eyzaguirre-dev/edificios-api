<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Administradora extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'rut', 'email_contacto', 'telefono_contacto', 'activo'];

    public function condominios(): HasMany
    {
        return $this->hasMany(Condominio::class);
    }

    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
