<?php
require_once __DIR__ . '/../../config/database.php';

try {
    $queries = [
        "ALTER TABLE utilisateur ADD COLUMN nom VARCHAR(255) NULL",
        "ALTER TABLE utilisateur ADD COLUMN prenom VARCHAR(255) NULL",
        "ALTER TABLE utilisateur ADD COLUMN email VARCHAR(255) NULL",
        "ALTER TABLE utilisateur ADD COLUMN date_naissance DATE NULL",
        "ALTER TABLE utilisateur ADD COLUMN civilite VARCHAR(50) NULL",
        "ALTER TABLE utilisateur ADD COLUMN code_postal VARCHAR(20) NULL",
        "ALTER TABLE utilisateur ADD UNIQUE (email)"
    ];

    foreach ($queries as $query) {
        try {
            $pdo->exec($query);
            echo "Succès: $query <br>";
        } catch (PDOException $e) {
            echo "Erreur ou déjà existant sur '$query' : " . $e->getMessage() . "<br>";
        }
    }
    echo "<br><b>Migration terminée avec succès ! Vous pouvez maintenant utiliser les formulaires.</b>";
} catch (Exception $e) {
    echo "Erreur critique : " . $e->getMessage();
}
?>
