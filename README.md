# Ligue 1 McDonalds - Plateforme Web

![Ligue 1 McDonalds](/assets/images/logos/ligue1.jpg)

Bienvenue sur le projet **Ligue 1 McDonalds**, une application web interactive dédiée au championnat de France de football. Ce projet offre une plateforme de démonstration incluant le calendrier des matchs, les classements en direct, ainsi qu'un espace membre.

## 🚀 Fonctionnalités

- **Calendrier Interactif :** Parcourez les journées de championnat, visualisez les rencontres passées et à venir grâce à une interface fluide (ScrollSpy & Lenis).
- **Classement en Temps Réel :** Suivez l'évolution du classement général de la Ligue 1.
- **Espace Membre :** Système de connexion et d'inscription sécurisé, permettant l'accès à un tableau de bord personnalisé.
- **Design Moderne & Animations :** Interface utilisateur dynamique utilisant AOS (Animate On Scroll) et Lenis pour un défilement doux, avec une expérience utilisateur optimisée.

## 📁 Structure du Projet

L'architecture du projet est conçue pour être claire, modulaire et évolutive :

```text
ligue1-webapp/
├── public/                 # Point d'entrée du serveur web
│   └── index.php           # Page d'accueil principale
│
├── src/                    # Code source PHP backend et frontend
│   ├── config/             # Configuration globale
│   │   └── database.php    # Connexion PDO sécurisée
│   ├── includes/           # Composants réutilisables (ex: Navbar)
│   │   └── navbar.php
│   └── pages/              # Vues principales de l'application
│       ├── auth/           # Authentification (login, register, logout, dashboard)
│       ├── classement.php
│       ├── matches.php
│       ├── club.php
│       └── saison.php
│
├── assets/                 # Ressources statiques
│   ├── css/                # Feuilles de styles modulaires
│   ├── js/                 # Scripts (animations, logique d'authentification)
│   └── images/             # Médias (Logos des clubs, Backgrounds)
│
└── database/               # Fichiers SQL d'initialisation (Schema & Seed)
```

## 🛠️ Technologies Utilisées

- **Frontend :** HTML5, CSS3, Vanilla JavaScript
- **Librairies JS :** [Lenis](https://github.com/studio-freight/lenis) (Smooth Scroll), [AOS](https://michalsnik.github.io/aos/) (Animations), [Flatpickr](https://flatpickr.js.org/) (Sélection de dates)
- **Backend :** PHP (PDO)
- **Base de Données :** MySQL / MariaDB

## ⚙️ Installation & Lancement en local

1. **Prérequis :** Disposer d'un serveur web local avec PHP et MySQL (ex: WampServer, XAMPP, MAMP).
2. **Base de données :**
   - Importez le fichier `database/schema.sql` dans votre serveur MySQL (par exemple via phpMyAdmin).
   - (Optionnel) Importez les données de test depuis `database/seed.sql`.
3. **Configuration :** 
   - Le fichier `src/config/database.php` gère la connexion. Par défaut, il tente de se connecter sur `localhost` avec l'utilisateur `root` (sans mot de passe) sur la base `ligue1`.
4. **Lancement via Terminal (serveur PHP intégré) :**
   ```bash
   # Depuis la racine du projet
   php -S localhost:8000
   ```
   *Accédez ensuite à `http://localhost:8000/index.php` depuis votre navigateur.*

## 🤝 Auteur

Développé dans le cadre d'un projet de conception web.

- **Portfolio :** [yanisbenkrouidem.com](https://yanisbenkrouidem.com/)
- **LinkedIn :** [Yanis Benkrouidem](https://www.linkedin.com/in/yanisbenkrouidem/)

---
*© 2025 Ligue 1 Company. Tous droits réservés.*
