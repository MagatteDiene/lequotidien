<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Categorie;
use Illuminate\Http\Request;
use SimpleXMLElement;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::publie()
            ->with(['categorie', 'auteur'])
            ->latest()
            ->get()
            ->map(fn($a) => $this->articleToArray($a))
            ->values();

        return $this->respond($request, ['articles' => $articles], 'articles');
    }

    public function parCategories(Request $request)
    {
        $categories = Categorie::with(['articlesPublies.auteur'])
            ->get()
            ->map(fn($c) => [
                'id'       => $c->id,
                'nom'      => $c->nom,
                'slug'     => $c->slug,
                'articles' => $c->articlesPublies->map(fn($a) => $this->articleToArray($a))->values(),
            ])
            ->values();

        return $this->respond($request, ['categories' => $categories], 'categories');
    }

    public function parCategorie(Request $request, string $slug)
    {
        $articles = Article::publie()
            ->whereHas('categorie', fn($q) => $q->where('slug', $slug))
            ->with(['categorie', 'auteur'])
            ->latest()
            ->get()
            ->map(fn($a) => $this->articleToArray($a))
            ->values();

        return $this->respond($request, ['categorie' => $slug, 'articles' => $articles], 'articles');
    }

    private function articleToArray(Article $article): array
    {
        return [
            'id'        => $article->id,
            'titre'     => $article->titre,
            'slug'      => $article->slug,
            'resume'    => $article->resume,
            'categorie' => $article->categorie?->nom,
            'auteur'    => $article->auteur?->name ?? 'Anonyme',
            'publie_le' => $article->created_at->format('Y-m-d H:i:s'),
        ];
    }

    private function respond(Request $request, array $data, string $xmlRoot)
    {
        $format = strtolower($request->query('format', 'json'));

        if ($format === 'xml') {
            $xml = new SimpleXMLElement("<{$xmlRoot}/>");
            $this->arrayToXml($data, $xml);
            return response($xml->asXML(), 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
        }

        return response()->json($data);
    }

    private function arrayToXml(array $data, SimpleXMLElement $xml): void
    {
        foreach ($data as $key => $value) {
            $tag = is_numeric($key) ? 'item' : preg_replace('/[^a-zA-Z0-9_\-]/', '_', $key);
            if (is_array($value) || is_object($value)) {
                $child = $xml->addChild($tag);
                $this->arrayToXml((array) $value, $child);
            } else {
                $xml->addChild($tag, htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8'));
            }
        }
    }
}
