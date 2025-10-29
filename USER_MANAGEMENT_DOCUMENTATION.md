# Gestion des Utilisateurs - Documentation

## Vue d'ensemble

Le système de gestion des utilisateurs permet aux administrateurs de créer, modifier, visualiser et supprimer les comptes utilisateurs de l'application. Cette fonctionnalité intègre également la gestion des rôles via Spatie Laravel Permission.

## Fonctionnalités

### 1. Liste des utilisateurs (`/users`)

**Composant**: `App\Livewire\Users\UserIndex`

**Fonctionnalités principales**:
- Affichage paginé de tous les utilisateurs (10 par page)
- Recherche en temps réel par nom ou email
- Filtrage par rôle
- Actions: Modifier, Supprimer
- Protection contre la suppression de son propre compte

**Colonnes affichées**:
- ID
- Nom complet
- Email
- Rôles (badges colorés)
- Actions

### 2. Création d'utilisateurs

**Modal**: Formulaire accessible via le bouton "Nouveau"

**Champs requis**:
- **Nom complet**: Le nom complet de l'utilisateur
- **Email**: Adresse email unique
- **Mot de passe**: Minimum 8 caractères
- **Confirmation du mot de passe**: Doit correspondre au mot de passe
- **Rôles**: Sélection multiple via checkboxes

**Validation**:
- Email unique dans la base de données
- Mot de passe minimum 8 caractères
- Confirmation de mot de passe obligatoire

### 3. Modification d'utilisateurs

**Modal**: Même formulaire que la création

**Comportement spécial**:
- Le mot de passe n'est PAS obligatoire lors de la modification
- Checkbox "Changer le mot de passe" pour mettre à jour le mot de passe
- Les rôles peuvent être mis à jour via les checkboxes

### 4. Suppression d'utilisateurs

**Comportement**:
- Confirmation requise avant suppression
- Protection: Un utilisateur ne peut pas supprimer son propre compte
- Message d'erreur affiché si tentative de suppression de son propre compte

## Intégration avec les Étudiants

### Création automatique de compte utilisateur

Lorsqu'un étudiant est créé, le système crée automatiquement:

**Composant**: `App\Livewire\Students\StudentForm`

**Processus**:
1. **Champ Email ajouté au formulaire étudiant**
2. **Création automatique du compte User**:
   - Name: Prénom + Nom de l'étudiant
   - Email: Email fourni dans le formulaire
   - Password: `password123` (mot de passe par défaut)
3. **Attribution automatique du rôle "student"**
4. **Liaison**: Le user_id est automatiquement associé à l'étudiant

**Message de confirmation**: 
```
Étudiant enregistré. Mot de passe par défaut: password123
```

### Modification d'étudiant

Lors de la modification d'un étudiant existant:
- Le nom et l'email du compte utilisateur associé sont mis à jour
- Le mot de passe reste inchangé
- Les rôles restent inchangés

## Structure des fichiers

```
app/
├── Livewire/
│   ├── Users/
│   │   ├── UserIndex.php      # Liste et gestion des utilisateurs
│   │   └── UserForm.php       # Formulaire création/modification
│   └── Students/
│       └── StudentForm.php    # Modifié pour créer des comptes
│
resources/views/livewire/
├── users/
│   ├── user-index.blade.php   # Vue liste des utilisateurs
│   └── user-form.blade.php    # Vue formulaire modal
└── students/
    └── student-form.blade.php # Modifié avec champ email
```

## Routes

```php
Route::middleware(['auth','ensure.role:admin'])->group(function () {
    Route::get('/users', UserIndex::class)->name('users.index');
    // ... autres routes
});
```

## Navigation

Menu ajouté dans la sidebar après "Rôles":
- Icône: `bi-person-circle`
- Label: "Utilisateurs"
- Route: `users.index`
- Accessible uniquement aux administrateurs

## Permissions requises

- **Accès**: Rôle `admin` requis
- **Suppression**: Ne peut pas supprimer son propre compte

## Modèles et Relations

### User Model
```php
class User extends Authenticatable
{
    use HasRoles, HasApiTokens;
    
    protected $fillable = ['name', 'email', 'password', 'profile'];
    
    public function student(): HasOne {
        return $this->hasOne(Student::class);
    }
}
```

### Student Model
```php
class Student extends Model
{
    protected $fillable = [
        'user_id',    // Nouveau champ
        'class_id',
        'first_name',
        'last_name',
        'dob',
        'registration_number',
        'extra'
    ];
    
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
```

## Sécurité

1. **Hachage des mots de passe**: Tous les mots de passe sont hachés avec `Hash::make()`
2. **Validation des emails**: Email unique vérifié au niveau de la base de données
3. **Protection CSRF**: Géré automatiquement par Laravel
4. **Authorization**: Middleware `ensure.role:admin`

## Mot de passe par défaut

⚠️ **Important**: Le mot de passe par défaut `password123` doit être changé par l'utilisateur lors de sa première connexion.

**Recommandation**: Implémenter un système de "force password change" à la première connexion.

## Messages de feedback

- ✅ "Utilisateur enregistré avec succès."
- ✅ "Utilisateur supprimé avec succès."
- ✅ "Étudiant enregistré. Mot de passe par défaut: password123"
- ❌ "Vous ne pouvez pas supprimer votre propre compte."

## Technologies utilisées

- **Livewire 3**: Composants réactifs
- **Bootstrap 5**: Interface utilisateur
- **Spatie Laravel Permission**: Gestion des rôles
- **Laravel 11**: Framework backend

