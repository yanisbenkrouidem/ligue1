document.addEventListener("DOMContentLoaded", function() {
    const navbar = document.getElementById("l1-navbar");
    
    if (navbar) {
        window.addEventListener("scroll", function() {
            if (window.scrollY > 50) {
                navbar.classList.add("scrolled");
            } else {
                navbar.classList.remove("scrolled");
            }
        });
    }

    // Function to show custom alert
    function showCustomAlert() {
        let alertOverlay = document.getElementById('customAlertOverlay');
        if (!alertOverlay) {
            // Create overlay
            alertOverlay = document.createElement('div');
            alertOverlay.id = 'customAlertOverlay';
            alertOverlay.className = 'custom-alert-overlay';
            
            // Create box
            const alertBox = document.createElement('div');
            alertBox.className = 'custom-alert-box';
            
            // Icon
            const icon = document.createElement('div');
            icon.className = 'custom-alert-icon';
            icon.innerHTML = '<svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';
            
            // Title
            const title = document.createElement('h3');
            title.innerHTML = 'Oups ! Vous êtes piégé...';
            
            // Text
            const text = document.createElement('p');
            text.innerHTML = 'Il s\'agit d\'un projet de démonstration, pas d\'un site officiel. Cette fonctionnalité n\'est donc pas disponible.<br><br>Bien essayé !';
            
            // Button
            const btn = document.createElement('button');
            btn.className = 'custom-alert-btn';
            btn.innerHTML = 'J\'ai compris';
            btn.onclick = function() {
                alertOverlay.classList.remove('show');
            };
            
            alertBox.appendChild(icon);
            alertBox.appendChild(title);
            alertBox.appendChild(text);
            alertBox.appendChild(btn);
            alertOverlay.appendChild(alertBox);
            document.body.appendChild(alertOverlay);
        }
        
        // Small delay to allow CSS transition if just created
        setTimeout(() => {
            alertOverlay.classList.add('show');
        }, 10);
    }

    // Handle "Bientôt disponible" alerts for dead ends
    const deadEndLinks = document.querySelectorAll('a[href="#"]');
    deadEndLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            showCustomAlert();
        });
    });
    
    const deadEndButtons = document.querySelectorAll('.btn-black, .btn-outline-black, .btn-outline');
    deadEndButtons.forEach(btn => {
        // Exclude submit buttons or explicitly linked buttons
        const hasValidHref = btn.tagName.toLowerCase() === 'a' && btn.hasAttribute('href') && btn.getAttribute('href') !== '#';
        if (btn.type !== 'submit' && !btn.id && !btn.hasAttribute('onclick') && !hasValidHref) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                showCustomAlert();
            });
        }
    });

    // Handle matches pills
    const matchPills = document.querySelectorAll('.date-pill');
    matchPills.forEach(pill => {
        pill.addEventListener('click', function() {
            // Remove active class from all
            matchPills.forEach(p => p.classList.remove('active'));
            // Add active class to clicked
            this.classList.add('active');
        });
    });
});
