<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/auth.php';

$inscriptionMessage = "";

if (isset($_POST["btninscrit"])) {
    $email = htmlspecialchars($_POST["email"] ?? '');
    $mdp = $_POST["mdp"];
    $nom = htmlspecialchars($_POST["nom"] ?? '');
    $prenom = htmlspecialchars($_POST["prenom"] ?? '');
    $date_naissance = htmlspecialchars($_POST["date_naissance"] ?? '');
    $civilite = htmlspecialchars($_POST["civilite"] ?? '');
    $code_postal = htmlspecialchars($_POST["code_postal"] ?? '');
    $pseudo = htmlspecialchars($_POST["pseudo"] ?? '');

    $req = $bdd->prepare("SELECT pseudoutil FROM utilisateur WHERE pseudoutil = :pseudo OR email = :email");
    $req->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $req->bindParam(':email', $email, PDO::PARAM_STR);
    $req->execute();
    $uneligne = $req->fetch();

    if ($uneligne) {
        $inscriptionMessage = "<p style='color: red; text-align: center; font-weight: bold;'>Ce pseudo ou cet email est déjà utilisé.</p>";
    } else {
        $req = $bdd->prepare("INSERT INTO utilisateur (pseudoutil, mdputil, nom, prenom, date_naissance, civilite, code_postal, email) VALUES (:pse, :mdp, :nom, :pre, :date, :civ, :cp, :email)");
        $req->bindParam(':pse', $pseudo, PDO::PARAM_STR);
        $req->bindParam(':mdp', $mdp, PDO::PARAM_STR);
        $req->bindParam(':nom', $nom, PDO::PARAM_STR);
        $req->bindParam(':pre', $prenom, PDO::PARAM_STR);
        $req->bindParam(':date', $date_naissance, PDO::PARAM_STR);
        $req->bindParam(':civ', $civilite, PDO::PARAM_STR);
        $req->bindParam(':cp', $code_postal, PDO::PARAM_STR);
        $req->bindParam(':email', $email, PDO::PARAM_STR);

        if ($req->execute()) {
            auth_login($pseudo);
            header("Location: /src/pages/auth/dashboard.php");
            exit();
        } else {
            $inscriptionMessage = "<p style='color: red; text-align: center; font-weight: bold;'>Échec de l'inscription.</p>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Ligue 1</title>
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
        <div class="auth-title-top">Créez un compte</div>
        
        <div class="auth-card">
            <p class="mandatory-note">Les champs marqués d'un astérisque (*) sont obligatoires</p>
            
            <?php echo $inscriptionMessage; ?>

            <form method="POST">
                <div class="input-group">
                    <label>Pseudo*</label>
                    <input type="text" name="pseudo" class="auth-input" required>
                </div>
                
                <div style="display: flex; gap: 15px;">
                    <div class="input-group" style="flex: 1;">
                        <label>Prénom*</label>
                        <input type="text" name="prenom" class="auth-input" required>
                    </div>
                    <div class="input-group" style="flex: 1;">
                        <label>Nom*</label>
                        <input type="text" name="nom" class="auth-input" required>
                    </div>
                </div>

                <div style="display: flex; gap: 15px;">
                    <div class="input-group" style="flex: 1;">
                        <label>Date de naissance*</label>
                        <input type="date" name="date_naissance" class="auth-input" required>
                    </div>
                    <div class="input-group" style="flex: 1;">
                        <label>Civilité*</label>
                        <select name="civilite" class="auth-input" required>
                            <option value="" disabled selected>Sélectionnez</option>
                            <option value="Monsieur">Monsieur</option>
                            <option value="Madame">Madame</option>
                        </select>
                    </div>
                </div>

                <div class="input-group">
                    <label>Code postal*</label>
                    <input type="text" name="code_postal" class="auth-input" required>
                </div>

                <div class="input-group">
                    <label>Adresse e-mail*</label>
                    <input type="email" name="email" class="auth-input" required>
                </div>

                <div class="input-group">
                    <label>Mot de passe*</label>
                    <input type="password" name="mdp" class="auth-input" required>
                    <span class="input-icon">👁️</span>
                    <div class="password-criteria">
                        <span>✔️ 8 caractères minimum</span>
                        <span>✔️ 1 majuscule</span>
                        <span>✔️ 1 minuscule</span>
                        <span>✔️ 1 chiffre</span>
                    </div>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" required>
                    <label>Je souhaite recevoir l'actualité des programmes et les offres sélectionnées par la LFP</label>
                </div>

                <div class="disclaimer-text">
                    La LFP traite vos informations renseignées, sur la base de votre consentement pour personnaliser votre service (contenus proposés, newsletters, adresses...) ainsi que vos publicités. Vous pouvez modifier immédiatement et à tout moment vos paramètres de confidentialité.
                    <br><br>
                    Pour en savoir plus sur la manière dont nous collectons, partageons, conservons et protégeons vos données ainsi que les droits que vous détenez sur celles-ci, veuillez consulter notre <a href="#">espace de confidentialité</a>.
                </div>

                <button type="submit" name="btninscrit" class="auth-btn">S'inscrire</button>

                <hr class="divider">

                <!-- Third party logins -->
                <a href="/src/pages/auth/google_login.php" class="auth-btn-outline" style="text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 10px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25C22.56 11.47 22.49 10.72 22.36 10H12V14.26H17.92C17.66 15.63 16.88 16.81 15.69 17.61V20.34H19.26C21.36 18.42 22.56 15.6 22.56 12.25Z" fill="#4285F4"/>
                        <path d="M12 23C14.97 23 17.46 22.02 19.26 20.34L15.69 17.61C14.71 18.27 13.46 18.66 12 18.66C9.17 18.66 6.77 16.75 5.88 14.16H2.21V17.01C4.01 20.59 7.71 23 12 23Z" fill="#34A853"/>
                        <path d="M5.88 14.16C5.65 13.48 5.52 12.76 5.52 12C5.52 11.24 5.65 10.52 5.88 9.84V6.99H2.21C1.46 8.47 1 10.18 1 12C1 13.82 1.46 15.53 2.21 17.01L5.88 14.16Z" fill="#FBBC05"/>
                        <path d="M12 5.34C13.62 5.34 15.07 5.9 16.21 6.99L19.34 3.86C17.46 2.11 14.97 1 12 1C7.71 1 4.01 3.41 2.21 6.99L5.88 9.84C6.77 7.25 9.17 5.34 12 5.34Z" fill="#EA4335"/>
                    </svg>
                    S'inscrire avec Google
                </a>

                <div class="auth-links muted" style="margin-top: 20px;">
                    Vous avez déjà un compte ? <br><br>
                    <a href="/src/pages/auth/login.php" style="font-weight: bold; text-decoration: underline;">Connectez-vous</a>
                </div>
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
