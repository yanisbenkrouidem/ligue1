<?php
session_start();
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/auth.php';

// Redirect to login if not authenticated (uses cookie-based auth for Vercel)
$authenticatedUser = auth_check();
if (!$authenticatedUser) {
    header("Location: /src/pages/auth/login.php");
    exit();
}

$pseudo = htmlspecialchars($authenticatedUser);

// Handle account deletion
if (isset($_POST['btnDeleteAccount'])) {
    $del = $pdo->prepare("DELETE FROM utilisateur WHERE pseudoutil = :pseudo");
    $del->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    if ($del->execute()) {
        auth_logout();
        header("Location: /index.php");
        exit();
    }
}

// Handle personal data update
$msg_perso = "";
if (isset($_POST['btnUpdatePerso'])) {
    $upd_nom = htmlspecialchars($_POST['nom']);
    $upd_prenom = htmlspecialchars($_POST['prenom']);
    $upd_date = htmlspecialchars($_POST['date_naissance']);
    $upd_civilite = htmlspecialchars($_POST['civilite']);
    $upd_cp = htmlspecialchars($_POST['code_postal']);

    $upd = $pdo->prepare("UPDATE utilisateur SET nom = :nom, prenom = :prenom, date_naissance = :date, civilite = :civ, code_postal = :cp WHERE pseudoutil = :pseudo");
    $upd->bindParam(':nom', $upd_nom, PDO::PARAM_STR);
    $upd->bindParam(':prenom', $upd_prenom, PDO::PARAM_STR);
    $upd->bindParam(':date', $upd_date, PDO::PARAM_STR);
    $upd->bindParam(':civ', $upd_civilite, PDO::PARAM_STR);
    $upd->bindParam(':cp', $upd_cp, PDO::PARAM_STR);
    $upd->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    
    if ($upd->execute()) {
        $msg_perso = "<p style='color: #4CAF50; font-size: 14px;'>Données personnelles mises à jour.</p>";
    } else {
        $msg_perso = "<p style='color: red; font-size: 14px;'>Erreur lors de la mise à jour.</p>";
    }
}

// Handle email update
$msg_email = "";
if (isset($_POST['btnUpdateEmail'])) {
    $upd_email = htmlspecialchars($_POST['email']);
    $upd = $pdo->prepare("UPDATE utilisateur SET email = :email WHERE pseudoutil = :pseudo");
    $upd->bindParam(':email', $upd_email, PDO::PARAM_STR);
    $upd->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    
    if ($upd->execute()) {
        $msg_email = "<p style='color: #4CAF50; font-size: 14px;'>Adresse e-mail mise à jour.</p>";
    } else {
        $msg_email = "<p style='color: red; font-size: 14px;'>Erreur lors de la mise à jour.</p>";
    }
}

// Handle password change
$msg_pwd = "";
if (isset($_POST['btnUpdatePwd'])) {
    $old_pwd = $_POST['old_pwd'];
    $new_pwd = $_POST['new_pwd'];
    
    // Fetch current pwd to check
    $req_check = $pdo->prepare("SELECT mdputil FROM utilisateur WHERE pseudoutil = :pseudo");
    $req_check->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $req_check->execute();
    $current_pwd_db = $req_check->fetchColumn();

    if ($old_pwd === $current_pwd_db) {
        $upd = $pdo->prepare("UPDATE utilisateur SET mdputil = :nmdp WHERE pseudoutil = :pseudo");
        $upd->bindParam(':nmdp', $new_pwd, PDO::PARAM_STR);
        $upd->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        if ($upd->execute()) {
            $msg_pwd = "<p style='color: #4CAF50; font-size: 14px;'>Mot de passe modifié avec succès.</p>";
        } else {
            $msg_pwd = "<p style='color: red; font-size: 14px;'>Erreur lors de la modification.</p>";
        }
    } else {
        $msg_pwd = "<p style='color: red; font-size: 14px;'>L'ancien mot de passe est incorrect.</p>";
    }
}

// Fetch user data (latest)
$req = $pdo->prepare("SELECT * FROM utilisateur WHERE pseudoutil = :pseudo");
$req->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
$req->execute();
$user_data = $req->fetch();

