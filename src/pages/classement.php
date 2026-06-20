<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';

// Fetch all clubs
$query = "
    SELECT 
        MAX(c.idclub) as idclub,
        c.nomcourt,
        MAX(c.logo) as logo,
        MAX(c.nbpoints) as nbpoints,
        MAX(c.butsmarques) as butsmarques,
        MAX(c.butsencaisses) as butsencaisses,
        (MAX(c.butsmarques) - MAX(c.butsencaisses)) AS diffbuts
    FROM club c
    GROUP BY c.nomcourt
    ORDER BY nbpoints DESC, diffbuts DESC
";
$stmt = $pdo->query($query);
$clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// For each club, calculate J, G, N, P, Form
foreach ($clubs as &$club) {
    $id = $club['idclub'];
    // We assume a match is played if its date is in the past, or if we just want to count all matches with a score.
    // Given the SQL dump, we will just take all matches where score is not null.
    $q_match = "SELECT * FROM rencontre WHERE (idclubdom = :id OR idclubext = :id) AND scoreclubdom IS NOT NULL ORDER BY daterenc DESC";
    $s_match = $pdo->prepare($q_match);
    $s_match->execute(['id' => $id]);
    $matches = $s_match->fetchAll(PDO::FETCH_ASSOC);

    $j = 0; $g = 0; $n = 0; $p = 0;
    $forme = [];

    foreach($matches as $m) {
        $j++;
        $isHome = ($m['idclubdom'] == $id);
        $scoreFor = $isHome ? $m['scoreclubdom'] : $m['scoreclubext'];
        $scoreAgainst = $isHome ? $m['scoreclubext'] : $m['scoreclubdom'];

        $result = '';
        if ($scoreFor > $scoreAgainst) {
            $g++;
            $result = 'W';
        } elseif ($scoreFor == $scoreAgainst) {
            $n++;
            $result = 'D';
        } else {
            $p++;
            $result = 'L';
        }
        
        if (count($forme) < 5) {
            $forme[] = $result; 
        }
    }
    
    $club['J'] = $j;
    $club['G'] = $g;
    $club['N'] = $n;
    $club['P'] = $p;
    $club['forme'] = array_reverse($forme); // chronologique: du plus ancien au plus rÃ©cent parmi les 5 derniers
}

// Mock Data for Top Scorers
$top_buteurs = [
    ['nom' => 'K. Mbappé', 'club' => 'Paris', 'stat' => 27, 'logo' => 'paris.png'],
    ['nom' => 'J. David', 'club' => 'Lille', 'stat' => 19, 'logo' => 'lille.png'],
    ['nom' => 'A. Lacazette', 'club' => 'Lyon', 'stat' => 18, 'logo' => 'lyon.png'],
    ['nom' => 'P. Aubameyang', 'club' => 'Marseille', 'stat' => 17, 'logo' => 'marseille.png'],
    ['nom' => 'W. Ben Yedder', 'club' => 'Monaco', 'stat' => 16, 'logo' => 'monaco.png']
];

// Mock Data for Top Assists
$top_passeurs = [
    ['nom' => 'O. Dembélé', 'club' => 'Paris', 'stat' => 8, 'logo' => 'paris.png'],
    ['nom' => 'R. Del Castillo', 'club' => 'Brest', 'stat' => 8, 'logo' => 'brest.png'],
    ['nom' => 'T. Savanier', 'club' => 'Montpellier', 'stat' => 7, 'logo' => 'montpellier.png'],
    ['nom' => 'A. Gomes', 'club' => 'Lille', 'stat' => 7, 'logo' => 'lille.png'],
    ['nom' => 'F. Sotoca', 'club' => 'Lens', 'stat' => 6, 'logo' => 'lens.png']
];

