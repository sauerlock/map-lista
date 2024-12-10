<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contato extends Model
{
    use HasFactory;

    // Adicione os campos que podem ser atribuídos em massa
    protected $fillable = [
        'nome',
        'cpf',
        'telefone',
        'endereco',
        'cep',
        'latitude',
        'longitude',
    ];

    // Se você não deseja permitir a atribuição em massa de todos os campos, use $guarded:
    // protected $guarded = [];  // Permite todos os campos serem preenchidos

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
