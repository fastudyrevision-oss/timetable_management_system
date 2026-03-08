<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Timetable System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px 0;
        }

        .signup-card {
            max-width: 500px;
            width: 100%;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            background: white;
            margin: auto;
            animation: slideUpFade 0.6s ease-out;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-out {
            opacity: 0 !important;
            transform: translateX(50px) !important;
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

        .form-control, .form-select {
            padding: 0.75rem 1rem;
        }
    </style>
</head>

<body>
    <div class="signup-card" id="authCard">
        <div class="text-center mb-4">
            <img src="/assets/images/logo.png" alt="Logo" height="70" class="mb-3 transition-hover">
            <h3 class="fw-bold text-dark">Create Account</h3>
            <p class="text-muted small">Register as CR, GR, or Society President</p>
        </div>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger border-0 shadow-sm rounded-3 small">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <form action="/signup" method="POST" id="signupForm">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="username" class="form-label fw-bold small">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-person text-muted"></i></span>
                        <input type="text" class="form-control border-0 bg-light" id="username" name="username" placeholder="e.g. Daniyal" required>
                    </div>
                    <div id="username-error" class="text-danger extra-small mt-1" style="display: none;">Username taken.</div>
                </div>
                
                <div class="col-md-6">
                    <label for="roll_number" class="form-label fw-bold small">Roll Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-card-text text-muted"></i></span>
                        <input type="text" class="form-control border-0 bg-light" id="roll_number" name="roll_number" placeholder="BITF22M501" required>
                    </div>
                    <div id="roll-error" class="text-danger extra-small mt-1" style="display: none;">Roll no registered.</div>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-bold small">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-envelope text-muted"></i></span>
                    <input type="email" class="form-control border-0 bg-light" id="email" name="email" placeholder="user@example.com" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="phone_number" class="form-label fw-bold small">Phone Number</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-telephone text-muted"></i></span>
                    <input type="text" class="form-control border-0 bg-light" id="phone_number" name="phone_number" placeholder="0300-1234567" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-bold small">Create Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-lock text-muted"></i></span>
                    <input type="password" class="form-control border-0 bg-light" id="password" name="password" placeholder="••••••••" required>
                    <button class="btn btn-light border-0 toggle-password" type="button">
                        <i class="bi bi-eye text-muted"></i>
                    </button>
                </div>
                <div class="password-strength-meter mt-2">
                    <div class="progress" style="height: 4px;">
                        <div id="strength-bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                    <small id="strength-text" class="text-muted extra-small">Complexity: low</small>
                </div>
            </div>

            <div class="mb-4">
                <label for="confirm_password" class="form-label fw-bold small">Verify Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-shield-check text-muted"></i></span>
                    <input type="password" class="form-control border-0 bg-light" id="confirm_password" name="confirm_password" placeholder="••••••••" required>
                </div>
                <small id="match-text" class="text-danger extra-small" style="display: none;">Passwords do not match.</small>
            </div>

            <div class="mb-4 pt-2 border-top">
                <label for="role" class="form-label fw-bold small">Registering As</label>
                <select class="form-select border-0 bg-primary bg-opacity-10 text-primary fw-bold" id="role" name="role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="cr">CR (Class Representative)</option>
                    <option value="gr">GR (Girls Representative)</option>
                    <option value="president">President (Society)</option>
                </select>
            </div>
            
            <div class="mb-3" id="society-container" style="display: none;">
                <label for="society_id" class="form-label fw-bold small">Your Society</label>
                <select class="form-select border-0 bg-light" id="society_id" name="society_id">
                    <option value="" disabled selected>Select Society</option>
                    <?php if (isset($societies)): foreach ($societies as $society): ?>
                        <option value="<?= $society['id'] ?>"><?= htmlspecialchars($society['name']) ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>

            <div class="mb-3" id="batch-container" style="display: none;">
                <label for="batch_id" class="form-label fw-bold small">Batch</label>
                <select class="form-select border-0 bg-light" id="batch_id" name="batch_id">
                    <option value="" disabled selected>Select Batch</option>
                    <?php if (isset($batches)): foreach ($batches as $batch): ?>
                        <option value="<?= $batch['id'] ?>"><?= htmlspecialchars($batch['name']) ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
            
            <div class="mb-4" id="section-container" style="display: none;">
                <label for="section_id" class="form-label fw-bold small">Section</label>
                <select class="form-select border-0 bg-light" id="section_id" name="section_id" disabled>
                    <option value="" disabled selected>Select Section</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 rounded-pill mb-3 py-2">Create Account</button>
            <div class="text-center mt-2">
                <p class="text-muted small">Already registered? <a href="/login" id="toLogin" class="text-primary fw-bold text-decoration-none transition-hover">Login Now</a></p>
            </div>
        </form>
    </div>

    <script>
        const allSections = <?= json_encode($sections ?? []) ?>;

        document.getElementById('role').addEventListener('change', function() {
            const batchContainer = document.getElementById('batch-container');
            const sectionContainer = document.getElementById('section-container');
            const societyContainer = document.getElementById('society-container');
            
            batchContainer.style.display = 'none';
            sectionContainer.style.display = 'none';
            societyContainer.style.display = 'none';

            if (this.value === 'cr' || this.value === 'gr') {
                batchContainer.style.display = 'block';
                sectionContainer.style.display = 'block';
            } else if (this.value === 'president') {
                societyContainer.style.display = 'block';
            }
        });

        document.getElementById('batch_id').addEventListener('change', function() {
            const batchId = this.value;
            const sectionSelect = document.getElementById('section_id');
            sectionSelect.innerHTML = '<option value="" disabled selected>Select Section</option>';
            
            if (batchId) {
                const filteredSections = allSections.filter(s => s.batch_id == batchId);
                filteredSections.forEach(s => {
                    const option = document.createElement('option');
                    option.value = s.id;
                    option.textContent = s.name;
                    sectionSelect.appendChild(option);
                });
                sectionSelect.disabled = false;
            } else {
                sectionSelect.disabled = true;
            }
        });

        // Password strength and matching
        const password = document.getElementById('password');
        const confirm = document.getElementById('confirm_password');
        const matchText = document.getElementById('match-text');
        const bar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');

        password.addEventListener('input', function() {
            const val = this.value;
            let strength = 0;
            if (val.length >= 8) strength += 33;
            if (/[a-z]/i.test(val)) strength += 33;
            if (/[0-9]/.test(val)) strength += 34;

            bar.style.width = strength + '%';
            if (strength < 65) {
                bar.className = 'progress-bar bg-danger';
                strengthText.textContent = 'Weak: Add letters and numbers';
            } else if (strength < 100) {
                bar.className = 'progress-bar bg-warning';
                strengthText.textContent = 'Medium: At least 8 characters';
            } else {
                bar.className = 'progress-bar bg-success';
                strengthText.textContent = 'Strong password';
            }
        });

        confirm.addEventListener('input', function() {
            if (this.value !== password.value) {
                matchText.style.display = 'block';
                this.classList.add('is-invalid');
            } else {
                matchText.style.display = 'none';
                this.classList.remove('is-invalid');
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

        // Animation transition to Login
        document.getElementById('toLogin').addEventListener('click', function(e) {
            e.preventDefault();
            const card = document.getElementById('authCard');
            card.classList.add('fade-out');
            setTimeout(() => {
                window.location.href = this.href;
            }, 500);
        });

        // Uniqueness validation logic
        const submitBtn = document.querySelector('button[type="submit"]');
        let usernameValid = true;
        let rollValid = true;

        function updateSubmitButton() {
            submitBtn.disabled = !(usernameValid && rollValid);
        }

        function checkUniqueness(type, value, errorId, validFlagUpdater) {
            if (!value) {
                document.getElementById(errorId).style.display = 'none';
                validFlagUpdater(true);
                updateSubmitButton();
                return;
            }

            fetch(`/auth/check-uniqueness?type=${type}&value=${encodeURIComponent(value)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.exists) {
                        document.getElementById(errorId).style.display = 'block';
                        validFlagUpdater(false);
                    } else {
                        document.getElementById(errorId).style.display = 'none';
                        validFlagUpdater(true);
                    }
                    updateSubmitButton();
                });
        }

        let usernameTimeout;
        document.getElementById('username').addEventListener('input', function() {
            clearTimeout(usernameTimeout);
            usernameTimeout = setTimeout(() => {
                checkUniqueness('username', this.value, 'username-error', (v) => usernameValid = v);
            }, 500);
        });

        let rollTimeout;
        document.getElementById('roll_number').addEventListener('input', function() {
            clearTimeout(rollTimeout);
            rollTimeout = setTimeout(() => {
                checkUniqueness('roll_number', this.value, 'roll-error', (v) => rollValid = v);
            }, 500);
        });
    </script>
</body>
</html>
