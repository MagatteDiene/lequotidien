@extends('layouts.admin')

@section('page_title', 'Gestion des articles')

@section('actions')
    <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">+ Nouvel article</a>
@endsection

@section('content')
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Auteur</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articles as $article)
                    <tr>
                        <td><strong>{{ $article->titre }}</strong></td>
                        <td>{{ $article->categorie->nom }}</td>
                        <td>{{ $article->auteur->name }}</td>
                        <td>{{ $article->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($article->publie)
                                <span class="badge badge-on">En ligne</span>
                            @else
                                <span class="badge badge-off">Brouillon</span>
                            @endif
                        </td>
                        <td style="text-align: right; display: flex; gap: 10px; justify-content: flex-end;">
                            <a href="{{ route('admin.articles.edit', $article->id) }}" class="btn btn-secondary btn-sm">Modifier</a>
                            <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" onsubmit="return confirm('Supprimer cet article ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $articles->links() }}
        </div>
    </div>
@endsection
