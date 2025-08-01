<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Register - Eco Waste</title>
    <style>
        body { font-family: Arial, sans-serif; background: #eef2f3; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .form-container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0px 4px 12px rgba(0,0,0,0.1); width: 300px; }
        h2 { margin-bottom: 20px; text-align: center; }
        input { width: 100%; margin-bottom: 15px; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        button { width: 100%; background: #27ae60; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #219150; }
        a { display: block; margin-top: 10px; text-align: center; color: #333; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>User Register</h2>
        @if (
            session('success'))
            <div style="color: green; margin-bottom: 10px;">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div style="color: red; margin-bottom: 10px;">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
        <a href="{{ route('login') }}">Already have an account? Login</a>
    </div>
</body>
</html>
