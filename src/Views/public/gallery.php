<?php
// src/Views/public/gallery.php
require '../src/Views/layouts/header.php';

// Fetch gallery items
$stmt = $pdo->query("SELECT * FROM gallery ORDER BY display_order ASC, created_at DESC");
$galleryItems = $stmt->fetchAll();

// Group items by title (Event Name)
$groupedItems = [];
foreach ($galleryItems as $item) {
    $groupedItems[$item['title']][] = $item;
}
?>

<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.3);
        --premium-blue: #1b3062;
        --accent-blue: #2563eb;
    }

    .gallery-header {
        text-align: center;
        margin-bottom: 60px;
        position: relative;
    }

    .gallery-header h1 {
        font-weight: 800;
        color: var(--premium-blue);
        letter-spacing: -1px;
        font-size: 3rem;
    }

    .header-line {
        width: 100px;
        height: 5px;
        background: linear-gradient(90deg, var(--accent-blue), #60a5fa);
        border-radius: 10px;
        margin: 20px auto;
    }

    .filter-container {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 50px;
        position: sticky;
        top: 20px;
        z-index: 100;
    }

    .filter-btn {
        padding: 10px 30px;
        border-radius: 50px;
        border: 1px solid var(--glass-border);
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        color: #4b5563;
        font-weight: 700;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .filter-btn.active {
        background: var(--premium-blue);
        color: white;
        border-color: var(--premium-blue);
        transform: scale(1.05);
        box-shadow: 0 10px 25px rgba(27, 48, 98, 0.3);
    }

    .event-group {
        margin-bottom: 80px;
    }

    .event-title {
        color: var(--premium-blue);
        font-weight: 800;
        margin-bottom: 35px;
        font-size: 1.75rem;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .event-title::after {
        content: "";
        flex-grow: 1;
        height: 2px;
        background: linear-gradient(90deg, #e5e7eb, transparent);
    }

    .masonry-grid {
        column-count: 3;
        column-gap: 25px;
    }

    @media (max-width: 1100px) {
        .masonry-grid { column-count: 2; }
    }
    @media (max-width: 768px) {
        .masonry-grid { column-count: 1; }
    }

    .gallery-item {
        break-inside: avoid;
        margin-bottom: 25px;
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        background: white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-block;
        width: 100%;
        cursor: pointer;
        opacity: 0;
        transform: translateY(30px);
    }

    .gallery-item.show {
        opacity: 1;
        transform: translateY(0);
    }

    .gallery-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
    }

    .media-container img, .media-container video {
        width: 100%;
        display: block;
        height: auto;
        transition: transform 0.8s cubic-bezier(0.2, 1, 0.3, 1);
    }

    .gallery-item:hover .media-container img {
        transform: scale(1.03);
    }

    .media-info {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 25px;
        background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.4) 60%, transparent 100%);
        color: white;
        transform: translateY(100%);
        transition: transform 0.4s ease;
    }

    .gallery-item:hover .media-info {
        transform: translateY(0);
    }

    .type-chip {
        position: absolute;
        top: 20px;
        right: 20px;
        padding: 6px 15px;
        background: var(--glass-bg);
        backdrop-filter: blur(8px);
        color: var(--premium-blue);
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        z-index: 10;
        border: 1px solid var(--glass-border);
    }

    /* Slideshow Lightbox Enhancement */
    #lightbox {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(10, 15, 30, 0.98);
        display: none;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        backdrop-filter: blur(15px);
    }

    .lightbox-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: none;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10001;
    }

    .lightbox-nav:hover {
        background: var(--accent-blue);
        transform: translateY(-50%) scale(1.1);
    }

    .nav-prev { left: 40px; }
    .nav-next { right: 40px; }

    #lightbox-wrapper {
        position: relative;
        max-width: 85vw;
        max-height: 80vh;
        display: flex;
        justify-content: center;
        align-items: center;
        animation: slideIn 0.5s cubic-bezier(0.2, 1, 0.3, 1);
    }

    @keyframes slideIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }

    #lightbox-content img, #lightbox-content video {
        max-width: 100%;
        max-height: 80vh;
        object-fit: contain;
        border-radius: 12px;
        box-shadow: 0 0 50px rgba(0,0,0,0.5);
    }

    #lightbox-caption {
        color: white;
        margin-top: 30px;
        text-align: center;
    }

    #lightbox-caption h4 {
        font-weight: 800;
        margin-bottom: 5px;
        font-size: 1.8rem;
    }

    #lightbox-caption p {
        opacity: 0.7;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .close-btn {
        position: absolute;
        top: 30px;
        right: 40px;
        color: white;
        font-size: 3rem;
        cursor: pointer;
        z-index: 10002;
        transition: all 0.3s ease;
    }

    .close-btn:hover {
        color: #ef4444;
        transform: rotate(90deg);
    }

    .slide-counter {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(255,255,255,0.1);
        padding: 5px 20px;
        border-radius: 30px;
        color: white;
        font-weight: 700;
        letter-spacing: 2px;
    }
</style>

