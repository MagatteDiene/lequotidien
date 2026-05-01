<?php
/**
 * Script de test du service SOAP — Le•Quotidien
 *
 * Usage : php test_soap.php
 * Modifier TOKEN, ADMIN_EMAIL, ADMIN_PASSWORD ci-dessous
 */

define('SOAP_URL',       'http://localhost/newsite/public/soap');
define('TOKEN',          'REMPLACER_PAR_VOTRE_TOKEN');
define('ADMIN_EMAIL',    'admin@news.com');
define('ADMIN_PASSWORD', '123');

$passed = 0;
$failed = 0;
$client = null;

function ok(string $label): void  { global $passed; echo "[✓] {$label}\n"; $passed++; }
function ko(string $label, string $detail = ''): void {
    global $failed;
    echo "[✗] {$label}\n";
    if ($detail) echo "    → {$detail}\n";
    $failed++;
}

echo "═══════════════════════════════════════════\n";
echo " Tests Service SOAP — Le•Quotidien\n";
echo "═══════════════════════════════════════════\n\n";

// Vérifier que l'extension soap est chargée
if (!extension_loaded('soap')) {
    echo "[FATAL] L'extension PHP 'soap' n'est pas activée.\n";
    echo "        Activez extension=soap dans votre php.ini\n";
    exit(1);
}

// Test 1 : Connexion et récupération WSDL
echo "── Connexion ────────────────────────────\n";
try {
    $client = new SoapClient(SOAP_URL, [
        'cache_wsdl' => WSDL_CACHE_NONE,
        'encoding'   => 'UTF-8',
        'trace'      => true,
    ]);
    ok('Connexion au WSDL réussie (' . SOAP_URL . ')');
} catch (Exception $e) {
    ko('Impossible de charger le WSDL', $e->getMessage());
    echo "\nArrêt des tests (impossible de se connecter).\n";
    exit(1);
}

// Test 2 : Méthodes disponibles
$methods = $client->__getFunctions();
$expected = ['listerUtilisateurs', 'ajouterUtilisateur', 'modifierUtilisateur', 'supprimerUtilisateur', 'authentifierUtilisateur'];
echo "\n── Méthodes WSDL ────────────────────────\n";
foreach ($expected as $method) {
    $found = false;
    foreach ($methods as $m) {
        if (str_contains($m, $method)) { $found = true; break; }
    }
    $found ? ok("Méthode '{$method}' présente") : ko("Méthode '{$method}' MANQUANTE");
}

// Test 3 : Authentification
echo "\n── Authentification ─────────────────────\n";

$res = $client->authentifierUtilisateur(ADMIN_EMAIL, ADMIN_PASSWORD);
if ($res->succes) {
    $user = json_decode($res->data, true);
    ok("authentifierUtilisateur — admin@news.com → {$user['role']}");
    if ($user['role'] === 'administrateur') {
        ok("Rôle administrateur confirmé");
    } else {
        ko("Rôle inattendu : {$user['role']} (attendu: administrateur)");
    }
} else {
    ko("authentifierUtilisateur échoué", $res->message);
}

$res2 = $client->authentifierUtilisateur('faux@email.com', 'mauvais');
if (!$res2->succes) {
    ok("Mauvais identifiants → succes=false ({$res2->message})");
} else {
    ko("Mauvais identifiants auraient dû échouer");
}

// Test 4 : Token invalide
echo "\n── Sécurité token ───────────────────────\n";
$res = $client->listerUtilisateurs('token_bidon');
if (!$res->succes) {
    ok("Token invalide → rejeté ({$res->message})");
} else {
    ko("Token bidon accepté — faille de sécurité !");
}

// Test 5 : Opérations CRUD
echo "\n── CRUD utilisateurs ────────────────────\n";

// Lister
$res = $client->listerUtilisateurs(TOKEN);
if ($res->succes) {
    $users = json_decode($res->data, true);
    ok("listerUtilisateurs → " . count($users) . " utilisateur(s)");
} else {
    ko("listerUtilisateurs échoué", $res->message);
}

// Ajouter
$testEmail = 'test_' . time() . '@test.com';
$res = $client->ajouterUtilisateur(TOKEN, 'Test Utilisateur', $testEmail, 'Test@1234', 'editeur');
if ($res->succes) {
    ok("ajouterUtilisateur → {$res->message}");
    // Extraire l'ID créé pour les tests suivants
    preg_match('/ID (\d+)/', $res->message, $matches);
    $newId = $matches[1] ?? null;
} else {
    ko("ajouterUtilisateur échoué", $res->message);
    $newId = null;
}

// Modifier
if ($newId) {
    $res = $client->modifierUtilisateur(TOKEN, (int)$newId, 'Test Modifié', $testEmail, 'editeur');
    $res->succes
        ? ok("modifierUtilisateur (ID {$newId}) → {$res->message}")
        : ko("modifierUtilisateur échoué", $res->message);
}

// Supprimer
if ($newId) {
    $res = $client->supprimerUtilisateur(TOKEN, (int)$newId);
    $res->succes
        ? ok("supprimerUtilisateur (ID {$newId}) → {$res->message}")
        : ko("supprimerUtilisateur échoué", $res->message);
}

// Rôle invalide
$res = $client->ajouterUtilisateur(TOKEN, 'X', 'x@x.com', '12345678', 'role_inexistant');
!$res->succes
    ? ok("Rôle invalide → rejeté ({$res->message})")
    : ko("Rôle invalide accepté — bug !");

echo "\n═══════════════════════════════════════════\n";
echo " Résultats : {$passed} réussis, {$failed} échoués\n";
echo "═══════════════════════════════════════════\n";
