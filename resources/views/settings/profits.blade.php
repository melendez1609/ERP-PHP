<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>G-ERP | Margen de Ganancias</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
</head>
<body>
    @include('partials.header')
    <div class="volume-control">
        <img class="audio-control volume-icon" src="{{ asset('icons/audio.png') }}" alt="audio">
    </div>
    <main class="settings-container">
        <section class="settings-section-top">
            <div class="settings-section-top-tittle">
                <h3>Margen de Ganancias</h3>
            </div>
        </section>
        <section class="settings-section-table">
            <table class="settings-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th style="width: 280px; text-align: center;">Ganancia (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>Producto de Ejemplo 1</strong>
                            <small style="display: block; color: #666;">Cód: PROD-001</small>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                <input type="number" 
                                       name="profit_margin" 
                                       value="15.00" 
                                       step="0.01" 
                                       min="0" 
                                       style="width: 90px; text-align: center; padding: 6px; border: 1px solid #ccc; border-radius: 4px;">
                                <button type="button" class="settings-table-button edit" style="padding: 6px 12px; white-space: nowrap;">Aplicar</button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Producto de Ejemplo 2</strong>
                            <small style="display: block; color: #666;">Cód: PROD-002</small>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; justify-content: center; gap: 8px;">
                                <input type="number" 
                                       name="profit_margin" 
                                       value="20.00" 
                                       step="0.01" 
                                       min="0" 
                                       style="width: 90px; text-align: center; padding: 6px; border: 1px solid #ccc; border-radius: 4px;">
                                <button type="button" class="settings-table-button edit" style="padding: 6px 12px; white-space: nowrap;">Aplicar</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
        <div class="pagination-container">
            <!-- Paginación estática temporal -->
        </div>
    </main>

    @include('partials.alert')
    @include('partials.footer')
    <script type="module" src="{{ asset('js/main.js') }}"></script> 
</body>
</html>