<div class="container py-5">
    <div class="gallery-header">
        <h1>Gallery</h1>
        <div class="header-line"></div>
        <p class="lead text-muted">Discover our department's milestones and cherished memories.</p>
    </div>

    <div class="filter-container">
        <button class="filter-btn active" data-filter="all">All Media</button>
        <button class="filter-btn" data-filter="image">Photography</button>
        <button class="filter-btn" data-filter="video">Cinematics</button>
    </div>

    <div id="galleryContent">
        <?php foreach ($groupedItems as $title => $items): ?>
            <div class="event-group" data-title="<?= htmlspecialchars($title) ?>">
                <h3 class="event-title">
                    <i class="bi bi-stars"></i>
                    <?= htmlspecialchars($title) ?>
                </h3>
                <div class="masonry-grid">
                    <?php foreach ($items as $item): ?>
                        <div class="gallery-item" 
                             data-type="<?= $item['media_type'] ?>" 
                             data-path="<?= $item['media_path'] ?>" 
                             data-title="<?= htmlspecialchars($item['title']) ?>"
                             data-category="<?= ucfirst($item['category']) ?>"
                             onclick="openSlideshow(this)">
                            <span class="type-chip">
                                <i class="bi bi-<?= ($item['media_type'] === 'video') ? 'play-fill' : 'camera-fill' ?> me-1"></i>
                                <?= $item['media_type'] ?>
                            </span>
                            <div class="media-container">
                                <?php if ($item['media_type'] === 'image'): ?>
                                    <img src="<?= htmlspecialchars($item['media_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" loading="lazy">
                                <?php else: ?>
                                    <div class="ratio ratio-16x9 bg-dark rounded d-flex align-items-center justify-content-center">
                                        <i class="bi bi-play-circle display-1 text-white opacity-50"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="media-info">
                                <h5 class="mb-1 fw-bold"><?= htmlspecialchars($item['title']) ?></h5>
                                <div class="badge bg-primary rounded-pill px-3"><?= ucfirst($item['category']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Slideshow/Lightbox -->
<div id="lightbox">
    <span class="close-btn" onclick="closeLightbox()">&times;</span>
    <button class="lightbox-nav nav-prev" onclick="changeSlide(-1)">
        <i class="bi bi-chevron-left"></i>
    </button>
    <button class="lightbox-nav nav-next" onclick="changeSlide(1)">
        <i class="bi bi-chevron-right"></i>
    </button>
    
    <div id="lightbox-wrapper">
        <div id="lightbox-content"></div>
    </div>
    
    <div id="lightbox-caption">
        <h4 id="slide-title"></h4>
        <p id="slide-category"></p>
    </div>
    
    <div class="slide-counter">
        <span id="current-index">1</span> / <span id="total-count">1</span>
    </div>
</div>

<script>
    let currentSlideIndex = 0;
    let visibleItems = [];

    document.addEventListener('DOMContentLoaded', () => {
        // Intersection Observer for scroll animations
        const items = document.querySelectorAll('.gallery-item');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('show');
            });
        }, { threshold: 0.1 });

        items.forEach(item => observer.observe(item));

        // Filtering
        const filterBtns = document.querySelectorAll('.filter-btn');
        const groups = document.querySelectorAll('.event-group');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                const filter = btn.dataset.filter;
                groups.forEach(group => {
                    let visibleInGroup = 0;
                    group.querySelectorAll('.gallery-item').forEach(item => {
                        if (filter === 'all' || item.dataset.type === filter) {
                            item.style.display = 'inline-block';
                            setTimeout(() => { item.style.opacity = '1'; item.style.transform = 'scale(1)'; }, 10);
                            visibleInGroup++;
                        } else {
                            item.style.opacity = '0';
                            item.style.transform = 'scale(0.8)';
                            setTimeout(() => item.style.display = 'none', 300);
                        }
                    });
                    group.style.display = visibleInGroup > 0 ? 'block' : 'none';
                });
            });
        });

        // Keyboard support
        document.addEventListener('keydown', (e) => {
            if (document.getElementById('lightbox').style.display === 'flex') {
                if (e.key === 'ArrowRight') changeSlide(1);
                if (e.key === 'ArrowLeft') changeSlide(-1);
                if (e.key === 'Escape') closeLightbox();
            }
        });
    });

    function openSlideshow(clickedItem) {
        // Collect all currently visible items for the slideshow
        const currentFilter = document.querySelector('.filter-btn.active').dataset.filter;
        visibleItems = Array.from(document.querySelectorAll('.gallery-item')).filter(item => {
            return currentFilter === 'all' || item.dataset.type === currentFilter;
        });

        currentSlideIndex = visibleItems.indexOf(clickedItem);
        updateSlideshow();
        
        document.getElementById('lightbox').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function changeSlide(direction) {
        currentSlideIndex += direction;
        if (currentSlideIndex >= visibleItems.length) currentSlideIndex = 0;
        if (currentSlideIndex < 0) currentSlideIndex = visibleItems.length - 1;
        updateSlideshow();
    }

    function updateSlideshow() {
        const item = visibleItems[currentSlideIndex];
        const content = document.getElementById('lightbox-content');
        const title = document.getElementById('slide-title');
        const category = document.getElementById('slide-category');
        const currentIdxDisplay = document.getElementById('current-index');
        const totalDisplay = document.getElementById('total-count');

        // Add cinematic fade animation
        content.style.opacity = '0';
        
        setTimeout(() => {
            content.innerHTML = '';
            if (item.dataset.type === 'image') {
                const img = document.createElement('img');
                img.src = item.dataset.path;
                content.appendChild(img);
            } else {
                const video = document.createElement('video');
                video.src = item.dataset.path;
                video.controls = true;
                video.autoplay = true;
                content.appendChild(video);
            }
            
            title.innerText = item.dataset.title;
            category.innerText = item.dataset.category;
            currentIdxDisplay.innerText = currentSlideIndex + 1;
            totalDisplay.innerText = visibleItems.length;
            
            content.style.opacity = '1';
        }, 200);
    }

    function closeLightbox() {
        const lightbox = document.getElementById('lightbox');
        const content = document.getElementById('lightbox-content');
        lightbox.style.display = 'none';
        content.innerHTML = ''; // Stop video
        document.body.style.overflow = 'auto';
    }
</script>

<?php require '../src/Views/layouts/footer.php'; ?>
