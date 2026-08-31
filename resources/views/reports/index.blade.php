<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G-ERP | Reporte del Sistema</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
</head>
<body>
    @include('partials.header')
    <div class="volume-control">
        <img class="audio-control volume-icon" src="{{ asset('icons/audio.png') }}" alt="audio">
    </div>
    <main class="erp-reports-container">
        <section class="reports-section-top">
            <div class="reports-section-top-tittle">
                <h3>Reporte del Sistema</h3>
            </div>
        </section>

        <section class="reports-section-table">
            <table class="reports-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Acción</th>
                        <th>Detalles</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $log->user?->name ?? 'Sistema' }}</td>
                        <td><strong>{{ $log->action }}</strong></td>
                        <td>
                            <pre style="margin: 0; font-size: 1.4vh; background: #f8fafc; padding: 5px; border-radius: 4px; max-width: 400px; overflow-x: auto;">{{ json_encode($log->details, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </td>
                        <td>{{ $log->ip_address }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px; color: #666; background-color: #fff;">
                            No hay registros de actividad aún.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <div class="pagination-container">
            {{ $logs->links('partials.pagination') }}
        </div>
    </main>

    @include('partials.footer')
    <script type="module" src="{{ asset('js/main.js') }}"></script>
</body>
</html>