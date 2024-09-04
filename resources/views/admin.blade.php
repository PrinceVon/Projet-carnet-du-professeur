@extends('layouts.user_type.auth')

@section('content')
    <div class="card">
        <h1>Liste des Utilisateurs</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>État</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->is_active ? 'Actif' : 'Inactif' }}</td>
                    <td>
                        <form action="{{ route('users.toggleActive', $user) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit">
                                {{ $user->is_active ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
@endsection
