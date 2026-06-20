<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/auth.php';

$connexionMessage = "";

// Traitement de la connexion
if (isset($_POST["btnconnect"])) {
    $pseudo = htmlspecialchars($_POST["username"]);
    $mdp = $_POST["password"];

    $req = $bdd->prepare("SELECT mdputil FROM utilisateur WHERE pseudoutil = :pseudo");
    $req->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $req->execute();
    $uneligne = $req->fetch();

    if ($uneligne) {
        if ($mdp == $uneligne['mdputil']) {
            auth_login($pseudo);
            header("Location: /src/pages/auth/dashboard.php");
            exit();
        } else {
            $connexionMessage = "<p style='color: red; text-align: center; font-weight: bold;'>Mot de passe incorrect.</p>";
        }
    } else {
        $connexionMessage = "<p style='color: red; text-align: center; font-weight: bold;'>Pseudo introuvable.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Ligue 1</title>
    <link rel="icon" type="image/jpg" href="/assets/images/logos/ligue1.jpg">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/auth-new.css">
</head>
<body class="auth-page">

    <header class="auth-header">
        <a href="/index.php">
            <img src="/assets/images/logos/ligue1.jpg" alt="Logo Ligue 1" style="border-radius: 4px;">
        </a>
    </header>

    <div class="auth-container">
        <div class="auth-title-top">Connectez-vous pour accéder à votre compte</div>
        
        <div class="auth-card">
            <?php echo $connexionMessage; ?>

            <form method="POST">
                <div class="input-group">
                    <input type="text" name="username" class="auth-input" placeholder="Adresse e-mail ou Pseudo" required>
                </div>
                
                <div class="input-group">
                    <input type="password" name="password" class="auth-input" placeholder="Mot de passe" required>
                    <span class="input-icon">👁️</span>
                </div>

                <div class="auth-links" style="text-align: center; margin-bottom: 20px;">
                    <a href="#" style="font-size: 12px; color: #666; font-weight: normal;">Mot de passe oublié ?</a>
                </div>

                <button type="submit" name="btnconnect" class="auth-btn">Connexion</button>

                <div class="auth-links muted" style="margin-bottom: 30px;">
                    Vous n'avez pas encore de compte ? <br><br>
                    <a href="/src/pages/auth/register.php" style="font-weight: bold; text-decoration: underline;">S'inscrire</a>
                </div>

                <hr class="divider">

                <!-- Third party logins -->
                <a href="/src/pages/auth/google_login.php" class="auth-btn-outline" style="text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 10px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25C22.56 11.47 22.49 10.72 22.36 10H12V14.26H17.92C17.66 15.63 16.88 16.81 15.69 17.61V20.34H19.26C21.36 18.42 22.56 15.6 22.56 12.25Z" fill="#4285F4"/>
                        <path d="M12 23C14.97 23 17.46 22.02 19.26 20.34L15.69 17.61C14.71 18.27 13.46 18.66 12 18.66C9.17 18.66 6.77 16.75 5.88 14.16H2.21V17.01C4.01 20.59 7.71 23 12 23Z" fill="#34A853"/>
                        <path d="M5.88 14.16C5.65 13.48 5.52 12.76 5.52 12C5.52 11.24 5.65 10.52 5.88 9.84V6.99H2.21C1.46 8.47 1 10.18 1 12C1 13.82 1.46 15.53 2.21 17.01L5.88 14.16Z" fill="#FBBC05"/>
                        <path d="M12 5.34C13.62 5.34 15.07 5.9 16.21 6.99L19.34 3.86C17.46 2.11 14.97 1 12 1C7.71 1 4.01 3.41 2.21 6.99L5.88 9.84C6.77 7.25 9.17 5.34 12 5.34Z" fill="#EA4335"/>
                    </svg>
                    Se connecter avec Google
                </a>
            </form>
        </div>
    </div>

    <footer class="auth-footer">
        <ul>
            <li><strong>Informations</strong></li>
            <li><a href="#">Aide et contact</a></li>
            <li><a href="#">Mentions légales</a></li>
            <li><a href="#">Conditions d'utilisation</a></li>
            <li><a href="#">Conditions générales d'abonnement</a></li>
            <li><a href="#">Espace de confidentialité</a></li>
        </ul>
    </footer>

    <script src="/assets/js/navbar.js" defer></script>
</body>
</html>
