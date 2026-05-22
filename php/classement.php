<?php
// Connexion à la base de données
$host = 'sql303.infinityfree.com';
$dbname = 'bdfoot2benkrouidembelkhiri';
$username = 'if0_38934862';
$password = '8uU2PeGqEEa';

try {
    $bdd = new PDO("mysql:host=sql303.infinityfree.com;dbname=if0_38934862_bdfoot2benkrouidembelkhiri;charset=utf8", "if0_38934862", "8uU2PeGqEEa");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ligue 1 McDonalds</title>
    <style type="text/css">
        * { padding: 0; margin: 0; box-sizing: border-box; }
        header {
            width: 100%;
            height: 100vh;
            background:linear-gradient(rgba(0,0,0,0.8),rgba(0,0,0,0.2)),url('assets/images/stade.jpg');
            background-size: cover;
            background-position: center;
            font-family: sans-serif;
        }
        nav{
            width: 100%;
            height: 100px;
            color: white;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
        .logo {
    text-decoration: none;
    font-size: 2.5em;
    font-weight: bold;
    letter-spacing: 3px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #FFD700; /* or another bright color like #00FFFF or #FF4500 */
    text-shadow: 2px 2px 5px rgba(0,0,0,0.7);
    transition: transform 0.3s ease, color 0.3s ease;
}

.logo:hover {
    transform: scale(1.05);
    color: #ffffff;
}
        .menu a{
            text-decoration: none;
            color: white;
            padding: 10px 20px;
            font-size: 20px;
            position: relative;
        }
        .menu a:before{
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            border-bottom: 2px solid aliceblue;
            transition: 0.4s linear;
        }
        .menu a:hover:before{
            width: 90%;
        }
        .register a:hover{
            background: transparent;
            border:1px solid antiquewhite;
        }
        .register a{
            text-decoration: none;
            color: white;
            padding: 10px 20px;
            font-size: 20px;
            background: black;
            border-radius: 8px;
            transition: 0.4s;
        }
        .h-txt{
            max-width: 650px;
            position: absolute;
            top:50%;
            left:50%;
            transform: translate(-50%,-50%);
            text-align: center;
            color: white;
        }
        .h-txt span{
            letter-spacing: 5px;
        }
        .h-txt h1 {
            font-size:3.5em ;
        }
        .h-txt a{
            font-family: sans-serif;
            text-decoration: none;
            background:antiquewhite;
            color: black;
            padding: 10px 20px;
            letter-spacing: 5px;
            transition: 0.4s ;
        }
        .h-txt a:hover{
              background: transparent;
            border:1px solid aliceblue;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                Ligue 1
            </div>
            <div class="menu">
                <a href="#">Acceuil</a>
                <a href="classement.php">Classement</a>
                <a href="#">Actus</a>
                <a href="resultatjournee.php">Journée</a>
                <a href="#">Calendrier</a>
            </div>
            <div class="register">
                <a href="#">Connexion</a>
            </div>
        </nav>
        <section class="h-txt">
            <span>Ligue 1</span>
            <h1>Classement de la saison 2024-2025</h1>
            <br>
            <a href="saison.php">Voir le Classement</a>
        </section>
    </header>
</body>
</html>
