# Fonctionnalité: Adresse et Informations du Tuteur pour les Étudiants

## 📋 Résumé
Cette fonctionnalité ajoute la possibilité de gérer l'adresse de l'étudiant ainsi que les informations du tuteur (nom et numéro de téléphone) dans le système de gestion des étudiants.

## 🆕 Nouveaux Champs Ajoutés

### Pour l'étudiant:
1. **Adresse** (`address`) - Champ texte pour l'adresse complète de l'étudiant
2. **Nom du tuteur** (`tutor_name`) - Nom complet du tuteur ou parent responsable
3. **Numéro du tuteur** (`tutor_phone`) - Numéro de téléphone du tuteur

## 📁 Fichiers Modifiés

### 1. Base de données
**Migration:** `database/migrations/2025_10_30_100638_add_address_and_tutor_to_students_table.php`
- Ajoute 3 nouvelles colonnes à la table `students`:
  - `address` (TEXT, nullable)
  - `tutor_name` (VARCHAR, nullable)
  - `tutor_phone` (VARCHAR, nullable)

### 2. Modèle
**Fichier:** `app/Models/Student.php`
- Ajout des nouveaux champs dans le tableau `$fillable`

### 3. Composant Livewire
**Fichier:** `app/Livewire/Students/StudentForm.php`
- Ajout de 3 nouvelles propriétés publiques: `$address`, `$tutor_name`, `$tutor_phone`
- Mise à jour de la méthode `load()` pour charger ces données
- Ajout de règles de validation dans la méthode `save()`

### 4. Vue Formulaire
**Fichier:** `resources/views/livewire/students/student-form.blade.php`
- Ajout d'une section "Informations de contact" avec le champ adresse
- Ajout d'une section "Informations du tuteur" avec nom et numéro
- Interface organisée avec des séparateurs visuels et des icônes

### 5. Vue Profil
**Fichier:** `resources/views/livewire/students/student-profile.blade.php`
- Affichage des informations du tuteur dans le profil de l'étudiant
- Organisation en colonnes pour une meilleure présentation
- Ajout d'icônes pour une meilleure UX

### 6. API Controller
**Fichier:** `app/Http/Controllers/API/StudentController.php`
- Ajout des nouveaux champs dans les réponses de toutes les méthodes
- Mise à jour des règles de validation dans `store()` et `update()`
- Support complet des nouveaux champs pour l'application mobile

## 🎨 Interface Utilisateur

### Formulaire d'ajout/modification
Le formulaire est maintenant divisé en sections claires:

1. **Informations de base** (déjà existantes)
   - Prénom, Nom, Email, Classe, Date de naissance, Matricule

2. **Informations de contact** (nouveau)
   - Adresse (textarea pour saisir l'adresse complète)

3. **Informations du tuteur** (nouveau)
   - Nom du tuteur
   - Numéro du tuteur

4. **Informations supplémentaires**
   - Note interne (JSON)

### Profil de l'étudiant
Le profil affiche maintenant:
- Les informations personnelles dans une colonne (incluant l'adresse si disponible)
- Les informations du tuteur dans une colonne séparée

## ✅ Validation
Les nouveaux champs ont les règles de validation suivantes:
- **address**: nullable, string, max 500 caractères
- **tutor_name**: nullable, string, max 255 caractères
- **tutor_phone**: nullable, string, max 20 caractères

Tous les champs sont optionnels pour permettre une saisie progressive des données.

## 🚀 Utilisation

### Créer un nouvel étudiant avec informations complètes:
1. Cliquer sur "Nouveau" dans la liste des étudiants
2. Remplir les informations de base
3. Ajouter l'adresse dans la section "Informations de contact"
4. Ajouter le nom et le numéro du tuteur dans la section "Informations du tuteur"
5. Enregistrer

### Modifier un étudiant existant:
1. Cliquer sur l'icône de modification dans la liste
2. Compléter ou modifier les nouveaux champs
3. Enregistrer

### Consulter le profil:
1. Cliquer sur l'icône de profil dans la liste
2. Les informations du tuteur apparaissent dans la section dédiée

### Utilisation via API (Mobile):
Les nouveaux champs sont automatiquement disponibles via l'API REST:

**GET** `/api/v1/students` - Liste avec informations complètes
**GET** `/api/v1/students/{id}` - Détails incluant adresse et tuteur
**POST** `/api/v1/students` - Créer avec tous les champs
**PUT** `/api/v1/students/{id}` - Mettre à jour les informations

Voir `API_DOCUMENTATION.md` pour les détails complets.

## 📊 Migration
Pour appliquer les changements à la base de données:
```bash
php artisan migrate
```

Pour annuler les changements:
```bash
php artisan migrate:rollback
```

## 🔄 Compatibilité
- Les étudiants existants ne sont pas affectés (les nouveaux champs sont NULL)
- Les nouveaux champs peuvent être remplis progressivement
- Aucune modification de code nécessaire dans d'autres parties du système

## 📝 Notes
- Les champs sont optionnels pour permettre une flexibilité maximale
- L'adresse est un champ texte pour accepter différents formats
- Le format du numéro de téléphone n'est pas strictement validé pour permettre différents formats internationaux
- Les informations du tuteur sont stockées directement dans la table students (pas de table séparée)

## 🎯 Améliorations futures possibles
- Validation du format de numéro de téléphone selon le pays
- Géolocalisation de l'adresse
- Possibilité d'ajouter plusieurs tuteurs
- Historique des modifications des informations de contact
- Export des coordonnées des tuteurs pour communication de masse