$nom = $user_data['nom'] ?? '';
$prenom = $user_data['prenom'] ?? '';
$date_naissance = $user_data['date_naissance'] ?? '';
$civilite = $user_data['civilite'] ?? '';
$code_postal = $user_data['code_postal'] ?? '';
$email = $user_data['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Ligue 1 McDonalds</title>
    <link rel="icon" type="image/jpg" href="/assets/images/logos/ligue1.jpg">
    <link rel="stylesheet" href="/assets/css/navbar.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="no-scroll-body">

    <?php include __DIR__ . '/../../includes/navbar.php'; ?>

    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <img src="/assets/images/avatar.png" alt="Profile Avatar" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin-bottom: 15px; border: 2px solid #333;">
            <div class="username"><?php echo $pseudo; ?></div>
            
            <ul class="nav-links">
                <li><a href="#" class="active">Vue d'ensemble</a></li>
            </ul>

            <div class="spacer"></div>
            <button class="btn-logout" onclick="window.location.href='/src/pages/auth/logout.php'">Me déconnecter</button>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1 class="page-title">Mes Informations</h1>

            <div class="dashboard-grid">
                <!-- Left Column -->
                <div class="dashboard-col">
                    <!-- Personnal Data -->
                    <div class="card">
                        <h2>Mes données personnelles</h2>
                        <?php echo $msg_perso; ?>
                        <form method="POST">
                            <div class="form-grid">
                                <div class="data-group">
                                    <label class="data-label">Prénom</label>
                                    <input type="text" name="prenom" class="dash-input" value="<?php echo htmlspecialchars($prenom); ?>" required>
                                </div>
                                
                                <div class="data-group">
                                    <label class="data-label">Nom</label>
                                    <input type="text" name="nom" class="dash-input" value="<?php echo htmlspecialchars($nom); ?>" required>
                                </div>
                                
                                <div class="data-group">
                                    <label class="data-label">Date de naissance</label>
                                    <input type="date" name="date_naissance" class="dash-input" value="<?php echo htmlspecialchars($date_naissance); ?>" required>
                                </div>
                                
                                <div class="data-group">
                                    <label class="data-label">Civilité</label>
                                    <select name="civilite" class="dash-input" required>
                                        <option value="Monsieur" <?php if($civilite === 'Monsieur') echo 'selected'; ?>>Monsieur</option>
                                        <option value="Madame" <?php if($civilite === 'Madame') echo 'selected'; ?>>Madame</option>
                                    </select>
                                </div>
                                
                                <div class="data-group">
                                    <label class="data-label">Code postal</label>
                                    <input type="text" name="code_postal" class="dash-input" value="<?php echo htmlspecialchars($code_postal); ?>" required>
                                </div>
                            </div>

                            <div class="card-actions">
                                <button type="submit" name="btnUpdatePerso" class="btn-black">Mettre à jour</button>
                                <button type="button" class="btn-outline-black" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer définitivement votre compte ? Cette action est irréversible.')) { document.getElementById('delete-form').submit(); }">Supprimer mon compte</button>
                            </div>
                        </form>
                        
                        <!-- Hidden form for deletion -->
                        <form id="delete-form" method="POST" style="display:none;">
                            <input type="hidden" name="btnDeleteAccount" value="1">
                        </form>
                    </div>

                </div>

                <!-- Right Column -->
                <div class="dashboard-col">
                    <!-- Email -->
                    <div class="card">
                        <h2>Mon Email</h2>
                        <?php echo $msg_email; ?>
                        <form method="POST">
                            <div class="data-group">
                                <label class="data-label">Adresse e-mail</label>
                                <input type="email" name="email" class="dash-input" value="<?php echo htmlspecialchars($email); ?>" required>
                            </div>
                            <button type="submit" name="btnUpdateEmail" class="btn-black">Modifier</button>
                        </form>
                    </div>

                    <!-- Password -->
                    <div class="card">
                        <h2>Mon mot de passe</h2>
                        <?php echo $msg_pwd; ?>
                        <form method="POST" style="display:flex; flex-direction:column; gap: 10px;">
                            <input type="password" name="old_pwd" placeholder="Ancien mot de passe" class="dash-input" required>
                            <input type="password" name="new_pwd" placeholder="Nouveau mot de passe" class="dash-input" required>
                            <button type="submit" name="btnUpdatePwd" class="btn-black" style="width: fit-content; margin-top: 10px;">Modifier</button>
                        </form>
                        <br>
                        <a href="#" class="link-muted">Mot de passe oublié ?</a>
                    </div>

                    <!-- Ads switch -->
                    <div class="card">
                        <h2>Publicité de nos Partenaires</h2>
                        <div class="switch-group">
                            <div class="switch-text">
                                <p>J'accepte que la Ligue de Football Professionnel (LFP) communique à ses partenaires sélectionnés les informations relatives à mon compte afin de recevoir des offres personnalisées (notamment billetterie et merchandising).</p>
                                <p>Je note que les informations relatives à mes préférences d'équipes ne seront communiquées qu'en cas d'acceptation des cookies.</p>
                                <a href="#">En savoir plus sur la gestion de mes données et mes droits</a>
                            </div>
                            <label class="switch">
                                <input type="checkbox" checked>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>
</html>
