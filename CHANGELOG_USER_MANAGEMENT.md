# Changelog - Gestion des Utilisateurs

## Date: 29 Octobre 2025

### ✨ Nouvelles Fonctionnalités

#### 1. Module de Gestion des Utilisateurs

**Fichiers créés**:
- `app/Livewire/Users/UserIndex.php` - Composant liste des utilisateurs
- `app/Livewire/Users/UserForm.php` - Composant formulaire utilisateur
- `resources/views/livewire/users/user-index.blade.php` - Vue liste
- `resources/views/livewire/users/user-form.blade.php` - Vue formulaire modal

**Fonctionnalités**:
- ✅ Créer des utilisateurs avec nom, email, mot de passe
- ✅ Modifier des utilisateurs existants
- ✅ Supprimer des utilisateurs (sauf soi-même)
- ✅ Recherche par nom ou email
- ✅ Filtrage par rôle
- ✅ Gestion des rôles (checkboxes multiples)
- ✅ Changement optionnel de mot de passe lors de la modification
- ✅ Pagination (10 par page)

#### 2. Création Automatique de Compte Utilisateur pour les Étudiants

**Fichier modifié**:
- `app/Livewire/Students/StudentForm.php`

**Changements**:
- Ajout du champ `email` au formulaire
- Ajout du champ `user_id` dans le composant
- Création automatique d'un compte `User` lors de l'ajout d'un étudiant
- Mot de passe par défaut: `password123`
- Attribution automatique du rôle `student`
- Mise à jour du nom et email de l'utilisateur lors de la modification

**Fichier modifié**:
- `resources/views/livewire/students/student-form.blade.php`

**Changements**:
- Ajout du champ Email dans le formulaire
- Message informatif sur le mot de passe par défaut

### 🔧 Modifications

#### Routes
**Fichier**: `routes/web.php`
- Ajout de l'import `use App\Livewire\Users\UserIndex;`
- Ajout de la route `Route::get('/users', UserIndex::class)->name('users.index');`

#### Navigation
**Fichier**: `resources/views/layouts/partials/sidebar.blade.php`
- Ajout du menu "Utilisateurs" avec icône `bi-person-circle`
- Placement après le menu "Rôles"

### 📋 Validations Implémentées

**UserForm**:
- Nom: requis, string, max 255 caractères
- Email: requis, email valide, unique dans la table users
- Mot de passe: requis lors de la création, min 8 caractères, avec confirmation
- Rôles: array

**StudentForm**:
- Email: requis, email valide, unique dans la table users
- Prénom/Nom: requis, string, max 255 caractères
- Matricule: requis, unique dans la table students
- Date de naissance: date valide (optionnelle)
- Classe: doit exister dans la table classes (optionnelle)

### 🔒 Sécurité

- ✅ Mots de passe hashés avec `Hash::make()`
- ✅ Protection contre la suppression de son propre compte
- ✅ Validation des emails uniques
- ✅ Middleware `ensure.role:admin` pour l'accès au module
- ✅ Protection CSRF automatique (Laravel)

### 📚 Documentation

**Fichiers créés**:
- `USER_MANAGEMENT_DOCUMENTATION.md` - Documentation complète du module

### ⚠️ Notes Importantes

1. **Mot de passe par défaut**: Les étudiants reçoivent le mot de passe `password123` 
   - À changer lors de la première connexion
   - Recommandation: Implémenter un système de changement forcé

2. **Migration nécessaire**: Vérifier que la colonne `user_id` existe dans la table `students`
   ```sql
   ALTER TABLE students ADD COLUMN user_id BIGINT UNSIGNED NULL;
   ALTER TABLE students ADD FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL;
   ```

3. **Rôles requis**: Le rôle `student` doit exister dans la table `roles`
   ```php
   Role::create(['name' => 'student']);
   ```

### 🎨 Interface Utilisateur

- Design cohérent avec le reste de l'application
- Utilisation de Bootstrap 5
- Icônes Bootstrap Icons
- Modals pour les formulaires
- Messages flash pour les confirmations
- Badges colorés pour les rôles
- Responsive design

### 🔄 Prochaines Étapes Recommandées

1. ☐ Créer une migration pour ajouter `user_id` à la table `students` si manquante
2. ☐ Implémenter le système de changement de mot de passe forcé
3. ☐ Ajouter un système d'envoi d'email avec les identifiants
4. ☐ Implémenter la réinitialisation de mot de passe
5. ☐ Ajouter un audit log pour les actions sur les utilisateurs
6. ☐ Créer des tests unitaires et fonctionnels

### 📝 Utilisation

**Pour créer un utilisateur**:
1. Aller sur `/users`
2. Cliquer sur "Nouveau"
3. Remplir le formulaire (nom, email, mot de passe, rôles)
4. Cliquer sur "Enregistrer"

**Pour créer un étudiant avec compte**:
1. Aller sur `/students`
2. Cliquer sur "Nouveau"
3. Remplir le formulaire incluant l'email
4. Le système crée automatiquement un compte avec mot de passe `password123`
5. Le rôle `student` est automatiquement attribué

**Pour modifier un utilisateur**:
1. Cliquer sur l'icône de modification
2. Modifier les champs souhaités
3. Cocher "Changer le mot de passe" si nécessaire
4. Cliquer sur "Enregistrer"

**Pour supprimer un utilisateur**:
1. Cliquer sur l'icône de suppression
2. Confirmer la suppression
3. Note: Impossible de supprimer son propre compte

