# API Documentation - School Management System

## Base URL
```
http://102.223.209.57/api/v1
```

## Authentication
Toutes les routes protégées nécessitent un token Bearer dans le header:
```
Authorization: Bearer {token}
```

---

## 📌 Authentication Endpoints

### 1. Login
**POST** `/login`

**Body:**
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Connexion réussie",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "user@example.com",
      "roles": ["student"],
      "permissions": []
    },
    "token": "1|xxxxxxxxxxx"
  }
}
```

### 2. Register
**POST** `/register`

**Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### 3. Get Profile
**GET** `/profile` 🔒

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "roles": ["student"],
    "student": {
      "id": 1,
      "first_name": "John",
      "last_name": "Doe",
      "registration_number": "STU001",
      "class": {
        "id": 1,
        "name": "Terminale A",
        "code": "TA"
      }
    }
  }
}
```

### 4. Update Profile
**PUT** `/profile` 🔒

**Body:**
```json
{
  "name": "John Updated",
  "email": "newemail@example.com"
}
```

### 5. Change Password
**POST** `/change-password` 🔒

**Body:**
```json
{
  "current_password": "oldpassword",
  "new_password": "newpassword123",
  "new_password_confirmation": "newpassword123"
}
```

### 6. Logout
**POST** `/logout` 🔒

---

## 📚 Students Endpoints

### 1. List Students
**GET** `/students` 🔒

**Query Parameters:**
- `class_id` - Filtrer par classe
- `search` - Rechercher par nom ou numéro d'inscription
- `per_page` - Nombre d'éléments par page (default: 15)
- `page` - Numéro de page

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "first_name": "John",
      "last_name": "Doe",
      "registration_number": "STU001",
      "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
      },
      "class": {
        "id": 1,
        "name": "Terminale A",
        "code": "TA"
      }
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

### 2. Get Student Details
**GET** `/students/{id}` 🔒

### 3. Create Student
**POST** `/students` 🔒 (Admin only)

**Body:**
```json
{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john@example.com",
  "password": "password123",
  "registration_number": "STU001",
  "dob": "2005-01-15",
  "class_id": 1
}
```

### 4. Update Student
**PUT** `/students/{id}` 🔒 (Admin only)

### 5. Delete Student
**DELETE** `/students/{id}` 🔒 (Admin only)

### 6. Get Student Enrollments
**GET** `/students/{id}/enrollments` 🔒

### 7. Get Student Grades
**GET** `/students/{id}/grades` 🔒

