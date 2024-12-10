<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class CPFValidationService
{
    public function validarCpf($cpf)
    {
        // Remover caracteres não numéricos
        $cpf = preg_replace('/\D/', '', $cpf);

        // Verificando se o CPF tem 11 dígitos
        if (strlen($cpf) != 11) {
            return false;
        }

        // Verificando se o CPF não é uma sequência de números repetidos (como 111.111.111-11)
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Calculando o primeiro dígito verificador
        $digits = substr($cpf, 0, 9); // Pegando os 9 primeiros dígitos
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += (int)$digits[$i] * (10 - $i); // Multiplicando por 10, 9, ..., 2
        }
        $firstDigit = 11 - ($sum % 11);
        if ($firstDigit >= 10) $firstDigit = 0; // Se for maior ou igual a 10, o primeiro dígito é 0

        // Calculando o segundo dígito verificador
        $sum = 0;
        $digits .= $firstDigit; // Adicionando o primeiro dígito para o cálculo do segundo
        for ($i = 0; $i < 10; $i++) {
            $sum += (int)$digits[$i] * (11 - $i); // Multiplicando por 11, 10, ..., 2
        }
        $secondDigit = 11 - ($sum % 11);
        if ($secondDigit >= 10) $secondDigit = 0; // Se for maior ou igual a 10, o segundo dígito é 0

        // Verificando se os dois dígitos calculados são iguais aos dígitos fornecidos
        $checkDigits = substr($cpf, -2);
        return $checkDigits == "{$firstDigit}{$secondDigit}";
    }
}
