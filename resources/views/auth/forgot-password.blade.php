<!-- /**
 * Forgot Password View
 *
 * This view renders the password reset request form.
 *
 * Responsibilities:
 * - Display a form for users to enter their registered email address
 * - Show success message when reset link is sent
 * - Display validation error messages for email field
 * - Submit the form to the password.email route
 *
 * Part of Laravel's authentication password reset flow.
 */ -->

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
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

/* Card */
.forgot-card {
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

/* Input */
input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Button */
button {
    width: 100%;
    padding: 10px;
    background: #4f46e5;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    opacity: 0.9;
}

/* Messages */
.success-message {
    background: #d4edda;
    color: #155724;
    padding: 8px;
    border-radius: 5px;
    margin-bottom: 10px;
    font-size: 14px;
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

<div class="forgot-container">
    <div class="forgot-card">
        <h2>Forgot Password</h2>

        @if(session('success'))
            <p class="success-message">{{ session('success') }}</p>
        @endif

        @error('email')
            <p class="error-message">{{ $message }}</p>
        @enderror

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="input-group">
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <button type="submit">Send Reset Link</button>
        </form>
    </div>
</div>

</body>
</html>
