<h2>Nueva Cotización</h2>

<form action="/cotizaciones" method="post">
    <label for="IdCliente">Cliente:</label>
    <select name="IdCliente" id="IdCliente" required>
        <option value="">-- Seleccione un cliente --</option>
        <?php foreach ($clientes as $c): ?>
            <option value="<?= $c['IdCliente'] ?>">
                <?= htmlspecialchars($c['Nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <br><br>

    <label for="Serie">Serie:</label>
    <input type="text" name="Serie" id="Serie" value="COT" required>

    <br><br>

    <label for="Folio">Folio:</label>
    <input type="number" name="Folio" id="Folio" required>

    <br><br>

    <label for="Fecha">Fecha:</label>
    <input type="date" name="Fecha" id="Fecha" value="<?= date('Y-m-d') ?>" required>

    <br><br>

    <button type="submit">Guardar</button>
</form>
