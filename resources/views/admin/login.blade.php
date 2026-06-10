<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | Gibran Amadeus</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-body">
    <main class="login-shell">
        <form class="login-card" method="post" action="{{ route('admin.login.store') }}">
            @csrf
            <div>
                <span class="eyebrow">Admin Area</span>
                <h1>Studio Login</h1>
                <p>Masuk untuk mengelola seri foto dan urutan portfolio.</p>
            </div>

            <label>
                <span>Email</span>
                <input name="email" type="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <small>{{ $message }}</small>
                @enderror
            </label>

            <label>
                <span>Password</span>
                <input name="password" type="password" required>
                @error('password')
                    <small>{{ $message }}</small>
                @enderror
            </label>

            <label class="check-row">
                <input name="remember" type="checkbox" value="1">
                <span>Remember me</span>
            </label>

            <button type="submit">Login</button>
        </form>
    </main>
</body>
</html>
