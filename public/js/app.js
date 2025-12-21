document.addEventListener('DOMContentLoaded', () => {
    // ==========================================
    // MOBILE MENU FUNCTIONALITY
    // ==========================================
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileNavClose = document.getElementById('mobileNavClose');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
    const mainNav = document.getElementById('mainNav');

    function openMobileMenu() {
        mobileMenuToggle?.classList.add('active');
        mobileMenuToggle?.setAttribute('aria-expanded', 'true');
        mainNav?.classList.add('active');
        mobileMenuOverlay?.classList.add('active');
        document.body.classList.add('mobile-menu-open');
    }

    function closeMobileMenu() {
        mobileMenuToggle?.classList.remove('active');
        mobileMenuToggle?.setAttribute('aria-expanded', 'false');
        mainNav?.classList.remove('active');
        mobileMenuOverlay?.classList.remove('active');
        document.body.classList.remove('mobile-menu-open');
    }

    mobileMenuToggle?.addEventListener('click', () => {
        if (mainNav?.classList.contains('active')) {
            closeMobileMenu();
        } else {
            openMobileMenu();
        }
    });

    mobileNavClose?.addEventListener('click', closeMobileMenu);
    mobileMenuOverlay?.addEventListener('click', closeMobileMenu);

    // Close mobile menu when clicking a link
    const mobileNavLinks = mainNav?.querySelectorAll('a');
    mobileNavLinks?.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                closeMobileMenu();
            }
        });
    });

    // Close mobile menu on window resize if larger than mobile breakpoint
    window.addEventListener('resize', () => {
        if (window.innerWidth > 768) {
            closeMobileMenu();
        }
    });

    // Close mobile menu on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && mainNav?.classList.contains('active')) {
            closeMobileMenu();
        }
    });

    // ==========================================
    // SEARCH FUNCTIONALITY
    // ==========================================
    const searchToggle = document.getElementById('searchToggle');
    const searchContainer = document.getElementById('searchContainer');
    const searchClose = document.getElementById('searchClose');
    const searchInput = document.getElementById('searchInput');

    searchToggle?.addEventListener('click', () => {
        searchContainer?.classList.add('active');
        searchInput?.focus();
    });

    searchClose?.addEventListener('click', () => {
        searchContainer?.classList.remove('active');
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && searchContainer?.classList.contains('active')) {
            searchContainer?.classList.remove('active');
        }
        // Open search with Ctrl/Cmd + K
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchContainer?.classList.add('active');
            searchInput?.focus();
        }
    });

    // ==========================================
    // DARK MODE TOGGLE
    // ==========================================
    const themeToggle = document.getElementById('themeToggle');
    const siteHeader = document.getElementById('siteHeader');
    
    // Check for saved theme preference or default to light
    const savedTheme = localStorage.getItem('theme') || 'light';
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
        siteHeader?.classList.add('dark');
    }

    themeToggle?.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        siteHeader?.classList.toggle('dark');
        
        const isDark = document.body.classList.contains('dark-mode');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    });

    // ==========================================
    // DROPDOWN MENUS (Desktop)
    // ==========================================
    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(dropdown => {
        const button = dropdown.querySelector('.dropbtn');
        const menu = dropdown.querySelector('.dropdown-content');
        if (!button || !menu) return;

        button.addEventListener('click', event => {
            event.preventDefault();
            const isVisible = menu.classList.contains('visible');
            menu.classList.toggle('visible');
            button.setAttribute('aria-expanded', !isVisible);
        });

        document.addEventListener('click', event => {
            if (!dropdown.contains(event.target)) {
                menu.classList.remove('visible');
                button.setAttribute('aria-expanded', 'false');
            }
        });
    });

    // ==========================================
    // HEADER SCROLL EFFECT
    // ==========================================
    let lastScroll = 0;
    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > 100) {
            siteHeader?.classList.add('scrolled');
        } else {
            siteHeader?.classList.remove('scrolled');
        }
        
        lastScroll = currentScroll;
    });

    // ==========================================
    // SMOOTH SCROLLING
    // ==========================================
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', event => {
            const targetId = link.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            if (targetElement) {
                event.preventDefault();
                targetElement.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // ==========================================
    // LAZY LOADING IMAGES
    // ==========================================
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                observer.unobserve(img);
            }
        });
    });
    images.forEach(img => imageObserver.observe(img));

    // ==========================================
    // BACK TO TOP BUTTON
    // ==========================================
    const backToTopBtn = document.createElement('button');
    backToTopBtn.innerHTML = '↑';
    backToTopBtn.className = 'back-to-top';
    backToTopBtn.setAttribute('aria-label', 'Back to top');
    backToTopBtn.style.cssText = `
        position: fixed;
        bottom: 24px;
        right: 24px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        border: none;
        border-radius: 12px;
        width: 48px;
        height: 48px;
        font-size: 20px;
        cursor: pointer;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(255, 153, 0, 0.3);
    `;
    document.body.appendChild(backToTopBtn);

    window.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            backToTopBtn.style.opacity = '1';
            backToTopBtn.style.visibility = 'visible';
        } else {
            backToTopBtn.style.opacity = '0';
            backToTopBtn.style.visibility = 'hidden';
        }
    });

    backToTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    backToTopBtn.addEventListener('mouseenter', () => {
        backToTopBtn.style.transform = 'translateY(-4px)';
        backToTopBtn.style.boxShadow = '0 8px 24px rgba(255, 153, 0, 0.4)';
    });

    backToTopBtn.addEventListener('mouseleave', () => {
        backToTopBtn.style.transform = 'translateY(0)';
        backToTopBtn.style.boxShadow = '0 4px 12px rgba(255, 153, 0, 0.3)';
    });

    // ==========================================
    // BUTTON LOADING ANIMATION
    // ==========================================
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Skip for auth forms to prevent infinite loading
            const form = btn.closest('form');
            if (form && !form.classList.contains('auth-form-grid')) {
                btn.innerHTML = '<span class="spinner"></span> Loading...';
                btn.disabled = true;
            }
        });
    });

    // ==========================================
    // COUNTDOWN TIMER FOR PROMO BANNER
    // ==========================================
    const countdownTimer = document.getElementById('countdownTimer');
    if (countdownTimer) {
        // Set end date to 7 days from now
        const endDate = new Date();
        endDate.setDate(endDate.getDate() + 7);
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = endDate.getTime() - now;
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            const daysEl = countdownTimer.querySelector('[data-days]');
            const hoursEl = countdownTimer.querySelector('[data-hours]');
            const minutesEl = countdownTimer.querySelector('[data-minutes]');
            const secondsEl = countdownTimer.querySelector('[data-seconds]');
            
            if (daysEl) daysEl.textContent = String(days).padStart(2, '0');
            if (hoursEl) hoursEl.textContent = String(hours).padStart(2, '0');
            if (minutesEl) minutesEl.textContent = String(minutes).padStart(2, '0');
            if (secondsEl) secondsEl.textContent = String(seconds).padStart(2, '0');
            
            if (distance < 0) {
                clearInterval(countdownInterval);
                countdownTimer.innerHTML = '<span style="color: white;">Sale Ended</span>';
            }
        }
        
        updateCountdown();
        const countdownInterval = setInterval(updateCountdown, 1000);
    }
});
