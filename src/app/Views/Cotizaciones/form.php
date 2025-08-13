<?php
require_once __DIR__ . '/../../../Core/Header.php';
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/css/Global.css">
<link rel="stylesheet" href="/css/Cotizaciones.css">


<div id="info-bar" class="info-bar">
    <span>
        ⛽ <strong>Diésel:</strong>
        <span id="diesel-price">$--</span>
        <span id="diesel-trend"></span>
    </span>
    <span>
        💵 <strong>Dólar (MXN):</strong>
        <span id="usd-mxn-rate">--</span>
        <span id="usd-trend"></span>
    </span>
</div>


<div class="dashboard">
    <section class="card">
        <h3>Nueva Cotización</h3>
        <form action="/fleet/cotizaciones" method="post">
        <label for="IdCliente">Cliente:</label>
            <div class="input-with-link">
                <select name="IdCliente" id="IdCliente" required>
                    <option value="">-- Seleccione un cliente --</option>
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?= $c['IdCliente'] ?>">
                            <?= htmlspecialchars($c['Nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <span class="add-link" id="addCliente">Agregar</span>
            </div>

            <label for="idUnidad">Unidad:</label>
            <div class="input-with-link">
                <select name="idUnidad" id="idUnidad" required>
                    <option value="">-- Seleccione una Unidad --</option>
                    <?php foreach ($unidades as $u): ?>
                        <option value="<?= $u['idUnidad'] ?>">
                            <?= htmlspecialchars($u['Nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="add-link" id="addUnidad">Agregar</span>
            </div>

            <label for="idRango">Rango:</label>
            <div class="input-with-link">
                <select name="idRango" id="idRango" required>
                    <option value="">-- Seleccione un Rango --</option>
                </select>
                <span class="add-link" id="addRango">Agregar</span>
            </div>

            <label for="Kilometros">Kilómetros:</label>
            <div class="input-with-link">
                <input type="number" name="Kilometros" id="Kilometros" min="0" step="0.01" placeholder="0.00" required>
                <span class="add-link" id="CalculaKM">Mapa</span>
            </div>

            <label for="Costo">Costo:</label>
            <input type="text" name="Costo" id="Costo" value="" readonly>

            <label for="Total">Total:</label>
            <input type="text" name="Total" id="Total" value="" readonly>

            <button type="submit">Guardar</button>
            <br>
        </form>
    </section>

    <section class="col-right">
        <div class="card" style="position: relative;">
            <h3>Buscar Cotización</h3>
            <input type="text" id="buscarCotizacion" placeholder="Buscar por folio / cliente">
            <div id="resultadosBusqueda" class="dropdown"></div>
        </div>

        <div class="card" id="detalleCotizacionCard">
            <h3>Cotización #</h3>
            <div id="detalleCotizacion"></div>
        </div>
    </section>
</div>

<!-- NOTIFICACIONES-->

<div id="notification" class="notification">
    <span class="notification-icon">✔</span>
    <span id="notification-message"></span>
</div>

<!-- MODAL GENÉRICO -->
<div id="appleModal" class="apple-modal">
    <div class="apple-modal-content">
        <span class="apple-modal-close">&times;</span>
        <h3 id="appleModalTitle">Título del Modal</h3>

        <form id="appleModalForm">
            <div class="form-group">
                <label for="modalInput">Nombre:</label>
                <input type="text" id="modalInput" name="modalInput" required>
            </div>

            <div class="modal-buttons">
                <button type="button" class="cancel-btn">Cancelar</button>
                <button type="submit" class="save-btn">Guardar</button>
            </div>
        </form>
    </div>
</div>


<script src="/js/Cotizaciones.js"></script>
<script src="/js/Global.js"></script>


