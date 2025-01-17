
<h2 class="text-center mt-5">Le Tue Prenotazioni</h2>
<p class="text-center">Ciao <?php echo htmlspecialchars($_SESSION["nome"]); ?>, ecco le tue prenotazioni:</p>
<div class="row">
    <?php foreach ($reservations as $prenotazione): ?>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Tavolo n. <?= htmlspecialchars($prenotazione['numero_tavolo']); ?></h5>
                    <p class="card-text">
                        <strong>Data e Ora:</strong> <?= htmlspecialchars($prenotazione['data_ora']); ?><br>
                        <strong>Numero di Persone:</strong> <?= htmlspecialchars($prenotazione['numero_persone']); ?><br>
                        <strong>Status:</strong> <?= htmlspecialchars(ucfirst($prenotazione['status'])); ?>
                    </p>
                    <form method="post" action="modifica_prenotazione.php">
                        <input type="hidden" name="prenotazione_id" value="<?= htmlspecialchars($prenotazione['id']); ?>">
                        <input type="hidden" name="numero_tavolo" value="<?= htmlspecialchars($prenotazione['numero_tavolo']); ?>">
                        <input type="hidden" name="data_ora" value="<?= htmlspecialchars($prenotazione['data_ora']); ?>">
                        <input type="hidden" name="numero_persone" value="<?= htmlspecialchars($prenotazione['numero_persone']); ?>">
                        <button type="submit" name="modifica" class="btn btn-primary">Modifica</button>
                        <button type="submit" name="annulla" class="btn btn-danger">Annulla</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>