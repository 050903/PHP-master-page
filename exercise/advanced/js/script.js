// js/script.js - Advanced 3D & Animation

document.addEventListener('DOMContentLoaded', function() {
    console.log('script.js đã được tải thành công!');

    // Highlight effect for song links
    const songLinks = document.querySelectorAll('.song-link');
    songLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            this.classList.add('song-highlight');
        });
        link.addEventListener('mouseleave', function() {
            this.classList.remove('song-highlight');
        });
    });

    // 3D hover for artist cards
    const artistCards = document.querySelectorAll('.artist-list-outer > li');
    artistCards.forEach(card => {
        card.addEventListener('mousemove', function(e) {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateX = ((y - centerY) / centerY) * 8;
            const rotateY = ((x - centerX) / centerX) * 12;
            card.style.transform = `perspective(900px) rotateX(${-rotateX}deg) rotateY(${rotateY}deg) scale(1.03)`;
        });
        card.addEventListener('mouseleave', function() {
            card.style.transform = '';
        });
    });

    // Floating hero text/buttons
    const hero = document.querySelector('.site-header .container');
    if (hero) {
        hero.style.transition = 'transform 1.5s cubic-bezier(.25,1.5,.5,1)';
        setInterval(() => {
            const tx = Math.random() * 8 - 4;
            const ty = Math.random() * 8 - 4;
            hero.style.transform = `translate(${tx}px, ${ty}px)`;
        }, 2200);
    }

    // Parallax hero background
    const header = document.querySelector('.site-header');
    if (header) {
        window.addEventListener('scroll', function() {
            const scrollY = window.scrollY;
            header.style.backgroundPosition = `center ${scrollY * 0.2}px`;
        });
    }

    // Search form validation
    const searchForm = document.querySelector('form.search-form');
    const searchInput = document.querySelector('input[name="search"]');
    if (searchForm && searchInput) {
        searchForm.addEventListener('submit', function(event) {
            const searchTerm = searchInput.value.trim();
            if (searchTerm === '') {
                event.preventDefault();
                showToast('Vui lòng nhập từ khóa tìm kiếm!');
                searchInput.focus();
            }
        });
    }

    // Song play toast/modal
    songLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const songTitle = this.getAttribute('data-title') || this.textContent;
            showToast(`Đang phát nhạc: <b>${songTitle}</b>`);
        });
    });

    // Smooth scroll for hero CTA
    const heroCta = document.querySelector('.hero-cta');
    if (heroCta) {
        heroCta.addEventListener('click', function(e) {
            const target = document.getElementById('music-list');
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }

    // Toast function
    function showToast(message) {
        let toast = document.getElementById('custom-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'custom-toast';
            toast.style.position = 'fixed';
            toast.style.bottom = '32px';
            toast.style.left = '50%';
            toast.style.transform = 'translateX(-50%)';
            toast.style.background = '#222';
            toast.style.color = '#fff';
            toast.style.padding = '1rem 2rem';
            toast.style.borderRadius = '2rem';
            toast.style.fontSize = '1.1rem';
            toast.style.boxShadow = '0 2px 12px 0 rgba(0,0,0,0.15)';
            toast.style.zIndex = '9999';
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            document.body.appendChild(toast);
        }
        toast.innerHTML = message;
        toast.style.opacity = '1';
        setTimeout(() => {
            toast.style.opacity = '0';
        }, 2000);
    }

    // --- 3D Galaxy Squares Animation ---
    const galaxyBg = document.querySelector('.galaxy-bg');
    if (galaxyBg) {
        const SQUARES = 18;
        const squares = [];
        const w = window.innerWidth;
        const h = window.innerHeight;
        for (let i = 0; i < SQUARES; i++) {
            const sq = document.createElement('div');
            sq.className = 'galaxy-square';
            // Randomize initial position and depth
            sq._x = Math.random() * w;
            sq._y = Math.random() * h;
            sq._z = Math.random() * 600 + 200;
            sq._rx = Math.random() * 360;
            sq._ry = Math.random() * 360;
            sq._speed = 0.2 + Math.random() * 0.4;
            galaxyBg.appendChild(sq);
            squares.push(sq);
        }
        function animateGalaxy() {
            for (let sq of squares) {
                sq._ry += sq._speed;
                sq._rx += sq._speed * 0.7;
                sq._z -= sq._speed * 2;
                if (sq._z < 80) {
                    sq._z = Math.random() * 600 + 400;
                    sq._x = Math.random() * w;
                    sq._y = Math.random() * h;
                }
                sq.style.transform = `translate3d(${sq._x}px,${sq._y}px,0) scale(${0.5 + sq._z/1200}) rotateY(${sq._ry}deg) rotateX(${sq._rx}deg)`;
                sq.style.opacity = 0.3 + 0.7 * (sq._z/1000);
            }
            requestAnimationFrame(animateGalaxy);
        }
        animateGalaxy();
        window.addEventListener('resize', () => {
            for (let sq of squares) {
                sq._x = Math.random() * window.innerWidth;
                sq._y = Math.random() * window.innerHeight;
            }
        });
    }
});