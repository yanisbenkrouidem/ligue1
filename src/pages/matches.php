<?php
/**
 * Matches Page
 * Displays all match results grouped by date with ScrollSpy navigation.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

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
        header("Location: /src/pages/matches.php#espace-fan");
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

$targetDateForScroll = null;
$searchMsg = null;

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $searchTerm = trim($_GET['search']);
    $searchTermLower = strtolower($searchTerm);
    
    $delimiters = [' vs ', ' - ', ' contre '];
    $teams = [];
    $foundSplit = false;
    foreach ($delimiters as $delim) {
        if (strpos($searchTermLower, $delim) !== false) {
            $parts = explode($delim, $searchTermLower);
            if (count($parts) >= 2) {
                $teams = [trim($parts[0]), trim($parts[1])];
                $foundSplit = true;
                break;
            }
        }
    }
    
    if (!$foundSplit) {
        $words = explode(' ', $searchTermLower);
        if (count($words) >= 2 && strlen($words[0]) > 2 && strlen($words[count($words)-1]) > 2) {
            $teams = [$words[0], $words[count($words)-1]];
        } else {
            $teams = [$words[0]];
        }
    }

    $abbreviations = [
        'psg' => 'paris',
        'ol' => 'lyon',
        'om' => 'marseille',
        'asse' => 'etienne',
        'losc' => 'lille',
        'srfc' => 'rennes',
        'mhs' => 'montpellier',
        'rcl' => 'lens',
        'asm' => 'monaco',
        'fcn' => 'nantes'
    ];
    
    foreach ($teams as &$t) {
        if (isset($abbreviations[$t])) {
            $t = $abbreviations[$t];
        }
    }
    unset($t);

    if (count($teams) >= 2) {
        $queryMatch = "
            SELECT r.daterenc 
            FROM rencontre r
            JOIN club c1 ON r.idclubdom = c1.idclub
            JOIN club c2 ON r.idclubext = c2.idclub
            WHERE r.daterenc IS NOT NULL
            AND (
                (c1.nomcourt LIKE :t1 AND c2.nomcourt LIKE :t2) OR 
                (c1.nomcourt LIKE :t2 AND c2.nomcourt LIKE :t1) OR
                (c1.nomclub LIKE :t1 AND c2.nomclub LIKE :t2) OR
                (c1.nomclub LIKE :t2 AND c2.nomclub LIKE :t1)
            )
            ORDER BY r.daterenc DESC
            LIMIT 1
        ";
        $stmtSearch = $pdo->prepare($queryMatch);
        $stmtSearch->execute([
            't1' => '%' . $teams[0] . '%',
            't2' => '%' . $teams[1] . '%'
        ]);
        $res = $stmtSearch->fetch();
        if ($res) {
            $targetDateForScroll = $res['daterenc'];
            $searchMsg = "Confrontation trouvée : redirection en cours...";
        } else {
            $searchMsg = "Aucun match trouvé pour '$searchTerm'.";
        }
    } elseif (count($teams) == 1) {
        $queryMatch = "
            SELECT r.daterenc 
            FROM rencontre r
            JOIN club c1 ON r.idclubdom = c1.idclub
            JOIN club c2 ON r.idclubext = c2.idclub
            WHERE r.daterenc IS NOT NULL
            AND (c1.nomcourt LIKE :t1 OR c2.nomcourt LIKE :t1 OR c1.nomclub LIKE :t1 OR c2.nomclub LIKE :t1)
            ORDER BY r.daterenc DESC
            LIMIT 1
        ";
        $stmtSearch = $pdo->prepare($queryMatch);
        $stmtSearch->execute(['t1' => '%' . $teams[0] . '%']);
        $res = $stmtSearch->fetch();
        if ($res) {
            $targetDateForScroll = $res['daterenc'];
            $searchMsg = "Match trouvé : redirection en cours...";
        } else {
            $searchMsg = "Aucun match trouvé pour '$searchTerm'.";
        }
    }
}
function formatFrenchDate($dateString) {
    $days = ['dim.', 'lun.', 'mar.', 'mer.', 'jeu.', 'ven.', 'sam.'];
    $months = ['', 'janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'];
    $timestamp = strtotime($dateString);
    $dayOfWeek = $days[date('w', $timestamp)];
    $day = date('d', $timestamp);
    if (substr($day, 0, 1) === '0') $day = substr($day, 1);
    $month = $months[date('n', $timestamp)];
    return "$dayOfWeek $day $month";
}

function getFullFrenchDate($dateString) {
    $days = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'];
    $months = ['', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
    $timestamp = strtotime($dateString);
    $dayOfWeek = $days[date('w', $timestamp)];
    $day = date('d', $timestamp);
    if (substr($day, 0, 1) === '0') $day = substr($day, 1);
    $month = $months[date('n', $timestamp)];
    $year = date('Y', $timestamp);
    return "$dayOfWeek $day $month $year";
}

// Fetch all matches ordered by date
$queryMatches = "
    SELECT r.*, 
           c1.nomcourt as dom_nom, c1.logo as dom_logo,
           c2.nomcourt as ext_nom, c2.logo as ext_logo
    FROM rencontre r
    JOIN club c1 ON r.idclubdom = c1.idclub
    JOIN club c2 ON r.idclubext = c2.idclub
    WHERE r.daterenc IS NOT NULL
    ORDER BY r.daterenc ASC, r.heurerenc ASC
";
$stmtMatches = $pdo->query($queryMatches);
$allMatches = $stmtMatches->fetchAll(PDO::FETCH_ASSOC);

$matchesByDate = [];
foreach ($allMatches as $m) {
    $date = $m['daterenc'];
    if (!isset($matchesByDate[$date])) {
        $matchesByDate[$date] = [];
    }
    $matchesByDate[$date][] = $m;
}

$dates = array_keys($matchesByDate);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Calendrier des Matchs - Ligue 1 McDonalds</title>
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Ligue 1 McDonalds - Matchs et Résultats">
    <meta property="og:description" content="Retrouvez le calendrier complet et les résultats de tous les matchs de la Ligue 1 McDonalds.">
    <meta property="og:image" content="https://ligue1.benkrouidem.com/assets/images/logos/ligue1.jpg">
    <meta property="og:url" content="https://ligue1.benkrouidem.com/src/pages/matches.php">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="icon" type="image/jpg" href="/assets/images/logos/ligue1.jpg">
    <link rel="stylesheet" href="/assets/css/navbar.css">
    <link rel="stylesheet" href="/assets/css/matches.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .date-section {
            padding-top: 150px;
            margin-top: -120px;
            padding-bottom: 30px;
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <!-- Background decorations -->
    <div class="bg-decorations" style="opacity: 0.15; filter: blur(3px);">
        <div style="background-image: url('/assets/images/backgrounds/walpapper2.jpg'); background-size: cover; background-position: center; position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></div>
    </div>

    <!-- Matches Header (Sticky) -->
    <section class="matches-header">
        <div class="header-top">
            <h1 class="header-title">Calendrier des matchs</h1>
        </div>
        
        <?php if ($searchMsg): ?>
            <div style="background-color: <?php echo $targetDateForScroll ? '#4CAF50' : '#f44336'; ?>; color: white; text-align: center; padding: 10px; font-weight: bold; font-size: 14px;">
                <?php echo htmlspecialchars($searchMsg); ?>
            </div>
        <?php endif; ?>

        <div class="date-slider-container">
            <button class="date-nav-btn" onclick="document.querySelector('.dates-wrapper').scrollBy(-150, 0)">&#10094;</button>
            <div class="dates-wrapper">
                <?php foreach($dates as $index => $date): ?>
                    <a href="#date-<?php echo $date; ?>" class="date-pill <?php echo ($index === 0) ? 'active' : ''; ?>" data-target="date-<?php echo $date; ?>">
                        <?php echo formatFrenchDate($date); ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <button class="date-nav-btn" onclick="document.querySelector('.dates-wrapper').scrollBy(150, 0)">&#10095;</button>
        </div>
    </section>

    <!-- Main Content -->
    <main class="matches-main">
        <?php foreach($matchesByDate as $date => $matches): ?>
            <div id="date-<?php echo $date; ?>" class="date-section">
                <div class="current-date-title">
                    <?php echo getFullFrenchDate($date); ?>
                </div>

                <div class="competition-card">
                    <div class="competition-header">
                        <img class="competition-logo" src="/assets/images/logos/ligue1.jpg" alt="Ligue 1 Logo">
                        <div class="competition-info">
                            <h2>Ligue 1 McDonald's</h2>
                            <?php 
                            $journeeLabel = "Journée du championnat";
                            if (count($matches) > 0 && isset($matches[0]['idjournee'])) {
                                $journeeLabel = "Journée " . $matches[0]['idjournee'];
                            }
                            ?>
                            <p>Saison 2024-2025 - <?php echo $journeeLabel; ?></p>
                        </div>
                    </div>

                    <div class="matches-grid">
                        <?php foreach($matches as $index => $m): ?>
                            <?php $delay = ($index % 10) * 50; ?>
                            <div class="match-card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                                <div class="match-teams">
                                    <div class="team">
                                        <img class="team-logo" src="/assets/images/logos/<?php echo $m['dom_logo']; ?>" alt="Logo">
                                        <span class="team-name"><?php echo htmlspecialchars($m['dom_nom']); ?></span>
                                    </div>
                                    <div class="team">
                                        <img class="team-logo" src="/assets/images/logos/<?php echo $m['ext_logo']; ?>" alt="Logo">
                                        <span class="team-name"><?php echo htmlspecialchars($m['ext_nom']); ?></span>
                                    </div>
                                </div>

                                <div class="match-status">
                                    <?php 
                                    if ($m['scoreclubdom'] !== null && $m['scoreclubext'] !== null && $m['scoreclubdom'] !== '' && $m['scoreclubext'] !== '') {
                                        $scoreDisplay = $m['scoreclubdom'] . ' - ' . $m['scoreclubext'];
                                        echo "<span class='match-time'>$scoreDisplay</span>";
                                        echo "<button class='match-details-btn'>Voir détails</button>";
                                    } else {
                                        $time = substr($m['heurerenc'], 0, 5);
                                        echo "<span class='match-time'>$time</span>";
                                        echo "<button class='match-action'>Composition</button>";
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </main>

    <!-- Fan Zone: Comments -->
    <section id="espace-fan" class="fan-zone">
        <div class="fan-zone-sidebar">
            <h2>Espace Fan</h2>
            <p>Les messages s'effacent automatiquement après 15 minutes.</p>

            <div class="comment-form-container">
                <?php if ($currentUser): ?>
                    <form method="POST" action="/src/pages/matches.php#espace-fan">
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
            <div class="comments-list" id="commentsCarousel2">
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
            <button class="carousel-nav-btn" onclick="document.getElementById('commentsCarousel2').scrollBy({left: 300, behavior: 'smooth'})">❯</button>
        </div>
    </section>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <!-- JS Logic -->
    <script>
        const sections = document.querySelectorAll('.date-section');
        const navPills = document.querySelectorAll('.date-pill');
        const datesWrapper = document.querySelector('.dates-wrapper');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    navPills.forEach(pill => pill.classList.remove('active'));
                    const activePill = document.querySelector(`.date-pill[data-target="${id}"]`);
                    if (activePill) {
                        activePill.classList.add('active');
                        const scrollLeft = activePill.offsetLeft - (datesWrapper.offsetWidth / 2) + (activePill.offsetWidth / 2);
                        datesWrapper.scrollTo({ left: scrollLeft, behavior: 'smooth' });
                    }
                }
            });
        }, { root: null, rootMargin: '-160px 0px -50% 0px', threshold: 0 });

        sections.forEach(sec => observer.observe(sec));

        navPills.forEach(pill => {
            pill.addEventListener('click', function(e) {
                e.preventDefault();
                const targetSection = document.getElementById(this.getAttribute('data-target'));
                if (targetSection && window.lenis) { window.lenis.scrollTo(targetSection, { offset: -150 }); }
                else if (targetSection) { targetSection.scrollIntoView({behavior: "smooth"}); }
            });
        });
    </script>
    <script src="https://unpkg.com/@studio-freight/lenis@1.0.42/dist/lenis.min.js"></script>
    <script src="/assets/js/navbar.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="/assets/js/animations.js"></script>
    <script>
        AOS.init({
            once: true,
            offset: 50,
        });

        // Search Auto-Scroll Logic
        document.addEventListener("DOMContentLoaded", function() {
            const targetDate = "<?php echo $targetDateForScroll ? htmlspecialchars($targetDateForScroll) : ''; ?>";
            
            if (targetDate) {
                const targetElement = document.getElementById("date-" + targetDate);
                const targetPill = document.querySelector(".date-pill[data-target='date-" + targetDate + "']");
                
                if (targetElement) {
                    setTimeout(() => {
                        // Scroll main window to the date section smoothly
                        const headerOffset = 180; // height of sticky header
                        const elementPosition = targetElement.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                        
                        window.scrollTo({
                            top: offsetPosition,
                            behavior: "smooth"
                        });
                        
                        // Scroll pills wrapper to show the active pill
                        if (targetPill) {
                            document.querySelectorAll('.date-pill').forEach(p => p.classList.remove('active'));
                            targetPill.classList.add('active');
                            targetPill.scrollIntoView({ behavior: "smooth", block: "nearest", inline: "center" });
                            
                            // Highlight the section briefly
                            targetElement.style.transition = "background-color 1s";
                            targetElement.style.backgroundColor = "rgba(219, 255, 0, 0.1)"; // Ligue 1 yellow tint
                            setTimeout(() => {
                                targetElement.style.backgroundColor = "transparent";
                            }, 2000);
                        }
                    }, 500); // slight delay to allow layout
                }
            }
        });
    </script>
</body>
</html>
