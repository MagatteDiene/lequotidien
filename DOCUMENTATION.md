# Documentation — Le•Quotidien

Plateforme d'actualité développée avec Laravel 12 et PostgreSQL.

---

## Table des matières

1. [Installation et configuration](#1-installation-et-configuration)
2. [Architecture de l'application](#2-architecture-de-lapplication)
3. [Rôles et accès](#3-rôles-et-accès)
4. [Interface web](#4-interface-web)
5. [Service web REST](#5-service-web-rest)
6. [Service web SOAP](#6-service-web-soap)
7. [Gestion des tokens API](#7-gestion-des-tokens-api)

---

## 1. Installation et configuration

### Prérequis
- PHP 8.2+ avec extensions : `pdo_pgsql`, `soap`, `simplexml`, `mbstring`
- PostgreSQL 13+
- Composer
- XAMPP (ou serveur Apache équivalent)

### Étapes

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
```

### Variables d'environnement (.env)

```
APP_URL=http://localhost/newsite/public
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=newsite
DB_USERNAME=postgres
DB_PASSWORD=votre_mot_de_passe
```

### Comptes par défaut (seeder)

| Email                  | Mot de passe | Rôle           |
|------------------------|--------------|----------------|
| admin@news.com         | 123          | administrateur |
| magatte@news.com       | 123          | editeur        |

---

## 2. Architecture de l'application

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AccueilController.php       — pages publiques
│   │   ├── ProfileController.php       — profil utilisateur
│   │   ├── SoapController.php          — endpoint SOAP
│   │   ├── Api/
│   │   │   └── ArticleController.php   — API REST
│   │   └── Admin/
│   │       ├── ArticleController.php
│   │       ├── CategorieController.php
│   │       ├── UserController.php
│   │       └── ApiTokenController.php
│   └── Middleware/
│       ├── CheckRole.php               — contrôle des rôles
│       └── VerifyApiToken.php          — auth token REST
├── Models/
│   ├── User.php
│   ├── Article.php
│   ├── Categorie.php
│   └── ApiToken.php
└── Services/
    └── SoapUserService.php             — logique métier SOAP
```

---

## 3. Rôles et accès

| Fonctionnalité                        | Public | Éditeur | Administrateur |
|---------------------------------------|--------|---------|----------------|
| Lire les articles                     | ✅     | ✅      | ✅             |
| Naviguer par catégorie                | ✅     | ✅      | ✅             |
| Gérer articles et catégories          | ❌     | ✅      | ✅             |
| Gérer les utilisateurs                | ❌     | ❌      | ✅             |
| Gérer les tokens API                  | ❌     | ❌      | ✅             |
| Modifier son profil                   | ❌     | ✅      | ✅             |

---

## 4. Interface web

### Pages publiques

| URL                          | Description                                  |
|------------------------------|----------------------------------------------|
| `/`                          | Accueil — liste des articles avec pagination |
| `/article/{slug}`            | Détail d'un article                          |
| `/categorie/{slug}`          | Articles d'une catégorie                     |

### Espace d'administration (`/admin`)

| URL                          | Description               | Rôle requis    |
|------------------------------|---------------------------|----------------|
| `/admin`                     | Tableau de bord           | editeur+       |
| `/admin/articles`            | Gestion des articles      | editeur+       |
| `/admin/categories`          | Gestion des catégories    | editeur+       |
| `/admin/users`               | Gestion des utilisateurs  | administrateur |
| `/admin/tokens`              | Gestion des tokens API    | administrateur |
| `/profile`                   | Profil personnel          | tout connecté  |

---

## 5. Service web REST

### Base URL

```
GET/POST http://localhost/newsite/public/api/...
```

### Authentification

Chaque requête doit fournir un token valide, soit via un header HTTP, soit via un paramètre d'URL.

**Header (recommandé) :**
```
X-Api-Token: votre_token_ici
```

**Paramètre d'URL :**
```
?token=votre_token_ici
```

### Format de réponse

Toutes les réponses sont retournées en **JSON** par défaut.
Pour obtenir du **XML**, ajouter `?format=xml` à l'URL.

---

### Endpoints

#### `GET /api/articles`
Retourne la liste de tous les articles publiés.

**Exemple de requête :**
```http
GET /api/articles HTTP/1.1
X-Api-Token: abc123...
```

**Réponse JSON :**
```json
{
  "articles": [
    {
      "id": 1,
      "titre": "Titre de l'article",
      "slug": "titre-de-l-article",
      "resume": "Résumé court...",
      "categorie": "Politique",
      "auteur": "Jean Dupont",
      "publie_le": "2026-04-30 10:00:00"
    }
  ]
}
```

**Réponse XML** (`?format=xml`) :
```xml
<?xml version="1.0"?>
<articles>
  <articles>
    <item>
      <id>1</id>
      <titre>Titre de l'article</titre>
      <slug>titre-de-l-article</slug>
      <resume>Résumé court...</resume>
      <categorie>Politique</categorie>
      <auteur>Jean Dupont</auteur>
      <publie_le>2026-04-30 10:00:00</publie_le>
    </item>
  </articles>
</articles>
```

---

#### `GET /api/articles/categories`
Retourne tous les articles regroupés par catégorie.

**Exemple de requête :**
```http
GET /api/articles/categories?format=json HTTP/1.1
X-Api-Token: abc123...
```

**Réponse JSON :**
```json
{
  "categories": [
    {
      "id": 1,
      "nom": "Politique",
      "slug": "politique",
      "articles": [
        {
          "id": 1,
          "titre": "...",
          "slug": "...",
          "resume": "...",
          "categorie": "Politique",
          "auteur": "Jean Dupont",
          "publie_le": "2026-04-30 10:00:00"
        }
      ]
    }
  ]
}
```

---

#### `GET /api/articles/categorie/{slug}`
Retourne les articles d'une catégorie spécifique.

**Paramètre d'URL :**
- `slug` (string) — le slug de la catégorie (ex: `politique`, `sport`)

**Exemple :**
```http
GET /api/articles/categorie/politique HTTP/1.1
X-Api-Token: abc123...
```

**Réponse JSON :**
```json
{
  "categorie": "politique",
  "articles": [ ... ]
}
```

---

### Codes d'erreur REST

| Code HTTP | Signification                          |
|-----------|----------------------------------------|
| 200       | Succès                                 |
| 401       | Token manquant ou invalide             |

**Réponse d'erreur :**
```json
{
  "succes": false,
  "message": "Token invalide ou expiré."
}
```

---

## 6. Service web SOAP

### Endpoint et WSDL

| Type   | URL                                           |
|--------|-----------------------------------------------|
| WSDL   | `GET http://localhost/newsite/public/soap`    |
| Appel  | `POST http://localhost/newsite/public/soap`   |

Le WSDL est généré dynamiquement à chaque requête GET sur l'endpoint.

### Namespace

```
http://lequotidien.local/soap/users
```

### Authentification SOAP

Les opérations de gestion des utilisateurs (lister, ajouter, modifier, supprimer) requièrent un **token** passé en premier paramètre. L'opération d'authentification ne nécessite pas de token.

---

### Opérations

#### `listerUtilisateurs`
Liste tous les utilisateurs du système.

**Paramètres :**
| Nom   | Type   | Description         |
|-------|--------|---------------------|
| token | string | Token d'API valide  |

**Réponse (ReponseData) :**
| Champ   | Type    | Description                              |
|---------|---------|------------------------------------------|
| succes  | boolean | true si l'opération a réussi             |
| message | string  | Message informatif ou d'erreur           |
| data    | string  | JSON array des utilisateurs (si succès)  |

**Format du champ `data` :**
```json
[
  {"id": 1, "nom": "Admin", "email": "admin@news.com", "role": "administrateur", "actif": true}
]
```

---

#### `ajouterUtilisateur`
Crée un nouvel utilisateur.

**Paramètres :**
| Nom      | Type   | Description                            |
|----------|--------|----------------------------------------|
| token    | string | Token d'API valide                     |
| nom      | string | Nom complet                            |
| email    | string | Adresse email (unique)                 |
| password | string | Mot de passe en clair (sera hashé)     |
| role     | string | `editeur` ou `administrateur`          |

**Réponse (ReponseSimple) :**
| Champ   | Type    | Description                  |
|---------|---------|------------------------------|
| succes  | boolean | true si créé avec succès     |
| message | string  | Message avec l'ID créé       |

---

#### `modifierUtilisateur`
Modifie un utilisateur existant.

**Paramètres :**
| Nom   | Type   | Description              |
|-------|--------|--------------------------|
| token | string | Token d'API valide       |
| id    | int    | ID de l'utilisateur      |
| nom   | string | Nouveau nom              |
| email | string | Nouvel email             |
| role  | string | `editeur` ou `administrateur` |

**Réponse (ReponseSimple)**

---

#### `supprimerUtilisateur`
Supprime un utilisateur (ses articles sont conservés avec auteur = null).

**Paramètres :**
| Nom   | Type   | Description              |
|-------|--------|--------------------------|
| token | string | Token d'API valide       |
| id    | int    | ID de l'utilisateur      |

**Réponse (ReponseSimple)**

**Contraintes :**
- Impossible de supprimer le seul administrateur du système.

---

#### `authentifierUtilisateur`
Authentifie un utilisateur par email et mot de passe. **Ne requiert pas de token.**

**Paramètres :**
| Nom      | Type   | Description      |
|----------|--------|------------------|
| email    | string | Email            |
| password | string | Mot de passe     |

**Réponse (ReponseData) :**
| Champ   | Type    | Description                             |
|---------|---------|-----------------------------------------|
| succes  | boolean | true si authentification réussie        |
| message | string  | Message de résultat                     |
| data    | string  | JSON de l'utilisateur (si succès)       |

**Format du champ `data` :**
```json
{"id": 1, "nom": "Admin", "email": "admin@news.com", "role": "administrateur"}
```

---

### Exemple d'appel SOAP en PHP

```php
<?php
$wsdl   = 'http://localhost/newsite/public/soap';
$token  = 'votre_token_ici';

$client = new SoapClient($wsdl, [
    'cache_wsdl' => WSDL_CACHE_NONE,
    'trace'      => true,
    'encoding'   => 'UTF-8',
]);

// Lister les utilisateurs
$result = $client->listerUtilisateurs($token);
if ($result->succes) {
    $users = json_decode($result->data, true);
    print_r($users);
}

// Authentifier un utilisateur
$auth = $client->authentifierUtilisateur('admin@news.com', '123');
if ($auth->succes) {
    $user = json_decode($auth->data, true);
    echo "Connecté : " . $user['nom'];
}

// Ajouter un utilisateur
$res = $client->ajouterUtilisateur($token, 'Marie Curie', 'marie@news.com', 'motdepasse', 'editeur');
echo $res->message;

// Modifier un utilisateur
$res = $client->modifierUtilisateur($token, 3, 'Marie Martin', 'marie@news.com', 'editeur');
echo $res->message;

// Supprimer un utilisateur
$res = $client->supprimerUtilisateur($token, 3);
echo $res->message;
```

---

### Exemple d'appel SOAP avec curl

```bash
curl -X POST http://localhost/newsite/public/soap \
  -H "Content-Type: text/xml; charset=utf-8" \
  -H "SOAPAction: authentifierUtilisateur" \
  -d '<?xml version="1.0" encoding="UTF-8"?>
<SOAP-ENV:Envelope
  xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
  xmlns:ns="http://lequotidien.local/soap/users">
  <SOAP-ENV:Body>
    <ns:authentifierUtilisateur>
      <email>admin@news.com</email>
      <password>123</password>
    </ns:authentifierUtilisateur>
  </SOAP-ENV:Body>
</SOAP-ENV:Envelope>'
```

---

## 7. Gestion des tokens API

Les tokens sont gérés depuis l'interface d'administration, accessible uniquement aux **administrateurs**.

**Accès :** `/admin/tokens`

### Créer un token
1. Se connecter en tant qu'administrateur
2. Aller dans **Tokens API** dans le menu latéral
3. Renseigner un nom descriptif (ex: "Application mobile", "Intégration partenaire")
4. Cliquer sur **Générer le token**
5. **Copier immédiatement la valeur du token** — elle ne sera plus accessible après rechargement

### Propriétés d'un token

| Propriété  | Description                                  |
|------------|----------------------------------------------|
| Nom        | Label descriptif pour identifier l'usage     |
| Token      | Chaîne aléatoire de 60 caractères            |
| Expiration | 6 mois après la création                     |
| Statut     | Actif / Désactivé (modifiable à tout moment) |

### Révoquer ou désactiver
- **Désactiver** : basculer le statut depuis l'interface (le token reste en base)
- **Supprimer** : supprimer définitivement depuis l'interface

Un token est invalide si :
- Son statut est **Désactivé**
- Sa date d'expiration est **dépassée**
- Il a été **supprimé**

---

## Notes techniques

- **Extension PHP soap** requise sur le serveur (`php.ini` : `extension=soap`)
- **Extension PHP simplexml** requise pour les réponses XML de l'API REST
- Le fichier WSDL est mis en cache dans `storage/app/users.wsdl` — ce répertoire doit être accessible en écriture par le serveur web
- Les routes SOAP (`/soap`) sont exemptées de la vérification CSRF de Laravel
- Les routes REST (`/api/...`) appliquent automatiquement le middleware `throttle:60,1` (60 requêtes/minute par IP)
