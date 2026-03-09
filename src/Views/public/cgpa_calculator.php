<?php
// src/Views/public/cgpa_calculator.php
require '../src/Views/layouts/header.php';
?>

<style>
    :root {
        --primary-blue: #1b3062;
        --accent-blue: #2563eb;
        --light-bg: #f8fafc;
        --border-color: #e5e7eb;
    }

    body {
        background: var(--light-bg);
    }

    .calculator-container {
        max-width: 900px;
        margin: 20px auto 50px;
        padding: 40px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        border: 1px solid var(--border-color);
    }

    .calc-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .calc-header h1 {
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 10px;
    }

    .header-line {
        width: 80px;
        height: 4px;
        background: var(--accent-blue);
        border-radius: 2px;
        margin: 0 auto 20px;
    }

    .mode-selector {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 35px;
    }

    .mode-btn {
        padding: 10px 25px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: white;
        color: #4b5563;
        font-weight: 600;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .mode-btn.active {
        background: var(--primary-blue);
        color: white;
        border-color: var(--primary-blue);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .mode-btn:hover:not(.active) {
        border-color: var(--accent-blue);
        color: var(--accent-blue);
    }

    .calc-section {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .calc-section.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .subject-row, .semester-row {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 20px;
        margin-bottom: 15px;
        align-items: end;
        padding: 20px;
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        transition: border-color 0.2s ease;
    }

    .subject-row:hover, .semester-row:hover {
        border-color: var(--accent-blue);
    }

    .input-label {
        font-size: 0.85rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 8px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control-calc {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background: #f9fafb;
        transition: all 0.2s ease;
    }

    .form-control-calc:focus {
        background: white;
        border-color: var(--accent-blue);
        outline: none;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .result-card {
        margin-top: 40px;
        padding: 30px;
        text-align: center;
        background: var(--light-bg);
        border: 2px solid var(--primary-blue);
        border-radius: 12px;
    }

    .result-title {
        color: var(--primary-blue);
        font-weight: 700;
        margin-bottom: 10px;
    }

    .result-value {
        font-size: 3.5rem;
        font-weight: 800;
        color: var(--primary-blue);
        margin-bottom: 5px;
    }

    .result-grade {
        font-size: 1.4rem;
        font-weight: 700;
        color: #059669; /* Green for grade */
    }

    .btn-calculate {
        width: 100%;
        padding: 16px;
        margin-top: 25px;
        border: none;
        border-radius: 8px;
        background: var(--primary-blue);
        color: white;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .btn-calculate:hover {
        background: #152950;
    }

    .grading-table-toggle {
        text-align: center;
        margin-top: 30px;
    }

    .grading-table-toggle a {
        color: var(--accent-blue);
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
    }

    .grading-table-toggle a:hover {
        text-decoration: underline;
    }

    @media (max-width: 600px) {
        .subject-row, .semester-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="calculator-container">
    <div class="calc-header">
        <h1>CGPA Calculator</h1>
        <div class="header-line"></div>
        <p class="lead text-muted">University Grading System Compliant</p>
    </div>

    <div class="mode-selector">
        <button class="mode-btn active" onclick="switchMode('gpa')">Calculate GPA</button>
        <button class="mode-btn" onclick="switchMode('cgpa')">Calculate CGPA</button>
    </div>

    <!-- GPA Section -->
    <div id="gpa-section" class="calc-section active">
        <div class="subject-list">
            <?php for ($i = 1; $i <= 8; $i++): ?>
            <div class="subject-row">
                <div>
                    <span class="input-label">Subject <?= $i ?></span>
                    <input type="text" class="form-control-calc" placeholder="Subject Name (Optional)">
                </div>
                <div>
                    <span class="input-label">Marks (0-100)</span>
                    <input type="number" step="0.01" class="form-control-calc gpa-marks" placeholder="Marks" min="0" max="100">
                </div>
                <div>
                    <span class="input-label">Credit Hours</span>
                    <input type="number" step="0.5" class="form-control-calc gpa-hours" placeholder="Cr.Hrs" min="0">
                </div>
            </div>
            <?php endfor; ?>
        </div>
        <button class="btn-calculate" onclick="calculateGPA()">Calculate Semester GPA</button>
    </div>

    <!-- CGPA Section -->
    <div id="cgpa-section" class="calc-section">
        <div class="semester-list">
            <?php for ($i = 1; $i <= 8; $i++): ?>
            <div class="semester-row">
                <div>
                    <span class="input-label">Semester <?= $i ?></span>
                    <input type="text" class="form-control-calc" value="Semester <?= $i ?>" readonly>
                </div>
                <div>
                    <span class="input-label">GPA (0-4.0)</span>
                    <input type="number" step="0.01" class="form-control-calc cgpa-val" placeholder="GPA" min="0" max="4">
                </div>
                <div>
                    <span class="input-label">Total Credit Hours</span>
                    <input type="number" step="0.5" class="form-control-calc cgpa-hours" placeholder="Cr.Hrs" min="0">
                </div>
            </div>
            <?php endfor; ?>
        </div>
        <button class="btn-calculate" onclick="calculateCGPA()">Calculate CGPA</button>
    </div>

    <div id="result-area" style="display: none;">
        <div class="result-card">
            <h4 id="result-title">Your Semester GPA</h4>
            <div class="result-value" id="result-value">0.00</div>
            <div class="result-grade" id="result-grade">Grade: -</div>
        </div>
    </div>

    <div class="grading-table-toggle">
        <a onclick="toggleGradingTable()">Show Grading System Table</a>
        <div id="grading-table" class="grading-table">
            <table class="table table-sm table-striped">
                <thead>
                    <tr><th>Marks</th><th>GPT</th><th>Grade</th></tr>
                </thead>
                <tbody id="grading-body">
                    <!-- Points will be injected by JS -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const fullGradingMap = {
        0:0.0, 1:0.0, 2:0.0, 3:0.0, 4:0.0, 5:0.0, 6:0.0, 7:0.0, 8:0.0, 9:0.0, 10:0.0, 11:0.0, 12:0.0, 13:0.0, 14:0.0, 15:0.0, 16:0.0, 17:0.0, 18:0.0, 19:0.0, 20:0.0, 21:0.0, 22:0.0, 23:0.0, 24:0.0, 25:0.0, 26:0.0, 27:0.0, 28:0.0, 29:0.0, 30:0.0, 31:0.0, 32:0.0, 33:0.0, 34:0.0, 35:0.0, 36:0.0, 37:0.0, 38:0.0, 39:0.0, 40:1, 41:1.1, 42:1.2, 43:1.3, 44:1.4, 45:1.5, 46:1.6, 47:1.7, 48:1.8, 49:1.9, 50:2, 51:2.06, 52:2.12, 53:2.18, 54:2.24, 55:2.3, 56:2.36, 57:2.43, 58:2.5, 59:2.57, 60:2.64, 61:2.7, 62:2.78, 63:2.85, 64:2.92, 65:3, 66:3.07, 67:3.14, 68:3.2, 69:3.27, 70:3.34, 71:3.4, 72:3.47, 73:3.54, 74:3.6, 75:3.67, 76:3.74, 77:3.8, 78:3.87, 79:3.94, 80:4, 81:4, 82:4, 83:4, 84:4, 85:4, 86:4, 87:4, 88:4, 89:4, 90:4, 91:4, 92:4, 93:4, 94:4, 95:4, 96:4, 97:4, 98:4, 99:4, 100:4
    };

    function getGradeLetter(gpt) {
        if (gpt >= 4.0) return 'A';
        if (gpt >= 3.0) return 'B';
        if (gpt >= 2.0) return 'C';
        if (gpt >= 1.0) return 'D';
        return 'F';
    }

    function getGPAForMarks(marks) {
        marks = Math.round(marks);
        if (marks > 100) marks = 100;
        if (marks < 0) marks = 0;
        
        const gpt = fullGradingMap[marks] || 0.0;
        return {gpt: gpt, grade: getGradeLetter(gpt)};
    }

    function switchMode(mode) {
        document.querySelectorAll('.mode-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.calc-section').forEach(sec => sec.classList.remove('active'));
        
        if (mode === 'gpa') {
            document.querySelector('.mode-btn:nth-child(1)').classList.add('active');
            document.getElementById('gpa-section').classList.add('active');
        } else {
            document.querySelector('.mode-btn:nth-child(2)').classList.add('active');
            document.getElementById('cgpa-section').classList.add('active');
        }
        document.getElementById('result-area').style.display = 'none';
    }

    function calculateGPA() {
        const marksInputs = document.querySelectorAll('.gpa-marks');
        const hoursInputs = document.querySelectorAll('.gpa-hours');
        
        let totalQualityPoints = 0;
        let totalHours = 0;
        
        marksInputs.forEach((input, index) => {
            const marks = parseFloat(input.value);
            const hours = parseFloat(hoursInputs[index].value);
            
            if (!isNaN(marks) && !isNaN(hours) && hours > 0) {
                const res = getGPAForMarks(marks);
                totalQualityPoints += (res.gpt * hours);
                totalHours += hours;
            }
        });
        
        if (totalHours === 0) {
            alert("Please enter marks and credit hours for at least one subject.");
            return;
        }
        
        const gpa = totalQualityPoints / totalHours;
        displayResult(gpa.toFixed(2), "Your Semester GPA", gpa);
    }

    function calculateCGPA() {
        const gpaInputs = document.querySelectorAll('.cgpa-val');
        const hoursInputs = document.querySelectorAll('.cgpa-hours');
        
        let totalQualityPoints = 0;
        let totalHours = 0;
        
        gpaInputs.forEach((input, index) => {
            const gpa = parseFloat(input.value);
            const hours = parseFloat(hoursInputs[index].value);
            
            if (!isNaN(gpa) && !isNaN(hours) && hours > 0) {
                totalQualityPoints += (gpa * hours);
                totalHours += hours;
            }
        });
        
        if (totalHours === 0) {
            alert("Please enter GPA and credit hours for at least one semester.");
            return;
        }
        
        const cgpa = totalQualityPoints / totalHours;
        displayResult(cgpa.toFixed(2), "Your CGPA", cgpa);
    }

    function displayResult(value, title, numericVal) {
        document.getElementById('result-area').style.display = 'block';
        document.getElementById('result-title').innerText = title;
        document.getElementById('result-value').innerText = value;
        
        let grade = '-';
        if (numericVal >= 4.0) grade = 'A';
        else if (numericVal >= 3.0) grade = 'B';
        else if (numericVal >= 2.0) grade = 'C';
        else if (numericVal >= 1.0) grade = 'D';
        else grade = 'F';
        
        document.getElementById('result-grade').innerText = "Grade: " + grade;
        
        // Scroll to result
        document.getElementById('result-area').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function toggleGradingTable() {
        const table = document.getElementById('grading-table');
        if (table.style.display === 'block') {
            table.style.display = 'none';
        } else {
            table.style.display = 'block';
            if (document.getElementById('grading-body').innerHTML === '') {
                populateGradingTable();
            }
        }
    }

    function populateGradingTable() {
        const body = document.getElementById('grading-body');
        // Let's create a more complete range for display
        let html = '';
        for (let m = 100; m >= 0; m--) {
            if (m % 5 === 0 || m === 100 || m === 40) {
                const res = getGPAForMarks(m);
                html += `<tr><td>${m}</td><td>${res.gpt.toFixed(2)}</td><td>${res.grade}</td></tr>`;
            }
        }
        body.innerHTML = html;
    }
</script>

<?php require '../src/Views/layouts/footer.php'; ?>
