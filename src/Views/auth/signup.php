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
            max-width: 450px;
            width: 100%;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background: white;
            margin: auto;
        }
    </style>
</head>

<body>
    <div class="signup-card">
        <div class="text-center mb-4">
            <img src="/assets/images/logo.png" alt="Logo" height="60" class="mb-2">
            <h3>Timetable System</h3>
            <p class="text-muted">Register as CR, GR, or President</p>
        </div>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <form action="/signup" method="POST" id="signupForm">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-person"></i></span>
                    <input type="text" class="form-control" id="username" name="username" placeholder="e.g. Daniyal" required>
                </div>
                <div id="username-error" class="text-danger small mt-1" style="display: none;">Username already exists.</div>
            </div>
            
            <div class="mb-3">
                <label for="roll_number" class="form-label">Roll Number</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-card-text"></i></span>
                    <input type="text" class="form-control" id="roll_number" name="roll_number" placeholder="BITF22M501" required>
                </div>
                <div id="roll-error" class="text-danger small mt-1" style="display: none;">Roll number already registered.</div>
                <small class="text-muted">Use your university roll number as identifier.</small>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                    <input type="email" class="form-control" id="email" name="email" placeholder="e.g. user@example.com" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="phone_number" class="form-label">Phone Number</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-telephone"></i></span>
                    <input type="text" class="form-control" id="phone_number" name="phone_number" placeholder="e.g. 0300-1234567" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <button class="btn btn-outline-secondary toggle-password" type="button">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <div class="password-strength-meter mt-1">
                    <div class="progress" style="height: 5px;">
                        <div id="strength-bar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                    <small id="strength-text" class="text-muted" style="font-size: 0.75rem;">Password must contain letters and numbers (min 8 chars).</small>
                </div>
            </div>

            <div class="mb-4">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-shield-check"></i></span>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <small id="match-text" class="text-danger" style="display: none; font-size: 0.75rem;">Passwords do not match.</small>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="cr">CR (Class Representative)</option>
                    <option value="gr">GR (Girls Representative)</option>
                    <option value="president">President (Society)</option>
                </select>
            </div>
            
            <div class="mb-3" id="society-container" style="display: none;">
                <label for="society_id" class="form-label">Society</label>
                <select class="form-select" id="society_id" name="society_id">
                    <option value="" disabled selected>Select Society</option>
                    <?php if (isset($societies)): foreach ($societies as $society): ?>
                        <option value="<?= $society['id'] ?>"><?= htmlspecialchars($society['name']) ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>

            <div class="mb-3" id="batch-container" style="display: none;">
                <label for="batch_id" class="form-label">Batch</label>
                <select class="form-select" id="batch_id" name="batch_id">
                    <option value="" disabled selected>Select Batch</option>
                    <?php if (isset($batches)): foreach ($batches as $batch): ?>
                        <option value="<?= $batch['id'] ?>"><?= htmlspecialchars($batch['name']) ?></option>
                    <?php endforeach; endif; ?>
                </select>
            </div>
            
            <div class="mb-4" id="section-container" style="display: none;">
                <label for="section_id" class="form-label">Section</label>
                <select class="form-select" id="section_id" name="section_id" disabled>
                    <option value="" disabled selected>Select Section</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 mb-2">Sign Up</button>
            <div class="text-center mt-3">
                <small>Already registered? <a href="/login">Login here</a></small>
            </div>
        </form>
    </div>

    <script>
        // Pass sections data from PHP to JS
        const allSections = <?= json_encode($sections ?? []) ?>;

        document.getElementById('role').addEventListener('change', function() {
            const batchContainer = document.getElementById('batch-container');
            const sectionContainer = document.getElementById('section-container');
            const societyContainer = document.getElementById('society-container');
            const batchSelect = document.getElementById('batch_id');
            const sectionSelect = document.getElementById('section_id');
            const societySelect = document.getElementById('society_id');
            
            batchContainer.style.display = 'none';
            sectionContainer.style.display = 'none';
            societyContainer.style.display = 'none';
            batchSelect.required = false;
            sectionSelect.required = false;
            societySelect.required = false;

            if (this.value === 'cr' || this.value === 'gr') {
                batchContainer.style.display = 'block';
                sectionContainer.style.display = 'block';
                batchSelect.required = true;
                sectionSelect.required = true;
            } else if (this.value === 'president') {
                societyContainer.style.display = 'block';
                societySelect.required = true;
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

        const password = document.getElementById('password');
        const confirm = document.getElementById('confirm_password');
        const bar = document.getElementById('strength-bar');
        const text = document.getElementById('strength-text');
        const matchText = document.getElementById('match-text');

        password.addEventListener('input', function() {
            const val = this.value;
            let strength = 0;
            if (val.length >= 8) strength += 33;
            if (/[a-z]/i.test(val)) strength += 33;
            if (/[0-9]/.test(val)) strength += 34;

            bar.style.width = strength + '%';
            if (strength < 65) {
                bar.className = 'progress-bar bg-danger';
                text.textContent = 'Weak: Add letters and numbers';
            } else if (strength < 100) {
                bar.className = 'progress-bar bg-warning';
                text.textContent = 'Medium: At least 8 characters';
            } else {
                bar.className = 'progress-bar bg-success';
                text.textContent = 'Strong password';
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

        document.querySelectorAll('.toggle-password').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
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