### 8. Get Student Results
**GET** `/students/{id}/results` 🔒

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "year": "2025",
      "term": "Semestre 1",
      "courses": [
        {
          "course": {
            "code": "MATH101",
            "title": "Mathématiques",
            "credits": 3
          },
          "grade": {
            "score": 85.5,
            "letter": "A"
          }
        }
      ],
      "total_credits": 18,
      "gpa": 3.5
    }
  ]
}
```

---

## 📖 Courses Endpoints

### 1. List Courses
**GET** `/courses` 🔒

**Query Parameters:**
- `search` - Rechercher par code ou titre
- `per_page` - Nombre par page

### 2. Get Course Details
**GET** `/courses/{id}` 🔒

### 3. Create Course
**POST** `/courses` 🔒 (Admin only)

**Body:**
```json
{
  "code": "MATH101",
  "title": "Mathématiques Avancées",
  "description": "Description du cours",
  "credits": 3
}
```

### 4. Update Course
**PUT** `/courses/{id}` 🔒 (Admin only)

### 5. Delete Course
**DELETE** `/courses/{id}` 🔒 (Admin only)

### 6. Get Course Offerings
**GET** `/courses/{id}/offerings` 🔒

### 7. Get Course Students
**GET** `/courses/{id}/students` 🔒

### 8. Get Course Teachers
**GET** `/courses/{id}/teachers` 🔒

---

## 📝 Grades Endpoints

### 1. List Grades
**GET** `/grades` 🔒

**Query Parameters:**
- `student_id` - Filtrer par étudiant

### 2. Create Grade
**POST** `/grades` 🔒 (Teacher/Admin only)

**Body:**
```json
{
  "enrollment_id": 1,
  "score": 85.5,
  "letter": "A"
}
```

### 3. Update Grade
**PUT** `/grades/{id}` 🔒 (Teacher/Admin only)

### 4. Delete Grade
**DELETE** `/grades/{id}` 🔒 (Admin only)

### 5. Bulk Create Grades
**POST** `/grades/bulk` 🔒 (Teacher/Admin only)

**Body:**
```json
{
  "grades": [
    {
      "enrollment_id": 1,
      "score": 85.5,
      "letter": "A"
    },
    {
      "enrollment_id": 2,
      "score": 90.0,
      "letter": "A+"
    }
  ]
}
```

### 6. Get Student Grades
**GET** `/grades/student/{student_id}` 🔒

### 7. Get Course Grades
**GET** `/grades/course/{course_id}` 🔒

---

## 📋 Enrollments Endpoints

### 1. List Enrollments
**GET** `/enrollments` 🔒

**Query Parameters:**
- `student_id` - Filtrer par étudiant
- `status` - Filtrer par statut (enrolled, completed, dropped, failed)

### 2. Create Enrollment
**POST** `/enrollments` 🔒 (Admin only)

**Body:**
```json
{
  "student_id": 1,
  "course_offering_id": 1,
  "status": "enrolled"
}
```

### 3. Update Enrollment
**PUT** `/enrollments/{id}` 🔒 (Admin only)

### 4. Delete Enrollment
**DELETE** `/enrollments/{id}` 🔒 (Admin only)

### 5. Bulk Enroll
**POST** `/enrollments/bulk` 🔒 (Admin only)

**Body:**
```json
{
  "student_ids": [1, 2, 3],
  "course_offering_id": 1
}
```

### 6. Update Enrollment Status
**PUT** `/enrollments/{id}/status` 🔒 (Admin only)

**Body:**
```json
{
  "status": "completed"
}
```

---

## 👨‍🏫 Teachers Endpoints

### 1. List Teachers
**GET** `/teachers` 🔒

### 2. Get Teacher Details
**GET** `/teachers/{id}` 🔒

### 3. Get Teacher Courses
**GET** `/teachers/{id}/courses` 🔒

### 4. Get Teacher Students
**GET** `/teachers/{id}/students` 🔒

---

## 🏫 Classes Endpoints

### 1. List Classes
**GET** `/classes` 🔒

### 2. Get Class Details
**GET** `/classes/{id}` 🔒

### 3. Create Class
**POST** `/classes` 🔒 (Admin only)

**Body:**
```json
{
  "name": "Terminale A",
  "code": "TA",
  "level": "Terminale"
}
```

### 4. Update Class
**PUT** `/classes/{id}` 🔒 (Admin only)

### 5. Delete Class
**DELETE** `/classes/{id}` 🔒 (Admin only)

### 6. Get Class Students
**GET** `/classes/{id}/students` 🔒

### 7. Get Class Courses
**GET** `/classes/{id}/courses` 🔒

---

## 📊 Dashboard Endpoints

### 1. Get Dashboard
**GET** `/dashboard` 🔒

Retourne des statistiques selon le rôle de l'utilisateur:
- **Admin**: Statistiques globales
- **Teacher**: Cours et étudiants
- **Student**: Notes et inscriptions

**Response (Student):**
```json
{
  "success": true,
  "data": {
    "student": {
      "id": 1,
      "name": "John Doe",
      "registration_number": "STU001",
      "class": {
        "name": "Terminale A",
        "code": "TA"
      }
    },
    "stats": {
      "total_enrollments": 8,
      "completed_courses": 2,
      "current_courses": 6,
      "gpa": 3.5
    },
    "recent_grades": [
      {
        "course": "Mathématiques",
        "score": 85.5,
        "letter": "A"
      }
    ]
  }
}
```

### 2. Get Dashboard Stats
**GET** `/dashboard/stats` 🔒 (Admin only)

---

## 📅 Attendance Endpoints

### 1. List Attendances
**GET** `/attendances` 🔒

**Query Parameters:**
- `student_id` - Filtrer par étudiant
- `course_offering_id` - Filtrer par session
- `date` - Filtrer par date

### 2. Create Attendance
**POST** `/attendances` 🔒 (Teacher/Admin only)

**Body:**
```json
{
  "student_id": 1,
  "course_offering_id": 1,
  "date": "2025-10-27",
  "status": "present"
}
```

**Status values:** `present`, `absent`, `late`, `excused`

### 3. Update Attendance
**PUT** `/attendances/{id}` 🔒 (Teacher/Admin only)

### 4. Delete Attendance
**DELETE** `/attendances/{id}` 🔒 (Admin only)

### 5. Bulk Create Attendances
**POST** `/attendances/bulk` 🔒 (Teacher/Admin only)

**Body:**
```json
{
  "attendances": [
    {
      "student_id": 1,
      "course_offering_id": 1,
      "date": "2025-10-27",
      "status": "present"
    },
    {
      "student_id": 2,
      "course_offering_id": 1,
      "date": "2025-10-27",
      "status": "absent"
    }
  ]
}
```

### 6. Get Student Attendances
**GET** `/attendances/student/{student_id}` 🔒

### 7. Get Course Attendances
**GET** `/attendances/course/{course_id}` 🔒

---

## 📚 Assignments Endpoints

### 1. List Assignments
**GET** `/assignments` 🔒

### 2. Get Assignment Details
**GET** `/assignments/{id}` 🔒

### 3. Create Assignment
**POST** `/assignments` 🔒 (Teacher/Admin only)

**Body:**
```json
{
  "course_offering_id": 1,
  "title": "Devoir de Mathématiques",
  "description": "Résoudre les exercices 1-10",
  "due_date": "2025-11-15",
  "max_score": 100
}
```

### 4. Update Assignment
**PUT** `/assignments/{id}` 🔒 (Teacher/Admin only)

### 5. Delete Assignment
**DELETE** `/assignments/{id}` 🔒 (Admin only)

### 6. Get Course Assignments
**GET** `/assignments/course/{course_id}` 🔒

### 7. Get Student Assignments
**GET** `/assignments/student/{student_id}` 🔒

### 8. Submit Assignment
**POST** `/assignments/{id}/submit` 🔒 (Student only)

**Body:**
```json
{
  "submission_content": "Mon travail...",
  "files": []
}
```

---

## 📖 Course Offerings Endpoints

### 1. List Course Offerings
**GET** `/course-offerings` 🔒

**Query Parameters:**
- `course_id` - Filtrer par cours
- `year` - Filtrer par année
- `term` - Filtrer par semestre

### 2. Get Offering Details
**GET** `/course-offerings/{id}` 🔒

### 3. Create Course Offering
**POST** `/course-offerings` 🔒 (Admin only)

**Body:**
```json
{
  "course_id": 1,
  "year": 2025,
  "term": "Semestre 1",
  "schedule": {
    "days": ["Lundi", "Mercredi"],
    "time": "08:00-10:00"
  }
}
```

### 4. Update Course Offering
**PUT** `/course-offerings/{id}` 🔒 (Admin only)

### 5. Delete Course Offering
**DELETE** `/course-offerings/{id}` 🔒 (Admin only)

### 6. Get Offering Enrollments
**GET** `/course-offerings/{id}/enrollments` 🔒

### 7. Get Offering Grades
**GET** `/course-offerings/{id}/grades` 🔒

---

## ⚠️ Error Responses

### Validation Error (422)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

### Unauthorized (401)
```json
{
  "success": false,
  "message": "Email ou mot de passe incorrect"
}
```

### Forbidden (403)
```json
{
  "success": false,
  "message": "Accès non autorisé"
}
```

### Not Found (404)
```json
{
  "success": false,
  "message": "Ressource non trouvée"
}
```

---

## 🔐 Roles & Permissions

### Roles disponibles:
- `admin` - Accès complet
- `teacher` - Gestion des notes et présences
- `student` - Consultation uniquement

### Permissions vérifiées automatiquement selon le rôle

---

## 📱 Exemples d'utilisation (Flutter/React Native)

### Flutter
```dart
// Login
final response = await http.post(
  Uri.parse('http://102.223.209.57/api/v1/login'),
  headers: {'Content-Type': 'application/json'},
  body: json.encode({
    'email': 'user@example.com',
    'password': 'password123'
  }),
);

// Authenticated Request
final token = 'YOUR_TOKEN';
final response = await http.get(
  Uri.parse('http://102.223.209.57/api/v1/profile'),
  headers: {
    'Authorization': 'Bearer $token',
    'Content-Type': 'application/json'
  },
);
```

### React Native (Axios)
```javascript
// Login
const response = await axios.post('http://102.223.209.57/api/v1/login', {
  email: 'user@example.com',
  password: 'password123'
});

// Authenticated Request
const token = response.data.data.token;
axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

const profile = await axios.get('http://102.223.209.57/api/v1/profile');
```

---

## 🚀 Test avec Postman/Insomnia

1. Importer la collection en utilisant l'URL de base
2. Créer une variable d'environnement `token`
3. Après le login, sauvegarder le token
4. Utiliser le token dans le header de toutes les requêtes protégées

---

## 📞 Support

Pour toute question ou problème, contactez l'équipe de développement.

