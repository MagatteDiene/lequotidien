@extends('layouts.admin')

@section('page_title', 'Gestion des utilisateurs')

@section('actions')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Nouvel utilisateur
    </a>
@endsection

@section('content')
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Rôle</th>
                    <th>Articles</th>
                    <th>Statut</th>
                    <th>Depuis le</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    @php
                        $colors = ['#ff5722','#2196F3','#4CAF50','#9C27B0','#FF9800','#00BCD4'];
                        $bg = $colors[abs(crc32($user->name)) % count($colors)];
                        $initials = collect(explode(' ', $user->name))->map(fn($w) => strtoupper($w[0] ?? ''))->filter()->take(2)->join('');
                    @endphp
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div class="avatar-initials" style="width: 36px; height: 36px; background: {{ $bg }}; font-size: 0.75rem; border-radius: 10px;">{{ $initials }}</div>
                                <div>
                                    <div style="font-weight: 700; font-size: 0.9rem;">
                                        {{ $user->name }}
                                        @if($user->id === auth()->id())
                                            <span style="font-size: 0.7rem; color: var(--primary); font-weight: 800; margin-left: 6px;">(vous)</span>
                                        @endif
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $user->role }}">{{ $user->role }}</span>
                        </td>
                        <td style="font-size: 0.85rem; font-weight: 700; color: var(--text-main);">
                            {{ $user->articles_count }}
                        </td>
                        <td>
                            @if($user->actif)
                                <span class="badge badge-on">Actif</span>
                            @else
                                <span class="badge badge-off">Inactif</span>
                            @endif
                        </td>
                        <td style="font-size: 0.82rem; color: var(--text-muted);">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-secondary btn-sm">Modifier</a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                          onsubmit="return confirm('Supprimer {{ addslashes($user->name) }} ? Ses articles seront conservés.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                    </form>
                                @endif
                            </div>
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
