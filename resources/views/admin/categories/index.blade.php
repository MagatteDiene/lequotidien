@extends('layouts.admin')

@section('page_title', 'Gestion des catégories')

@section('actions')
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">+ Nouvelle catégorie</a>
@endsection

@section('content')
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>Articles</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $cat)
                    <tr>
                        <td><strong>{{ $cat->nom }}</strong></td>
                        <td><code>{{ $cat->slug }}</code></td>
                        <td>{{ Str::limit($cat->description, 50) }}</td>
                        <td>{{ $cat->articles_count }}</td>
                        <td style="text-align: right; display: flex; gap: 10px; justify-content: flex-end;">
                            <a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn btn-secondary btn-sm">Modifier</a>
                            <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Supprimer cette catégorie ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
