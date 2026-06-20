<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/src/config/database.php';
require_once __DIR__ . '/src/config/auth.php';

// Restore auth from cookie (needed for Vercel serverless)
$currentUser = auth_check();

// Handle new comment
if (isset($_POST['btnSubmitComment']) && $currentUser) {
    $contenu = htmlspecialchars($_POST['comment_text']);
    if (!empty(trim($contenu))) {
        // Find idutil
        $stmtUser = $pdo->prepare("SELECT idutil FROM utilisateur WHERE pseudoutil = :pseudo");
        $stmtUser->execute(['pseudo' => $currentUser]);
        $user = $stmtUser->fetch();
        if ($user) {
            $stmt = $pdo->prepare("INSERT INTO commentaire (idutil, libelle, datecom, idjournee, numrenc) VALUES (:idutil, :contenu, NOW(), 1, 1)");
            $stmt->bindParam(':idutil', $user['idutil']);
            $stmt->bindParam(':contenu', $contenu);
            $stmt->execute();
        }
        header("Location: /index.php#espace-fan");
        exit();
    }
}

// Fetch all comments
$stmtComments = $pdo->query("
    SELECT c.libelle as contenu, c.datecom as date_creation, u.pseudoutil 
    FROM commentaire c 
    JOIN utilisateur u ON c.idutil = u.idutil 
    WHERE c.datecom >= DATE_SUB(NOW(), INTERVAL 15 MINUTE)
    ORDER BY c.datecom DESC, c.idcom DESC
    LIMIT 50
");
$comments = $stmtComments->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Ligue 1 McDonalds</title>
    <link rel="icon" type="image/jpg" href="/assets/images/logos/ligue1.jpg">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/navbar.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>

    <?php include __DIR__ . '/src/includes/navbar.php'; ?>

    <!-- Hero Banner -->
    <section class="hero-kids" data-aos="fade-in" data-aos-duration="1200">
        <div class="hero-kids-content">
            <img src="/assets/images/ligue1-kids-demande.webp" alt="A la demande" class="kids-img-demande" data-aos="fade-up" data-aos-delay="200">
            <img src="/assets/images/ligue1-kids-logo.webp" alt="Ligue 1 Kids" class="kids-img-logo" data-aos="fade-up" data-aos-delay="400">
            <p class="kids-text" data-aos="fade-up" data-aos-delay="600">Hey les jeunes fans ! On a des émissions rien que pour vous !</p>
            <a href="#" class="btn-kids" data-aos="fade-up" data-aos-delay="800">REGARDEZ MAINTENANT</a>
        </div>
    </section>


    <section class="area1" data-aos="fade-up">
        <div class="area1--content">
            <div class="bar"></div>
            <h2 data-aos="fade-right">En ce moment chez la Ligue 1<br/> McDonalds</h2>
            <div class="area1--quote" data-aos="fade-right" data-aos-delay="100"><em>"Diffuseurs Officiels" - Bein Sports, DAZN</em></div>
            <div class="area1--text" data-aos="fade-right" data-aos-delay="200">Les grands joueurs se réunissent pour sacrer le meilleur club de France.</div>
            <a href="/src/pages/matches.php" class="button" data-aos="zoom-in" data-aos-delay="300">Voir plus</a>
        </div>
        <div class="area1--img1" data-aos="fade-left" data-aos-delay="200"></div>
        <div class="area1--img2" data-aos="fade-left" data-aos-delay="400"></div>
    </section>

    <section class="area2" data-aos="fade-up">
        <div class="area2--img1" data-aos="fade-right" data-aos-delay="200"></div>
        <div class="area2--content">
            <div class="bar"></div>
            <h2 data-aos="fade-left">Classement en direct.</h2>
            <div class="area2--text" data-aos="fade-left" data-aos-delay="100">Retrouvez les meilleures affiches de la Ligue 1 McDonald's sous forme d'extraits en quasi-direct. Quelques secondes après un but, un arrêt spectaculaire ou une occasion en or, une notification vidéo vous alerte pour revoir l'action !</div>
            <a href="/src/pages/classement.php" class="button" data-aos="zoom-in" data-aos-delay="200">Voir plus</a>
        </div>
    </section>

    <section class="area3" data-aos="fade-up">
        <div class="area3--content">
            <h2 data-aos="fade-right">Découvrez le calendrier<br/>des matchs</h2>
            <div class="area3--text" data-aos="fade-right" data-aos-delay="100">Consultez les dates et horaires des prochaines rencontres de la Ligue 1 McDonald's, et ne manquez aucune affiche de votre équipe favorite.</div>
            <a href="/src/pages/matches.php" class="button" data-aos="zoom-in" data-aos-delay="200">Voir plus</a>
        </div>
        <div class="area3--img1" data-aos="fade-left" data-aos-delay="200"></div>
    </section>

    <section class="area5"></section>

    <!-- Fan Zone: Comments -->
    <section id="espace-fan" class="fan-zone" data-aos="fade-up">
        <div class="fan-zone-sidebar">
            <h2>Espace Fan</h2>
            <p>Les messages s'effacent automatiquement après 15 minutes.</p>

            <div class="comment-form-container">
                <?php if ($currentUser): ?>
                    <form method="POST" action="/index.php#espace-fan">
                        <textarea name="comment_text" class="comment-input" rows="4" placeholder="Partagez votre passion, <?php echo htmlspecialchars($currentUser); ?>..." required></textarea>
                        <button type="submit" name="btnSubmitComment" class="btn-black">Publier</button>
                    </form>
                <?php else: ?>
                    <div class="login-prompt">
                        <p>Connectez-vous pour participer à la discussion !</p>
                        <a href="/src/pages/auth/login.php" class="btn-black">Me connecter</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="fan-zone-carousel-wrapper">
            <div class="comments-list" id="commentsCarousel1">
                <?php if (count($comments) === 0): ?>
                    <p class="no-comments">Soyez le premier à commenter !</p>
                <?php else: ?>
                    <?php foreach ($comments as $c): ?>
                        <div class="comment-card">
                            <div class="comment-header">
                                <span class="comment-author"><?php echo htmlspecialchars($c['pseudoutil']); ?></span>
                                <span class="comment-date"><?php echo date('d/m/Y H:i', strtotime($c['date_creation'])); ?></span>
                            </div>
                            <div class="comment-body">
                                <?php echo nl2br(htmlspecialchars($c['contenu'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <button class="carousel-nav-btn" onclick="document.getElementById('commentsCarousel1').scrollBy({left: 300, behavior: 'smooth'})">❯</button>
        </div>
    </section>

    <?php include __DIR__ . '/src/includes/footer.php'; ?>

    <!-- Animations JS -->
    <script src="https://unpkg.com/@studio-freight/lenis@1.0.42/dist/lenis.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="/assets/js/animations.js"></script>

    <!-- Welcome Popup -->
    <div id="welcomePopup" class="welcome-popup-overlay">
        <div class="welcome-popup-content">
            <h2>Bienvenue sur ma plateforme !</h2>
            <p><strong>Je suis Yanis Benkrouidem.</strong> Vous explorez ici une version de démonstration d'un projet développé dans le cadre d'un projet. J'espère que vous apprécierez votre visite et que vous trouverez l'expérience claire et agréable. Merci pour votre intérêt !</p>
            <div class="welcome-popup-buttons">
                <div class="welcome-popup-buttons-left">
                    <a href="https://yanisbenkrouidem.com/" target="_blank" class="btn-outline">PORTFOLIO</a>
                    <a href="https://www.linkedin.com/in/yanisbenkrouidem/" target="_blank" class="btn-outline">LINKEDIN</a>
                </div>
                <button id="closePopupBtn" class="btn-filled">ACCÉDER AU PROJET</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const popup = document.getElementById('welcomePopup');
            const closeBtn = document.getElementById('closePopupBtn');
            
            if (!localStorage.getItem('welcomePopupSeen')) {
                setTimeout(() => { popup.classList.add('show'); }, 100);
            }
            
            closeBtn.addEventListener('click', () => { 
                popup.classList.remove('show'); 
                localStorage.setItem('welcomePopupSeen', 'true');
            });
        });
    </script>
</body>
</html>
