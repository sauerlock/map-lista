<form action="{{ route('password.update') }}" method="POST">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" value="{{ old('email') }}" required>

    <label for="password">Nova Senha:</label>
    <input type="password" id="password" name="password" required>

    <label for="password_confirmation">Confirmar Nova Senha:</label>
    <input type="password" id="password_confirmation" name="password_confirmation" required>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <button type="submit">Alterar Senha</button>
</form>
