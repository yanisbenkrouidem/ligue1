<?php
// Connexion à la base de données
$host = 'sql303.infinityfree.com';
$dbname = 'bdfoot2benkrouidembelkhiri';
$username = 'if0_38934862';
$password = '8uU2PeGqEEa';

try {
    $bdd = new PDO("mysql:host=sql303.infinityfree.com;dbname=if0_38934862_bdfoot2benkrouidembelkhiri;charset=utf8", "if0_38934862", "8uU2PeGqEEa");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "
        SELECT 
            c.idclub,
            c.nomcourt,
            c.logo,
            c.nbpoints,
            c.butsmarques,
            c.butsencaisses,
            (c.butsmarques - c.butsencaisses) AS diffbuts
        FROM club c
        ORDER BY c.nbpoints DESC, diffbuts DESC
    ";
    $stmt = $pdo->query($query);
    $clubs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Classement Ligue 1</title>
    <style>
        body {
            margin: 0;
            padding: 40px 20px;
            background: #0e0e0e;
            font-family: 'Segoe UI', sans-serif;
            color: #fff;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
            animation: fadeIn 1s ease-out;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background: rgba(255,255,255,0.05);
            border-radius: 12px;
            overflow: hidden;
            backdrop-filter: blur(5px);
        }

        th, td {
            padding: 14px 12px;
            text-align: center;
        }

        th {
            background-color: rgba(255,255,255,0.1);
            text-transform: uppercase;
            font-size: 0.9em;
            letter-spacing: 1px;
        }

        tr {
            transition: background 0.3s ease;
        }

        tr:hover {
            background: rgba(255,255,255,0.08);
        }

        img.logo {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            vertical-align: middle;
        }

        td.club-name {
            text-align: left;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Bandeaux */
        .champions {
            border-left: 6px solid #007bff;
        }

        .barrage {
            border-left: 6px solid orange;
        }

        .relegation {
            border-left: 6px solid #dc3545;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <h1>Classement Ligue 1 2024-2025</h1>
    <table>
        <tr>
            <th>Pos</th>
            <th>Club</th>
            <th>Pts</th>
            <th>BM</th>
            <th>BE</th>
            <th>Diff</th>
        </tr>
        <?php
        $position = 1;
        foreach ($clubs as $club) {
            $diff = $club['butsmarques'] - $club['butsencaisses'];

            // Classe CSS selon la position
            $class = '';
            if ($position <= 3) $class = 'champions';
            elseif ($position == 16) $class = 'barrage';
            elseif ($position >= count($clubs) - 1) $class = 'relegation';

            echo "<tr class='$class'>
                <td>$position</td>
                <td class='club-name'>
                    <a href='club.php?idclub={$club['idclub']}' style='text-decoration:none; color:inherit; display:flex; align-items:center; gap:10px;'>
                        <img class='logo' src='images/{$club['logo']}' alt='Logo'>
                        {$club['nomcourt']}
                    </a>
                </td>
                <td>{$club['nbpoints']}</td>
                <td>{$club['butsmarques']}</td>
                <td>{$club['butsencaisses']}</td>
                <td>$diff</td>
            </tr>";
            $position++;
        }
        ?>
    </table>
</body>
</html>
