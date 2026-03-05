</div> <!-- End of main container -->

<footer class="bg-dark text-white pt-5 pb-3 mt-5">
    <div class="container">
        <div class="row g-4">
            <!-- Brand & About -->
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <img src="/assets/images/logo.png" alt="Logo" height="35" class="me-2 filter-white">
                    <h5 class="mb-0 fw-bold">Timetable System</h5>
                </div>
                <p class="text-muted small">Providing a seamless academic experience through efficient scheduling and accessible faculty information. Dedicated to excellence in education and innovation.</p>
                <div class="social-links mt-3">
                    <a href="#" class="text-white-50 me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white-50 me-3"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-white-50 me-3"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="text-white-50"><i class="bi bi-instagram"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">Navigation</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="/" class="text-white-50 text-decoration-none hover-white">Home</a></li>
                    <li class="mb-2"><a href="/timetable" class="text-white-50 text-decoration-none hover-white">Timetable</a></li>
                    <li class="mb-2"><a href="/faculty" class="text-white-50 text-decoration-none hover-white">Faculty</a></li>
                    <li class="mb-2"><a href="/societies" class="text-white-50 text-decoration-none hover-white">Societies</a></li>
                    <li class="mb-2"><a href="/login" class="text-white-50 text-decoration-none hover-white">Admin Login</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">Support</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-white">Help Center</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-white">Privacy Policy</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-white">Terms of Use</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-white">Contact Us</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-4 col-md-6">
                <h6 class="fw-bold mb-3 text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">Contact Us</h6>
                <ul class="list-unstyled small text-white-50">
                    <li class="mb-2 d-flex align-items-start">
                        <i class="bi bi-geo-alt-fill me-2 text-primary"></i>
                        <span>Department of Information Technology, Ch. Muhammad Ali block, University of Sargodha, Sargodha, Pakistan</span>
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                        <i class="bi bi-envelope-fill me-2 text-primary"></i>
                        <span>Chairman.it@uos.edu.pk</span>
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                        <i class="bi bi-telephone-fill me-2 text-primary"></i>
                        <span>048-9230811-15 Ex.364</span>
                    </li>
                </ul>
            </div>
        </div>

        <hr class="my-4 border-secondary opacity-25">

        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 small text-white-50">&copy; <?= date('Y') ?> University Timetable Management System. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                <p class="mb-0 small text-white-50">Designed for Educational Excellence</p>
            </div>
        </div>
    </div>
</footer>

<style>
.filter-white { filter: brightness(0) invert(1); }
.hover-white:hover { color: white !important; transition: color 0.2s ease; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>