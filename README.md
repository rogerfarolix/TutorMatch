# Installation du Système de Matchmaking TutorMatch

## Prérequis

-   PHP 8.1+
-   Composer
-   Base de données (MySQL/PostgreSQL/SQLite)

## Étapes d'installation

### 1. Configuration de la base de données

Configurez votre fichier `.env` avec vos paramètres de base de données :

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tutormatch
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 2. Installation des dépendances

```bash
composer install
```

### 3. Génération de la clé d'application

```bash
php artisan key:generate
```

### 4. Création et exécution des migrations

```bash
# Créer les fichiers de migration
php artisan make:migration create_tutors_table
php artisan make:migration create_students_table
php artisan make:migration create_matches_table

# Exécuter les migrations
php artisan migrate
```

### 5. Création des modèles et contrôleurs

```bash
# Créer les modèles
php artisan make:model Tutor
php artisan make:model Student
php artisan make:model Match

# Créer les contrôleurs
php artisan make:controller TutorController --resource
php artisan make:controller StudentController --resource
php artisan make:controller MatchController
php artisan make:controller DashboardController

# Créer le service de matchmaking
mkdir app/Services
# Créer le fichier MatchmakingService.php dans app/Services/
```

### 6. Peuplement de la base de données avec des données de test

```bash
php artisan db:seed
```

### 7. Lancement du serveur de développement

```bash
php artisan serve
```

L'application sera accessible sur : http://localhost:8000

## Structure du projet

### Migrations

-   `create_tutors_table` : Table des tuteurs avec leurs matières, niveaux et disponibilités
-   `create_students_table` : Table des élèves avec leurs besoins
-   `create_matches_table` : Table des matchs avec scores de compatibilité

### Modèles

-   `Tutor` : Modèle tuteur avec méthodes de compatibilité
-   `Student` : Modèle élève
-   `Match` : Modèle de match avec relations

### Contrôleurs

-   `TutorController` : CRUD des tuteurs
-   `StudentController` : CRUD des élèves + visualisation matchs
-   `MatchController` : Gestion des matchs et génération
-   `DashboardController` : Page d'accueil avec statistiques

### Services

-   `MatchmakingService` : Algorithme de matching et calcul de compatibilité

## Fonctionnalités

### ✅ Gestion des tuteurs

-   Ajout/modification/suppression de tuteurs
-   Définition des matières enseignées
-   Niveaux pris en charge
-   Disponibilités horaires
-   Tarifs et expérience

### ✅ Gestion des élèves

-   Ajout/modification/suppression d'élèves
-   Matières demandées
-   Niveau scolaire
-   Disponibilités
-   Budget maximum

### ✅ Algorithme de matchmaking

-   **Score de compatibilité sur 100 points :**
    -   40% pour les matières communes
    -   30% pour la compatibilité de niveau
    -   30% pour les créneaux horaires communs
-   Classement par score décroissant
-   Détails des matchs (matières, créneaux, budget)

### ✅ Interface utilisateur

-   Dashboard avec statistiques
-   Visualisation des meilleurs matchs
-   Gestion des statuts (suggéré/accepté/rejeté)
-   Interface responsive avec Bootstrap

### ✅ Fonctionnalités bonus

-   Score de compatibilité détaillé
-   Filtrage par expérience et budget
-   Gestion des statuts de match
-   Interface intuitive et moderne

## Utilisation

1. **Ajouter des tuteurs** via la section "Tuteurs"
2. **Ajouter des élèves** via la section "Élèves"
3. **Visualiser les matchs** automatiquement générés
4. **Accepter/Rejeter** les matchs depuis l'interface élève
5. **Générer tous les matchs** depuis le dashboard si nécessaire

## Exemple de données (déjà incluses dans le seeder)

### Tuteurs

-   **Ahmed** : Mathématiques, Lycée, Lundi 18h-20h + Mercredi 16h-20h + Samedi 10h-19h
-   **Sarah** : Physique, Collège & Lycée, Mercredi 14h-16h + Samedi 10h-22h
-   **Karim** : Français, Terminale, Lundi 18h-20h

### Élèves

-   **Ali** : Mathématiques, Lycée, Lundi 18h-20h → **Match parfait avec Ahmed**
-   **Yasmine** : Physique, Collège, Mercredi 14h-16h → **Match parfait avec Sarah**

## Algorithme de matching

L'algorithme prend en compte :

1. **Compatibilité des matières** (40 points max)
2. **Compatibilité du niveau** (30 points max)
3. **Créneaux horaires communs** (30 points max)

**Exemple de calcul :**

-   Matières : 1/1 matière commune = 40 points
-   Niveau : Compatible = 30 points
-   Disponibilité : 2h en commun sur 10h max = 6 points
-   **Score total : 76/100 = 76%**
