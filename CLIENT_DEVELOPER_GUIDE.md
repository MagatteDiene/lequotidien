# Guide développeur client — Le•Quotidien Web Services

Ce document contient tout ce dont vous avez besoin pour développer une application cliente
qui consomme les services web de la plateforme **Le•Quotidien**.

---

## 1. Vue d'ensemble

L'application expose deux types de services web :

| Type | URL de base                          | Usage                        |
|------|--------------------------------------|------------------------------|
| SOAP | `http://<SERVEUR>/soap`              | Gestion des utilisateurs     |
| REST | `http://<SERVEUR>/api/articles`      | Consultation des articles    |

> Remplacez `<SERVEUR>` par l'adresse réelle fournie par l'administrateur.
> En local XAMPP : `http://localhost/newsite/public`

---

## 2. Authentification

### 2.1 Token API (pour les appels REST et SOAP de gestion)

Chaque appel aux services protégés nécessite un **token API** généré par un administrateur.

- **REST** : passer le token dans le header `X-Api-Token: <token>`
- **SOAP** : passer le token en premier paramètre de chaque méthode

### 2.2 Authentification utilisateur (SOAP)

Avant toute opération de gestion, votre application doit vérifier les droits de l'utilisateur
en appelant `authentifierUtilisateur(email, password)`. Cette méthode ne requiert pas de token.

---

## 3. Service SOAP — Gestion des utilisateurs

### Endpoints

| Action       | URL                                  |
|--------------|--------------------------------------|
| WSDL         | `GET http://<SERVEUR>/soap`          |
| Appels SOAP  | `POST http://<SERVEUR>/soap`         |

### Opérations disponibles

#### `authentifierUtilisateur(email, password)` — sans token

```
Entrée  : email (string), password (string)
Sortie  : ReponseData { succes, message, data (JSON string) }

data (si succes=true) :
  { "id": 1, "nom": "Admin", "email": "admin@news.com", "role": "administrateur" }
```

> **Important** : Vérifiez que `role == "administrateur"` pour autoriser l'accès aux fonctions de gestion.

#### `listerUtilisateurs(token)`

```
Entrée  : token (string)
Sortie  : ReponseData { succes, message, data (JSON array string) }

data (si succes=true) :
  [{ "id": 1, "nom": "...", "email": "...", "role": "...", "actif": true }, ...]
```

#### `ajouterUtilisateur(token, nom, email, password, role)`

```
Entrée  : token, nom, email, password, role ("editeur" | "administrateur")
Sortie  : ReponseSimple { succes, message }
```

#### `modifierUtilisateur(token, id, nom, email, role)`

```
Entrée  : token, id (int), nom, email, role ("editeur" | "administrateur")
Sortie  : ReponseSimple { succes, message }
```

#### `supprimerUtilisateur(token, id)`

```
Entrée  : token, id (int)
Sortie  : ReponseSimple { succes, message }
```

### Codes d'erreur courants

| succes | message                                    | Cause                          |
|--------|--------------------------------------------|--------------------------------|
| false  | Token invalide ou expiré.                  | Token incorrect ou périmé      |
| false  | Identifiants incorrects.                   | Email ou mot de passe erroné   |
| false  | Impossible de supprimer le seul admin.     | Protection système             |
| false  | Cet email est déjà utilisé.               | Doublon email                  |

---

## 4. Application Python (prête à l'emploi)

### Prérequis

```bash
pip install zeep
```

### Fichier `client_app.py`

