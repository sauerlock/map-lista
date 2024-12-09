<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo</title>
</head>
<body>
<h1>Bem-vindo à aplicação!</h1>

@if (session('status'))
    <div>
        <p style="color: green;">{{ session('status') }}</p>
    </div>
@endif

<!-- Resto do conteúdo da página -->
</body>
</html>
