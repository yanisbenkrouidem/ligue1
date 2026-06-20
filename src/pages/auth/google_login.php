<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/auth.php';

// Google OAuth 2.0 Credentials
$clientID = getenv('GOOGLE_CLIENT_ID') ?: 'YOUR_CLIENT_ID';
$clientSecret = getenv('GOOGLE_CLIENT_SECRET') ?: 'YOUR_CLIENT_SECRET';

// Determine the exact redirect URI to avoid mismatch errors
if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
    $redirectUri = 'http://localhost:8000/src/pages/auth/google_login.php';
} else {
    $redirectUri = 'https://ligue1.benkrouidem.com/src/pages/auth/google_login.php';
}

if (!isset($_GET['code'])) {
    // 1. Redirect to Google for authorization
    // On Vercel serverless, sessions may not persist between requests.
    // We store the state in a cookie as a fallback.
    $state = bin2hex(random_bytes(16));
    $_SESSION['oauth2state'] = $state;
    setcookie('oauth2state', $state, [
        'expires' => time() + 600, // 10 minutes
        'path' => '/',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    $authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
        'client_id' => $clientID,
        'redirect_uri' => $redirectUri,
        'response_type' => 'code',
        'scope' => 'email profile',
        'state' => $state,
        'access_type' => 'online'
    ]);

    header('Location: ' . $authUrl);
    exit();
} else {
    // 2. We have a code, let's exchange it for an access token
    // Check state from session OR cookie (Vercel fallback)
    $savedState = $_SESSION['oauth2state'] ?? $_COOKIE['oauth2state'] ?? null;
    
    if (empty($_GET['state']) || ($savedState !== null && $_GET['state'] !== $savedState)) {
        exit('Invalid state');
    }

    $ch = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'client_id' => $clientID,
        'client_secret' => $clientSecret,
        'redirect_uri' => $redirectUri,
        'code' => $_GET['code'],
        'grant_type' => 'authorization_code'
    ]));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $tokenData = json_decode($response, true);

    if (isset($tokenData['error'])) {
        exit('Error fetching token: ' . htmlspecialchars($tokenData['error_description'] ?? $tokenData['error']));
    }

    $accessToken = $tokenData['access_token'];

    // 3. Get User Profile from Google
    $ch = curl_init('https://www.googleapis.com/oauth2/v2/userinfo');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $userResponse = curl_exec($ch);
    
    $googleUser = json_decode($userResponse, true);

    if (isset($googleUser['error'])) {
        exit('Error fetching user info');
    }

    $email = $googleUser['email'];
    
    // Create a pseudo from email (everything before @)
    $pseudoBase = explode('@', $email)[0];
    $pseudo = $pseudoBase;

    // 4. Log the user in or register them in our database
    try {
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE pseudoutil = :pseudo");
        $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // User exists -> Log them in via cookie
            auth_login($user['pseudoutil']);
        } else {
            // User does not exist -> Register them automatically
            $suffix = 1;
            $originalPseudo = $pseudo;
            while (true) {
                $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE pseudoutil = :pseudo");
                $checkStmt->execute(['pseudo' => $pseudo]);
                if ($checkStmt->fetchColumn() == 0) {
                    break;
                }
                $pseudo = $originalPseudo . $suffix;
                $suffix++;
            }

            $randomPassword = bin2hex(random_bytes(8));
            $insertStmt = $pdo->prepare("INSERT INTO utilisateur (pseudoutil, mdputil) VALUES (:pseudo, :mdputil)");
            $insertStmt->execute([
                'pseudo' => $pseudo,
                'mdputil' => $randomPassword
            ]);

            // Log them in via cookie
            auth_login($pseudo);
        }

        // Redirect to dashboard
        header("Location: /src/pages/auth/dashboard.php");
        exit();

    } catch (PDOException $e) {
        exit("Erreur de base de données : " . $e->getMessage());
    } catch (Exception $e) {
        exit("Erreur : " . $e->getMessage());
    }
}
