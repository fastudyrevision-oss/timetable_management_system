<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IT Department Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-card {
            max-width: 400px;
            width: 100%;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            background: white;
            margin: 1rem;
            animation: slideUpFade 0.6s ease-out;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-out {
            opacity: 0 !important;
            transform: translateX(-50px) !important;
            pointer-events: none;
        }

        .btn-primary {
            background-color: #4f46e5;
            border: none;
            padding: 0.8rem;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #4338ca;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }
    </style>
</head>

<body>
    <div class="login-card" id="authCard">
        <div class="text-center mb-4">
            <img src="/assets/images/logo.png" alt="Logo" height="70" class="mb-3 transition-hover">
            <h3 class="fw-bold text-dark">Welcome Back</h3>
            <p class="text-muted small">Sign in to your account</p>
        </div>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger border-0 shadow-sm rounded-3 small">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-success border-0 shadow-sm rounded-3 small">
                <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($_SESSION['flash_message']); unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>
        <form action="/login" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label fw-bold small">Roll Number / Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0">
                        <i class="bi bi-person-badge text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-0 bg-light" id="username" name="identifier" placeholder="Enter roll no or username" required>
                </div>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label fw-bold small">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" class="form-control border-0 bg-light" id="password" name="password" placeholder="••••••••" required>
                    <button class="btn btn-light border-0 toggle-password" type="button">
                        <i class="bi bi-eye text-muted"></i>
                    </button>
                </div>
            </div>
            <div class="mb-4">
                <label for="role" class="form-label fw-bold small">Login As</label>
                <select class="form-select border-0 bg-light" id="role" name="role">
                    <option value="student">Student (View Only)</option>
                    <option value="cr">CR (Class Representative)</option>
                    <option value="gr">GR (Girls Representative)</option>
                    <option value="president">President (Society)</option>
                </select>
                <!-- Admin is hidden but still works if credentials match -->
            </div>

            <div class="mb-4" id="society-login-container" style="display: none;">
                <label for="society_id" class="form-label fw-bold small text-primary">Select Your Society</label>
                <select class="form-select border-0 bg-primary bg-opacity-10 text-primary" id="society_id" name="society_id">
                    <option value="" disabled selected>Choose Society</option>
                    <?php if (isset($societies)): foreach ($societies as $society): ?>
                        <option value="<?= $society['id'] ?>"><?= htmlspecialchars($society['name']) ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 rounded-pill mb-3">Login Now</button>
            <a href="/" class="btn btn-outline-secondary w-100 rounded-pill mb-3 border-0 bg-light text-muted">Continue as Guest</a>
            
            <div class="text-center mt-3">
                <p class="text-muted small">Don't have an account? <a href="/signup" id="toSignup" class="text-primary fw-bold text-decoration-none transition-hover">Sign Up Here</a></p>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('role').addEventListener('change', function() {
            if (this.value === 'student') {
                window.location.href = '/';
            }
            const societyContainer = document.getElementById('society-login-container');
            if (this.value === 'president') {
                societyContainer.style.display = 'block';
            } else {
                societyContainer.style.display = 'none';
            }
        });

        // Toggle Password
        document.querySelectorAll('.toggle-password').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('bi-eye', 'bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                }
            });
        });

        // Animation transition to Signup
        document.getElementById('toSignup').addEventListener('click', function(e) {
            e.preventDefault();
            const card = document.getElementById('authCard');
            card.classList.add('fade-out');
            setTimeout(() => {
                window.location.href = this.href;
            }, 500);
        });
    </script>
</body>
</html>
