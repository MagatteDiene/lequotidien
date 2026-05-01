<?php
/**
 * Script de test de l'API REST — Le•Quotidien
 *
 * Usage : php test_rest.php
 * Modifier TOKEN ci-dessous avec un token généré depuis /admin/tokens
 */

define('BASE_URL', 'http://localhost/newsite/public/api');
define('TOKEN',    'REMPLACER_PAR_VOTRE_TOKEN');

$passed = 0;
$failed = 0;

function test(string $label, string $url, int $expectedCode = 200): void
{
    global $passed, $failed;

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => ['X-Api-Token: ' . TOKEN],
        CURLOPT_TIMEOUT        => 10,
    ]);

    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);

    if ($err) {
        echo "[ERREUR RÉSEAU] {$label} — {$err}\n";
        $failed++;
        return;
    }

    $ok = ($code === $expectedCode);
    $icon = $ok ? '✓' : '✗';
    echo "[{$icon}] {$label} (HTTP {$code})\n";

    if (!$ok) {
        echo "    Attendu : HTTP {$expectedCode}\n";
        echo "    Réponse : " . substr($body, 0, 200) . "\n";
        $failed++;
    } else {
        $data = json_decode($body, true);
        if ($data) {
            $keys = implode(', ', array_keys($data));
            echo "    Clés réponse : {$keys}\n";
        }
        $passed++;
    }
}

echo "═══════════════════════════════════════════\n";
echo " Tests API REST — Le•Quotidien\n";
echo "═══════════════════════════════════════════\n\n";

// Test 1 : Sans token → 401
$ch = curl_init(BASE_URL . '/articles');
curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true]);
$body = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$icon = ($code === 401) ? '✓' : '✗';
echo "[{$icon}] Accès sans token → doit retourner 401 (obtenu: {$code})\n";
$code === 401 ? $passed++ : $failed++;

// Test 2 : Avec token invalide → 401
$ch = curl_init(BASE_URL . '/articles');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => ['X-Api-Token: token_bidon_12345'],
]);
$body = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$icon = ($code === 401) ? '✓' : '✗';
echo "[{$icon}] Token invalide → doit retourner 401 (obtenu: {$code})\n";
$code === 401 ? $passed++ : $failed++;

echo "\n";

// Test 3 : Liste des articles (JSON)
test('GET /api/articles (JSON)', BASE_URL . '/articles');

// Test 4 : Liste des articles (XML)
$ch = curl_init(BASE_URL . '/articles?format=xml');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => ['X-Api-Token: ' . TOKEN],
]);
$body = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
$isXml = (strpos($body, '<?xml') === 0 || strpos($body, '<articles') !== false);
$icon = ($code === 200 && $isXml) ? '✓' : '✗';
echo "[{$icon}] GET /api/articles?format=xml (XML valide : " . ($isXml ? 'oui' : 'non') . ")\n";
($code === 200 && $isXml) ? $passed++ : $failed++;

// Test 5 : Articles par catégories
test('GET /api/articles/categories', BASE_URL . '/articles/categories');

// Test 6 : Articles par catégorie (slug existant)
// Récupère d'abord les catégories pour trouver un slug valide
$ch = curl_init(BASE_URL . '/articles/categories');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => ['X-Api-Token: ' . TOKEN],
]);
$body = curl_exec($ch);
curl_close($ch);
$data = json_decode($body, true);
$slug = $data['categories'][0]['slug'] ?? null;

if ($slug) {
    test("GET /api/articles/categorie/{$slug}", BASE_URL . '/articles/categorie/' . $slug);
} else {
    echo "[!] Impossible de tester /categorie/{slug} : aucune catégorie trouvée\n";
}

echo "\n═══════════════════════════════════════════\n";
echo " Résultats : {$passed} réussis, {$failed} échoués\n";
echo "═══════════════════════════════════════════\n";
