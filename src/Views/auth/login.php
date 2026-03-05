<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Timetable System</title>
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
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background: white;
            margin: 1rem;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <img src="/assets/images/logo.png" alt="Logo" height="60" class="mb-2">
            <h3>Timetable System</h3>
        </div>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['flash_message']); unset($_SESSION['flash_message']); ?>
            </div>
        <?php endif; ?>
        <form action="/login" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Roll Number / Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-vcard" viewBox="0 0 16 16">
                          <path d="M5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4m4-2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5M9 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4A.5.5 0 0 1 9 8m1 2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5"/>
                          <path d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM1 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H8.96q.04-.245.04-.5C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1 1 0 0 1 1 12z"/>
                        </svg>
                    </span>
                    <input type="text" class="form-control" id="username" name="identifier" required>
                </div>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <button class="btn btn-outline-secondary toggle-password" type="button">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Login As</label>
                <select class="form-select" id="role" name="role">
                    <option value="student">Student (View Only)</option>
                    <option value="admin">Admin</option>
                    <option value="cr">CR</option>
                    <option value="gr">GR</option>
                    <option value="president">President (Society)</option>
                </select>
            </div>

            <div class="mb-3" id="society-login-container" style="display: none;">
                <label for="society_id" class="form-label">Select Your Society</label>
                <select class="form-select" id="society_id" name="society_id">
                    <option value="" disabled selected>Select Society</option>
                    <?php if (isset($societies)): foreach ($societies as $society): ?>
                        <option value="<?= $society['id'] ?>"><?= htmlspecialchars($society['name']) ?></option>
                    <?php endforeach; endif; ?>
                </select>
                <small class="text-muted">Select the society you lead.</small>
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
            </script>
            
            <button type="submit" class="btn btn-primary w-100 mb-2">Login</button>
            <a href="/" class="btn btn-outline-secondary w-100 mb-2">Continue as Student</a>
            
            <div class="text-center mt-3">
                <small>Don't have an account? <a href="/signup">Sign up as CR/GR</a></small>
            </div>
        </form>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });
        });
    </script>
</body>

</html>