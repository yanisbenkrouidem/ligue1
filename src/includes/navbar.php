<?php
/**
 * Navbar Component
 * Included across all pages for consistent navigation.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isConnected = isset($_SESSION['user']);
$username = $isConnected ? htmlspecialchars($_SESSION['user']) : '';
?>
<header id="l1-navbar">
    <div class="l1-nav-bottom">
        <div class="l1-nav-left-group">
            <a href="/index.php" class="l1-bottom-logo">
                <img src="/assets/images/logos/ligue1.jpg" alt="Ligue 1 Logo" style="height: 45px; border-radius: 4px; margin-right: 40px;">
            </a>
            <div class="l1-nav-bottom-links">
                <a href="/index.php">ACCUEIL</a>
                <a href="/src/pages/matches.php">MATCHS</a>
                <a href="/src/pages/classement.php">CLASSEMENTS</a>
            </div>
        </div>
        
        <div class="l1-nav-right-group">
            <div class="notifications-dropdown">
                <button class="l1-notif-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                    <span class="notif-badge">3</span>
                </button>
                <div class="notif-menu">
                    <div class="notif-header">Notifications (3)</div>
                    <div class="notif-item">
                        <strong>Nouveau match !</strong><br>Le Classique PSG vs OM approche !
                    </div>
                    <div class="notif-item">
                        <strong>Ligue 1 Kids</strong><br>De nouvelles émissions sont disponibles.
                    </div>
                    <div class="notif-item">
                        <strong>Billetterie</strong><br>Ouverture des ventes pour la 12ème journée.
                    </div>
                </div>
            </div>
            
            <?php if ($isConnected): ?>
                <a href="#" class="btn-abonner">S'ABONNER</a>
                <a href="/src/pages/auth/dashboard.php" class="user-avatar-btn">
                    <img src="/assets/images/avatar.png" alt="Avatar">
                </a>
            <?php else: ?>
                <a href="/src/pages/auth/login.php" class="btn-abonner">S'INSCRIRE</a>
                <a href="/src/pages/auth/login.php" class="btn-connecter">SE CONNECTER</a>
            <?php endif; ?>
        </div>
    </div>
    <script src="/assets/js/navbar.js" defer></script>
</header>
