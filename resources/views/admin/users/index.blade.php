@extends('layouts.admin')

@section('page_title', 'Gestion des utilisateurs')

@section('actions')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Nouvel utilisateur</a>
@endsection

@section('content')
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th>Inscrit le</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge badge-{{ $user->role }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td>
                            @if($user->actif)
                                <span class="badge badge-on">Actif</span>
                            @else
                                <span class="badge badge-off">Inactif</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td style="text-align: right; display: flex; gap: 10px; justify-content: flex-end;">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-secondary btn-sm">Modifier</a>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $users->links() }}
        </div>
    </div>
@endsection
