<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: #f4f6f8;
            font-family: 'Segoe UI', Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: #fff;
            padding: 32px 28px 24px 28px;
            border-radius: 10px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            width: 350px;
        }
        .login-container h2 {
            margin-bottom: 24px;
            text-align: center;
            color: #333;
        }
        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 12px 10px;
            margin-bottom: 18px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 15px;
            background: #f9fafb;
            transition: border 0.2s;
        }
        .login-container input:focus {
            border-color: #6366f1;
            outline: none;
        }
        .login-container .button {
            width: 100%;
            padding: 12px;
            background: #6366f1;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .login-container .button:hover {
            background: #4f46e5;
        }
        #alertBox {
            margin-top: 10px;
        }
        .button:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form id="loginForm" autocomplete="off">
            <input type="email" name="email" placeholder="Email" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <input value="Login" type="submit" id="loginBtn">
        </form>
        <div id="alertBox"></div>
        <div id="result"></div>
    </div>

 <script>
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const email = this.email.value.trim();
        const password = this.password.value;
        const loginBtn = document.getElementById('loginBtn');

        const originalText = loginBtn.innerText;
        loginBtn.innerText = 'Loading...';
        loginBtn.disabled = true;

        try {
            const response = await fetch('{{ route("login.ajax") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();

            Swal.fire({
                icon: data.success ? 'success' : 'error',
                title: data.title || (data.success ? 'Login berhasil' : 'Login gagal'),
                text: data.message || '',
                timer: data.success ? 1500 : undefined,
                showConfirmButton: !data.success
            });

            if (data.success) {
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 1500);
            }
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Terjadi kesalahan!',
                text: 'Login gagal. Silakan coba lagi.',
                timer: 1800,
                showConfirmButton: false
            });
        } finally {
            // Reset tombol
            loginBtn.innerText = originalText;
            loginBtn.disabled = false;
        }
    });
</script>

</body>
</html>
