<?php
// src/Views/public/academic_calendar.php
require '../src/Views/layouts/header.php';
?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-dark mb-3">Academic Calendar</h1>
        <div class="title-accent mx-auto mb-4"></div>
        <p class="lead text-muted mx-auto" style="max-width: 800px;">Stay up to date with the official academic schedule, holidays, and important deadlines for the upcoming semesters.</p>
    </div>

    <!-- Fall Semester 2025 -->
    <div class="calendar-section mb-5">
        <div class="d-flex align-items-center mb-4">
            <div class="icon-box bg-primary text-white me-3">
                <i class="bi bi-calendar3 fs-3"></i>
            </div>
            <h2 class="fw-bold text-dark mb-0">Fall Semester 2025</h2>
        </div>
        <div class="table-responsive rounded shadow-sm">
            <table class="table table-hover align-middle mb-0 bg-white">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Event</th>
                        <th class="text-end pe-4">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td class="ps-4">Commencement of Classes</td><td class="text-end pe-4 fw-bold">Sep 01</td></tr>
                    <tr><td class="ps-4">Eid Milad-un-Nabi**</td><td class="text-end pe-4 fw-bold">Sep 05</td></tr>
                    <tr><td class="ps-4">Deadline to enroll/change the courses</td><td class="text-end pe-4 fw-bold">Sep 08</td></tr>
                    <tr><td class="ps-4">Deadline to withdraw any course</td><td class="text-end pe-4 fw-bold">Sep 26</td></tr>
                    <tr><td class="ps-4">Deadline for submission of MS/MPhil/MSc(Hons)/PhD thesis</td><td class="text-end pe-4 fw-bold">Sep 30</td></tr>
                    <tr><td class="ps-4 text-primary fw-bold">Mid-term Exams***</td><td class="text-end pe-4 fw-bold text-primary">Oct 27-31</td></tr>
                    <tr><td class="ps-4">Iqbal Day*</td><td class="text-end pe-4 fw-bold">Nov 09</td></tr>
                    <tr><td class="ps-4">Quaid Day*</td><td class="text-end pe-4 fw-bold">Dec 25</td></tr>
                    <tr><td class="ps-4 text-primary fw-bold">Final-terms Exams***</td><td class="text-end pe-4 fw-bold text-primary">Dec 29-Jan 02</td></tr>
                    <tr><td class="ps-4 fw-bold">Declaration of Result</td><td class="text-end pe-4 fw-bold px-3 py-1 rounded bg-success-soft text-success d-inline-block mt-2 mb-2 me-4">Jan 09</td></tr>
                    <tr><td class="ps-4">Submission of Six Monthly report by PhD & MS/MPhil/MSc(Hons) scholars</td><td class="text-end pe-4 fw-bold">Jan 16</td></tr>
                    <tr><td class="ps-4">Comprehensive Examination of PhD</td><td class="text-end pe-4 fw-bold">Jan 14-16</td></tr>
                    <tr><td class="ps-4 text-muted">Semester Break for students</td><td class="text-end pe-4 fw-bold text-muted">Jan 19-30</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Spring Semester 2026 -->
    <div class="calendar-section mb-5">
        <div class="d-flex align-items-center mb-4">
            <div class="icon-box bg-primary text-white me-3">
                <i class="bi bi-calendar3-event fs-3"></i>
            </div>
            <h2 class="fw-bold text-dark mb-0">Spring Semester 2026</h2>
        </div>
        <div class="table-responsive rounded shadow-sm">
            <table class="table table-hover align-middle mb-0 bg-white">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Event</th>
                        <th class="text-end pe-4">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td class="ps-4">Commencement of Classes</td><td class="text-end pe-4 fw-bold">Feb 02</td></tr>
                    <tr><td class="ps-4">Kashmir Solidarity Day*</td><td class="text-end pe-4 fw-bold">Feb 05</td></tr>
                    <tr><td class="ps-4">Deadline to enroll/change the courses</td><td class="text-end pe-4 fw-bold">Feb 09</td></tr>
                    <tr><td class="ps-4">Deadline to withdraw any course</td><td class="text-end pe-4 fw-bold">Feb 27</td></tr>
                    <tr><td class="ps-4">Deadline for submission of MS/MPhil/MSc(Hons)/PhD thesis****</td><td class="text-end pe-4 fw-bold">Feb 28</td></tr>
                    <tr><td class="ps-4">Eid ul Fitr**</td><td class="text-end pe-4 fw-bold">Mar 19-21</td></tr>
                    <tr><td class="ps-4">Pakistan Day Celebrations*</td><td class="text-end pe-4 fw-bold">Mar 23</td></tr>
                    <tr><td class="ps-4 text-primary fw-bold">Mid Term Examination***</td><td class="text-end pe-4 fw-bold text-primary">Mar 30-Apr 03</td></tr>
                    <tr><td class="ps-4">Labor Day*</td><td class="text-end pe-4 fw-bold">May 01</td></tr>
                    <tr><td class="ps-4">Eid ul Azha**</td><td class="text-end pe-4 fw-bold">May 26-28</td></tr>
                    <tr><td class="ps-4">Yaum-i-Takbeer*</td><td class="text-end pe-4 fw-bold">May 28</td></tr>
                    <tr><td class="ps-4 text-primary fw-bold">Final Term Examinations***</td><td class="text-end pe-4 fw-bold text-primary">Jun 08-12</td></tr>
                    <tr><td class="ps-4 fw-bold">Declaration of Result</td><td class="text-end pe-4 fw-bold px-3 py-1 rounded bg-success-soft text-success d-inline-block mt-2 mb-2 me-4">Jun 19</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Summer Semester 2026 -->
    <div class="calendar-section mb-5">
        <div class="d-flex align-items-center mb-4">
            <div class="icon-box bg-primary text-white me-3">
                <i class="bi bi-sun fs-3"></i>
            </div>
            <h2 class="fw-bold text-dark mb-0">Summer Semester 2026</h2>
        </div>
        <div class="table-responsive rounded shadow-sm">
            <table class="table table-hover align-middle mb-0 bg-white">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Event</th>
                        <th class="text-end pe-4">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td class="ps-4">Registration and Submission of Fee</td><td class="text-end pe-4 fw-bold">Jun 22-29</td></tr>
                    <tr><td class="ps-4">Youm e Ashura**</td><td class="text-end pe-4 fw-bold">Jun 25</td></tr>
                    <tr><td class="ps-4">Commencement of Classes for Summer Semester</td><td class="text-end pe-4 fw-bold">Jun 29</td></tr>
                    <tr><td class="ps-4">Comprehensive Examination of PhD</td><td class="text-end pe-4 fw-bold">Jul 01-03</td></tr>
                    <tr><td class="ps-4">Submission of Six Monthly report by PhD & MS/MPhil/MSc(Hons) scholars</td><td class="text-end pe-4 fw-bold">Jul 17</td></tr>
                    <tr><td class="ps-4 text-primary fw-bold">Mid Term Examination***</td><td class="text-end pe-4 fw-bold text-primary">Jul 22-24</td></tr>
                    <tr><td class="ps-4">Independence Day*</td><td class="text-end pe-4 fw-bold">Aug 14</td></tr>
                    <tr><td class="ps-4 text-primary fw-bold">Final Term Examination***</td><td class="text-end pe-4 fw-bold text-primary">Aug 24-26</td></tr>
                    <tr><td class="ps-4 fw-bold">Declaration of Result</td><td class="text-end pe-4 fw-bold px-3 py-1 rounded bg-success-soft text-success d-inline-block mt-2 mb-2 me-4">Sep 01</td></tr>
                </tbody>
            </table>
        </div>
        <div class="alert alert-info mt-3 shadow-sm border-0">
            <i class="bi bi-info-circle-fill me-2"></i>
            <strong>Note:</strong> Summer Semester will be offered for undergraduate programs only.
        </div>
    </div>

    <!-- Notes/Legend -->
    <div class="row mt-5 pt-4 border-top">
        <div class="col-md-6 mb-4 mb-md-0">
            <h5 class="fw-bold text-dark mb-3">Holiday & Event Markers</h5>
            <ul class="list-unstyled small text-muted">
                <li class="mb-2"><span class="fw-bold text-primary">*</span> Subject to declaration of holiday by the Government of Punjab</li>
                <li class="mb-2"><span class="fw-bold text-primary">**</span> Subject to appearance of moon</li>
                <li class="mb-2"><span class="fw-bold text-primary">***</span> Off days may be included for examination if required</li>
                <li class="mb-2"><span class="fw-bold text-primary">****</span> As 28-02-2026 is Saturday so next working day will be considered as last date</li>
            </ul>
        </div>
        <div class="col-md-6">
            <h5 class="fw-bold text-dark mb-3">Contact Records</h5>
            <p class="small text-muted">For any queries regarding the academic calendar, please contact the departmental office during working hours.</p>
        </div>
    </div>
</div>

<style>
.title-accent {
    width: 60px;
    height: 4px;
    background: #1b3062;
    border-radius: 2px;
}
.icon-box {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background-color: #1b3062 !important;
}
.bg-success-soft {
    background-color: rgba(25, 135, 84, 0.1);
}
.table thead th {
    border-top: none;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
}
.calendar-section .table {
    border: 1px solid rgba(0,0,0,0.05);
}
.calendar-section .table tr:last-child td {
    border-bottom: none;
}
.calendar-section h2 {
    color: #1b3062 !important;
}
</style>

<?php require '../src/Views/layouts/footer.php'; ?>
