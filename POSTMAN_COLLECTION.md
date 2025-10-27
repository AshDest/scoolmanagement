# School Management API - Collection Postman

Voici une collection Postman complète pour tester l'API.

## Import dans Postman

1. Ouvrez Postman
2. Cliquez sur "Import"
3. Copiez-collez ce fichier JSON

```json
{
  "info": {
    "name": "School Management API",
    "description": "API complète pour l'application mobile de gestion scolaire",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "variable": [
    {
      "key": "base_url",
      "value": "https://school.ashuzadestin.space/api/v1",
      "type": "string"
    },
    {
      "key": "token",
      "value": "",
      "type": "string"
    }
  ],
  "auth": {
    "type": "bearer",
    "bearer": [
      {
        "key": "token",
        "value": "{{token}}",
        "type": "string"
      }
    ]
  },
  "item": [
    {
      "name": "Auth",
      "item": [
        {
          "name": "Login",
          "event": [
            {
              "listen": "test",
              "script": {
                "exec": [
                  "if (pm.response.code === 200) {",
                  "    var jsonData = pm.response.json();",
                  "    pm.collectionVariables.set('token', jsonData.data.token);",
                  "}"
                ]
              }
            }
          ],
          "request": {
            "method": "POST",
            "header": [],
            "body": {
              "mode": "raw",
              "raw": "{\n  \"email\": \"admin@example.com\",\n  \"password\": \"password\"\n}",
              "options": {
                "raw": {
                  "language": "json"
                }
              }
            },
            "url": {
              "raw": "{{base_url}}/login",
              "host": ["{{base_url}}"],
              "path": ["login"]
            }
          }
        },
        {
          "name": "Register",
          "request": {
            "method": "POST",
            "header": [],
            "body": {
              "mode": "raw",
              "raw": "{\n  \"name\": \"John Doe\",\n  \"email\": \"john@example.com\",\n  \"password\": \"password123\",\n  \"password_confirmation\": \"password123\"\n}",
              "options": {
                "raw": {
                  "language": "json"
                }
              }
            },
            "url": {
              "raw": "{{base_url}}/register",
              "host": ["{{base_url}}"],
              "path": ["register"]
            }
          }
        },
        {
          "name": "Get Profile",
          "request": {
            "method": "GET",
            "header": [],
            "url": {
              "raw": "{{base_url}}/profile",
              "host": ["{{base_url}}"],
              "path": ["profile"]
            }
          }
        },
        {
          "name": "Logout",
          "request": {
            "method": "POST",
            "header": [],
            "url": {
              "raw": "{{base_url}}/logout",
              "host": ["{{base_url}}"],
              "path": ["logout"]
            }
          }
        }
      ]
    },
    {
      "name": "Students",
      "item": [
        {
          "name": "List Students",
          "request": {
            "method": "GET",
            "header": [],
            "url": {
              "raw": "{{base_url}}/students?per_page=10",
              "host": ["{{base_url}}"],
              "path": ["students"],
              "query": [
                {
                  "key": "per_page",
                  "value": "10"
                }
              ]
            }
          }
        },
        {
          "name": "Get Student",
          "request": {
            "method": "GET",
            "header": [],
            "url": {
              "raw": "{{base_url}}/students/1",
              "host": ["{{base_url}}"],
              "path": ["students", "1"]
            }
          }
        },
        {
          "name": "Create Student",
          "request": {
            "method": "POST",
            "header": [],
            "body": {
              "mode": "raw",
              "raw": "{\n  \"first_name\": \"John\",\n  \"last_name\": \"Doe\",\n  \"email\": \"john.student@example.com\",\n  \"password\": \"password123\",\n  \"registration_number\": \"STU001\",\n  \"dob\": \"2005-01-15\",\n  \"class_id\": 1\n}",
              "options": {
                "raw": {
                  "language": "json"
                }
              }
            },
            "url": {
              "raw": "{{base_url}}/students",
              "host": ["{{base_url}}"],
              "path": ["students"]
            }
          }
        },
        {
          "name": "Get Student Grades",
          "request": {
            "method": "GET",
            "header": [],
            "url": {
              "raw": "{{base_url}}/students/1/grades",
              "host": ["{{base_url}}"],
              "path": ["students", "1", "grades"]
            }
          }
        },
        {
          "name": "Get Student Results",
          "request": {
            "method": "GET",
            "header": [],
            "url": {
              "raw": "{{base_url}}/students/1/results",
              "host": ["{{base_url}}"],
              "path": ["students", "1", "results"]
            }
          }
        }
      ]
    },
    {
      "name": "Courses",
      "item": [
        {
          "name": "List Courses",
          "request": {
            "method": "GET",
            "header": [],
            "url": {
              "raw": "{{base_url}}/courses",
              "host": ["{{base_url}}"],
              "path": ["courses"]
            }
          }
        },
        {
          "name": "Get Course",
          "request": {
            "method": "GET",
            "header": [],
            "url": {
              "raw": "{{base_url}}/courses/1",
              "host": ["{{base_url}}"],
              "path": ["courses", "1"]
            }
          }
        }
      ]
    },
    {
      "name": "Grades",
      "item": [
        {
          "name": "List Grades",
          "request": {
            "method": "GET",
            "header": [],
            "url": {
              "raw": "{{base_url}}/grades",
              "host": ["{{base_url}}"],
              "path": ["grades"]
            }
          }
        },
        {
          "name": "Create Grade",
          "request": {
            "method": "POST",
            "header": [],
            "body": {
              "mode": "raw",
              "raw": "{\n  \"enrollment_id\": 1,\n  \"score\": 85.5,\n  \"letter\": \"A\"\n}",
              "options": {
                "raw": {
                  "language": "json"
                }
              }
            },
            "url": {
              "raw": "{{base_url}}/grades",
              "host": ["{{base_url}}"],
              "path": ["grades"]
            }
          }
        }
      ]
    },
    {
      "name": "Dashboard",
      "item": [
        {
          "name": "Get Dashboard",
          "request": {
            "method": "GET",
            "header": [],
            "url": {
              "raw": "{{base_url}}/dashboard",
              "host": ["{{base_url}}"],
              "path": ["dashboard"]
            }
          }
        }
      ]
    }
  ]
}
```

## Variables d'environnement

- `base_url`: https://school.ashuzadestin.space/api/v1
- `token`: (sera automatiquement rempli après le login)

## Test rapide

1. Exécutez "Auth > Login" 
2. Le token sera automatiquement sauvegardé
3. Testez les autres endpoints protégés

