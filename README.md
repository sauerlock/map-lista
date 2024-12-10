# Escopo do Projeto


## Objetivo

Desenvolver uma aplicação para gerenciamento de contatos, com funcionalidades de cadastro, edição, exclusão, e validação de dados, incluindo integração com a API de Geolocalização para obter coordenadas baseadas no endereço e validação de CPF com base em regras específicas.

## Funcionalidades Principais

### Cadastro de Contatos:
- Permitir que o usuário cadastre novos contatos, incluindo informações como nome, CPF, telefone, endereço e CEP.

### Validação do CPF:
- O CPF deve ser validado de acordo com o algoritmo oficial.
- O CPF não pode ser duplicado por usuário.

### Geolocalização:
- Obter coordenadas (latitude e longitude) para o endereço fornecido usando a API do Google Maps.

### Endereço:
- O endereço pode ser informado com ou sem complemento.
- O CEP é validado antes da consulta ao Google Maps.

### Edição de Contatos:
- Permitir que o usuário edite informações de um contato já cadastrado, incluindo nome, telefone, endereço e CPF.
- **Recalcular Coordenadas**: A edição do endereço deve recalcular as coordenadas (latitude e longitude).

### Exclusão de Contatos:
- Permitir a exclusão de um contato, garantindo que o usuário possa excluir dados a qualquer momento.

### Validação de Dados:
- **CPF**: Validar se o CPF informado é válido e único para o usuário.
- **CEP**: Verificar a validade do CEP usando a API ViaCEP e a Google Maps API.

### Mensagens de Feedback:
- O sistema retorna mensagens de sucesso ou erro com base nas ações realizadas (ex: cadastro, edição, exclusão).
- Mensagens específicas para erros como "CPF já registrado" ou "Endereço não encontrado".

### Autenticação de Usuários:
- Implementação de um sistema de login e cadastro de usuários com autenticação via token.
- O token é utilizado para proteger as rotas de CRUD de contatos e garantir que o usuário autenticado só possa acessar seus próprios contatos.

### Testes Automatizados:
- **Testes de Requisição**:
    - Testes para garantir que as rotas de cadastro, login, edição, e exclusão de contatos estão funcionando corretamente.
    - Teste de validação de CPF e de coordenação de geolocalização.

## Tecnologias Utilizadas

1. **Laravel ^11.31**: Framework PHP para o backend da aplicação.
2. **SQLite**: Banco de dados relacional para persistir os dados dos contatos e usuários.
3. **API Google Maps**: API externa para obter as coordenadas (latitude e longitude) do endereço do contato.
4. **API ViaCEP**: API para consulta do CEP e validação do endereço.
5. **PHPUnit**: Framework para testes automatizados das funcionalidades.
6. **Laravel Sanctum**: Utilizado para autenticação via tokens (para proteger as rotas de CRUD dos contatos).

## Arquitetura e Organização do Código

### Controladores:
- **AuthController**: Controla as ações de registro, login, logout e exclusão da conta de usuário.
- **ContatoController**: Controla as ações de CRUD dos contatos (inclusão, listagem, atualização, exclusão e busca) além de acessar o Services de CEP, Maps e CPF.
- **ForgotPasswordController**: Envio de email com token para gerar nova senha
- **PasswordResetController**: Encaminhamento a view de alteração de senha
- **EnderecoController**: Responsável pela validação de CEP e coordenação de geolocalização via APIs externas.
- **GoogleGeocodingService**: Serviço que integra a API do Google Maps para obter coordenadas.

### Validação de CPF:
- **CPFValidationService**: Serviço responsável por validar o CPF conforme o algoritmo oficial e verificar duplicatas.

### Service Providers:
- **GoogleGeocodingServiceProvider**: Provider que registra o serviço de geolocalização.
- **CPFValidationServiceProvider**: Provider que registra o serviço de validação de CPF.

### Middleware:
- **AuthMiddleware**: Middleware que garante que as rotas de contatos só sejam acessadas por usuários autenticados.

### Roteamento:
- As rotas estão organizadas de forma a proteger as ações de CRUD de contatos com autenticação via token.

### Mensagens de Erro:
- Erros de validação e exceções são tratados e retornam mensagens claras para o usuário.

## Rotas da API

### **POST /api/register**:
- Cadastro de um novo usuário.

### **POST /api/login**:
- Login de um usuário.

### **POST /api/logout**:
- Logout do usuário.

### **POST /api/contatos**:
- Cadastro de um novo contato.

### **GET /api/contatos**:
- Listar todos os contatos do usuário autenticado.

### **GET /api/contatos/{id}**:
- Obter detalhes de um contato específico.

### **PUT /api/contatos/{id}**:
- Atualizar um contato existente.

### **DELETE /api/contatos/{id}**:
- Excluir um contato.

### **GET /api/search**:
- Buscar contatos por nome ou CPF.

### **POST /api/cep**:
- Validar e consultar um CEP.

## Como Rodar o Projeto

1. **Instalar Dependências**:
    - Execute `composer install` para instalar as dependências do projeto.

2. **Configurar o .env**:
    - Configure as variáveis do arquivo `.env` (como `GOOGLE_API_KEY`, banco de dados, etc.).

3. **Rodar as Migrations**:
    - Execute `php artisan migrate` para criar as tabelas no banco de dados.

4. **Iniciar o Servidor**:
    - Execute `php artisan serve` para iniciar o servidor local.

5. **Executar os Testes**:
    - Execute `php artisan test` para rodar os testes automatizados.

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

