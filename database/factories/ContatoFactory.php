<?php

namespace Database\Factories;

use App\Models\Contato;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ContatoFactory extends Factory
{
    protected $model = Contato::class;

    public function definition()
    {
        return [
            'nome' => $this->faker->name,
            'cpf' => $this->faker->unique()->numerify('###.###.###-##'),  // CPF format
            'telefone' => $this->faker->phoneNumber,
            'endereco' => $this->faker->address,
            'cep' => $this->faker->postcode,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'user_id' => 1,  // assuming the user with ID 1 exists
        ];
    }
}