function getFormIcon($result) {
    if ($result === 'W') return '<span class="forme-badge forme-w">V</span>';
    if ($result === 'D') return '<span class="forme-badge forme-d">-</span>';
    return '<span class="forme-badge forme-l">D</span>';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Classement Ligue 1</title>
    <link rel="icon" type="image/jpg" href="/assets/images/logos/ligue1.jpg">
    <link rel="stylesheet" href="/assets/css/navbar.css">
    <link rel="stylesheet" href="/assets/css/matches.css">
    <link rel="stylesheet" href="/assets/css/classement.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>



    <div class="classement-container" style="margin-top: 20px;">
        
        <!-- Table Section -->
        <div class="table-section">

            <table class="classement-table">
                <thead>
                    <tr>
                        <th class="col-pos">#</th>
                        <th class="col-club">Club</th>
                        <th class="col-pts">Pts</th>
                        <th class="hide-mobile">J.</th>
                        <th class="hide-mobile">G-N-P</th>
                        <th class="hide-mobile">But +</th>
                        <th class="hide-mobile">But -</th>
                        <th>Diff</th>
                        <th class="hide-mobile">Forme</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $position = 1;
                    foreach ($clubs as $club) {
                        $zoneClass = '';
                        if ($position <= 3) $zoneClass = 'zone-ucl';
                        elseif ($position == 4) $zoneClass = 'zone-uel';
                        elseif ($position == 5) $zoneClass = 'zone-uecl';
                        elseif ($position == 16) $zoneClass = 'zone-playoff';
                        elseif ($position >= 17) $zoneClass = 'zone-rel';

                        $gnp = "{$club['G']}-{$club['N']}-{$club['P']}";
                        
                        // Add slight delay for each row
                        $delay = ($position % 10) * 50; 
                        
                        echo "<tr class='$zoneClass' data-aos='fade-up' data-aos-delay='$delay'>";
                        echo "<td class='col-pos'>$position</td>";
                        echo "<td class='col-club'>
                                <img class='club-logo' src='/assets/images/logos/{$club['logo']}' alt='Logo'>
                                <a href='club.php?idclub={$club['idclub']}' style='color:inherit; text-decoration:none;'>{$club['nomcourt']}</a>
                              </td>";
                        echo "<td class='col-pts'>{$club['nbpoints']}</td>";
                        echo "<td class='hide-mobile'>{$club['J']}</td>";
                        echo "<td class='hide-mobile'>{$gnp}</td>";
                        echo "<td class='hide-mobile'>{$club['butsmarques']}</td>";
                        echo "<td class='hide-mobile'>{$club['butsencaisses']}</td>";
                        echo "<td>{$club['diffbuts']}</td>";
                        
                        echo "<td class='col-forme hide-mobile'>";
                        foreach($club['forme'] as $f) {
                            echo getFormIcon($f);
                        }
                        echo "</td>";
                        echo "</tr>";
                        $position++;
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Sidebar Stats Section -->
        <div class="stats-section">
            <div class="stat-card" data-aos="fade-left" data-aos-delay="200">
                <h3>Meilleurs Buteurs</h3>
                <ul class="stat-list">
                    <?php foreach($top_buteurs as $buteur): ?>
                    <li>
                        <div class="player-info">
                            <div class="player-avatar">
                                <img src="/assets/images/logos/<?php echo $buteur['logo']; ?>" alt="Logo">
                            </div>
                            <div class="player-details">
                                <span class="player-name"><?php echo $buteur['nom']; ?></span>
                                <span class="player-club"><?php echo $buteur['club']; ?></span>
                            </div>
                        </div>
                        <div class="player-stat"><?php echo $buteur['stat']; ?></div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="stat-card" data-aos="fade-left" data-aos-delay="400">
                <h3>Meilleurs Passeurs</h3>
                <ul class="stat-list">
                    <?php foreach($top_passeurs as $passeur): ?>
                    <li>
                        <div class="player-info">
                            <div class="player-avatar">
                                <img src="/assets/images/logos/<?php echo $passeur['logo']; ?>" alt="Logo">
                            </div>
                            <div class="player-details">
                                <span class="player-name"><?php echo $passeur['nom']; ?></span>
                                <span class="player-club"><?php echo $passeur['club']; ?></span>
                            </div>
                        </div>
                        <div class="player-stat"><?php echo $passeur['stat']; ?></div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <div style="width: 100%; margin-top: 40px;" data-aos="fade-up">
        <img src="/assets/images/backgrounds/walpapper3.jpg" style="width: 100%; display: block; height: auto; max-height: 500px; object-fit: cover; box-shadow: 0 4px 15px rgba(0,0,0,0.2);" alt="Ligue 1 Banner">
    </div>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <!-- Animations JS -->
    <script src="https://unpkg.com/@studio-freight/lenis@1.0.42/dist/lenis.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="/assets/js/animations.js"></script>
</body>
</html>

