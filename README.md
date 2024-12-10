<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>
README.md
Projeto de Cadastro de Contatos com Geolocalização
Este projeto oferece uma API para cadastro, listagem, atualização e exclusão de contatos, além de integrar com o Google Maps para obter as coordenadas (latitude e longitude) de um endereço fornecido. Ele também realiza a validação de CPF de acordo com o algoritmo oficial.

Funcionalidades
Cadastro de usuários: Permite criar um novo usuário.
Cadastro de contatos: Permite cadastrar contatos, incluindo informações como nome, CPF, telefone, endereço e CEP.
Geolocalização: O sistema consulta as coordenadas (latitude e longitude) do endereço fornecido utilizando a API do Google Maps.
Validação de CPF: O sistema valida o CPF conforme o algoritmo oficial.
Autenticação e Autorização: Utiliza Laravel Sanctum para autenticação via tokens.
Recuperação de Senha: Funcionalidade de redefinição de senha com envio de link para o e-mail do usuário.
Tecnologias Utilizadas
Laravel 10
MySQL ou SQLite (Banco de dados)
Google Maps API (Para obtenção de coordenadas)
Sanctum (Autenticação via token)
Xdebug (Para depuração)
Endpoints
1. Registrar Usuário (POST /api/register)
Registra um novo usuário com os campos: name, email, password, password_confirmation.

Exemplo de Request:

json
Copiar código
{
  "name": "João Silva",
  "email": "joao@exemplo.com",
  "password": "senha123",
  "password_confirmation": "senha123"
}
Resposta:

json
Copiar código
{
  "message": "Usuário cadastrado com sucesso!",
  "token": "1|zADxAU3JZX0Geg9TLvL1fRnQ1inGMOETHKEuq12L04072411",
  "user": {
    "id": 1,
    "name": "João Silva",
    "email": "joao@exemplo.com"
  }
}
2. Login (POST /api/login)
Faz o login do usuário e retorna o token de autenticação.

Exemplo de Request:

json
Copiar código
{
  "email": "joao@exemplo.com",
  "password": "senha123"
}
Resposta:

json
Copiar código
{
  "token": "1|zADxAU3JZX0Geg9TLvL1fRnQ1inGMOETHKEuq12L04072411",
  "user": {
    "id": 1,
    "name": "João Silva",
    "email": "joao@exemplo.com"
  }
}
3. Cadastrar Contato (POST /api/contatos)
Cadastra um novo contato, incluindo o nome, CPF, telefone, endereço e CEP. O sistema calcula automaticamente a latitude e longitude com base no endereço fornecido.

Exemplo de Request:

json
Copiar código
{
  "nome": "Maria Oliveira",
  "cpf": "12345678901",
  "telefone": "11987654321",
  "endereco": "Rua Exemplo, 123",
  "cep": "12345-678"
}
Resposta:

json
Copiar código
{
  "id": 1,
  "nome": "Maria Oliveira",
  "cpf": "12345678901",
  "telefone": "11987654321",
  "endereco": "Rua Exemplo, 123",
  "cep": "12345-678",
  "latitude": -23.550520,
  "longitude": -46.633308
}
4. Obter Coordenadas (POST /api/coordenadas)
Consulta a latitude e longitude com base no endereço fornecido.

Exemplo de Request:

json
Copiar código
{
  "endereco": "Avenida Paulista, 1578"
}
Resposta:

json
Copiar código
{
  "latitude": -23.56168,
  "longitude": -46.65629
}
5. Recuperação de Senha (POST /api/forgot-password)
Solicita um link para redefinir a senha do usuário.

Exemplo de Request:

json
Copiar código
{
  "email": "joao@exemplo.com"
}
Resposta:

json
Copiar código
{
  "status": "RESET_LINK_SENT"
}
6. Redefinir Senha (POST /api/reset-password)
Permite ao usuário redefinir sua senha, fornecendo o token e a nova senha.

Exemplo de Request:

json
Copiar código
{
  "email": "joao@exemplo.com",
  "password": "novaSenha123",
  "password_confirmation": "novaSenha123",
  "token": "token-do-email"
}
Resposta:

json
Copiar código
{
  "message": "A senha foi alterada com sucesso!"
}
Regras de Validação
CPF:

O CPF deve ser validado de acordo com o algoritmo oficial.
Não são permitidos CPFs duplicados na base (para cada usuário).
O CPF é obrigatório no cadastro e deve ser único para cada usuário.
Endereço:

O endereço deve ser fornecido completo, mas o complemento é opcional.
O sistema irá obter automaticamente a latitude e longitude com base no endereço usando a API do Google Maps.
Autenticação:

O sistema usa o Laravel Sanctum para autenticação baseada em tokens.
Apenas usuários autenticados podem realizar ações como cadastrar ou editar contatos.
Erro de CPF Duplicado:

O sistema verifica se o CPF já existe na base de dados e retorna um erro com status 422 caso o CPF já esteja cadastrado para o usuário.