```python
#!/usr/bin/env python3
"""
Application cliente - Gestion des utilisateurs de Le•Quotidien
Nécessite : pip install zeep
"""

import json
import getpass
import sys
from zeep import Client, Settings

# ─── CONFIGURATION ───────────────────────────────────────────────────────────
WSDL_URL    = "http://localhost/newsite/public/soap"
API_TOKEN   = "REMPLACER_PAR_VOTRE_TOKEN_API"
# ─────────────────────────────────────────────────────────────────────────────


def connexion_soap():
    settings = Settings(strict=False, xml_huge_tree=True)
    try:
        return Client(WSDL_URL, settings=settings)
    except Exception as e:
        print(f"[ERREUR] Impossible de se connecter au service : {e}")
        print(f"Vérifiez que le serveur est accessible à : {WSDL_URL}")
        sys.exit(1)


def authentifier(client, email, password):
    res = client.service.authentifierUtilisateur(email=email, password=password)
    return res


def lister(client):
    res = client.service.listerUtilisateurs(token=API_TOKEN)
    if not res.succes:
        print(f"  Erreur : {res.message}")
        return
    users = json.loads(res.data)
    if not users:
        print("  Aucun utilisateur trouvé.")
        return
    print(f"\n  {'ID':<5} {'Nom':<25} {'Email':<30} {'Rôle':<15} {'Actif'}")
    print("  " + "─" * 80)
    for u in users:
        actif = "✓" if u["actif"] else "✗"
        print(f"  {u['id']:<5} {u['nom']:<25} {u['email']:<30} {u['role']:<15} {actif}")


def ajouter(client):
    print("\n  — Nouvel utilisateur —")
    nom      = input("  Nom complet    : ").strip()
    email    = input("  Email          : ").strip()
    password = getpass.getpass("  Mot de passe   : ")
    print("  Rôles : editeur | administrateur")
    role     = input("  Rôle           : ").strip()

    res = client.service.ajouterUtilisateur(
        token=API_TOKEN, nom=nom, email=email, password=password, role=role
    )
    print(f"  {'✓' if res.succes else '✗'}  {res.message}")


def modifier(client):
    print("\n  — Modifier un utilisateur —")
    id_user  = int(input("  ID utilisateur : ").strip())
    nom      = input("  Nouveau nom    : ").strip()
    email    = input("  Nouvel email   : ").strip()
    print("  Rôles : editeur | administrateur")
    role     = input("  Nouveau rôle   : ").strip()

    res = client.service.modifierUtilisateur(
        token=API_TOKEN, id=id_user, nom=nom, email=email, role=role
    )
    print(f"  {'✓' if res.succes else '✗'}  {res.message}")


def supprimer(client):
    print("\n  — Supprimer un utilisateur —")
    id_user = int(input("  ID utilisateur : ").strip())
    confirm = input(f"  Confirmer la suppression de l'utilisateur #{id_user} ? (oui/non) : ").strip()
    if confirm.lower() != "oui":
        print("  Annulé.")
        return

    res = client.service.supprimerUtilisateur(token=API_TOKEN, id=id_user)
    print(f"  {'✓' if res.succes else '✗'}  {res.message}")


def menu(client, user_info):
    options = {
        "1": ("Lister les utilisateurs",   lister),
        "2": ("Ajouter un utilisateur",     ajouter),
        "3": ("Modifier un utilisateur",    modifier),
        "4": ("Supprimer un utilisateur",   supprimer),
        "5": ("Quitter",                    None),
    }

    print(f"\n  Connecté en tant que : {user_info['nom']} ({user_info['role']})")
    print("  " + "═" * 45)

    while True:
        print("\n  MENU PRINCIPAL")
        for key, (label, _) in options.items():
            print(f"  [{key}] {label}")

        choix = input("\n  Votre choix : ").strip()

        if choix == "5":
            print("  Au revoir.")
            break
        elif choix in options:
            print()
            options[choix][1](client)
        else:
            print("  Choix invalide.")


def main():
    print("═" * 50)
    print("  Le•Quotidien — Gestion des utilisateurs")
    print("═" * 50)

    email    = input("  Email    : ").strip()
    password = getpass.getpass("  Mot de passe : ")

    client = connexion_soap()

    print("\n  Authentification en cours...")
    res = authentifier(client, email, password)

    if not res.succes:
        print(f"\n  ✗ Accès refusé : {res.message}")
        sys.exit(1)

    user_info = json.loads(res.data)

    if user_info["role"] != "administrateur":
        print(f"\n  ✗ Accès refusé : vous êtes '{user_info['role']}', droits administrateur requis.")
        sys.exit(1)

    print(f"\n  ✓ Authentification réussie. Bienvenue, {user_info['nom']} !")
    menu(client, user_info)


if __name__ == "__main__":
    main()
```

### Utilisation

```bash
# Installer la dépendance
pip install zeep

# Éditer le fichier : remplacer REMPLACER_PAR_VOTRE_TOKEN_API
# par le token fourni par l'administrateur

# Lancer l'application
python client_app.py
```

---

