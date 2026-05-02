<img width="1892" height="1027" alt="image" src="https://github.com/user-attachments/assets/5bb1dc9e-667d-4733-9afa-048903b0df86" />
<img width="1901" height="1025" alt="image" src="https://github.com/user-attachments/assets/b9418272-c45c-4d3f-a9fb-fe87f5e85ff4" />
<img width="1817" height="1025" alt="image" src="https://github.com/user-attachments/assets/d78da029-3da3-4834-bbef-399824baf3ea" />

# Le•Quotidien

> Projet réalisé dans le cadre de l'examen d'**Architecture Logicielle**.

Application de gestion d'un journal en ligne, développée avec **Laravel 12** et **PostgreSQL**.

## Fonctionnalités

- Interface publique : lecture des articles, navigation par catégorie
- Back-office : gestion des articles, catégories, utilisateurs et tokens API
- Service REST : exposition des articles en JSON ou XML
- Service SOAP : gestion des utilisateurs pour applications clientes
- Authentification par rôles : `editeur` et `administrateur`

## Installation locale

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Configurer `.env` avec les paramètres de base de données PostgreSQL.

## Services web

**REST** — base URL : `/api`  
Authentification : en-tête `X-Api-Token` ou paramètre `?token=`

| Endpoint | Description |
|---|---|
| GET /api/articles | Tous les articles publiés |
| GET /api/articles/categories | Articles groupés par catégorie |
| GET /api/articles/categorie/{slug} | Articles d'une catégorie |

Ajouter `?format=xml` pour obtenir la réponse en XML.

**SOAP** — endpoint : `/soap` (WSDL disponible en GET)  
Méthodes : `authentifierUtilisateur`, `listerUtilisateurs`, `ajouterUtilisateur`, `modifierUtilisateur`, `supprimerUtilisateur`

Voir `CLIENT_DEVELOPER_GUIDE.txt` pour le détail d'intégration.


## Documentation

- `DOCUMENTATION.txt` — documentation technique complète
- `CLIENT_DEVELOPER_GUIDE.txt` — guide d'intégration du service SOAP
