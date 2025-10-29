# Guide Rapide - Gestion des Utilisateurs et Étudiants

## 🎯 Résumé des Nouvelles Fonctionnalités

Ce guide explique comment utiliser les nouvelles fonctionnalités de gestion des utilisateurs et la création automatique de comptes pour les étudiants.

---

## 📋 Table des Matières

1. [Gestion des Utilisateurs](#gestion-des-utilisateurs)
2. [Création d'Étudiants avec Compte](#création-détudiants-avec-compte)
3. [Mots de Passe](#mots-de-passe)
4. [FAQ](#faq)

---

## 👥 Gestion des Utilisateurs

### Accéder au Module

1. Connectez-vous en tant qu'**administrateur**
2. Cliquez sur **"Utilisateurs"** dans le menu latéral gauche
3. La page affiche la liste de tous les utilisateurs

### Créer un Nouvel Utilisateur

1. Cliquez sur le bouton **"+ Nouveau"**
2. Remplissez le formulaire :
   - **Nom complet** : Nom de l'utilisateur
   - **Email** : Adresse email (doit être unique)
   - **Mot de passe** : Minimum 8 caractères
   - **Confirmer le mot de passe** : Retapez le mot de passe
   - **Rôles** : Cochez un ou plusieurs rôles (admin, teacher, student)
3. Cliquez sur **"Enregistrer"**

### Modifier un Utilisateur

1. Cliquez sur l'icône **✏️ (crayon)** dans la colonne Actions
2. Modifiez les informations souhaitées
3. Pour changer le mot de passe :
   - Cochez **"Changer le mot de passe"**
   - Entrez le nouveau mot de passe et sa confirmation
4. Cliquez sur **"Enregistrer"**

### Supprimer un Utilisateur

1. Cliquez sur l'icône **🗑️ (corbeille)** dans la colonne Actions
2. Confirmez la suppression

⚠️ **Note** : Vous ne pouvez pas supprimer votre propre compte.

### Rechercher et Filtrer

- **Recherche** : Tapez dans le champ de recherche pour filtrer par nom ou email
- **Filtre par rôle** : Utilisez le menu déroulant pour afficher uniquement les utilisateurs d'un rôle spécifique

---

## 👨‍🎓 Création d'Étudiants avec Compte

### Processus Automatique

Lorsque vous créez un étudiant, le système crée **automatiquement** un compte utilisateur associé.

### Créer un Étudiant

1. Allez sur **"Étudiants"** dans le menu
2. Cliquez sur **"+ Nouveau"**
3. Remplissez le formulaire :
   - **Prénom** : Prénom de l'étudiant
   - **Nom** : Nom de famille
   - **Email** : ⭐ **NOUVEAU** - Adresse email pour le compte
   - **Classe** : Classe de l'étudiant (optionnel)
   - **Date de naissance** : Date au format JJ/MM/AAAA (optionnel)
   - **Matricule** : Numéro unique de l'étudiant
   - **Note interne** : Information complémentaire (optionnel)
4. Cliquez sur **"Enregistrer"**

### Ce qui se Passe Automatiquement

✅ Un compte utilisateur est créé avec :
- **Nom** : Prénom + Nom de l'étudiant
- **Email** : Email fourni dans le formulaire
- **Mot de passe** : `password123` (par défaut)
- **Rôle** : `student` (attribué automatiquement)

✅ Le compte est lié à l'étudiant via `user_id`

✅ Message de confirmation affiché :
```
Étudiant enregistré. Mot de passe par défaut: password123
```

### Modifier un Étudiant

1. Cliquez sur l'icône **✏️** dans la liste des étudiants
2. Modifiez les informations
3. Si vous changez le nom ou l'email :
   - Le compte utilisateur associé est **automatiquement mis à jour**
   - Le mot de passe reste inchangé

---

## 🔑 Mots de Passe

### Mot de Passe par Défaut pour les Étudiants

**Mot de passe** : `password123`

⚠️ **Important** : 
- Ce mot de passe doit être changé lors de la première connexion
- Communiquez ce mot de passe à l'étudiant de manière sécurisée
- Recommandez à l'étudiant de le changer immédiatement

### Changer le Mot de Passe d'un Utilisateur

**En tant qu'administrateur** :
1. Allez dans **"Utilisateurs"**
2. Cliquez sur **✏️** pour l'utilisateur concerné
3. Cochez **"Changer le mot de passe"**
4. Entrez le nouveau mot de passe (minimum 8 caractères)
5. Confirmez le mot de passe
6. Cliquez sur **"Enregistrer"**

**En tant qu'utilisateur** :
- Utilisez la fonctionnalité "Mot de passe oublié" (si implémentée)
- Ou contactez un administrateur

---

## ❓ FAQ

### Q1 : Puis-je créer un utilisateur sans être étudiant ?

**R** : Oui ! Allez dans **"Utilisateurs"** et créez un compte en assignant les rôles souhaités (admin, teacher, etc.).

### Q2 : Que se passe-t-il si je supprime un utilisateur lié à un étudiant ?

**R** : Le champ `user_id` de l'étudiant sera mis à `NULL` grâce à la contrainte `nullOnDelete`. L'étudiant reste dans le système mais n'a plus de compte associé.

### Q3 : Puis-je changer l'email d'un étudiant après création ?

**R** : Oui, modifiez l'étudiant et changez l'email. Le compte utilisateur associé sera automatiquement mis à jour.

### Q4 : Comment savoir quel utilisateur est lié à quel étudiant ?

**R** : 
- Allez sur le profil de l'étudiant (`/students/{id}`)
- Ou vérifiez le champ `user_id` dans la base de données

### Q5 : Puis-je supprimer mon propre compte d'administrateur ?

**R** : Non, le système vous empêche de supprimer votre propre compte pour éviter de vous bloquer l'accès.

### Q6 : Les étudiants existants ont-ils automatiquement un compte ?

**R** : Non, seuls les nouveaux étudiants créés après cette mise à jour auront un compte automatique. Pour les étudiants existants :
1. Créez manuellement un compte dans **"Utilisateurs"**
2. Ou modifiez l'étudiant et ajoutez/changez son email (si implémenté)

### Q7 : Combien de rôles puis-je assigner à un utilisateur ?

**R** : Autant que nécessaire. Un utilisateur peut avoir plusieurs rôles simultanément (ex: teacher + admin).

### Q8 : Le mot de passe est-il sécurisé ?

**R** : Oui, tous les mots de passe sont :
- Hashés avec `bcrypt` via Laravel
- Jamais stockés en clair dans la base de données
- Validés avec un minimum de 8 caractères

---

## 🔄 Workflow Recommandé

### Pour créer un nouvel étudiant

```
1. Cliquer sur "Étudiants" → "+ Nouveau"
2. Remplir toutes les informations (incluant email)
3. Enregistrer
4. Noter le mot de passe par défaut: password123
5. Communiquer les identifiants à l'étudiant de manière sécurisée
6. Demander à l'étudiant de changer son mot de passe
```

### Pour créer un enseignant ou administrateur

```
1. Cliquer sur "Utilisateurs" → "+ Nouveau"
2. Remplir nom, email, mot de passe
3. Cocher le(s) rôle(s) approprié(s) : teacher et/ou admin
4. Enregistrer
5. Communiquer les identifiants de manière sécurisée
```

---

## 📞 Support

Pour toute question ou problème :
- Consultez la documentation technique : `USER_MANAGEMENT_DOCUMENTATION.md`
- Consultez le changelog : `CHANGELOG_USER_MANAGEMENT.md`
- Contactez l'équipe de développement

---

## ✅ Checklist de Vérification

Avant de commencer à utiliser les nouvelles fonctionnalités :

- [ ] La base de données est à jour (migrations exécutées)
- [ ] Les rôles existent (admin, teacher, student)
- [ ] Vous êtes connecté en tant qu'administrateur
- [ ] Vous avez accès au menu "Utilisateurs"
- [ ] Vous pouvez créer un étudiant de test

---

**Version** : 1.0  
**Date** : 29 Octobre 2025  
**Auteur** : Équipe de Développement

