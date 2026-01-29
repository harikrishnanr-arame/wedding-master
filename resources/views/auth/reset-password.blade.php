<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f6f9;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.reset-card {
    background: #fff;
    padding: 30px;
    width: 100%;
    max-width: 380px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    text-align: center;
}

h2 {
    margin-bottom: 20px;
    font-size: 20px;
}

input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

input:focus {
    outline: none;
    border-color: #4f46e5;
}

button {
    width: 100%;
    padding: 10px;
    background: #4f46e5;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    opacity: 0.9;
}

.error-message {
    background: #f8d7da;
    color: #721c24;
    padding: 8px;
    border-radius: 5px;
    margin-bottom: 10px;
    font-size: 14px;
}
</style>

</head>
<body>

<div class="reset-container">
    <div class="reset-card">
        <h2>Reset Password</h2>

        @if ($errors->any())
            <div class="error-message">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="input-group">
                <input type="email" name="email" value="{{ $email }}" readonly>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="New Password" required>
            </div>

            <div class="input-group">
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
            </div>

            <button type="submit">Reset Password</button>
        </form>
    </div>
</div>

</body>
</html>
