# 🎯 TutorMatch - Système de Matchmaking

<div align="center">

**Une plateforme qui connecte tuteurs et élèves grâce à un algorithme de compatibilité **

https://drive.google.com/file/d/1Rg9OjQgezevPGpiQkh5DOK8APymdSzip/view?usp=sharing 🌟 Présentation du Projet

TutorMatch analyse :

-   📚 La compatibilité des matières
-   🎓 L'adéquation des niveaux scolaires
-   ⏰ Les créneaux horaires disponibles
-   💰 Les budgets et tarifs

Le système génère des **scores de compatibilité sur 100 points** pour garantir les meilleures correspondances possibles.

## ✨ Fonctionnalités Clés

### 🔐 **Système d'Authentification**

-   Inscription et connexion sécurisées
-   Comptes de démonstration pré-créés

### 👨‍🏫 **Gestion des Tuteurs**

-   Profils détaillés avec matières enseignées
-   Définition des niveaux pris en charge
-   Planification des disponibilités
-   Gestion des tarifs et expérience

### 🎓 **Gestion des Élèves**

-   Profils personnalisés avec besoins spécifiques
-   Matières recherchées et niveau scolaire
-   Disponibilités et budget maximum
-   Visualisation des matchs recommandés

### 🤖 **Algorithme de Matchmaking **

```
Score de Compatibilité = 40% Matières + 30% Niveau + 30% Disponibilités
```

-   **40 points** : Compatibilité des matières communes
-   **30 points** : Adéquation des niveaux scolaires
-   **30 points** : Créneaux horaires partagés

### 📊 **Dashboard **

-   Statistiques en temps réel
-   Visualisation des meilleurs matchs
-   Gestion des statuts (suggéré/accepté/rejeté)
-   Interface responsive et moderne

## 🚀 Installation Rapide

### Prérequis Système

```bash
PHP 8.1+
Composer
MySQL 8.0+
```

### 1️⃣ Configuration Initiale

```bash
# Cloner le projet
git clone [[votre-repo]](https://github.com/rogerfarolix/TutorMatch)
cd TutorMatch

# Installation des dépendances
composer install

# Configuration de l'environnement
cp .env.example .env
php artisan key:generate
```

### 2️⃣ Configuration Base de Données

Modifiez votre fichier `.env` :

```bash
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

# Peuplement avec données de démonstration (inclut les utilisateurs)
php artisan db:seed
```

### 4️⃣ Lancement de l'Application

```bash
php artisan serve
```

🌐 **Accédez à l'application sur : http://localhost:8000**

## 👥 Comptes de Démonstration

Le seeder crée automatiquement des comptes de test :

| Type    | Email                | Mot de Passe          | Description    |
| ------- | -------------------- | --------------------- | -------------- |
| 👨‍🏫 User | admin@tutormatch.com | passwordtutormatch123 | Administrateur |

## 🎯 Exemple de Matching Parfait

### Cas d'Usage Concret

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

**🎯 Résultat : Score de 100/100** ✨

-   ✅ Matière commune (40 pts)
-   ✅ Niveau compatible (30 pts)
-   ✅ Créneaux parfaits (30 pts)

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
│   └── MatchModel              # Modèle de match
└── Services/
    └── MatchmakingService # Algorithme de matching
```

### Base de Données

```sql
users          # Table des utilisateurs (authentification)
tutors         # Profils tuteurs avec compétences
students       # Profils élèves avec besoins
matches        # Résultats des matchings avec scores
```

## 🔧 Technologies Utilisées

-   **Backend** : Laravel 10.x, PHP 8.1+
-   **Base de Données** : MySQL 8.0+
-   **Frontend** : Bootstrap 5, JavaScript ES6
-   **Authentification** : Laravel Sanctum
-   **Architecture** : MVC, Services Pattern

## 📈 Roadmap & Améliorations Futures

-   [ ] 🔔 Système de notifications en temps réel
-   [ ] 💬 Chat intégré tuteur-élève
-   [ ] 📱 Application mobile (API REST)
-   [ ] 🎥 Visioconférence intégrée
-   [ ] 📊 Analytics avancées
-   [ ] 🌍 Support multi-langues

---

<div align="center">

**Développé avec ❤️ Par Roger Gnanih**

</div>
