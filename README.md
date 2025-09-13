# 🎯 TutorMatch - Système de Matchmaking

<div align="center">

![TutorMatch Banner](https://i.ibb.co/0FvG1k5/tutormatch-banner.png)  
**Une plateforme qui connecte tuteurs et élèves grâce à un algorithme de compatibilité**

[🌟 Présentation du Projet](https://drive.google.com/file/d/1Rg9OjQgezevPGpiQkh5DOK8APymdSzip/view?usp=sharing)

</div>

---

## 🏷️ Badges

![Laravel](https://img.shields.io/badge/Laravel-10.x-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.1-blue?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-blue?style=for-the-badge&logo=mysql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-purple?style=for-the-badge&logo=bootstrap)

---

## 📌 Description

TutorMatch analyse :

-   📚 Compatibilité des matières
-   🎓 Adéquation des niveaux scolaires
-   ⏰ Créneaux horaires disponibles
-   💰 Budgets et tarifs

Le système génère un **score de compatibilité sur 100 points** pour garantir les meilleures correspondances possibles.

---

## ✨ Fonctionnalités Clés

### 🔐 Authentification

-   Inscription et connexion sécurisées
-   Comptes de démonstration pré-créés

### 👨‍🏫 Gestion des Tuteurs

-   Profils détaillés avec matières enseignées
-   Définition des niveaux pris en charge
-   Planification des disponibilités
-   Gestion des tarifs et de l’expérience

### 🎓 Gestion des Élèves

-   Profils personnalisés avec besoins spécifiques
-   Matières recherchées et niveau scolaire
-   Disponibilités et budget maximum
-   Visualisation des matchs recommandés

### 🤖 Algorithme de Matchmaking

```text
Score de Compatibilité = 40% Matières + 30% Niveau + 30% Disponibilités
```

-   40 pts : Compatibilité des matières
-   30 pts : Adéquation des niveaux scolaires
-   30 pts : Créneaux horaires partagés

### 📊 Dashboard

-   Statistiques en temps réel
-   Visualisation des meilleurs matchs
-   Gestion des statuts (suggéré / accepté / rejeté)
-   Interface moderne et responsive

---

## 🚀 Installation Rapide

### Prérequis

```bash
PHP 8.1+
Composer
MySQL 8.0+
```

### 1️⃣ Configuration Initiale

```bash
# Cloner le projet
git clone https://github.com/rogerfarolix/TutorMatch
cd TutorMatch

# Installation des dépendances
composer install

# Configuration de l'environnement
cp .env.example .env
php artisan key:generate
```

### 2️⃣ Configuration Base de Données

Modifiez `.env` :

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tutormatch
DB_USERNAME=root
DB_PASSWORD=
```

### 3️⃣ Initialisation de la Base de Données

```bash
# Exécution des migrations
php artisan migrate

# Peuplement avec données de démonstration
php artisan db:seed
```

### 4️⃣ Lancement de l'Application

```bash
php artisan serve
```

🌐 Accédez à l'application : [http://localhost:8000](http://localhost:8000)

---

## 👥 Comptes de Démonstration

| Type    | Email                                               | Mot de Passe          | Description    |
| ------- | --------------------------------------------------- | --------------------- | -------------- |
| 👨‍🏫 User | [admin@tutormatch.com](mailto:admin@tutormatch.com) | passwordtutormatch123 | Administrateur |

---

## 🎯 Exemple de Matching Parfait

**👨‍🏫 Ahmed (Tuteur)**

-   Matière : Mathématiques
-   Niveau : Lycée
-   Disponibilité : Lundi 18h-20h
-   Tarif : 25€/h

**🎓 Ali (Élève)**

-   Matière recherchée : Mathématiques
-   Niveau : Lycée
-   Disponibilité : Lundi 18h-20h
-   Budget : 30€/h

**🎯 Résultat : Score 100/100**

-   ✅ Matière commune (40 pts)
-   ✅ Niveau compatible (30 pts)
-   ✅ Créneaux parfaits (30 pts)

---

## 🏗️ Architecture Technique

### Structure des Dossiers

```
app/
├── Http/Controllers/
│   ├── Auth/               # Contrôleurs d'authentification
│   ├── TutorController     # Gestion des tuteurs
│   ├── StudentController   # Gestion des élèves
│   └── MatchController     # Système de matching
├── Models/
│   ├── User               # Modèle utilisateur
│   ├── Tutor              # Modèle tuteur
│   ├── Student            # Modèle élève
│   └── MatchModel         # Modèle de match
└── Services/
    └── MatchmakingService # Algorithme de matching
```

### Base de Données

```sql
users          # Utilisateurs (authentification)
tutors         # Profils tuteurs
students       # Profils élèves
matches        # Résultats des matchings avec scores
```

---

## 🔧 Technologies Utilisées

-   **Backend** : Laravel 10.x, PHP 8.1+
-   **Base de Données** : MySQL 8.0+
-   **Frontend** : Bootstrap 5, JavaScript ES6
-   **Authentification** : Laravel Sanctum
-   **Architecture** : MVC, Services Pattern

---

## 📈 Roadmap & Améliorations Futures

-   🔔 Notifications en temps réel
-   💬 Chat intégré tuteur-élève
-   📱 Application mobile (API REST)
-   🎥 Visioconférence intégrée
-   📊 Analytics avancées
-   🌍 Support multi-langues

---

<div align="center">

**Développé avec ❤️ par Roger Gnanih**

![GitHub followers](https://img.shields.io/github/followers/rogerfarolix?label=Suivez%20moi&style=social)

</div>
