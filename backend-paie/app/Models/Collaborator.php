<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collaborator extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'matricule',
        'date_naissance',
        'email',
        'adresse',
        'genre',
        'date_embauche',
        'salaire_base',
        'poste',
        'statut',
    ];

    /**
     * Relation : un collaborateur appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paies()
    {
        return $this->hasMany(Paie::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
