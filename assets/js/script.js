// assets/js/script.js

document.addEventListener('DOMContentLoaded', () => {
    // Mobile Menu Toggle
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');

    if (hamburger) {
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('nav-active');
            hamburger.classList.toggle('toggle');
            
            if (navLinks.classList.contains('nav-active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = 'auto';
            }
        });
    }

    // Modal Logic
    const loginModal = document.getElementById('loginModal');
    const openLoginBtn = document.getElementById('openLoginModal');
    const closeBtns = document.querySelectorAll('.close-modal');
    
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const showRegisterLink = document.getElementById('showRegister');
    const showLoginLink = document.getElementById('showLogin');

    // Open Modal
    if (openLoginBtn) {
        openLoginBtn.addEventListener('click', () => {
            loginModal.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        });
    }

    // Close Modal
    closeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            if (loginModal) loginModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
    });

    // Click outside to close
    window.addEventListener('click', (e) => {
        if (e.target === loginModal) {
            loginModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });

    // Toggle Forms
    if (showRegisterLink) {
        showRegisterLink.addEventListener('click', (e) => {
            e.preventDefault();
            loginForm.classList.remove('active');
            registerForm.classList.add('active');
        });
    }

    if (showLoginLink) {
        showLoginLink.addEventListener('click', (e) => {
            e.preventDefault();
            registerForm.classList.remove('active');
            loginForm.classList.add('active');
        });
    }

    // Loading Indicator for Forms
    const forms = document.querySelectorAll('form');
    if(forms.length > 0) {
        // Create loading overlay element
        const overlay = document.createElement('div');
        overlay.id = 'loadingOverlay';
        overlay.innerHTML = '<div class="spinner"></div><p style="color:white; margin-top:15px; font-weight:500;">Memproses...</p>';
        
        // Add basic styles directly
        Object.assign(overlay.style, {
            display: 'none',
            position: 'fixed',
            top: '0',
            left: '0',
            width: '100%',
            height: '100%',
            backgroundColor: 'rgba(0,0,0,0.7)',
            zIndex: '9999',
            flexDirection: 'column',
            justifyContent: 'center',
            alignItems: 'center',
            backdropFilter: 'blur(3px)'
        });

        // Add spinner CSS
        const style = document.createElement('style');
        style.innerHTML = `
            .spinner {
                width: 50px;
                height: 50px;
                border: 5px solid rgba(255,255,255,0.3);
                border-radius: 50%;
                border-top-color: #a34e00;
                animation: spin 1s ease-in-out infinite;
            }
            @keyframes spin {
                to { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
        document.body.appendChild(overlay);

        forms.forEach(form => {
            form.addEventListener('submit', function() {
                // Ignore empty required fields logic handled by browser
                if (this.checkValidity()) {
                    overlay.style.display = 'flex';
                }
            });
        });
    }
});
