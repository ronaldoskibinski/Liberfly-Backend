<!DOCTYPE html>
<html>
    <head>
    <title>Tela de Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    </head>
    <body>
        <div class="container">
            <h2>Tela de Login</h2>
            <form action="api/auth/login" method="post">
                @csrf
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="text" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Senha:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <button type="submit">Entrar</button>
                </div>
            </form>
        </div>
    </body>
</html>