<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contato extends Model
{
    use HasFactory;

    // Adicione os campos que podem ser atribuídos em massa
    protected $fillable = [
        'nome',      // Permite o campo 'nome' ser preenchido via atribuição em massa
        'cpf',       // Permite o campo 'cpf' ser preenchido via atribuição em massa
        'telefone',  // Permite o campo 'telefone' ser preenchido via atribuição em massa
        'endereco',  // Permite o campo 'endereco' ser preenchido via atribuição em massa
        'cep',       // Permite o campo 'cep' ser preenchido via atribuição em massa
        'latitude',  // Permite o campo 'latitude' ser preenchido via atribuição em massa
        'longitude', // Permite o campo 'longitude' ser preenchido via atribuição em massa
    ];

    // Se você não deseja permitir a atribuição em massa de todos os campos, use $guarded:
    // protected $guarded = [];  // Permite todos os campos serem preenchidos

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
