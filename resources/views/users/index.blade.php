<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>G-ERP | Usuarios</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
</head>
<body>
    @include('partials.header')
    <div class="volume-control">
        <img class="audio-control volume-icon" src="{{ asset('icons/audio.png') }}" alt="audio">
    </div>
    <main class="erp-users-container">
        <section class="users-section-top">
            <div class="users-section-top-tittle">
                <h3>Usuarios</h3>
            </div>
            <div>
                <button class="users-create-button" data-modal-target="modal-create">Crear</button>
            </div>
        </section>

        <section class="users-section-table">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Correo Electrónico</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Activo</th>
                        <th>Creado</th>
                        <th>Actualizado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    @php
                        $isSelf = (int) auth()->id() === (int) $user->id;
                    @endphp
                    <tr>
                        <td style="text-align: center;">
                            @if($user->image)
                                <a href="{{ route('user.image', basename($user->image)) }}" target="_blank" title="Ver fotografía del usuario" style="display: inline-flex; align-items: center; justify-content: center;">
                                    <img src="{{ asset('icons/picture.png') }}" alt="Ver foto" class="profile-image">
                                </a>
                            @else
                                <span style="color: #9ca3af; font-size: 1.5vh;">Sin foto</span>
                            @endif
                        </td>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span>{{ $user->role?->name ?? 'Sin rol' }}</span>
                        </td>
                        <td>
                            <span class="status-text-{{ $user->id }}">
                                {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" 
                                       class="user-status-toggle" 
                                       data-id="{{ $user->id }}"
                                       data-is-self="{{ $isSelf ? 'true' : 'false' }}"
                                       {{ $user->is_active ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>{{ $user->created_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                        <td>{{ $user->updated_at?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                        <td class="no-wrap">
                            <button class="users-table-button edit" 
                                    type="button" 
                                    data-modal-target="modal-edit"
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    data-email="{{ $user->email }}"
                                    data-role-id="{{ $user->role_id }}"
                                    data-url="{{ route('users.update', $user->id) }}"
                                    data-image="{{ $user->image }}">
                                Editar
                            </button>

                            @if($isSelf)
                                <button class="users-table-button delete" 
                                        type="button"
                                        data-modal-target="modal-alert"
                                        data-mode="info"
                                        data-title="Acción no permitida"
                                        data-message="No puedes eliminar tu propio usuario en sesión.">
                                    Eliminar
                                </button>
                            @else
                                <button class="users-table-button delete" 
                                        type="button"
                                        data-modal-target="modal-alert"
                                        data-action="{{ route('users.destroy', $user->id) }}"
                                        data-method="DELETE"
                                        data-title="Eliminar Usuario"
                                        data-message="¿Estás seguro de que deseas eliminar al usuario '{{ $user->name }}'?"
                                        data-btn-text="Eliminar"
                                        data-btn-class="btn-danger">
                                    Eliminar
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <div class="pagination-container">
            {{ $users->links('partials.pagination') }}
        </div>
    </main>

    @include('users.partials.modal-create')
    @include('users.partials.modal-edit')
    @include('partials.alert')

    @include('partials.footer')
    <script type="module" src="{{ asset('js/main.js') }}"></script> 
</body>
</html>