## 5. Application Java (guide d'implémentation)

### Dépendance Maven

```xml
<dependency>
    <groupId>com.sun.xml.ws</groupId>
    <artifactId>jaxws-rt</artifactId>
    <version>4.0.2</version>
</dependency>
```

### Générer le stub à partir du WSDL

```bash
wsimport -keep -verbose http://<SERVEUR>/soap
```

Cela génère automatiquement les classes Java `UsersService`, `UsersPortType`, etc.

### Exemple d'utilisation Java

```java
import java.util.Scanner;
import java.util.Console;

public class ClientApp {
    private static final String TOKEN = "REMPLACER_PAR_VOTRE_TOKEN_API";

    public static void main(String[] args) {
        Scanner scanner = new Scanner(System.in);
        Console console = System.console();

        System.out.println("=".repeat(50));
        System.out.println("  Le•Quotidien — Gestion des utilisateurs");
        System.out.println("=".repeat(50));

        System.out.print("  Email    : ");
        String email = scanner.nextLine().trim();

        System.out.print("  Mot de passe : ");
        String password = (console != null)
            ? new String(console.readPassword())
            : scanner.nextLine();

        // Connexion au service SOAP
        UsersService service = new UsersService();
        UsersPortType port   = service.getUsersPort();

        // Authentification
        ReponseData authResult = port.authentifierUtilisateur(email, password);

        if (!authResult.isSucces()) {
            System.out.println("Accès refusé : " + authResult.getMessage());
            return;
        }

        // Parser le JSON de retour (utiliser org.json ou Gson)
        // JSONObject user = new JSONObject(authResult.getData());
        // if (!user.getString("role").equals("administrateur")) { ... }

        // Afficher le menu et traiter les choix...
        afficherMenu(port, scanner);
    }

    private static void afficherMenu(UsersPortType port, Scanner scanner) {
        while (true) {
            System.out.println("\n  [1] Lister les utilisateurs");
            System.out.println("  [2] Ajouter un utilisateur");
            System.out.println("  [3] Modifier un utilisateur");
            System.out.println("  [4] Supprimer un utilisateur");
            System.out.println("  [5] Quitter");
            System.out.print("\n  Votre choix : ");

            String choix = scanner.nextLine().trim();

            switch (choix) {
                case "1" -> {
                    ReponseData res = port.listerUtilisateurs(TOKEN);
                    System.out.println(res.isSucces() ? res.getData() : "Erreur : " + res.getMessage());
                }
                case "2" -> {
                    // Lire nom, email, password, role...
                    // ReponseSimple res = port.ajouterUtilisateur(TOKEN, nom, email, password, role);
                }
                case "3" -> {
                    // Lire id, nom, email, role...
                    // ReponseSimple res = port.modifierUtilisateur(TOKEN, id, nom, email, role);
                }
                case "4" -> {
                    // Lire id...
                    // ReponseSimple res = port.supprimerUtilisateur(TOKEN, id);
                }
                case "5" -> { return; }
                default  -> System.out.println("  Choix invalide.");
            }
        }
    }
}
```

---

## 6. Informations à obtenir de l'administrateur

Avant de démarrer le développement, vous avez besoin de :

| Information          | Où la trouver                               |
|----------------------|---------------------------------------------|
| URL du serveur       | Communiquée par l'admin                     |
| Token API            | Généré depuis `/admin/tokens`               |
| Compte de test admin | `admin@news.com` / mot de passe communiqué  |

---

## 7. Flux applicatif attendu

```
Démarrage
   │
   ▼
Saisie email + mot de passe
   │
   ▼
Appel SOAP : authentifierUtilisateur(email, password)
   │
   ├─► succes = false  ──► "Accès refusé" + quitter
   │
   └─► succes = true
          │
          ├─► role ≠ "administrateur"  ──► "Droits insuffisants" + quitter
          │
          └─► role = "administrateur"
                 │
                 ▼
              Menu principal
              ┌─────────────────────────────────────┐
              │ [1] Lister   → listerUtilisateurs()  │
              │ [2] Ajouter  → ajouterUtilisateur()  │
              │ [3] Modifier → modifierUtilisateur() │
              │ [4] Supprimer→ supprimerUtilisateur()│
              │ [5] Quitter                           │
              └─────────────────────────────────────┘
```
