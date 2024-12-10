# SEGUIR O CAMINHO DO USUARIO

### Lembrar de sempre utilizar o token gerado no momento do login

## REGISTER
### **POST /api/register**:
- Cadastro de um novo usuário.
## LOGIN
### **POST /api/login**:
- Login de um usuário.
## LOGOUT
### **POST /api/logout**:
- Logout do usuário.

## ADD CONTATOS
### **POST /api/contatos**:
- Cadastro de um novo contato.
## GET CONTATOS
### **GET /api/contatos**:
- Listar todos os contatos do usuário autenticado.
### **GET /api/contatos/{id}**:
- Obter detalhes de um contato específico.
## EDITA CONTATOS
### **PUT /api/contatos/{id}**:
- Atualizar um contato existente.
## DELETA CONTATOS
### **DELETE /api/contatos/{id}**:
- Excluir um contato.
## SEARCH CONTATOS
### **GET /api/search**:
- Buscar contatos por nome ou CPF.

## FORGOT PASSWORD
### **POST api/forgot-password**
- Envia email de recuperação de senha com token
## RESET PASSWORD
### **api/reset-password?token={{token}}**
- Com o token altera a senha

## BUSCA ENDERECO
### **POST /api/cep**:
- Validar e consultar um CEP.
## OBTEM COORDENADAS
## **GET /api/obter-coordenadas?endereco=Rua Teste, 123**
- Busca geolocalização o GoogleMaps




